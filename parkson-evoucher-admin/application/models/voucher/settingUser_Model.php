<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class settingUser_Model extends CI_Model
{
  //Store Voucher Setting table**
  var $table         = 'user'; //DATABASE TABLE NAME
	var $column_order  = array('user.Fullname', null,null,null,null); //FIELD IN TABLE
	var $column_search = array('user.Fullname'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('app_logger.UserId' => 'desc'); // DATA ORDERING

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
  function _extra_datatables($post,$userid, $permission){

    $this->db->select("*");
    // $this->db->select("store.StoreId as storeName");
    // $this->db->join("user","user.UserId = ".$this->table.".UserId");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");

    if ($userid->UserRoleId != 1 || $userid->StoreId != 0) {
      $this->db->join('user_store','user_store.UserId = user.UserId');
      $this->db->join('store','store.StoreId = user_store.StoreId');
      $this->db->where("store.StoreId", $userid->StoreId);
    }
    $array = [0,2];
    $this->db->where_not_in('user.UserId', $array);

  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
	function get_datatables($post,$userid, $permission)
	{
    $this->db->from($this->table);
		if($post['length'] != -1)
    $this->_extra_datatables($post,$userid, $permission);
		$this->db->limit($post['length'], $post['start']);
		$this->_get_datatables_query($post);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($post,$userid, $permission)
	{
    $this->db->from($this->table);
    $this->_extra_datatables($post,$userid, $permission);
		$this->_get_datatables_query($post);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($post,$userid, $permission)
	{
		$this->db->from($this->table);
    $this->_extra_datatables($post,$userid, $permission);
		return $this->db->count_all_results();
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //Store Voucher Setting table**

  function changeStatus($status){
    $this->db->update('user_voucher_settings', $status);
  }

  function getAllUser(){
    $query = $this->db->get("user");
    return $query->result();
  }

  function usersetting_all($insert){
    $result = $this->db->insert('user_voucher_settings',$insert);
    return $this->db->insert_id();
  }

  function usersetting_all_update($update){
    $this->db->where('UserId',$update['UserId']);
    return $this->db->update('user_voucher_settings',$update);
  }

  function changeStatus_ind($status,$userid){
    $this->db->where('UserId',$userid);
    $this->db->update('user_voucher_settings', $status);
  }

  function get_checkedExtend($UserId){
    $this->db->where('UserId',$UserId);
    $this->db->where('StatusId',1);
    $query = $this->db->get('user_voucher_settings');
    return $query->row();
  }

  function check_user_setting($UserId){
    $this->db->where('UserId', $UserId);
    $query = $this->db->get('user_voucher_settings');
    return $query->row();
  }
}
