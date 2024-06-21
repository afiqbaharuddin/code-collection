<?php

  class dashboard_Model extends CI_Model
  {
    //iddle log out
    function get_generalSetting(){
      $this->db->where('StatusId', 1);
      $query = $this->db->get('general_settings');
      return $query->row();
    }

    function get_userFlag($user){
      $this->db->where('UserId',$user);
      $query = $this->db->get('user');
      return $query->row();
    }

    function passexpirycheck($user){
      // $this->db->select('expiryPassword');
      // $this->db->where('UserId',$user);
      $this->db->order_by('PasswordLogId','desc');
      $query = $this->db->get('password_log');
      return $query->row();
    }

    function oneTimePass(){
      // $this->db->where('UserId',$user);
      $this->db->order_by('PassSettingId', 'desc');
      $query = $this->db->get('password_settings');
      return $query->row();
    }

    function updateloginFlag($notfirst){
      $this->db->where('FirstTimeLogin', 1);
      $this->db->update('user', $notfirst);
    }

    function get_voucher_issued_today_today($store){
      $this->db->select('HOUR(ActivityDate) as hour, VoucherActivityCategoryId');
      $array = [1,7,5,3];
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('voucher_activity)' != 1);
      $this->db->where_in('VoucherActivityCategoryId',$array);
      if ($store != 0) {
        $this->db->where('voucher_activity.StoreId', $store);
      }
      $query = $this->db->get('voucher_activity');
      return $query->result();
    }

    function get_storedetails()
    {
      $this->db->where('StoreId !=',0);
      $this->db->where('StoreStatusId',1);
      $query = $this->db->get('store');
  		return $query->num_rows();
    }

    function get_storeclose()
    {
      $this->db->where('StoreStatusId',2);
      $query = $this->db->get('store');
  		return $query->num_rows();
    }

    function get_campaign(){
      $this->db->where('CampaignStatusId',1);
      $query = $this->db->get('campaign');
      return $query->num_rows();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PV and DV info card section

    function get_voucher_issued(){
      $this->db->select('vouchers.VoucherIssuanceId');
      $this->db->from('vouchers');
      return $this->db->count_all_results();
    }

    function get_voucher_issued_store($id){
      $this->db->select('vouchers.VoucherIssuanceId,voucher_issuance.IssuanceStoreId');
      $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = vouchers.VoucherIssuanceId");
      $this->db->where('voucher_issuance.IssuanceStoreId', $id);
      $this->db->from('vouchers');
      return $this->db->count_all_results();
    }

    function get_voucher_issued_today(){
      $this->db->select('ActivityDate,VoucherActivityCategoryId,VoucherTypeId');
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherActivityCategoryId',1);
      $this->db->where('VoucherTypeId !=',1);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_voucher_issued_today_store($id){
      $this->db->select('voucher_activity.StoreId,ActivityDate,VoucherActivityCategoryId');
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherActivityCategoryId',1);
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('voucher_activity.StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    //redeem
    function get_voucher_redeem(){
      $this->db->select('VoucherActivityCategoryId,VoucherTypeId');
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->where('VoucherTypeId !=',1);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_voucher_redeem_today(){
      $this->db->select('ActivityDate,VoucherActivityCategoryId,VoucherTypeId');
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_voucher_redeem_store($id){
      $this->db->select('VoucherActivityCategoryId,StoreId');
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return  $this->db->count_all_results();
    }

    function get_voucher_redeem_today_store($id){
      $this->db->select('ActivityDate,VoucherActivityCategoryId,StoreId');
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    //void today all
    function get_voucher_void(){
      $this->db->select('ActivityDate,VoucherActivityCategoryId,VoucherTypeId');
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherActivityCategoryId',5);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_vouchers_void_today_store($id){
      $this->db->select('ActivityDate,VoucherActivityCategoryId,StoreId,VoucherTypeId');
      $this->db->where('VoucherTypeId !=',1);
      $this->db->where('DATE(ActivityDate)', date('Y-m-d'));
      $this->db->where('VoucherActivityCategoryId',5);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    //refund
    function get_voucher_refund(){
      $this->db->select('VoucherActivityCategoryId');
      $this->db->where('VoucherActivityCategoryId',6);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

    function get_voucher_refund_store($id){
      $this->db->select('VoucherActivityCategoryId,StoreId');
      $this->db->where('VoucherActivityCategoryId',6);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

// End  of PV and DV Infocard Section
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// GV Infocard section

    function get_giftvoucher_issued(){
      $this->db->select('gift_voucher.VoucherIssuanceId');
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_giftvoucher_issued_store($id){
      $this->db->select('StoreId');
      $this->db->where('gift_voucher.StoreId', $id);
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_giftvoucher_redeem(){
      $this->db->select('VoucherStatusId');
      $this->db->where('VoucherStatusId',7);
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_giftvoucher_redeem_store($id){
      $this->db->select('VoucherActivityCategoryId,StoreId,VoucherTypeId');
      $this->db->where('VoucherActivityCategoryId',3);
      $this->db->where('VoucherTypeId',1);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return  $this->db->count_all_results();
    }

    function get_giftvoucher_void(){
      $this->db->select('VoucherStatusId');
      $this->db->where('VoucherStatusId',4);
      $this->db->from('gift_voucher');
      return $this->db->count_all_results();
    }

    function get_giftvouchers_void_store($id){
      $this->db->select('StoreId,VoucherActivityCategoryId,VoucherTypeId');
      $this->db->where('VoucherActivityCategoryId',5);
      $this->db->where('VoucherTypeId',1);
      $this->db->where('StoreId', $id);
      $this->db->from('voucher_activity');
      return $this->db->count_all_results();
    }

// End of GV Infocard Section
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function get_userCount($userid)
    {
      $this->db->where('user_store.UserId',$userid);
      $query = $this->db->get('user_store');
      return $query->row();
    }

    //force first time usere change password
    function get_securitydetails($id)
    {
      $this->db->select('*');
      $this->db->from('user');
      $this->db->where('UserId', $id);
      $query = $this->db->get();
      $query = $query->row();
      return $query;
    }

    function securityUpdate($get,$id){
      if ($get['newPassword'] !=null) {

        $pass    = encrypt($get['newPassword'],ENCRYPTION_KEY);

        $data = array(
                      'Password'   => $pass,
                 );
      }
      $this->db->where('UserId', $id); //first column
      $this->db->update('user',$data);
    }

    function user_role($id){
      $this->db->join('user','user.UserRoleId = user_role.UserRoleId');
      $this->db->where('UserId', $id);
      $query = $this->db->get('user_role');
      return $query->row();
    }

    function store($id){
      // $this->db->join('user','user.UserId = user_store.UserId');
      $this->db->where('UserId', $id);
      $query = $this->db->get('user_store');
      return $query->row();
    }
  }
 ?>
