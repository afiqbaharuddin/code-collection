<?php
  class Voucher extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('voucher/voucher_Model');
      $this->load->model('voucher/gift_Model');
      $this->load->model('voucher/gift_import_Model');
      $this->load->model('voucher/gift_manualError_Model');
      $this->load->model('voucher/predefined_Model');
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
      $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
  	}

    function promotionlist()
    {
       $data = array_merge($this->global_data);

       $data['header']         =  $this->load->view('templates/main-header',"",true);
       $data['topbar']         =  $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']        =  $this->load->view('templates/main-sidebar',"",true);
       $data['footer']         =  $this->load->view('templates/main-footer',"",true);
       $data['bottom']         =  $this->load->view('templates/main-bottom',"",true);

       $data['filterstatus']   = $this->voucher_Model->get_filterstatus()->result();
       $data['filterstore']    = $this->voucher_Model->get_filterstore()->result();
       $data['filtertype']     = $this->voucher_Model->get_filtertype()->result();

       $data['userCount']      = $this->voucher_Model->get_userCount($this->session->userdata('UserId'));

       $this->load->view('voucher/promotion_voucher_list', $data);
    }

    function info_card(){
      //all store
      $data['vouchers_issued_today']    = $this->voucher_Model->get_voucher_issued_today();
      $data['vouchers_redeem_today']    = $this->voucher_Model->get_voucher_redeem_today();
      $data['vouchers_void_today']      = $this->voucher_Model->get_voucher_void_today();
      $data['vouchers_refund_today']    = $this->voucher_Model->get_voucher_refund_today();

      //store
      $data['userCount']    = $this->voucher_Model->get_userCount($this->session->userdata('UserId'));

      $data['vouchers_issued_today_store']    = $this->voucher_Model->get_voucher_issued_today_store($data['userCount']->StoreId);
      $data['vouchers_redeem_today_store']    = $this->voucher_Model->get_voucher_redeem_today_store($data['userCount']->StoreId);
      $data['vouchers_void_today_store']      = $this->voucher_Model->get_voucher_void_today_store($data['userCount']->StoreId);
      $data['vouchers_refund_today_store']    = $this->voucher_Model->get_voucher_refund_today_store($data['userCount']->StoreId);

      echo json_encode($data);
    }

    public function voucherlisting(){
      $data = array_merge($this->global_data);
      $post = $this->input->post();

      $permission = $this->voucher_Model->get_permissionVoucherList($this->session->userdata('UserId'));
      $userid     = $this->voucher_Model->userStore($this->session->userdata('UserId'));

      //FIRST LAYER SEARCHING
  		if ($post['VoucherNumber'] != '') {
  			$post['voucher'] = $this->voucher_Model->get_voucher_number($post['VoucherNumber']);
  		}

  		if (!empty($post['PosNumber'])) {
  			$post['pos'] = $this->voucher_Model->get_voucher_pos($post['PosNumber']);
  		}

  		if ($post['VoucherStatus'] == 3 || $post['VoucherStatus'] == 5 || $post['VoucherStatus'] == 6) {
  			if ($post['StartDate'] != '' && $post['EndDate'] != '') {
  				$post['history'] = $this->voucher_Model->get_voucher_history_filter($post,$userid->UserId,$permission);
  			}
  		}
  		//FIRST LAYER SEARCHING

  		//FILTER BY CONDITION
  		if ($post['VoucherStatus'] != '' && $post['StartDate'] != '' && $post['EndDate'] != '' ) {
  			if ($post['VoucherStatus'] != 2 && $post['VoucherStatus'] != 3 && $post['VoucherStatus'] != 5 && $post['VoucherStatus'] != 6) {
  				$post['vouchid'] = [];
  				if ($post['ReceiptNumber'] != '') {
  		      $post['vouchid'] = $this->voucher_Model->get_voucher_receipt($post['ReceiptNumber']);
  		    }
  				$post['activity'] = $this->voucher_Model->get_voucher_activity($post,$userid->UserId,$permission);
  				if (!empty($post['activity'])) {
  					$post['empty'] = 1;
  				}else {
  					$post['empty'] = 2;
  				}
  			}else {
  				$post['empty'] = 3;
  			}
  		}else {
  			$post['empty'] = 3;
  		}
  		//FILTER BY CONDITION

      if ($post['VoucherStatus'] == '' && $post['StartDate'] == '' && $post['EndDate'] == '' && $post['ReceiptNumber'] == '' && $post['StoreCode'] == '' && $post['PosNumber'] == ''
      && $post['VoucherType'] == '' && $post['VoucherNumber'] == '') {
        $list        = [];
        $recordtotal = [];
        $filtered    = [];
      } else {
        $list        = $this->voucher_Model->get_datatables($post, $userid->UserId,$permission);
        $recordtotal = $this->voucher_Model->count_all($post,$userid->UserId,$permission);
        $filtered    = $this->voucher_Model->count_filtered($post,$userid->UserId,$permission);
      }

      // $list  = $this->voucher_Model->get_datatables($post,$userid->UserId,$permission);
      $table = array();
      $no    = $post['start'];

      foreach ($list as $field) {

        $no++;
        $row   = array();
        $row[] = $field->StoreCode;
  			$row[] = $field->VouchersNumber;
  			$row[] = date('Y-m-d H:i:s', strtotime($field->CreatedDate));
        $row[] = $field->VoucherName;
        $row[] = $field->VouchersValue;

        if($field->VoucherStatusId == 3){
          $extendDate = $this->voucher_Model->checkExtendDateList($field->VoucherId);
          if (isset($extendDate)) {
            $row[] = date('Y-m-d', strtotime($extendDate->ExtendDate));
          }else {
            $row[] = '';
          }
        }else {
            $row[] = date('Y-m-d', strtotime($field->ExpDate));
        }

        $row[] = '<div class="ms-3 badge bg-label-'.$field->VoucherStatusColor.'">'.$field->VoucherStatusName.'</div>';
        $row[] = '<a href="'.base_url().'voucher/VoucherDetails/promotiondetails/'.$field->VoucherId.'">
                    <div class="d-inline-block- text-nowrap">
                      <button class="btn btn-sm btn-icon">
                        <i class="bx bx-edit"></i>
                      </button>
                    </div>
                  </a>';
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $recordtotal,
        "recordsFiltered"     => $filtered,
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function get_voucherpos(){
      $data = array_merge($this->global_data);

      $storecode = $this->input->post('storecode');
      $pos       = $this->voucher_Model->get_pos($storecode);

      $list = [];
      foreach ($pos as $row) {
        $list .= '<option value="'.$row->POSNumber.'">'.$row->POSNumber.'</option>';
      }

      $result['token']   = $data['csrf']['hash'];
      $result['result']  = $list;
      echo json_encode($result);
    }

    public function export_voucher_csv()
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

      $permission      = $this->voucher_Model->get_permissionVoucherList($this->session->userdata('UserId'));
      $response        = $this->voucher_Model->voucher_report($data,$permission);
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

      $vouchertype   = $this->voucher_Model->get_vouchertype($data['type']);
      $voucherstatus = $this->voucher_Model->get_voucherstatus($data['status']);

      if ($permission->UserRoleId == 1 || $permission->StoreId == 0) {
        $bystore             = $this->voucher_Model->get_bystoreadmin($data['store']);
      } else {
        $data['reportstore'] = $this->session->userdata('UserId');
        $bystore             = $this->voucher_Model->get_bystore($data['reportstore']);
      }

      echo "\r"; echo "\r"; echo strtoupper("VOUCHER REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: " .$vouchertype);
      echo "\n";
      echo strtoupper("VOUCHER STATUS: " .$voucherstatus);
      echo "\n";
      echo "\n";

      $i = 0;
      $totalamount = 0;

      foreach ($response as $key) {

        $operator = $key->IssuanceCashierId;

        if ($key->Source == 'POS') {
          $operator = $key->IssuanceCashierId;
        }else {
          $staffid  = $this->voucher_Model->get_loginid($key->IssuanceLoginId);
          $operator = $staffid;
        }

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
         'VOUCHER STATUS'  => $key->VoucherStatusName,
         'OPERATOR'        => "'".$operator."'",
       );

       $no++;
       array_push($arraylist,$array);

       $i++;
       $totalamount += $key->VouchersValue;
      };

      if ($from == "" && $to == "") {
        $this->download_send_headers("Voucher Report.csv");
      }else {
        $this->download_send_headers("Voucher Report  ".$from." - ".$to.".csv");
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

//======================================================================================================================================

    function giftlist()
    {
      $data = array_merge($this->global_data);

      $data['header']       = $this->load->view('templates/main-header',"",true);
      $data['topbar']       = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']      = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']       = $this->load->view('templates/main-footer',"",true);
      $data['bottom']       = $this->load->view('templates/main-bottom',"",true);

      $data['filterstatus'] = $this->gift_Model->get_filterstatus()->result();
      $data['filterstore']  = $this->gift_Model->get_filterstore()->result();

      $data['userCount']    = $this->voucher_Model->get_userCount($this->session->userdata('UserId'));

      $this->load->view('voucher/gift_voucher_list', $data);
    }

    function gift_infocard(){
      //all store
      $data['evouchers_sold_today']    = $this->gift_Model->get_evoucher_sold_today();
      $data['evouchers_void_today']    = $this->gift_Model->get_evoucher_void_today();
      $data['evouchers_redeem_today']  = $this->gift_Model->get_evoucher_redeem_today();
      $data['evouchers_upload_today']  = $this->gift_Model->get_evoucher_upload_today();

      //store
      $data['userCount']    = $this->voucher_Model->get_userCount($this->session->userdata('UserId'));

      $data['evouchers_sold_today_store']    = $this->gift_Model->get_evoucher_sold_today_store($data['userCount']->StoreId);
      $data['evouchers_void_today_store']    = $this->gift_Model->get_evoucher_void_today_store($data['userCount']->StoreId);
      $data['evouchers_redeem_today_store']  = $this->gift_Model->get_evoucher_redeem_today_store($data['userCount']->StoreId);
      $data['evouchers_upload_today_store']  = $this->gift_Model->get_evoucher_upload_today_store($data['userCount']->StoreId);

      echo json_encode($data);
    }

    public function giftlisting(){
      $data = array_merge($this->global_data);
      $post = $this->input->post();

      $permission = $this->gift_Model->get_permissionVoucherList($this->session->userdata('UserId'));
      $userid     = $this->gift_Model->userStore($this->session->userdata('UserId'));

      //FIRST LAYER SEARCHING
      if ($post['VoucherNumber'] != '') {
        $post['voucher'] = $this->gift_Model->get_voucher_number($post['VoucherNumber']);
      }

      if ($post['PosNumber'] != '') {
        $post['pos'] = $this->gift_Model->get_voucher_pos($post['PosNumber']);
      }

      if ($post['VoucherStatus'] == 3 || $post['VoucherStatus'] == 5 || $post['VoucherStatus'] == 6) {
  			if ($post['StartDate'] != '' && $post['EndDate'] != '') {
  				$post['history'] = $this->gift_Model->get_voucher_history_filter($post,$userid->UserId,$permission);
  			}
  		}
      //FIRST LAYER SEARCHING

      //FILTER BY CONDITION
      if ($post['VoucherStatus'] != '' && $post['StartDate'] != '' && $post['EndDate'] != '' ) {
  			if ($post['VoucherStatus'] != 2 && $post['VoucherStatus'] != 3 && $post['VoucherStatus'] != 5 && $post['VoucherStatus'] != 6) {
  				$post['vouchid'] = [];
  				if ($post['ReceiptNumber'] != '') {
  		      $post['vouchid'] = $this->gift_Model->get_voucher_receipt($post['ReceiptNumber']);
  		    }
  				$post['activity'] = $this->gift_Model->get_voucher_activity($post,$userid->UserId,$permission);
  				if (!empty($post['activity'])) {
  					$post['empty'] = 1;
  				}else {
  					$post['empty'] = 2;
  				}
  			}else {
  				$post['empty'] = 3;
  			}
  		}else {
  			$post['empty'] = 3;
  		}
      //FILTER BY CONDITION

      if ($post['VoucherStatus'] == '' && $post['StartDate'] == '' && $post['EndDate'] == '' && $post['ReceiptNumber'] == '' && $post['StoreCode'] == '' && $post['PosNumber'] == ''
      && $post['BatchNumber'] == '' && $post['VoucherNumber'] == '') {
        $list        = [];
        $recordtotal = [];
        $filtered    = [];
      } else {
        $list        = $this->gift_Model->get_datatables($post, $userid->UserId,$permission);
        $recordtotal = $this->gift_Model->count_all($post,$userid->UserId,$permission);
        $filtered    = $this->gift_Model->count_filtered($post,$userid->UserId,$permission);
      }

      // $list  = $this->gift_Model->get_datatables($post, $userid->UserId,$permission);
      $table = array();
      $no    = $post['start'];

      foreach ($list as $field) {

        //if the status is available, there is no exp date
        if (isset($field->ExpDate)) {
          $expiredDate = date('Y-m-d', strtotime($field->ExpDate));
        }else {
          $expiredDate ='';
        }

        $no++;
        $row   = array();
  			$row[] = $field->VouchersNumber;
  			$row[] = date('Y-m-d h:i A', strtotime($field->CreatedDate));
        $row[] = date('Y-m-d', strtotime($field->IssuedDate));
        $row[] = $field->VoucherName;
        $row[] = $field->VoucherValueGift;

        if($field->VoucherStatusId == 3){
          $extendDate = $this->gift_Model->checkExtendDateList($field->GiftVouchersId);
          if (isset($extendDate)) {
            $row[] = date('Y-m-d', strtotime($extendDate->ExtendDate));
          }else {
            $row[] = '';
          }
        }else {
          $row[] = $expiredDate;
        }

        $row[] = '<div class="">
                    <span class="badge bg-label-'.$field->VoucherStatusColor.'">'.$field->VoucherStatusName.'</span>
                  </div>';

        $row[] = '<a href="'.base_url().'voucher/VoucherDetails/giftdetails/'.$field->GiftVouchersId.'">
                    <div class="d-inline-block- text-nowrap">
                      <button class="btn btn-sm btn-icon">
                        <i class="bx bx-edit"></i>
                      </button>
                    </div>
                  </a>';
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $recordtotal,
        "recordsFiltered"     => $filtered,
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    public function export_giftvoucher_csv(){
      $data = array_merge($this->global_data);

      if ($this->session->userdata('Gift_Filter_Store') != 0) {
        $data['store'] = $this->session->userdata('Gift_Filter_Store');
      }

      $permission      = $this->gift_Model->get_permissionVoucherList($this->session->userdata('UserId'));
      $response        = $this->gift_Model->giftvoucher_report($data,$permission);
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
        $bystore             = $this->gift_Model->get_bystoreadmin($data['store']);
      } else {
        $data['reportstore'] = $this->session->userdata('UserId');
        $bystore             = $this->gift_Model->get_bystore($data['reportstore']);
      }

      echo "\r"; echo "\r"; echo strtoupper("VOUCHER REPORT FOR " .$bystore);
      echo "\n";
      echo strtoupper("VOUCHER TYPE: GIFT VOUCHER");
      echo "\n";
      echo strtoupper("VOUCHER STATUS: ALL STATUS");
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
      };

      if ($from == "" && $to == "") {
        $this->download_send_headers("Gift Voucher Report.csv");
      }else {
        $this->download_send_headers("Gift Voucher Report  ".$from." - ".$to.".csv");
      }

      echo $this->array2csv($arraylist);
      echo "\r"; echo "\r"; echo "TOTAL AMOUNT: " .$totalamount;
      echo "\n";
      echo "TOTAL LINE: " .$i;
    }

//======================================================================================================================================

    //create voucher type loading page
    function createvoucher()
    {
       $data = array_merge($this->global_data);

       $data['header']               = $this->load->view('templates/main-header',"",true);
       $data['topbar']               = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']              = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']               = $this->load->view('templates/main-footer',"",true);
       $data['bottom']               = $this->load->view('templates/main-bottom',"",true);

       $data['issuancedb']           = $this->predefined_Model->get_issuance()->result();
       $data['voucherstatus_create'] = $this->predefined_Model->get_voucherstatus_create()->result();
       $data['serialize']            = $this->predefined_Model->get_serialize()->result();
       $data['position']             = $this->predefined_Model->get_position()->result();
       $data['storecredit']          = $this->predefined_Model->get_storecredit()->result();

       $this->load->view('voucher/create_voucher', $data);
    }


    function createVoucherTypeForm() {

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();

      $this->form_validation->set_rules('vouchername', 'Voucher Name', 'required');
      $this->form_validation->set_rules('vouchershortname', 'Voucher Short Name', 'required');
      $this->form_validation->set_rules('issuanceType', 'Issuance Type', 'required');
      $this->form_validation->set_rules('prefix1', 'Prefix', 'required');
      $this->form_validation->set_rules('serializeselect', 'Select Serialize or Non-Serialize', 'required');
      $this->form_validation->set_rules('voucherstatus', 'Status', 'required');
      $this->form_validation->set_rules('storecredit', 'Store Credit', 'required');

      if ($get['storecredit'] == 2) {
        $this->form_validation->set_rules('addprefix', 'Additional Prefix', 'required');
      }


      if ($get['position'] == 1 || $get['position'] == 2 || $get['position'] == 3 || $get['position'] == 4 || $get['position'] == 5 || $get['position'] == 6 || $get['position'] == 7
          || $get['position'] == 8 || $get['position'] == 9 ) {

      $this->form_validation->set_rules('unserializenum', 'Serialize or Non-Serialize Number', 'required');
      }

      //date tak masuk dengan value tak masuk
      if ($get['voucherstatus'] == 1) {
         $get['date'] = date('Y-m-d');
      }else {
        $get['date'] = null;
      }

      if($this->form_validation->run() == TRUE){

        $voucher_shortname = $get['vouchershortname'];
        $continue = 11; //true

          if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
          {
            if ($get['prefix2'] != null ) {
              if ($get['prefix2'] > $get['prefix1']) {
                $get['prefixTwo'] = $get['prefix2'];
              }else {
                $continue =99;
                $message = 'Second prefix must bigger than first prefix';
              }
            }else {
              $get['prefixTwo'] = null;
            }

            if ($get['prefixx'] == null) {
              $continue =99;
              $message = 'Please choose prefix position';
            }

            if ($get['serialCheckbox'] == null) {
              $continue =99;
              $message = 'Please choose serial position';
            }

            //sort prefix position
            if ($continue == 11) {
              $explode            = explode(",",$get['prefixx']);
              $result['sorted']   = sort($explode);
              $get['firstPrefix'] = current($explode);
              $get['lastPrefix']  = end($explode);

              //sort serial number position
              $result['sortedSerial'] = sort($get['serialCheckbox']);
              $get['firstSerial']     = current($get['serialCheckbox']);
              $get['lastSerial']      = end($get['serialCheckbox']);

              $totalvalue = $get['totalvalue'];
              $allvalue = '';
              for ($i=1; $i<= $totalvalue; $i++) {
                $valueval = $get['value_'.$i];
                if ($i == 1) {
                  $allvalue .= $valueval;
                }else {
                  $allvalue .= ','.$valueval;
                }
              }

            $count = $this->voucher_Model->count_shortname($voucher_shortname);
            if ($count == 1) {
              $this->voucher_Model->updateStatus($voucher_shortname);
            }

            if ($voucherid = $this->voucher_Model->createVoucherType($get,$allvalue))
            {
              $vouchername = $this->voucher_Model->get_vouchername($voucherid);

              $status     = true;
              $response   = "Voucher Type has been created.";
              $errorcode  = 200;
              $actmsg     = " create Voucher Type, ".$vouchername;
            } else {
              $status     = false;
              $response   = 'Something went wrong. Please try again later.';
              $errorcode  = 500;
              $actmsg     = " is trying to create Voucher Type ID ".$vouchername.". Failed.";
            }
          } else
          {
            $status    = false;
            $response  = $message;
            $errorcode = 500;
            $actmsg    = " is trying to create Voucher Type. ".$message;
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 27,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
        }
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

    //Edit voucher type loading page
    function editvoucher()
    {
       $data = array_merge($this->global_data);

       $data['header']             = $this->load->view('templates/main-header',"",true);
       $data['topbar']             = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']            = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']             = $this->load->view('templates/main-footer',"",true);
       $data['bottom']             = $this->load->view('templates/main-bottom',"",true);

       $data['VoucherTypeId']      = $this->uri->segment(4);

       $data['voucherstatus_edit'] = $this->predefined_Model->get_voucherstatus_edit()->result();
       $data['storecredit']        = $this->predefined_Model->get_storecredit()->result();

       $data['editdata']           = $this->voucher_Model->get_Editdetails($data['VoucherTypeId']);
       $data['count_vouchervalue'] = $this->voucher_Model->count_vouchervalue($data['VoucherTypeId']);

       $data['voucherval'] = explode(",",$data['count_vouchervalue']->VoucherValue);

       $this->load->view('voucher/edit_voucher', $data);
    }

    function editVoucherTypeForm(){

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();
      $id  = $get['editid'];

      $this->form_validation->set_rules('voucherstatus', 'Status', 'required');

      if ($get['voucherstatus'] == 2 ) {
        $this->form_validation->set_rules('inactiveDate', 'Inactive Date ', 'required');
      }

      if($this->form_validation->run() == TRUE){

          $data['AppLoggerId'] = $get['loggerid'];
          $totalvalue = $get['totalvalue'];

          $allvalue = '';
          for ($i=1; $i<= $totalvalue; $i++) {

            if (isset($get['value_'.$i])) {
              $valueval = $get['value_'.$i];
              if ($valueval != "") {
                if ($i == 1) {
                  $allvalue .= $valueval;
                } else {
                  $allvalue .= ','.$valueval;
                }
              }
            }
          }

          if ($this->App_logger_model->update_app_logger_data($data))
          {
            if ($this->voucher_Model->editVoucherType($get, $id,$allvalue))
            {
              $vouchername = $this->voucher_Model->get_vouchername($get['editid']);

              $status     = true;
              $response   = "Voucher Type has been updated.";
              $errorcode  = 200;
              $actmsg     = " update Voucher Type, ".$vouchername;
            } else {
              $status     = false;
              $response   = [
                "type"    => "authentication",
                "error"   => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode  = 500;
              $actmsg     = " is trying to update Voucher Type ".$vouchername.". Failed.";
            }
          } else
          {
            $status    = false;
            $response  = [
              "type"   => "authentication",
              "error"  => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode = 500;
            $actmsg    = " is trying to update Voucher Type ".$vouchername.". Applogger update failed.";
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 13,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
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

        $result['token']    = $data['csrf']['hash'];
        $result['status']   = $status;
        $result['message']  = $response;

        echo json_encode($result);
    }

    function removeVoucherValue(){

      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $data['AppLoggerId'] = $get['loggerid'];
      $this->App_logger_model->update_app_logger_data($data);

      if ($this->voucher_Model->remove_vouchervalue($get)) {
        $result['token']    = $data['csrf']['hash'];
        $result['status']   = true;
        $result['message']  = 'Success';
      }else {
        $result['token']    = $data['csrf']['hash'];
        $result['status']   = false;
        $result['message']  = 'Something went wrong';
      }
      echo json_encode($result);
    }

//======================================================================================================================================

// function import_giftvoucher_csv()
// {
//   $data = array_merge($this->global_data);
//
//   $data['IpAddress']       = $this->input->ip_address();
//   $data['OperatingSystem'] = $this->agent->platform();
//   $data['Browser']         = $this->agent->browser();
//
//   $this->output->enable_profiler(TRUE);
//   $this->load->library('csvimport');
//
//   $data['UserId']     				  = $this->session->userdata('UserId');
//
//   $array                    		= explode('.', $_FILES['file']['name']);
//   $extension                		= end($array);
//   $originalFileName         		= str_replace(' ', '_', $array[0]);
//   $newFileName              		= "upload-gift-voucher".date('YmdGis').".".$extension;
//
//   $data['CodeNumber']      			= date('YmdGis');
//   $data['NewFileName']      		= $newFileName;
//   $data['OriginalFileName'] 		= $_FILES['file']['name'];
//   $data['NewPath']          		= "uploads/import/".$newFileName;
//   $data['RequestStatusId']	    = "2";
//
//   $config['file_name']      		= $newFileName;
//   $config['upload_path']    		= "uploads/import/";
//   $config['allowed_types']  		= 'xlsx|xls|csv|txt|xltx';
//   $config['max_size']       		= '50120';
//
//   $this->load->library('upload', $config);
//   $this->upload->initialize($config);
//   $data['upload_data'] = '';
//
//   $actmsg         = '';
//   $error_log      = array();
//   $successImport  = 0;
//   $importActivity = [];
//
//   if(isset($_FILES["file"]["tmp_name"]))
//   {
//     $path        = $_FILES["file"]["tmp_name"];
//     $csvData     = $this->csvimport->parse_csv($_FILES['file']['tmp_name']);
//     $get         = $this->input->post();
//     $errorImport = '';
//
//     if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
//     {
//       if(!empty($csvData))
//       {
//         $memData     = [];
//         $csvfile     = true;
//
//         $vouchernumber  = $this->gift_Model->getGiftVoucherNumber();
//         $storeid        = $this->gift_Model->getStoreId();
//
//         foreach($csvData as $row)
//         {
//           $searchFor = $row['VOUCHER NUMBER'];
//           $gvNumber = array_values(array_filter($vouchernumber, function($element) use($searchFor){
//             return isset($element->VouchersNumber) && $element->VouchersNumber == $searchFor;
//           }));
//
//           $searchFor = $row['ISSUE STORE'];
//           $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
//             return isset($element->StoreCode) && $element->StoreCode == $searchFor;
//           }));
//
//           $searchFor   = $row['REDEEM STORE'];
//           $redeemStore = array_values(array_filter($storeid, function($element) use($searchFor){
//             return isset($element->StoreCode) && $element->StoreCode == $searchFor;
//           }));
//
//           $issuedate  = implode("-", array_reverse(explode("/", $row['ISSUE DATE'])));
//           $expirydate = implode("-", array_reverse(explode("/", $row['EXPIRY DATE'])));
//
//             if (!empty($storecode))
//             {
//               if (isset($vouchernumber))
//               {
//                 if ($row['ACTION'] == null) {
//                   $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                       <td>'.$row['VOUCHER NUMBER'].'</td>
//                                       <td>'.$row['ISSUE STORE'].'</td>
//                                       <td>Voucher already exist. The Batch Number is '.$row['BATCH NUMBER'].'.</td>
//                                     </tr>';
//                   $message = 'Some info is wrong/missing!';
//                   $csvfile = false;
//                   $actmsg  = " is trying to Import Existing Gift Voucher. Failed.";
//                   $error_log[] = array(
//                     'VoucherNumber' => $row['VOUCHER NUMBER'],
//                     'ImportDate'    => date('Y-m-d H:i:s'),
//                     'ErrorMessage'  => 'Voucher already exist. The Batch Number is '.$row['BATCH NUMBER'].'.'
//                   );
//                 }
//                 else
//                 {
//                     if ($vouchernumber->VoucherStatusId == 1 || $vouchernumber->VoucherStatusId == 3 || $vouchernumber->VoucherStatusId == 10)
//                     {
//                       if ($row['ACTION'] == 'CANCEL')
//                       {
//                         $voucheractivity = [
//                           'GiftVouchersId' => $vouchernumber->GiftVouchersId,
//                           'CancelDate'     => date('Y-m-d H:i:s'),
//                           'CancelReasons'  => 3
//                         ];
//                         $giftstatus = 5;
//
//                       }
//                       elseif ($row['ACTION'] == 'BLOCK')
//                       {
//                         $voucheractivity = [
//                           'GiftVouchersId' => $vouchernumber->GiftVouchersId,
//                           'BlockDate'      => date('Y-m-d H:i:s'),
//                           'BlockReasons'   => 3
//                         ];
//                         $giftstatus = 6;
//                       }
//                       $voucherdetails = [
//                         'GiftVouchersId'  => $vouchernumber->GiftVouchersId,
//                         'VoucherStatusId' => $giftstatus
//                       ];
//
//                       if ($this->gift_Model->updateGift($voucherdetails) || $this->gift_Model->insertGiftHistory($voucheractivity)) {
//                         $successImport++;
//                         // $importActivity[] = $row['VOUCHER NUMBER'];
//                       }
//                     }
//                     else
//                     {
//                       $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                           <td>'.$row['VOUCHER NUMBER'].'</td>
//                                           <td>'.$row['ISSUE STORE'].'</td>
//                                           <td>Not allowed to override ACTION on this voucher.</td>
//                                         </tr>';
//                       $message = 'Some info is wrong/missing!';
//                       $csvfile = false;
//                       $actmsg  = " is trying to Import Existing Gift Voucher. Failed.";
//                       $error_log[] = array(
//                         'VoucherNumber' => $row['VOUCHER NUMBER'],
//                         'ImportDate'    => date('Y-m-d H:i:s'),
//                         'ErrorMessage'  => 'Not allowed to override ACTION on this voucher.'
//                       );
//                     }
//                 }
//               }elseif ($row['BATCH NUMBER'] == null) {
//                 $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                    <td>'.$row['VOUCHER NUMBER'].'</td>
//                                    <td>'.$row['ISSUE STORE'].'</td>
//                                    <td>Batch Number cannot be Empty!</td>
//                                  </tr>';
//                 $message = 'Some info is wrong/missing!';
//                 $csvfile = false;
//                 $actmsg  = " is trying to Import Gift Voucher without Batch Number. Failed.";
//                 $error_log[] = array(
//                   'VoucherNumber' => $row['VOUCHER NUMBER'],
//                   'ImportDate'    => date('Y-m-d H:i:s'),
//                   'ErrorMessage'  => 'Batch Number cannot be Empty!'
//                 );
//               }else {
//                 if ($row['VOUCHER AMOUNT'] != 0 || $row['VOUCHER AMOUNT'] != "0" || $row['VOUCHER AMOUNT'] != "0.0")
//                   {
//                     if (empty($row['REDEEM STORE']) || !empty($redeemStore)) {
//                       if (empty($row['REDEEM STORE']) || $redeemStore[0]->StoreStatusId == 1) {
//                         if ($row['EXPIRY DATE'] != null) {
//                           $memData[] = array(
//                               'StoreId'            => $storecode[0]->StoreId,
//                               'IssuedDate'         => date('Y-m-d',strtotime($issuedate)),
//                               'VouchersNumber'     => $row['VOUCHER NUMBER'],
//                               'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
//                               'RedeemStore'        => empty($row['REDEEM STORE']) ? null : $redeemStore[0]->StoreCode,
//                               'ExpDate'            => date('Y-m-d', strtotime($expirydate)),
//                               'BatchNumber'        => $row['BATCH NUMBER'],
//                               'VoucherStatusId'    => 1,
//                               'VoucherTypeId'      => 1,
//                               'VoucherIssuanceId'  => 0,
//                               'AppLoggerId'        => $get['loggerid'],
//                             );
//                         }else {
//                           $memData[] = array(
//                             'StoreId'            => $storecode[0]->StoreId,
//                             'IssuedDate'         => date('Y-m-d', strtotime($issuedate)),
//                             'VouchersNumber'     => $row['VOUCHER NUMBER'],
//                             'VoucherIssuanceId'  => 0,
//                             'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
//                             'RedeemStore'        => empty($row['REDEEM STORE']) ? null : $redeemStore[0]->StoreCode,
//                             'BatchNumber'        => $row['BATCH NUMBER'],
//                             'VoucherStatusId'    => 10,
//                             'VoucherTypeId'      => 1,
//                             'AppLoggerId'        => $get['loggerid'],
//                             );
//                         }
//                         $importActivity[] = $row['VOUCHER NUMBER'];
//                       }else {
//                         $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                             <td>'.$row['VOUCHER NUMBER'].'</td>
//                                             <td>'.$row['ISSUE STORE'].'</td>
//                                             <td>Redeem Store not active</td>
//                                           </tr>';
//                         $message = 'Some info is wrong/missing!';
//                         $csvfile = false;
//                         $actmsg  = " is trying to Import Gift Voucher. Inactive Redeem Store.";
//                         $error_log[] = array(
//                           'VoucherNumber' => $row['VOUCHER NUMBER'],
//                           'ImportDate'    => date('Y-m-d H:i:s'),
//                           'ErrorMessage'  => 'Redeem Store not active'
//                         );
//                       }
//                     } else {
//                       $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                           <td>'.$row['VOUCHER NUMBER'].'</td>
//                                           <td>'.$row['ISSUE STORE'].'</td>
//                                           <td>Redeem Store not exist!</td>
//                                         </tr>';
//
//                       $message = 'Some info is wrong/missing!';
//                       $csvfile = false;
//                       $actmsg  = " is trying to Import Gift Voucher. Redeem Store not Exist.";
//                       $error_log[] = array(
//                         'VoucherNumber' => $row['VOUCHER NUMBER'],
//                         'ImportDate'    => date('Y-m-d H:i:s'),
//                         'ErrorMessage'  => 'Redeem Store not exist!'
//                       );
//                     }
//                   }else {
//                     $errorImport .= '
//                                       <tr class="bg-danger text-white" style="font-weight:bold">
//                                         <td>'.$row['VOUCHER NUMBER'].'</td>
//                                         <td>'.$row['ISSUE STORE'].'</td>
//                                         <td>Not allowed to import with Voucher Amount 0.</td>
//                                       </tr>';
//                       $message = 'Imported Voucher cannot have Amount 0.';
//                       $csvfile = false;
//                       $actmsg  = " is trying to Import Vouchers with Amount RM 0. Failed.";
//                       $error_log[] = array(
//                         'VoucherNumber' => $row['VOUCHER NUMBER'],
//                         'ImportDate'    => date('Y-m-d H:i:s'),
//                         'ErrorMessage'  => 'Not allowed to import with Voucher Amount 0.'
//                       );
//                   }
//               }
//             }
//             else
//             {
//               $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                                   <td>'.$row['VOUCHER NUMBER'].'</td>
//                                   <td>'.$row['ISSUE STORE'].'</td>
//                                   <td>Store not exist!</td>
//                                 </tr>';
//
//               $message = 'Some info is wrong/missing!';
//               $csvfile = false;
//               $actmsg  = " is trying to Import Gift Voucher. Store not Exist.";
//               $error_log[] = array(
//                 'VoucherNumber' => $row['VOUCHER NUMBER'],
//                 'ImportDate'    => date('Y-m-d H:i:s'),
//                 'ErrorMessage'  => 'Store not exist!'
//               );
//             }
//         }
//
//         if (!empty($memData)) {
//           $this->gift_Model->insertImportGift($memData);
//           //retrieve gv id
//           $voucherMapping = $this->gift_Model->getVoucherMapping(array_column($memData,'VouchersNumber'));
//           $successImport += count($memData);
//         }
//       } else {
//         $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
//                             <td>-</td>
//                             <td>-</td>
//                             <td>CSV File Empty!</td>
//                           </tr>';
//
//         $message = 'CSV file is empty';
//         $csvfile = false;
//         $actmsg  = " is trying to Import Gift Voucher. CSV file empty.";
//         $error_log[] = array(
//           'VoucherNumber' => 'Empty CSV File',
//           'ImportDate'    => date('Y-m-d H:i:s'),
//           'ErrorMessage'  => 'CSV File Empty!'
//         );
//       }
//
//       if (!empty($error_log)) {
//         $batchSize = 1000;
//         $chunks    = array_chunk($error_log,$batchSize);
//         foreach ($chunks as $chunk) {
//           $this->db->insert_batch('manual_import_errorlog', $chunk);
//         }
//       }
//     }
//
//     if (!$this->upload->do_upload('file',$newFileName))
//     {
//       $data['invalid']              = FALSE;
//       $message = $this->upload->display_errors();
//     } else {
//       $data['invalid']              = FALSE;
//       $upload_data                  = $this->upload->data();
//       $downloadsData['file']        = $upload_data['file_name'];
//       $data['CreatedDate']          = date("Y-m-d H:i:s", time() );
//       $data['CreatedBy']            = $data['UserId'];
//       if ($csvfile == true) {
//         $message = 'File has been uploaded. Total Line is '.$successImport;
//         $actmsg  = " Successfully Import Gift Voucher.";
//       }
//     }
//
//     $act = [
//         'UserId'           => $data['UserId'],
//         'ActivityTypeId'   => 37,
//         'ActivityDetails'  => $data['Fullname'].$actmsg,
//         'ActiveDate'       => date('Y-m-d H:i:s'),
//         'IpAddress'        => $data['IpAddress'],
//         'OperatingSystem'  => $data['OperatingSystem'],
//         'Browser'          => $data['Browser'],
//       ];
//
//       $this->ActivityLog_Model->insert_activity($act);
//
//       if (!empty($importActivity)) {
//         $act2 = [];
//         foreach ($importActivity as $voucherNumber) {
//           $act2[] = [
//             'UserId'                      => $data['UserId'],
//             'VoucherActivityCategoryId'   => 14,
//             'Details'                     => $data['Fullname'].' import Gift Voucher '.$voucherNumber,
//             'ActivityDate'                => date('Y-m-d H:i:s'),
//             'VouchersId'                  => $voucherMapping[$voucherNumber],
//             'VouchersNumber'              => $voucherNumber,
//             'VoucherTypeId'               => 1,
//             'Source'                      => 2,
//           ];
//         }
//         if (!empty($act2)) {
//           $this->VoucherLog_Model->insert_batch_activity($act2);
//         }
//       }
//     }
//
//     $result['csv']           = $csvfile;
//     $result['errorimport']   = $errorImport;
//     $result['message']       = $message;
//     $result['token']         = $data['csrf']['hash'];
//     $result['successImport'] = $successImport;
//
//     echo json_encode($result);
//   }

function update_csv(){
  $data = array_merge($this->global_data);

  $data['IpAddress']       = $this->input->ip_address();
  $data['OperatingSystem'] = $this->agent->platform();
  $data['Browser']         = $this->agent->browser();

  // $this->output->enable_profiler(TRUE);
  $this->load->library('csvimport');

  $data['UserId']     				  = $this->session->userdata('UserId');

  $array                    		= explode('.', $_FILES['file']['name']);
  $extension                		= end($array);
  $originalFileName         		= str_replace(' ', '_', $array[0]);
  $newFileName              		= "upload-gift-voucher".date('YmdGis').".".$extension;

  $data['CodeNumber']      			= date('YmdGis');
  $data['NewFileName']      		= $newFileName;
  $data['OriginalFileName'] 		= $_FILES['file']['name'];
  $data['NewPath']          		= "uploads/import/".$newFileName;
  $data['RequestStatusId']	    = "2";

  $config['file_name']      		= $newFileName;
  $config['upload_path']    		= "uploads/import/";
  $config['allowed_types']  		= 'xlsx|xls|csv|txt|xltx';
  $config['max_size']       		= '50120';

  $this->load->library('upload', $config);
  $this->upload->initialize($config);
  $data['upload_data'] = '';

  if (isset($_FILES["file"]["tmp_name"])) {
    $path        = $_FILES["file"]["tmp_name"];
    $csvData     = $this->csvimport->parse_csv($_FILES['file']['tmp_name']);
    $get         = $this->input->post();

    if (!empty($csvData)) {
      $csvfile         = true;
      $voucherno       = [];

      foreach ($csvData as $row) {
        $voucherno[] = $row['VOUCHER NUMBER'];
      }
      $existingVoucherNumber  = $this->gift_Model->get_giftvoucherno($voucherno);

      foreach ($csvData as $row) {
        if (in_array($row['VOUCHER NUMBER'],$existingVoucherNumber)) {

          $gvNumber      = $this->gift_Model->get_gvnumber($row['VOUCHER NUMBER']);
          $oldexpirydate = $gvNumber[0]->ExpDate;
          $newexpirydate = implode("-", array_reverse(explode("/", $row['EXPIRY DATE'])));

          $voucheractivity = [
            'GiftVouchersId' => $gvNumber[0]->GiftVouchersId,
            'ExtendDate'     => date('Y-m-d', strtotime($newexpirydate)),
          ];
          $this->gift_Model->insertGiftHistory($voucheractivity);

          $voucherdetails = [
            'GiftVouchersId'  => $gvNumber[0]->GiftVouchersId,
            'ExpDate'         => date('Y-m-d', strtotime($newexpirydate)),
            'Remarks'         => "File upload expiry date: " .date('Y-m-d', strtotime($oldexpirydate)),
            'VoucherStatusId' => 3,
          ];
          $this->gift_Model->updateGift($voucherdetails);
        }
      }
    }
  }

  $result['csv']           = $csvfile;
  $result['token']         = $data['csrf']['hash'];

  echo json_encode($result);
}

function import_giftvoucher_csv()
{
  $data = array_merge($this->global_data);

  $data['IpAddress']       = $this->input->ip_address();
  $data['OperatingSystem'] = $this->agent->platform();
  $data['Browser']         = $this->agent->browser();

  // $this->output->enable_profiler(TRUE);
  $this->load->library('csvimport');

  $data['UserId']     				  = $this->session->userdata('UserId');

  $array                    		= explode('.', $_FILES['file']['name']);
  $extension                		= end($array);
  $originalFileName         		= str_replace(' ', '_', $array[0]);
  $newFileName              		= "upload-gift-voucher".date('YmdGis').".".$extension;

  $data['CodeNumber']      			= date('YmdGis');
  $data['NewFileName']      		= $newFileName;
  $data['OriginalFileName'] 		= $_FILES['file']['name'];
  $data['NewPath']          		= "uploads/import/".$newFileName;
  $data['RequestStatusId']	    = "2";

  $config['file_name']      		= $newFileName;
  $config['upload_path']    		= "uploads/import/";
  $config['allowed_types']  		= 'xlsx|xls|csv|txt|xltx';
  $config['max_size']       		= '50120';

  $this->load->library('upload', $config);
  $this->upload->initialize($config);
  $data['upload_data'] = '';

  $actmsg         = '';
  $error_log      = array();
  $successImport  = 0;
  $importActivity = [];

  if(isset($_FILES["file"]["tmp_name"]))
  {
    $path        = $_FILES["file"]["tmp_name"];
    $csvData     = $this->csvimport->parse_csv($_FILES['file']['tmp_name']);
    $get         = $this->input->post();
    $errorImport = '';

    if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
    {
      if(!empty($csvData))
      {
        $memData     = [];
        $csvfile     = true;
        $storeid     = $this->gift_Model->getStoreId();
        $voucherno   = [];

        foreach ($csvData as $row) {
          $voucherno[] = $row['VOUCHER NUMBER'];
        }
        // $existingVoucherNumber  = $this->gift_Model->getExistingVoucherNumber();
        $existingVoucherNumber  = $this->gift_Model->get_giftvoucherno($voucherno);

        foreach($csvData as $row)
        {
          if (in_array($row['VOUCHER NUMBER'],$existingVoucherNumber)) {
            if ($row['ACTION'] == null) {
              $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                  <td>' . $row['VOUCHER NUMBER'] . '</td>
                                  <td>' . $row['ISSUE STORE'] . '</td>
                                  <td>Voucher already exist. The Batch Number is ' . $row['BATCH NUMBER'] . '.</td>
                               </tr>';
              $message = 'Some info is wrong/missing!';
              $csvfile = false;
              $actmsg = " is trying to Import Existing Gift Voucher. Failed.";
              $error_log[] = array(
                  'VoucherNumber' => $row['VOUCHER NUMBER'],
                  'ImportDate' => date('Y-m-d H:i:s'),
                  'ErrorMessage' => 'Voucher already exist. The Batch Number is ' . $row['BATCH NUMBER'] . '.'
              );
            }else {
              $gvNumber = $this->gift_Model->getVoucherByNumber($row['VOUCHER NUMBER']);

              if ($gvNumber[0]->VoucherStatusId == 1 || $gvNumber[0]->VoucherStatusId == 3 || $gvNumber[0]->VoucherStatusId == 10) {
                if ($row['ACTION'] == 'CANCEL') {
                  $voucheractivity = [
                      'GiftVouchersId' => $gvNumber[0]->GiftVouchersId,
                      'CancelDate'     => date('Y-m-d H:i:s'),
                      'CancelReasons'  => 3
                  ];
                  $giftstatus = 5;
                }elseif ($row['ACTION'] == 'BLOCK') {
                  $voucheractivity = [
                      'GiftVouchersId' => $gvNumber[0]->GiftVouchersId,
                      'BlockDate'      => date('Y-m-d H:i:s'),
                      'BlockReasons'   => 3
                  ];
                  $giftstatus = 6;
                }
                $voucherdetails = [
                    'GiftVouchersId' => $gvNumber[0]->GiftVouchersId,
                    'VoucherStatusId' => $giftstatus
                ];

                if ($this->gift_Model->updateGift($voucherdetails) || $this->gift_Model->insertGiftHistory($voucheractivity)) {
                  $successImport++;
                  // $importActivity[] = $row['VOUCHER NUMBER'];
                }
              }else {
                $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                    <td>' . $row['VOUCHER NUMBER'] . '</td>
                                    <td>' . $row['ISSUE STORE'] . '</td>
                                    <td>Not allowed to override ACTION on this voucher.</td>
                                 </tr>';
                $message = 'Some info is wrong/missing!';
                $csvfile = false;
                $actmsg = " is trying to Import Existing Gift Voucher. Failed.";
                $error_log[] = array(
                  'VoucherNumber' => $row['VOUCHER NUMBER'],
                  'ImportDate' => date('Y-m-d H:i:s'),
                  'ErrorMessage' => 'Not allowed to override ACTION on this voucher.'
                );
              }
            }
            continue;
          }

          $searchFor = $row['ISSUE STORE'];
          $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
            return isset($element->StoreCode) && $element->StoreCode == $searchFor;
          }));

          $searchFor   = $row['REDEEM STORE'];
          $redeemStore = array_values(array_filter($storeid, function($element) use($searchFor){
            return isset($element->StoreCode) && $element->StoreCode == $searchFor;
          }));

          $issuedate  = implode("-", array_reverse(explode("/", $row['ISSUE DATE'])));
          $expirydate = implode("-", array_reverse(explode("/", $row['EXPIRY DATE'])));

            if (!empty($storecode))
            {
              if ($row['BATCH NUMBER'] == null) {
                $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                   <td>'.$row['VOUCHER NUMBER'].'</td>
                                   <td>'.$row['ISSUE STORE'].'</td>
                                   <td>Batch Number cannot be Empty!</td>
                                 </tr>';
                $message = 'Some info is wrong/missing!';
                $csvfile = false;
                $actmsg  = " is trying to Import Gift Voucher without Batch Number. Failed.";
                $error_log[] = array(
                  'VoucherNumber' => $row['VOUCHER NUMBER'],
                  'ImportDate'    => date('Y-m-d H:i:s'),
                  'ErrorMessage'  => 'Batch Number cannot be Empty!'
                );
              }else {
                if ($row['VOUCHER AMOUNT'] != 0 || $row['VOUCHER AMOUNT'] != "0" || $row['VOUCHER AMOUNT'] != "0.0")
                  {
                    if (empty($row['REDEEM STORE']) || !empty($redeemStore)) {
                      if (empty($row['REDEEM STORE']) || $redeemStore[0]->StoreStatusId == 1) {
                        if ($row['EXPIRY DATE'] != null) {
                          $memData[] = array(
                              'StoreId'            => $storecode[0]->StoreId,
                              'IssuedDate'         => date('Y-m-d',strtotime($issuedate)),
                              'VouchersNumber'     => $row['VOUCHER NUMBER'],
                              'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
                              'RedeemStore'        => empty($row['REDEEM STORE']) ? null : $redeemStore[0]->StoreCode,
                              'ExpDate'            => date('Y-m-d', strtotime($expirydate)),
                              'BatchNumber'        => $row['BATCH NUMBER'],
                              'VoucherStatusId'    => 1,
                              'VoucherTypeId'      => 1,
                              'VoucherIssuanceId'  => 0,
                              'AppLoggerId'        => $get['loggerid'],
                            );
                        }else {
                          $memData[] = array(
                            'StoreId'            => $storecode[0]->StoreId,
                            'IssuedDate'         => date('Y-m-d', strtotime($issuedate)),
                            'VouchersNumber'     => $row['VOUCHER NUMBER'],
                            'VoucherIssuanceId'  => 0,
                            'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
                            'RedeemStore'        => empty($row['REDEEM STORE']) ? null : $redeemStore[0]->StoreCode,
                            'BatchNumber'        => $row['BATCH NUMBER'],
                            'VoucherStatusId'    => 10,
                            'VoucherTypeId'      => 1,
                            'AppLoggerId'        => $get['loggerid'],
                            );
                        }
                        $importActivity[] = $row['VOUCHER NUMBER'];
                      }else {
                        $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                            <td>'.$row['VOUCHER NUMBER'].'</td>
                                            <td>'.$row['ISSUE STORE'].'</td>
                                            <td>Redeem Store not active</td>
                                          </tr>';
                        $message = 'Some info is wrong/missing!';
                        $csvfile = false;
                        $actmsg  = " is trying to Import Gift Voucher. Inactive Redeem Store.";
                        $error_log[] = array(
                          'VoucherNumber' => $row['VOUCHER NUMBER'],
                          'ImportDate'    => date('Y-m-d H:i:s'),
                          'ErrorMessage'  => 'Redeem Store not active'
                        );
                      }
                    } else {
                      $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                          <td>'.$row['VOUCHER NUMBER'].'</td>
                                          <td>'.$row['ISSUE STORE'].'</td>
                                          <td>Redeem Store not exist!</td>
                                        </tr>';

                      $message = 'Some info is wrong/missing!';
                      $csvfile = false;
                      $actmsg  = " is trying to Import Gift Voucher. Redeem Store not Exist.";
                      $error_log[] = array(
                        'VoucherNumber' => $row['VOUCHER NUMBER'],
                        'ImportDate'    => date('Y-m-d H:i:s'),
                        'ErrorMessage'  => 'Redeem Store not exist!'
                      );
                    }
                  }else {
                    $errorImport .= '
                                      <tr class="bg-danger text-white" style="font-weight:bold">
                                        <td>'.$row['VOUCHER NUMBER'].'</td>
                                        <td>'.$row['ISSUE STORE'].'</td>
                                        <td>Not allowed to import with Voucher Amount 0.</td>
                                      </tr>';
                      $message = 'Imported Voucher cannot have Amount 0.';
                      $csvfile = false;
                      $actmsg  = " is trying to Import Vouchers with Amount RM 0. Failed.";
                      $error_log[] = array(
                        'VoucherNumber' => $row['VOUCHER NUMBER'],
                        'ImportDate'    => date('Y-m-d H:i:s'),
                        'ErrorMessage'  => 'Not allowed to import with Voucher Amount 0.'
                      );
                  }
              }
            }
            else
            {
              $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                  <td>'.$row['VOUCHER NUMBER'].'</td>
                                  <td>'.$row['ISSUE STORE'].'</td>
                                  <td>Store not exist!</td>
                                </tr>';

              $message = 'Some info is wrong/missing!';
              $csvfile = false;
              $actmsg  = " is trying to Import Gift Voucher. Store not Exist.";
              $error_log[] = array(
                'VoucherNumber' => $row['VOUCHER NUMBER'],
                'ImportDate'    => date('Y-m-d H:i:s'),
                'ErrorMessage'  => 'Store not exist!'
              );
            }
        }

        if (!empty($memData)) {
          $this->gift_Model->insertImportGift($memData);
          //retrieve gv id
          // $voucherMapping = $this->gift_Model->getVoucherMapping(array_column($memData,'VouchersNumber'));
          $successImport += count($memData);
        }
      } else {
        $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                            <td>-</td>
                            <td>-</td>
                            <td>CSV File '.$data['OriginalFileName'].' is Empty!</td>
                          </tr>';

        $message = 'CSV file is empty';
        $csvfile = false;
        $actmsg  = " is trying to Import Gift Voucher. CSV file, ".$data['OriginalFileName']." is empty.";
        $error_log[] = array(
          'VoucherNumber' => 'Empty CSV File',
          'ImportDate'    => date('Y-m-d H:i:s'),
          'ErrorMessage'  => 'CSV File '.$data['OriginalFileName'].' is Empty!'
        );
      }

      if (!empty($error_log)) {
        $batchSize = 1000;
        $chunks    = array_chunk($error_log,$batchSize);
        foreach ($chunks as $chunk) {
          $this->db->insert_batch('manual_import_errorlog', $chunk);
        }
      }
    }

    if (!$this->upload->do_upload('file',$newFileName))
    {
      $data['invalid']              = FALSE;
      $message = $this->upload->display_errors();
    } else {
      $data['invalid']              = FALSE;
      $upload_data                  = $this->upload->data();
      $downloadsData['file']        = $upload_data['file_name'];
      $data['CreatedDate']          = date("Y-m-d H:i:s", time() );
      $data['CreatedBy']            = $data['UserId'];
      if ($csvfile == true) {
        $message = 'File has been uploaded. Total Line is '.$successImport;
        $actmsg  = " Successfully Import Gift Voucher. CSV file name: " .$data['OriginalFileName']. ". Total Voucher Imported: ".$successImport;
      }
    }

    $act = [
        'UserId'           => $data['UserId'],
        'ActivityTypeId'   => 37,
        'ActivityDetails'  => $data['Fullname'].$actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);

      if (!empty($importActivity)) {
        $act2 = [];
        foreach ($importActivity as $voucherNumber) {
          $act2[] = [
            'UserId'                      => $data['UserId'],
            'VoucherActivityCategoryId'   => 14,
            'Details'                     => $data['Fullname'].' import Gift Voucher '.$voucherNumber,
            'ActivityDate'                => date('Y-m-d H:i:s'),
            // 'VouchersId'                  => $voucherMapping[$voucherNumber],
            'VouchersNumber'              => $voucherNumber,
            'VoucherTypeId'               => 1,
            'Source'                      => 2,
          ];
        }
        if (!empty($act2)) {
          $this->VoucherLog_Model->insert_batch_activity($act2);
        }
      }
    }

    $result['csv']           = $csvfile;
    $result['errorimport']   = $errorImport;
    $result['message']       = $message;
    $result['token']         = $data['csrf']['hash'];
    $result['successImport'] = $successImport;

    echo json_encode($result);
  }

  function import_giftvoucher_scheduler(){
    $data = array_merge($this->global_data);

    $data['header']  = $this->load->view('templates/main-header',"",true);
    $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']  = $this->load->view('templates/main-footer',"",true);
    $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

    $this->load->view('voucher/import_gift_voucher_scheduler', $data);
  }

  function import_giftvoucher_listing(){
    $data  = array_merge($this->global_data);
    $post  = $this->input->post();
    $id    = $this->uri->segment(4);
    $list  = $this->gift_import_Model->get_datatables($post,$id);
    $table = array();
    $no    = $post['start'];

    foreach ($list as $field) {

      $no++;
      $row   = array();
      $row[] = $field->CSVFileName;
      $row[] = date('h:i A', strtotime($field->ScheduledImportTime));
      $row[] = $field->ImportError;
      $row[] = '<div class="ms-3 badge bg-label-'.$field->ImportStatusColor.'">'.$field->ImportStatusName.'</div>';
      $row[] = '<a href="'.base_url().'voucher/VoucherDetails/errorLog_details/'.$field->CSVFileId.'">
                  <div class="d-inline-block- text-nowrap">
                    <button class="btn btn-sm btn-icon">
                      <i class="bx bx-edit"></i>
                    </button>
                  </div>
                </a>';

      $table[] = $row;
    }

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->gift_import_Model->count_all($post),
      "recordsFiltered"     => $this->gift_import_Model->count_filtered($post),
      "data"                => $table,
      $data['csrf']['name'] => $data['csrf']['hash']
    );
    echo json_encode($output);
  }

  function scheduler_import_giftvoucher(){
    $data   = array_merge($this->global_data);
    $get    = $this->input->post();

    $this->load->library('csvimport');

    $this->form_validation->set_rules('filename','CSV File Name','required');

    if (empty($_FILES['importfile']['name'])) {
      $this->form_validation->set_rules('importfile','CSV File','required');
    }

    $this->form_validation->set_rules('importTime','Scheduled Import Time','required');

    $status = false;
    if ($this->form_validation->run() == TRUE) {

      $array                    		= explode('.', $_FILES['importfile']['name']);
      $extension                		= end($array);
      $originalFileName         		= str_replace(' ', '_', $array[0]);
      $newFileName              		= "upload-gift-voucher".date('YmdGis').".".$extension;

      $data['CodeNumber']      			= date('YmdGis');
      $data['NewFileName']      		= $newFileName;
      $data['OriginalFileName'] 		= $_FILES['importfile']['name'];
      $data['NewPath']          		= "uploads/pending-import/".$newFileName;
      $data['RequestStatusId']	    = "2";

      $config['file_name']      		= $newFileName;
      $config['upload_path']    		= "uploads/pending-import/";
      $config['allowed_types']  		= 'xlsx|xls|csv|txt|xltx';
      $config['max_size']       		= '50120';

      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      $data['upload_data'] = '';

      if (isset($_FILES["importfile"]["tmp_name"])) {
        $path    = $_FILES["importfile"]["tmp_name"];
        $csvData = $this->csvimport->parse_csv($_FILES['importfile']['tmp_name']);
        $get     = $this->input->post();

        if (!empty($csvData)) {
          $voucherdata = [];
          $csvfile     = true;
          $storeid     = $this->gift_Model->getStoreId();

          $issuestore = [];
          foreach ($csvData as $row) {
            $searchFor = $row['ISSUE STORE'];
            $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
              return isset($element->StoreCode) && $element->StoreCode == $searchFor;
            }));

            if (empty($storecode)) {
              $issuestore[] = $searchFor;
            }
          }

          if (!empty($issuestore)) {
            $message = 'The following issue stores are not exist: ' . implode(', ', $issuestore);
            $csvfile = false;
          }else {
            $data = array(
              'CSVFileName'           => $get['filename'],
              'ScheduledImportTime'   => $get['importTime'],
              'ImportStatusId'        => 2,
            );
            $csvid = $this->gift_import_Model->import_csvFile($data);

            foreach ($csvData as $row) {
              $issuedate   = implode("-", array_reverse(explode("/", $row['ISSUE DATE'])));
              $expirydate  = implode("-", array_reverse(explode("/", $row['EXPIRY DATE'])));

              $searchFor = $row['ISSUE STORE'];
              $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
                return isset($element->StoreCode) && $element->StoreCode == $searchFor;
              }));

              $searchFor   = $row['REDEEM STORE'];
              $redeemStore = array_values(array_filter($storeid, function($element) use($searchFor){
                return isset($element->StoreCode) && $element->StoreCode == $searchFor;
              }));

              if (isset($redeemStore[0])) {
                $redeemStore = $redeemStore[0]->StoreCode;
              }else {
                $redeemStore = '';
              }

              if ($row['EXPIRY DATE'] != null) {
                $voucherdata[] = array(
                  'CSVFileId'          => $csvid,
                  'StoreId'            => $storecode[0]->StoreId,
                  'IssuedDate'         => date('Y-m-d',strtotime($issuedate)),
                  'VouchersNumber'     => $row['VOUCHER NUMBER'],
                  'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
                  'RedeemStore'        => $redeemStore,
                  'ExpDate'            => date('Y-m-d', strtotime($expirydate)),
                  'BatchNumber'        => $row['BATCH NUMBER'],
                  'VoucherStatusId'    => 1,
                  'VoucherTypeId'      => 1,
                  'VoucherIssuanceId'  => 0,
                );
              }else {
                $voucherdata[] = array(
                  'CSVFileId'          => $csvid,
                  'StoreId'            => $storecode[0]->StoreId,
                  'IssuedDate'         => date('Y-m-d',strtotime($issuedate)),
                  'VouchersNumber'     => $row['VOUCHER NUMBER'],
                  'VoucherValueGift'   => $row['VOUCHER AMOUNT'],
                  'RedeemStore'        => $redeemStore,
                  'BatchNumber'        => $row['BATCH NUMBER'],
                  'VoucherStatusId'    => 10,
                  'VoucherTypeId'      => 1,
                  'VoucherIssuanceId'  => 0,
                );
              }
            }

            if (!empty($voucherdata)) {
              $batchSize = 1000;
              $chunks    = array_chunk($voucherdata,$batchSize);
              foreach ($chunks as $chunk) {
                $this->db->insert_batch('pending_gift_voucher',$chunk);
              }
            }
          }
        }else {
          $message = 'CSV file is empty';
          $csvfile = false;
          $actmsg  = " is trying to Import Gift Voucher. CSV file empty.";
        }

        if (!$this->upload->do_upload('importfile',$newFileName)) {
          $data['invalid']  = FALSE;
          $message          = $this->upload->display_errors();
        }else {
          $data['invalid']              = FALSE;
          $upload_data                  = $this->upload->data();
          $downloadsData['file']        = $upload_data['file_name'];
          $data['CreatedDate']          = date("Y-m-d H:i:s", time() );
          // $data['CreatedBy']            = $data['UserId'];
          if ($csvfile == true) {
            $message = 'File has been uploaded.';
            $actmsg  = " CSV File successfully Upload!";
          }
        }
      }
    }else {
      $error = $this->form_validation->error_array();
      $salah = '';
      foreach ($error as $key) {
        $salah .= $key.'<br>';
      }

      $status    = false;
      $response  = $salah;
      $errorcode = 400;
    }

    $result['csv']      = $csvfile;
    $result['message']  = $message;

    echo json_encode($result);
  }

  function import_giftvoucher_errorLog(){
    $data = array_merge($this->global_data);

    $data['header']  = $this->load->view('templates/main-header',"",true);
    $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']  = $this->load->view('templates/main-footer',"",true);
    $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

    $this->load->view('voucher/import_gift_voucher_errorLog', $data);
  }

  function manual_giftvoucher_errorLog(){
    $data = array_merge($this->global_data);
    $post = $this->input->post();

    $list  = $this->gift_manualError_Model->get_datatables($post);
    $table = array();
    $no    = $post['start'];

    foreach ($list as $field) {

      $no++;

      $row     = array();
      $row[]   = $field->VoucherNumber;
      $row[]   = date('Y-m-d H:i:s', strtotime($field->ImportDate));;
      $row[]   = $field->ErrorMessage;
      $table[] = $row;
    }

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->gift_manualError_Model->count_all($post),
      "recordsFiltered"     => $this->gift_manualError_Model->count_filtered($post),
      "data"                => $table,
      $data['csrf']['name'] => $data['csrf']['hash']
    );
    echo json_encode($output);
  }

//======================================================================================================================================

function import_voucher_csv()
{
  $data = array_merge($this->global_data);

  $data['IpAddress']       = $this->input->ip_address();
  $data['OperatingSystem'] = $this->agent->platform();
  $data['Browser']         = $this->agent->browser();

  $this->load->library('csvimport');

  $data['UserId']     				= $this->session->userdata('UserId');

  $array                    		= explode('.', $_FILES['file']['name']);
  $extension                		= end($array);
  $originalFileName         		= str_replace(' ', '_', $array[0]);
  $newFileName              		= "upload-vouchers".date('YmdGis').".".$extension;

  $data['CodeNumber']      			= date('YmdGis');
  $data['NewFileName']      		= $newFileName;
  $data['OriginalFileName'] 		= $_FILES['file']['name'];
  $data['NewPath']          		= "uploads/import/".$newFileName;
  $data['RequestStatusId']	    = "2";

  $config['file_name']      		= $newFileName;
  $config['upload_path']    		= "uploads/import/";
  $config['allowed_types']  		= 'xlsx|xls|csv|txt|xltx';
  $config['max_size']       		= '5120';

  $this->load->library('upload', $config);
  $this->upload->initialize($config);
  $data['upload_data'] = '';

  if(isset($_FILES["file"]["tmp_name"]))
  {
    $path   = $_FILES["file"]["tmp_name"];

    $csvData = $this->csvimport->parse_csv($_FILES['file']['tmp_name']);
    $get = $this->input->post();

    $data['AppLoggerId'] = $get['loggerid'];

    $id = $get['uploadid'];

    if ($get['loggerid'] = $this->App_logger_model->update_app_logger_data($data)) {
      if(!empty($csvData)) {

        $errorImport = '';
        $updatevoucher = [];
        $csvfile = true;

        foreach($csvData as $row){

          $vouchernumber = $this->voucher_Model->getVoucherNumber($row['VOUCHER NUMBER']);

          if(isset($vouchernumber)){
            if($vouchernumber->VoucherStatusId == 1 || $vouchernumber->VoucherStatusId == 3) {

              if ($row['ACTION'] == 'CANCEL') {
                $updatevoucher[] = [
                  'VoucherId'  => $vouchernumber->VoucherId,
                  'VoucherStatusId'  => 5,
                ];

                $inserthistory = [
                  'VoucherId'  => $vouchernumber->VoucherId,
                  'VoucherStatusId'  => 5,
                ];
                $this->voucher_Model->insert_voucherStatusHistory($inserthistory);
              }elseif($row['ACTION'] == 'BLOCK') {
                $updatevoucher[] = [
                  'VoucherId'  => $vouchernumber->VoucherId,
                  'VoucherStatusId'  => 6,
                ];

                $inserthistory = [
                  'VoucherId'  => $vouchernumber->VoucherId,
                  'VoucherStatusId'  => 6,
                ];
                $this->voucher_Model->insert_voucherStatusHistory($inserthistory);
              }else {
                $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                    <td>'.$row['VOUCHER NUMBER'].'</td>
                                    <td>Status not valid!</td>
                                  </tr>';
                $message = 'Some info is wrong/missing!';
                $csvfile = false;
                $actmsg  = " is trying to Import Voucher. Invalid Status.";
              }
            }
            else {
              $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                  <td>'.$row['VOUCHER NUMBER'].'</td>
                                  <td>Voucher not available</td>
                                </tr>';
              $message = 'Some info is wrong/missing!';
              $csvfile = false;
              $actmsg  = " is trying to Import Unavailable Voucher. Failed.";
            }
          }
          else {
            $errorImport .= '<tr class="bg-danger text-white" style="font-weight:bold">
                                <td>'.$row['VOUCHER NUMBER'].'</td>
                                <td>Voucher Number not exist!</td>
                              </tr>';
            $message = 'Some info is wrong/missing!';
            $csvfile = false;
            $actmsg  = " is trying to Import not existing Voucher Number. Failed.";
          }
      }

      if(!empty($updatevoucher)) {
        $update = $this->voucher_Model->editimportVoucher($updatevoucher);
        //update import voucher
      }

      } else {
      $message = 'CSV file is empty';
      $actmsg  = " is trying to Import Voucher. Empty CSV file.";
      }
    }

    if (!$this->upload->do_upload('file',$newFileName))
    {
        $data['invalid']              = FALSE;
        // $data                         = array('msg' => $this->upload->display_errors());
        $message = $this->upload->display_errors();
    } else {
        $data['invalid']              = FALSE;
        $upload_data                  = $this->upload->data();
        $downloadsData['file']        = $upload_data['file_name'];
        $data['CreatedDate']          = date("Y-m-d H:i:s", time() );
        $data['CreatedBy']            = $data['UserId'];
        if($csvfile == true) {
          $message = 'File has been uploaded';
          $actmsg  = " Successfully Import Voucher.";
        }
      }

      $act = [
        'UserId'           => $data['UserId'],
        'ActivityTypeId'   => 37,
        'ActivityDetails'  => $data['Fullname'].$actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    $result['csv']         = $csvfile;
    $result['errorimport'] = $errorImport;
    $result['message']     = $message;
    $result['token']       = $data['csrf']['hash'];
    echo json_encode($result);
  }

//======================================================================================================================================

    function formatReport(){
      $data = array_merge($this->global_data);

       // file name
       $filename = 'voucher_format'.date('Ymd').'.csv';
       header("Content-Description: File Transfer");
       header("Content-Disposition: attachment; filename=$filename");
       header("Content-Type: application/csv; ");

       // file creation
       $file = fopen('php://output', 'w');

       $header = array("ISSUE STORE","ISSUE DATE","VOUCHER NUMBER","VOUCHER AMOUNT","EXPIRY DATE", "ACTION");
       fputcsv($file, $header);

       fclose($file);
       exit;
    }

    function formatGiftReport(){
      $data = array_merge($this->global_data);

       // file name
       $filename = 'Giftvoucher_format'.date('Ymd').'.csv';
       header("Content-Description: File Transfer");
       header("Content-Disposition: attachment; filename=$filename");
       header("Content-Type: application/csv; ");

       // file creation
       $file = fopen('php://output', 'w');

       $header = array('ISSUE STORE','ISSUE DATE','VOUCHER NUMBER','VOUCHER AMOUNT','REDEEM STORE','EXPIRY DATE','BATCH NUMBER','ACTION');

       fputcsv($file, $header);

       fclose($file);
       exit;
    }
  }
 ?>
