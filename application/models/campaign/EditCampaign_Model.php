<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EditCampaign_Model extends CI_Model
{
  //datatable start
  var $table         = 'campaign_store'; //DATABASE TABLE NAME
  var $column_order  = array('store.StoreCode','store.StoreName','StartDate','EndDate','campaign_status.Status',null); //FIELD IN TABLE
  var $column_search = array('store.StoreCode','store.StoreName','StartDate','EndDate','campaign_status.Status'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('app_logger.CreatedDate' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
    {
     if($post['search']['value']) // if datatable send POST for search
			{
        if ($item == 'StartDate' || $item == 'EndDate') {
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
  function _extra_datatables($post,$id){
    $this->db->select("*");
    $this->db->join("store","store.StoreId = campaign_store.StoreId");
    $this->db->join("campaign_status","campaign_status.CampaignStatusId = ".$this->table.".CampaignStatusId");
    $this->db->where('campaign_store.CampaignId',$id );
  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  function get_datatables($post,$id)
  {
    $this->db->from($this->table);
    if($post['length'] != -1)
    $this->_extra_datatables($post,$id);
    $this->db->limit($post['length'], $post['start']);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($post,$id)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$id);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($post,$id)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$id);
    return $this->db->count_all_results();
  }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //datatable end

  function get_editstatus() {
    $this->db->where('CampaignStatusId !=', 4);
    $query = $this->db->get('campaign_status');
    return $query;
  }

  function terminate_status($id) {
    $this->db->where('CampaignId', $id);
    $query = $this->db->get('campaign');
    return $query->row();
  }
  // insert if not exist
  function get_campaign_storecode($arraystore) {

    if (!empty($arraystore)) {
      $this->db->where_not_in('StoreId', $arraystore);
    }
    $array = [0,41];

    $this->db->where('StoreStatusId',1);
    $this->db->where_not_in('StoreId', $array);
    $this->db->order_by('StoreCode', 'asc');
    $query = $this->db->get('store');
    return $query;
  }

  function get_campaign_store($campaignid) {
    $this->db->where('CampaignId', $campaignid);
    $query = $this->db->get('campaign_store');
    return $query->result();
  }

  function get_campaignstatus() {
    $array = [3,4,5];
    $this->db->where_not_in('CampaignStatusId', $array);;
    $query = $this->db->get('campaign_status');
    return $query;
  }

  function get_storestatus() {
    $array = [2,3];

    $this->db->where_in('CampaignStatusId ', $array);
    $query = $this->db->get('campaign_status');
    return $query;
  }

  function get_campaign() {
    $query = $this->db->get('campaign');
    return $query;
  }

  function get_vouchertype(){
    $query = $this->db->get('voucher_type');
    return $query;
  }

  function get_campaignvouchertype($campaignid) {
    $this->db->where('CampaignId',$campaignid);
    $query = $this->db->get('campaign_voucher_type');
    return $query->result();
  }

  function get_store($storeid, $campaignid){
    $this->db->select("*");
    $this->db->join("store","store.StoreId = campaign_store.StoreId");
    $this->db->where('campaign_store.StoreId',$storeid);
    $this->db->where('campaign_store.CampaignId',$campaignid);

    $query = $this->db->get('campaign_store');
    return $query->row();
  }

  //create store in edit campaign
  function insertStoreCampaign($val,$get,$id){
    $data = array(
                    'StoreId'             => $val,
                    'CampaignId'          => $id,
                    'StartDate'           => $get['startDate'],
                    'EndDate'             => $get['endDate'],
                    'CampaignStatusId'    => $get['bs-validation-status'],
    );
    $add = $this->db->get('campaign_store');

    if ($add->num_rows() >= 0) {
      $this->db->insert('campaign_store',$data);
      return $this->db->insert_id();
    }
  }

  //edit campaign store (edit)
  function editCampaignStore($get, $id) {
      $data = array(
                    'CampaignStatusId'  => $get['editStoreStatus'],
                    'InactiveDate'      => $get['inactivestoredate'],
                    'ExtendDate'        => $get['editStoreExtendDate'],
      );

      $this->db->where('StoreId',$id);
      return $this->db->update('campaign_store',$data);
  }

  //add card campaign
  function insertCardCampaign($val,$get,$id) {
    $data = array (
                    'CardId'             => $val,
                    'CampaignId'         => $id,
                    'StartDate'          => $get['cardStart'],
                    'EndDate'            => $get['cardEnd'],
                    'CampaignStatusId'  => $get['cardStatus'],
                  );
    $this->db->insert('card_campaign', $data);
    return $this->db->insert_id();
  }

  //get edit campaign details
  function get_campaigndetails($campaignid)
  {
    $this->db->join('campaign_type', 'campaign_type.CampaignTypeId = campaign.CampaignTypeId');
    $this->db->join('redeem_type', 'redeem_type.RedeemTypeId = campaign.RedeemTypeId');
    $this->db->join('campaign_status', 'campaign_status.CampaignStatusId = campaign.CampaignStatusId');
    // $this->db->join('voucher_type', 'voucher_type.VoucherTypeId = campaign.VoucherTypeId');

    $this->db->where('CampaignId',$campaignid);
    $query = $this->db->get('campaign');
    return $query->row();
  }

  //get all extend date feom edit campaign to store
  function editCampaignExtendStore($get,$id){
    $data = array(
              'CampaignStatusId'   => 3,
              'ExtendDate'         =>$get['extenddate'],

    );
    $this->db->where('CampaignId',$id);
    return $this->db->update('campaign_store',$data);
  }
  //get all extend date feom edit campaign to card
  function editCampaignExtendCard($get,$id){
    $data = array(
              'CampaignStatusId'   => 3,
              'ExtendDate'         =>$get['extenddate'],
    );
    $this->db->where('CampaignId',$id);
    return $this->db->update('card_campaign',$data);
  }

  //get all terminate date feom edit campaign to store
  function editCampaignTerminateStore($get,$id){
    $data = array(
              'CampaignStatusId'   => 5,
              'ExtendDate'         =>$get['terminatedate'],

    );
    $this->db->where('CampaignId',$id);
    return $this->db->update('campaign_store',$data);
  }

  //get all terminate date feom edit campaign to card
  function editCampaignTerminateCard($get,$id){
    $data = array(
              'CampaignStatusId'   => 5,
              'ExtendDate'         =>$get['terminatedate'],
    );
    $this->db->where('CampaignId',$id);
    return $this->db->update('card_campaign',$data);
  }

  //edit campaign
  function editCampaign($get, $id)
  {
    if ($get['editcampaignstatus'] == 3) {
      $data = array(
                    'CampaignStatusId'         => $get['editcampaignstatus'],
                    'ExtendDate'               => $get['extenddate'],
                    'Remark'                   => $get['remark'],
      );
    } else if ($get['editcampaignstatus']== 2) {
      $data = array(
                    // 'CampaignStatusId'         => $get['editcampaignstatus'],
                    'InactiveDate'             => $get['inactiveDate'],
                    'Remark'                   => $get['remark'],
      );
    } else if ($get['editcampaignstatus'] == 5) {
        $data = array(
                      // 'CampaignStatusId'       => $get['editcampaignstatus'],
                      'TerminateDate'          => $get['terminatedate'],
                      'Remark'                 => $get['remark'],
        );
      } else {
        $data = array(
                      // 'CampaignStatusId'         => $get['editcampaignstatus'],
                      'Remark'                   => $get['remark'],
        );
    }
    $this->db->where('CampaignId',$id);
    return $this->db->update('campaign',$data);
  }


  //form edit campaign
  //permission to edit form, if expired button submit disabled
  function SubmitPermissionCampaign($id){
    $this->db->where('CampaignId', $id);
    $query = $this->db->get('campaign');
    return $query->row();
  }

  //edit store in edit Campaign
  function voucherPermissionCampaignStore($id){
    $this->db->select("CampaignStatusId");
    $this->db->where('CampaignStoreId', $id);
    $query = $this->db->get('campaign_store');
    return $query->row()->CampaignStatusId;
  }

  //edit cards in edit campaign
  function SubmitPermissionCampaignCard($id){
    $this->db->select("CampaignStatusId");
    $this->db->where_in('CardId', $id);
    $query = $this->db->get('card_campaign');
    return $query->row()->CampaignStatusId;
  }

  //extend date campaignstore datatable
  function get_extendDate($id){
    $this->db->where("campaign_store.CampaignStoreId", $id);
    $query = $this->db->get('campaign_store');
    return $query->row();
  }

  function get_campname($id) {
    $this->db->where('campaign.CampaignId',$id);
    $query = $this->db->get('campaign');
    return $query->row()->CampaignName;
  }

  function currentstatus($id) {
    $this->db->where('campaign_status.CampaignStatusId',$id);
    $query = $this->db->get('campaign_status');
    return $query->row()->Status;
  }

  function get_storecampname($id) {
    $this->db->join("campaign","campaign.CampaignId = campaign_store.CampaignId");

    $this->db->where('campaign_store.CampaignStoreId',$id);
    $query = $this->db->get('campaign_store');
    return $query->row()->CampaignName;
  }

  function get_campstore($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    return $query->row()->StoreName;
  }

  function get_cardname($id) {
    $this->db->join("card","card.CardId = card_campaign.CardId");

    $this->db->where('card_campaign.CardCampaignId',$id);
    $query = $this->db->get('card_campaign');
    return $query->row()->CardName;
  }

  function get_campcardname($id) {
    $this->db->join("campaign","campaign.CampaignId = card_campaign.CampaignId");

    $this->db->where('card_campaign.CardCampaignId',$id);
    $query = $this->db->get('card_campaign');
    return $query->row()->CampaignName;
  }

  function storename($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    return $query->row()->StoreName;
  }
}
 ?>
