<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VoucherLog_Model extends CI_Model

{
  var $table         = 'voucher_activity'; //DATABASE TABLE NAME
  var $column_order  = array('user.Fullname', 'user_role.Role','ActivityDate','voucher_activity_category.VoucherActivityCategoryName','Details','voucher_activity.VouchersNumber', 'voucher_type.VoucherName'); //FIELD IN TABLE
  var $column_search = array('user.Fullname','user_role.Role', 'ActivityDate','voucher_activity_category.VoucherActivityCategoryName','Details','voucher_activity.VouchersNumber', 'voucher_type.VoucherName'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('ActivityDate' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
    {
      if($post['search']['value']) // if datatable send POST for search
      {
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $post['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $post['search']['value']);
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
    $this->db->select("user.Fullname,user_role.Role, voucher_activity.ActivityDate,voucher_activity_category.VoucherActivityCategoryName,voucher_activity.Details,voucher_activity.VouchersNumber,
    voucher_type.VoucherName, voucher_activity.VoucherLogsId,voucher_activity.VoucherTypeId");

    $this->db->join("user","user.UserId = voucher_activity.UserId", "inner");
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId", "left");
    $this->db->join("voucher_type", "voucher_type.VoucherTypeId = voucher_activity.VoucherTypeId");
    $this->db->join("voucher_activity_category", "voucher_activity_category.VoucherActivityCategoryId = voucher_activity.VoucherActivityCategoryId", "left");


    if (isset($post['filter_role']) && $post['filter_role'] !=0) {
      $this->db->where('user.UserRoleId', $post['filter_role']);
    }

    if (isset($post['filter_name']) && $post['filter_name'] != "") {
      $this->db->like('user.Fullname', $post['filter_name']);
    }

    if (isset($post['filter_voucherno']) && $post['filter_voucherno'] != "") {
      $this->db->like('voucher_activity.VouchersNumber', $post['filter_voucherno']);
    }

    if (isset($post['filter_category']) && $post['filter_category'] != 0) {
      $this->db->where('voucher_activity_category.VoucherActivityCategoryId', $post['filter_category']);
    }

    if (isset($post['date_from']) && $post['date_from'] != "") {
      $this->db->where('DATE(voucher_activity.ActivityDate) >=', $post['date_from']);
    }

    if (isset($post['date_to']) && $post['date_to'] != "") {
      $this->db->where('DATE(voucher_activity.ActivityDate) <=', $post['date_to']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->join("user_store", "user_store.UserId = voucher_activity.UserId");
        $this->db->where("voucher_activity.StoreId", $permission->StoreId);
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
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($post,$userid,$permission)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$userid,$permission);
    return $this->db->count_all_results();
  }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User list table**

  function get_role() {
    $this->db->order_by('Role', 'asc');
    $this->db->where('UserRoleId !=',99);
    $query = $this->db->get('user_role');
    return $query;
  }

  function get_category() {
    $this->db->order_by('VoucherActivityCategoryName', 'asc');
    $query = $this->db->get('voucher_activity_category');
    return $query;
  }

  function get_username() {
    $query = $this->db->get('user');
    return $query;
  }

  function get_voucherdetails($voucherissuanceid) {
    $this->db->select("*");
    $this->db->select("voucher_activity.ReceiptNumber as RealReceiptNumber");
    $this->db->select("voucher_activity.ReceiptDateTime as RealReceiptDateTime");
    $this->db->select("voucher_activity.ActivityDate as ReactiveDate");

    $this->db->join("vouchers","vouchers.VouchersNumber = voucher_activity.VouchersNumber");
    $this->db->join('app_logger','app_logger.AppLoggerId = vouchers.AppLoggerId','inner');
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");

    $this->db->where('voucher_activity.VoucherLogsId',$voucherissuanceid);

    $query = $this->db->get('voucher_activity');
    return $query->row();
  }

  function get_voucherdetailsgift($voucherissuanceid) {
    $this->db->select("*");
    $this->db->select("voucher_activity.ReceiptNumber as RealReceiptNumber");
    $this->db->select("voucher_activity.ReceiptDateTime as RealReceiptDateTime");
    $this->db->select("voucher_activity.PosId as RedemptionPos");

    $this->db->join("gift_voucher","gift_voucher.VouchersNumber = voucher_activity.VouchersNumber");
    $this->db->join('app_logger', 'app_logger.AppLoggerId = gift_voucher.AppLoggerId','inner');
    $this->db->join("gift_voucher_issuance","gift_voucher_issuance.GiftVoucherIssuanceId = gift_voucher.VoucherIssuanceId");

    $this->db->where('voucher_activity.VoucherLogsId',$voucherissuanceid);

    $query = $this->db->get('voucher_activity');
    return $query->row();
  }

  function get_issuance($id) {
    $this->db->select("voucher_issuance.IssuancePOSId, voucher_issuance.ReceiptNumber, voucher_issuance.ReceiptDateTime, posA.POSId,posA.POSNumber");
    $this->db->select("voucher_issuance.ReceiptNumber as OriginalReceiptNumber");
    $this->db->select("voucher_issuance.ReceiptDateTime as OriginalReceiptDateTime");
    $this->db->select("posA.POSNumber as OriginalPOSNumber");

    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
    $this->db->join("pos as posA","posA.POSId = voucher_issuance.IssuancePOSId");

    $this->db->where('voucher_issuance.VoucherIssuanceId',$id);
    $query = $this->db->get('voucher_issuance');
    return $query->row();
  }

  function get_gift($id) {
    $this->db->select("*");
    $this->db->join("store","store.StoreId = gift_voucher_issuance.StoreId");
    $this->db->join("pos as posA","posA.POSId = gift_voucher_issuance.PosId");

    $this->db->where('gift_voucher_issuance.GiftVoucherIssuanceId',$id);
    $query = $this->db->get('gift_voucher_issuance');
    return $query->row();
  }

  function get_card($id) {
    $this->db->join("card","card.CardId = voucher_card.CardId");

    $this->db->where('voucher_card.VoucherIssuanceId',$id);
    $query = $this->db->get('voucher_card');
    return $query->result();
  }

  function get_redeemreceipt($id,$category) {
    $this->db->where("voucher_activity.VouchersId", $id);
    $this->db->where("voucher_activity.VoucherActivityCategoryId", $category);
    $this->db->order_by("VoucherLogsId","desc");
    $query = $this->db->get('voucher_activity');

    if (isset($query->row()->ReceiptNumber)) {
      return $query->row()->ReceiptNumber;
    } else {
      return '';
    }
  }

  function get_voucher_pos($id) {
    $this->db->where('pos.POSId',$id);
    $query = $this->db->get('pos');
    return $query->row();
  }

  function insert_activity($act) {
    $result = $this->db->insert('voucher_activity', $act);
    return $this->db->insert_id();
  }

  function insert_batch_activity($act2){
    $batchSize = 1000;
    $chunks    = array_chunk($act2,$batchSize);

    foreach ($chunks as $chunk) {
      $this->db->insert_batch('voucher_activity',$chunk);
    }
  }

  public function voucherlog_csv($obj,$permission) {
    $this->db->select("*");
    $this->db->join("user","user.UserId = voucher_activity.UserId");
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
    $this->db->join("voucher_type", "voucher_type.VoucherTypeId = voucher_activity.VoucherTypeId");
    $this->db->join("voucher_activity_category", "voucher_activity_category.VoucherActivityCategoryId = voucher_activity.VoucherActivityCategoryId");
    $this->db->join("user_store", "user_store.UserId = user.UserId");
    $this->db->join("store", "store.StoreId = user_store.StoreId");

    $this->db->order_by("DATE(voucher_activity.ActivityDate)");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(voucher_activity.ActivityDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(voucher_activity.ActivityDate) <=',$obj['end']);
    }

    if (isset($obj['category']) && $obj['category'] != 0) {
      $this->db->where('voucher_activity.VoucherActivityCategoryId', $obj['category']);
    }

    if (isset($obj['role']) && $obj['role'] != 0) {
      $this->db->where('user_role.UserRoleId', $obj['role']);
    }

    if (isset($obj['name']) && $obj['name'] != 0) {
      $this->db->like('user.Fullname', $obj['name']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("user_Store.StoreId", $permission->StoreId);
      }
    }

    $query = $this->db->get('voucher_activity');
    return $query->result();
  }

  function get_user($id) {
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");

    $this->db->where("user.UserId",$id);
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_bystore($id) {
    $this->db->join("user_store","user_store.UserId = user.UserId");
    $this->db->join("store","store.StoreId = user_store.StoreId");

    $this->db->where('user.UserId',$id);
    $query = $this->db->get('user');
    return $query->row()->StoreName;
  }

  function get_bycategory($id) {
    $this->db->where('voucher_activity_category.VoucherActivityCategoryId',$id);
    $query = $this->db->get('voucher_activity_category');
    if (isset($query->row()->VoucherActivityCategoryName)) {
      return $query->row()->VoucherActivityCategoryName;
    } else {
      return 'ALL CATEGORY';
    }
  }

  function get_permissionvoucherlog($id) {
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");

    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function userstore($id) {
    $this->db->where("UserId", $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }
}
?>
