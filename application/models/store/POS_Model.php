<?php

class POS_Model extends CI_Model
{
  //create pos
  function insertPOS($get,$id, $storepos, $countpos)
  {
    // $number = $this->db->get('pos');

      if ($countpos != 0) {
        $data = array(
                      'StatusId' => 1,
        );

        // $this->db->where('StoreId',$get['storeid']);
        $this->db->where('POSNumber',$storepos);
        return $this->db->update('pos',$data);
      }
      else {
        $data = array(
                      'POSNumber'   => $storepos,
                      'StoreId'     => $id,
                      'StatusId'    => 1,
                      'AppLoggerId' => $get['loggerid'],
                      );

        $this->db->insert('pos',$data);
        return $this->db->insert_id();
      }
    }

  function count_pos($storepos){
    $this->db->where('POSNumber', $storepos);
    // $this->db->where('StoreId',$store);
    $this->db->where('StatusId',1);
    $query = $this->db->get('pos');
    return $query->num_rows();
  }

  function count_countpos($storepos){
    $this->db->where('POSNumber', $storepos);
    $query = $this->db->get('pos');
    return $query->num_rows();
  }

  function removePos($get) {

    $data = array(
                  'StatusId' => 3,
    );

    $this->db->where('StoreId',$get['storeid']);
    $this->db->where('PosId',$get['posid']);
    return $this->db->update('pos',$data);
  }

  function get_storename($id) {
    $this->db->where('store.StoreId',$id);
    $query = $this->db->get('store');
    return $query->row()->StoreName;
  }

  function get_posnumber($id) {
    $this->db->where('pos.POSId',$id);
    $query = $this->db->get('pos');
    return $query->row()->POSNumber;
  }
}
 ?>
