<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign_Model extends CI_Model
//id,name,start,end,status,action
{
  var $table         = 'campaign'; //DATABASE TABLE NAME
  var $column_order  = array('CampaignId', 'CampaignName',null,'StartDate','EndDate','ExtendDate',null,'campaign_status.Status',null); //FIELD IN TABLE
  var $column_search = array('CampaignId', 'CampaignName','StartDate','EndDate','ExtendDate','campaign_status.Status'); //FIELD FOR SEARCHING PURPOSES
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
        if($item == 'StartDate' || $item == 'EndDate') {
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
  function _extra_datatables($post){
    $this->db->select("*");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");
    $this->db->join("campaign_status","campaign_status.CampaignStatusId = ".$this->table.".CampaignStatusId");
    $this->db->order_by('CreatedDate', 'desc');
  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  function get_datatables($post)
  {
    $this->db->from($this->table);
    if($post['length'] != -1)
    $this->_extra_datatables($post);
    $this->db->limit($post['length'], $post['start']);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($post)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($post)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post);
    return $this->db->count_all_results();
  }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User list table**

  function get_status()
  {
    $this->db->select('CampaignStatusId');
    $query = $this->db->get('campaign');
    return $query->result();
  }

  //card campaign expired counting days
  function get_cardCampaign($expireddate,$campaign_status)
  {
    // $array=[1,3];
    // $this->db->where_in('CampaignStatusId',$array);

    $this->db->where('CampaignStatusId',1);
    $this->db->where('DATE(EndDate) >=',$expireddate);

    $this->db->or_where('CampaignStatusId',3);
    $this->db->where('DATE(ExtendDate) >=',$expireddate);

    // if($campaign_status == 1){
    //   // $this->db->where('CampaignStatusId', 1);
    //   $this->db->where('EndDate <=',$expireddate);
    // }
    // if ($campaign_status == 3){
    //   // $this->db->where('CampaignStatusId', 3);
    //   $this->db->where('ExtendDate <=',$expireddate);
    // }
    $this->db->limit(8);
    $query = $this->db->get('campaign');
    return $query->result();
  }

  function get_vouchertypename($campaignid)
  {
    $this->db->where('CampaignId', $campaignid);
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = campaign_voucher_type.VoucherTypeId");
    $query = $this->db->get('campaign_voucher_type');
    return $query->result();
  }

  function get_dateCampaign(){
    $this->db->where('StatusId',1);
    $query = $this->db->get('general_settings');
    return $query->row();

  }

  function get_camptype() {
    $query = $this->db->get('campaign_type');
    return $query;
  }

  //dropdown voucher type on create campaign
  function get_vouchertype() {
    $this->db->where('VoucherTypeId !=',1);
    $query = $this->db->get('voucher_type');
    return $query;
  }
  //create campaign
  function get_store()
  {
    $this->db->where('StoreId !=',0);
    $this->db->where('StoreStatusId ',1);
    $query = $this->db->get('store');
    return $query;
  }
  //create campaign
  function get_redeemtype()
  {
    $query = $this->db->get('redeem_type');
    return $query;
  }
  //create campaign
  function get_card()
  {
    $query = $this->db->get('card');
    return $query;
  }

  function insertCampaign($get)
  {
    $data = array(
                  'CampaignTypeId'      => $get['camptype'],
                  'CampaignName'        => $get['campaignname'],
                  // 'VoucherTypeId' => $get['vouchertype[]'],
                  'StartDate'           => $get['startDate'],
                  'EndDate'             => $get['endDate'],
                  'RedeemTypeId'        => $get['redeemtype'],
                  'Remark'              => $get['remark'],
                  'CampaignStatusId'    => 1,
                  'AppLoggerId'         => $get['loggerid'],
      );
      $result = $this->db->insert('campaign',$data);
      return $this->db->insert_id();
    }

      function vouchertypeCampaign($vouchertype,$campaignid,$get)
      {
        $data = array(
                      'VoucherTypeId' => $vouchertype,
                      'CampaignId'    => $campaignid,

          );
          $result = $this->db->insert('campaign_voucher_type',$data);
          return $this->db->insert_id();
      }

      function storeCampaign($storeid,$campaignid,$get)
      {
        $data = array(
                      'StoreId'          => $storeid,
                      'CampaignId'       => $campaignid,
                      'CampaignStatusId' => 1,
                      'StartDate'        => $get['startDate'],
                      'EndDate'           => $get['endDate'],
          );
          $result = $this->db->insert('campaign_store',$data);
          return $this->db->insert_id();
      }

      function cardCampaign($cardid,$campaignid,$get)
      {
        $data = array(
                      'CardId'            => $cardid,
                      'CampaignId'        => $campaignid,
                      'CampaignStatusId'  => 1,
                      'StartDate'         => $get['startDate'],
                      'EndDate'           => $get['endDate'],
          );
          $result = $this->db->insert('card_campaign',$data);
          return $this->db->insert_id();
      }

      function storeCampaign_check($StoreId)
      {
        $this->db->join("store","store.StoreId = campaign_store.StoreId");
        $this->db->join("campaign","campaign.CampaignId = campaign_store.CampaignId");
        $this->db->where('campaign_store.StoreId',$StoreId);
        $this->db->where('campaign.CampaignTypeId',3);
        $this->db->where('campaign.CampaignStatusId',1);
        $query = $this->db->get('campaign_store');
        return $query->row();
      }

      function get_campname($id) {
        $this->db->where('campaign.CampaignId',$id);
        $query = $this->db->get('campaign');
        return $query->row()->CampaignName;
      }
    }
 ?>
