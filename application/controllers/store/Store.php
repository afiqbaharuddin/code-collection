<?php

  class Store extends CI_Controller
  {

    function __construct()
    {
      parent::__construct();
      $this->load->model('user/User_Model');
      $this->load->model('store/Store_Model');
      $this->load->model('App_logger_model');
      $this->load->model('logs/ActivityLog_Model');

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
    $this->global_data['AppType']     = 2;
    $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() ); //for edit part
    $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);
    }

    //storelist page view
    function StoreList()
    {
      $data = array_merge($this->global_data);

      $data['header']        = $this->load->view('templates/main-header',"",true);
      $data['topbar']        = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']       = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']        = $this->load->view('templates/main-footer',"",true);
      $data['bottom']        = $this->load->view('templates/main-bottom',"",true);

      $data['StoreId']       = $this->uri->segment(4);
      $storeid               = $this->uri->segment(4);

      $data['carddb']        = $this->Store_Model->get_card()->result(); //fetch card from db (dropdown)
      $data['storestatusdb'] = $this->Store_Model->get_status()->result(); //fetch status from db(dropdown)

      $data['totaluser'] = $this->Store_Model->get_total($data['StoreId']);

      //instoreusers page card
      $data['totaluserall'] = $this->Store_Model->get_totalall($data['StoreId']);

      // $data['userTable']=$this->Store_Model->showStore();
      $data['openStore'] = $this->Store_Model->get_storeOpen(); //cards open
      $data['closeStore'] = $this->Store_Model->get_storeClose(); //cards open

      $this->load->view('store/list_store', $data);
    }

      // store list data table
      public function listing(){
        $data = array_merge($this->global_data);

        $post = $this->input->post();
        $id = $this->uri->segment(4);

        $permission = $this->Store_Model->get_permission($this->session->userdata('UserId'));
        $userid     = $this->Store_Model->get_userStore($this->session->userdata('UserId'));
        $list       = $this->Store_Model->get_datatables($post,$id, $userid->StoreId, $permission);
        $table      = array();

        $no    = $post['start'];

        foreach ($list as $field) {

          $no++;
          $getuserid = $this->User_Model->getuserid($field->CreatedBy);

          if (isset($getuserid)) {
            $fullname = $getuserid->Fullname;
          }else {
            $fullname = '';
          }

          if ($permission->UserRoleId == 1 || $permission->UserId == 1|| $permission->StoreId == 0) {
            $button = '<div class="d-inline-block">
                      <a href="'.base_url().'store/ListUser/listuser/'.$field->StoreId. '">
                        <button class="btn btn-sm btn-icon">
                          <i class="bx bxs-group"></i>
                        </button>
                        </a>

                        <a href="'.base_url().'store/EditStore/edit/'.$field->StoreId. '">
                          <button class="btn btn-sm btn-icon">
                            <i class="bx bxs-edit"></i>
                          </button>
                        </a>
                      </div>';
          }else {

            if ($userid->StoreId == $field->StoreId) {
              $button = '<div class="d-inline-block">
                            <a href="'.base_url().'store/ListUser/listuser/'.$field->StoreId. '">
                              <button class="btn btn-sm btn-icon">
                                <i class="bx bxs-group"></i>
                              </button>
                            </a>
                          </div>

                          <a href="'.base_url().'store/EditStore/edit/'.$field->StoreId. '">
                            <button class="btn btn-sm btn-icon">
                              <i class="bx bxs-edit"></i>
                            </button>
                          </a>
                        </div>';
            }else {
              $button = '';
            }
          }
            $row   = array();

            $row[] = $field->StoreCode;
            $row[] = $field->StoreName;
            $row[] = $fullname;               //createdby
            $row[] = $this->Store_Model->get_total($field->StoreId);

            $row[] = '<div class="">
                        <span class= "badge bg-label-'.$field->StoreStatusColor.'">'.$field->StoreStatusName.'</span>
                      </div>';

            $row[] = $button;
            $table[] = $row;
        }

        $output = array(
          "draw"                => $post['draw'],
          "recordsTotal"        => $this->Store_Model->count_all($post,$id),
          "recordsFiltered"     => $this->Store_Model->count_filtered($post,$id),
          "data"                => $table,
          $data['csrf']['name'] => $data['csrf']['hash']
        );
        echo json_encode($output);
      }


    //To validate create store form
    function create_store(){

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('storecode', 'Store Code', 'required');
      $this->form_validation->set_rules('Storename', 'Store Name', 'required');
      $this->form_validation->set_rules('status', 'status', 'required');

      $get = $this->input->post();

      $result['get'] = $get;

      if($this->form_validation->run() == TRUE){

        $storecode = $get['storecode'];
        $count    = $this->Store_Model->count_store($storecode);

        if ($count != 0) {

          $message = ' Store Code already Exist!!';

          $status    = false;
          $response  = $message;
          $errorcode = 400;
      }
      else {

        $checking  = [];
        $duplicate = [];
        for ($i=1; $i < $get['totalpos'] + 1 ; $i++) {

          if (isset($get['posno_'.$i])) {
            if (in_array($get['posno_'.$i],$checking)) {
              $duplicate[] = $get['posno_'.$i];
            }else {
              $checking[] = $get['posno_'.$i];
            }
          }
        }

        if (empty($duplicate)) {
          if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
          {
            $totalpos = $get['totalpos'];


            if ($storeid = $this->Store_Model->insertStore($get))
            {
              // $storename  = $this->Store_Model->get_storename($storeid);
                for ($i=1; $i <= $totalpos; $i++) {
                  if ($get['posno_'.$i] != "") {
                    $posval = $get['posno_'.$i];
                    $this->Store_Model->insertPOS($posval,$storeid,$get);
                    $this->Store_Model->insertVoucherSettingPermission($storeid,$get);
                  }
              }

              $storename  = $this->Store_Model->get_storename($storeid);

              if (isset($get['card'])) {
                foreach ($get['card'] as $card => $val) {
                  $this->Store_Model->insertCard($val,$storeid,$get);
                }
              }

              $status     = true;
              $response   = "Store has been created.";
              $errorcode  = 200;
              $actmsg     = " create Store ".$storename;
            } else {
              $status     = false;
              $response   = [
                "type"    => "authentication",
                "error"   => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode  = 500;
              $actmsg     = " is trying to create Store ".$storename.". Failed.";
            }
          } else
          {
            $status    = false;
            $response  = [
              "type"   => "authentication",
              "error"  => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode = 500;
            $actmsg    = " is trying to create Store ".$storename.". Applogger update failed.";
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 22,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d h:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
        }else {

          $response = '';
          foreach ($duplicate as $key => $value) {
            $response .= 'POS Number '.$value.' is duplicated! <br>';
          }
          $status    = false;
          $errorcode = 400;
        }
      }

      }
      else {
        $error = $this->form_validation->error_array();
        $salah = '';
        foreach ($error as $keye) {
          $salah .= $keye.'<br>';
        }

        $status    = false;
        $response  = $salah;
        $errorcode = 400;
      }

      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }
}
 ?>
