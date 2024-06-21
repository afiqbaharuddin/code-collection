<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListUser_model extends CI_Model
{
  //User list table**
  var $table         = 'user_store'; //DATABASE TABLE NAME
	var $column_order  = array('user.StaffId', 'user.Fullname','user_role.Role','status.StatusName',null); //FIELD IN TABLE
	var $column_search = array('user.StaffId', 'user.Fullname','user_role.Role','status.StatusName'); //FIELD FOR SEARCHING PURPOSES
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

    // $this->db->where('user.StatusId !=',4);
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**

  //CHANGE ONLY ON THIS PART**
  function _extra_datatables($post, $id){

    $this->db->select("*");
    $this->db->select("user_store.StatusId as userStatusStore");
    $this->db->join("user","user.UserId = ".$this->table.".UserId");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");
    $this->db->join("user_role","user_role.UserRoleId = ".$this->table.".UserRoleId");
    $this->db->join("status","status.StatusId = ".$this->table.".StatusId");
    $this->db->join("store","store.StoreId = ".$this->table.".StoreId");
    $this->db->where('user_store.StoreId', $id);

  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
	function get_datatables($post,$id)
	{
    $this->db->from($this->table);
		if($post['length'] != -1)
    $this->_extra_datatables($post, $id);
		$this->db->limit($post['length'], $post['start']);
		$this->_get_datatables_query($post);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($post, $id)
	{
    $this->db->from($this->table);
    $this->_extra_datatables($post, $id);
		$this->_get_datatables_query($post);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($post, $id)
	{
		$this->db->from($this->table);
    $this->_extra_datatables($post, $id);
		return $this->db->count_all_results();
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User list table**

  function get_storedetails($storeid) {
    $this->db->where('StoreId',$storeid);
    $query = $this->db->get('store');
    return $query->row();
  }

  function get_userid($userstoreId) {
    $this->db->select('UserId');
    $this->db->where('UserStoreId', $userstoreId);
    $query = $this->db->get('user_store');
    return $query->row()->UserId;
  }

  function update_userStatus($status, $userstoreId) {

    $this->db->set('StatusId', $status);
    $this->db->where('UserStoreId', $userstoreId);
    return $this->db->update('user_store');;
  }

  function update_statusUser($status,$userstoreId,$update) {

    $data = array(
                    'StatusId' => $status,
                  );

    $this->db->where('UserId', $update);
    return $this->db->update('user', $data);
  }

  function get_username($id) {
    $this->db->join("user","user.UserId = user_store.UserId");
    $this->db->where('user_store.UserStoreId',$id);
    $query = $this->db->get('user_store');
    return $query->row()->Fullname;
  }

  function currentstatus($id) {
    $this->db->where('status.StatusId',$id);
    $query = $this->db->get('status');
    return $query->row()->StatusName;
  }
}
