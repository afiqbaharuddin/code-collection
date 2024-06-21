<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AssignUser_Model extends CI_Model {

  function get_managerstatus() {
    $array=[1,2];
    $this->db->where_in("StatusId" , $array);
    $query = $this->db->get('status');
    return $query;
  }

  function get_managercurrentrole($id){
    $this->db->join("managerduty", "managerduty.UserRoleId = user_role.UserRoleId");
    $this->db->where('managerduty.UserId', $id);
    $query = $this->db->get('user_role');
    return $query->row();
  }

  function get_MOD($id)
  {
    $array=[4,5];
    $this->db->select('*');
    $this->db->join("managerduty", "managerduty.UserId = user.UserId");
    $this->db->where("user.UserId", $id);
    $this->db->where_in("managerduty.StatusId" , $array);
    $this->db->where("user.StatusId", 1);
    $query = $this->db->get("user");
    // $query = $this->db->get("managerduty");
    return $query->row();
  }

  //get for assign manager duty
  function get_userdetails($id) {
    $this->db->select('*');
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
    $this->db->join("managerduty","managerduty.UserId = user.UserId");
    $this->db->where('user.UserId', $id);
    $this->db->order_by('managerduty.ManagerDutyId', 'desc');
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_statusMOD($id){
    $this->db->select('*');
    $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
    $this->db->join("user_store","user_store.UserId = user.UserId");
    $this->db->where('user.UserId', $id);
    $query = $this->db->get('user');
    $result = $query->row();
    return $result;
  }

  function assignmanager($get)
  {
    if ('StatusId' != 4  ) {

      $data = array(
                    'UserId'        => $get['userid'],
                    'StoreId'       => $get['storeid'],
                    'UserRoleId'    => $get['currentid'],
                    'TempRole'      => 104,
                    'StartDate'     => $get['start_date'],
                    'EndDate'       => $get['end_date'],
                    'StatusId'      => $get['managerstatus'],
                    'Remark'        => $get['remark'],
                    'AppLoggerId'   => $get['loggerid'],
      );

      $result = $this->db->insert('managerduty',$data);
      return $this->db->insert_id();
    }
  }

  function assignmanagerLater($get)
  {
    if ('StatusId' != 4  ) {

      $data = array(
                    'UserId'        => $get['userid'],
                    'StoreId'       => $get['storeid'],
                    'UserRoleId'    => $get['currentid'],
                    'TempRole'      => 104,
                    'StartDate'     => $get['start_date'],
                    'EndDate'       => $get['end_date'],
                    'StatusId'      => $get['managerstatus'],
                    'Remark'        => $get['remark'],
                    'AppLoggerId'   => $get['loggerid'],
      );

      $result = $this->db->insert('managerduty',$data);
      return $this->db->insert_id();
    }
  }

    function updateRole($manager,$userid){
      $data = array(
                      'UserRoleId' => 104,
                    );
      $this->db->where('UserId', $userid);
      return $this->db->update('user',$data);
    }

    function updateRole_userStore($manager,$userid){
      $data = array(
                      'UserRoleId' => 104,
                    );
      $this->db->where('UserId', $userid);
      return $this->db->update('user_store',$data);
    }

    function get_username($id) {
      $this->db->where('user.UserId',$id);
      $query = $this->db->get('user');
      return $query->row()->Fullname;
    }

}
 ?>
