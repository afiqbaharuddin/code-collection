<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//card in campaign

class Cardlist_model extends CI_Model

{
  var $table         = 'card_campaign'; //DATABASE TABLE NAME
  var $column_order  = array('card.CardName','StartDate','EndDate','campaign_status.Status',null); //FIELD IN TABLE
  var $column_search = array('card.CardName','StartDate','EndDate','campaign_status.Status'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('StartDate' => 'desc'); // DATA ORDERING

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
    $this->db->join("campaign_status","campaign_status.CampaignStatusId = ".$this->table.".CampaignStatusId");
    $this->db->join("card", "card.CardId = ".$this->table.".CardId");
    $this->db->where('card_campaign.CampaignId', $id);
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
  //store list table**

  function get_campaign_card($campaignid){
    $this->db->where('CampaignId', $campaignid);
    $query = $this->db->get('card_campaign');
    return $query->result();
  }

  function get_campaign_card_permission($campaignid){
    $this->db->where('CampaignId', $campaignid);
    $query = $this->db->get('card_campaign');
    return $query->result();
  }

  //insert if not exist card (edit campaign)
  function get_addcard($arraycard) {
      if(!empty($arraycard)){
        $this->db->where_not_in('CardId', $arraycard);
      }

    $this->db->order_by('card.CardName', 'asc');
    $query = $this->db->get('card');
    return $query;
  }

    function get_card($cardid,$campaignid) {
      $this->db->join("card","card.CardId = card_campaign.CardId");
      $this->db->where('card_campaign.CardId',$cardid);
      $this->db->where('card_campaign.CampaignId',$campaignid);
      $query = $this->db->get('card_campaign');
      return $query->row();
    }

    function get_cardstatus() {
      $array =['3','4', '5'];
      $this->db->where_not_in('CampaignStatusId', $array);
      $query = $this->db->get('campaign_status');
      return $query;
    }

    function get_edit_cardstatus() {
      $array =['1','4', '5'];
      $this->db->where_not_in('CampaignStatusId', $array);
      $query = $this->db->get('campaign_status');
      return $query;
    }

    //edit card for campaign (edit campaign)
    function editCardCampaign($get, $id) {
      $data = array (
                      'CampaignStatusId'  => $get['editCardStatus'],
                      'ExtendDate'        => $get['extendCardDate'],
                      'InactiveDate'      => $get['inactiveCardDate'],

      );

      $this->db->where('CardId', $id);
      return $this->db->update('card_campaign', $data);
    }

    //extend date campaigncard datatable
    function get_extendDate($id){
      $this->db->where("card_campaign.CardCampaignId", $id);
      $query = $this->db->get('card_campaign');
      return $query->row();
    }
}

 ?>
