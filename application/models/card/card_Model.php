<?php
  class card_Model extends CI_Model
  {

    function insertCard($get)
    {
      $data = array(
                    'CardPrefix'  => $get['PrefixNumber'],
                    'CardName'    => $get['cardName'],
                    'StatusId'    => $get['status'],
                    'AppLoggerId' => $get['loggerid'], //jangan lupa buang
      );
      $result = $this->db->insert('card',$data);
      return $this->db->insert_id();
    }

  }

 ?>
