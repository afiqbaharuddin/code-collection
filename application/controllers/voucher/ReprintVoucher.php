<?php

class ReprintVoucher extends CI_Controller{

  function __construct()  {
    parent::__construct();
    $this->load->model('voucher/reprintVoucher_Model');
    $this->load->model('logs/ActivityLog_Model');
    $this->load->model('logs/VoucherLog_Model');
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

  function reprintvoucher()
  {
    $data['header']    = $this->load->view('templates/main-header',"",true);
    $data['topbar']    = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']   = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']    = $this->load->view('templates/main-footer',"",true);
    $data['bottom']    = $this->load->view('templates/main-bottom',"",true);

    $UserId= $this->session->userdata('UserId');

    $data['permission'] = $this->reprintVoucher_Model->get_permission($this->session->userdata('UserId'));
    $data['store']      = $this->reprintVoucher_Model->get_storeAdmin();
    $data['reprint']    = $this->reprintVoucher_Model->get_reprintdetails($UserId);

    if (isset($data['reprint'])) {
      $data['posdb']     = $this->reprintVoucher_Model->get_posReprint($data['reprint']->StoreId);

    }else {
      $data['posdb']     = [];
    }

    //reprint check to show message
    $data['reprintAllowVoucherSetting']  = $this->reprintVoucher_Model->get_reprintAllowVoucherSetting($this->session->userdata('UserId'));
    $data['reprintValidation']          = $this->reprintVoucher_Model->get_reprintValidation();

    $this->load->view('voucher/reprint_voucher', $data);
  }

  function get_pos(){
    $data = array_merge($this->global_data);

    $storecode = $this->input->post('storecode');
    $user = $this->reprintVoucher_Model->get_user_reprint();

    $pos = $this->reprintVoucher_Model->get_pos($storecode, $user);

    $list = '';
    foreach ($pos as $row) {
      $list .= '<option value="'.$row->POSId.'">'.$row->POSNumber.'</option>';
    }

    $result['token']   = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }

  function reprintvoucherForm() {

    $data = array_merge($this->global_data);
    $get  = $this->input->post();
    $id   = $get['layoutid'];
    $user = $this->reprintVoucher_Model->get_user();

    $UserId = $this->session->userdata('UserId');
    $this->form_validation->set_rules('posNumber', 'POS Number', 'required');
    $this->form_validation->set_rules('receiptNumber', 'Receipt Number', 'required');

    $posReceiptNum = implode(array("-",$get["receiptposnumber"]));
    $rn            = $get['receiptstorecode'].$posReceiptNum.$get['receiptNumber'];
    $count         = $this->reprintVoucher_Model->get_reprintNumCheck($rn);

    $reprintSetting = $this->reprintVoucher_Model->reprintSetting($get['storeid']);
    $reprint        = 0;
    $permission     = $this->reprintVoucher_Model->get_permission($this->session->userdata('UserId'));

    if($this->form_validation->run() == TRUE){

      $checkreceipt = $this->reprintVoucher_Model->get_receiptchecking($rn);

      if ($count < $reprintSetting->NumReprint)
      {
        if ($checkreceipt != null)
        {
          if (date('Y-m-d', strtotime($checkreceipt->ReceiptDateTime)) == date('Y-m-d')) {
            $get['VoucherIssuanceId'] = $checkreceipt->VoucherIssuanceId;
            $statusvoucher = $this->reprintVoucher_Model->get_status($get['VoucherIssuanceId']);

            if ($permission->UserRoleId == 1 || $get['storeid'] == $checkreceipt->IssuanceStoreId)
            {
              if ($get['posNumber'] == $checkreceipt->IssuancePOSId)
              {
                if ($statusvoucher->VoucherStatusId != 1 && $statusvoucher->VoucherStatusId != 3) {
                  if ($statusvoucher->VoucherStatusId == 4) {
                    $status     = false;
                    $response   = 'Receipt Number has been Void!. The voucher is not allowed to Reprint ';
                    $errorcode  = 400;
                    $actmsg     = " is trying to Reprint Voucher. Receipt Number is Void.";
                  }
                  elseif ($statusvoucher->VoucherStatusId == 5) {
                    $status     = false;
                    $response   = 'Receipt Number has been Cancelled!. The voucher is not allowed to Reprint ';
                    $errorcode  = 400;
                    $actmsg     = " is trying to Reprint Voucher. Receipt Number is Cancelled.";
                  }
                  elseif ($statusvoucher->VoucherStatusId == 6) {
                    $status     = false;
                    $response   = 'Receipt Number has been Blocked!. The voucher is not allowed to Reprint ';
                    $errorcode  = 400;
                    $actmsg     = " is trying to Reprint Voucher. Receipt Number is Blocked.";
                  }
                  elseif ($statusvoucher->VoucherStatusId == 7) {
                    $status     = false;
                    $response   = 'Receipt Number has been Redeemed!. The voucher is not allowed to Reprint ';
                    $errorcode  = 400;
                    $actmsg     = " is trying to Reprint Voucher. Receipt Number is Redeemed.";
                  }elseif ($statusvoucher->VoucherStatusId == 2) {
                    $status     = false;
                    $response   = 'Receipt Number is Expired!. The voucher is not allowed to Reprint ';
                    $errorcode  = 400;
                    $actmsg     = " is trying to Reprint Voucher. Receipt Number is Expired.";
                  }
                }
                else {

                  $get['AppLoggerId'] = $this->App_logger_model->insert_app_logger_data($data);
                  $this->reprintVoucher_Model->reprintVoucher($get,$rn);
                  $allvoucher = $this->reprintVoucher_Model->getreprintVoucher($get['VoucherIssuanceId']);

                  $reprint   = $checkreceipt->VoucherIssuanceId;
                  $status    = true;
                  $response  = "Reprint voucher success.";
                  $errorcode = 200;
                  $actmsg    = " Successfully Reprint Voucher. Receipt Number ".$checkreceipt->ReceiptNumber. ". Total sales is: RM " .$checkreceipt->TotalSales;
                }
              }
              else
              {
                $status     = false;
                $response   = 'POS Number is not valid';
                $errorcode  = 400;
                $actmsg     = " is trying to Reprint Voucher with Invalid POS Number. Failed.";
              }
            }
            else {
              $status     = false;
              $response   = 'Store is not valid';
              $errorcode  = 400;
              $actmsg     = " is trying to Reprint Voucher with Invalid Store. Failed.";
            }
          }else {
            $status     = false;
            $response   = 'The Voucher is not issued on Today';
            $errorcode  = 400;
            $actmsg     = " is trying to Reprint Voucher with Invalid Issued Date Today. Failed.";
          }
        }
        else
        {
          $status     = false;
          $response   = 'Receipt Number not Found ';
          $errorcode  = 400;
          $actmsg     = " is trying to Reprint Voucher. Receipt Number not Found.";
        }
      }
     else
     {
         $status     = false;
         $response   = 'Receipt Number has exceed the Reprint Allowed! <br> This voucher can only be Reprint '.$reprintSetting->NumReprint.' times';
         $errorcode  = 400;
         $actmsg     = " is trying to Reprint Voucher. Receipt Number has Exceed Reprint Allowed.";
    }

      if ($status == true) {
        foreach ($allvoucher as $row) {
          $act = [
            'UserId'                      => $data['UserId'],
            'VoucherActivityCategoryId'   => 13,
            'Details'                     => $data['Fullname'].' '.$actmsg,
            'ActivityDate'                => date('Y-m-d H:i:s'),
            'VouchersId'                  => $row->VoucherId,
            'VouchersNumber'              => $row->VouchersNumber,
            'VoucherTypeId'               => $row->VoucherTypeId,
            'Source'                      => 2,
          ];

          $this->VoucherLog_Model->insert_activity($act);
        }
      }
        else {
          $act = [
            'UserId'                      => $data['UserId'],
            'VoucherActivityCategoryId'   => 13,
            'Details'                     => $data['Fullname'].' '.$actmsg,
            'ActivityDate'                => date('Y-m-d H:i:s'),
            'VouchersId'                  => '',
            'VouchersNumber'              => '',
            'VoucherTypeId'               => '',
            'Source'                      => 2,
          ];

          $this->VoucherLog_Model->insert_activity($act);
        }
      }
      else
          {
            $error = $this->form_validation->error_array();
            $salah = '';
            foreach ($error as $keye) {
              $salah .= $keye.'<br>';
            }

            $status    = false;
            $response  = $salah;
            $errorcode = 400;
          }

      $result['token']           = $data['csrf']['hash'];
      $result['status']          = $status;
      $result['message']         = $response;
      $result['successreprint']  = $reprint;

      echo json_encode($result);
      }
    }
 ?>
