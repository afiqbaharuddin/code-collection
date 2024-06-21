<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_Model extends CI_Model
{
  //Store List Table**
  var $table         = 'store'; //DATABASE TABLE NAME
  var $column_order  = array('StoreCode','StoreName',null,null,'store_status.StoreStatusName',null); //FIELD IN TABLE
  var $column_search = array('StoreCode','StoreName','store_status.StoreStatusName'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('AppLoggerId' => 'desc'); // DATA ORDERING

  public function __construct()
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
    $this->db->join("store_status","store_status.StoreStatusId = store.StoreStatusId");
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
  // Store List Table**

  //open store card
  function get_storeOpen()
  {
    $this->db->where('StoreId !=',0);
    $this->db->where('StoreStatusId',1);
    $query = $this->db->get('store');
    return $query->num_rows();
  }

  function get_storeClose()
  {
    $this->db->where('StoreStatusId',2);
    $query = $this->db->get('store');
    return $query->num_rows();
  }

  function get_total($storeid)
  {
    // $this->db->join("user_store","user_store.UserId = user.UserId");
    // $this->db->join("store","store.StoreId = user_store.StoreId");
    $this->db->where('StoreId',$storeid);
    $this->db->where('StatusId',1);
    $query = $this->db->get('user_store');
    return $query->num_rows();
  }

  function get_totalall(){
    // $this->db->where('StoreId',$storeid);
    // $this->db->where('StatusId',1);
    $query = $this->db->get('user_store');
    return $query->num_rows();
  }

  function count_store($storecode){
    $this->db->where('StoreCode', $storecode);
    $query = $this->db->get('store');
    return $query->num_rows();
  }

  function get_card() //dropdown get from db
  {
    $this->db->order_by('CardName', 'asc');
    $query = $this->db->get('card');
		return $query;
  }

  function get_status()
  {
    $this->db->where('StoreStatusId !=', 3);
    $query = $this->db->get('store_status');
    return $query;
  }

  function get_addcard() {
    $query = $this->db->get('card');
    return $query;
  }

  function get_storestatus_edit() {
    $query = $this->db->get('store_status');
    return $query;
  }

  //create Store
  function insertPOS($posno,$storeid,$get)
  {
    $data = array(
                  'POSNumber'   => $posno,
                  'StoreId'     => $storeid,
                  'StatusId'    => $get['status'],
                  'AppLoggerId' => $get['loggerid'],

                );
  $result = $this->db->insert('pos',$data);
  return $this->db->insert_id();
  }

  function insertCard($cardid, $storeid,$get)
  {
    // if ($get['status'] == 2) {
    //   $data = array(
    //                 'CardId'      => $cardid,
    //                 'StoreId'     => $storeid,
    //                 'StatusId'    => 1, // should display active card only?? and same with table card...
    //                 // 'StartDate'   => date('Y-m-d'),
    //  );
    // }
    // else {
      $data = array(
                    'CardId'      => $cardid,
                    'StoreId'     => $storeid,
                    'StatusId'    => 1, // should display active card only?? and same with table card...
                    'StartDate'   => date('Y-m-d'),
     );
    // }

   $result = $this->db->insert('card_store',$data);
   return $this->db->insert_id();
  }

  function insertStore($get)
  {
    $data = array(
                  'StoreCode'     => $get['storecode'],
                  'StoreName'     => $get['Storename'],
                  'StoreStatusId' => $get['status'],
                  'AppLoggerId'   => $get['loggerid'],

    );
    $result = $this->db->insert('store',$data);
    return $this->db->insert_id();
  }

  function insertVoucherSettingPermission($storeid){
    $data = array(
                  'StoreId'                   => $storeid,
                  'NumReprintCheck'           =>0,
                  'StoreVoucherIssuanceCheck' =>0,
                  'StatusId'                  => 1,
                  // 'AppLoggerId'           => $get['loggerid'],
             );
            $result = $this->db->insert('store_voucher_settings', $data);
            return $this->db->insert_id();
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

  function get_storename($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    return $query->row()->StoreName;
  }
}
?>
