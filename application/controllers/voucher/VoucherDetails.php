<?php
  class VoucherDetails extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('voucher/gift_Model');
      $this->load->model('voucher/voucher_Model');
      $this->load->model('voucher/gift_import_Model');
      $this->load->model('App_logger_model');
      $this->load->model('logs/VoucherLog_Model');
      $this->load->model('logs/ActivityLog_Model');

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

      $this->global_data['UserId']        = $this->session->userdata('UserId');
      $this->global_data['Fullname']      = $this->session->userdata('Fullname');
      $this->global_data['Role']          = $this->session->userdata('Role');
      $this->global_data['LoginId']       = $this->session->userdata('LoginId');

      $this->global_data['AppType']       = 2;
      $this->global_data['CreatedDate']   = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']     = $this->global_data['UserId'];
      $this->global_data['UpdatedDate']   = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']     = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function giftdetails()
    {
      $data['header']  = $this->load->view('templates/main-header',"",true);
      $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']  = $this->load->view('templates/main-footer',"",true);
      $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

      $data['GiftVouchersId']  = $this->uri->segment(4);
      $data['status']          = $this->gift_Model->get_giftstatus()->result();

      $data['statusUnblockPermission']  = $this->gift_Model->get_statusUnblockPermission()->result();

      $data['reasonBlock']     = $this->gift_Model->get_reasons_block()->result();
      $data['reasonCancel']    = $this->gift_Model->get_reasons_cancel()->result();
      $data['giftEdit']        = $this->gift_Model->get_giftEdit($data['GiftVouchersId']);
      $data['giftEdit2']       = $this->gift_Model->get_giftEdit2($data['GiftVouchersId']);
      $data['history']         = $this->gift_Model->get_voucher_history($data['GiftVouchersId'], $data['giftEdit']->VoucherStatusId);
      $data['permission']      = $this->gift_Model->get_permission($this->session->userdata('UserId'));
      $data['original']        = $this->gift_Model->get_originalgift($data['GiftVouchersId'],$data['giftEdit']->VoucherStatusId);

      $this->load->view('voucher/gift_voucher_details', $data);
    }

    function giftEdit()
    {
      $data  = array_merge($this->global_data);
      $get   = $this->input->post();
      $id    = $get['giftvoucherid'];
      $today = date('Y-m-d');

      $this->form_validation->set_rules('status', 'Status', 'required');

      if ($get['status'] == 1) {
        $this->form_validation->set_rules('expireddate', 'Expired Date', 'required');
      }

      if ($get['status'] == 3 ) {
        $this->form_validation->set_rules('extendDate', 'Extend Date', 'required');
      }

      if ($get['status'] == 5 ) {
        $this->form_validation->set_rules('canceldate', 'Cancel Date', 'required');
        $this->form_validation->set_rules('cancelReason', 'Cancel Reason', 'required');
      }

      if ($get['status'] == 6 ) {
        $this->form_validation->set_rules('blockDate', 'Block Date', 'required');
        $this->form_validation->set_rules('blockReason', 'Block Reason', 'required');
      }

      if ($get['status'] == 9 ) {
        $this->form_validation->set_rules('inactivedate', 'Inactive Date', 'required');
      }

      if($this->form_validation->run() == TRUE){

        $data['AppLoggerId'] = $get['loggerid'];

        $status = $get['status'];

        if ($get['status'] == 2) {
          $category = 9;
        } elseif ($get['status'] == 3) {
          $category = 8;
        } elseif ($get['status'] == 4) {
          $category = 5;
        } elseif ($get['status'] == 5) {
          $category = 10;
        } elseif ($get['status'] == 6) {
          $category = 2;
        } elseif ($get['status'] == 7) {
          $category = 3;
        } elseif ($get['status'] == 8) {
          $category = 6;
        } elseif ($get['status'] == 9) {
          $category = 11;
        } elseif ($get['status'] == 10) {
          $category = 12;
        }elseif ($get['status'] == 1) {
          $category = 7;
        }

        if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->gift_Model->giftEdit($get, $id))
          {
            $currentstatus = $this->gift_Model->currentstatus($get['status']);
            $voucherno     = $this->gift_Model->get_voucherno($get['giftvoucherid']);

            if ($get['status'] != 10 || $get['status'] != 1) {
              $this->gift_Model->voucherReason($id,$get,$data);
            }

            if ($get['status'] == 3) {
              $this->gift_Model->giftEdit_today($get, $id);
            }

            if ($get['status'] != 3) {
              if ($today == $get['blockDate'] || $today == $get['canceldate']) {
                $this->gift_Model->giftEdit_today($get, $id);
              }
            }

            $status     = true;
            $response   = "Gift Voucher has been updated.";
            $errorcode  = 200;
            $actmsg     = " ".$currentstatus. " Gift Vouchers ".$voucherno;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Gift Voucher ".$voucherno.". Failed.";
          }
        } else {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Gift Voucher ".$voucherno.". Applogger update failed.";
        }

        $act = [
          'UserId'                      => $data['UserId'],
          'VoucherActivityCategoryId'   => $category,
          'Details'                     => $data['Fullname'].$actmsg,
          'ActivityDate'                => date('Y-m-d H:i:s'),
          'VouchersId'                  => $get['giftvoucherid'],
          'VouchersNumber'              => $get['vouchersnumber'],
          'VoucherTypeId'               => $get['vouchertypeid'],
          'Source'                      => 2,
        ];

        $this->VoucherLog_Model->insert_activity($act);
    } else {
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

    function giftdetailsform()
    {
      $data    = array_merge($this->global_data);
      $get     = $this->input->post();
      $details = $get['giftDetails'];

      if($this->form_validation->run() == TRUE){

        $data['AppLoggerId'] = $get['loggerid'];
        $this->App_logger_model->update_app_logger_data($data);

        $this->gift_Model->giftDetails($get,$details);

        $status     = true;
        $response   = "Gift Voucher Details has been updated.";
        $errorcode  = 200;
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

    function promotiondetails()
    {
      $data['header']      = $this->load->view('templates/main-header',"",true);
      $data['topbar']      = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']     = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']      = $this->load->view('templates/main-footer',"",true);
      $data['bottom']      = $this->load->view('templates/main-bottom',"",true);

      $data['VoucherId']   = $this->uri->segment(4);
      $data['status']      = $this->voucher_Model->get_status()->result();

      $data['statusUnblockPermission']  = $this->voucher_Model->get_statusUnblockPermission()->result();

      $data['reasonBlock']    = $this->voucher_Model->get_reasons_block()->result();
      $data['reasonCancel']   = $this->voucher_Model->get_reasons_cancel()->result();
      $data['voucher']        = $this->voucher_Model->get_voucher($data['VoucherId']);
      $data['card']           = $this->voucher_Model->get_card($data['voucher']->VoucherIssuanceId);
      $data['history']        = $this->voucher_Model->get_voucher_history($data['VoucherId'],$data['voucher']->VoucherStatusId);
      $data['original']       = $this->voucher_Model->get_originalvoucher($data['VoucherId'],$data['voucher']->VoucherStatusId);
      $data['permission']     = $this->voucher_Model->get_permission($this->session->userdata('UserId'));

      $this->load->view('voucher/promotion_voucher_details', $data);
    }

    function promotionEdit(){

      $data   = array_merge($this->global_data);
      $get    = $this->input->post();
      $id     = $get['voucherId'];
      $today  = date('Y-m-d');

      $this->form_validation->set_rules('status', 'Status', 'required');

      if ($get['status'] == 3 ) {
        $this->form_validation->set_rules('extenddate', 'Extend Date', 'required');
      }

      if ($get['status'] == 5 ) {
        $this->form_validation->set_rules('canceldate', 'Cancel Date', 'required');
        $this->form_validation->set_rules('cancelReason', 'Cancel Reason', 'required');
      }

      if ($get['status'] == 6 ) {
        $this->form_validation->set_rules('blockDate', 'Block Date', 'required');
        $this->form_validation->set_rules('blockReason', 'Block Reason', 'required');
      }

      if($this->form_validation->run() == TRUE){

        $data['AppLoggerId'] = $get['loggerid'];

        $status = $get['status'];

        if ($get['status'] == 2) {
          $category = 9;
        } elseif ($get['status'] == 3) {
          $category = 8;
        } elseif ($get['status'] == 4) {
          $category = 5;
        } elseif ($get['status'] == 5) {
          $category = 10;
        } elseif ($get['status'] == 6) {
          $category = 2;
        } elseif ($get['status'] == 7) {
          $category = 3;
        } elseif ($get['status'] == 8) {
          $category = 6;
        } elseif ($get['status'] == 9) {
          $category = 11;
        } elseif ($get['status'] == 10) {
          $category = 12;
        }

        if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->voucher_Model->voucherEdit($get, $id))
          {
            $currentstatus = $this->voucher_Model->currentstatus($get['status']);
            $voucherno     = $this->voucher_Model->get_voucherno($get['voucherId']);

            if ($get['status'] != 3) {
              $this->voucher_Model->voucherReason($id,$get,$data);
            }else {
              $this->voucher_Model->voucherEdit_today($get, $id);
              $this->voucher_Model->voucherReason($id,$get,$data);
            }

            if ($get['status'] != 3) {
              if ($today == $get['blockDate'] || $today == $get['canceldate']) {
                $this->voucher_Model->voucherEdit_today($get, $id);
              }
            }

            $status     = true;
            $response   = "Vouchers has been updated.";
            $errorcode  = 200;
            $actmsg     = " ".$currentstatus. " Vouchers ".$voucherno;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Vouchers ID ".$voucherno.". Failed.";
          }
        } else {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Vouchers ID ".$voucherno.". Applogger update failed.";
        }

        $act = [
          'UserId'                      => $data['UserId'],
          'VoucherActivityCategoryId'   => $category,
          'Details'                     => $data['Fullname'].$actmsg,
          'ActivityDate'                => date('Y-m-d H:i:s'),
          'VouchersId'                  => $get['voucherid'],
          'VouchersNumber'              => $get['vouchersnumber'],
          'VoucherTypeId'               => $get['vouchertypeid'],
          'Source'                      => 2,
        ];

        $this->VoucherLog_Model->insert_activity($act);
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

    function editdetails2(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();
      $id = $get['voucherId'];

      if (isset($get['remark']) && $get['remark'] != "") {
        $this->voucher_Model->voucherDetailsEdit($get, $id);

        $status     = true;
        $response   = "Voucher has been updated.";
        $errorcode  = 200;
      }
      else{
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

    function errorLog_details(){
      $data = array_merge($this->global_data);

      $data['header']  = $this->load->view('templates/main-header',"",true);
      $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']  = $this->load->view('templates/main-footer',"",true);
      $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

      $data['CSVFileId']  = $this->uri->segment(4);

      $this->load->view('voucher/giftvoucher_errorLog_details', $data);
    }

    function scheduler_giftvoucher_errorLog(){
      $data = array_merge($this->global_data);
      $post = $this->input->post();
      $id   = $this->uri->segment(4);

      $list  = $this->gift_import_Model->get_errorlog_datatables($post,$id);
      $table = array();
      $no    = $post['start'];

      foreach ($list as $field) {

        $no++;

        $row   = array();
        $row[] = $field->VoucherNumber;
        $row[] = date('Y-m-d H:i:s', strtotime($field->ImportDate));;
        $row[] = $field->ErrorMessage;

        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->gift_import_Model->errorlog_count_all($post,$id),
        "recordsFiltered"     => $this->gift_import_Model->errorlog_count_filtered($post,$id),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }
  }
 ?>
