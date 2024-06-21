<?php

class gift_manualError_Model extends CI_Model
{
  var $table         = 'manual_import_errorlog'; //DATABASE TABLE NAME
	var $column_order  = array('VoucherNumber', 'ImportDate','ErrorMessage'); //FIELD IN TABLE
	var $column_search = array('VoucherNumber', 'ImportDate','ErrorMessage'); //FIELD FOR SEARCHING PURPOSES
	var $order         = array('ErrorLogId' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
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
}
?>
