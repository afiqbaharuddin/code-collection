<?php
// echo 'Your account has created and has a Strong password. password is: '.decode($encryptedpassword,ENCRYPTION_KEY);

  class User extends CI_Controller
  {

    public function __construct()
    {
      parent::__construct();
      $this->load->model('user/User_Model');
      $this->load->model('logs/ActivityLog_Model');
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

      $this->global_data['AppType']   = 2;
      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['CreatedBy']   = $this->global_data['UserId'];
    	$this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
    	$this->global_data['UpdatedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
  	}

    //user - main view page
    function UserList()
    {
      $data = array_merge($this->global_data);

      $data['header']             = $this->load->view('templates/main-header',"",true);
      $data['topbar']             = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']            = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']             = $this->load->view('templates/main-footer',"",true);
      $data['bottom']             = $this->load->view('templates/main-bottom',"",true);

      $data['storecode_create']   = $this->User_Model->get_storecode_create()->result();
      $data['storecode_edit']     = $this->User_Model->get_storecode_edit()->result();
      $data['userrole_create']    = $this->User_Model->get_userrole_create()->result();
      $data['userrole_edit']      = $this->User_Model->get_userrole_edit()->result();
      $data['userstatus_edit']    = $this->User_Model->get_userstatus_edit()->result();

      $data['userCount']    = $this->User_Model->get_userCount($this->session->userdata('UserId'));

      //cards top on user page
      $data['totalUser']    = $this->User_Model->get_totaluser();
      $data['activeUser']   = $this->User_Model->get_totalActiveuser();
      $data['inactiveUser'] = $this->User_Model->get_totalInactiveuser();

      $data['unlockmanuallyadmin'] = $this->User_Model->unlockmanuallyadmin();

      // echo"<pre>";
      // print_r($data['unlockmanuallyadmin']);die;
      // echo"</pre>";

      //cards top on user page based on store assigned
      $data['totalUserStore']    = $this->User_Model->get_totaluserStore($data['userCount']->StoreId);
      $data['activeUserStore']   = $this->User_Model->get_totalActiveuserStore($data['userCount']->StoreId);
      $data['inactiveUserStore'] = $this->User_Model->get_totalInactiveuserStore($data['userCount']->StoreId);

      $data['UserId']             = $this->uri->segment(4);
      $data['password']           = $this->User_Model->generatepass()->row();

      $data['userlist'] = $this->User_Model->get_user_list();

      $data['user_role'] = $this->User_Model->get_user_role($this->session->userdata('UserId'));

      $this->load->view('user/user_list', $data);
    }

    // function get_user_list(){
    //   $user = $this->User_Model->get_user_list();
    //
    //   echo "<pre>";
    //   print_r($user);
    //   echo "</pre>";
    // }

    //user listtable
    public function listing(){
      $data = array_merge($this->global_data);

      $post = $this->input->post();
      $id = $this->uri->segment(4);

      // $encrypted_password = '596b524759565a335054303d';
      // print_r(decrypt($encrypted_password,ENCRYPTION_KEY));die;

      $permission = $this->User_Model->get_permission($this->session->userdata('UserId'));
      $userid     = $this->User_Model->userStore($this->session->userdata('UserId'));

      $list  = $this->User_Model->get_datatables($post, $userid->StoreId,$permission);
      // $list_store  = $this->User_Model->get_store_datatables($post, $userid->StoreId);
      $table = array();
      $no    = $post['start'];

          foreach ($list as $field){
            $no++;
            $getuserid = $this->User_Model->getuserid($field->CreatedBy);

            if (isset($getuserid)) {
              $fullname = $getuserid->Fullname;
            }else {
              $fullname = '';
            }

            $getinactivedate  = $this->User_Model->get_inactivedate($field->InactiveDate);
            if (isset($getinactivedate)) {
              $inactivedate = $getinactivedate->InactiveDate;
            } else {
              $inactivedate = '';
            }

          $row   = array();
          $row[] = '<div class="d-flex flex-column">
                      <a class="text-body text-truncate">
                        <span class="fw-semibold">'.$field->Fullname.'</span>
                      </a>
                      <small class="text-muted">'.$field->StaffId.'</small>
                    </div>';
          $row[] = $field->Role;
          $row[] = $field->StoreName;
          $row[] = $fullname;

          $row[] = date('Y-m-d H:i:s', strtotime($field->CreatedDate));
          $row[] = $inactivedate;

          $row[] = '<span>
                      <span class="badge bg-label-'.$field->StatusColor.'">'.$field->StatusName.'</span>
                    </span>';


          $row[] = '<button class="btn btn-sm btn-icon viewuser" data-userid="'.$field->UserId.'"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditUser">
                    <i class="bx bx-edit"></i>
                    </button>';

            $table[] = $row;
          }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->User_Model->count_all($post, $userid->StoreId,$permission),
        "recordsFiltered"     => $this->User_Model->count_filtered($post, $userid->StoreId,$permission),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function createUser(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      if (strlen($get['staffId']) < 6) {
        $this->form_validation->set_rules('staffId', 'Staff Id', 'required|min_length[6]');
      }

      //kalau flag 2, baru confirm pass
      if ($get['flagConfirm']==2) {
        $this->form_validation->set_rules('confirmPass', 'Confirm Password', 'required');
      }

      $this->form_validation->set_rules('loginId', 'Login Id', 'required');
      $this->form_validation->set_rules('password', 'Password', 'required');
      $this->form_validation->set_rules('storeCode', 'Store Code', 'required');
      $this->form_validation->set_rules('fullname', 'Fullname', 'required');
      $this->form_validation->set_rules('userRole', 'User Role', 'required');

      if($this->form_validation->run() == TRUE){

        $staffid   = $get['staffId'];
        $count  = $this->User_Model->count_staffid($staffid);

        $loginid     = $get['loginId'];
        $countLogin  = $this->User_Model->count_Login($loginid);

        if ($count != 0 ) {

            $message = ' Staff Id already Exist!!';

            $status    = false;
            $response  = $message;
            $errorcode = 400;

        }elseif ($countLogin != 0) {

              $message = ' Login Id already Exist!!';

              $status    = false;
              $response  = $message;
              $errorcode = 400;
        } else {
            $pass                 = $get['password'];
            $password             = $this->User_Model->get_password_setting();
            $pass_status          = 11;//okay
            $error                = '';
            $encryptedpassword    = encrypt($this->input->post('password'),ENCRYPTION_KEY);

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

            //pass checking
            if ($pass_status == 99) {
              $status    = false;
              $response  = $error;
              $errorcode = 400;
            }
            else
            {
              if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
              {
                $insertuser = array(
                  'StaffId'         => $get['staffId'],
                  'LoginId'         => $get['loginId'],
                  'Password'        => $encryptedpassword,
                  'Fullname'        => $get['fullname'],
                  'Email'           => $get['email'],
                  'UserRoleId'      => $get['userRole'],
                  'PhoneNo'         => $get['phonenum'],
                  'StatusId'        => 1,
                  'FirstTimeLogin'  => 1,
                  'AttemptDate'     => date("Y-m-d"),
                  // 'AttemptNumber' => ,
                  'AppLoggerId'     => $get['loggerid'],
                );

                if ($userid = $this->User_Model->insertUser($insertuser))
                {
                  $username  = $this->User_Model->get_username($userid);

                  $this->User_Model->insert_permissionVoucherSetting($userid,$get);
                  $this->User_Model->inserusertStore($userid,$get);
                  $this->User_Model->insert_userPassLog($userid, $encryptedpassword);

                  $status     = true;
                  $response   = "User has been created.";
                  $errorcode  = 200;
                  $actmsg     = " create User ".$username;
                } else {
                  $status     = false;
                  $response   = [
                    "type"    => "authentication",
                    "error"   => array('error' => 'Something went wrong. Please try again later.'),
                  ];
                  $errorcode  = 500;
                  $actmsg     = " is trying to create User ID ".$username .". Failed.";
                }
              } else
              {
                $status    = false;
                $response  = [
                  "type"   => "authentication",
                  "error"  => array('error' => 'Something went wrong. Please try again later.'),
                ];
                $errorcode = 500;
                $actmsg    = " is trying to create User ID ".$username.". Applogger update failed.";
              }
              $act = [
                'UserId'           => $data['UserId'],
                'ActivityTypeId'   => 1,
                'ActivityDetails'  => $data['Fullname'].$actmsg,
                'ActiveDate'       => date('Y-m-d H:i:s'),
                'IpAddress'        => $data['IpAddress'],
                'OperatingSystem'  => $data['OperatingSystem'],
                'Browser'          => $data['Browser'],
              ];

              $this->ActivityLog_Model->insert_activity($act);
          }
      }
        //function create user form end
      }
      else
      {
        $message = $this->form_validation->error_array();
        $warningmessage ="";

        foreach ($message as $key) {
          $warningmessage .= $key."<br>";
        }

        $status    = false;
        $response  = $warningmessage;
        $errorcode = 400;
      }

      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    //get user detail for edit
    function userdetails(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $result['user']     = $this->User_Model->get_user($get['staff']);
      // echo "string";
      // print_r($result['user']);
      // $this->User_Model->get_userstore($get['staff']);
      $result['token']    = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function editUser()
    {
      $data = array_merge($this->global_data);
      $get  = $this->input->post();
      $id   = $get['userid'];
      $role = $get['role'];
      // print_r($role);die;

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('fullname', 'Fullname', 'required');

      if (isset($get['status'])) {
        if ($get['status'] == 2) {
          $this->form_validation->set_rules('inactiveDate', 'Inactive Date', 'required');
        }
      }

      if (isset($_POST['password']) && $_POST['password'])
      {
        $this->form_validation->set_rules('confirmpass', 'Confirm Password', 'required');
      }

        if($this->form_validation->run() == TRUE){

          if (isset($get['password']) && $get['password'] != "")
          {
            if ($get['confirmpass'] && $get['password'] == $get['confirmpass'] )
            {

              $pass = $get['password'];
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
                  if ($check->PasswordLogValue == encrypt($get['password'],ENCRYPTION_KEY)) {
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
                $this->User_Model->updateUser($get, $id);
                if ($role == 1) {
                  $this->User_Model->updateusertStore($get, $id); //check balik
                }

                //To add password log
                if ($get['password'] != null ) {
                  $send = array(
                    'PasswordLogValue'   => encrypt($get['password'],ENCRYPTION_KEY),
                    'UserId'   => $id,
                    'CreatedDate' => $data['CreatedDate']
                 );
                 $this->User_Model->insertPasswordLog($send);
                }

                $data['AppLoggerId'] = $get['loggerid'];
                $this->App_logger_model->update_app_logger_data($data);

                $status     = true;
                $response   = 'User Updated Sucessfully';
                $errorcode  = 200;
              }
            }
            else
            {
              $status     = false;
              $response   = 'Password and Confirm Password are not match';
              $errorcode  = 400;
            }
          }
          else
          {
            $this->User_Model->updateUser($get, $id);
            if ($role == 1) {
              $this->User_Model->updateusertStore($get, $id); //check balik
            }//check balik

            $data['AppLoggerId'] = $get['loggerid'];

            if ($this->App_logger_model->update_app_logger_data($data))
          {
            if ($this->User_Model->updateUser($get, $id))
            {
              $username   = $this->User_Model->get_username($get['userid']);

              $status     = true;
              $response   = "User has been updated.";
              $errorcode  = 200;
              $actmsg     = " update User ".$username;
            } else {
              $status     = false;
              $response   = [
                "type"    => "authentication",
                "error"   => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode  = 500;
              $actmsg     = " is trying to update User ID ".$username.". Failed.";
            }
          } else
          {
            $status    = false;
            $response  = [
              "type"   => "authentication",
              "error"  => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode = 500;
            $actmsg    = " is trying to update User ID ".$username.". Applogger update failed.";
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 12,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
          }
        }
        else
        {
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

    //get details reset password
    function resetPassDetails()
    {
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $result['details'] = $this->User_Model->get_resetPassword($get['userid']);
      $result['userid'] = $get['userid'];

      if ($result['details'] != null) {
        if ($result['details']->PasswordLogValue != "") {
          $result['decrypt'] = decrypt($result['details']->PasswordLogValue,ENCRYPTION_KEY);
        }
      }

      $result['token']    = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function updateDefaultPass()
    {
      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();
      $id  = $get['userid'];

      $encryptedpassword    = encrypt($this->input->post('defaultpass'),ENCRYPTION_KEY);

      if ($this->User_Model->updateDefaultPass($id, $encryptedpassword)) {
        $this->User_Model->updateDefaultPass_passLog($id , $encryptedpassword);
        $username   = $this->User_Model->get_username($get['userid']);

        $status     = true;
        $response   = 'Default Password Updated Sucessfully';
        $errorcode  = 200;
        $actmsg     = " update Default Password User ".$username;
      }else {
        $status     = false;
        $response   = 'Default Password update is unsuccessful';
        $errorcode  = 500;
        $actmsg     = " is trying to update User ID ".$username.". Failed.";
      }

      $act = [
        'UserId'           => $data['UserId'],
        'ActivityTypeId'   => 12,
        'ActivityDetails'  => $data['Fullname'].$actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);

      $result['status']   = $status;
      $result['message']   = $response;
      $result['token']   = $data['csrf']['hash'];
      echo json_encode($result);
    }


    function password() //test password page
    {
      $data = array_merge($this->global_data);

      $data['password']  = $this->User_Model->get_password_setting();

      $data['header']       = $this->load->view('templates/main-header',"",true);
      $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']       = $this->load->view('templates/main-footer',"",true);
      $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

      $this->load->view('user/password', $data);
    }

    //password settings
    function insertPass()
    {
      $data = array_merge($this->global_data);
      $post = $this->input->post();
      $result['get'] = $post;

      $pass = $post['pass'];

      $password  = $this->User_Model->get_password_setting();

      $pass_status = 11;//okay
      $error = '';


      if ($password->MinPassCheck == 1) {
        $length = $password->MinPassValue;
        if (strlen($pass) < $length) {
          $pass_status = 99;//not okay
          $error .= 'Password Length must be more than '.$password->MinPassValue.'<br>';
        }
      }

      if ($password->UppercaseCheck == 1) {
        $upper = preg_match_all("/[A-Z]/", $pass);
        if ($upper < $password->UppercaseValue) {
          $pass_status = 99;//not okay
          $error .= 'Password must contain uppercase at least '.$password->UppercaseValue.' character<br>';
        }
      }

      if ($password->LowercaseCheck == 1) {
        $lower = preg_match_all("/[a-z]/", $pass);
        if ($lower < $password->LowercaseValue) {
          $pass_status = 99;//not okay
          $error .= 'Password must contain lowercase at least '.$password->LowercaseValue.' character<br>';
        }
      }

      if ($password->NumbersCheck == 1) {
        $number = preg_match_all("/[0-9]/", $pass);
        if ($number < $password->NumbersValue) {
          $pass_status = 99;//not okay
          $error .= 'Password must contain numbers at least '.$password->NumbersValue.' character<br>';
        }
      }

      if ($password->PasswordHistoryCheck == 1) {// for user change passs
        $history  = $this->User_Model->get_password_history($data['UserId'],$pass);
        if ($history < $password->PasswordHistoryValue) {
          $pass_status = 99;//not okay
          $error .= 'Similar Password cannot be reused until '.$password->PasswordHistoryValue.'  times of Password changes. <br>';
        }
      }

      // print_r();

      if ($pass_status == 99) {
        $result['status']   = false;
        $result['message']  = $error;
      }else {
        $result['status']   = true;
        $result['message']  = 'Success';
      }

      $result['token']    = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function generatepass() {
      $data = array_merge($this->global_data);

      $data['password']  = $this->User_Model->get_password_setting();

      $data['header']       = $this->load->view('templates/main-header',"",true);
      $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']       = $this->load->view('templates/main-footer',"",true);
      $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

      $this->load->view('user/generatepass', $data);
    }
  }
 ?>
