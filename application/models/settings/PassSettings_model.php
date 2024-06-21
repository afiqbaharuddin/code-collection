<?php
  /**
   *
   */
  class PassSettings_model extends CI_Model
  {

    function settingPass($data)
    {

      $this->db->insert('password_settings',$data);
      return $this->db->insert_id();
    }

    function changeStatus($status)
    {
      $this->db->update('password_settings', $status);
    }

    function changeStatusCancel($statusdb)
    {
      return $this->db->update('password_settings', $statusdb);
    }

    function getAll()
    {
      $this->db->select('*');
      $this->db->from('password_settings');
      $data = $this->db->get();
      return $data->row();
    }

    function get_passdata() {
      $this->db->where("StatusId",1);
      $query = $this->db->get('password_settings');
      return $query->row();
    }

    function resetpasswordsetting($data, $get){

      $data = array(
                      'MinPassCheck'              => 'N',
                      'UppercaseCheck'            => 'N',
                      'LowercaseCheck'            => 'N',
                      'NumbersCheck'              => 'N',
                      'PasswordAgeCheck'          => 'N',
                      'PasswordHistoryCheck'      => 'N',
                      'IntialLogOneTimePassCheck' => 'N',
                      'OwnPassCheck'              => 'N',
                      'StatusId'                  => 1,
                      'AppLoggerId'               => $data['AppLoggerId'],
               );
        $this->db->insert('password_settings',$data);
        return $this->db->insert_id();
    }
  }
 ?>
