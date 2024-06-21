<?php
  class Reports extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('reports/Report_model');
      $this->load->model('settings/RolePermission_Model');
      $this->global_data['active'] = $this->uri->segment(1);

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
      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time());
    	$this->global_data['CreatedBy']   = $this->global_data['UserId'];
    	$this->global_data['EditedDate']  = date("Y-m-d H:i:s", time());
    	$this->global_data['EditedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function index()
    {
       $data['header']          = $this->load->view('templates/main-header',"",true);
       $data['topbar']          = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']         = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']          = $this->load->view('templates/main-footer',"",true);
       $data['bottom']          = $this->load->view('templates/main-bottom',"",true);

       $data['filtertype']      = $this->Report_model->get_filtertype()->result();
       $data['filterstatus']    = $this->Report_model->get_filterstatus()->result();
       $data['filterreason']    = $this->Report_model->get_filterreason()->result();
       $data['filtercampaign']  = $this->Report_model->get_filtercampaign()->result();
       $data['filtercategory']  = $this->Report_model->get_filtercategory()->result();
       $data['filterstore']     = $this->Report_model->get_filterstore()->result();

       $data['permission']      =$this->Report_model->get_permission($this->session->userdata('UserId'));

      $this->load->view('reports/reports_list', $data);
    }

    //Vouchers Report
    public function export_voucher_report()
    {
      $data  = array_merge($this->global_data);

      if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
        $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
      }

      if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
        $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
      }

      if ($this->session->userdata('Voucher_Filter_Status') != 0) {
        $data['status'] = $this->session->userdata('Voucher_Filter_Status');
      }

      if ($this->session->userdata('Voucher_Filter_Type') != 0) {
        $data['type'] = $this->session->userdata('Voucher_Filter_Type');
      }

      if ($this->session->userdata('Voucher_Filter_Store') != 0) {
        $data['store'] = $this->session->userdata('Voucher_Filter_Store');
      }

      if ($this->session->userdata('Voucher_Filter_Terminal') != null) {
        $data['terminal'] = $this->session->userdata('Voucher_Filter_Terminal');
      }

      if ($this->session->userdata('Voucher_Filter_Reason') != 0) {
        $data['reason'] = $this->session->userdata('Voucher_Filter_Reason');
      }

      if ($this->session->userdata('Voucher_Filter_ReceiptNo') != null) {
        $data['receipt'] = $this->session->userdata('Voucher_Filter_ReceiptNo');
      }

      if (!empty($data['store'])) {
        $storecode  = $this->Report_model->get_storecode($data['store']);
      } else {
        $storecode  = [];
      }

      $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
      $response   = $this->Report_model->voucher_report($data,$permission,$storecode);
      $arraylist  = [];
      $no         = 1;

      if ($this->session->userdata('Voucher_Filter_Type') != 0) {
        $data['type'] = $this->session->userdata('Voucher_Filter_Type');
      } else {
        $data['type'] = '';
      }

      if ($this->session->userdata('Voucher_Filter_Status') != 0) {
        $data['status'] = $this->session->userdata('Voucher_Filter_Status');
      } else {
        $data['status'] = '';
      }

      if ($this->session->userdata('Voucher_Filter_Store') != 0) {
        $data['store'] = $this->session->userdata('Voucher_Filter_Store');
      } else {
        $data['store'] = '';
      }

      if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
        $from = $this->session->userdata('Voucher_Filter_Startdate');
      } else {
        $from = '';
      }

      if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
        $to = $this->session->userdata('Voucher_Filter_Enddate');
      } else {
        $to = '';
      }

      $vouchertype   = $this->Report_model->get_vouchertype($data['type']);
      $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

      if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
        $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
      } else {
        $data['reportstore'] = $this->session->userdata('UserId');
        $bystore             = $this->Report_model->get_bystore($data['reportstore']);
      }

      if ($data['status'] == 4)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER VOID REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "FOR  ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {
          $operator = $key->IssuanceCashierId;

          $receipt = $this->Report_model->get_receipt_by_status($key->VoucherId,5);

          $array = array(
           'VOID STORE'           => $key->StoreCode,
           'VOID STORE NAME'      => $key->StoreName,
           'VOID DATE'            => date('d/m/Y',strtotime($key->VoidDate)),
           'VOID TIME'            => date('h:i A',strtotime($key->VoidDate)),
           'VOID VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
           'VOID POS'             => $key->POSNumber,
           'VOID TRANSACTION'     => $receipt,
           'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
           'ISSUE STORE'          => $key->StoreCode,
           'ISSUE DATE'           => date('d/m/Y',strtotime($key->ReceiptDateTime)),
           'ISSUE TIME'           => date('h:i A',strtotime($key->ReceiptDateTime)),
           'VOUCHER AMOUNT'       => $key->VouchersValue,
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Void Report.csv");
        }else {
          $this->download_send_headers("Voucher Void Report  ".$from." - ".$to.".csv");
        }
      } elseif ($data['status'] == 7)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER REDEMPTION REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "REDEEMED PERIOD   ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {

          $redeemdetails = $this->Report_model->get_redeembystatus($key->VoucherId);

          $searchFor = $key->StoreId;
          $filterbystore = array_values(array_filter($redeemdetails, function($element) use($searchFor){
            return isset($element->StoreId) && $element->StoreId == $searchFor;
          }));

          $searchFor = $key->PosId;
          $filterbypos = array_values(array_filter($redeemdetails, function($element) use($searchFor){
            return isset($element->PosId) && $element->PosId == $searchFor;
          }));

          $searchFor = $key->ReceiptDateTime;
          $filterbydate = array_values(array_filter($redeemdetails, function($element) use($searchFor){
            return isset($element->ReceiptDateTime) && $element->ReceiptDateTime == $searchFor;
          }));

          $searchFor = $key->ReceiptNumber;
          $filterbyreceipt = array_values(array_filter($redeemdetails, function($element) use($searchFor){
            return isset($element->ReceiptNumber) && $element->ReceiptNumber == $searchFor;
          }));

          $searchFor = $key->VouchersNumber;
          $filterbyvoucherno = array_values(array_filter($redeemdetails, function($element) use($searchFor){
            return isset($element->VouchersNumber) && $element->VouchersNumber == $searchFor;
          }));

          $array = array(
           'REDEEMED STORE'           => $filterbystore[0]->StoreCode,
           'REDEEMED STORE NAME'      => $filterbystore[0]->StoreName,
           'REDEEMED DATE'            => date('d/m/Y',strtotime($filterbydate[0]->ReceiptDateTime)),
           'REDEEMED TIME'            => date('h:i A',strtotime($filterbydate[0]->ReceiptDateTime)),
           'REDEEMED VOUCHER NUMBER'  => "'".$filterbyvoucherno[0]->VouchersNumber."'",
           'REDEEMED POS'             => $filterbypos[0]->POSNumber,
           'REDEEMED TRANSACTION'     => $filterbyreceipt[0]->ReceiptNumber,
           'EXPIRED DATE'             => date('d/m/Y',strtotime($key->ExpDate)),
           'ISSUE STORE'              => $key->StoreCode,
           'ISSUE DATE'               => date('d/m/Y',strtotime($key->CreatedDate)),
           'ISSUE TIME'               => date('h:i A',strtotime($key->CreatedDate)),
           'VOUCHER AMOUNT'           => $key->VouchersValue,
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Redemption Report.csv");
        }else {
          $this->download_send_headers("Voucher Redemption Report ".$from." - ".$to.".csv");
        }
      } elseif ($data['status'] == 8)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER REFUND REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "REFUND PERIOD   ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {
          $operator = $key->IssuanceCashierId;

          $receipt = $this->Report_model->get_receipt_by_status($key->VoucherId,6);

          $array = array(
           'REFUND STORE'            => $key->StoreCode,
           'REFUND STORE NAME'       => $key->StoreName,
           'REFUND DATE'             => date('d/m/Y',strtotime($key->RefundDate)),
           'REFUND TIME'             => date('h:i A',strtotime($key->RefundDate)),
           'REFUND VOUCHER NUMBER'   => "'".$key->VouchersNumber."'",
           'REFUND POS'              => $key->POSNumber,
           'REFUND TRANSACTION'      => $receipt,
           'EXPIRED DATE'            => date('d/m/Y',strtotime($key->ExpDate)),
           'ISSUE STORE'             => $key->StoreCode,
           'ISSUE DATE'              => date('d/m/Y',strtotime($key->CreatedDate)),
           'ISSUE TIME'              => date('h:i A',strtotime($key->CreatedDate)),
           'VOUCHER AMOUNT'          => $key->VouchersValue,
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Refund Report.csv");
        }else {
          $this->download_send_headers("Voucher Refund Report ".$from." - ".$to.".csv");
        }
      } elseif ($data['status'] == 6)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER BLOCK REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "FOR  ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {

          $operator = $key->IssuanceCashierId;
          $staffid  = $this->Report_model->get_staffid($key->VoucherId,2);

          $array = array(
           'STORE'           => $key->StoreCode,
           'NAME'            => $key->StoreName,
           'ISSUING DATE'    => date('d/m/Y',strtotime($key->CreatedDate)),
           'ISSUING TIME'    => date('h:i A',strtotime($key->CreatedDate)),
           'VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
           'VOUCHER AMT'     => $key->VouchersValue,
           'POS'             => $key->POSNumber,
           'TRANSACTION'     => $key->ReceiptNumber,
           'EXPIRED DATE'    => date('d/m/Y',strtotime($key->ExpDate)),
           'BLOCK DATE'      => date('d/m/Y',strtotime($key->BlockDate)),
           'BLOCK TIME'      => date('h:i A',strtotime($key->BlockDate)),
           'BLOCK REASONS'   => $key->ReasonName,
           'BLOCKED BY'      => $staffid,
           'OPERATOR'        => "'".$operator."'",
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Block Report.csv");
        }else {
          $this->download_send_headers("Voucher Block Report  ".$from." - ".$to.".csv");
        }
      } elseif ($data['status'] == 5)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER CANCEL REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "FOR  ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {
          // $operator = $key->IssuanceCashierId;

          $staffid    = $this->Report_model->get_staffid($key->VoucherId,10);

          $array = array(
           'STORE'           => $key->StoreCode,
           'NAME'            => $key->StoreName,
           'ISSUING DATE'    => date('d/m/Y',strtotime($key->CreatedDate)),
           'ISSUING TIME'    => date('h:i A',strtotime($key->CreatedDate)),
           'VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
           'VOUCHER AMT'     => $key->VouchersValue,
           'POS'             => $key->POSNumber,
           'TRANSACTION'     => $key->ReceiptNumber,
           'EXPIRED DATE'    => date('d/m/Y',strtotime($key->ExpDate)),
           'CANCEL DATE'     => date('d/m/Y',strtotime($key->CancelDate)),
           'CANCEL TIME'     => date('h:i A',strtotime($key->CancelDate)),
           'CANCEL REASONS'  => $key->ReasonName,
           'CANCELLED BY'    => $staffid,
           // 'OPERATOR'        => "'".$operator."'",
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Cancel Report.csv");
        }else {
          $this->download_send_headers("Voucher Cancel Report  ".$from." - ".$to.".csv");
        }
      } elseif ($data['status'] == 3)
      {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER EXTEND REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "FOR  ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        foreach ($response as $key) {
          $operator = $key->IssuanceCashierId;
          $staffid  = $this->Report_model->get_staffid($key->VoucherId,8);

          $array = array(
           'STORE'           => $key->StoreCode,
           'NAME'            => $key->StoreName,
           'ISSUING DATE'    => date('d/m/Y',strtotime($key->CreatedDate)),
           'ISSUING TIME'    => date('h:i A',strtotime($key->CreatedDate)),
           'VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
           'VOUCHER AMT'     => $key->VouchersValue,
           'POS'             => $key->POSNumber,
           'TRANSACTION'     => $key->ReceiptNumber,
           'EXPIRED DATE'    => date('d/m/Y',strtotime($key->ExpDate)),
           'EXTEND DATE'     => date('d/m/Y',strtotime($key->ExtendDate)),
           'EXTENDED BY'     => $staffid,
           'OPERATOR'        => "'".$operator."'",
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Extend Report.csv");
        }else {
          $this->download_send_headers("Voucher Extend Report  ".$from." - ".$to.".csv");
        }
      } else {
        echo "\r"; echo "\r"; echo strtoupper("DAILY VOUCHER ISSUANCE REPORT FOR " .$bystore);
        echo "\n";
        echo strtoupper("VOUCHER TYPE: " .$vouchertype);
        echo "\n";
        echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
        echo "\n";
        echo "FOR  ".$from."  TO  ".$to;
        echo "\n";
        echo "\n";

        $i = 0;
        $totalamount = 0;

        $vouch = [];
        foreach ($response as $row) {
          $vouch[] = $row->VoucherId;
        }

        $vouchvalue = $this->Report_model->get_voucher_value($vouch);

        foreach ($response as $key) {

          $operator = $key->IssuanceCashierId;

          if ($key->Source == 'POS') {
            $operator = $key->IssuanceCashierId;
            $terminal = $key->POSNumber;
          }else {
            $staffid  = $this->Report_model->get_loginid($key->IssuanceLoginId);
            $operator = $staffid;
            $terminal = "ECS" .$key->StoreCode;
          }

          // $searchFor = $key->VoucherIssuanceId;
          // $filterbyissuance = array_values(array_filter($vouchvalue, function($element) use($searchFor){
          //   return isset($element->VoucherIssuanceId) && $element->VoucherIssuanceId == $searchFor;
          // }));
          //
          // $typecsv = '';
          //
          // if(!empty($filterbyissuance)){
          //   foreach ($filterbyissuance as $rowvalue) {
          //     $typecsv .= $rowvalue->VoucherShortName.$rowvalue->VouchersValue.':'.$rowvalue->Total.', ';
          //   }
          // }

          // echo "<pre>";
          // print_r($filterbyreceipt);
          // echo "</pre>";

          $array = array(
           'STORE'                => $key->StoreCode,
           'NAME'                 => $key->StoreName,
           'ISSUING DATE'         => date('d/m/Y',strtotime($key->ReceiptDateTime)),
           'ISSUING TIME'         => date('h:i A',strtotime($key->ReceiptDateTime)),
           'TOTAL SALES'          => $key->TotalSales,
           'COSMETIC SALES'       => $key->Cosmeticsales,
           'VOUCHER NUMBER'       => "'".$key->VouchersNumber."'",
           'VOUCHER AMT'          => $key->VouchersValue,
           'POS'                  => $key->POSNumber,
           'TRANSACTION'          => $key->ReceiptNumber,
           // 'VOUCHER ENTITLEMENT'  => $typecsv,
           'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
           'VOUCHER STATUS'       => $key->VoucherStatusName,
           'REDEEM TYPE'          => $key->RedeemTypeName,
           'OPERATOR'             => "'".$operator."'",
           'TERMINAL ID'          => $terminal,
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         $totalamount += $key->VouchersValue;
        };

        // echo "<pre>";
        // print_r($arraylist);
        // echo "</pre>";

        if ($from == "" && $to == "") {
          $this->download_send_headers("Voucher Report.csv");
        }else {
          $this->download_send_headers("Voucher Report  ".$from." - ".$to.".csv");
        }
      }

      echo $this->array2csv($arraylist);
      echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
      echo "\n";
      echo "TOTAL LINE: " .$i;
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

   function filtervoucher() {
     $data = array_merge($this->global_data);

     $get = $this->input->post();
     $this->session->set_userdata('Voucher_Filter_Startdate',$get['voucher_datefrom']);
     $this->session->set_userdata('Voucher_Filter_Enddate',$get['voucher_dateto']);
     $this->session->set_userdata('Voucher_Filter_Type',$get['vouchertypefilter']);
     $this->session->set_userdata('Voucher_Filter_Status',$get['voucherstatusfilter']);
     $this->session->set_userdata('Voucher_Filter_Reason',$get['reasonfilter']);
     $this->session->set_userdata('Voucher_Filter_Campaign',$get['campaignfilter']);
     $this->session->set_userdata('Voucher_Filter_Store',$get['storefilter']);
     $this->session->set_userdata('Voucher_Filter_Terminal',$get['terminalfilter']);
     $this->session->set_userdata('Voucher_Filter_ReceiptNo',$get['receiptnofilter']);

     $output['token'] = $data['csrf']['hash'];
     echo json_encode($output);
   }
   //=========================================================================================//End of Vouchers REPORT

   //Users Report
   public function export_user_report() {
     $data            = array_merge($this->global_data);

     if ($this->session->userdata('User_Filter_Startdate') != null) {
       $data['start'] = $this->session->userdata('User_Filter_Startdate');
     }

     if ($this->session->userdata('User_Filter_Enddate') != null) {
       $data['end'] = $this->session->userdata('User_Filter_Enddate');
     }

     $permission  = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
     $response    = $this->Report_model->user_report($data,$permission);
     $arraylist   = [];
     $no          = 1;

     if ($this->session->userdata('User_Filter_Startdate') != null) {
       $from = $this->session->userdata('User_Filter_Startdate');
     } else {
       $from = '';
     }

     if ($this->session->userdata('User_Filter_Enddate') != null) {
       $to = $this->session->userdata('User_Filter_Enddate');
     } else {
       $to = '';
     }

     $data['reportstore'] = $this->session->userdata('UserId');
     $bystore             = $this->Report_model->get_bystore($data['reportstore']);

     echo "\r"; echo "\r"; echo strtoupper("MONTHLY USER REPORT FOR " .$bystore);
     echo "\n";
     echo "FOR  ".$from."  TO  ".$to;
     echo "\n";
     echo "\n";

     $i = 0;
     foreach ($response as $key) {

       $array = array(
         'NO'                => $no,
         'STAFF ID'          => $key->StaffId,
         'ROLE'              => $key->Role,
         'NAME'              => $key->Fullname,
         'EMAIL'             => $key->Email,
         'PHONE NO'          => $key->PhoneNo,
         'STATUS'            => $key->StatusName,
         'CREATED TIME'      => date('h:i A',strtotime($key->CreatedDate)),
         'CREATED DATE'      => date('d/m/Y',strtotime($key->CreatedDate)),
      );

      $no++;
      array_push($arraylist,$array);
      $i++;
     }

     if ($from == "" && $to == "") {
       $this->download_send_headers("Users Report.csv");
     }else {
       $this->download_send_headers("Users Report  ".$from." - ".$to.".csv");
     }

     echo $this->array2csv($arraylist);
   }

  function filteruser() {
    $data = array_merge($this->global_data);

    $get = $this->input->post();
    $this->session->set_userdata('User_Filter_Startdate',$get['user_datefrom']);
    $this->session->set_userdata('User_Filter_Enddate',$get['user_dateto']);

    $output['token'] = $data['csrf']['hash'];
    echo json_encode($output);
  }
  //=========================================================================================//End of Users Report

  //User Log Report
  public function export_userlog_report()
  {
    $data            = array_merge($this->global_data);

    if ($this->session->userdata('UserLog_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('UserLog_Filter_Startdate');
    }

    if ($this->session->userdata('UserLog_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('UserLog_Filter_Enddate');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));

    $response        = $this->Report_model->userlog_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

    if ($this->session->userdata('UserLog_Filter_Startdate') != null) {
      $from = $this->session->userdata('UserLog_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('UserLog_Filter_Enddate') != null) {
      $to = $this->session->userdata('UserLog_Filter_Enddate');
    } else {
      $to = '';
    }

    $data['reportstore']  = $this->session->userdata('UserId');
    $bystore              = $this->Report_model->get_bystore($data['reportstore']);

    echo "\r"; echo "\r"; echo strtoupper("MONTHLY USER LOG REPORT FOR " .$bystore);
    echo "\n";
    echo "FOR  ".$from."  TO  ".$to;
    echo "\n";
    echo "\n";

    $i = 0;
    foreach ($response as $key) {

      $array = array(
        'NO'                => $no,
        'USER ID'           => $key->UserId,
        'NAME'              => $key->Fullname,
        'ROLE'              => $key->Role,
        'DATE'              => date('d/m/Y',strtotime($key->ActiveDate)),
        'CATEGORY'          => $key->ActivityTypeName,
        'DETAILS'           => $key->ActivityDetails,
        'IP ADDRESS'        => $key->IpAddress,
        'BROWSER'           => $key->Browser,
     );

     $no++;
     array_push($arraylist,$array);
     $i++;
    }

    if ($from == "" && $to == "") {
      $this->download_send_headers("User Activity Log Report.csv");
    }else {
      $this->download_send_headers("User Activity Log Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);
  }

 function filteruserlog() {
   $data = array_merge($this->global_data);

   $get = $this->input->post();
   $this->session->set_userdata('UserLog_Filter_Startdate',$get['userlog_datefrom']);
   $this->session->set_userdata('UserLog_Filter_Enddate',$get['userlog_dateto']);

   $output['token'] = $data['csrf']['hash'];
   echo json_encode($output);
 }
   //=========================================================================================//End of UserLog Report

   //Voucher Log Report
   public function export_voucherlog_report() {
     $data  = array_merge($this->global_data);

     if ($this->session->userdata('VoucherLog_Filter_Startdate') != null) {
       $data['start'] = $this->session->userdata('VoucherLog_Filter_Startdate');
     }

     if ($this->session->userdata('VoucherLog_Filter_Enddate') != null) {
       $data['end'] = $this->session->userdata('VoucherLog_Filter_Enddate');
     }

     if ($this->session->userdata('VoucherLog_Filter_Category') != null) {
       $data['category'] = $this->session->userdata('VoucherLog_Filter_Category');
     }

     $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
     $response   = $this->Report_model->voucherlog_report($data,$permission);
     $arraylist  = [];
     $no         = 1;

     if ($this->session->userdata('VoucherLog_Filter_Startdate') != null) {
       $from = $this->session->userdata('VoucherLog_Filter_Startdate');
     } else {
       $from = '';
     }

     if ($this->session->userdata('VoucherLog_Filter_Enddate') != null) {
       $to = $this->session->userdata('VoucherLog_Filter_Enddate');
     } else {
       $to = '';
     }

     $data['reportstore'] = $this->session->userdata('UserId');
     $bystore             = $this->Report_model->get_bystore($data['reportstore']);
     $category            = $this->Report_model->get_bycategory($data['category']);


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
         $user     = $this->Report_model->get_user($key->UserId);
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

   // echo "<pre>";
   // print_r($arraylist);
   // echo "</pre>";

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
    $this->session->set_userdata('VoucherLog_Filter_Startdate',$get['voucherlog_datefrom']);
    $this->session->set_userdata('VoucherLog_Filter_Enddate',$get['voucherlog_dateto']);
    $this->session->set_userdata('VoucherLog_Filter_Category',$get['categoryfilter']);

    $output['token'] = $data['csrf']['hash'];
    echo json_encode($output);
  }
  //=========================================================================================//End of VoucherLog Report

  // Reconciliation Voucher Report
  public function export_reconciliation_report() {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response   = $this->Report_model->reconciliation_report($data,$permission);
    $arraylist  = [];
    $no = 1;

    if ($this->session->userdata('Voucher_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    } else {
      $data['store'] = '';
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    $vouchertype   = $this->Report_model->get_vouchertype($data['type']);

    if ($vouchertype == 'All Voucher') {
      $data['type'] = 0;
    }

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("VOUCHER RECONCILIATION REPORT (RM) FOR " .$bystore);
    echo "\n";
    echo strtoupper("VOUCHER TYPE: " .$vouchertype);
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo "\n";

    $i = 0;
    $p = 0;
    $a = 0;
    $b = 0;
    $c = 0;
    $d = 0;

    $stores = [];
    foreach ($response as $row) {
      $stores[] = $row->StoreId;
    }

    $prevunredeemed  = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'].'- 1 month')),1,'P');
    $unredeemed      = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),0,'A'); //0 stand for not filtering by status
    $redeemed        = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),7,'B');
    $expired         = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),2,'C');
    $nonexpired      = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),1,'D');
    $unredeemexpired = $this->Report_model->get_total_voucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),2,'E');

    foreach ($response as $key) {

      if (!empty($prevunredeemed)) {
        $searchFor = $key->StoreId;
        $fprevunredeemed = array_values(array_filter($prevunredeemed, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else {
        $fprevunredeemed = 0;
      }

      if (!empty($fprevunredeemed)) {
        $fprevunredeemed = $fprevunredeemed[0]->VouchersValue;
      }else {
        $fprevunredeemed = 0;
      }

      if (!empty($unredeemed)) {
        $searchFor = $key->StoreId;
        $funredeemed = array_values(array_filter($unredeemed, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else {
        $funredeemed =  0;
      }

      if (!empty($funredeemed)) {
        $funredeemed = $funredeemed[0]->VouchersValue;
      }else {
        $funredeemed = 0;
      }

      if (!empty($redeemed)) {
        $searchFor = $key->StoreId;
        $fredeemed = array_values(array_filter($redeemed, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else {
        $fredeemed = 0;
      }

      if (!empty($fredeemed)) {
        $fredeemed = $fredeemed[0]->VouchersValue;
      }else {
        $fredeemed = 0;
      }

      if (!empty($expired)) {
        $searchFor = $key->StoreId;
        $fexpired = array_values(array_filter($expired, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else {
        $fexpired = 0;
      }

      if (!empty($fexpired)) {
        $fexpired = $expired[0]->VouchersValue;
      }else {
        $fexpired = 0;
      }

      if(!empty($nonexpired)) {
        $searchFor = $key->StoreId;
        $fnonexpired = array_values(array_filter($nonexpired, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else{
        $fnonexpired = 0;
      }

      if (!empty($fnonexpired)) {
        $fnonexpired = $fnonexpired[0]->VouchersValue;
      }else {
        $fnonexpired = 0;
      }

      if(!empty($unredeemexpired)) {
        $searchFor = $key->StoreId;
        $funredeemexpired = array_values(array_filter($unredeemexpired, function($element) use($searchFor){
          return isset($element->IssuanceStoreId) && $element->IssuanceStoreId == $searchFor;
        }));
      }else{
        $funredeemexpired = 0;
      }

      if (!empty($funredeemexpired)) {
        $funredeemexpired = $funredeemexpired[0]->VouchersValue;
      }else {
        $funredeemexpired = 0;
      }

      $P = $fprevunredeemed;
      $A = $funredeemed;
      $B = $fredeemed;
      $C = $fnonexpired;
      $D = $funredeemexpired;

      $array = array(
        'NO'                                    => $no,
        'STORE CODE'                            => $key->StoreCode,
        'STORE NAME'                            => $key->StoreName,
        'PREV.MONTH NON EXPIRED & UNREDEEMED
                             (P)'               => number_format($P,2,'.',''),
        'ISSUING MONTH
             (A)'                               => number_format($A,2,'.',''),
        'TOTAL REDEEMED
             (B)'                               => number_format($B,2,'.',''),
        'TOTAL ISSUED UNREDEEMED (NON EXPIRED)
                 (C) = ((P + A) - B)'           => number_format($C,2,'.',''),
        'TOTAL ISSUED UNREDEEMED (EXPIRED)
                 (C) = ((P + A) - B)'           => number_format($D,2,'.',''),
     );
     $no++;
     array_push($arraylist,$array);

     $i++;
     $p += $P;
     $a += $A;
     $b += $B;
     $c += $C;
     $d += $D;
  }

  // echo "<pre>";
  // print_r($arraylist);
  // echo "</pre>";

    if ($first == "" && $last == "") {
       $this->download_send_headers("Reconciliation Voucher Report.csv");
     }else {
       $this->download_send_headers("Reconciliation Voucher Report  ".$first." - ".$last.".csv");
     }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "COMPANY TOTAL: ".$i;
    echo "\n";
    echo "\n"; echo "PREV.MONTH NON EXPIRED & UNREDEEMED TOTAL: ".$p;
    echo "\n"; echo "TOTAL ISSUING MONTH: ".$a;
    echo "\n"; echo "TOTAL REDEEMED: ".$b;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (NON EXPIRED): ".$c;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (EXPIRED): ".$d;
  }

//=========================================================================================//End of Reconciliation Report

  //Unredeemed & Not Expired Report
  public function export_notexpired_report()
  {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Voucher_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    } else {
      $data['store'] = '';
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    $vouchertype = $this->Report_model->get_vouchertype($data['type']);

    if ($vouchertype == 'All Voucher') {
      $data['type'] = 0;
    }

    $response = $this->Report_model->unredeemed_notexpired_report($data['type'],$data['store'],date('Y-m-d', strtotime($data['start'])),1,'D',$permission);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("UNREDEEMED & NOT EXPIRED VOUCHERS REPORT FOR " .$bystore);
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo strtoupper("VOUCHER TYPE: " .$vouchertype);
    echo "\n";
    echo "\n";

    $i = 0;
    $totalamount   = 0;
    $filteredstore = [];

    foreach ($response as $key) {

      $array = array(
        'NO'                           => $no,
        'ISSUED STORE CODE'            => $key->StoreCode,
        'ISSUED STORE'                 => $key->StoreName,
        'ISSUED DATE'                  => date('d/m/Y',strtotime($key->CreatedDate)),
        'EXPIRED DATE'                 => date('d/m/Y',strtotime($key->ExpDate)),
        'UNEXPIRED VOUCHER NUMBER'     => "'".$key->VouchersNumber."'",
        'VOUCHER AMOUNT'               => $key->VouchersValue,
        'VOUCHER TYPE'                 => $key->VoucherName,

     );
     $no++;
     array_push($arraylist,$array);

     $i++;

     if (!in_array($key->StoreId,$filteredstore)) {
       $filteredstore[] = $key->StoreId;
       $nonexpired      = $this->Report_model->get_expired_nonexpired($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),1,'D');
       $C               = $nonexpired;
     }

     if ($this->session->userdata('Voucher_Filter_Store') != 0) {
       $totalamount = number_format($C,2,'.','');
     } else {
       $totalamount =  $totalamount + $key->VouchersValue;
     }
  }

  // echo "<pre>";
  // print_r($arraylist);
  // echo "</pre>";

    if ($first == "" && $last == "") {
      $this->download_send_headers("Unredeemed Not Expired Report.csv");
    }else {
      $this->download_send_headers("Unredeemed Not Expired Report  ".$first." - ".$last.".csv");
    }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

 //=========================================================================================//End of Unredeemed & Not Expired Report

//Unredeemed & Expired Report
  public function export_expired_report() {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Voucher_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    } else {
      $data['store'] ='';
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    $vouchertype = $this->Report_model->get_vouchertype($data['type']);

    if ($vouchertype == 'All Voucher') {
      $data['type'] = 0;
    }

    $response =  $this->Report_model->unredeemed_expired_report($data['type'],$data['store'],date('Y-m-d', strtotime($data['start'])),2,'E',$permission);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("UNREDEEMED & EXPIRED VOUCHERS REPORT FOR " .$bystore);
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo strtoupper("VOUCHER TYPE: " .$vouchertype);
    echo "\n";
    echo "\n";

    $i = 0;
    $totalamount   = 0;
    $filteredstore = [];

    foreach ($response as $key) {

      $array = array(
          'No'                           => $no,
          'ISSUED STORE CODE'            => $key->StoreCode,
          'ISSUED STORE'                 => $key->StoreName,
          'ISSUED DATE'                  => date('d/m/Y',strtotime($key->CreatedDate)),
          'EXPIRED DATE'                 => date('d/m/Y',strtotime($key->ExpDate)),
          // 'EXTEND DATE'                  => date('d/m/Y',strtotime($key->ExtendDate)),
          'EXPIRED VOUCHER NUMBER'       => "'".$key->VouchersNumber."'",
          'VOUCHER AMOUNT'               => $key->VouchersValue,
          'VOUCHER TYPE'                 => $key->VoucherName,
       );

     $no++;
     array_push($arraylist,$array);

     $i++;

     if (!in_array($key->StoreId,$filteredstore)) {
       $filteredstore[] = $key->StoreId;
       $unredeemexpired = $this->Report_model->get_expired_nonexpired($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),2,'E');
       $D               = $unredeemexpired;
     }

     if ($this->session->userdata('Voucher_Filter_Store') != 0) {
       $totalamount = number_format($D,2,'.','');
     } else {
       $totalamount = $totalamount + $key->VouchersValue;
     }
  }

    // echo "<pre>";
    // print_r($arraylist);
    // echo "</pre>";

    if ($first == "" && $last == "") {
      $this->download_send_headers("Unredeemed Expired Report.csv");
    }else {
      $this->download_send_headers("Unredeemed Expired Report  ".$first." - ".$last.".csv");
    }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

//=========================================================================================//End of Unredeemed & Expired Report


  //Voucher Issuance by Campaign Report
  public function export_voucherissuance_campaign()
  {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    }

    if ($this->session->userdata('Voucher_Filter_Campaign') != null) {
      $data['campaign'] = $this->session->userdata('Voucher_Filter_Campaign');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response   = $this->Report_model->voucherissuance_campaign_report($data,$permission);
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Campaign') != null) {
      $data['campaign'] = $this->session->userdata('Voucher_Filter_Campaign');
    } else {
      $data['campaign'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $from = $this->session->userdata('Voucher_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $to = $this->session->userdata('Voucher_Filter_Enddate');
    } else {
      $to = '';
    }

    $vouchertype  = $this->Report_model->get_vouchertype($data['type']);
    $campaignname = $this->Report_model->get_campaignname($data['campaign']);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("VOUCHER ISSUANCE BY CAMPAIGN REPORT FOR " .$bystore);
    echo "\n";
    echo "FOR   ".$from." TO  ".$to;
    echo "\n";
    echo strtoupper("CAMPAIGN NAME: " .$campaignname);
    echo "\n";
    echo strtoupper("VOUCHER TYPE: " .$vouchertype);
    echo "\n";
    echo "\n";

    $i = 0;
    $totalamount = 0;

    foreach ($response as $key) {

      $array = array(
        'NO'                           => $no,
        'CAMPAIGN ID'                  => $key->CampaignId,
        'CAMPAIGN NAME'                => $key->CampaignName,
        'START DATE'                   => date('d/m/Y',strtotime($key->StartDate)),
        'END DATE'                     => date('d/m/Y',strtotime($key->EndDate)),
        'EXPIRED DATE'                 => date('d/m/Y',strtotime($key->ExpDate)),
        'VOUCHER TYPE'                 => $key->VoucherName,
        'VOUCHER NUMBER'               => "'".$key->VouchersNumber."'",
        'VOUCHER AMOUNT'               => $key->VouchersValue,
        'VOUCHER STATUS'               => $key->VoucherStatusName,
     );

     $no++;
     array_push($arraylist,$array);

     $i++;
     $totalamount += $key->VouchersValue;
  }

    if ($from == "" && $to == "") {
      $this->download_send_headers("Voucher Issuance by Campaign Report.csv");
    }else {
      $this->download_send_headers("Voucher Issuance by Campaign Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

//=========================================================================================//End of Voucher Issuance by Campaign Report

  public function export_duplicatevoucher_report()
  {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    }

    if ($this->session->userdata('Voucher_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Voucher_Filter_Status');
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission      = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response        = $this->Report_model->duplicatevoucher_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

    if ($this->session->userdata('Voucher_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Voucher_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Voucher_Filter_Status');
    } else {
      $data['status'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    } else {
      $data['store'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $from = $this->session->userdata('Voucher_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $to = $this->session->userdata('Voucher_Filter_Enddate');
    } else {
      $to = '';
    }

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("DUPLICATE PV/DV TRANSACTION REPORT");
    echo "\n";
    echo strtoupper("STORE: " .$bystore);
    echo "\n";
    echo "SALES DATE : ".$from." - ".$to;
    echo "\n";
    echo "\n";

    $dupvoucher = [];
    foreach ($response as $key) {
      if ($key->Total > 1) {
        $dupvoucher[] = $key->VouchersNumber;
      }
    }

    if (!empty($dupvoucher)) {
      $responses = $this->Report_model->get_duplicate($dupvoucher);
    } else {
      $responses = [];
    }

    $vouch = [];
    foreach ($response as $row) {
      $vouch[] = $row->VoucherId;
    }

    $vouchvalue = $this->Report_model->get_voucher_value($vouch);

    foreach ($responses as $key) {

      $operator  = $key->IssuanceCashierId;
      if ($operator != null) {
        $operator  = "'".$key->IssuanceCashierId."'";
      } else {
        $operator  = '';
      }

      $searchFor = $key->VoucherIssuanceId;
      $filterbyissuance = array_values(array_filter($vouchvalue, function($element) use($searchFor){
        return isset($element->VoucherIssuanceId) && $element->VoucherIssuanceId == $searchFor;
      }));

      $typecsv = '';

      if(!empty($filterbyissuance)){
        foreach ($filterbyissuance as $rowvalue) {
          $typecsv .= $rowvalue->VoucherShortName.$rowvalue->VouchersValue.':'.$rowvalue->Total.', ';
        }
      }

      // echo "<pre>";
			// 	print_r($responses);
			// echo "</pre>";

        $array = array(
         'STORE NAME'           => $key->StoreCode. ' - ' .$key->StoreName,
         'ISSUING DATE'         => date('d/m/Y',strtotime($key->CreatedDate)),
         'ISSUING TIME'         => date('h:i A',strtotime($key->CreatedDate)),
         'VOUCHER NUMBER'       => "'".$key->VouchersNumber."'",
         'CODE'                 => $key->VoucherShortName,
         'DESCRIPTION'          => $key->VoucherName,
         'POS'                  => $key->POSNumber,
         'RECEIPT DATE'         => date('d/m/Y',strtotime($key->ReceiptDateTime)),
         'RECEIPT TIME'         => date('h:i A',strtotime($key->ReceiptDateTime)),
         'RECEIPT NO'           => $key->ReceiptNumber,
         'AMOUNT (RM)'          => $key->VouchersValue,
         'TOTAL SALES'          => $key->TotalSales,
         'COSMETIC SALES'       => $key->Cosmeticsales,
         'VOUCHER ENTITLEMENT'  => $typecsv,
         'CASHIER ID'           => $operator,
         'VOUCHER STATUS'       => $key->VoucherStatusName,
       );

     array_push($arraylist,$array);
    };

    // echo "<pre>";
    // print_r($arraylist);
    // echo "</pre>";

    if ($from == "" && $to == "") {
      $this->download_send_headers("Duplicate Voucher Report.csv");
    }else {
      $this->download_send_headers("Duplicate Voucher Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);
  }

//=========================================================================================//End of Duplicate Voucher Report

  public function export_duplicatereceipt_report()
  {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Voucher_Filter_Startdate');
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Voucher_Filter_Enddate');
    }

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission      = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response        = $this->Report_model->duplicatereceipt_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

    if ($this->session->userdata('Voucher_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    } else {
      $data['store'] = '';
    }

    if ($this->session->userdata('Voucher_Filter_Startdate') != null) {
      $from = $this->session->userdata('Voucher_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Voucher_Filter_Enddate') != null) {
      $to = $this->session->userdata('Voucher_Filter_Enddate');
    } else {
      $to = '';
    }

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("DUPLICATE RECEIPT OVER ISSUING REPORT");
    echo "\n";
    echo strtoupper("STORE: " .$bystore);
    echo "\n";
    echo "SALES DATE : ".$from." - ".$to;
    echo "\n";
    echo "\n";

    // echo "<pre>";
    // print_r($response);
    // echo "</pre>";

    $dupreceipt = [];
    foreach ($response as $key) {
      if ($key->Total > 1) {
        $dupreceipt[] = $key->ReceiptNumber;
      }
    }

    if (!empty($dupreceipt)) {
      $group     = $this->Report_model->get_duplicatereceipt_group($dupreceipt);
      $responses = $this->Report_model->get_duplicatereceipt($dupreceipt);
    } else {
      $group     = [];
      $responses = [];
    }

    $vouch = [];
    foreach ($responses as $row) {
      $vouch[] = $row->VoucherId;
    }

    $vouchvalue = $this->Report_model->get_voucher_value($vouch);

    foreach ($group as $key) {

      $operator  = $key->IssuanceCashierId;
      if ($operator != null) {
        $operator  = "'".$key->IssuanceCashierId."'";
      } else {
        $operator  = '';
      }

      $searchFor = $key->VoucherIssuanceId;
      $filterbyissuance = array_values(array_filter($vouchvalue, function($element) use($searchFor){
        return isset($element->VoucherIssuanceId) && $element->VoucherIssuanceId == $searchFor;
      }));

      $typecsv = '';

      if(!empty($filterbyissuance)){
        foreach ($filterbyissuance as $rowvalue) {
          $typecsv .= $rowvalue->VoucherShortName.$rowvalue->VouchersValue.':'.$rowvalue->Total.', ';
        }
      }

      $array = array(
        'STORE NAME'           => $key->StoreCode. ' - ' .$key->StoreName,
        'RECEIPT DATE'         => date('d/m/Y',strtotime($key->ReceiptDateTime)),
        'RECEIPT TIME'         => date('h:i A',strtotime($key->ReceiptDateTime)),
        'RECEIPT NO'           => $key->ReceiptNumber,
        'POS'                  => $key->POSNumber,
        'VOUCHER ENTITLEMENT'  => $typecsv,
        'CASHIER ID'           => $operator,
     );

       array_push($arraylist,$array);
    };

    // echo "<pre>";
    // print_r($arraylist);
    // echo "</pre>";

    if ($from == "" && $to == "") {
      $this->download_send_headers("Duplicate Receipt Over Issuing Report.csv");
    }else {
      $this->download_send_headers("Duplicate Receipt Over Issuing Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);
  }
//=========================================================================================//End of Duplicate Receipt Report


  //Gift Voucher Upload Report
  public function export_giftvoucherupload_report() {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Gift_Filter_Status');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    }

    if ($this->session->userdata('Gift_Filter_POSNumber') != 0) {
      $data['pos'] = $this->session->userdata('Gift_Filter_POSNumber');
    }

    if ($this->session->userdata('Gift_Filter_Terminal') != null) {
      $data['terminal'] = $this->session->userdata('Gift_Filter_Terminal');
    }

    if ($this->session->userdata('Gift_Filter_BatchNumber') != null) {
      $data['batchnumber'] = $this->session->userdata('Gift_Filter_BatchNumber');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response   = $this->Report_model->giftvoucherupload_report($data,$permission);
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Gift_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Gift_Filter_Status');
    } else {
      $data['status'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $from = $this->session->userdata('Gift_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $to = $this->session->userdata('Gift_Filter_Enddate');
    } else {
      $to = '';
    }

    $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHERS UPLOAD REPORT FOR " .$bystore);
    echo "\n";
    echo "VOUCHER TYPE: GIFT VOUCHER";
    echo "\n";
    echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
    echo "\n";
    echo "FOR  ".$from."  TO  ".$to;
    echo "\n";
    echo "\n";


    $i = 0;
    $totalamount = 0;

    foreach ($response as $key) {

      if ($key->ExpDate != null) {
        $expired = date('d/m/Y',strtotime($key->ExpDate));
      }else {
        $expired = '';
      }

      $array = array(
        'ISSUED STORE CODE' => $key->StoreCode,
        'ISSUED STORE NAME' => $key->StoreName,
        'VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
        'VOUCHER VALUE'     => $key->VoucherValueGift,
        'UPLOAD DATE'       => date('d/m/Y',strtotime($key->CreatedDate)),
        'EXPIRED DATE'      => $expired,
        'ISSUED DATE'       => date('d/m/Y',strtotime($key->IssuedDate)),
        'BATCH NUMBER'      => $key->BatchNumber,
        'VOUCHER STATUS'    => $key->VoucherStatusName,
        'SOURCE'            => $key->Source,
     );

     $no++;
     array_push($arraylist,$array);

     $i++;
     $totalamount += $key->VoucherValueGift;
  }

  // echo "<pre>";
  // print_r($arraylist);
  // echo "</pre>";

    if ($from == "" && $to == "") {
      $this->download_send_headers("Gift Voucher Upload Report.csv");
    }else {
      $this->download_send_headers("Gift Voucher Upload Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);
    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

 //=========================================================================================//End of Gift Voucher Upload Report

//Gift Voucher REPORT
  public function giftvoucher_report() {
    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Gift_Filter_Status');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    }

    if ($this->session->userdata('Gift_Filter_POSNumber') != 0) {
      $data['pos'] = $this->session->userdata('Gift_Filter_POSNumber');
    }

    if ($this->session->userdata('Gift_Filter_ReceiptNo') != null) {
      $data['receipt'] = $this->session->userdata('Gift_Filter_ReceiptNo');
    }

    if ($this->session->userdata('Gift_Filter_BatchNumber') != null) {
      $data['batchnumber'] = $this->session->userdata('Gift_Filter_BatchNumber');
    }

    if ($this->session->userdata('Gift_Filter_Terminal') != null) {
      $data['terminal'] = $this->session->userdata('Gift_Filter_Terminal');
    }

    if ($this->session->userdata('Gift_Filter_Reason') != 0) {
      $data['reason'] = $this->session->userdata('Gift_Filter_Reason');
    }

    if (!empty($data['store'])) {
      $storecode  = $this->Report_model->get_storecode($data['store']);
    } else {
      $storecode  = [];
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response   = $this->Report_model->giftvoucher_report($data,$permission,$storecode);
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $from = $this->session->userdata('Gift_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $to = $this->session->userdata('Gift_Filter_Enddate');
    } else {
      $to = '';
    }

    if ($this->session->userdata('Gift_Filter_Status') != 0) {
      $data['status'] = $this->session->userdata('Gift_Filter_Status');
    } else {
      $data['status'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    if ($data['status'] == 7) {
      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER REDEMPTION REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $redeemdetails = $this->Report_model->get_redeembystatus($key->GiftVouchersId);

        $searchFor = $key->StoreId;
        $filterbystore = array_values(array_filter($redeemdetails, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));

        $searchFor = $key->PosId;
        $filterbypos = array_values(array_filter($redeemdetails, function($element) use($searchFor){
          return isset($element->PosId) && $element->PosId == $searchFor;
        }));

        $searchFor = $key->ReceiptDateTime;
        $filterbydate = array_values(array_filter($redeemdetails, function($element) use($searchFor){
          return isset($element->ReceiptDateTime) && $element->ReceiptDateTime == $searchFor;
        }));

        $searchFor = $key->ReceiptNumber;
        $filterbyreceipt = array_values(array_filter($redeemdetails, function($element) use($searchFor){
          return isset($element->ReceiptNumber) && $element->ReceiptNumber == $searchFor;
        }));

        $array = array(
         'REDEMPTION STORE'       => $filterbystore[0]->StoreCode,
         'REDEMPTION STORE NAME'  => $filterbystore[0]->StoreName,
         'REDEMPTION DATE'        => date('d/m/Y',strtotime($filterbydate[0]->ReceiptDateTime)),
         'REDEMPTION TIME'        => date('H:i:s',strtotime($filterbydate[0]->ReceiptDateTime)),
         'GIFT VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
         'REDEMPTION POS'         => $filterbypos[0]->POSNumber,
         'REDEMPTION TRANSACTION' => $filterbyreceipt[0]->ReceiptNumber,
         'EXPIRED DATE'           => date('d/m/Y',strtotime($key->ExpDate)),
         'ISSUE STORE'            => $key->StoreCode,
         'ISSUE DATE'             => $key->IssuedDate,
         'GIFT VOUCHER VALUE'     => $key->VoucherValueGift,
         'SOURCE'                 => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      echo "<pre>";
      print_r($arraylist);
      echo "</pre>";

      // if ($from == "" && $to == "") {
      //   $this->download_send_headers("Gift Voucher Redemption Report.csv");
      // }else {
      //   $this->download_send_headers("Gift Voucher Redemption Report  ".$from." - ".$to.".csv");
      // }
    }elseif ($data['status'] == 6) {

      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER BLOCK REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $staffid  = $this->Report_model->get_staffid($key->GiftVouchersId,2);

        $array = array(
         'STORE CODE'           => $key->StoreCode,
         'STORE NAME'           => $key->StoreName,
         'ISSUING DATE'         => date('d/m/Y',strtotime($key->IssuedDate)),
         'GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
         'GIFT VOUCHER VALUE'   => $key->VoucherValueGift,
         'POS'                  => $key->POSNumber,
         'TRANSACTION'          => $key->ReceiptNumber,
         'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
         'BLOCK DATE'           => date('d/m/Y',strtotime($key->BlockDate)),
         'BLOCK TIME'           => date('h:i A',strtotime($key->BlockDate)),
         'BLOCK REASONS'        => $key->ReasonName,
         'BLOCKED BY'           => $staffid,
         'SOURCE'               => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      // echo "<pre>";
      // print_r($arraylist);
      // echo "</pre>";

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Block Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Block Report  ".$from." - ".$to.".csv");
      }
    }elseif ($data['status'] == 5) {

      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER CANCEL REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $staffid  = $this->Report_model->get_staffid($key->GiftVouchersId,10);

        $array = array(
         'STORE CODE'           => $key->StoreCode,
         'STORE NAME'           => $key->StoreName,
         'ISSUING DATE'         => date('d/m/Y',strtotime($key->IssuedDate)),
         'GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
         'GIFT VOUCHER VALUE'   => $key->VoucherValueGift,
         'POS'                  => $key->POSNumber,
         'TRANSACTION'          => $key->ReceiptNumber,
         'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
         'CANCEL DATE'          => date('d/m/Y',strtotime($key->CancelDate)),
         'CANCEL TIME'          => date('h:i A',strtotime($key->CancelDate)),
         'CANCEL REASONS'       => $key->ReasonName,
         'CANCELLED BY'         => $staffid,
         'SOURCE'               => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      // echo "<pre>";
      // print_r($arraylist);
      // echo "</pre>";

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Cancel Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Cancel Report  ".$from." - ".$to.".csv");
      }
    }elseif ($data['status'] == 4) {

      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER VOID REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $receipt = $this->Report_model->get_receipt_by_status($key->GiftVouchersId,5);

        $array = array(
         'VOID STORE'           => $key->StoreCode,
         'VOID STORE NAME'      => $key->StoreName,
         'VOID DATE'            => date('d/m/Y',strtotime($key->VoidDate)),
         'VOID TIME'            => date('h:i A',strtotime($key->VoidDate)),
         'VOID VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
         'VOID POS'             => $key->POSNumber,
         'VOID TRANSACTION'     => $receipt,
         'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
         'ISSUE STORE'          => $key->StoreCode,
         'ISSUE DATE'           => date('d/m/Y',strtotime($key->IssuedDate)),
         'ISSUE TIME'           => date('h:i A',strtotime($key->IssuedDate)),
         'VOUCHER AMOUNT'       => $key->VoucherValueGift,
         'SOURCE'               => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      // echo "<pre>";
      // print_r($arraylist);
      // echo "</pre>";

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Void Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Void Report  ".$from." - ".$to.".csv");
      }
    }elseif ($data['status'] == 3) {

      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER EXTEND REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $staffid  = $this->Report_model->get_staffid($key->GiftVouchersId,8);

        $array = array(
         'STORE CODE'           => $key->StoreCode,
         'STORE NAME'           => $key->StoreName,
         'ISSUING DATE'         => date('d/m/Y',strtotime($key->IssuedDate)),
         'GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
         'GIFT VOUCHER VALUE'   => $key->VoucherValueGift,
         'POS'                  => $key->POSNumber,
         'TRANSACTION'          => $key->ReceiptNumber,
         'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
         'EXTEND DATE'          => date('d/m/Y',strtotime($key->ExtendDate)),
         'EXTENDED BY'          => $staffid,
         'SOURCE'               => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      // echo "<pre>";
      // print_r($arraylist);
      // echo "</pre>";

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Extend Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Extend Report  ".$from." - ".$to.".csv");
      }
    } else {

      echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER SOLD REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "FOR  ".$from."  TO  ".$to;
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $array = array(
         'STORE CODE'           => $key->StoreCode,
         'STORE NAME'           => $key->StoreName,
         'ISSUING DATE'         => date('d/m/Y',strtotime($key->IssuedDate)),
         'GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
         'GIFT VOUCHER VALUE'   => $key->VoucherValueGift,
         'BATCH NUMBER'         => $key->BatchNumber,
         'POS'                  => $key->POSNumber,
         'TRANSACTION'          => $key->ReceiptNumber,
         'EXPIRED DATE'         => date('d/m/Y',strtotime($key->ExpDate)),
         'VOUCHER STATUS'       => $key->VoucherStatusName,
         'OPERATOR'             => $key->CashierId,
         'SOURCE'               => $key->Source,
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VoucherValueGift;
      };

      // echo "<pre>";
      // print_r($arraylist);
      // echo "</pre>";

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Sold Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Sold Report  ".$from." - ".$to.".csv");
      }
    }
    echo $this->array2csv($arraylist);
    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

  function filter_giftvoucher() {
    $data = array_merge($this->global_data);

    $get = $this->input->post();
    $this->session->set_userdata('Gift_Filter_Startdate',$get['gift_datefrom']);
    $this->session->set_userdata('Gift_Filter_Enddate',$get['gift_dateto']);
    $this->session->set_userdata('Gift_Filter_Status',$get['giftstatus']);
    $this->session->set_userdata('Gift_Filter_ReceiptNo',$get['giftreceiptno']);
    $this->session->set_userdata('Gift_Filter_BatchNumber',$get['batchnumber']);
    $this->session->set_userdata('Gift_Filter_POSNumber',$get['posnumber']);
    $this->session->set_userdata('Gift_Filter_Store',$get['giftstore']);
    $this->session->set_userdata('Gift_Filter_Terminal',$get['giftterminal']);
    $this->session->set_userdata('Gift_Filter_Reason',$get['giftreason']);

    $output['token'] = $data['csrf']['hash'];
    echo json_encode($output);
  }

//=========================================================================================//End of Gift Voucher Sold Report

  //Gift Voucher DUplicate Receipt Report
  public function gift_duplicatereceipt_report() {

    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Voucher_Filter_Store');
    }

    $permission      = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response        = $this->Report_model->gift_duplicatereceipt_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $from = $this->session->userdata('Gift_Filter_Startdate');
    } else {
      $from = '';
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $to = $this->session->userdata('Gift_Filter_Enddate');
    } else {
      $to = '';
    }

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("GIFT VOUCHER DUPLICATE RECEIPT OVER ISSUING REPORT");
    echo "\n";
    echo strtoupper("STORE: " .$bystore);
    echo "\n";
    echo "SALES DATE : ".$from." - ".$to;
    echo "\n";
    echo "\n";

    $dupreceipt = [];
    foreach ($response as $key) {
      if ($key->Total > 1) {
        $dupreceipt[] = $key->ReceiptNumber;
      }
    }

    if (!empty($dupreceipt)) {
      $group     = $this->Report_model->gift_duplicatereceipt_group($dupreceipt);
      $responses = $this->Report_model->gift_duplicatereceipt($dupreceipt);
    } else {
      $group     = [];
      $responses = [];
    }

    foreach ($group as $key) {

      $operator = $key-> CashierId;
      if ($operator != null) {
        $operator = "'".$key-> CashierId."'";
      }else {
        $operator = '';
      }

      $array = array(
        'STORE NAME'           => $key->StoreCode. ' - ' .$key->StoreName,
        'RECEIPT DATE'         => date('d/m/Y',strtotime($key->ReceiptDateTime)),
        'RECEIPT TIME'         => date('h:i A',strtotime($key->ReceiptDateTime)),
        'RECEIPT NO'           => $key->ReceiptNumber,
        'POS'                  => $key->POSNumber,
        'CASHIER ID'           => $operator,
      );

      array_push($arraylist,$array);
    };

    // echo "<pre>";
    // print_r($arraylist);
    // echo "</pre>";

    if ($from == "" && $to == "") {
      $this->download_send_headers("Gift Voucher Duplicate Receipt Over Issuing Report.csv");
    }else {
      $this->download_send_headers("Gift Voucher Duplicate Receipt Over Issuing Report  ".$from." - ".$to.".csv");
    }

    echo $this->array2csv($arraylist);
  }

//=========================================================================================//End of Gift Voucher Duplicate Receipt Report

  //Gift Voucher Duplicate Report
  // public function giftvoucher_duplicate_report(){
  //
  //   $data = array_merge($this->global_data);
  //
  //   if ($this->session->userdata('Gift_Filter_Startdate') != null) {
  //     $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Enddate') != null) {
  //     $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Status') != 0) {
  //     $data['status'] = $this->session->userdata('Gift_Filter_Status');
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Store') != 0) {
  //     $data['store'] = $this->session->userdata('Gift_Filter_Store');
  //   }
  //
  //   $permission      = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
  //   $response        = $this->Report_model->gift_duplicatevoucher_report($data,$permission);
  //   $arraylist       = [];
  //   $no              = 1;
  //
  //   if ($this->session->userdata('Gift_Filter_Status') != 0) {
  //     $data['status'] = $this->session->userdata('Gift_Filter_Status');
  //   } else {
  //     $data['status'] = '';
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Store') != 0) {
  //     $data['store'] = $this->session->userdata('Gift_Filter_Store');
  //   } else {
  //     $data['store'] = '';
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Startdate') != null) {
  //     $from = $this->session->userdata('Gift_Filter_Startdate');
  //   } else {
  //     $from = '';
  //   }
  //
  //   if ($this->session->userdata('Gift_Filter_Enddate') != null) {
  //     $to = $this->session->userdata('Gift_Filter_Enddate');
  //   } else {
  //     $to = '';
  //   }
  //
  //   if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
  //     $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
  //   } else {
  //     $data['reportstore'] = $this->session->userdata('UserId');
  //     $bystore             = $this->Report_model->get_bystore($data['reportstore']);
  //   }
  //
  //   echo "\r"; echo "\r"; echo strtoupper("DUPLICATE GIFT VOUCHER TRANSACTION REPORT");
  //   echo "\n";
  //   echo strtoupper("STORE: " .$bystore);
  //   echo "\n";
  //   echo "SALES DATE : ".$from." - ".$to;
  //   echo "\n";
  //   echo "\n";
  //
  //   $dupvoucher = [];
  //   foreach ($response as $key) {
  //     if ($key->Total > 1) {
  //       $dupvoucher[] = $key->VouchersNumber;
  //     }
  //   }
  //
  //   if (!empty($dupvoucher)) {
  //     $responses = $this->Report_model->get_giftduplicate($dupvoucher);
  //   } else {
  //     $responses = [];
  //   }
  //
  //   foreach ($responses as $key) {
  //
  //     $operator  = $key->CashierId;
  //     if ($operator != null) {
  //       $operator  = "'".$key->CashierId."'";
  //     } else {
  //       $operator  = '';
  //     }
  //
  //     $array = array(
  //      'STORE NAME'           => $key->StoreCode. ' - ' .$key->StoreName,
  //      'ISSUING DATE'         => date('d/m/Y',strtotime($key->IssuedDate)),
  //      'GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
  //      'CODE'                 => $key->VoucherShortName,
  //      'DESCRIPTION'          => $key->VoucherName,
  //      'POS'                  => $key->POSNumber,
  //      'RECEIPT DATE'         => date('d/m/Y',strtotime($key->ReceiptDateTime)),
  //      'RECEIPT TIME'         => date('h:i A',strtotime($key->ReceiptDateTime)),
  //      'RECEIPT NO'           => $key->ReceiptNumber,
  //      'AMOUNT (RM)'          => $key->VoucherValueGift,
  //      'CASHIER ID'           => $operator,
  //      'VOUCHER STATUS'       => $key->VoucherStatusName,
  //    );
  //
  //    array_push($arraylist,$array);
  //   };
  //
  //   echo "<pre>";
  //   print_r($arraylist);
  //   echo "</pre>";
  //
  //   echo $this->array2csv($arraylist);
  //   // if ($from == "" && $to == "") {
  //   //   $this->download_send_headers("Duplicate Gift Voucher Report.csv");
  //   // }else {
  //   //   $this->download_send_headers("Duplicate Gift Voucher Report  ".$from." - ".$to.".csv");
  //   // }
  // }

//=========================================================================================//End of Gift Voucher Duplicate Report

  //Gift Vouchers Reconciliation Report
  public function gift_reconciliation_report() {

    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $response   = $this->Report_model->gift_reconciliation_report($data,$permission);
    $arraylist  = [];
    $no = 1;

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("GIFT VOUCHER RECONCILIATION REPORT (RM) FOR " .$bystore);
    echo "\n";
    echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo "\n";

    $i = 0;
    $p = 0;
    $a = 0;
    $b = 0;
    $c = 0;
    $d = 0;

    $stores = [];
    foreach ($response as $row) {
      $stores[] = $row->StoreId;
    }

    $prevunredeemed  = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'].'- 1 month')),1,'P');
    $unredeemed      = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),0,'A'); //0 stand for not filtering by status
    $redeemed        = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),7,'B');
    $expired         = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),2,'C');
    $nonexpired      = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),1,'D');
    $unredeemexpired = $this->Report_model->get_total_giftvoucher_by_status_group($data['type'],$stores,date('Y-m-d', strtotime($data['start'])),2,'E');

    foreach ($response as $key) {

      if (!empty($prevunredeemed)) {
        $searchFor = $key->StoreId;
        $fprevunredeemed = array_values(array_filter($prevunredeemed, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else {
        $fprevunredeemed = 0;
      }

      if (!empty($fprevunredeemed)) {
        $fprevunredeemed = $fprevunredeemed[0]->VoucherValueGift;
      }else {
        $fprevunredeemed = 0;
      }

      if (!empty($unredeemed)) {
        $searchFor = $key->StoreId;
        $funredeemed = array_values(array_filter($unredeemed, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else {
        $funredeemed =  0;
      }

      if (!empty($funredeemed)) {
        $funredeemed = $funredeemed[0]->VoucherValueGift;
      }else {
        $funredeemed = 0;
      }

      if (!empty($redeemed)) {
        $searchFor = $key->StoreId;
        $fredeemed = array_values(array_filter($redeemed, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else {
        $fredeemed = 0;
      }

      if (!empty($fredeemed)) {
        $fredeemed = $fredeemed[0]->VoucherValueGift;
      }else {
        $fredeemed = 0;
      }

      if (!empty($expired)) {
        $searchFor = $key->StoreId;
        $fexpired = array_values(array_filter($expired, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else {
        $fexpired = 0;
      }

      if (!empty($fexpired)) {
        $fexpired = $expired[0]->VoucherValueGift;
      }else {
        $fexpired = 0;
      }

      if(!empty($nonexpired)) {
        $searchFor = $key->StoreId;
        $fnonexpired = array_values(array_filter($nonexpired, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else{
        $fnonexpired = 0;
      }

      if (!empty($fnonexpired)) {
        $fnonexpired = $fnonexpired[0]->VoucherValueGift;
      }else {
        $fnonexpired = 0;
      }

      if(!empty($unredeemexpired)) {
        $searchFor = $key->StoreId;
        $funredeemexpired = array_values(array_filter($unredeemexpired, function($element) use($searchFor){
          return isset($element->StoreId) && $element->StoreId == $searchFor;
        }));
      }else{
        $funredeemexpired = 0;
      }

      if (!empty($funredeemexpired)) {
        $funredeemexpired = $funredeemexpired[0]->VoucherValueGift;
      }else {
        $funredeemexpired = 0;
      }

      $P = $fprevunredeemed;
      $A = $funredeemed;
      $B = $fredeemed;
      $C = $fnonexpired;
      $D = $funredeemexpired;

      $array = array(
        'NO'                                    => $no,
        'STORE CODE'                            => $key->StoreCode,
        'STORE NAME'                            => $key->StoreName,
        'PREV.MONTH NON EXPIRED & UNREDEEMED
                             (P)'               => number_format($P,2,'.',''),
        'ISSUING MONTH
             (A)'                               => number_format($A,2,'.',''),
        'TOTAL REDEEMED
             (B)'                               => number_format($B,2,'.',''),
        'TOTAL ISSUED UNREDEEMED (NON EXPIRED)
                 (C) = ((P + A) - B)'           => number_format($C,2,'.',''),
        'TOTAL ISSUED UNREDEEMED (EXPIRED)
                 (C) = ((P + A) - B)'           => number_format($D,2,'.',''),
     );
     $no++;
     array_push($arraylist,$array);

     $i++;
     $p += $P;
     $a += $A;
     $b += $B;
     $c += $C;
     $d += $D;
  }

  // echo "<pre>";
  // print_r($arraylist);
  // echo "</pre>";

    if ($first == "" && $last == "") {
       $this->download_send_headers("Gift Voucher Reconciliation Report.csv");
     }else {
       $this->download_send_headers("Gift Voucher Reconciliation Report  ".$first." - ".$last.".csv");
     }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "COMPANY TOTAL: ".$i;
    echo "\n";
    echo "\n"; echo "PREV.MONTH NON EXPIRED & UNREDEEMED TOTAL: ".$p;
    echo "\n"; echo "TOTAL ISSUING MONTH: ".$a;
    echo "\n"; echo "TOTAL REDEEMED: ".$b;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (NON EXPIRED): ".$c;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (EXPIRED): ".$d;
  }

//=========================================================================================//End of Gift Voucher Reconciliation Report

  //Unredeemed & not expired Report
  public function gift_unredeemed_notexpired() {

    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Gift_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    $response = $this->Report_model->gift_unredeemed_notexpired($data['type'],$data['store'],date('Y-m-d',strtotime($data['start'])),1,'D',$permission);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("UNREDEEMED & NOT EXPIRED GIFT VOUCHERS REPORT FOR " .$bystore);
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
    echo "\n";
    echo "\n";

    $i = 0;
    $totalamount   = 0;
    $filteredstore = [];

    foreach ($response as $key) {

      $array = array(
        'NO'                              => $no,
        'ISSUED STORE CODE'               => $key->StoreCode,
        'ISSUED STORE'                    => $key->StoreName,
        'ISSUED DATE'                     => date('d/m/Y',strtotime($key->IssuedDate)),
        'EXPIRED DATE'                    => date('d/m/Y',strtotime($key->ExpDate)),
        'UNEXPIRED GIFT VOUCHER NUMBER'   => "'".$key->VouchersNumber."'",
        'GIFT VOUCHER AMOUNT'             => $key->VoucherValueGift,
        'VOUCHER TYPE'                    => $key->VoucherName,
     );

     $no++;
     array_push($arraylist,$array);

     $i++;

     if (!in_array($key->StoreId,$filteredstore)) {
       $filteredstore[] = $key->StoreId;
       $nonexpired      = $this->Report_model->get_expired_nonexpired_gift($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),1,'D');
       $C               = $nonexpired;
     }

     if ($this->session->userdata('Gift_Filter_Store') != 0) {
       $totalamount = number_format($C,2,'.','');
     } else {
       $totalamount =  $totalamount + $key->VoucherValueGift;
     }
  }

  // echo "<pre>";
  // print_r($arraylist);
  // echo "</pre>";

    if ($first == "" && $last == "") {
      $this->download_send_headers("Gift Voucher Unredeemed Not Expired Report.csv");
    }else {
      $this->download_send_headers("Gift Voucher Unredeemed Not Expired Report  ".$first." - ".$last.".csv");
    }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

//=========================================================================================//End of Gift Voucher Unredeemed Not Expired Report

  //Gift Voucher Unredeemed Expired Report
  public function gift_unredeemed_expired() {

    $data = array_merge($this->global_data);

    if ($this->session->userdata('Gift_Filter_Startdate') != null) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }

    if ($this->session->userdata('Gift_Filter_Enddate') != null) {
      $data['end'] = $this->session->userdata('Gift_Filter_Enddate');
    }

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    $arraylist  = [];
    $no         = 1;

    if ($this->session->userdata('Gift_Filter_Startdate') != null ) {
      $data['start'] = $this->session->userdata('Gift_Filter_Startdate');
    }else {
      $data['start'] = date('Y-m-d');
    }

    if ($this->session->userdata('Gift_Filter_Type') != 0) {
      $data['type'] = $this->session->userdata('Gift_Filter_Type');
    } else {
      $data['type'] = '';
    }

    if ($this->session->userdata('Gift_Filter_Store') != 0) {
      $data['store'] = $this->session->userdata('Gift_Filter_Store');
    } else {
      $data['store'] = '';
    }

    $first = date('Y-m',strtotime($data['start'])).'-01';
    $last  = date('Y-m-t',strtotime($data['start']));

    $response =  $this->Report_model->gift_unredeemed_expired($data['type'],$data['store'],date('Y-m-d', strtotime($data['start'])),2,'E',$permission);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("UNREDEEMED & EXPIRED GIFT VOUCHERS REPORT FOR " .$bystore);
    echo "\n";
    echo "FROM  ".$first."  TO  ".$last;
    echo "\n";
    echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
    echo "\n";
    echo "\n";

    $i = 0;
    $totalamount   = 0;
    $filteredstore = [];

    foreach ($response as $key) {

      $array = array(
          'No'                           => $no,
          'ISSUED STORE CODE'            => $key->StoreCode,
          'ISSUED STORE'                 => $key->StoreName,
          'ISSUED DATE'                  => date('d/m/Y',strtotime($key->IssuedDate)),
          'EXPIRED DATE'                 => date('d/m/Y',strtotime($key->ExpDate)),
          'EXPIRED GIFT VOUCHER NUMBER'  => "'".$key->VouchersNumber."'",
          'GIFT VOUCHER AMOUNT'          => $key->VoucherValueGift,
          'VOUCHER TYPE'                 => $key->VoucherName,
       );

     $no++;
     array_push($arraylist,$array);

     $i++;

     if (!in_array($key->StoreId,$filteredstore)) {
       $filteredstore[] = $key->StoreId;
       $unredeemexpired = $this->Report_model->get_expired_nonexpired_gift($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),2,'E');
       $D               = $unredeemexpired;
     }

     if ($this->session->userdata('Gift_Filter_Store') != 0) {
       $totalamount = number_format($D,2,'.','');
     } else {
       $totalamount = $totalamount + $key->VoucherValueGift;
     }
  }

    // echo "<pre>";
    // print_r($arraylist);
    // echo "</pre>";

    if ($first == "" && $last == "") {
      $this->download_send_headers("Gift Vouchers Unredeemed Expired Report.csv");
    }else {
      $this->download_send_headers("Gift Vouchers Unredeemed Expired Report  ".$first." - ".$last.".csv");
    }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
    echo "\n";
    echo "TOTAL LINE: " .$i;
  }

//=========================================================================================//End of Gift Voucher Unredeemed Expired Report
}
?>
