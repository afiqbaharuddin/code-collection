<?php
  /**
   *
   */
  class voucherLayout_Model extends CI_Model
  {
    function get_reprintSuccess($reprintid){
      $this->db->select('*');
      // $this->db->join("reprint_voucher","reprint_voucher.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
      $this->db->join("vouchers","vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
      $this->db->join("voucher_type","voucher_type.VoucherTypeId = vouchers.VoucherTypeId");
      $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId");
      $this->db->join("pos","pos.POSId = voucher_issuance.IssuancePOSId");
      $this->db->where('voucher_issuance.VoucherIssuanceId', $reprintid);
      $this->db->order_by('vouchers.VoucherTypeId', 'asc');
      $this->db->order_by('vouchers.VouchersValue', 'desc');
      $query = $this->db->get('voucher_issuance');
      return $query->result();
    }

    // function voucherbarcode($reprintid){
    // $this->db->join("vouchers","vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId");
    // $this->db->where('voucher_issuance.VoucherIssuanceId', $reprintid);
    // $query = $this->db->get('vouchers');
    // return $query->result();
    // }

    function get_duplicateVoucher($id){
      $this->db->select('ReceiptNumber');
      $this->db->where('VoucherIssuanceId', $id);
      $query = $this->db->get("reprint_voucher");
      return $query->row();
    }

    function get_extendDate($id){
      // $this->db->select('ExtendDate');
      // $this->db->where('VoucherIssuanceId', $id);
      $this->db->join("vouchers","vouchers.VoucherId = voucher_status_history.VoucherId");
      $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
      $this->db->where('vouchers.VoucherIssuanceId', $id);
      $this->db->order_by('voucher_status_history.VoucherStatusHistoryId', 'desc');
      $query = $this->db->get("voucher_status_history");
      return $query->row();
    }

    function get_printStaffId($id)
    {
      // $this->db->join('user','user.UserId = voucher_issuance.IssuanceLoginId');
      // $this->db->where('voucher_issuance.VoucherIssuanceId',$id);
      // $query = $this->db->get('voucher_issuance');
      // return $query->row();

      $this->db->where('UserId',$id);
      $query = $this->db->get('user');
      return $query->row();
    }

    function insertlog($data) {
      $this->db->insert('printer_log', $data);
    }

    function get_status() {
      $query = $this->db->get('printer_log');
      return $query->result();
    }

    function updateStatus($id){
      $data =
              [
                'PrintedStatus' =>1,
              ];

      // $this->db->where('PrintedStatus', 2);
      $this->db->where('VoucherId', $id);
      $this->db->update('printer_log', $data);
    }

    function get_status_pending($id){
      $this->db->where('VoucherId', $id);
      $this->db->order_by('PrinterLogId', 'desc');
      $query = $this->db->get('printer_log');
      return $query->row();
    }

  }
 ?>
