<?php
//voucher type
class PredefinedVoucher extends CI_Controller
{

  public function __construct() {
    parent::__construct();
    $this->load->model('voucher/predefined_Model');
    $this->load->model('logs/ActivityLog_Model');
    $this->load->model('logs/VoucherLog_Model');
    $this->load->model('App_logger_model');

    if ($this->session->userdata('is_logged_in') == false) {
      redirect();
    }

    //CSRF PROTECTION\\
    $this->global_data['csrf'] = [
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
    ];
    //CSRF PROTECTION\\

    $this->global_data['UserId']      = $this->session->userdata('UserId');
    $this->global_data['Fullname']    = $this->session->userdata('Fullname');
    $this->global_data['Role']        = $this->session->userdata('Role');
    $this->global_data['AppType']     = 2;
    $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']   = $this->global_data['UserId'];
    $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);
  }

  function predefinedlist()
  {
    $data = array_merge($this->global_data);

    $data['header']               = $this->load->view('templates/main-header',"",true);
    $data['topbar']               = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']              = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']               = $this->load->view('templates/main-footer',"",true);
    $data['bottom']               = $this->load->view('templates/main-bottom',"",true);

    $this->load->view('voucher/predefinedVoucher', $data);
  }

  public function predefinedlisting(){
    $data  = array_merge($this->global_data);

    $post  = $this->input->post();
    $list  = $this->predefined_Model->get_datatables($post);
    $table = array();
    $no    = $post['start'];
    $i    = 1;

    foreach ($list as $field)
    {
      if($field->StatusId == 1)
      {
        $check = 'checked';
      }elseif ($field->StatusId == 2) {
        $check = '';
      }elseif ($field->StatusId == 3) {
        $check = 'disabled';
      }

      $no++;

      $row   = array();
      $row[] = $field->VoucherShortName;
      $row[] = $field->VoucherName;
      $row[] = $field->VoucherValue;
      $row[] = $field->ActivateDate;
      $row[] = $field->InactivateDate;

      $statuspermission     = $this->RolePermission_Model->submenu_master(7);

      if ($field->StatusId) {
        if ($statuspermission->Update == 1) {

          $switch= '<label class="switch" >
              <input type="checkbox" class="switch-input is-valid toggle" id="toggle_'.$i.'"  data-num="'.$i.'"  value="'.$field->StatusId.'" '.$check.'  />
              <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
              </span>
          </label>';

        }else {
          $switch= '';
        }
      }

      $row[] = '<td id="status">
                  <input type="hidden" id="vouchershortname_'.$i.'" name="voucherstatus" value="'.$field->VoucherShortName.'"/>
                  <input type="hidden" id="vouchertypeid_'.$i.'" name="voucherstatus" value="'.$field->VoucherTypeId.'"/>

                  '.$switch.'

                    <span id="showstatusname_'.$i.'">
                      <span class="badge bg-label-'.$field->StatusColor.'"  style="margin-left:35px">'.$field->StatusName.'</span>
                    </span>
                </td>';

      $row[] = '<a href="'.base_url().'voucher/Voucher/editvoucher/'.$field->VoucherTypeId.'">
                  <div class="d-inline-block- text-nowrap">
                    <button class="btn btn-sm btn-icon">
                      <i class="bx bx-edit"></i>
                    </button>
                  </div>
                </a>';
      $table[] = $row;

      $i++;
    }

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->predefined_Model->count_all($post),
      "recordsFiltered"     => $this->predefined_Model->count_filtered($post),
      "data"                => $table,
      $data['csrf']['name'] => $data['csrf']['hash']
    );
    echo json_encode($output);
  }


  function get_status()
	{
		$data   = array_merge($this->global_data);
    $get    = $this->input->post();
    $status = $get['status'];
    $type   = $get['vouchertypeId'];

    $data['IpAddress']        = $this->input->ip_address();
    $data['OperatingSystem']  = $this->agent->platform();
    $data['Browser']          = $this->agent->browser();

		$status            = $this->input->post('status');
    $vouchertypeId     = $this->input->post('vouchertypeId');
    $vouchertype       = $this->input->post('vouchershortname');

    $data['AppLoggerId'] = $get['loggerid'];

     if ($this->App_logger_model->update_app_logger_data($data))
     {
       if ($this->predefined_Model->updateStatus($status, $vouchertypeId, $vouchertype))
       {
         $currentstatus = $this->predefined_Model->currentstatus($get['status']);
         $type          = $this->predefined_Model->get_type($get['vouchertypeId']);

         $status     = true;
         $response   = "Voucher Type Status has been updated.";
         $errorcode  = 200;
         $actmsg     = " update Voucher Type Status to " .$currentstatus. " for ".$type;
       } else {
         $status     = false;
         $response   = [
           "type"    => "authentication",
           "error"   => array('error' => 'Something went wrong. Please try again later.'),
         ];
         $errorcode  = 500;
         $actmsg     = " is trying to update Voucher Type Status for ".$type.". Failed.";
       }
     }
     else
     {
       $status    = false;
       $response  = [
         "type"   => "authentication",
         "error"  => array('error' => 'Something went wrong. Please try again later.'),
       ];
       $errorcode = 500;
       $actmsg    = " is trying to update Voucher Type Status for ".$type.". Applogger update failed.";
     }
     $act = [
       'UserId'           => $data['UserId'],
       'ActivityTypeId'   => 14,
       'ActivityDetails'  => $data['Fullname'].$actmsg,
       'ActiveDate'       => date('Y-m-d H:i:s'),
       'IpAddress'        => $data['IpAddress'],
       'OperatingSystem'  => $data['OperatingSystem'],
       'Browser'          => $data['Browser'],
     ];

     $this->ActivityLog_Model->insert_activity($act);

		$result['token'] = $data['csrf']['hash'];
		echo json_encode($result);
	}
}
 ?>
