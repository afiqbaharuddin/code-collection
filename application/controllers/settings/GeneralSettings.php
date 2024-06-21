<?php

  class GeneralSettings extends CI_Controller
  {
    function __construct()
    {
      parent::__construct();
      $this->load->model('settings/GeneralSettings_model');
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

      $data['header']     = $this->load->view('templates/main-header',"",true);
      $data['topbar']     = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']    = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']     = $this->load->view('templates/main-footer',"",true);
      $data['bottom']     = $this->load->view('templates/main-bottom',"",true);

      $data['general']    = $this->GeneralSettings_model->get_generaldata();

      $this->load->view('settings/general_settings', $data);
    }

    function settings(){

      $datas = array_merge($this->global_data);

      $datas['IpAddress']       = $this->input->ip_address();
      $datas['OperatingSystem'] = $this->agent->platform();
      $datas['Browser']         = $this->agent->browser();

      $get = $this->input->post();

      $continue = 11; // proceed
      $salah = '';

      if (isset($get['validationCheck']) && $get['validationCheck'] != null) {
        if ($get['validation'] == "") {
          $continue = 99;
          $salah   .= 'Please insert validation start date.<br>';
        }
      }

      if (isset($get['campaignCheck']) && $get['campaignCheck'] != null) {
        if ($get['campaign'] == "") {
          $continue   = 99;
          $salah     .= 'Please insert campaign set Due day.<br>';
        }
      }

      if (isset($get['limitCheck']) && $get['limitCheck'] != null) {
        if ($get['limit'] == "") {
          $continue = 99;
          $salah   .= 'Please insert Limit Attempt User Log In.<br>';
        }
      }

      if (isset($get['timeCheck']) && $get['timeCheck'] != null) {
        if ($get['time'] == "") {
          $continue = 99;
          $salah   .= 'Please insert Idle Timeout User Logout (Mins).<br>';
        }
      }

      if (isset($get['lockoutCheck']) && $get['lockoutCheck'] != null) {
        if ($get['lockout'] == "") {
          $continue = 99;
          $salah   .= 'Please insert Number of failed login before account lockout.<br>';
        }
      }

      if ($continue == 11) {

        //Allow Campaign Validation
        if (isset($get['validationCheck']) && $get['validationCheck'] != null) {
            $data['CampaignValidationCheck']  = "Y";
            $data['CampaignValidationDate']   = $get['validation'];
        }else {
            $data['CampaignValidationCheck']  = "N";
        }

        // Campaign Set Due (Days)
        if (isset($get['campaignCheck']) && $get['campaignCheck'] != null) {
            $data['CampaignSetDueCheck'] = "Y";
            $data['CampaignSetDueDays']  = $get['campaign'];
        }else {
            $data['CampaignSetDueCheck'] = "N";
        }

        //Limit Attempt User Log In
        if (isset($get['limitCheck']) && $get['limitCheck'] != null) {
            $data['LimitUserLogCheck']="Y";
            $data['LimitUserLogNum']=$get['limit'];
        }else {
            $data['LimitUserLogCheck']="N";
        }

        //Idle Timeout User Logout (Mins)
        if (isset($get['timeCheck']) && $get['timeCheck'] != null) {
            $data['IdleTimeoutCheck']="Y";
            $data['IdleTimeoutNum']=$get['time'];

        }else {
            $data['IdleTimeoutCheck']="N";
        }

        //Number of failed login before account lockout
        if (isset($get['lockoutCheck']) && $get['lockoutCheck'] != null) {
            $data['NumFailedLoginCheck']="Y";
            $data['NumFailedLoginNum']=$get['lockout'];

        }else {
            $data['NumFailedLoginCheck']="N";
        }

        //Unlock account manually by administrator
        if (isset($get['unlockmannual']) && $get['unlockmannual'] != null) {
            $data['UnlockManuallyCheck']="Y";
        }else {
            $data['UnlockManuallyCheck']="N";
        }

        if ($data['AppLoggerId'] = $this->App_logger_model->insert_app_logger_data($datas))
        {
          $statusdb = array('StatusId' => 2);
          $this->GeneralSettings_model->changeStatus($statusdb);
          $data['StatusId'] = 1;

          if ($id = $this->GeneralSettings_model->generalSettings($data))
          {
            $status     = true;
            $response   = "General Setting has been succesfully saved.";
            $errorcode  = 200;
            $actmsg     = " update General Settings ";
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update General Settings. Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update General Settings. Applogger update failed.";
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
    }
    else
    {
      $status    = false;
      $response  = $salah;
      $errorcode = 400;
    }

      $result['token']    = $datas['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    function CancelSettings(){

      $datas = array_merge($this->global_data);

      $datas['IpAddress']       = $this->input->ip_address();
      $datas['OperatingSystem'] = $this->agent->platform();
      $datas['Browser']         = $this->agent->browser();

      $get = $this->input->post();

        if ($data['AppLoggerId'] = $this->App_logger_model->insert_app_logger_data($datas))
        {
          $statusdb = array('StatusId' => 2);
          $this->GeneralSettings_model->changeStatusCancel($statusdb);
          // $data['StatusId'] = 1;

          if ($id = $this->GeneralSettings_model->CancelgeneralSettings($data, $get))
          {
            $status     = true;
            $response   = "General Setting has been succesfully saved.";
            $errorcode  = 200;
            $actmsg     = " update General Settings ";
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update General Settings. Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update General Settings. Applogger update failed.";
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
