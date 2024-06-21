 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

//data table cards
class CardPrefix_Model extends CI_Model

{
  var $table         = 'card'; //DATABASE TABLE NAME
  var $column_order  = array('CardPrefix', 'CardName','status.StatusName',null); //FIELD IN TABLE
  var $column_search = array('CardPrefix', 'CardName','status.StatusName'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('AppLoggerId' => 'desc'); // DATA ORDERING

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
    $this->db->join("status","status.StatusId = ".$this->table.".StatusId");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->table.".AppLoggerId");

    $this->db->order_by('CardId', 'asc');
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
  //Card Prefix table**


  //add card prefix
  function insertCard($get) {
    $data = array(
                  'CardPrefix'  => $get['PrefixNumber'],
                  'CardName'    => $get['cardName'],
                  'StatusId'    => $get['cardstatus'],
                  'AppLoggerId' => $get['loggerid'], //jangan lupa buang
    );
    $result = $this->db->insert('card',$data);
    return $this->db->insert_id();
  }

  //edit card prefix
  function editCards($get, $id) {

    $data = array(
                  'CardPrefix'    => $get['prefixnumber'],
                  'CardName'      => $get['editcardname'],
                  // 'StatusId'      => $get['cardstatus'],
                  // 'AppLoggerId'   => $get['loggerid'], //jangan lupa buang
    );

    $this->db->where('CardId',$id);
    return $this->db->update('card',$data);
  }

  function get_carddetails($cardid) {

    $this->db->where('CardId',$cardid);

    $query = $this->db->get('card');
    return $query->row();
  }

  function get_cardstatus() {
    $array = [3,4,5];
    $this->db->where_not_in('StatusId',$array);
    $query = $this->db->get('status');
    return $query;
  }

  function update_cardStatus( $status, $cardId) {

    $this->db->set('StatusId', $status);
    $this->db->where('CardId', $cardId);
    return $this->db->update('card');
  }

  function get_prefix($id){
    $this->db->where('CardPrefix', $id);
    $query = $this->db->get('card');
    return $query->num_rows();
  }

  function get_cardname($id) {
    $this->db->where('card.CardId',$id);
    $query = $this->db->get('card');
    return $query->row()->CardName;
  }

  function get_cardprefix($id) {
    $this->db->where('card.CardId',$id);
    $query = $this->db->get('card');
    return $query->row()->CardPrefix;
  }

  function get_status($id) {
    $this->db->where('status.StatusId',$id);
    $query = $this->db->get('status');
    return $query->row()->StatusName;
  }
}
?>
