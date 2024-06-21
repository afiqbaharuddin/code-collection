<?php
/**
 *
 */
class Login_model extends CI_Model
{
  function get_loginId($obj, $id)
  {
  $this->db->select('*');
  $this->db->where('LoginId',$obj['loginId']);
  // $this->db->where('UserId', $id);
  $this->db->where('StatusId',1);
  $query = $this->db->get('user');
  return $query->row();
  }

  function get_password($obj)
  {
    $this->db->select('*');
    $this->db->join('user_role','user_role.UserRoleId = user.UserRoleId');
    $this->db->where('BINARY(LoginId)',$obj['loginId']);
    // $this->db->where('Password',$obj['Password']);
    $this->db->where('Password',encrypt($obj['Password'],ENCRYPTION_KEY));
    // $this->db->where('user_credential.status_id', 1);
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_password_count($obj)
  {
    $this->db->select('*');
    $this->db->join('user_role','user_role.UserRoleId = user.UserRoleId');
    $this->db->where('BINARY(LoginId)',$obj['loginId']);
    $this->db->where('Password',encrypt($obj['Password'],ENCRYPTION_KEY));
    $query = $this->db->get('user');
    return $query->num_rows();
  }


  function status_inactive($id){
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_lockoutlogin(){
    $this->db->order_by('GeneralSettingId', 'desc');
    $query = $this->db->get('general_settings');
    return $query->row();
  }

  function blockuser($LoginId){
    $data = array(
                  // 'UserId' => $LoginId,
                  'StatusId' => 2,
                  'InactiveDate' => date('Y-m-d'),
                 );
    $this->db->where('UserId', $LoginId); //first column
    return $this->db->update('user',$data);
  }

  function set_retryPassword($id){
    $this->db->set('RetryPassword', 0);
    $this->db->where('LoginId',$id);
    return $this->db->update('user');
  }

  function update_retryPassword($id, $rP){
    $this->db->set('RetryPassword', $rP);
    $this->db->where('LoginId',$id);
    return $this->db->update('user');
  }
}

 ?>
