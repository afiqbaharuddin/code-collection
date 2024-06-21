<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class predefined_Model extends CI_Model
{
  var $table         = 'voucher_type'; //DATABASE TABLE NAME
	var $column_order  = array('VoucherShortName', 'VoucherName','VoucherValue','ActivateDate','InactivateDate','status.StatusName',null); //FIELD IN TABLE
	var $column_search = array('VoucherShortName', 'VoucherName','VoucherValue','ActivateDate','InactivateDate','status.StatusName'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('app_logger.ActivityDate' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
    {
      if($post['search']['value']) // if datatable send POST for search
      {
        if($item == 'ActivateDate' || $item == 'InactivateDate' ) {
        $date = str_replace('/', '-', $post['search']['value']);
          if($i===0) // first loop
          {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $post['search']['value']);
          }
          else
          {
            $this->db->or_like($item,date('Y-m-d', strtotime($date)));
            $this->db->or_like($item,date('H:i:s', strtotime($date)));	          }
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
    $this->db->join("status","status.StatusId = ".$this->table.".StatusId");
    $this->db->where('VoucherTypeId !=', 1);
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

  public function get_value($id)
  {
    $this->db->select("*");
    $this->db->where("VoucherTypeId",$id);
    $query = $this->db->get("voucher_type_value");
    return $query->result();
  }

  function get_issuance() {
    $query = $this->db->get('issuance_type');
    return $query;
  }

  function get_storecredit() {
    $query = $this->db->get('store_credit_type');
    return $query;
  }

  //dropdown voucher status at create voucher
  function get_voucherstatus_create() {
    $array = [3,4,5];
    $this->db->where_not_in("StatusId",$array);
    $query = $this->db->get('status');
    return $query;
  }

  //dropdown voucher status at edit voucher
  function get_voucherstatus_edit() {
    $array = [3,4,5];
    $this->db->where_not_in("StatusId",$array);
    $query = $this->db->get('status');
    return $query;
  }

  function get_storedetails($storeid) {

    $this->db->where('StoreId',$storeid);

    $query = $this->db->get('store');
		return $query->row();
  }

  function updateStatus($status, $vouchertypeId, $vouchershortname) {
    if ($status == 1) {
      $this->db->set('StatusId', 2);
      $this->db->where('VoucherShortName', $vouchershortname);
      $this->db->update('voucher_type');
    }
    $this->db->set('StatusId', $status);
    $this->db->where('VoucherTypeId', $vouchertypeId);
    return $this->db->update('voucher_type');
  }

  function get_serialize(){
    $query = $this->db->get('voucher_serialize');
		return $query;
  }

  function get_position(){
    $query = $this->db->get('voucher_type_position');
		return $query;
  }

  function currentstatus($id) {
    $this->db->where('status.StatusId',$id);
    $query = $this->db->get('status');
    return $query->row()->StatusName;
  }

  function get_type($id) {
    $this->db->where('voucher_type.VoucherTypeId',$id);
    $query = $this->db->get('voucher_type');
    return $query->row()->VoucherName;
  }
}
?>
