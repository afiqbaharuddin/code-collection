<?php
/**
 *
 */
class scheduler_Model extends CI_Model
{
  // get start date manager Duty
  function get_StartDateManager(){
    $query = $this->db->get('managerduty');
    return $query->result();
  }

  function update_MOD($data){
    return $this->db->update_batch('managerduty',$data,'ManagerDutyId');
  }

  function update_User_MOD($data){
    return $this->db->update_batch('user',$data,'UserId');
  }

  function update_UserStore_MOD($data){
    return $this->db->update_batch('user_store',$data,'UserId');
  }

  //expired manager On Duty
  function get_EndDateManager(){
    $this->db->where('StatusId', 4);
    $query = $this->db->get('managerduty');
    return $query->result();
  }

  function update_EndDateManager($data){
    return $this->db->update_batch('managerduty',$data,'ManagerDutyId');
  }

  //INACTIVE expired manager On Duty
  // function get_EndDateManagerInactive(){
  //   $this->db->where('StatusId', 2);
  //   $query = $this->db->get('managerduty');
  //   return $query->result();
  // }

  function get_statusManager(){
    $today        = date('Y-m-d');
    $this->db->where('StartDate', $today);
    $query = $this->db->get('managerduty');
    return $query->result();
  }

  function get_statusManager2(){
    $this->db->where('StatusId', 3);
    $this->db->order_by('ManagerDutyId', 'desc');
    $this->db->group_by('UserId');
    $query = $this->db->get('managerduty');
    return $query->result();
  }

  function updateToOldRoleUser($data){
    return $this->db->update_batch('user',$data,'UserId');
  }

  function updateToOldRoleStore($data){
    return $this->db->update_batch('user_store',$data,'UserId');
  }

  //checking expired cards
  function get_EndCard(){
    $this->db->where('StatusId', 1);
    $query = $this->db->get('card_store');
    return $query->result();
  }

  function update_Card($data){
    return $this->db->update_batch('card_store',$data,'CardStoreId');
  }

  //voucher type
  function get_statusVoucherType(){
    $this->db->where('StatusId', 1);
    $query = $this->db->get('voucher_type');
    return $query->result();
  }

  function update_statusVoucherTyp($data){
    return $this->db->update_batch('voucher_type',$data,'VoucherTypeId');
  }

  //vouchers
  function get_statusVouchers(){
    $this->db->where('VoucherStatusId', 1);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function update_statusVouchers($data){
    return $this->db->update_batch('vouchers',$data,'VoucherId');
  }

  //gift vouchers
  function get_statusGiftVouchers(){
    $this->db->where('VoucherStatusId', 1);
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  function update_statusGiftVouchers($data){
    return $this->db->update_batch('gift_voucher',$data,'GiftVouchersId');
  }

  function get_statusActiveGift(){
    $this->db->join("voucher_status_history","voucher_status_history.GiftVouchersId = gift_voucher.GiftVouchersId");
    $this->db->where('VoucherStatusId', 1);
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  //block/cancel (Vouchers)
  function get_vouchers(){
    $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
    $this->db->where('vouchers.VoucherStatusId', 1);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function update_vouchers($data){
    return $this->db->update_batch('vouchers',$data,'VoucherId');
  }

  //block/cancel (gift Vouchers)
  function get_giftvouchers(){
    $this->db->join("voucher_status_history","voucher_status_history.GiftVouchersId = gift_voucher.GiftVouchersId");
    $this->db->where('gift_voucher.VoucherStatusId', 1);
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  function update_giftvouchers($data){
    return $this->db->update_batch('gift_voucher',$data,'GiftVouchersId');
  }

  //campaign expired
  function get_statusCampaign(){
    $array = [1,2,3];
    $this->db->where_in('CampaignStatusId', $array);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function update_statusCampaign($data){
    return $this->db->update_batch('campaign',$data,'CampaignId');
  }

  // campaign extend
  function get_statusCampaignExtend(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function update_statusCampaignExtend($data){
    return $this->db->update_batch('campaign',$data,'CampaignId');
  }

  //campaign store expired
  function get_expiredCampaignStore(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('campaign_store');
    return $query->result();
  }

  function get_campaign_date(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function update_expiredCampaignStore($data){
    return $this->db->update_batch('campaign_store',$data,'CampaignStoreId');
  }

  //campaign extend store
  function get_extendCampaignStore(){
    $this->db->where('CampaignStatusId', 3);
    $query = $this->db->get('campaign_store');
    return $query->result();
  }

  function update_extendCampaignStore($data){
    return $this->db->update_batch('campaign_store',$data,'CampaignStoreId');
  }

  //expired campaign cards
  function get_expiredCampaignCard(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('card_campaign');
    return $query->result();
  }

  function update_expiredCampaignCard($data){
    return $this->db->update_batch('card_campaign',$data,'CardCampaignId');
  }

  //extend campaign card
  function get_extendCampaignCard(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('card_campaign');
    return $query->result();
  }

  function update_extendCampaignCard($data){
    return $this->db->update_batch('card_campaign',$data,'CardCampaignId');
  }

  //terminate campaign card
  function get_TerminateCampaignCard(){
    $this->db->join('campaign', 'campaign.CampaignId = card_campaign. CampaignId');
    $array = [1,2,3];
    $this->db->where_in('campaign.CampaignStatusId', $array);
    $query = $this->db->get('card_campaign');
    return $query->result();
  }

  //update campaign card
  function update_terminateCampaignCard($data){
    return $this->db->update_batch('card_campaign',$data,'CardCampaignId');
  }

  //terminate campaign store
  function get_TerminateCampaignStore(){
    $this->db->join('campaign', 'campaign.CampaignId = campaign_store. CampaignId');
    $array = [1,2,3];
    $this->db->where_in('campaign.CampaignStatusId', $array);
    $query = $this->db->get('campaign_store');
    return $query->result();
  }

  function update_terminateCampaignStore($data){
    return $this->db->update_batch('campaign_store',$data,'CampaignStoreId');
  }

  //inactive user
  function get_inactiveUser(){
    $this->db->where('StatusId', 1);
    $query = $this->db->get('user');
    return $query->result();
  }

  function update_userstatus($data){
    return $this->db->update_batch('user',$data,'UserId');
  }

  function update_userstorestatus($data){
    return $this->db->update_batch('user_store',$data,'UserId');
  }

  //close store status
  function get_closeStore(){
    // $this->db->where('StoreStatusId', 1);
    $query = $this->db->get('store');
    return $query->result();
  }

  function update_storestatus($data){
    return $this->db->update_batch('store',$data,'StoreId');
  }

  //voucher type
  function get_inactiveVoucherType(){
    $this->db->where('StatusId', 1);
    $query = $this->db->get('voucher_type');
    return $query->result();
  }

  function update_VoucherType($data){
    return $this->db->update_batch('voucher_type',$data,'VoucherTypeId');
  }

  //inactive campaign
  function get_inactiveCampaign(){
    $this->db->where('CampaignStatusId', 1);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function update_campaign($data){
    return $this->db->update_batch('campaign',$data,'CampaignId');
  }

  //expired extend campaign
  function get_statusExtendCampaign(){
    $this->db->where('CampaignStatusId', 3);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function update_statusExtendCampaign($data){
    return $this->db->update_batch('campaign',$data,'CampaignId');
  }


  //expired extend vouchers
  function get_statusExtendVouchers(){
    $this->db->join("voucher_status_history","voucher_status_history.VoucherId = vouchers.VoucherId");
    $this->db->where('vouchers.VoucherStatusId', 3);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function update_statusExtendVouchers($data){
    return $this->db->update_batch('vouchers',$data,'VoucherId');
  }

  //expired extend gift vouchers
  function get_statusExtendGift(){
    $this->db->join("voucher_status_history","voucher_status_history.GiftVouchersId = gift_voucher.GiftVouchersId");
    $this->db->where('gift_voucher.VoucherStatusId', 3);
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  function update_statusExtendGift($data){
    return $this->db->update_batch('gift_voucher',$data,'GiftVouchersId');
  }

  //Import Gift Voucher Pending Check
  function get_pendingImport(){
    $this->db->where('ImportStatusId', 2);
    $query = $this->db->get('import_gift_voucher_scheduler');
    return $query->result();
  }

  //update csv file import status id
  function updateImportStatus($id) {
    $data = array(
      'ImportStatusId' => 1
    );

    $this->db->where('CSVFileId',$id);
    $this->db->update('import_gift_voucher_scheduler',$data);
  }

  function getGiftVoucherNumber(){
    $query = $this->db->get('gift_voucher');
    return $query->result();
  }

  function getStoreId(){
    $query = $this->db->get('store');
    return $query->result();
  }

  function insertImportGift($voucherDataBatch){
    $this->db->insert_batch('gift_voucher', $voucherDataBatch);
  }

  function updateImportError($fileId,$errorCount){
    $this->db->where('CSVFileId',$fileId);
    $this->db->update('import_gift_voucher_scheduler', array('ImportError' => $errorCount));
  }

  function schedulerImportErrorLog($fileId,$errorlog){
    $this->db->where('CSVFileId',$fileId);
    $batchSize = 1000;
    $chunks    = array_chunk($errorlog,$batchSize);
    foreach ($chunks as $chunk) {
      $this->db->insert_batch('scheduler_import_errorlog',$chunk);
    }
  }

  function get_pending_vouchers_by_csv_file_id($csvFileId){
    $this->db->select('*');
    $this->db->where('CSVFileId', $csvFileId);
    $query = $this->db->get('pending_gift_voucher');
    return $query->result();
  }

  function insert_gift_voucher($data) {
    $batchSize = 1000;
    $chunks    = array_chunk($data,$batchSize);
    foreach ($chunks as $chunk) {
      $this->db->insert_batch('gift_voucher',$chunk);
    }
  }

  function get_giftvoucherno($voucherNumber){
    $this->db->select('VouchersNumber');
    $batchSize      = 1000;
    $chunks         = array_chunk($voucherNumber,$batchSize);
    $i = 1;

    foreach ($chunks as $giftvoucher) {
      if ($i == 1) {
        $this->db->where_in('gift_voucher.vouchersNumber',$giftvoucher);
      }else {
        $this->db->or_where_in('gift_voucher.vouchersNumber',$giftvoucher);
      }
      $i++;
    }
    $query  = $this->db->get('gift_voucher');
    $result = $query->result_array();
    return array_column($result,'VouchersNumber');
  }

  function deleteData($csvFileId) {
    $this->db->where('CSVFileId', $csvFileId);
    $this->db->delete('pending_gift_voucher');
  }

  // // Bulk delete function
  public function deletegv() {
    $this->db->where_in('VoucherTypeId',1);
    return $this->db->delete('voucher_activity');
  }
}

 ?>
