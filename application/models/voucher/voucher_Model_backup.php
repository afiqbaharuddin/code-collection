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
      // $this->db->select('*');
      $this->db->select("store.StoreCode,vouchers.VouchersNumber,app_logger.CreatedDate,voucher_type.VoucherName,vouchers.VouchersValue,vouchers.ExpDate,
      voucher_status.VoucherStatusName,vouchers.VoucherStatusId,voucher_status.VoucherStatusColor,vouchers.VoucherId,voucher_status.VoucherStatusId");

      $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
      $this->db->join("app_logger","app_logger.AppLoggerId = vouchers.AppLoggerId", "left");
      $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->join("voucher_status","voucher_status.VoucherStatusId = vouchers.VoucherStatusId");
      $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
      $this->db->order_by('vouchers.Numbering','desc');

      if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
        $this->db->join("user_store","user_store.StoreId = store.StoreId");
      }

    if (isset($post['filterStoreCode']) && $post['filterStoreCode'] != "") {
      if (!in_array(0,$post['filterStoreCode'])) {
        $this->db->where_in('store.StoreId', $post['filterStoreCode']);
      }
    }

    if (isset($post['filterPosNumber']) && $post['filterPosNumber'] != "") {
      $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId");
      $this->db->where('pos.POSNumber', $post['filterPosNumber']);
    }

    if (isset($post['filterReceiptNumber']) && $post['filterReceiptNumber'] != "") {
      $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");
      $this->db->where('voucher_activity.ReceiptNumber', $post['filterReceiptNumber']);
    }

    if (isset($post['filtervoucherno']) && $post['filtervoucherno'] != "") {
        $this->db->where('vouchers.VouchersNumber', $post['filtervoucherno']);
    }

    if (isset($post['filter_status']) && $post['filter_status'] != "") {
      if (!in_array(0,$post['filter_status'])) {
        $this->db->where_in('vouchers.VoucherStatusId', $post['filter_status']);
      }
    }

    if (isset($post['vouchertypefilter']) && $post['vouchertypefilter'] != "") {
      if (!in_array(0,$post['vouchertypefilter'])) {
        $this->db->where_in('vouchers.VoucherTypeId', $post['vouchertypefilter']);
      }
    }

    if (isset($post['filter_status'])) {
      if ($post['filter_status'][0] == 7) {
        $this->db->join("voucher_activity","voucher_activity.VouchersId = vouchers.VoucherId");
        $this->db->where('VoucherActivityCategoryId',3);
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_activity.ReceiptDateTime) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_activity.ReceiptDateTime) <=',$post['date_to']);
        }
        // if (isset($post['date_from']) && $post['date_from'] != null) {
        //   $this->db->where('DATE(vouchers.RedemptionDateTime) >=',$post['date_from']);
        // }
        // if (isset($post['date_to']) && $post['date_to'] != null) {
        //   $this->db->where('DATE(vouchers.RedemptionDateTime) <=',$post['date_to']);
        // }
      } elseif ($post['filter_status'][0] == 8) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_status_history.RefundDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_status_history.RefundDate) <=',$post['date_to']);
        }
      } elseif ($post['filter_status'][0] == 4) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_status_history.VoidDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_status_history.VoidDate) <=',$post['date_to']);
        }
      } elseif ($post['filter_status'][0] == 2) {
        // $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(vouchers.ExpDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(vouchers.ExpDate) <=',$post['date_to']);
        }
      } elseif ($post['filter_status'][0] == 5) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_status_history.CancelDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_status_history.CancelDate) <=',$post['date_to']);
        }
      } elseif ($post['filter_status'][0] == 3) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_status_history.ExtendDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_status_history.ExtendDate) <=',$post['date_to']);
        }
      } elseif ($post['filter_status'][0] == 6) {
        $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(voucher_status_history.BlockDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(voucher_status_history.BlockDate) <=',$post['date_to']);
        }
      } else {
        if (isset($post['date_from']) && $post['date_from'] != null) {
          $this->db->where('DATE(app_logger.CreatedDate) >=',$post['date_from']);
        }
        if (isset($post['date_to']) && $post['date_to'] != null) {
          $this->db->where('DATE(app_logger.CreatedDate) <=',$post['date_to']);
        }
      }
    } else {
      if (isset($post['date_from']) && $post['date_from'] != null) {
        $this->db->where('DATE(app_logger.CreatedDate) >=',$post['date_from']);
      }
      if (isset($post['date_to']) && $post['date_to'] != null) {
        $this->db->where('DATE(app_logger.CreatedDate) <=',$post['date_to']);
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
    if (isset($post['filter_status'][0] )) {
      // || $post['filter_status'][0] == 2
      if ($post['filter_status'][0] == 8 ) {
        $this->db->group_by('voucher_status_history.VoucherId');
      }
    }

    $this->db->limit($post['length'], $post['start']);
    $this->_get_datatables_query($post);

    if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
      $this->db->where("user_store.UserId", $userid);
    }

    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($post,$userid,$permission)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$userid,$permission);
    $this->_get_datatables_query($post);

    if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
      $this->db->where("user_store.UserId", $userid);
    }

    $query = $this->db->get();
    return $query->num_rows();
  }

   function count_all($post,$userid,$permission)
    {
      $this->db->from($this->table);
      $this->_extra_datatables($post,$userid,$permission);

      if ($permission->UserRoleId != 1 && $permission->StoreId != 0 || $permission->StoreId != 0) {
        $this->db->where("user_store.UserId", $userid);
      }

      return $this->db->count_all_results();
    }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User list table**

  function get_redeemtype() {
    $query = $this->db->get('redeem_type');
    return $query;
  }

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

  function get_filterpos() {
    $this->db->order_by('POSNumber','asc');
    $query = $this->db->get('pos');
    return $query;
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
      if ($get['prefix2'] == null) {
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
                      'StatusId'                   => $get['voucherstatus'],
                      'ActivateDate'               => $get['date'],
                      'Remarks'                    => $get['remarks'],
                      'AppLoggerId'                => $get['loggerid'],
          );
      }
    } else {
      if ($get['prefix2'] == null) {
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

    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $query = $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $query = $this->db->where('VoucherActivityCategoryId',1);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_issued_today_store($id){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $query = $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
    $query = $this->db->where('VoucherActivityCategoryId',1);
    $query = $this->db->where('voucher_activity.StoreId', $id);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_redeem_today(){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $this->db->where('voucher_activity.VoucherActivityCategoryId',3);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_redeem_today_store($id){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $this->db->where('voucher_activity.VoucherActivityCategoryId',3);
    $this->db->where('voucher_activity.StoreId', $id);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_void_today(){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('voucher_activity.VoucherActivityCategoryId',5);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_void_today_store($id){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('voucher_activity.VoucherActivityCategoryId',5);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $this->db->where('voucher_activity.StoreId', $id);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_refund_today(){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('voucher_activity.VoucherActivityCategoryId',6);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
  }

  function get_voucher_refund_today_store($id){
    $this->db->where('voucher_activity.VoucherTypeId !=', 1);
    $this->db->where('voucher_activity.VoucherActivityCategoryId',6);
    $this->db->where('DATE(voucher_activity.ActivityDate)', date('Y-m-d'));
    $this->db->where('voucher_activity.StoreId', $id);
    $query = $this->db->get('voucher_activity');
    return $query->num_rows();
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

  function voucherReason($voucherid,$get){

      if ($get['status']==5) {
        $data = array(
                      'VoucherId'        => $voucherid,
                      'CancelDate'       => $get['canceldate'].' '.date('H:i:s', time()),
                      'CancelReasons'    => $get['cancelReason'],
        );
      }elseif ($get['status']==6) {
        $data = array(
                      'VoucherId'        => $voucherid,
                      // 'BlockDate'        => $get['blockDate'],
                      'BlockDate'        => $get['blockDate'].' '.date('H:i:s', time()),
                      'BlockReasons'     => $get['blockReason'],
        );
      }elseif ($get['status']==3) {
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
}

 ?>
