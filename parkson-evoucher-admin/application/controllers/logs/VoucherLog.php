<?php
  class VoucherLog extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('logs/VoucherLog_Model');

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
      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    	$this->global_data['CreatedBy']   = $this->global_data['UserId'];
    	$this->global_data['EditedDate']  = date("Y-m-d H:i:s", time() );
    	$this->global_data['EditedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function voucher()
    {
      $data = array_merge($this->global_data);

      $data['header']       = $this->load->view('templates/main-header',"",true);
      $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']       = $this->load->view('templates/main-footer',"",true);
      $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

      $data['role']         = $this->VoucherLog_Model->get_role()->result();
      $data['category']     = $this->VoucherLog_Model->get_category()->result();
      $data['username']     = $this->VoucherLog_Model->get_username()->result();

      $this->load->view('logs/voucher_log', $data);
    }

    public function voucherloglisting(){

      $data = array_merge($this->global_data);
      $post  = $this->input->post();

      $permission = $this->VoucherLog_Model->get_permissionvoucherlog($this->session->userdata('UserId'));
      $userid     = $this->VoucherLog_Model->userstore($this->session->userdata('UserId'));

      $list  = $this->VoucherLog_Model->get_datatables($post,$userid->UserId,$permission);
      $table = array();
      $no    = $post['start'];

      $this->session->set_userdata('Filter_Role',$post['filter_role']);
      $this->session->set_userdata('Filter_Name',$post['filter_name']);
      $this->session->set_userdata('Filter_VoucherNumber',$post['filter_voucherno']);
      $this->session->set_userdata('Filter_Category',$post['filter_category']);
      $this->session->set_userdata('Filter_DateFrom',$post['date_from']);
      $this->session->set_userdata('Filter_DateTo',$post['date_to']);

      foreach ($list as $field) {

        $no++;
        $row   = array();
        // $row[] = $no;
        $row[] = $field->Fullname;
        $row[] = $field->Role;
        $row[] = date('Y-m-d H:i:s', strtotime($field->ActivityDate));
        $row[] = $field->VoucherActivityCategoryName;
        $row[] = $field->Details;

        $row[] = '<td>
                    <a href="#" class="details" data-voucherissuanceid="'.$field->VoucherLogsId.'" data-type="'.$field->VoucherTypeId.'">'.$field->VouchersNumber.'</a>
                  </td>';

        $row[] = $field->VoucherName;
        $table[] = $row;
      }

      $totalrecords  = $this->VoucherLog_Model->count_all($post,$userid->UserId,$permission);

      $output = array(
        "draw"                => $post['draw'],
        // "recordsTotal"        => $this->VoucherLog_Model->count_all($post,$userid->UserId,$permission),
        // "recordsFiltered"     => $this->VoucherLog_Model->count_filtered($post,$userid->UserId,$permission),
        "recordsFiltered"     => $totalrecords,
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function voucherDetails() {

      $data = array_merge($this->global_data);
      $get  = $this->input->post();

      if ($get['type'] != 1) {
        $details = $this->VoucherLog_Model->get_voucherdetails($get['voucherissuanceid']);
        $redeem  = $this->VoucherLog_Model->get_redeemreceipt($details->VouchersId,3);

        $type            = $this->VoucherLog_Model->get_issuance($details->VoucherIssuanceId);
        $card            = $this->VoucherLog_Model->get_card($details->VoucherIssuanceId);

        $receiptno       = $type->ReceiptNumber;
        $receiptdatetime = $type->ReceiptDateTime;
        $oripos          = $type->POSNumber;

        $cardnumber = '';
        $cardid     = '';

        foreach ($card as $row) {
          if (isset($row->CardNumber) != null) {
            $cardnumber .= $row->CardNumber. ', ';
          } else {
            $cardnumber = '';
          }
          if (isset($row->CardId)  != null) {
            $cardid .= $row->CardId. ' - ' .$row->CardName. ', ';
          } else {
            $cardid = '';
          }
        }

      } else {
        $details = $this->VoucherLog_Model->get_voucherdetailsgift($get['voucherissuanceid']);
        $redeem  = $this->VoucherLog_Model->get_redeemreceipt($details->GiftVouchersId,3);
        $type    = $this->VoucherLog_Model->get_gift($details->GiftVoucherIssuanceId);

        $receiptno       = $type->ReceiptNumber;
        $receiptdatetime = $type->ReceiptDateTime;
        $oripos          = $type->POSNumber;
        $cardnumber      = '';
        $cardid          = '';
      }

      if ($details->PosId == null) {
        $pos       = $this->VoucherLog_Model->get_voucher_pos($details->IssuancePOSId);
        $posnumber = $pos->POSNumber;
      } else {
        if ($details->VoucherTypeId == 1) {
          if ($details->VoucherActivityCategoryId != 14) {
            $pos       = $this->VoucherLog_Model->get_voucher_pos($details->RedemptionPos);
            $posnumber = $pos->POSNumber;
          }else {
            $posnumber = '';
          }
        } else {
          $pos       = $this->VoucherLog_Model->get_voucher_pos($details->PosId);
          $posnumber = $pos->POSNumber;
        }
      }

      if ($details->VoucherActivityCategoryId == 3) {
        $posnumber       = $pos->POSNumber;
        $redemptionstore = $details->RedemptionStore;
        $redemptionby    = $details->RedemptionBy;
        $datetime        = $details->RedemptionDateTime;
      } else {
        $posnumber       = '';
        $redemptionstore = '';
        $redemptionby    = '';
        $datetime        = '';
      }

      $result['details'] = [
        'AppLoggerId'              => $details->AppLoggerId,
        'VouchersNumber'           => $details->VouchersNumber,
        'RealReceiptNumber'        => $details->RealReceiptNumber,
        'RealReceiptDateTime'      => $details->RealReceiptDateTime,
        'OriginalReceiptNumber'    => $receiptno,
        'OriginalReceiptDateTime'  => $receiptdatetime,
        'RealPOSNumber'            => $posnumber,
        'RedemptionStore'          => $redemptionstore,
        'CreatedDate'              => $details->CreatedDate,
        'RedemptionDateTime'       => $datetime,
        'RedemptionBy'             => $redemptionby,
        'CardNumber'               => $cardnumber,
        'CardId'                   => $cardid,
        'OriginalPOSNumber'        => $oripos,
        'VoucherIssuanceId'        => $details->VoucherIssuanceId,
        'ReceiptNumber'            => $redeem,

        'VoucherActivityCategoryId' => $details->VoucherActivityCategoryId,
      ];


      $result['token']   = $data['csrf']['hash'];

      echo json_encode($result);
    }

    public function update_faq_post()
    {
      $import              = $this->input->post();
      $data['AppLoggerId'] = $data['AppLoggerId'];
      $data['UpdatedBy']   = $data['UserId'];
      $data['UpdatedDate'] = date("Y-m-d H:i:s", time() );

      if ($this->App_logger_model->update_app_logger_data($data)) {
        $sendata = array(
          'FaqQuestion'   => $data['FaqQuestion'],
          'FaqAnswer'     => $data['FaqAnswer'],
          'FaqCategoryId' => $data['FaqCategoryId'],
          'StatusId'      => $data['StatusId'],
        );

        if ($this->Faq_model->update_faq($sendata,$data['FaqId']))
        {
          $status    = true;
          $response  = [ ];
          $errorcode = 200;
          $actmsg    = " update FAQ ID ".$data['FaqId'];
        } else {
          $status    = false;
          $response  = [
            "type"  => "authentication",
            "error" => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg = " is trying to update FAQ ID ".$data['FaqId'].". Failed.";
        }
        } else
        {
          $status    = false;
          $response  = [
            "type"  => "authentication",
            "error" => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg = " is trying to update FAQ ID ".$data['FaqId'].". Applogger update failed.";
        }
        $act = [
          'UserId'                      => $data['UserId'],
          'VoucherActivityCategoryId'   => 13,
          'Details'                     => $data['Fullname'].$actmsg,
          'ActivityDate'                => date('Y-m-d H:i:s'),
          'VouchersNumber'              => $data['VouchersNumber'],
          'VoucherTypeId'               => $data['VoucherTypeId'],
        ];
        $this->VoucherLog_Model->insert_activity($act);
    }

    public function export_voucherlog_csv()
    {
      $data = array_merge($this->global_data);

      if ($this->session->userdata('Filter_DateFrom') != null) {
        $data['start'] = $this->session->userdata('Filter_DateFrom');
      }

      if ($this->session->userdata('Filter_DateTo') != null) {
        $data['end'] = $this->session->userdata('Filter_DateTo');
      }

      if ($this->session->userdata('Filter_Category') != null) {
        $data['category'] = $this->session->userdata('Filter_Category');
      }

      if ($this->session->userdata('Filter_Role') != null) {
        $data['role'] = $this->session->userdata('Filter_Role');
      }

      if ($this->session->userdata('Filter_Name') != null) {
        $data['name'] = $this->session->userdata('Filter_Name');
      }

      $permission      = $this->VoucherLog_Model->get_permissionvoucherlog($this->session->userdata('UserId'));
      $response        = $this->VoucherLog_Model->voucherlog_csv($data,$permission);
      $arraylist       = [];
      $no              = 1;

      if ($this->session->userdata('Filter_DateFrom') != null) {
        $from = $this->session->userdata('Filter_DateFrom');
      } else {
        $from = '';
      }

      if ($this->session->userdata('Filter_DateTo') != null) {
        $to = $this->session->userdata('Filter_DateTo');
      } else {
        $to = '';
      }

      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->VoucherLog_Model->get_bystore($data['reportstore']);
      $category            = $this->VoucherLog_Model->get_bycategory($data['category']);

      echo "\r"; echo "\r"; echo strtoupper("VOUCHER LOG REPORT FOR " .$bystore);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "CATEGORY: " .$category;
      echo "\n";
      echo "\n";

      $i = 0;
      foreach ($response as $key) {

        if ($key->UserId == 0) {
          $userid   = $key->UserId;
          $fullname = 'POS';
          $role     = 'POS';
        } else {
          $user     = $this->VoucherLog_Model->get_user($key->UserId);
          $userid   = $key->UserId;

          if(isset($user)) {
            $fullname = $user->Fullname;
            $role     = $user->Role;
          } else {
            $fullname = '';
            $role     = '';
          }
        }

        $receipt = $key->ReceiptNumber;
        if (!empty($receipt)) {
          $receipt = "'".$key->ReceiptNumber."'";
        }else {
          $receipt = '';
        }

        $array = array(
          'NO'                => $no,
          'STAFF ID'          => "'".$key->StaffId."'",
          'STORE'             => $key->StoreName,
          'ROLE'              => $role,
          'DATE'              => date('d/m/Y',strtotime($key->ActivityDate)),
          'TIME'              => date('h:i A',strtotime($key->ActivityDate)),
          'CATEGORY'          => $key->VoucherActivityCategoryName,
          'DETAILS'           => $key->Details,
          'VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
          'RECEIPT NUMBER'    => $receipt,
          'VOUCHER TYPE'      => $key->VoucherName,
       );

       $no++;
       array_push($arraylist,$array);
       $i++;
    }

      if ($from == "" && $to == "") {
        $this->download_send_headers("Voucher Log Report.csv");
      }else {
        $this->download_send_headers("Voucher Log Report  ".$from." - ".$to.".csv");
      }

      echo $this->array2csv($arraylist);
    }

    function filtervoucherlog() {
      $data = array_merge($this->global_data);

      $get = $this->input->post();
      $this->session->set_userdata('Filter_Role',$get['filter_role']);
      $this->session->set_userdata('Filter_Name',$get['filter_name']);
      $this->session->set_userdata('Filter_Category',$get['filter_category']);
      $this->session->set_userdata('Filter_DateFrom',$get['date_from']);
      $this->session->set_userdata('Filter_DateTo',$get['date_to']);

      $output['token'] = $data['csrf']['hash'];
      echo json_encode($output);
    }

    function download_send_headers($filename) { //fix code
      // disable caching

      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");

      // force download
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");

      // disposition / encoding on response body
      header("Content-Disposition: attachment;filename={$filename}");
      header("Content-Transfer-Encoding: binary");
    }

    function array2csv(array &$array){ //fix code
     if (count($array) == 0) {
       return null;
     }
     ob_start();
     $df = fopen("php://output", 'w');

     $headers = 'test,test2';

     fputcsv($df,array_keys(reset($array)));
     foreach ($array as $row) {
       fputcsv($df, $row);
     }
     fclose($df);
     return ob_get_clean();
   }
  }
 ?>
