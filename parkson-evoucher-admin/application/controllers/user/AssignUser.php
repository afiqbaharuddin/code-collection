<?php

  class AssignUser extends CI_Controller
  {

    function __construct()
    {
      parent::__construct();
      $this->load->model('user/AssignUser_Model');
      $this->load->model('App_logger_model');
      $this->load->model('logs/ActivityLog_Model');
      $this->load->model('scheduler_Model');

      if ($this->session->userdata('is_logged_in') == false) {
        redirect();
    }

    //CSRF PROTECTION\\
    $this->global_data['csrf'] = [
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
    ];
    //CSRF PROTECTION\\

    $this->global_data['UserId']       = $this->session->userdata('UserId');
    $this->global_data['Fullname']     = $this->session->userdata('Fullname');
    $this->global_data['Role']         = $this->session->userdata('Role');

    $this->global_data['CreatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']    = $this->global_data['UserId'];
    $this->global_data['AppType']      = 2;
    $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() ); //for edit part
    $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);

    }

    function assign()
    {
      $data = array_merge($this->global_data);

      $data['header']         = $this->load->view('templates/main-header',"",true);
      $data['topbar']         = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']        = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']         = $this->load->view('templates/main-footer',"",true);
      $data['bottom']         = $this->load->view('templates/main-bottom',"",true);

      $data['UserId']         = $this->uri->segment(4);
      $id                     = $this->uri->segment(4);

      $data['checking']            = $this->AssignUser_Model->get_MOD($id);

      if(isset($data['checking'])){
        $data['details']        = $this->AssignUser_Model->get_userdetails($id);     //get assign manager details
      }else{
        $data['details']        = $this->AssignUser_Model->get_statusMOD($id);       //get assign manager details
      }

      $data['managerstatus']  = $this->AssignUser_Model->get_managerstatus()->result(); //status
      $data['currentrole']    = $this->AssignUser_Model->get_managercurrentrole($id); //status
      // print_r($data['UserId']  );die;

      $this->load->view('user/assign_user', $data);
    }

    function assignmanager() {

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get    = $this->input->post();
      $userid = $get['userid'];
      $today  = date('Y-m-d');

      $this->form_validation->set_rules('start_date','Start Date','required');
      $this->form_validation->set_rules('end_date','End Date','required');
      $this->form_validation->set_rules('managerstatus','Status','required');

      if($this->form_validation->run() == TRUE){
        $username  = $this->AssignUser_Model->get_username($get['userid']);

        if ($today == $get['start_date'])
        {
            if ( $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
            {
              if ($manager = $this->AssignUser_Model->assignmanager($get))
              {
                $this->AssignUser_Model->updateRole($manager, $userid);
                $this->AssignUser_Model->updateRole_userStore($manager, $userid);

                $status     = true;
                $response   = "Manager on Duty have been assigned.";
                $errorcode  = 200;
                $actmsg     = " Assign " .$username. " as Manager on Duty today. ";
              } else {
                $status     = false;
                $response   = [
                  "type"    => "authentication",
                  "error"   => array('error' => 'Something went wrong. Please try again later.'),
                ];
                $errorcode  = 500;
                $actmsg     = " is trying to assign " .$username. " as Manager on Duty. Failed.";
              }
            } else
            {
              $status    = false;
              $response  = [
                "type"   => "authentication",
                "error"  => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode = 500;
              $actmsg    = " is trying to assign " .$username. " as Manager on Duty. Applogger update failed.";
            }
            $act = [
              'UserId'           => $data['UserId'],
              'ActivityTypeId'   => 30,
              'ActivityDetails'  => $data['Fullname'].$actmsg,
              'ActiveDate'       => date('Y-m-d H:i:s'),
              'IpAddress'        => $data['IpAddress'],
              'OperatingSystem'  => $data['OperatingSystem'],
              'Browser'          => $data['Browser'],
            ];

            $this->ActivityLog_Model->insert_activity($act);
        }else {
          $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data);
          $this->AssignUser_Model->assignmanagerLater($get);

          $status     = true;
          $response   = "Manager on Duty have been assigned.";
          $actmsg     = " assigned ".$username." as Manager on Duty later on " .$get['start_date'];

          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 30,
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
    }
 ?>
