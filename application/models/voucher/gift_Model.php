<?php

  class gift_Model extends CI_Model
  {
    var $table         = 'gift_voucher'; //DATABASE TABLE NAME
  	var $column_order  = array('VouchersNumber','app_logger.CreatedDate','voucher_type.VoucherName','gift_voucher.VoucherValueGift','Expdate','voucher_status.VoucherStatusName',null); //FIELD IN TABLE
  	var $column_search = array('VouchersNumber','app_logger.CreatedDate','voucher_type.VoucherName','gift_voucher.VoucherValueGift','Expdate','voucher_status.VoucherStatusName'); //FIELD FOR SEARCHING PURPOSES
  	var $order         = array('gift_voucher.GiftVouchersId' => 'desc'); // DATA ORDERING

    //START AJAX DATATABLES
    //NO NEED TO CHANGE. JUST COPY AND PASTE**
    private function _get_datatables_query($post)
    {
      $i = 0;
      foreach ($this->column_search as $item) // loop column
      {
        if($post['search']['value']) // if datatable send POST for search
        {
          if($item == 'app_logger.CreatedDate' || $item == 'Expdate') {
          $date = str_replace('/', '-', $post['search']['value']);
            if($i===0) // first loop
            {
              $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
              $this->db->like($item, $post['search']['value']);
            }
            else
            {
              $this->db->or_like($item,date('Y-m-d', strtotime($date)));
              $this->db->or_like($item,date('H:i:s', strtotime($date)));
            }
            } else {
              if($i===0) // first loop
            {
              $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
              $this->db->like($item, $post['search']['value']);
            }
            else
            {
              $this->db->or_like($item, $post['search']['value']);
            }
          }

          if(count($this->column_search) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
      }

        if(isset($post['order'])) // here order processing
        {
          $this->db->order_by($this->column_order[$post['order']['0']['column']], $post['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
          $order = $this->order;
          $this->db->order_by(key($order), $order[key($order)]);
        }

    }
    //NO NEED TO CHANGE. JUST COPY AND PASTE**

    //CHANGE ONLY ON THIS PART**
    function _extra_datatables($post,$userid,$permission){

      $this->db->select("gift_voucher.IssuedDate,gift_voucher.VouchersNumber,gift_voucher.GiftVouchersId,gift_voucher.VoucherValueGift,gift_voucher.ExpDate,store.StoreCode,
      app_logger.CreatedDate,voucher_status.VoucherStatusColor,voucher_status.VoucherStatusName,voucher_status.VoucherStatusId,voucher_type.VoucherName");

      $this->db->join("gift_voucher_issuance","gift_voucher_issuance.GiftVoucherIssuanceId = ".$this->table.".VoucherIssuanceId");
      $this->db->join("voucher_status","voucher_status.VoucherStatusId = ".$this->table.".VoucherStatusId");
      $this->db->join("voucher_type","voucher_type.VoucherTypeId = ".$this->table.".VoucherTypeId");
      $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");
      $this->db->join("store","store.StoreId = ".$this->table.".StoreId");

      if ($post['empty'] == 1) {
        $chunk = array_chunk($post['activity'],1000);
        $i = 1;
        foreach ($chunk as $giftvoucher) {
          if ($i == 1) {
            $this->db->where_in('gift_voucher.GiftVouchersId', $giftvoucher);
          }else {
            $this->db->or_where_in('gift_voucher.GiftVouchersId', $giftvoucher);
          }
          $i++;}
      }elseif ($post['empty'] == 2) {
        $this->db->where('gift_voucher.GiftVouchersId', 0);
      }elseif ($post['empty'] == 3) {

        if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
          $this->db->join("user_store","user_store.StoreId = store.StoreId");
          $this->db->where("user_store.UserId", $userid);
        }

        if ($post['ReceiptNumber'] != '') {
          if ($post['VoucherStatus'] == 7) {
            $this->db->join("voucher_activity","voucher_activity.VouchersId = gift_voucher.GiftVouchersId");
            $this->db->where('voucher_activity.ReceiptNumber', $post['ReceiptNumber']);
          }else {
            $this->db->where('gift_voucher_issuance.ReceiptNumber', $post['ReceiptNumber']);
          }
        }

        if (!empty($post['voucher'])) {
          $chunk = array_chunk($post['voucher'],1000);
          $i = 1;
          foreach ($chunk as $giftvoucher) {
            if ($i == 1) {
              $this->db->where_in('gift_voucher.GiftVouchersId', $giftvoucher);
            }else {
              $this->db->or_where_in('gift_voucher.GiftVouchersId', $giftvoucher);
            }
            $i++;}
        }elseif ($post['VoucherNumber'] != '') {
          $this->db->where('gift_voucher.VouchersNumber', 0);
        }

        if (!empty($post['StoreCode'])) {
          $this->db->where_in('store.StoreId', $post['StoreCode']);
        }

        if (isset($post['pos'])) {
          $this->db->where_in('gift_voucher_issuance.PosId', $post['pos']->POSId);
        }

        if ($post['VoucherStatus'] != '') {
          $this->db->where('gift_voucher.VoucherStatusId', $post['VoucherStatus']);
        }

        if (isset($post['BatchNumber']) && $post['BatchNumber'] != "") {
          $this->db->where_in('gift_voucher.BatchNumber', $post['BatchNumber']);
        }

        if ($post['StartDate'] != '' && $post['EndDate'] != '') {
          if ($post['VoucherStatus'] == '') {
            $this->db->where('DATE(IssuedDate) >=', $post['StartDate']);
            $this->db->where('DATE(IssuedDate) <=', $post['EndDate']);
          } elseif ($post['VoucherStatus'] == 2) {
            $this->db->where('DATE(ExpDate) >=', $post['StartDate']);
            $this->db->where('DATE(ExpDate) <=', $post['EndDate']);
          }else {
            if (!empty($post['history'])) {
              $chunk = array_chunk($post['history'],1000);
              $i = 1;
              foreach($chunk as $giftvoucher)
              {
                if ($i == 1) {
                  $this->db->where_in('gift_voucher.GiftVouchersId', $giftvoucher);
                }else {
                  $this->db->or_where_in('gift_voucher.GiftVouchersId', $giftvoucher);
                }
              $i++;}
            }else {
              $this->db->where('gift_voucher.GiftVouchersId', 0);
            }
          }
        }
      }
    }
    //CHANGE ONLY ON THIS PART**

    //NO NEED TO CHANGE. JUST COPY AND PASTE**
    function get_datatables($post,$userid,$permission)
    {
      $this->db->from($this->table);
      if($post['length'] != -1)
      $this->_extra_datatables($post,$userid,$permission);
      $this->db->limit($post['length'], $post['start']);
      $this->_get_datatables_query($post);
      $query = $this->db->get();
      return $query->result();
    }

    function count_filtered($post,$userid,$permission)
    {
      $this->db->from($this->table);
      $this->_extra_datatables($post,$userid,$permission);
      $this->_get_datatables_query($post);
      return $this->db->count_all_results();
    }

    function count_all($post,$userid,$permission)
    {
      $this->db->from($this->table);
      $this->_extra_datatables($post,$userid,$permission);
      return $this->db->count_all_results();
    }
    //NO NEED TO CHANGE. JUST COPY AND PASTE**
    //END AJAX DATATABLES
    //User list table**

    function get_voucher_number($num){
      $this->db->select('GiftVouchersId');
      $this->db->like('gift_voucher.VouchersNumber',$num,'both');
  		$query = $this->db->get('gift_voucher');
      $vouchid = [];
      foreach ($query->result() as $row) {
        $vouchid[] = $row->GiftVouchersId;
      }
      return $vouchid;
    }

    function get_voucher_pos($pos){
      $this->db->select('POSId');
      $this->db->where('pos.POSNumber', $pos);
  		$query = $this->db->get('pos');
  		return $query->row();
    }

    function get_voucher_history_filter($post){
      $this->db->select('GiftVouchersId');
      if ($post['VoucherStatus'] == 3) {
        $this->db->where('DATE(ExtendDate) >=', $post['StartDate']);
        $this->db->where('DATE(ExtendDate) <=', $post['EndDate']);
      }elseif ($post['VoucherStatus'] == 5) {
        $this->db->where('DATE(CancelDate) >=', $post['StartDate']);
        $this->db->where('DATE(CancelDate) <=', $post['EndDate']);
      }elseif ($post['VoucherStatus'] == 6) {
        $this->db->where('DATE(BlockDate) >=', $post['StartDate']);
        $this->db->where('DATE(BlockDate) <=', $post['EndDate']);
      }
  		$query = $this->db->get('voucher_status_history');
      $vouchid = [];
      foreach ($query->result() as $row) {
        $vouchid[] = $row->GiftVouchersId;
      }
      return $vouchid;
    }

    function get_voucher_receipt($receipt){
      $this->db->select('GiftVoucherIssuanceId');
      if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
        $this->db->join("user_store","user_store.StoreId = gift_voucher_issuance.StoreId");
        $this->db->where("user_store.UserId", $userid);
      }
      $this->db->where('gift_voucher_issuance.ReceiptNumber', $receipt);
  		$query = $this->db->get('gift_voucher_issuance');
      $result = $query->row();

      if (isset($result)) {
        $this->db->select('GiftVouchersId');
        $this->db->where('gift_voucher.VoucherIssuanceId', $query->row()->VoucherIssuanceId);
    		$query = $this->db->get('gift_voucher');

        $vouchid = [];
        foreach ($query->result() as $row) {
          $vouchid[] = $row->GiftVouchersId;
        }
      }else {
        $vouchid = [];
      }
      return $vouchid;
    }

    function get_voucher_activity($post,$userid,$permission) {
      $this->db->select('VouchersId');
      $this->db->join("voucher_status","voucher_status.CategoryId = voucher_activity.VoucherActivityCategoryId");

      if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
        $this->db->join("user_store","user_store.StoreId = voucher_activity.StoreId");
        $this->db->where("user_store.UserId", $userid);
      }

      if (!empty($post['StoreCode'])) {
  			$this->db->where_in('voucher_activity.StoreId', $post['StoreCode']);
  		}

      if (isset($post['pos'])) {
  			$this->db->where_in('voucher_activity.PosId', $post['pos']->POSId);
  		}

      if (isset($post['voucher'])) {
  			$this->db->where('voucher_activity.VouchersId', $post['voucher']->GiftVouchersId);
  		}elseif($post['VoucherNumber'] != '') {
        $this->db->where('voucher_activity.VouchersId', 0);
      }

      $this->db->where('voucher_status.VoucherStatusId', $post['VoucherStatus']);

      if ($post['StartDate'] != '' && $post['EndDate'] != '') {
        $this->db->where('DATE(ActivityDate) >=', $post['StartDate']);
        $this->db->where('DATE(ActivityDate) <=', $post['EndDate']);
      }

      if (!empty($post['vouchid'])) {
        $chunk = array_chunk($post['vouchid'],1000);
        $i = 1;
        foreach($chunk as $giftvoucher)
        {
          if ($i == 1) {
            $this->db->where_in('voucher_activity.VouchersId', $giftvoucher);
          }else {
            $this->db->or_where_in('voucher_activity.VouchersId', $giftvoucher);
          }
        $i++;}
      }
  		$query = $this->db->get('voucher_activity');

  		$vouchid = [];
      foreach ($query->result() as $row) {
        $vouchid[] = $row->VouchersId;
      }
      return $vouchid;
    }

    function get_evoucher_upload_today(){
      $this->db->where('DATE(IssuedDate)', date('Y-m-d'));
      $this->db->where('gift_voucher.VoucherStatusId',10);
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_evoucher_void_today(){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $this->db->where('VoucherActivityCategoryId',5);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_evoucher_redeem_today(){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_evoucher_sold_today(){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $array = [14,15,16];
      $this->db->where_in('VoucherActivityCategoryId',$array);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    //e-vouchers card by store
    function get_evoucher_upload_today_store($id){
      $this->db->where('DATE(IssuedDate)', date('Y-m-d'));
      $this->db->where('VoucherStatusId',10);
      $this->db->where('StoreId', $id);
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_evoucher_void_today_store($id){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $this->db->where('VoucherActivityCategoryId',5);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_evoucher_redeem_today_store($id){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_evoucher_sold_today_store($id){
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId',1);
      $array = [14,15,16];
      $this->db->where_in('VoucherActivityCategoryId',$array);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_filterstatus() {
      $this->db->order_by('VoucherStatusName', 'asc');
      $query = $this->db->get('voucher_status');
      return $query;
    }

    function get_filterstore() {
      $this->db->order_by('StoreCode', 'asc');
      $this->db->where('StoreId !=',0);
      $query = $this->db->get('store');
      return $query;
    }

    //gift voucher
    function get_giftstatus(){
      $array = [1,2,3,4,5,6,9,10,11];
      $this->db->order_by('VoucherStatusName', 'asc');
      $this->db->where_in('VoucherStatusId',$array);
      $query = $this->db->get('voucher_status');
      return $query;
    }

    function get_statusUnblockPermission(){
      $array = [1,3];
      $this->db->where_in('VoucherStatusId',$array);
      $query = $this->db->get('voucher_status');
      return $query;
    }

    function get_reasons_cancel(){
      $this->db->join("reason_type","reason_type.ReasonTypeId = reason.ReasonTypeId");
      $this->db->where('reason_type.ReasonTypeId',1);
      $query = $this->db->get('reason');
      return $query;
    }

    function get_reasons_block(){
      $this->db->join("reason_type","reason_type.ReasonTypeId = reason.ReasonTypeId");
      $this->db->where('reason_type.ReasonTypeId',2);
      $query = $this->db->get('reason');
      return $query;
    }

    function get_voucher_history($id, $status){

      if ($status == 6) {
        $this->db->join('reason', 'reason.ReasonId = voucher_status_history.BlockReasons');
      }elseif ($status == 5) {
        $this->db->join('reason', 'reason.ReasonId = voucher_status_history.CancelReasons');
      }

      $this->db->where('GiftVouchersId',$id);
      $this->db->order_by('VoucherStatusHistoryId', 'desc');
      $query = $this->db->get('voucher_status_history');
      return $query->row();
    }

    function get_giftEdit($id){
      $this->db->select('*, app_logger.CreatedDate as createddate');
      $this->db->join('app_logger', 'app_logger.AppLoggerId = gift_voucher.AppLoggerId');
      $this->db->join('voucher_status','voucher_status.VoucherStatusId = gift_voucher.VoucherStatusId');
      $this->db->join('store','store.StoreId = gift_voucher.StoreId');

      $this->db->where('gift_voucher.GiftVouchersId',$id);
      $query = $this->db->get('gift_voucher');
      return $query->row();
    }

    function get_giftEdit2($id){
      $this->db->select('*, app_logger.CreatedDate as createddate');
      $this->db->join('app_logger', 'app_logger.AppLoggerId = gift_voucher.AppLoggerId');
      $this->db->join('voucher_status','voucher_status.VoucherStatusId = gift_voucher.VoucherStatusId');
      $this->db->join('gift_voucher_issuance','gift_voucher_issuance.GiftVoucherIssuanceId = gift_voucher.VoucherIssuanceId');
      $this->db->join('endpoint_gift_activation','endpoint_gift_activation.EndpointGiftActivationId = gift_voucher_issuance.EndpointGiftActivationId');
      $this->db->join('pos','pos.POSId = gift_voucher_issuance.POSId');

      $this->db->where('gift_voucher.GiftVouchersId',$id);
      $query = $this->db->get('gift_voucher');
      return $query->row();
    }

    function get_originalgift($id,$status){
      $this->db->join("gift_voucher","gift_voucher.VouchersNumber = voucher_activity.VouchersNumber");
      $this->db->join('pos','pos.POSId = voucher_activity.PosId');
      $this->db->where('gift_voucher.GiftVouchersId',$id);

      if ($status == 4) {
        $this->db->where('VoucherActivityCategoryId',5);
      }elseif ($status == 8) {
        $this->db->where('VoucherActivityCategoryId',6);
      }elseif ($status == 7) {
        $this->db->where('VoucherActivityCategoryId',3);
      }

      $this->db->order_by('voucher_activity.VoucherLogsId', 'DESC');
      $this->db->limit(1);

      $query = $this->db->get('voucher_activity');
      return $query->row();
    }

    function giftEdit($get, $id){
      if (isset($get['remark'])) {
        if (isset($get['status']) == 5 || isset($get['status']) == 6) {
          $data = array(
                        'Remarks'         => $get['remark'],
          );
        }else {
          $data = array(
                        'ExpDate'         => $get['expireddate'],
                        'Remarks'         => $get['remark'],
          );
        }
      }

      $this->db->where('GiftVouchersId',$id);
      return $this->db->update('gift_voucher',$data);
    }

    function giftEdit_today($get, $id){
      if (isset($get['remark'])) {
          $data = array(
                        'VoucherStatusId' => $get['status'],
                        'Remarks'         => $get['remark'],
          );
        }

      $this->db->where('GiftVouchersId',$id);
      return $this->db->update('gift_voucher',$data);
    }

    function voucherReason($giftvoucherid,$get){

      if ($get['status'] == 3) {
        $data = array(
                      'GiftVouchersId'   => $giftvoucherid,
                      'ExtendDate'       => $get['extendDate'],
        );
      } elseif ($get['status'] == 5) {
        $data = array(
                      'GiftVouchersId'   => $giftvoucherid,
                      'CancelDate'       => $get['canceldate'].' '.date('H:i:s', time()),
                      'CancelReasons'    => $get['cancelReason'],
        );
      }elseif ($get['status'] == 6) {
        $data = array(
                      'GiftVouchersId'   => $giftvoucherid,
                      'BlockDate'        => $get['blockDate'].' '.date('H:i:s', time()),
                      'BlockReasons'     => $get['blockReason'],
        );
      } elseif ($get['status'] == 9) {
        $data = array(
                      'GiftVouchersId'   => $giftvoucherid,
                      'InactiveDate'     => $get['inactivedate'],
        );
      }

      $this->db->insert('voucher_status_history', $data);
      return $this->db->insert_id();
    }

    function currentstatus($id) {
      $this->db->where('voucher_status.VoucherStatusId',$id);
      $query = $this->db->get('voucher_status');
      return $query->row()->VoucherStatusName;
    }

    function giftDetails($get, $details){
      $data = array(
                    'Remarks' => $get['remark'],
      );
      $this->db->where('GiftVouchersId',$details);
      $this->db->update('gift_voucher',$data);
    }

    function updateGift($data){
      $this->db->where('GiftVouchersId',$data['GiftVouchersId']);
      $this->db->update('gift_voucher',$data);
    }

    function getVoucherNumber($row){
      $this->db->select('*');
      $this->db->where('VouchersNumber', $row);
      $query = $this->db->get('gift_voucher');
      return $query->row();
    }

    function getGiftVoucherNumber(){
      $this->db->select('VouchersNumber,VoucherStatusId,GiftVouchersId');
      $query = $this->db->get('gift_voucher');
      return $query->result();
    }

    function get_batchnumber($row){
      $this->db->select('BatchNumber');
      $this->db->where('BatchNumber', $row);
      $query = $this->db->get('gift_voucher');
      return $query->row();
    }

    function insertGiftHistory($data){
      $this->db->insert('voucher_status_history', $data);
      return $this->db->insert_id();
    }

    //for insert and update import gift voucher
    function insertImportGift($memData){
      $batchSize = 1000;
      $chunks    = array_chunk($memData,$batchSize);
      foreach ($chunks as $chunk) {
        $this->db->insert_batch('gift_voucher',$chunk);
      }
    }

      // if (isset($vouchernumber)) {
      //   if ($row['ACTION'] == 'CANCEL') {
      //     $id = 5;
      //
      //     $this->db->set('VoucherStatusId', $id);
      //     $this->db->where('VouchersNumber', $row['VOUCHER NUMBER']);
      //     return $this->db->update('gift_voucher');
      //   }elseif($row['ACTION'] == 'BLOCK') {
      //     $id = 6;
      //
      //     $this->db->set('VoucherStatusId', $id);
      //     $this->db->where('VouchersNumber', $row['VOUCHER NUMBER']);
      //     return $this->db->update('gift_voucher');
      //   }
      // }else {
        // return $this->db->insert_id();
      // }


    function getStoreId(){
      $query = $this->db->get('store');
      return $query->result();
    }

    function get_permission($id){
      $this->db->where('StatusId', 1);
      $this->db->where('UserId', $id);
      $query = $this->db->get('user_voucher_settings');
      return $query->row();
    }

    function get_permissionVoucherList($id){
      $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");
      $this->db->where("user_store.UserId" , $id);
      $query = $this->db->get('user_store');
      return $query->row();
    }

    function userStore($id){
      $this->db->where("userId", $id);
      $query = $this->db->get('user_store');
      return $query->row();
    }

    function checkExtendDateList($id){
      $this->db->join("voucher_status_history","voucher_status_history.GiftVouchersId = gift_voucher.GiftVouchersId");
      $this->db->where("gift_voucher.GiftVouchersId", $id);
      $this->db->order_by('VoucherStatusHistoryId', 'desc');
      $query = $this->db->get('gift_voucher');
      return $query->row();
    }

    function get_voucherno($id) {
      $this->db->where('gift_voucher.GiftVouchersId',$id);
      $query = $this->db->get('gift_voucher');
      return $query->row()->VouchersNumber;
    }

    function giftvoucher_report($obj,$permission){
      $this->db->select("*");
      $this->db->join("gift_voucher_issuance","gift_voucher_issuance.GiftVoucherIssuanceId = gift_voucher.VoucherIssuanceId");
      $this->db->join("store","store.StoreId = gift_voucher.StoreId");
      $this->db->join("voucher_status","voucher_status.VoucherStatusId = gift_voucher.VoucherStatusId");
      $this->db->join("app_logger","app_logger.AppLoggerId = gift_voucher.AppLoggerId");

      if ($permission->UserRoleId != 1) {
        if ($permission->StoreId != 0) {
          $this->db->where("store.StoreId", $permission->StoreId);
        }
      }

      $this->db->order_by('DATE(gift_voucher.IssuedDate)', 'asc');
      $query = $this->db->get('gift_voucher');
      return $query->result();
    }

    function get_voucherstatus($id) {
      $this->db->where('voucher_status.VoucherStatusId',$id);
      $query = $this->db->get('voucher_status');
      if (isset($query->row()->VoucherStatusName)) {
        return $query->row()->VoucherStatusName;
      } else {
        return 'ALL STATUS';
      }
    }

    function get_bystoreadmin($id) {
      $this->db->where('store.StoreId',$id);
      $query = $this->db->get('store');
      if (isset($query->row()->StoreName)) {
        return $query->row()->StoreName;
      } else {
        return 'ALL STORE';
      }
    }

    function get_bystore($id) {
      $this->db->join("user_store","user_store.UserId = user.UserId");
      $this->db->join("store","store.StoreId = user_store.StoreId");

      $this->db->where('user.UserId',$id);
      $query = $this->db->get('user');
      return $query->row()->StoreName;
    }

    function get_loginid($id) {
      $this->db->where("user.UserId", $id);
      $query = $this->db->get('user');

      if (isset($query->row()->StaffId)) {
        return $query->row()->StaffId;
      } else {
        return '';
      }
    }

    function getVoucherMapping($vouchersNumber){
      $this->db->select('GiftVouchersId,VouchersNumber');
      $voucherMapping = [];
      $batchSize      = 1000;
      $chunks         = array_chunk($vouchersNumber,$batchSize);

      foreach ($chunks as $chunk) {
        $this->db->where_in('VouchersNumber',$chunk);
        $query  = $this->db->get('gift_voucher');
        $result = $query->result();

        foreach ($result as $row) {
          $voucherMapping[$row->VouchersNumber] = $row->GiftVouchersId;
        }
      }
      return $voucherMapping;
    }

    function getExistingVoucherNumber(){
      $this->db->select('VouchersNumber');
      $query  = $this->db->get('gift_voucher');
      $result = $query->result_array();
      return array_column($result,'VouchersNumber');
    }

    function get_giftvoucherno($voucherNumber){
      $this->db->select('VouchersNumber');
      $batchSize      = 1000;
      $chunks         = array_chunk($voucherNumber,$batchSize);
      $i = 1;

      foreach ($chunks as $giftvoucher) {
        if ($i == 1) {
          $this->db->where_in('gift_voucher.VouchersNumber', $giftvoucher);
        }else {
          $this->db->or_where_in('gift_voucher.VouchersNumber', $giftvoucher);
        }
        $i++;
      }
      $query  = $this->db->get('gift_voucher');
      $result = $query->result_array();
      return array_column($result,'VouchersNumber');
    }

    function getVoucherByNumber($voucherNumber){
      $this->db->select('*');
      $this->db->from('gift_voucher');
      $this->db->where('VouchersNumber', $voucherNumber);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return [];
      }
    }

    function get_gvnumber($voucherNumber){
      $this->db->select('VouchersNumber,ExpDate,GiftVouchersId');
      $this->db->from('gift_voucher');
      $this->db->where('VouchersNumber', $voucherNumber);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return [];
      }
    }
  }
 ?>
