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
      $this->output->enable_profiler(TRUE);
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
      $response   = $this->Report_model->vouchers_report($data,$permission,$storecode);

      // echo "<pre>";
      // print_r($response);
      // echo "</pre>";

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

        // $voucherdetail = $this->Report_model->get_voucherdetail();

        // echo "<pre>";
        // print_r($voucherdetail);
        // echo "</pre>";

        foreach ($response as $key) {

          $searchFor = $key->VouchersValue;
          $filterbyvalue = array_values(array_filter($response, function($element) use($searchFor){
            return isset($element->VouchersValue) && $element->VouchersValue == $searchFor;
          }));

          $searchFor = $key->VoucherStatusName;
          $filterbystatus = array_values(array_filter($response, function($element) use($searchFor){
            return isset($element->VoucherStatusName) && $element->VoucherStatusName == $searchFor;
          }));

          $searchFor = $key->RedeemTypeId;
          $filterbyredeem= array_values(array_filter($response, function($element) use($searchFor){
            return isset($element->RedeemTypeId) && $element->RedeemTypeId == $searchFor;
          }));


          // echo "<pre>";
          // print_r($filterbyredeem);
          // echo "</pre>";

          $array = array(
           'STORE'                => $key->StoreCode,
           'NAME'                 => $key->StoreName,
           'ISSUING DATE'         => date('d/m/Y',strtotime($key->ActivityDate)),
           'ISSUING TIME'         => date('h:i A',strtotime($key->ActivityDate)),
           'VOUCHER NUMBER'       => $key->VouchersNumber,
           'TRANSACTION'          => $key->ReceiptNumber,
           'POS'                  => $key->POSNumber,
           'VOUCHER AMT'          => $filterbyvalue[0]->VouchersValue,
           'VOUCHER STATUS'       => $filterbystatus[0]->VoucherStatusName,
           'REDEEM TYPE'          => $filterbyredeem[0]->RedeemTypeName,
         );

         $no++;
         array_push($arraylist,$array);

         $i++;
         // $totalamount += $key->VouchersValue;
        };

        echo "<pre>";
        print_r($arraylist);
        echo "</pre>";

        // if ($from == "" && $to == "") {
        //   $this->download_send_headers("Voucher Report.csv");
        // }else {
        //   $this->download_send_headers("Voucher Report  ".$from." - ".$to.".csv");
        // }

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

     $response        = $this->Report_model->voucherlog_report($data,$permission);
     $arraylist       = [];
     $no              = 1;

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

     echo "\r"; echo "\r"; echo strtoupper("MONTHLY VOUCHER LOG REPORT FOR " .$bystore);
     echo "\n";
     echo "FOR  ".$from."  TO  ".$to;
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

       $array = array(
         'NO'                => $no,
         'USER ID'           => $key->StaffId,
         'NAME'              => $fullname,
         'ROLE'              => $role,
         'DATE'              => date('d/m/Y',strtotime($key->ActivityDate)),
         'CATEGORY'          => $key->VoucherActivityCategoryName,
         'DETAILS'           => $key->Details,
         'VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
         'VOUCHER TYPE'      => $key->VoucherName,
      );

      $no++;
      array_push($arraylist,$array);
      $i++;
   }

   echo "<pre>";
   print_r($arraylist);
   echo "</pre>";

    // if ($from == "" && $to == "") {
    //   $this->download_send_headers("Voucher Log Report.csv");
    // }else {
    //   $this->download_send_headers("Voucher Log Report  ".$from." - ".$to.".csv");
    // }
    // echo $this->array2csv($arraylist);
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

  //Gift Voucher Upload Report
  public function export_giftvoucher_report() {
    $data = array_merge($this->global_data);

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

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));

    $response        = $this->Report_model->giftvoucherupload_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

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

      $array = array(
        // 'NO'                => $no,
        'GIFT VOUCHER ID'   => $key->GiftVouchersId,
        'VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
        'VOUCHER VALUE'     => $key->VoucherValueGift,
        'CREATED DATE'      => date('d/m/Y',strtotime($key->CreatedDate)),
        'EXPIRED DATE'      => date('d/m/Y',strtotime($key->ExpDate)),
        'ISSUED DATE'       => date('d/m/Y',strtotime($key->IssuedDate)),
        'VOUCHER STATUS'    => $key->VoucherStatusName,
        'VOUCHER TYPE'      => $key->VoucherName,
     );

     $no++;
     array_push($arraylist,$array);

     $i++;
     $totalamount += $key->VoucherValueGift;
  }

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

 //Gift Voucher Sold Report
 public function export_giftvouchersold_report() {
   $data            = array_merge($this->global_data);

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

   $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));

   $response        = $this->Report_model->giftvouchersold_report($data,$permission);
   $arraylist       = [];
   $no              = 1;

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

   $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

   if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
     $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
   } else {
     $data['reportstore'] = $this->session->userdata('UserId');
     $bystore             = $this->Report_model->get_bystore($data['reportstore']);
   }

   echo "\r"; echo "\r"; echo strtoupper("DAILY GIFT VOUCHER SOLD FOR " .$bystore);
   echo "\n";
   echo "SOLD PERIOD:  " .$from. "  TO  " .$to;
   echo "\n";
   echo "VOUCHER TYPE: GIFT VOUCHER";
   echo "\n";
   echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
   echo "\n";
   echo "\n";

   $i = 0;
   $totalamount = 0;

   foreach ($response as $key) {

     $array = array(
        'NO'                => $no,
        'GIFT VOUCHER ID'   => $key->GiftVouchersId,
        'VOUCHER NUMBER'    => "'".$key->VouchersNumber."'",
        'VOUCHER VALUE'     => $key->VoucherValueGift,
        'EXPIRED DATE'      => date('d/m/Y',strtotime($key->ExpDate)),
        'ISSUED DATE'       => date('d/m/Y',strtotime($key->IssuedDate)),
        'VOUCHER STATUS'    => $key->VoucherStatusName,
        'VOUCHER TYPE'      => $key->VoucherName,
     );

    $no++;
    array_push($arraylist,$array);

    $i++;
    $totalamount += $key->VoucherValueGift;
 }

   if ($from == "" && $to == "") {
      $this->download_send_headers("Gift Voucher Sold Report.csv");
    }else {
      $this->download_send_headers("Gift Voucher Sold Report  ".$from." - ".$to.".csv");
    }
   echo $this->array2csv($arraylist);

   echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
   echo "\n";
   echo "TOTAL LINE: " .$i;
 }

  //=========================================================================================//End of Gift Voucher Sold Report

  // Reconciliation Voucher Report
  public function export_reconciliation_report() {
    $data            = array_merge($this->global_data);

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

    // if ($this->session->userdata('Voucher_Filter_Status') != 0) {
    //   $data['status'] = $this->session->userdata('Voucher_Filter_Status');
    // }

    $permission = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));

    $response        = $this->Report_model->reconciliation_report($data,$permission);
    $arraylist       = [];
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
        //Get unredeemed
        // $prevunredeemed  = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'].'- 1 month')),1,'P');
        // $unredeemed      = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),0,'A'); //0 stand for not filtering by status
        // $redeemed        = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),7,'B');
        // $expired         = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),2,'C');
        // $nonexpired      = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),1,'D');
        // $unredeemexpired = $this->Report_model->get_total_voucher_by_status($data['type'],$key->StoreId,date('Y-m-d', strtotime($data['start'])),2,'E');

        // print_r($prevunredeemed);
        // die;

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

      // $P = $prevunredeemed;
      // $A = $unredeemed;
      // $B = $redeemed;
      // $C = $nonexpired;
      // $D = $unredeemexpired;

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

  echo "<pre>";
  print_r($arraylist);
  echo "</pre>";
    //
    // if ($first == "" && $last == "") {
    //    $this->download_send_headers("Reconciliation Voucher Report.csv");
    //  }else {
    //    $this->download_send_headers("Reconciliation Voucher Report  ".$first." - ".$last.".csv");
    //  }
    echo $this->array2csv($arraylist);

    echo "\r"; echo "\r"; echo "COMPANY TOTAL: ".$i;
    echo "\n";
    echo "\n"; echo "PREV.MONTH NON EXPIRED & UNREDEEMED TOTAL: ".$p;
    echo "\n"; echo "TOTAL ISSUING MONTH: ".$a;
    echo "\n"; echo "TOTAL REDEEMED: ".$b;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (NON EXPIRED): ".$c;
    echo "\n"; echo "TOTAL ISSUED UNREDEEMED (EXPIRED): ".$d;
  }

//=========================================================================================//End of Gift Voucher Upload Report

  //Unredeemed & Not Expired Report
  public function export_notexpired_report()
  {
    $data            = array_merge($this->global_data);

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

    $permission      = $this->Report_model->get_permissionVoucherList($this->session->userdata('UserId'));
    // $response        = $this->Report_model->unredeemed_notexpired_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

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

  echo "<pre>";
  print_r($arraylist);
  echo "</pre>";

    // if ($first == "" && $last == "") {
    //   $this->download_send_headers("Unredeemed Not Expired Report.csv");
    // }else {
    //   $this->download_send_headers("Unredeemed Not Expired Report  ".$first." - ".$last.".csv");
    // }
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
    // $response        = $this->Report_model->unredeemed_expired_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

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

    echo "<pre>";
    print_r($arraylist);
    echo "</pre>";

    // if ($first == "" && $last == "") {
    //   $this->download_send_headers("Unredeemed Expired Report.csv");
    // }else {
    //   $this->download_send_headers("Unredeemed Expired Report  ".$first." - ".$last.".csv");
    // }
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

    $response        = $this->Report_model->voucherissuance_campaign_report($data,$permission);
    $arraylist       = [];
    $no              = 1;

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

    $data['reportstore']  = $this->session->userdata('UserId');
    $bystore              = $this->Report_model->get_bystore($data['reportstore']);

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

    $vouchertype   = $this->Report_model->get_vouchertype($data['type']);
    $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

    if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
      $bystore             = $this->Report_model->get_bystoreadmin($data['store']);
    } else {
      $data['reportstore'] = $this->session->userdata('UserId');
      $bystore             = $this->Report_model->get_bystore($data['reportstore']);
    }

    echo "\r"; echo "\r"; echo strtoupper("DUPLICATE GV/PV/DV/MRV TRANSACTION REPORT");
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

    echo "<pre>";
    print_r($arraylist);
    echo "</pre>";

    // if ($from == "" && $to == "") {
    //   $this->download_send_headers("Duplicate Voucher Report.csv");
    // }else {
    //   $this->download_send_headers("Duplicate Voucher Report  ".$from." - ".$to.".csv");
    // }

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
    $response        = $this->Report_model->duplicatereceipt_report($data,$permission);
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

    $vouchertype   = $this->Report_model->get_vouchertype($data['type']);
    $voucherstatus = $this->Report_model->get_voucherstatus($data['status']);

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

    echo "<pre>";
    print_r($arraylist);
    echo "</pre>";

    // if ($from == "" && $to == "") {
    //   $this->download_send_headers("Duplicate Receipt Over Issuing Report.csv");
    // }else {
    //   $this->download_send_headers("Duplicate Receipt Over Issuing Report  ".$from." - ".$to.".csv");
    // }

    echo $this->array2csv($arraylist);
  }
//=========================================================================================//End of Duplicate Receipt Report
}
?>
