<?php

// defined('BASEPATH') OR exit('No direct script access allowed');

Class reprintVoucher_Model extends CI_Model
{

  //reprint voucher get pos based on logged user
  function get_pos($StoreId) {
    // $this->db->join('store', 'store.StoreId = pos.StoreId');
    $this->db->where('pos.StatusId', 1);
    $this->db->where('pos.StoreId',$StoreId);
    $this->db->order_by('pos.POSNumber', 'asc');
    $query = $this->db->get('pos');
    return $query->result();
  }

  function get_posAdminissuance($StoreId) {
    $this->db->join('store', 'store.StoreId = pos.StoreId');
    $this->db->where('pos.StatusId', 1);
    $this->db->where('store.StoreCode',$StoreId);
    $this->db->order_by('pos.POSNumber', 'asc');
    $query = $this->db->get('pos');
    return $query->result();
  }

  function get_campaign_store($StoreCode){
    $this->db->select('*');
    $this->db->join('campaign','campaign.CampaignId = campaign_store.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign_store.InactiveDate) <=',date('Y-m-d'));
    $this->db->where('campaign_store.CampaignStatusId',2);
    $status = [1,2,3];
    $this->db->where_in('campaign.CampaignStatusId',$status);
    $query = $this->db->get('campaign_store');
    $storeA = $query->result();

    $this->db->select('*');
    $this->db->join('campaign','campaign.CampaignId = campaign_store.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign_store.ExtendDate) >',date('Y-m-d'));
    $this->db->where('campaign_store.CampaignStatusId',3);
    $query = $this->db->get('campaign_store');
    $storeB = $query->result();

    $this->db->select('*');
    $this->db->join('campaign','campaign.CampaignId = campaign_store.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign_store.StartDate) <=',date('Y-m-d'));
    $this->db->where('DATE(campaign_store.EndDate) >',date('Y-m-d'));
    $this->db->where('campaign_store.CampaignStatusId',1);
    $query = $this->db->get('campaign_store');
    $storeC = $query->result();

    $store = array_merge($storeA, $storeB);
    $store = array_merge($store, $storeC);

    return $store;
  }

  function get_campaign_admin($StoreCode){
    $this->db->select('*');
    $this->db->join('campaign_store','campaign_store.CampaignId = campaign.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign.InactiveDate) <=',date('Y-m-d'));
    $this->db->where('campaign.CampaignStatusId',2);
    $query = $this->db->get('campaign');
    $campaign = $query->result();

    $camid = [];
    foreach ($campaign as $row) {
      $camid[] = $row->CampaignId;
    }

    $this->db->select('*');
    $this->db->join('campaign_store','campaign_store.CampaignId = campaign.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign.ExtendDate) >',date('Y-m-d'));
    $this->db->where('campaign.CampaignStatusId',3);
    $query = $this->db->get('campaign');
    $campaign = $query->result();

    foreach ($campaign as $row) {
      $camid[] = $row->CampaignId;
    }

    $this->db->select('*');
    $this->db->join('campaign_store','campaign_store.CampaignId = campaign.CampaignId');
    $this->db->join('store', 'store.StoreId = campaign_store.StoreId','inner');
    $this->db->where('store.StoreCode',$StoreCode);
    $this->db->where('DATE(campaign.StartDate) <=',date('Y-m-d'));
    $this->db->where('DATE(campaign.EndDate) >',date('Y-m-d'));
    $this->db->where('campaign.CampaignStatusId',1);
    $query = $this->db->get('campaign');
    $campaign = $query->result();

    foreach ($campaign as $row) {
      $camid[] = $row->CampaignId;
    }

    if (!empty($camid)) {
      $this->db->select('*');
      $this->db->where_in('campaign.CampaignId',$camid);
      $this->db->order_by('campaign.CampaignTypeId','desc');
      $query = $this->db->get('campaign');
      $campaign = $query->result();
    }else {
      $campaign = [];
    }
    return $campaign;
  }

  function get_campaign_type($id){
    $this->db->select('*');
    $this->db->join('voucher_type', 'voucher_type.VoucherTypeId = campaign_voucher_type.VoucherTypeId');
    // $this->db->where('pos.StatusId', 1);
    $this->db->where('CampaignId',$id);
    $query = $this->db->get('campaign_voucher_type');
    return $query->result();
  }

  function get_storeId($StoreCode) {
    $this->db->select('StoreId');
    // $this->db->join('store', 'store.StoreId = pos.StoreId');
    // $this->db->where('pos.StatusId', 1);
    $this->db->where('StoreCode',$StoreCode);
    $query = $this->db->get('store');
    return $query->row();
  }

  function get_value($voucher){
    $this->db->select('VoucherValue');
    $this->db->where('StatusId', 1);
    $this->db->where('VoucherTypeId', $voucher);
    $query = $this->db->get('voucher_type');
    return $query->row();
  }

  //reprint voucher get pos based on logged user - naz
  function get_posReprint($StoreId) {
    $this->db->join('store', 'store.StoreId = pos.StoreId');
    $this->db->where('pos.StatusId', 1);
    $this->db->where('store.StoreId',$StoreId);
    $query = $this->db->get('pos');
    return $query->result();
  }

  function count_receiptNumber($receiptnumber){
    $this->db->where('voucher_issuance.ReceiptNumber', $receiptnumber);
    $query = $this->db->get('voucher_issuance');
    return $query->num_rows();
  }

  function count_storeIssuance($store, $pos, $receiptnumber){
    $this->db->join('store', 'store.StoreId = voucher_issuance.IssuanceStoreId');
    $this->db->join('pos', 'pos.POSId = voucher_issuance.IssuancePOSId');

    $this->db->where('store.StoreCode', $store);
    $this->db->where('pos.POSNumber', $pos);

    $this->db->where('voucher_issuance.ReceiptNumber', $receiptnumber);
    $query = $this->db->get('voucher_issuance');
    return $query->num_rows();
  }

  //get success reprint
  function get_reprintSuccess($reprintid){
    $this->db->join('vouchers', 'vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId');
    $this->db->join('voucher_type', 'voucher_type.VoucherTypeId = vouchers.VoucherTypeId');
    $this->db->join('app_logger', 'app_logger.AppLoggerId = vouchers.AppLoggerId');
    $this->db->where('voucher_issuance.VoucherIssuanceId', $reprintid);
    // $this->db->group_by("vouchers.VoucherTypeId");
    $this->db->order_by('vouchers.VoucherTypeId', 'asc');
    $this->db->order_by('vouchers.VouchersValue', 'desc');
    $query = $this->db->get('voucher_issuance');
    return $query->result();
  }

  //admin select store
  function get_store(){
    $array = [0,41];
    $this->db->where_not_in('StoreId', $array);
    $this->db->where('StoreStatusId', 1);
    $this->db->order_by('StoreCode', 'asc');
    $query = $this->db->get('store');
    return $query;
  }

  //admin can selct pos based on store selected
  function get_posadmin($StoreId){
    $this->db->where('pos.StatusId', 1);
    $this->db->where('pos.StoreId', $StoreId);
    $this->db->order_by('POSNumber', 'asc');
    $query = $this->db->get('pos');
    return $query;
  }

  function get_issuancepos($StoreId){
    if (!empty($StoreId)) {
      $this->db->where('pos.StatusId', 1);
      $this->db->where('pos.StoreId', $StoreId);
    }
    $this->db->order_by('POSNumber', 'asc');
    $query = $this->db->get('pos');
    return $query->result();
  }

  function get_issuanceredeem(){
    $this->db->where('RedeemTypeId',2);
    $query = $this->db->get('redeem_type');
    return $query;
  }

  function get_campaignValidation()
  {
    // $this->db->where('CampaignValidationCheck', '');
    $this->db->where('StatusId', 1);
    $query = $this->db->get('general_settings');
    return $query->row();
  }

  function get_vouchertype() {
    $this->db->where('VoucherTypeId !=', 1);
    $this->db->where('voucher_type.StatusId', 1);
    $this->db->order_by('VoucherName', 'asc');
    $query = $this->db->get('voucher_type');
    return $query;
  }

  function get_vouchervalue(){
    $query = $this->db->get('voucher_type');
    return $query;
  }

  function get_voucher_type($type) {
    $this->db->where('VoucherTypeId', $type);
    $query = $this->db->get('voucher_type');
    return $query->row();
  }

  function get_campaign($StoreId){
    $this->db->join('campaign_store', 'campaign_store.CampaignId = campaign.CampaignId');
    $this->db->where('campaign.CampaignStatusId', 1);
    $this->db->where('campaign_store.StoreId', $StoreId);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  //reprint voucher details
  function get_reprintdetails($userid) {
    // $this->db->join('voucher_issuance', 'voucher_issuance.IssuanceLoginId = user.UserId');
    $this->db->join('user_store', 'user_store.UserId = user.UserId');
    $this->db->join('store', 'store.StoreId = user_store.StoreId');
    $this->db->join('pos', 'pos.StoreId = store.StoreId');
    $this->db->where('user.UserId', $userid);
    $query = $this->db->get('user');
    return $query->row();
  }

  // receipt checking for reprint voucher
  function get_receiptchecking($checkreceipt){
    $this->db->join('vouchers', 'vouchers.VoucherIssuanceId = voucher_issuance.VoucherIssuanceId');
    $this->db->join('app_logger', 'app_logger.AppLoggerId = voucher_issuance.AppLoggerId');
    $this->db->where('voucher_issuance.ReceiptNumber', $checkreceipt);
    $query = $this->db->get('voucher_issuance');
    return $query->row();
  }

  function getreprintVoucher($id){
    $this->db->where('VoucherIssuanceId', $id);
    $query = $this->db->get('vouchers');
    return $query->result();
  }

  function get_status($id){
    $this->db->select('VoucherStatusId');
    $this->db->where('VoucherIssuanceId', $id);
    $query = $this->db->get('vouchers');
    return $query->row();
  }

  //insert reprint voucher
  function reprintVoucher($get,$rn)
  {

    $data = array(
                    'VoucherIssuanceId'    => $get['VoucherIssuanceId'],
                    'AppLoggerId'          => $get['AppLoggerId'],
                    'ReceiptNumber'        => $rn,
    );

      $this->db->insert('reprint_voucher',$data);
      return $this->db->insert_id();
  }

  function get_role(){
    $query = $this->db->get('user_role');
    return $query->row();

  }
  //get mannual issuance
  function get_issuancestore($UserId){
    $this->db->join('user_store', 'user_store.StoreId = store.StoreId');
    $this->db->join('user', 'user.UserId = user_store.UserId');
    $this->db->join('user_role', 'user_role.UserRoleId = user_store.UserRoleId');
    // $this->db->join('campaign_store', 'campaign_store.StoreId = store.StoreId');
    // $this->db->join('campaign', 'store.StoreId = campaign_store.StoreId');
    $this->db->where('user.UserId', $UserId);
    // $this->db->where('user_role.UserRoleId', 114);
    $query = $this->db->get('store');
    return $query->row();
  }

  function insertmannualIssuance($get){

      $data = array(
                      'IssuanceStoreId'    => $get['store'],
                      'IssuancePOSId'      => $get['pos'],
                      'ReceiptDateTime'    => $get['datetime'],
                      'ReceiptNumber'      => $get['receiptNumber'],
                      'Cosmeticsales'      => $get['cosmeticsale'],
                      'TotalSales'         => $get['totalsales'],
                      'IssuanceTypeId'     => $get['vouchertype'],
                      'AppLoggerId'        => $get['AppLoggerId'],
                      'Source'             => 'System',
      );

    $this->db->insert('voucher_issuance',$data);
    return $this->db->insert_id();
  }

  function insertCampaignIssuance($get,$inssuance){
    if (isset($get['campaign']) != '') {

      $data = array(
                      'VoucherIssuanceId'   => $inssuance,
                      'CampaignId'          => $get['campaign'],
                      'VoucherTypeId'       => $get['vouchertype'],
                      'VouchersValue'       => $get['vouchervalue'],
                      'AppLoggerId'         => $get['AppLoggerId'],

      );
    }elseif (isset($get['redeemtype']) != '') {
      $data = array(
                      'VoucherIssuanceId' => $inssuance,
                      'RedeemTypeId'  => $get['redeemtype'],
                      'VoucherTypeId' => $get['vouchertype'],
                      'VouchersValue' => $get['vouchervalue'],
                      'AppLoggerId'   => $get['AppLoggerId'],
      );

    }
      $this->db->insert('vouchers',$data);
      return $this->db->insert_id();
  }

  function generate_voucher($terminal,$payload){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->config->item('api_path').'manual',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_HTTPHEADER => array(
        'API-KEY: '.$this->config->item('api_key'),
        'TERMINAL-ID: '.$terminal,
        'Authorization: Basic '.$this->config->item('api_auth'),
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
  }

  function get_reprintValidation()
  {
    $this->db->where('StatusId', 1);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  //reprint allow in from voucher settings
  function get_reprintAllowVoucherSetting($id){
    $this->db->join('user_store', 'user_store.StoreId = store_voucher_settings.StoreId');
    $this->db->where('user_store.UserId', $id);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  function get_reprintNumCheck($id){
    $this->db->select('ReceiptNumber');
    $this->db->where('ReceiptNumber', $id);
    $query = $this->db->get('reprint_voucher');
    return $query->num_rows();
  }

  function reprintSetting($id){
    $this->db->join('store', 'store.StoreId = store_voucher_settings.StoreId');
    $this->db->select('NumReprint');
    $this->db->where('store.StoreId', $id);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }
  //reprint allow in from voucher settings -end

  function issuanceValidation($id){//check storeid where
    // $this->db->select('user_store.StoreId');
    $this->db->join('user_store', 'user_store.StoreId = store_voucher_settings.StoreId');
    $this->db->where('user_store.UserId', $id);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  function get_permission($id){
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");
    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_storeAdmin(){
    $this->db->where('StoreId !=', 0);
    $this->db->where('StoreStatusId', 1);
    $this->db->order_by('StoreCode', 'asc');
    $query = $this->db->get('store');
    return $query->result();
  }

  function get_user(){
    $query = $this->db->get('user_store');
    return $query->result();
  }

  function get_user_reprint(){
  $query = $this->db->get('user_store');
  return $query->row();
  }

  function campaignValidation(){
    $query = $this->db->get('general_settings');
    return $query->row();
  }

    function campaign_redeemtype($id, $cid){
      // $array = [1,3];
      $query = $this->db->join("store","store.StoreId = campaign_store.StoreId");
      $query = $this->db->join("campaign","campaign.CampaignId = campaign_store.CampaignId");
      $this->db->where('store.StoreCode', $id);
      $this->db->where('campaign.CampaignId', $cid);
      // $this->db->where_in('campaign_store.CampaignStatusId', $);
      $query = $this->db->get('campaign_store');
      return $query->row();
    }

}
?>
