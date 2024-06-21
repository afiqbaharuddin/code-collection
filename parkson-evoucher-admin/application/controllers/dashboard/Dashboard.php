<?php

class Dashboard extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('dashboard/dashboard_Model');
    $this->load->model('user/User_Model');
    $this->load->model('App_logger_model');
    $this->load->library('encryption');


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

    $data['title']   = 'Dashboard';
    $data['header']  = $this->load->view('templates/main-header',$data,true);
    $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']  = $this->load->view('templates/main-footer',"",true);
    $data['bottom']  = $this->load->view('templates/main-bottom',"",true);
    // print_r($this ->session-> set_userdata ('is_logged_in')) ;

    //show cards vouchers for all store
    $data['openStore']  = $this->dashboard_Model->get_storedetails();
    $data['closeStore'] = $this->dashboard_Model->get_storeclose();
    $data['campaign']   = $this->dashboard_Model->get_campaign();

    $data['user_role']  = $this->dashboard_Model->user_role($this->session->userdata('UserId'));
    $data['store']      = $this->dashboard_Model->store($this->session->userdata('UserId'));
    $data['userCount']  = $this->dashboard_Model->get_userCount($this->session->userdata('UserId'));

    $user = $this->session->userdata('UserId');

    $data['firstLogin']      = $this->dashboard_Model->get_userFlag($user);
    $data['oneTimePass']     = $this->dashboard_Model->oneTimePass();
    $data['passexpirycheck'] = $this->dashboard_Model->passexpirycheck($user);

    if ($data['oneTimePass']->IntialLogOneTimePassCheck == 0) {
      $notfirst = array('FirstTimeLogin' => 2);
      $this->dashboard_Model->updateloginFlag($notfirst);
    }

    $this->load->view('dashboard/dashboard', $data);
  }

  function card_allstore(){
    //all store card pv & dv
    $data['voucher_issued']          = $this->dashboard_Model->get_voucher_issued();
    $data['voucher_issued_today']    = $this->dashboard_Model->get_voucher_issued_today(); //graph and card
    $data['voucher_void_today']      = $this->dashboard_Model->get_voucher_void(); //graph
    $data['voucher_redeem_today']    = $this->dashboard_Model->get_voucher_redeem_today(); //graph and card
    $data['voucher_redeem']          = $this->dashboard_Model->get_voucher_redeem();
    $data['voucher_refund']          = $this->dashboard_Model->get_voucher_refund();

    //all store card gv
    $data['giftvoucher_issued']      = $this->dashboard_Model->get_giftvoucher_issued();
    $data['giftvoucher_redeem']      = $this->dashboard_Model->get_giftvoucher_redeem();
    $data['giftvoucher_void']          = $this->dashboard_Model->get_giftvoucher_void();

    //for graph
    $data['voucher_issued_today_graph']    = $this->dashboard_Model->get_voucher_issued_today(); //graph and card
    $data['voucher_redeem_today_graph']    = $this->dashboard_Model->get_voucher_redeem_today(); //graph and card

    echo json_encode($data);
  }

  function card_bystore() {
    $data['userCount']  = $this->dashboard_Model->get_userCount($this->session->userdata('UserId'));

    //show card for particular Stores PV and DV
    $data['vouchers_issued_store']          = $this->dashboard_Model->get_voucher_issued_store($data['userCount']->StoreId);
    $data['vouchers_issued_today_store']    = $this->dashboard_Model->get_voucher_issued_today_store($data['userCount']->StoreId);
    $data['vouchers_refund_store']          = $this->dashboard_Model->get_voucher_refund_store($data['userCount']->StoreId);
    $data['vouchers_redeem_today_store']    = $this->dashboard_Model->get_voucher_redeem_today_store($data['userCount']->StoreId); //graph and card
    $data['vouchers_redeem_store']          = $this->dashboard_Model->get_voucher_redeem_store($data['userCount']->StoreId);
    $data['vouchers_void_today_store']      = $this->dashboard_Model->get_vouchers_void_today_store($data['userCount']->StoreId); //graph

    //show card for particular Stores GV
    $data['giftvouchers_issued_store']      = $this->dashboard_Model->get_giftvoucher_issued_store($data['userCount']->StoreId);
    $data['giftvouchers_redeem_store']      = $this->dashboard_Model->get_giftvoucher_redeem_store($data['userCount']->StoreId);
    $data['giftvouchers_void_store']        = $this->dashboard_Model->get_giftvouchers_void_store($data['userCount']->StoreId); //graph

    //for graph
    $data['vouchers_issued_today_store_graph']    = $this->dashboard_Model->get_voucher_issued_today_store($data['userCount']->StoreId);
    $data['vouchers_redeem_today_store_graph']    = $this->dashboard_Model->get_voucher_redeem_today_store($data['userCount']->StoreId); //graph and card

    echo json_encode($data);
  }

  function login(){
    $data = array_merge($this->global_data);

    $get = $this->input->post();
    $id = $get['userid'];

    $this->form_validation->set_rules('currentPassword', 'Current Password', 'required');
    $this->form_validation->set_rules('newPassword', 'New Password', 'required');
    $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required');

    //example
    // $plain_text = 'This is a plain-text message!';
    // $ciphertext = encrypt($plain_text,ENCRYPTION_KEY);
    //
    // // Outputs: This is a plain-text message!
    // echo decrypt($ciphertext,ENCRYPTION_KEY);

    if($this->form_validation->run() == TRUE)
    {
      if ($get['newPassword'] && $get['newPassword'] != "" )
      {
        if ($get['confirmPassword'] && $get['newPassword'] == $get['confirmPassword'])
        {
          $data['security']     = $this->dashboard_Model->get_securitydetails($id);

          $decrypt_currentpass = decrypt(($data['security']->Password),ENCRYPTION_KEY);

          if ($get['currentPassword'] == $decrypt_currentpass)
          {
            $pass         = $get['newPassword'];
            // $pass         = encrypt($get['newPassword'],ENCRYPTION_KEY);
            $password     = $this->User_Model->get_password_setting();
            $pass_status  = 11;  //okay
            $error        = '';

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
              $this->dashboard_Model->securityUpdate($get, $id);//update to user table

              $notfirst           = array('FirstTimeLogin' => 2);
              $this->dashboard_Model->updateloginFlag($notfirst);

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

          $result['token']    = $data['csrf']['hash'];
          $result['status']   = $status;
          $result['message']  = $response;

          echo json_encode($result);
  }

  function forceChangePass(){
    $data = array_merge($this->global_data);

    $get = $this->input->post();
    $id = $get['userid'];
    // print_r($id);

    $this->form_validation->set_rules('currentPassword', 'Current Password', 'required');
    $this->form_validation->set_rules('newPassword', 'New Password', 'required');
    $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required');

    if($this->form_validation->run() == TRUE)
    {
      if ($get['newPassword'] && $get['newPassword'] != "" )
      {
        if ($get['confirmPassword'] && $get['newPassword'] == $get['confirmPassword'])
        {
          $data['security']     = $this->dashboard_Model->get_securitydetails($id);

          $decrypt_currentpass = decrypt(($data['security']->Password),ENCRYPTION_KEY);

          if ($get['currentPassword'] == $decrypt_currentpass)
          {
            $pass         = $get['newPassword'];
            $password     = $this->User_Model->get_password_setting();
            $pass_status  = 11;  //okay
            $error        = '';

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
              $this->dashboard_Model->securityUpdate($get, $id);

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

          $result['token']    = $data['csrf']['hash'];
          $result['status']   = $status;
          $result['message']  = $response;

          echo json_encode($result);
  }

  function graphVouchers(){

    $data  = array_merge($this->global_data);
    $role  = $this->dashboard_Model->user_role($this->session->userdata('UserId'));
    $store = $this->dashboard_Model->get_userCount($this->session->userdata('UserId'));

    // graph
		$result['dataA'] = [];
		$result['dataB'] = [];
		$result['dataC'] = [];
    $result['last']  = [];
    $result['start'] = [];

    if ($role->UserRoleId==1 || $store->StoreId== 0) {
      $result = $this->dashboard_Model->get_voucher_issued_today_today(0);
    }else {
      $result = $this->dashboard_Model->get_voucher_issued_today_today($store->StoreId);
    }

    $searchFor = 1;
    $filterbyone = array_values(array_filter($result, function($element) use($searchFor){
      return isset($element->VoucherActivityCategoryId) && $element->VoucherActivityCategoryId == $searchFor;
    }));

    $searchFor = 7;
    $filterbyseven = array_values(array_filter($result, function($element) use($searchFor){
      return isset($element->VoucherActivityCategoryId) && $element->VoucherActivityCategoryId == $searchFor;
    }));

    $combineA = array_merge($filterbyone, $filterbyseven);

    $searchFor = 3;
    $filterbythree = array_values(array_filter($result, function($element) use($searchFor){
      return isset($element->VoucherActivityCategoryId) && $element->VoucherActivityCategoryId == $searchFor;
    }));

    $searchFor = 5;
    $filterbyfive = array_values(array_filter($result, function($element) use($searchFor){
      return isset($element->VoucherActivityCategoryId) && $element->VoucherActivityCategoryId == $searchFor;
    }));

      for ($t=1; $t < 25; $t++) {
        $hour = date('H', strtotime($t.':00'));

        //ACTIVE
        $searchFor = $hour;
        $filterbyA = array_values(array_filter($combineA, function($element) use($searchFor){
          return isset($element->hour) && sprintf('%02d', $element->hour) == $searchFor;
        }));

        if (!empty($filterbyA)) {
          $result['dataA'][] = count($filterbyA);
        }else {
          $result['dataA'][] = 0;
        }

        //REDEEM
        $searchFor = $hour;
        $filterbyB = array_values(array_filter($filterbythree, function($element) use($searchFor){
          return isset($element->hour) && sprintf('%02d', $element->hour) == $searchFor;
        }));

        if (!empty($filterbyB)) {
          $result['dataB'][] = count($filterbyB);
        }else {
          $result['dataB'][] = 0;
        }

        //VOID
        $searchFor = $hour;
        $filterbyC = array_values(array_filter($filterbyfive, function($element) use($searchFor){
          return isset($element->hour) && sprintf('%02d', $element->hour) == $searchFor;
        }));

        if (!empty($filterbyC)) {
          $result['dataC'][] = count($filterbyC);
        }else {
          $result['dataC'][] = 0;
        }
  }
    $result['token']    = $data['csrf']['hash'];
    echo json_encode($result);
  }
}

 ?>
