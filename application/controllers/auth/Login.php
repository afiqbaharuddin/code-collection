<?php
  class Login extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('auth/Login_model');
      $this->load->model('logs/ActivityLog_Model');
      $this->load->model('App_logger_model');
      // $this->load->library('encryption');

      //CSRF PROTECTION\\
      $this->global_data['csrf'] =
      [
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
    }

    public function index()
    {
     $data = array_merge($this->global_data);

     $data['header']= $this->load->view('templates/auth-header',"",true);
     $data['footer']= $this->load->view('templates/auth-footer',"",true);

     $data['lockoutCheck']= $this->Login_model->get_lockoutlogin();
     $this->load->view('auth/login', $data);
    }

    public function validating()
    {
      // session_destroy();
      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      if ($this->session->userdata('UserId')) {
          $response  = "There is current active account. <br> Please Sign Out previous account.";
          $status    = false;
          $errorcode = 400;
          $actmsg    = "Anonymous is trying to Login. Failed.";

      }else {

        $this->form_validation->set_rules('loginId', 'Login ID', 'required');
        $this->form_validation->set_rules('Password', 'Password', 'required');

        $get = $this->input->post();
        $id  = $this->input->post($get['userid']);
        $count = $get['count'];

        $lockoutlogin = $this->Login_model->get_lockoutlogin();

        if($this->form_validation->run() == TRUE)
        {
          $LoginId  = $this->Login_model->get_loginId($get,$id);

          if (!isset($LoginId))
          {
            $status    = false;
            $response  = "User doesn't exist!";
            $errorcode = 404;
            $actmsg    = "Anonymous is trying to Login. Failed.";
          }
          else
          {
            $Password  = $this->Login_model->get_password($get);
            // $this->Login_model->blockuser($LoginId);

            if ($lockoutlogin->NumFailedLoginCheck == 'Y') {
                if (!isset($Password))
                {
                  if ($LoginId->RetryPassword < $lockoutlogin->NumFailedLoginNum) {
                    $rP = $LoginId->RetryPassword +1;

                    $this->Login_model->update_retryPassword($LoginId->LoginId, $rP);

                    $status    = false;
                    $response  = "Your ID or Password is incorrect";
                    $errorcode = 400;
                    $actmsg     = "User is trying to Login with wrong ID or Password";

                  }
                  else {
                    $this->Login_model->blockuser($LoginId->UserId);
                    $status    = false;
                    $response  = "Your ID has been blocked.\nPLEASE CONTACT YOUR ADMIN!";
                    $errorcode = 400;
                    $actmsg     = "User is trying to Login with wrong ID or Password";
                  }
                }
                else
                {
                  $this->session->set_userdata('UserId', $Password->UserId);
                  $this->session->set_userdata('Fullname', $Password->Fullname);
                  $this->session->set_userdata('Role', $Password->Role);
                  $this->session->set_userdata('RoleId', $Password->UserRoleId);
                  $this->session->set_userdata('is_logged_in', true);

                  $this->Login_model->set_retryPassword($LoginId->LoginId);

                  $status    = true;
                  $response  = "Validating..";
                  $errorcode = 200;
                  $actmsg    = "$Password->Fullname Successfuly Login ";

                }

                $act = [
                'UserId'           => $LoginId->UserId,
                'ActivityTypeId'   => 3,
                'ActivityDetails'  => $data['Fullname'].$actmsg,
                'ActiveDate'       => date('Y-m-d H:i:s'),
                'IpAddress'        => $data['IpAddress'],
                'OperatingSystem'  => $data['OperatingSystem'],
                'Browser'          => $data['Browser'],
              ];
              $this->ActivityLog_Model->insert_activity($act);
            }
            else {
              if (!isset($Password))
              {
                  $status    = false;
                  $response  = "Your ID or Password is incorrect";
                  $errorcode = 400;
                  $actmsg     = "User is trying to Login with wrong ID or Password";
              }
              else
              {
                $this->session->set_userdata('UserId', $Password->UserId);
                $this->session->set_userdata('Fullname', $Password->Fullname);
                $this->session->set_userdata('Role', $Password->Role);
                $this->session->set_userdata('RoleId', $Password->UserRoleId);
                $this->session->set_userdata('is_logged_in', true);

                $this->Login_model->set_retryPassword($LoginId->LoginId);

                $status    = true;
                $response  = "Validating..";
                $errorcode = 200;
                $actmsg    = "$Password->Fullname Successfuly Login ";

              }
            }
          }
        }

        else
        {
          $status    = false;
          $response  = $this->form_validation->error_array();
          $errorcode = 400;
        }
      }

      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    public function logout()
    {
      $this->session->sess_destroy();
      redirect('auth/Login');
    }

    public function getToken()
    {
      $data = array_merge($this->global_data);
      $result['token'] = $data['csrf']['hash'];
      echo json_encode($result);
    }
  }
 ?>
