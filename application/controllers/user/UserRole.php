<?php

  class UserRole extends CI_Controller
  {

    function __construct()
    {
      parent::__construct();
      $this->load->model('user/User_Model');
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

      //important put every page
      $this->global_data['UserId']       = $this->session->userdata('UserId');
      $this->global_data['Fullname']     = $this->session->userdata('Fullname');
      $this->global_data['Role']         = $this->session->userdata('Role');

      $this->global_data['CreatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']    = $this->global_data['UserId'];
      $this->global_data['AppType']      = 2;

      $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
      $this->global_data['UserRoleId']   = $this->session->userdata('UserRoleId');
      $this->global_data['Role']         = $this->session->userdata('Role');
      // $this->output->enable_profiler(TRUE);
    }

    function UserRole() //user role list page
    {
      $data = array_merge($this->global_data);

      $data['header']   = $this->load->view('templates/main-header',"",true);
      $data['topbar']   = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']  = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']   = $this->load->view('templates/main-footer',"",true);
      $data['bottom']   = $this->load->view('templates/main-bottom',"",true);

      $data['userrolestatus']     = $this->User_Model->get_userrole_status()->result(); //fetch user role status from db (dropdown)
      $this->load->view('user/user_role', $data);
    }

    public function listing(){
      $data  = array_merge($this->global_data);

      $post  = $this->input->post();
      $list  = $this->User_Model->get_role_datatables($post);
      $table = array();
      $no    = $post['start'];

      $k     = 1;

      foreach ($list as $field) {

        if($field->StatusId == 1)
        {
          $check = 'checked';
        } else {
          $check = '';
        }

        $no++;
        $row   = array();
  			$row[] = $no;
        // $row[] = $field->UserRoleId;
  			$row[] = $field->Role;
        $row[] = date('d/m/Y h:i A', strtotime($field->CreatedDate));

        $row[] = '<span id="showstatusname_'.$k.'">
                    <span class="badge bg-label-'.$field->StatusColor.'">'.$field->StatusName.'</span>
                  </span>';

        $row[] = '<input type="hidden" id="rolestatus_'.$k.'" name="rolestatus" value="'.$field->UserRoleId.'"/>
                    <label class="switch">
                    <input id="toggle_'.$k.'" type="checkbox" class="switch-input is-valid toggle" data-num="'.$k.'" value="'.$field->UserRoleId.'" '.$check.'/>
                      <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                  </label>';

        $table[] = $row;

        $k++;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->User_Model->count_role_all($post),
        "recordsFiltered"     => $this->User_Model->count_role_filtered($post),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function createRole()
    {
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('rolename', 'Role Name', 'required');
      $this->form_validation->set_rules('status', 'Status', 'required');

      if($this->form_validation->run() == TRUE){

        $role   = $get['rolename'];
        $count  = $this->User_Model->count_role($role);

        if ($count != 0) {

            $message = ' Role already Exist!!';

            $status    = false;
            $response  = $message;
            $errorcode = 400;
        }
        else {

          if ( $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
          {
            if ($id = $this->User_Model->insertRole($get))
            {
              $rolename = $this->User_Model->get_rolename($id);

              $this->User_Model->insertRolePermission($id, $get);
              $this->User_Model->insertRoleSubPermission($id, $get);


              $status     = true;
              $response   = "User Role has been created.";
              $errorcode  = 200;
              $actmsg     = " create User Role, ".$rolename;
            } else {
              $status     = false;
              $response   = [
                "type"    => "authentication",
                "error"   => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode  = 500;
              $actmsg     = " is trying to create User Role ID ".$rolename.". Failed.";
            }
          } else

          {
            $status    = false;
            $response  = [
              "type"   => "authentication",
              "error"  => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode = 500;
            $actmsg    = " is trying to create User Role ID ".$rolename.". Applogger update failed.";
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 21,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
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

    function get_rolestatus() {

      $data         = array_merge($this->global_data);
      $get          = $this->input->post();

      $data['IpAddress']        = $this->input->ip_address();
      $data['OperatingSystem']  = $this->agent->platform();
      $data['Browser']          = $this->agent->browser();

      $status       = $this->input->post('status');
      $userroleId   = $this->input->post('userroleId');

      $data['AppLoggerId'] = $get['loggerid'];

      if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->User_Model->updateRoleStatus($status, $userroleId))
          {
            $rolename      = $this->User_Model->get_rolename($userroleId);
            $currentstatus = $this->User_Model->get_currentstatus($status);

            $status     = true;
            $response   = "User Role has been updated.";
            $errorcode  = 200;
            $actmsg     = " update User Role Status, ".$rolename. " to " .$currentstatus;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update User Role ID ".$rolename.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update User Role ID ".$rolename.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 11,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
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
