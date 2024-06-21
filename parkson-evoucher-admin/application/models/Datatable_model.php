<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_model extends CI_Model
{
  var $table         = 'vouchers'; //DATABASE TABLE NAME
	var $column_order  = array('VouchersNumber',null,'voucher_type.VoucherShortName','VouchersValue','ExpDate','app_logger.CreatedDate','VoucherStatusName'); //FIELD IN TABLE
	var $column_search = array('VouchersNumber','voucher_type.VoucherShortName','VouchersValue','ExpDate','app_logger.CreatedDate','VoucherStatusName'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('vouchers.VoucherId' => 'desc'); // DATA ORDERING

  function __construct()
  {
    parent::__construct();
    // $CI = &get_instance();
    // $this->db1 = $CI->load->database('db1', TRUE);
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
    $this->db->select("vouchers.VouchersNumber,vouchers.VoucherId,vouchers.VouchersValue,vouchers.ExpDate,store.StoreCode,voucher_type.VoucherShortName,app_logger.CreatedDate,voucher_status.VoucherStatusColor,voucher_status.VoucherStatusName");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");
    $this->db->join("voucher_issuance","voucher_issuance.VoucherIssuanceId = ".$this->table.".VoucherIssuanceId");
    $this->db->join("store","store.StoreId = voucher_issuance.IssuanceStoreId",'inner');
    $this->db->join("voucher_type","voucher_type.VoucherTypeId = ".$this->table.".VoucherTypeId");
    $this->db->join("voucher_status","voucher_status.VoucherStatusId = ".$this->table.".VoucherStatusId");

    if ($post['empty'] == 1) {
      $chunk = array_chunk($post['activity'],1000);
      $i = 1;
      foreach($chunk as $vouchers)
      {
        if ($i == 1) {
          $this->db->where_in('vouchers.VoucherId', $vouchers);
        }else {
          $this->db->or_where_in('vouchers.VoucherId', $vouchers);
        }
      $i++;}
    }elseif ($post['empty'] == 2) {
      $this->db->where('vouchers.VoucherId', 0);
    }elseif ($post['empty'] == 3) {
      if ($post['ReceiptNumber'] != '') {
        $this->db->where('voucher_issuance.ReceiptNumber', $post['ReceiptNumber']);
      }

      if (isset($post['voucher'])) {
        $this->db->where('vouchers.VoucherId', $post['voucher']->VoucherId);
      }elseif($post['VoucherNumber'] != '') {
        $this->db->where('vouchers.VoucherId', 0);
      }

      if (!empty($post['StoreCode'])) {
        $this->db->where_in('store.StoreId', $post['StoreCode']);
      }

      if (isset($post['pos'])) {
        $this->db->where_in('voucher_issuance.IssuancePOSId', $post['pos']->POSId);
      }

      if ($post['VoucherStatus'] != '') {
        $this->db->where('vouchers.VoucherStatusId', $post['VoucherStatus']);
      }

      if ($post['VoucherType'] != '') {
        $this->db->where('vouchers.VoucherTypeId', $post['VoucherType']);
      }

      if ($post['StartDate'] != '' && $post['EndDate'] != '') {
        if ($post['VoucherStatus'] == '') {
          $this->db->where('DATE(CreatedDate) >=', $post['StartDate']);
          $this->db->where('DATE(CreatedDate) <=', $post['EndDate']);
        }elseif ($post['VoucherStatus'] == 2) {
          $this->db->where('DATE(ExpDate) >=', $post['StartDate']);
          $this->db->where('DATE(ExpDate) <=', $post['EndDate']);
        }else {
          if (!empty($post['history'])) {
            $chunk = array_chunk($post['history'],1000);
            $i = 1;
            foreach($chunk as $vouchers)
            {
              if ($i == 1) {
                $this->db->where_in('vouchers.VoucherId', $vouchers);
              }else {
                $this->db->or_where_in('vouchers.VoucherId', $vouchers);
              }
            $i++;}
          }else {
            $this->db->where('vouchers.VoucherId', 0);
          }
        }
      }
    }
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
		// $query = $this->db->get();
		return $this->db->count_all_results();
	}

	public function count_all($post)
	{
		$this->db->from($this->table);
    $this->_extra_datatables($post);
		return $this->db->count_all_results();
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES

  function get_store()
	{
		$query = $this->db->get('store');
		return $query->result();
	}

  function get_status()
	{
		$query = $this->db->get('voucher_status');
		return $query->result();
	}

  function get_type()
	{
		$query = $this->db->get('voucher_type');
		return $query->result();
	}

  function get_voucher_activity($post)
	{
    $this->db->select('VouchersId');
    $this->db->join("voucher_status","voucher_status.CategoryId = voucher_activity.VoucherActivityCategoryId");

    if (!empty($post['StoreCode'])) {
			$this->db->where_in('voucher_activity.StoreId', $post['StoreCode']);
		}

    if (isset($post['pos'])) {
			$this->db->where_in('voucher_activity.PosId', $post['pos']->POSId);
		}

    if (isset($post['voucher'])) {
			$this->db->where('voucher_activity.VouchersId', $post['voucher']->VoucherId);
		}elseif($post['VoucherNumber'] != '') {
      $this->db->where('voucher_activity.VouchersId', 0);
    }

    if ($post['VoucherType'] != '') {
      $this->db->where('voucher_activity.VoucherTypeId', $post['VoucherType']);
    }

    $this->db->where('voucher_status.VoucherStatusId', $post['VoucherStatus']);

    if ($post['StartDate'] != '' && $post['EndDate'] != '') {
      $this->db->where('DATE(ActivityDate) >=', $post['StartDate']);
      $this->db->where('DATE(ActivityDate) <=', $post['EndDate']);
    }

    if (!empty($post['vouchid'])) {
      $chunk = array_chunk($post['vouchid'],1000);
      $i = 1;
      foreach($chunk as $vouchers)
      {
        if ($i == 1) {
          $this->db->where_in('voucher_activity.VouchersId', $vouchers);
        }else {
          $this->db->or_where_in('voucher_activity.VouchersId', $vouchers);
        }
      $i++;}
    }

		$query = $this->db->get('voucher_activity');
		$vouchid = [];
    foreach ($query->result() as $row) {
      $vouchid[] = $row->VouchersId;
    }
    return $vouchid;
	}

  function get_voucher_number($num)
	{
    $this->db->select('VoucherId');
    $this->db->where('vouchers.VouchersNumber', $num);
		$query = $this->db->get('vouchers');
		return $query->row();
	}

  function get_voucher_pos($pos)
	{
    $this->db->select('POSId');
    $this->db->where('pos.POSNumber', $pos);
		$query = $this->db->get('pos');
		return $query->row();
	}

  function get_voucher_receipt($receipt)
	{
    $this->db->select('VoucherIssuanceId');
    $this->db->where('voucher_issuance.ReceiptNumber', $receipt);
		$query = $this->db->get('voucher_issuance');
    $result = $query->row();

    if (isset($result)) {
      $this->db->select('VoucherId');
      $this->db->where('vouchers.VoucherIssuanceId', $query->row()->VoucherIssuanceId);
  		$query = $this->db->get('vouchers');

      $vouchid = [];
      foreach ($query->result() as $row) {
        $vouchid[] = $row->VoucherId;
      }
    }else {
      $vouchid = [];
    }
    return $vouchid;
	}

  function get_voucher_history($post)
	{
    $this->db->select('VoucherId');
    if ($post['VoucherStatus'] == 3) {
      $this->db->where('DATE(ExtendDate) >=', $post['StartDate']);
      $this->db->where('DATE(ExtendDate) <=', $post['EndDate']);
    }elseif ($post['VoucherStatus'] == 5) {
      $this->db->where('DATE(CancelDate) >=', $post['StartDate']);
      $this->db->where('DATE(CancelDate) <=', $post['EndDate']);
    }elseif ($post['VoucherStatus'] == 6) {
      $this->db->where('DATE(BlockDate) >=', $post['StartDate']);
      $this->db->where('DATE(BlockDate) <=', $post['EndDate']);
    }
		$query = $this->db->get('voucher_status_history');
    $vouchid = [];
    foreach ($query->result() as $row) {
      $vouchid[] = $row->VoucherId;
    }
    return $vouchid;
	}
}
