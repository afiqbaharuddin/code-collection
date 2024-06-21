<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EditStore_Model extends CI_Model
{
  // //POS List Table**
  var $postable         = 'pos'; //DATABASE TABLE NAME
  var $poscolumn_order  = array('POSNumber',null); //FIELD IN TABLE
  var $poscolumn_search = array('POSNumber'); //FIELD FOR SEARCHING PURPOSES
  var $posorder         = array('POSNumber' => 'asc'); // DATA ORDERING


  // //START AJAX DATATABLES
  // //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_pos_datatables_query($post)
	{
		$i = 0;
		foreach ($this->poscolumn_search as $item) // loop column
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

				if(count($this->poscolumn_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($post['order'])) // here order processing
		{
			$this->db->order_by($this->poscolumn_order[$post['order']['0']['column']], $post['order']['0']['dir']);
		}
		else if(isset($this->posorder))
		{
			$order = $this->posorder;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
  // //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //
  // //CHANGE ONLY ON THIS PART**
  function _extra_pos_datatables($post,$id){

    $this->db->select("*");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->postable.".AppLoggerId");
    $this->db->join("status","status.StatusId = ".$this->postable.".StatusId");
    $this->db->join("store","store.StoreId = ".$this->postable.".StoreId");
    $this->db->where('pos.StoreId', $id);
    $this->db->where('pos.StatusId', 1);
    $this->db->order_by('posNumber', 'asc');
  }
  //CHANGE ONLY ON THIS PART**

  // //NO NEED TO CHANGE. JUST COPY AND PASTE**
	function get_pos_datatables($post,$id)
	{
    $this->db->order_by('POSNumber', 'asc');
    $this->db->from($this->postable);
		if($post['length'] != -1)
    $this->_get_pos_datatables_query($post);
    $this->_extra_pos_datatables($post,$id);
		$this->db->limit($post['length'], $post['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($post,$id)
	{
    $this->db->from($this->postable);
    $this->_get_pos_datatables_query($post);
    $this->_extra_pos_datatables($post,$id);

		$query = $this->db->get();
		return $query->num_rows();
	}

  public function count_all($post,$id)
	{
		$this->db->from($this->postable);
    $this->_get_pos_datatables_query($post);
    $this->_extra_pos_datatables($post,$id);
		return $this->db->count_all_results();
	}
   //NO NEED TO CHANGE. JUST COPY AND PASTE**
   //END AJAX DATATABLES
   // POS List Table**

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Manager List Table**
  var $managertable         = 'managerduty'; //DATABASE TABLE NAME
  var $managercolumn_order  = array('user.Fullname','user.StaffId','user_role.Role','user_role.Role','StartDate','EndDate','status.StatusName',null); //FIELD IN TABLE
  var $managercolumn_search = array('user.Fullname','user.StaffId','user_role.Role','user_role.Role','StartDate','EndDate','status.StatusName'); //FIELD FOR SEARCHING PURPOSES
  var $managerorder         = array('EndDate' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_manager_datatables_query($post)
	{
		$i = 0;
		foreach ($this->managercolumn_search as $item) // loop column
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

         if(count($this->managercolumn_search) - 1 == $i) //last loop
           $this->db->group_end(); //close bracket
       }
       $i++;
		}

		if(isset($post['order'])) // here order processing
		{
			$this->db->order_by($this->managercolumn_order[$post['order']['0']['column']], $post['order']['0']['dir']);
		}
		else if(isset($this->managerorder))
		{
			$order = $this->managerorder;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**

  //CHANGE ONLY ON THIS PART**
  function _extra_manager_datatables($post,$id){

    $this->db->select("*, user_role.UserRoleId as currentRole");

    $this->db->join("app_logger","app_logger.AppLoggerId = managerduty.AppLoggerId");
    $this->db->join("user","user.UserId = managerduty.UserId");
    $this->db->join("user_role","user_role.UserRoleId = managerduty.TempRole");
    $this->db->join("status","status.StatusId = managerduty.StatusId");
    $this->db->where('managerduty.StoreId',$id );
    // $this->db->where('managerduty.StatusId !=',3);

  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
	function get_manager_datatables($post,$id)
	{
    $this->db->from($this->managertable);
		if($post['length'] != -1)
    $this->_extra_manager_datatables($post,$id);
		$this->db->limit($post['length'], $post['start']);
		$this->_get_manager_datatables_query($post);
		$query = $this->db->get();
		return $query->result();
	}

	function count_manager_filtered($post,$id)
	{
    $this->db->from($this->managertable);
    $this->_extra_manager_datatables($post,$id);
		$this->_get_manager_datatables_query($post);
		$query = $this->db->get();
		return $query->num_rows();
	}

  public function count_manager_all($post,$id)
	{
		$this->db->from($this->managertable);
    $this->_extra_manager_datatables($post,$id);
		return $this->db->count_all_results();
	}
  // //NO NEED TO CHANGE. JUST COPY AND PASTE**
  // //END AJAX DATATABLES
  // // Manager List Table**

  function current_position($role){
    $this->db->select('*');
    $this->db->join('user_role', 'user_role.UserRoleId = managerduty.UserRoleId');
    // $this->db->where('user.UserId',$role);
    // $query = $this->db->get('user');
    // return $query->row();
    $this->db->where('managerduty.UserId',$role);
    $query = $this->db->get('managerduty');
    return $query->row();
  }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_addcard() {
    $this->db->order_by('CardName','asc');
    $this->db->where('StatusId', 1);
    $query = $this->db->get('card');
    return $query;
  }

  function get_storestatus_edit() {
    // $this->db->where('StatusId !=', 3);
    $query = $this->db->get('store_status');
    return $query;
  }

  //edit Store
  function editStorename($get,$id) {
    if ($get['editstorestatus'] == 2) {
      $data = array(
                    'StoreName'         => $get['editstorename'],
                    // 'StoreStatusId'     => 2,
                    'StoreInactiveDate' => $get['storecloseddate'],
      );
    }else {
      $data = array(
                    'StoreName'         => $get['editstorename'],
                    'StoreStatusId'     => 1,

      );
    }

    $this->db->where('StoreId',$id);
    return $this->db->update('store',$data);
  }

  function get_storedetails($storeid) {
    $this->db->where('StoreId',$storeid);
    $query = $this->db->get('store');
		return $query->row();
  }

  function update_modStatus($status, $managerdutyId) {

    $this->db->set('StatusId', $status);
    $this->db->where('ManagerDutyId', $managerdutyId);
    return $this->db->update('managerduty');
  }

  function get_cardstore($storeid) {
    $this->db->join("card","card.CardId = card_store.CardId");
    $this->db->where('StoreId',$storeid);
    $this->db->where('card_store.StatusId !=',3);
    $query = $this->db->get('card_store');
		return $query->result();
  }

    function get_removedcard($storeid) {
    $this->db->join("card","card.CardId = card_store.CardId");
    $this->db->where('StoreId',$storeid);
    $this->db->where('card_store.StatusId',3);
    $query = $this->db->get('card_store');
    return $query->result();
  }

  function remove_card($get) {

    $data = array(
      'StatusId' => 3,
      'EndDate'  => date('Y-m-d'),
    );
    $this->db->where('CardStoreId',$get['cardstore']);
    // $this->db->where('CardId',$get['cardid']);

    return $this->db->update('card_store',$data);
  }

  function addCard($cardid,$id,$cardstart,$cardend,$status) {

    $data = array(
                  'CardId'      => $cardid,
                  'StoreId'     => $id,
                  'StartDate'   => $cardstart,
                  'EndDate'     => $cardend,
                  'StatusId'    => $status,
                );

    $this->db->insert('card_store',$data);
    return $this->db->insert_id();
  }

  function editCard_startdate($cardid,$id,$cardstart,$cardend,$status) {

    $data = array(
      'StartDate'   => $cardstart,
      'EndDate'     => $cardend,
      'StatusId'    => $status,
    );

    $this->db->where('CardStoreId',$cardid);
    // $this->db->where('StoreId',$id);
    return $this->db->update('card_store',$data);
  }

  function editCard_nostartdate($cardid,$id,$cardend) {

    $data = array(
      'EndDate'     => $cardend,
     );

    $this->db->where('CardStoreId',$cardid);
    // $this->db->where('StoreId',$id);
    return $this->db->update('card_store',$data);
  }

  function check_card($storeid,$cardid) {
    $this->db->where('StoreId',$storeid);
    $this->db->where('CardId',$cardid);
    $this->db->where('StatusId',1);
    $query = $this->db->get('card_store');
		return $query->row();
  }

  function get_storename($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    return $query->row()->StoreName;
  }

  function get_cardname($id) {
    $this->db->where('card.CardId',$id);
    $query = $this->db->get('card');
    return $query->row()->CardName;
  }

  function get_username($id) {
    $this->db->join("user","user.UserId = managerduty.UserId");
    $this->db->where('managerduty.ManagerDutyId',$id);
    $query = $this->db->get('managerduty');
    return $query->row()->Fullname;
  }

  function get_status($id) {
    $this->db->where('status.StatusId',$id);
    $query = $this->db->get('status');
    return $query->row()->StatusName;
  }
}
?>
