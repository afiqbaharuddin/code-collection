<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLog_Model extends CI_Model
//id,name,start,end,status,action
{
  var $table         = 'user_activity'; //DATABASE TABLE NAME
  var $column_order  = array('user.Fullname','user_role.Role' ,'ActiveDate','activity_type.ActivityTypeName','ActivityDetails','IpAddress', 'Browser'); //FIELD IN TABLE
  var $column_search = array('user.Fullname','user_role.Role' ,'ActiveDate','activity_type.ActivityTypeName','ActivityDetails','IpAddress', 'Browser'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('ActivityId' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
  {
    $i = 0;
    foreach ($this->column_search as $item) // loop column
    {
      if($post['search']['value']) // if datatable send POST for search
			{
        if ($item == 'ActiveDate') {
        $date = str_replace('/', '-', $post['search']['value']);
          if($i===0) // first loop
          {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $post['search']['value']);
          }
          else
          {
              $this->db->or_like($item,date('Y-m-d', strtotime($date)));
              $this->db->or_like($item,date('H:i:s', strtotime($date)));
          }
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

      // $order = $this->order;
      // $this->db->order_by(key($order), $order[key($order)]);

      if(isset($post['order'])) // here order processing
      {
        $this->db->order_by($this->column_order[$post['order']['0']['column']], $post['order']['0']['dir']);
      }
      else if(isset($this->order))
      {
        $order = $this->order;
        // print_r($order);die;
        $this->db->order_by(key($order), $order[key($order)]);
      }

  }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**

  //CHANGE ONLY ON THIS PART**
  function _extra_datatables($post,$userid,$permission){
    $this->db->select("*");
    $this->db->join("user","user.UserId = user_activity.UserId");
    $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
    $this->db->join("user_role", "user_role.UserRoleId = user.UserRoleId");
    $this->db->join("activity_type", "activity_type.ActivityTypeId = user_activity.ActivityTypeId");

    if (isset($post['filter_role']) && $post['filter_role'] != 0) {
      $this->db->where('user.UserRoleId', $post['filter_role']);
    }

    if (isset($post['filter_name']) && $post['filter_name'] != "") {
      $this->db->like('user.Fullname', $post['filter_name']);
    }

    if (isset($post['filter_category']) && $post['filter_category'] != 0) {
      $this->db->where('activity_type.ActivityTypeId', $post['filter_category']);
    }

    if (isset($post['filter_store']) && $post['filter_store'] != 0) {
      $this->db->join("user_store","user_store.UserId = user_activity.UserId");
      $this->db->where('user_store.StoreId', $post['filter_store']);
    }

    if (isset($post['date_from']) && $post['date_from'] != "") {
      $this->db->where('DATE(user_activity.ActiveDate) >=', $post['date_from']);
      // $this->db->where('app_logger.CreatedDate >=', $post['date_from']);
    }

    if (isset($post['date_to']) && $post['date_to'] != "") {
      $this->db->where('DATE(user_activity.ActiveDate) <=', $post['date_to']);
    }

    // if ($permission->UserRoleId != 1 || $permission->StoreId != 0) {
    //   $this->db->where("store.StoreId", $permission->StoreId);
    // }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->join("user_store", "user_store.UserId = user_activity.UserId");
        $this->db->where("user_store.StoreId", $permission->StoreId);
      }
    }
  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  function get_datatables($post,$userid,$permission)
  {
    $this->db->from($this->table);
    if($post['length'] != -1)
    $this->_extra_datatables($post,$userid,$permission);
    $this->db->limit($post['length'], $post['start']);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered($post,$userid,$permission)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$userid,$permission);
    $this->_get_datatables_query($post);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($post,$userid,$permission)
  {
    $this->db->from($this->table);
    $this->_extra_datatables($post,$userid,$permission);
    return $this->db->count_all_results();
  }
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User list table**

  function get_role() {
    $this->db->order_by('Role', 'asc');
    $this->db->where('UserRoleId !=',99);
    $query = $this->db->get('user_role');
    return $query;
  }

  function get_category() {
    $this->db->order_by('ActivityTypeName', 'asc');
    $query = $this->db->get('activity_type');
    return $query;
  }

  function get_store() {
    $this->db->order_by('StoreName', 'asc');
    $query = $this->db->get('store');
    return $query;
  }

  function insert_activity($act) {
    $result = $this->db->insert('user_activity', $act);
    return $this->db->insert_id();
  }

  public function userlog_csv($obj,$permission) {
    $this->db->select('*');
    $this->db->join("user","user.UserId = user_activity.UserId");
    $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
    $this->db->join("user_role", "user_role.UserRoleId = user.UserRoleId");
    $this->db->join("activity_type", "activity_type.ActivityTypeId = user_activity.ActivityTypeId");
    $this->db->join("user_Store", "user_Store.UserId = user.UserId");
    $this->db->join("store", "store.StoreId = user_Store.StoreId");

    $this->db->order_by("DATE(user_activity.ActiveDate)");

    if (isset($obj['start']) && $obj['start'] != null) {
      $this->db->where('DATE(user_activity.ActiveDate) >=',$obj['start']);
    }

    if (isset($obj['end']) && $obj['end'] != null) {
      $this->db->where('DATE(user_activity.ActiveDate) <=',$obj['end']);
    }

    if (isset($obj['category']) && $obj['category'] != 0) {
      $this->db->where('activity_type.ActivityTypeId', $obj['category']);
    }

    if (isset($obj['store']) && $obj['store'] != 0) {
      $this->db->where('user_store.StoreId', $obj['store']);
    }

    if (isset($obj['role']) && $obj['role'] != 0) {
      $this->db->where('user_role.UserRoleId', $obj['role']);
    }

    if (isset($obj['name']) && $obj['name'] != 0) {
      $this->db->like('user.Fullname', $obj['name']);
    }

    if ($permission->UserRoleId != 1) {
      if ($permission->StoreId != 0) {
        $this->db->where("user_store.StoreId", $permission->StoreId);
      }
    }

    $query = $this->db->get('user_activity');
    return $query->result();
  }

  function get_bystore($id) {
    $this->db->join("user","user.UserId = user_store.UserId");
    $this->db->join("store","store.StoreId = user_store.StoreId");

    $this->db->where('user_store.StoreId',$id);
    $query = $this->db->get('user_store');
    return $query->row()->StoreName;
  }

  function get_bycategory($id) {
    $this->db->where('activity_type.ActivityTypeId',$id);
    $query = $this->db->get('activity_type');
    if (isset($query->row()->ActivityTypeName)) {
      return $query->row()->ActivityTypeName;
    } else {
      return 'ALL CATEGORY';
    }
  }

  function get_permissionactivitylog($id) {
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");

    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function userstore($id) {
    $this->db->where("UserId", $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_permission($id) {
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");

    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }
}
?>
