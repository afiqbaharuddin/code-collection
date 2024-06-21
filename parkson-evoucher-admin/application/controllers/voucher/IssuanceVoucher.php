<?php

class IssuanceVoucher extends CI_Controller
{
  function __construct() {
    parent:: __construct();
    $this->load->model('voucher/reprintVoucher_Model');
    $this->load->model('settings/PassSettings_model');
    $this->load->model('logs/VoucherLog_Model');
    $this->load->model('logs/ActivityLog_Model');
    $this->load->model('App_logger_model');
    $this->load->model('voucher/voucherLayout_Model');

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

    $this->global_data['UserId']       = $this->session->userdata('UserId');
    $this->global_data['Fullname']     = $this->session->userdata('Fullname');
    $this->global_data['Role']         = $this->session->userdata('Role');

    $this->global_data['AppType']      = 2;
    $this->global_data['CreatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']    = $this->global_data['UserId'];
    $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
  }

  function issuancevoucher()
  {
    $data = array_merge($this->global_data);

    $data['header']           = $this->load->view('templates/main-header',"",true);
    $data['topbar']           = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']          = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']           = $this->load->view('templates/main-footer',"",true);
    $data['bottom']           = $this->load->view('templates/main-bottom',"",true);

    $UserId                   = $this->session->userdata('UserId');
    $UserRoleId               = $this->session->userdata('Role');

    $data['issuanceredeem']   = $this->reprintVoucher_Model->get_issuanceredeem()->result();
    $data['voucherdb']        = $this->reprintVoucher_Model->get_vouchertype()->result();
    $data['vouchervalue']     = $this->reprintVoucher_Model->get_vouchervalue()->result();

    $data['adminstore']       = $this->reprintVoucher_Model->get_store()->result();// for admin to selct store
    // $data['adminpos']         = $this->reprintVoucher_Model->get_posadmin();

    $data['data']               = $this->reprintVoucher_Model->get_issuancestore($UserId);
    if (isset($data['data'])) {
      $data['posissuance']      = $this->reprintVoucher_Model->get_issuancepos($data['data']->StoreId);
      $data['campaign']         = $this->reprintVoucher_Model->get_campaign($data['data']->StoreId);
    }else {
      $data['posissuance']      = [];
      $data['campaign']         = [];
    }

    $data['campaignValidation']  = $this->reprintVoucher_Model->get_campaignValidation();
    $data['issuanceValidation']  = $this->reprintVoucher_Model->issuanceValidation($this->session->userdata('UserId'));

    $this->load->view('voucher/issuanceVoucher', $data);
  }

  //for print successful reprint voucher page!!
  function list()
  {
    $data['header']       = $this->load->view('templates/main-header',"",true);
    $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']       = $this->load->view('templates/main-footer',"",true);
    $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

    $data['reprintid'] = $this->uri->segment(4);
    $data['sucessReprint']= $this->reprintVoucher_Model->get_reprintSuccess($data['reprintid']);

    $this->load->view('voucher/issuanceVoucherList', $data);
  }

  function reprint()
  {
    $data = array_merge($this->global_data);

    $data['header']       = $this->load->view('templates/main-header',"",true);
    $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']       = $this->load->view('templates/main-footer',"",true);
    $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

    $data['reprintid'] = $this->uri->segment(4);
    $data['sucessReprint']= $this->reprintVoucher_Model->get_reprintSuccess($data['reprintid']);

    foreach ($data['sucessReprint'] as $row) {
      $array = [
         'VoucherId'       => $row->VoucherId,
         'PrintedDate'     => date('Y-m-d H:i:s'),
         'PrintedBy'       => $data['UserId'],
     ];
     $this->voucherLayout_Model->insertlog($array);
    }

    $this->load->view('voucher/issuanceVoucherList', $data);
  }

  function posStoreAdmin(){
    $data = array_merge($this->global_data);

    $id= $this->input->post('id');
    $result['pos']= $this->reprintVoucher_Model->get_posadmin($id)->result();

    $result['token']    = $data['csrf']['hash'];
    echo json_encode($result);

  }

  function get_pos(){
    $data = array_merge($this->global_data);

    $storecode = $this->input->post('storecode');
    // $pos = $this->reprintVoucher_Model->get_pos($storecode);
    $pos = $this->reprintVoucher_Model->get_posAdminissuance($storecode);

    $list = '';
    foreach ($pos as $row) {
      $list .= '<option value="'.$row->POSNumber.'">'.$row->POSNumber.'</option>';
    }

    $result['token']    = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }

  function get_campaign(){
    $data = array_merge($this->global_data);

    $storecode = $this->input->post('storecode');

    $camstore = $this->reprintVoucher_Model->get_campaign_store($storecode);
    $camid = [];
    foreach ($camstore as $row) {
      $camid[] = $row->CampaignId;
    }
    $store = $this->reprintVoucher_Model->get_campaign_admin($storecode);

    $list = '';
    foreach ($store as $row) {

      $searchFor = $row->CampaignId;
      $filterbycam =
      array_values(array_filter($camstore, function($element) use($searchFor){
        return isset($element->CampaignId) && $element->CampaignId == $searchFor;
      }));
      if (!empty($filterbycam)) {
        $list .= '<option value="'.$row->CampaignId.'">'.$row->CampaignName.'</option>';
      }
    }

    $result['token']    = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }

  function get_vouchertype(){
    $data = array_merge($this->global_data);

    $campaign = $this->input->post('campaign');
    // print_r($campaign);die;
    $type = $this->reprintVoucher_Model->get_campaign_type($campaign);

    $list = '';
    foreach ($type as $row) {
      $list .= '<option value="'.$row->VoucherTypeId.'">'.$row->VoucherName.'</option>';
    }

    $result['token']    = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }


  function get_value(){

    $data = array_merge($this->global_data);
    $get = $this->input->post();

    $voucher = $this->input->post('vouchertype');
    $value   = $this->reprintVoucher_Model->get_value($voucher);
    // print_r($value->VoucherValue);
    $listing = explode(",", $value->VoucherValue);
    $list = '';

      foreach ($listing as $row) {

          $list .= '<option value="'.$row.'">'.$row.'</option>';
      }

    $result['token']   = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }

  function get_value_new_row(){

    $data = array_merge($this->global_data);
    $get = $this->input->post();

    $voucher = $this->input->post('voucherTypenew');
    $value   = $this->reprintVoucher_Model->get_value($voucher);

    $listing = explode(",", $value->VoucherValue);
    $list = '';

      foreach ($listing as $row) {
        $list .= '<option value="'.$row.'">'.$row.'</option>';
      }

    $result['token']   = $data['csrf']['hash'];
    $result['result']  = $list;
    echo json_encode($result);
  }

  function manualIssuance(){
    $data = array_merge($this->global_data);

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    $get  = $this->input->post();

    $UserId = $this->session->userdata('UserId');
    $RoleId = $this->session->userdata('Role');
    $role   = $get['userroleid'];

    if ($get['inputvalidation'] == 'no') {
      $this->form_validation->set_rules('date', 'Receipt Date', 'required');
      $this->form_validation->set_rules('time', 'Receipt Time', 'required');
      $this->form_validation->set_rules('pos', 'POS Number', 'required');
      $this->form_validation->set_rules('receiptNumber', 'Receipt Number', 'required|min_length[9]|max_length[9]');

      if($this->form_validation->run() == TRUE){
        $posReceiptNum = implode(array("-",$get["receiptposnumber"]));
        $receiptnumber = $get['receiptstorecode'].$posReceiptNum.$get["receiptNumber"];

        $store         = $get['storecodeadmin'];
        $pos           = $get['pos'];

        $countStore    = $this->reprintVoucher_Model->count_storeIssuance($store, $pos, $receiptnumber);

        if ($countStore !=0 ) {
          $message = 'Receipt Number already issued for this Store!!';
          $status    = false;
          $response  = $message;
          $errorcode = 400;
          $actmsg    = " trying to manually issue voucher. Receipt Number ".$receiptnumber." already issued. Failed.";

          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 38,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
        }else {
          $message = 'Receipt number valid';
          $status    = true;
          $response  = $message;
          $errorcode = 200;
        }
      }else {
        $error = $this->form_validation->error_array();
        $salah = '';
        foreach ($error as $keye) {
          $salah .= $keye.'<br>';
        }

        $status    = false;
        $response  = $salah;
        $errorcode = 400;
      }
    }else {

      $this->form_validation->set_rules('date', 'Receipt Date', 'required');
      $this->form_validation->set_rules('time', 'Receipt Time', 'required');
      $this->form_validation->set_rules('pos', 'POS Number', 'required');

      $this->form_validation->set_rules('receiptNumber', 'Receipt Number', 'required|min_length[9]|max_length[9]');

      $this->form_validation->set_rules('cosmeticsale', 'Cosmetic Sale', 'required');
      $this->form_validation->set_rules('totalsales', 'Total Sales', 'required');

      $campaignValidation = $this->reprintVoucher_Model->campaignValidation();

      if ($campaignValidation->CampaignValidationCheck == 'N') {
        $this->form_validation->set_rules('redeemtype', 'Redeem Type', 'required');
      }

      if($this->form_validation->run() == TRUE){

        $reprint = 0;
        $terminal = base64_encode($get['storecodeadmin'].'+'.$get['pos']);

        if (isset($get['redeemtype'])) {
          if ($get['redeemtype'] == 1) {
            $redeem = "L";
          }else {
            $redeem = "N";
          }
        }else {
          $campaign_redeemtype = $this->reprintVoucher_Model->campaign_redeemtype($get['storecodeadmin'], $get['campaign']);
          if (isset($campaign_redeemtype)) {
            if ($campaign_redeemtype->RedeemTypeId == 1) {
              $redeem = "L";
            }else {
              $redeem = "N";
            }
          }else {
            $redeem = "L";
          }
        }

        $voucher = [];
        for ($i=1; $i < $get['totalrow']+1; $i++) {
          if (isset($get['vouchertype'.$i]) && isset($get['vouchervalue'.$i])) {
            $vouchertype = $this->reprintVoucher_Model->get_voucher_type($get['vouchertype'.$i]);

            if (isset($vouchertype)) {
              $type = $vouchertype->VoucherShortName;
            }else {
              $type = 0;
            }

            if (isset($get['vouchervalue'.$i])) {
              $val = $get['vouchervalue'.$i];
            }else {
              $val = 0;
            }

            if (isset($get['quantity'.$i])) {
              $qty = $get['quantity'.$i];
            }else {
              $qty = 0;
            }

            $voucher[] = [
              'redeemType'   => $redeem,
              'voucherType'  => $type,
              'voucherValue' => $val,
              'voucherQty'   => $qty,
            ];
          }
        }

        $posReceiptNum = implode(array("-",$get["receiptposnumber"]));
        $receiptnumber = $get['receiptstorecode'].$posReceiptNum.$get["receiptNumber"];
        $store         = $get['storecodeadmin'];
        $pos           = $get['pos'];

        $countStore    = $this->reprintVoucher_Model->count_storeIssuance($store ,$store, $pos);

        if ($countStore !=0 ) {

            $message = ' Receipt Number already issued for this store';
            $response  = $message;
            $errorcode = 400;
            $actmsg    = " trying to manually issue voucher. Receipt Number ".$receiptnumber." already issued. Failed.";
        }else {

        if ($get['redeemtype']) {
          $payload = [
            'requestTime'     => strtotime(date('Y-m-d H:i:s')),
            'signature'       => base64_encode(strtotime(date('Y-m-d H:i:s'))),
            'storeCode'       => $get['storecodeadmin'],
            'posNumber'       => $get['pos'],
            'cashierID'       => '',
            'loginID'         => $UserId,
            'RedeemTypeId'    => $get['redeemtype'],
            'receiptDateTime' => $get['date'].' '.date('H:i:s', strtotime($get['time'])),
            'receiptNumber'   => $receiptnumber ,
            'totalSales'      => $get['totalsales'],
            'cosmeticSales'   => $get['cosmeticsale'],
            'vouchers'        => $voucher
          ];
        }else {
          $payload = [
            'requestTime'     => strtotime(date('Y-m-d H:i:s')),
            'signature'       => base64_encode(strtotime(date('Y-m-d H:i:s'))),
            'storeCode'       => $get['storecodeadmin'],
            'posNumber'       => $get['pos'],
            'cashierID'       => '',
            'loginID'         => $UserId,
            'campaignID'      => $get['campaign'],
            'receiptDateTime' => $get['date'].' '.date('H:i:s', strtotime($get['time'])),
            'receiptNumber'   => $receiptnumber ,
            'totalSales'      => $get['totalsales'],
            'cosmeticSales'   => $get['cosmeticsale'],
            'vouchers'        => $voucher
          ];
        }

        $generated = $this->reprintVoucher_Model->generate_voucher($terminal,json_encode($payload));
        // print_r($generated);die;
        if ($generated->status == 0) {
          $status    = true;
          $response  = $generated->issuanceid;
          $errorcode = 200;
          $actmsg    = " manually issued voucher. Successful. Receipt Number ".$receiptnumber;
        }else {
          $status    = false;
          if ($generated->message == "INVALID_VOUCHER_QTY") {
            $response = 'Voucher Quantity cannot be Zero or Empty';
          }elseif ($generated->message == "VOUCHER_EMPTY") {
            $response = 'Voucher Type is Required';
          }elseif ($generated->message == "INVALID_VOUCHER_TYPE") {
            $response = 'Voucher Type is Required';
          }else {
            $response  = $generated->message;
          }
          
          if ($generated->message == "INVALID_VOUCHER_QTY") {
            $errorcode = 400;
            $actmsg    = " trying to manually issue voucher. User left Voucher Quantity Zero or Empty. Receipt Number ".$receiptnumber;
          }elseif ($generated->message == "VOUCHER_EMPTY") {
            $errorcode = 400;
            $actmsg    = " trying to manually issue voucher. User left Voucher Type line Empty. Receipt Number ".$receiptnumber;
          }elseif ($generated->message == "INVALID_VOUCHER_TYPE") {
            $errorcode = 400;
            $actmsg    = " trying to manually issue voucher. User did not select the Voucher Type. Receipt Number ".$receiptnumber;
          }else {
            $errorcode = 400;
          }
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 38,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }
    }else {
        $error = $this->form_validation->error_array();
        $salah = '';
        foreach ($error as $keye) {
          $salah .= $keye.'<br>';
        }

        $status    = false;
        $response  = $salah;
        $errorcode = 400;
      }
    }

    $result['token']           = $data['csrf']['hash'];
    $result['status']          = $status;
    $result['message']         = $response;
    $result['successreprint']  = $response;

    echo json_encode($result);
  }
}
 ?>
