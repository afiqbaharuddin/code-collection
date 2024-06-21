<?php
  /**
   *
   */
  class ProfileSecurity extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('profile/Profile_Model');
      $this->load->model('user/User_Model');
      $this->load->model('App_logger_model');

      if ($this->session->userdata('is_logged_in') == false)
      {
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
    	$this->global_data['UpdatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['UpdatedBy']   = $this->global_data['UserId'];
    }

    public function index()
    {
      $data = array_merge($this->global_data);

      $data['header']=$this->load->view('templates/main-header',"",true);
      $data['topbar']=$this->load->view('templates/main-topbar',"",true);
      $data['sidebar']=$this->load->view('templates/main-sidebar',"",true);
      $data['footer']=$this->load->view('templates/main-footer',"",true);
      $data['bottom']=$this->load->view('templates/main-bottom',"",true);


      $id                   = $this->session->userdata('UserId');
      $data['security']     = $this->Profile_Model->get_securitydetails($id);
      $data['ownPass']      = $this->Profile_Model->get_ownPass();
      // print_r($data['ownPass'] );die;

      $this->load->view('profile/profile_security', $data);
    }

    function security(){

      $data = array_merge($this->global_data);
      $get = $this->input->post();
      $id = $get['userid'];

      if (isset($get['currentPassword'])) {
        $this->form_validation->set_rules('newPassword', 'New Password', 'required');
        $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required');
      }

      if($this->form_validation->run() == TRUE)
      {
        if ($get['newPassword'] && $get['newPassword'] != "" )
        {
          if ($get['confirmPassword'] && $get['newPassword'] == $get['confirmPassword'])
          {
            $data['security']     = $this->Profile_Model->get_securitydetails($id);

            $decrypt_currentpass = decrypt(($data['security']->Password),ENCRYPTION_KEY);

            if ($get['currentPassword'] == $decrypt_currentpass)
            {
              $pass = $get['newPassword'];
              $password  = $this->User_Model->get_password_setting();
              $pass_status = 11;  //okay
              $error = '';

              //minimum password
              if ($password->MinPassCheck == 1) {
                $length = $password->MinPassValue;
                if (strlen($pass) < $length) {
                  $pass_status = 99;//not okay
                  $error .= 'Password Length must be more than '.$password->MinPassValue.'<br>';
                }
              }

              //Uppercase
              if ($password->UppercaseCheck == 1) {
                $upper = preg_match_all("/[A-Z]/", $pass);
                if ($upper < $password->UppercaseValue) {
                  $pass_status = 99;//not okay
                  $error .= 'Password must contain uppercase at least '.$password->UppercaseValue.' character<br>';
                }
              }

              //lowercase
              if ($password->LowercaseCheck == 1) {
                $lower = preg_match_all("/[a-z]/", $pass);
                if ($lower < $password->LowercaseValue) {
                  $pass_status = 99;//not okay
                  $error .= 'Password must contain lowercase at least '.$password->LowercaseValue.' character<br>';
                }
              }

              //numbers
              if ($password->NumbersCheck == 1) {
                $number = preg_match_all("/[0-9]/", $pass);
                if ($number < $password->NumbersValue) {
                  $pass_status = 99;//not okay
                  $error .= 'Password must contain numbers at least '.$password->NumbersValue.' character<br>';
                }
              }

              //password history
              if ($password->PasswordHistoryCheck == 1) {   // for user change passs
                $history  = $this->User_Model->get_password_log($get['userid'],$password->PasswordHistoryValue);

                $exist = false;
                foreach ($history as $check) {
                  if ($check->PasswordLogValue == encrypt($get['newPassword'],ENCRYPTION_KEY)) {
                    $exist = true;
                  }
                }

                if ($exist == true) {
                  $pass_status = 99;//not okay
                  $error .= 'Similar Password cannot be reused until '.$password->PasswordHistoryValue.'  times of Password changes. <br>';
                }
              }

              //pass checking
              if ($pass_status == 99) {
                $status    = false;
                $response  = $error;
                $errorcode = 400;
              }
              else
              {
                //To add password log
                if ($get['confirmPassword'] != null ) {
                  $send = array(
                    'PasswordLogValue'   => encrypt($get['confirmPassword'],ENCRYPTION_KEY),
                    'UserId'   => $id,
                    'CreatedDate' => $data['CreatedDate']
                 );
                 $this->User_Model->insertPasswordLog($send);
                }

                $data['AppLoggerId'] = $get['loggerid'];
                $this->App_logger_model->update_app_logger_data($data);
                $this->Profile_Model->securityUpdate($get, $id);

                $status     = true;
                $response   = 'New Password Updated Sucessfully';
                $errorcode  = 200;
              }
            }
            else
            {
              $status     = false;
              $response   = 'Old Password is wrong. Please Try again!';
              $errorcode  = 400;
            }
        }
        else
        {
            $status     = false;
            $response   = 'New Password and Confirm Password are not match';
            $errorcode  = 400;
        }
      }
      else {
      //         $this->Profile_Model->securityUpdate($get, $id);
      //
      //         $data['AppLoggerId'] = $get['loggerid'];
      //         $this->App_logger_model->update_app_logger_data($data);
      //
      //         $status     = true;
      //         $response   = 'New Password Updated Sucessfully';
      //         $errorcode  = 200;

               $status     = false;
               $response   = 'failed';
               $errorcode  = 400;
            }
    }
    else {
            $error  = $this->form_validation->error_array();
            $showerror = '';
            foreach ($error as $err => $errval) {
              $showerror .= $errval.'<br>';
              }
            $data['update'] = $error;
            $status     = false;
            $response   = $showerror;
            $errorcode  = 400;
        }

    $result['status']   = $status;
    $result['message']   = $response;
    $result['token']   = $data['csrf']['hash'];

    echo json_encode($result);
  }
}
?>
