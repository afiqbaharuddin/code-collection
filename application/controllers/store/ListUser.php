<?php

  class ListUser extends CI_Controller
  {

    public function __construct() {
  		parent::__construct();
      $this->load->model('store/ListUser_model');
      $this->load->model('logs/ActivityLog_Model');
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

      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['CreatedBy']   = $this->global_data['UserId'];
    	$this->global_data['UpdatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['UpdatedBy']   = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
  	}

    function listuser() //in store user page
    {
      $data = array_merge($this->global_data);

      $data['header']   = $this->load->view('templates/main-header',"",true);
      $data['topbar']   = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']  = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']   = $this->load->view('templates/main-footer',"",true);
      $data['bottom']   = $this->load->view('templates/main-bottom',"",true);

      $data['StoreId']  = $this->uri->segment(4); //1

      $data['details']  = $this->ListUser_model->get_storedetails($data['StoreId']);

      $this->load->view('store/list_user', $data);
    }

    public function listing(){
      $data = array_merge($this->global_data);

      $post = $this->input->post();
      $id = $this->uri->segment(4); //bawak specific store  2

      $list  = $this->ListUser_model->get_datatables($post,$id);
      $table = array();
      $no    = $post['start'];

      $x     = 1;

      foreach ($list as $field) {

        if($field->userStatusStore == 1)
        {
          $check = 'checked';
        } else {
          $check = '';
        }

        $no++;

        $row   = array();
  			// $row[] = $no;
        $row[] = $field->StaffId;
  			$row[] = $field->Fullname;
  			$row[] = $field->Role;

        $row[] = '<span id="showuserstatus_'.$x.'">
                    <span class="badge bg-label-'.$field->StatusColor.'">'.$field->StatusName.'</span>
                  </span>';

        $row[] = '<td>
                    <input type="hidden" id="updateuserstatus_'.$x.'" name="updateuserstatus" value="'.$field->UserStoreId.'"/>
                    <label class="switch" >
                      <input type="checkbox" class="switch-input is-valid toggle" id="toggle_'.$x.'" data-num="'.$x.'" value="'.$field->userStatusStore.'" '.$check.'/>
                        <span class="switch-toggle-slider">
                          <span class="switch-on"></span>
                          <span class="switch-off"></span>
                        </span>
                    </label>
                  </td>';

        $table[] = $row;

        $x++;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->ListUser_model->count_all($post,$id),
        "recordsFiltered"     => $this->ListUser_model->count_filtered($post,$id),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function get_userstatus()   {

      $data   = array_merge($this->global_data);
      $get    = $this->input->post();
      $userid = $get['userstoreId'];
      $status = $get['status'];

      $data['IpAddress']        = $this->input->ip_address();
      $data['OperatingSystem']  = $this->agent->platform();
      $data['Browser']          = $this->agent->browser();

      $status      = $this->input->post('status');
      $userstoreId = $this->input->post('userstoreId');

      $data['AppLoggerId'] = $get['loggerid'];

      if ($this->App_logger_model->update_app_logger_data($data))
      {
        $update = $this->ListUser_model->update_userStatus($status, $userstoreId);
        $userid = $this->ListUser_model->get_userid($userstoreId);

        if ($update != null)
        {
          $this->ListUser_model->update_statusUser($status, $userstoreId, $userid);

          $username       = $this->ListUser_model->get_username($get['userstoreId']);
          $currentstatus  = $this->ListUser_model->currentstatus($get['status']);

          $status     = true;
          $response   = "In Store Users has been updated.";
          $errorcode  = 200;
          $actmsg     = " update In Store Users Status to " .$currentstatus. " for " .$username;
        } else {
          $status     = false;
          $response   = [
            "type"    => "authentication",
            "error"   => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode  = 500;
          $actmsg     = " is trying to update In Store Users ID ".$username.". Failed.";
        }
      } else
      {
        $status    = false;
        $response  = [
          "type"   => "authentication",
          "error"  => array('error' => 'Something went wrong. Please try again later.'),
        ];
        $errorcode = 500;
        $actmsg    = " is trying to update In Store Users ID ".$userstoreId.". Applogger update failed.";
      }
      $act = [
        'UserId'           => $data['UserId'],
        'ActivityTypeId'   => 16,
        'ActivityDetails'  => $data['Fullname'].$actmsg,
        'ActiveDate'       => date('Y-m-d h:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);

      $result['token']  = $data['csrf']['hash'];
      echo json_encode($result);
    }
  }
 ?>
