<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class settingStore_Model extends CI_Model
{
  //Store Voucher Setting table**
  var $table         = 'store'; //DATABASE TABLE NAME
	var $column_order  = array('store.StoreName', null,null); //FIELD IN TABLE
	var $column_search = array('store.StoreName'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('StoreName' => 'desc'); // DATA ORDERING

  function __construct()
  {
    parent::__construct();
  }

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
  function _extra_datatables($post){

    $this->db->select("*");
    $this->db->join("app_logger","app_logger.AppLoggerId = store.AppLoggerId");
    $this->db->where('StoreId !=', 0);
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
  //Store Voucher Setting table**

//voucher settings
  function getAllStore(){
    $query = $this->db->get('store');
    return $query->result();
  }

  function changeStatus($status)
  {
    $this->db->update('store_voucher_settings', $status);
  }

  function storesetting_all($insert){
     $result = $this->db->insert('store_voucher_settings', $insert);
     return $this->db->insert_id();
  }

  function storesetting_all_update($update){
    $this->db->where('StoreId',$update['StoreId']);
    return $this->db->update('store_voucher_settings', $update);
  }

  function changeStatus_ind($status,$storeid)
  {
    $this->db->where('StoreId',$storeid);
    $this->db->update('store_voucher_settings', $status);
  }

  function get_datastore(){
    $query = $this->db->get('store');
    return $query->num_rows();
  }

  function get_checkedReprint($StoreId){
    // $this->db->where('NumReprintCheck',1);
    $this->db->where('StatusId',1);
    $this->db->where('StoreId', $StoreId);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  function get_checkedIssuance($StoreId){
    $this->db->where('StatusId',1);
    $this->db->where('StoreId', $StoreId);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  function check_store_setting($StoreId){
    $this->db->where('StoreId', $StoreId);
    $query = $this->db->get('store_voucher_settings');
    return $query->row();
  }

  function get_permission($id){
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");
    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_userStore($id){
    $this->db->where("userId", $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

}
