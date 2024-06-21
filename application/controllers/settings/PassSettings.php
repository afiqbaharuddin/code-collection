<?php
//password settings

class PassSettings extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('settings/PassSettings_model');
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
  $this->global_data['AppType']     = 2;
  $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() ); //for edit part
  $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
  // $this->output->enable_profiler(TRUE);
  }

  function index()
  {
    $data = array_merge($this->global_data);

    $data['header']   = $this->load->view('templates/main-header',"",true);
    $data['topbar']   = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']  = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']   = $this->load->view('templates/main-footer',"",true);
    $data['bottom']   = $this->load->view('templates/main-bottom',"",true);

    $data['pass']     = $this->PassSettings_model->get_passdata();

    $this->load->view('settings/password_settings', $data);
  }

  function settings()
  {
    $datas = array_merge($this->global_data);

    $datas['IpAddress']       = $this->input->ip_address();
    $datas['OperatingSystem'] = $this->agent->platform();
    $datas['Browser']         = $this->agent->browser();

    $get = $this->input->post();

    $continue = 11; // proceed
    $salah = '';

    if (isset($get['minPassCheck']) && $get['minPassCheck'] != null) {
      if ($get['minPassvalue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Minimum password length.<br>';
      }
    }

    if (isset($get['upCharCheck']) && $get['upCharCheck'] != null) {
      if ($get['upCharValue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Uppercase characters (A through Z).<br>';
      }
    }

    if (isset($get['lowCharCheck']) && $get['lowCharCheck'] != null) {
      if ($get['lowCharValue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Lowercase characters (A through z).<br>';
      }
    }

    if (isset($get['numberCheck']) && $get['numberCheck'] != null) {
      if ($get['numberValue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Numbers.<br>';
      }
    }

    if (isset($get['ageCheck']) && $get['ageCheck'] != null) {
      if ($get['ageValue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Lowercase characters (A through z).<br>';
      }
    }

    if (isset($get['historyCheck']) && $get['historyCheck'] != null) {
      if ($get['historyValue'] == "") {
        $continue = 99;
        $salah .= 'Please insert Lowercase characters (A through z).<br>';
      }
    }

    if ($continue == 11) {

        //1 Minimum password length
        if (isset($get['minPassCheck']) && $get['minPassCheck'] != null) {
            $data['MinPassCheck']= 1;
            $data['MinPassValue']=$get['minPassvalue'];
        }else {
            $data['MinPassCheck']=0;
            // $data['MinPassValue']=0;

        }

        //2 Uppercase characters (A through Z)
        if (isset($get['upCharCheck']) && $get['upCharCheck']!= null) {
          $data['UppercaseCheck']= 1;
          $data['UppercaseValue']= $get['upCharValue'];

          }else {
          $data['UppercaseCheck']=0;

        }

        //3 Lowercase characters (A through z)
        if (isset($get['lowCharCheck']) && $get['lowCharCheck']!== null) {
          $data['LowercaseCheck']=1;
          $data['LowercaseValue']=$get['lowCharValue'];

          }else {
          $data['LowercaseCheck']=0;

        }

        //4 Numbers
        if (isset($get['numberCheck']) && $get['numberCheck']!== null) {
          $data['NumbersCheck']=1;
          $data['NumbersValue']=$get['numberValue'];

          }else {
          $data['NumbersCheck']=0;

        }

        //5 Frequency of forced password changes (Password Age)
        if (isset($get['ageCheck']) && $get['ageCheck']!== null) {
          $data['PasswordAgeCheck']=1;
          $data['PasswordAgeValue']=$get['ageValue'];

          }else {
          $data['PasswordAgeCheck']=0;

        }

        //6 Password History
        if (isset($get['historyCheck']) && $get['historyCheck']!== null) {
          $data['PasswordHistoryCheck']=1;
          $data['PasswordHistoryValue']=$get['historyValue'];

          }else {
          $data['PasswordHistoryCheck']=0;

        }

        //7 Initial log-on uses a one-time password
        if (isset($get['initialonetime']) && $get['initialonetime']!==null) {
        $data['IntialLogOneTimePassCheck']=1;

        }else {
          $data['IntialLogOneTimePassCheck']=0;
        }

        //8 Ability of users to assign their own password
        if (isset($get['initialownps']) && $get['initialownps']!==null) {
        $data['OwnPassCheck']=1;
        }else {
          $data['OwnPassCheck']=0;
        }

      if ($data['AppLoggerId'] = $this->App_logger_model->insert_app_logger_data($datas))
        {
          $data['StatusId'] = 1;
          $status           = array('StatusId' => 2);
          $this->PassSettings_model->changeStatus($status);

          if ($id = $this->PassSettings_model->settingPass($data))
          {
            $status     = true;
            $response   = "Password Setting has been succesfully saved.";
            $errorcode  = 200;
            $actmsg     = " update Password Settings ";
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to Password Settings. Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Password Settings. Applogger update failed.";
        }
        $act = [
          'UserId'           => $datas['UserId'],
          'ActivityTypeId'   => 29,
          'ActivityDetails'  => $datas['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
          'IpAddress'        => $datas['IpAddress'],
          'OperatingSystem'  => $datas['OperatingSystem'],
          'Browser'          => $datas['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
    }
    else {

      $status    = false;
      $response  = $salah;
      $errorcode = 400;
    }

      $result['token']    = $datas['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
  }

  function ResetSettings(){

    $datas = array_merge($this->global_data);

    $datas['IpAddress']       = $this->input->ip_address();
    $datas['OperatingSystem'] = $this->agent->platform();
    $datas['Browser']         = $this->agent->browser();

    $get = $this->input->post();

      if ($data['AppLoggerId'] = $this->App_logger_model->insert_app_logger_data($datas))
      {
        $statusdb = array('StatusId' => 2);
        $this->PassSettings_model->changeStatusCancel($statusdb);

        if ($id = $this->PassSettings_model->resetpasswordsetting($data, $get))
        {
          $status     = true;
          $response   = "Password Setting has been succesfully saved.";
          $errorcode  = 200;
          $actmsg     = " update Password Settings ";
        } else {
          $status     = false;
          $response   = [
            "type"    => "authentication",
            "error"   => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode  = 500;
          $actmsg     = " is trying to update Password Settings. Failed.";
        }
      } else
      {
        $status    = false;
        $response  = [
          "type"   => "authentication",
          "error"  => array('error' => 'Something went wrong. Please try again later.'),
        ];
        $errorcode = 500;
        $actmsg    = " is trying to update Password Settings. Applogger update failed.";
      }
      $act = [
        'UserId'           => $datas['UserId'],
        'ActivityTypeId'   => 28,
        'ActivityDetails'  => $datas['Fullname'].$actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $datas['IpAddress'],
        'OperatingSystem'  => $datas['OperatingSystem'],
        'Browser'          => $datas['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);

    $result['token']    = $datas['csrf']['hash'];
    $result['status']   = $status;
    $result['message']  = $response;

    echo json_encode($result);
  }
}
 ?>
