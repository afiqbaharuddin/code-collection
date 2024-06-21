<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_Model extends CI_Model {

  function get_userdetails($id)
  {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('UserId', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
  }

  function edit_profile($userid, $sent)
  {
    $this->db->where('UserId', $userid);
    $this->db->update('user', $sent);
  }

  function get_securitydetails($id)
  {
    $this->db->select('*');
    $this->db->from('user');
    $this->db->where('UserId', $id);
    $query = $this->db->get();
    $result = $query->row();
    return $result;
  }

  function securityUpdate($get,$id)
  {
    if ($get['newPassword'] !=null) {

      $pass    = encrypt($get['newPassword'],ENCRYPTION_KEY);

      $data = array(
                    'Password'   => $pass,
               );
    }else {
      // code...
    }
    $this->db->where('UserId', $id); //first column
    $this->db->update('user',$data);
  }

  function get_ownPass()
  {
    $this->db->order_by('PassSettingId', 'desc');
    $query = $this->db->get('password_settings');
    return $query->row();
  }
}
?>
