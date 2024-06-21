<?php

class gift_import_Model extends CI_Model
{
  var $table         = 'import_gift_voucher_scheduler'; //DATABASE TABLE NAME
	var $column_order  = array('CSVFileName', 'ScheduledImportTime','import_status.ImportStatusName',null); //FIELD IN TABLE
	var $column_search = array('CSVFileName', 'ScheduledImportTime','import_status.ImportStatusName'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('CSVFileId' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
    {
      if($post['search']['value']) // if datatable send POST for search
      {
        if($item == 'ScheduledImportTime') {
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
    $this->db->join("import_status","import_status.ImportStatusId = ".$this->table.".ImportStatusId");
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

  function import_csvFile($data){
    $result = $this->db->insert('import_gift_voucher_scheduler',$data);
    return $this->db->insert_id();
  }

  function pending_import_csvFile($voucherdata){
    $this->db->insert_batch('pending_gift_voucher',$voucherdata);
  }

//scheduler import gift voucher error log list
var $errorlogtable         = 'scheduler_import_errorlog'; //DATABASE TABLE NAME
var $errorlogcolumn_order  = array('VoucherNumber', 'ImportDate','ErrorMessage'); //FIELD IN TABLE
var $errorlogcolumn_search = array('VoucherNumber', 'ImportDate','ErrorMessage'); //FIELD FOR SEARCHING PURPOSES
var $errorlogorder         = array('CSVFileId' => 'desc'); // DATA ORDERING

//START AJAX DATATABLES
//NO NEED TO CHANGE. JUST COPY AND PASTE**
private function _get_errorlog_datatables_query($post)
{
  $i = 0;
  foreach ($this->errorlogcolumn_search as $item) // loop column
  {
    if($post['search']['value']) // if datatable send POST for search
    {
      if($item == 'ImportDate') {
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

      if(count($this->errorlogcolumn_search) - 1 == $i) //last loop
        $this->db->group_end(); //close bracket
    }
    $i++;
  }

    if(isset($post['order'])) // here order processing
    {
      $this->db->order_by($this->errorlogcolumn_order[$post['order']['0']['column']], $post['order']['0']['dir']);
    }
    else if(isset($this->errorlogorder))
    {
      $order = $this->errorlogorder;
      $this->db->order_by(key($order), $order[key($order)]);
    }

}
//NO NEED TO CHANGE. JUST COPY AND PASTE**

//CHANGE ONLY ON THIS PART**
function _extra_errorlog_datatables($post,$id){
  $this->db->select("*");
  $this->db->where('CSVFileId',$id);
}
//CHANGE ONLY ON THIS PART**

//NO NEED TO CHANGE. JUST COPY AND PASTE**
function get_errorlog_datatables($post,$id)
{
  $this->db->from($this->errorlogtable);
  if($post['length'] != -1)
  $this->_extra_errorlog_datatables($post,$id);
  $this->db->limit($post['length'], $post['start']);
  $this->_get_errorlog_datatables_query($post);
  $query = $this->db->get();
  return $query->result();
}

function errorlog_count_filtered($post,$id)
{
  $this->db->from($this->errorlogtable);
  $this->_extra_errorlog_datatables($post,$id);
  $this->_get_errorlog_datatables_query($post);
  $query = $this->db->get();
  return $query->num_rows();
}

public function errorlog_count_all($post,$id)
{
  $this->db->from($this->errorlogtable);
  $this->_extra_errorlog_datatables($post,$id);
  return $this->db->count_all_results();
}
//NO NEED TO CHANGE. JUST COPY AND PASTE**
//END AJAX DATATABLES

}
?>
