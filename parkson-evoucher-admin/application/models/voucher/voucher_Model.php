<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class voucher_Model extends CI_Model
{
  var $table         = 'vouchers'; //DATABASE TABLE NAME
	var $column_order  = array('store.StoreCode', 'vouchers.VouchersNumber','app_logger.CreatedDate','voucher_type.VoucherName','vouchers.VouchersValue','Expdate','voucher_status.VoucherStatusName',null); //FIELD IN TABLE
	var $column_search = array('store.StoreCode', 'vouchers.VouchersNumber','app_logger.CreatedDate','voucher_type.VoucherName','vouchers.VouchersValue','Expdate','voucher_status.VoucherStatusName'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('vouchers.VoucherId' => 'desc'); // DATA ORDERING

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
    $this->db->select("vouchers.VouchersNumber,vouchers.VoucherId,vouchers.VouchersValue,vouchers.ExpDate,store.StoreCode,voucher_type.VoucherName,
    app_logger.CreatedDate,voucher_status.VoucherStatusColor,voucher_status.VoucherStatusName,voucher_status.VoucherStatusId");

    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = ".$this->table.".VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId",'inner');
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = ".$this->table.".VoucherTypeId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = ".$this->table.".VoucherStatusId");

    if ($post['empty'] == 1) {
      $chunk = array_chunk($post['activity'],1000);
      $i = 1;
      foreach($chunk as $vouchers)
      {
        if ($i == 1) {
          $this->db->where_in('vouchers.VoucherId', $vouchers);
        }else {
          $this->db->or_where_in('vouchers.VoucherId', $vouchers);
        }
        $i++;}
      } elseif ($post['empty'] == 2) {
        $this->db->where('vouchers.VoucherId', 0);
      } elseif ($post['empty'] == 3) {

        if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
          $this->db->join("user_store","user_store.StoreId = store.StoreId");
          $this->db->where("user_store.UserId", $userid);
        }

        if ($post['ReceiptNumber'] != '') {
          $this->db->where('voucher_issuance.ReceiptNumber', $post['ReceiptNumber']);
        }

        if (!empty($post['voucher'])) {
          $chunk = array_chunk($post['voucher'],1000);
          $i = 1;
          foreach($chunk as $vouchers)
          {
            if ($i == 1) {
              $this->db->where_in('vouchers.VoucherId', $vouchers);
            }else {
              $this->db->or_where_in('vouchers.VoucherId', $vouchers);
            }
          $i++;}
        } elseif($post['VoucherNumber'] != '') {
          $this->db->where('vouchers.VouchersNumber', 0);
        }

        if (!empty($post['StoreCode'])) {
          $this->db->where_in('store.StoreId', $post['StoreCode']);
        }

        if (isset($post['pos'])) {
          $this->db->where_in('voucher_issuance.IssuancePOSId', $post['pos']->POSId);
        }

        if ($post['VoucherStatus'] != '') {
          $this->db->where('vouchers.VoucherStatusId', $post['VoucherStatus']);
        }

        if ($post['VoucherType'] != '') {
          $this->db->where('vouchers.VoucherTypeId', $post['VoucherType']);
        }

        if ($post['StartDate'] != '' && $post['EndDate'] != '') {
          if ($post['VoucherStatus'] == '') {
            $this->db->where('DATE(ReceiptDateTime) >=', $post['StartDate']);
            $this->db->where('DATE(ReceiptDateTime) <=', $post['EndDate']);
          } elseif ($post['VoucherStatus'] == 2) {
            $this->db->where('DATE(ExpDate) >=', $post['StartDate']);
            $this->db->where('DATE(ExpDate) <=', $post['EndDate']);
          }else {
            if (!empty($post['history'])) {
              $chunk = array_chunk($post['history'],1000);
              $i = 1;
              foreach($chunk as $vouchers)
              {
                if ($i == 1) {
                  $this->db->where_in('vouchers.VoucherId', $vouchers);
                }else {
                  $this->db->or_where_in('vouchers.VoucherId', $vouchers);
                }
              $i++;}
            }else {
              $this->db->where('vouchers.VoucherId', 0);
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
    // $query = $this->db->get();
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

  function get_filterstatus() {
    $this->db->order_by('VoucherStatusName','asc');
    $query = $this->db->get('voucher_status');
    return $query;
  }

  function get_filterstore() {
    $this->db->where('StoreId !=',0);
    $this->db->order_by('StoreCode','asc');
    $query = $this->db->get('store');
    return $query;
  }

  function get_filtertype() {
    $this->db->where('VoucherTypeId !=',0);
    $this->db->order_by('VoucherName','asc');
    $query = $this->db->get('voucher_type');
    return $query;
  }

  function get_voucher_activity($post,$userid,$permission)
	{
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
			$this->db->where('voucher_activity.VouchersId', $post['voucher']->VoucherId);
		}elseif($post['VoucherNumber'] != '') {
      $this->db->where('voucher_activity.VouchersId', 0);
    }

    if ($post['VoucherType'] != '') {
      $this->db->where('voucher_activity.VoucherTypeId', $post['VoucherType']);
    }

    $this->db->where('voucher_status.VoucherStatusId', $post['VoucherStatus']);

    if ($post['StartDate'] != '' && $post['EndDate'] != '') {
      $this->db->where('DATE(ActivityDate) >=', $post['StartDate']);
      $this->db->where('DATE(ActivityDate) <=', $post['EndDate']);
    }

    if (!empty($post['vouchid'])) {
      $chunk = array_chunk($post['vouchid'],1000);
      $i = 1;
      foreach($chunk as $vouchers)
      {
        if ($i == 1) {
          $this->db->where_in('voucher_activity.VouchersId', $vouchers);
        }else {
          $this->db->or_where_in('voucher_activity.VouchersId', $vouchers);
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

  function get_voucher_number($num)
	{
    $this->db->select('VoucherId');
    $this->db->like('vouchers.VouchersNumber',$num,'both');
		$query = $this->db->get('vouchers');
    $vouchid = [];
    foreach ($query->result() as $row) {
      $vouchid[] = $row->VoucherId;
    }
    return $vouchid;
	}

  function get_voucher_pos($pos)
	{
    $this->db->select('POSId');
    $this->db->where('pos.POSNumber', $pos);
		$query = $this->db->get('pos');
		return $query->row();
	}

  function get_voucher_receipt($receipt)
	{
    $this->db->select('VoucherIssuanceId');
    if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
      $this->db->join("user_store","user_store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->where("user_store.UserId", $userid);
    }
    $this->db->where('voucher_issuance.ReceiptNumber', $receipt);
		$query = $this->db->get('voucher_issuance');
    $result = $query->row();

    if (isset($result)) {
      $this->db->select('VoucherId');
      $this->db->where('vouchers.VoucherIssuanceId', $query->row()->VoucherIssuanceId);
  		$query = $this->db->get('vouchers');

      $vouchid = [];
      foreach ($query->result() as $row) {
        $vouchid[] = $row->VoucherId;
      }
    }else {
      $vouchid = [];
    }
    return $vouchid;
	}

  function get_voucher_history_filter($post)
	{
    $this->db->select('VoucherId');
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
      $vouchid[] = $row->VoucherId;
    }
    return $vouchid;
	}

  function get_pos($StoreId) {
    $this->db->join('store', 'store.StoreId = pos.StoreId');
    $this->db->where('pos.StatusId', 1);
    $this->db->where('store.StoreCode',$StoreId);
    $query = $this->db->get('pos');
    return $query->result();
  }

  function createVoucherType($get,$valueval){
    if($get['position'] != 0) {
      if ($get['prefix2'] == null && $get['addprefix'] == null) {
        $data = array(
                      'VoucherName'                => $get['vouchername'],
                      'VoucherShortName'           => $get['vouchershortname'],
                      'IssuanceTypeId'             => $get['issuanceType'],
                      'PrefixRangeFirst'           => $get['prefix1'],
                      'VoucherSerializeId'         => $get['serializeselect'],
                      'VoucherTypePositionId'      => $get['position'],
                      'SerializeNumber'            => $get['unserializenum'],
                      'PrefixPositionStart'        => $get['firstPrefix'],
                      'PrefixPositionEnd'          => $get['lastPrefix'],
                      'SerialNumberPositionStart'  => $get['firstSerial'],
                      'SerialNumberPositionEnd'    => $get['lastSerial'],
                      'VoucherValue'               => $valueval,
                      'StoreCredit'                => $get['storecredit'],
                      'StatusId'                   => $get['voucherstatus'],
                      'ActivateDate'               => $get['date'],
                      'Remarks'                    => $get['remarks'],
                      'AppLoggerId'                => $get['loggerid'],
          );
      }else {
        $data = array(
                      'VoucherName'                => $get['vouchername'],
                      'VoucherShortName'           => $get['vouchershortname'],
                      'IssuanceTypeId'             => $get['issuanceType'],
                      'PrefixRangeFirst'           => $get['prefix1'],
                      'PrefixRangeSecond'          => $get['prefix2'],
                      'VoucherSerializeId'         => $get['serializeselect'],
                      'VoucherTypePositionId'      => $get['position'],
                      'SerializeNumber'            => $get['unserializenum'],
                      'PrefixPositionStart'        => $get['firstPrefix'],
                      'PrefixPositionEnd'          => $get['lastPrefix'],
                      'SerialNumberPositionStart'  => $get['firstSerial'],
                      'SerialNumberPositionEnd'    => $get['lastSerial'],
                      'VoucherValue'               => $valueval,
                      'StoreCredit'                => $get['storecredit'],
                      'AdditionalPrefix'           => $get['addprefix'],
                      'StatusId'                   => $get['voucherstatus'],
                      'ActivateDate'               => $get['date'],
                      'Remarks'                    => $get['remarks'],
                      'AppLoggerId'                => $get['loggerid'],
          );
      }
    } else {
      if ($get['prefix2'] == null && $get['addprefix'] == null) {
        $data = array(
                      'VoucherName'                => $get['vouchername'],
                      'VoucherShortName'           => $get['vouchershortname'],
                      'IssuanceTypeId'             => $get['issuanceType'],
                      'PrefixRangeFirst'           => $get['prefix1'],
                      'VoucherSerializeId'         => $get['serializeselect'],
                      'VoucherTypePositionId'      => $get['position'],
                      // 'SerializeNumber'            => $get['unserializenum'],
                      'PrefixPositionStart'        => $get['firstPrefix'],
                      'PrefixPositionEnd'          => $get['lastPrefix'],
                      'SerialNumberPositionStart'  => $get['firstSerial'],
                      'SerialNumberPositionEnd'    => $get['lastSerial'],
                      'VoucherValue'               => $valueval,
                      'StoreCredit'                => $get['storecredit'],
                      'StatusId'                   => $get['voucherstatus'],
                      'ActivateDate'               => $get['date'],
                      'AppLoggerId'                => $get['loggerid'],
          );
      }else {
        $data = array(
                      'VoucherName'                => $get['vouchername'],
                      'VoucherShortName'           => $get['vouchershortname'],
                      'IssuanceTypeId'             => $get['issuanceType'],
                      'PrefixRangeFirst'           => $get['prefix1'],
                      'PrefixRangeSecond'          => $get['prefix2'],
                      'VoucherSerializeId'         => $get['serializeselect'],
                      'VoucherTypePositionId'      => $get['position'],
                      // 'SerializeNumber'            => $get['unserializenum'],
                      'PrefixPositionStart'        => $get['firstPrefix'],
                      'PrefixPositionEnd'          => $get['lastPrefix'],
                      'SerialNumberPositionStart'  => $get['firstSerial'],
                      'SerialNumberPositionEnd'    => $get['lastSerial'],
                      'VoucherValue'               => $valueval,
                      'StoreCredit'                => $get['storecredit'],
                      'AdditionalPrefix'           => $get['addprefix'],
                      'StatusId'                   => $get['voucherstatus'],
                      'ActivateDate'               => $get['date'],
                      'AppLoggerId'                => $get['loggerid'],
          );
      }
    }

      $result = $this->db->insert('voucher_type',$data);
      return $this->db->insert_id();
  }

  function get_Editdetails($id){
    $this->db->join('issuance_type', 'issuance_type.IssuanceTypeId = voucher_type.IssuanceTypeId');
    $this->db->join('voucher_serialize', 'voucher_serialize.VoucherSerializeId = voucher_type.VoucherSerializeId');
    $this->db->join('voucher_type_position', 'voucher_type_position.VoucherTypePositionId = voucher_type.VoucherTypePositionId');
    $this->db->join('status', 'status.StatusId = voucher_type.StatusId');
    $query = $this->db->where('voucher_type.VoucherTypeId',$id);
    $this->db->order_by('VoucherValue', 'asc');
    $query = $this->db->get('voucher_type');
    return $query->row();
  }

  function editVoucherType($get, $id,$valueval){
    if ($get['inactiveDate'] != null) {
      $data = array(
                    'VoucherValue'          => $valueval,
                    'InactivateDate'        => $get['inactiveDate'],
                    'Remarks'               => $get['remarks'],
                    'StatusId'              => 1,
        );
    }else {
      $data = array(
                    'VoucherValue'          => $valueval,
                    'Remarks'               => $get['remarks'],
                    'StatusId'              => 1,
        );
    }

        $this->db->where('VoucherTypeId',$id);
        return $this->db->update('voucher_type',$data);
  }

  //permission to edit form, if expired button submit disabled
  function voucherPermissionEdit($id){
    $this->db->select("StatusId");
    $this->db->where('VoucherTypeId', $id);
    $query = $this->db->get('voucher_type');
    return $query->row()->StatusId;
  }

  //vouchers
  //permission to edit form, if expired button submit disabled
  function voucherPermissionEditVoucher($id){
    $this->db->select("VoucherStatusId");
    $this->db->where('VoucherId', $id);
    $query = $this->db->get('vouchers');
    return $query->row()->VoucherStatusId;
  }

  //gift vouchers
  //permission to edit form, if expired button submit disabled
  function voucherPermissionEditGiftVoucher($id){
    $this->db->select("VoucherStatusId");
    $this->db->where('GiftVouchersId', $id);
    $query = $this->db->get('gift_voucher');
    return $query->row()->VoucherStatusId;
  }

  function remove_vouchervalue(){
    $data = array(
                  'VoucherValue' => 3,
                  // 'EndDate' => date('Y-m-d'),

    );
    $this->db->where('VoucherTypeId',$get['editid']);
    return $this->db->update('voucher_type',$data);
  }

  function get_status_voucher(){
    $query = $this->db->get('vouchers');
    return $query->row()->VoucherStatusId;
  }

  //pv voucher
  function get_voucher($id){
    $this->db->select('*, app_logger.CreatedDate as createddate');
    $this->db->join('app_logger', 'app_logger.AppLoggerId = vouchers.AppLoggerId');
    $this->db->join('redeem_type', 'redeem_type.RedeemTypeId = vouchers.RedeemTypeId');
    $this->db->join('voucher_issuance', 'voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId');
    $this->db->join('endpoint_issuance','endpoint_issuance.EndpointIssuanceId = voucher_issuance.EndpointIssuanceId');
    $this->db->join('pos', 'pos.POSId = voucher_issuance.IssuancePOSId');
    $this->db->join('store', 'store.StoreId = voucher_issuance.IssuanceStoreId');
    $this->db->join('voucher_status', 'voucher_status.VoucherStatusId = vouchers.VoucherStatusId');

    $this->db->where('vouchers.VoucherId',$id);
    $query = $this->db->get('vouchers');
    return $query->row();
  }

  function get_status_reactive(){
    $status = ['7','11'];
    $this->db->where_in('VoucherStatusId', $status);
    $query = $this->db->get('voucher_status');
    return $query->result();
  }

  function get_card($id) {
    $this->db->join('card', 'card.CardId = voucher_card.CardId');

    $this->db->where('voucher_card.VoucherIssuanceId',$id);
    $query = $this->db->get('voucher_card');
    return $query->result();
  }

  function get_originalvoucher($id,$status){
    $this->db->join('pos', 'pos.POSId = voucher_activity.PosId');
    $this->db->join('store', 'store.StoreId = voucher_activity.StoreId');
    $this->db->where('voucher_activity.VouchersId',$id);

    if ($status == 4) {
      $this->db->where('VoucherActivityCategoryId',5);
    }elseif ($status == 8) {
      $this->db->where('VoucherActivityCategoryId',6);
    }elseif ($status == 7) {
      $this->db->where('VoucherActivityCategoryId',3);
    }elseif ($status == 11) {
      $this->db->where('VoucherActivityCategoryId',15);
    }

    $query = $this->db->get('voucher_activity');
    return $query->row();
  }

  function get_redeemreactive_receipt($id,$status){
    $this->db->where('voucher_activity.VouchersId',$id);
    if ($status == 11) {
      $this->db->where('VoucherActivityCategoryId',3);
    }

    $query = $this->db->get('voucher_activity');
    return $query->row();
  }

  function get_voucher_history($id,$status){
    if ($status == 6) {
      $this->db->join('reason', 'reason.ReasonId = voucher_status_history.BlockReasons');
    }elseif ($status == 5) {
      $this->db->join('reason', 'reason.ReasonId = voucher_status_history.CancelReasons');
    }

    $this->db->where('VoucherId',$id);
    $this->db->order_by('VoucherStatusHistoryId', 'desc');
    $query = $this->db->get('voucher_status_history');
    return $query->row();
  }

  function get_status(){
    $array = [1,2,3,4,5,6,7,8,11];
    $this->db->where_in('VoucherStatusId',$array);
    $this->db->order_by('VoucherStatusName', 'asc');
    $query = $this->db->get('voucher_status');
    return $query;
  }

  //vouchers card
  function get_voucher_issued_today(){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('VoucherActivityCategoryId',1);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_issued_today_store($id){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId,StoreId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('VoucherActivityCategoryId',1);
    $this->db->where('StoreId', $id);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_redeem_today(){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('VoucherActivityCategoryId',3);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_redeem_today_store($id){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId,StoreId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('VoucherActivityCategoryId',3);
    $this->db->where('StoreId', $id);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_void_today(){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('VoucherActivityCategoryId',5);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_void_today_store($id){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId,StoreId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('VoucherActivityCategoryId',5);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('StoreId', $id);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_refund_today(){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('VoucherActivityCategoryId',6);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }

  function get_voucher_refund_today_store($id){
    $this->db->select('VoucherTypeId,ActivityDate,VoucherActivityCategoryId,StoreId');
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('VoucherActivityCategoryId',6);
    $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $this->db->where('StoreId', $id);
    $this->db->from('voucher_activity');
    return $this->db->count_all_results();
  }
  //vouchers card

  function get_reasons_block(){
    $this->db->join("reason_type","reason_type.ReasonTypeId = reason.ReasonTypeId");
    $this->db->where('reason_type.ReasonTypeId',2);
    $this->db->order_by('reason.ReasonName', 'asc');
    $query = $this->db->get('reason');
    return $query;
  }


  function get_reasons_cancel(){
    $this->db->join("reason_type","reason_type.ReasonTypeId = reason.ReasonTypeId");
    $this->db->where('reason_type.ReasonTypeId',1);
    $this->db->order_by('reason.ReasonName', 'asc');
    $query = $this->db->get('reason');
    return $query;
  }

  function voucherEdit($get, $id){
    if (isset($get['remark'])) {
      $data = array(
                    'Remarks'         => $get['remark'],
      );
    }
    $this->db->where('VoucherId',$id);
    return $this->db->update('vouchers',$data);
  }

  function voucherEdit_today($get, $id){
    if (isset($get['remark'])) {
      if (isset($get['status']) == 5 || isset($get['status']) == 6) {
        $data = array(
                      'VoucherStatusId' => $get['status'],
                      'Remarks'         => $get['remark'],
        );
      }else {
        $data = array(
                      'Remarks'         => $get['remark'],
        );
      }
    }

    $this->db->where('VoucherId',$id);
    return $this->db->update('vouchers',$data);
  }

  function voucherReason($voucherid,$get){

      if ($get['status'] == 5) {
        $data = array(
                      'VoucherId'        => $voucherid,
                      'CancelDate'       => $get['canceldate'].' '.date('H:i:s', time()),
                      'CancelReasons'    => $get['cancelReason'],
        );
      }elseif ($get['status'] == 6) {
        $data = array(
                      'VoucherId'        => $voucherid,
                      'BlockDate'        => $get['blockDate'].' '.date('H:i:s', time()),
                      'BlockReasons'     => $get['blockReason'],
        );
      }elseif ($get['status'] == 3) {
        $data = array(
                      'VoucherId'        => $voucherid,
                      'ExtendDate'       => $get['extenddate'],
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

  function getVoucherNumber($id){
    $array = ['1','3','10'];

    $this->db->select('*');
    $this->db->where('VouchersNumber',$id);
    $this->db->where_in('VoucherStatusId',$array);
    // $condition = array('vouchers.VoucherStatusId' => 1, 'vouchers.VoucherStatusId' => 3, 'vouchers.VoucherStatusId' => 10);
    // $this->db->where($condition);
    $query = $this->db->get('vouchers');
    return $query->row();
  }

  //update import voucher
  function editimportVoucher($data){
    // $this->db->where('VoucherId',$data['VoucherId']);
    $this->db->update_batch('vouchers',$data,'VoucherId');
  }

  function insert_voucherStatusHistory($update){
    if ($update['VoucherStatusId'] == 5) {
      $data = array(
                      'VoucherId' => $update['VoucherId'],
                      'CancelDate' => date('Y-m-d H:i:s', time()),
                 );
    }elseif ($update['VoucherStatusId'] == 6) {
        $data = array(
            'VoucherId' => $update['VoucherId'],
            'BlockDate' => date('Y-m-d H:i:s', time()),
       );
    }
       $result = $this->db->insert('voucher_status_history', $data);
       return $this->db->insert_id();
  }

  function voucherDetailsEdit($get,$id){

    $data = array(

                  'Remarks'        => $get['remark'],
    );
    $this->db->where('VoucherId',$id);
    $this->db->update('vouchers',$data);
  }

  function get_permission($id){
    $this->db->where('StatusId', 1);
    $this->db->where('UserId', $id);
    $query = $this->db->get('user_voucher_settings');
    return $query->row();
  }

  function get_statusUnblockPermission(){
    $array = [1,3];
    $this->db->where_in('VoucherStatusId',$array);
    $query = $this->db->get('voucher_status');
    return $query;
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
    $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
    $this->db->where("vouchers.VoucherId", $id);
    $this->db->order_by('VoucherStatusHistoryId', 'desc');
    $query = $this->db->get('vouchers');
    return $query->row();
  }

    function get_userCount($userid)
    {
      $this->db->where('user_store.UserId',$userid);
      $query = $this->db->get('user_store');
      return $query->row();
    }

     function voucher_report($obj,$permission)
    {
        $this->db->select('*');
        $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
        $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
        $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
        $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId");
        $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
        $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId","inner");

      if ($permission->UserRoleId != 1) {
        if ($permission->StoreId != 0) {
          $this->db->where("voucher_issuance.IssuanceStoreId", $permission->StoreId);
        }
      }

      $query = $this->db->get('vouchers');
      return $query->result();
    }

    function get_vouchertype($id) {
      $this->db->where('voucher_type.VoucherTypeId',$id);
      $query = $this->db->get('voucher_type');
      if (isset($query->row()->VoucherName)) {
        return $query->row()->VoucherName;
      } else {
        return 'All Voucher';
      }
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

    function count_shortname($id){
      $this->db->where('VoucherShortName', $id);
      $this->db->where('StatusId', 1);
      $query = $this->db->get('voucher_type');
      return $query->num_rows();
    }

    function updateStatus($vouchershortname) {
      $this->db->set('StatusId', 2);
      $this->db->where('VoucherShortName', $vouchershortname);
      return $this->db->update('voucher_type');
    }

    function get_vouchername($id) {
      $this->db->where('voucher_type.VoucherTypeId',$id);
      $query = $this->db->get('voucher_type');
      return $query->row()->VoucherName;
    }

    function get_voucherno($id) {
      $this->db->where('vouchers.VoucherId',$id);
      $query = $this->db->get('vouchers');
      return $query->row()->VouchersNumber;
    }

    function count_vouchervalue($id){
      $this->db->where('VoucherTypeId',$id);
      $query = $this->db->get('voucher_type');
      return $query->row();
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
}

 ?>
