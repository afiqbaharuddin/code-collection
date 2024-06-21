<?php
  /**
   *
   */
  class activityLog_model extends CI_Model
  {
    var $table         = 'activity'; //DATABASE TABLE NAME
    var $column_order  = array('useradmin.FullName', 'adminrole.AdminRoleName','activity.ActivityDate','activitytype.ActivityTypeName', 'activity.ActivityDetails', 'activity.IpAddress', 'activity.OperatingSystem'); //FIELD IN TABLE
    var $column_search = array('useradmin.FullName','adminrole.AdminRoleName','activity.ACtivityDetails'); //FIELD FOR SEARCHING PURPOSES
    var $order         = array('activity.ActivityId' => 'desc'); // DATA ORDERING

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
    $this->db->join('useradmin','userAdmin.UserAdminId = activity.UserId');
    $this->db->join('adminrole','adminrole.AdminRoleId = useradmin.AdminRoleId');
    $this->db->join('activitytype','activitytype.ActivityTypeId = activity.ActivityTypeId');
    $this->db->order_by('activity.ActivityId', 'desc');

    if (isset($post['date_from']) && $post['date_from'] != "") {
      $this->db->where('DATE(activity.ActivityDate) >=', $post['date_from']);
    }

    if (isset($post['date_to']) && $post['date_to'] != "") {
      $this->db->where('DATE(activity.ActivityDate) <=', $post['date_to']);
    }
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

  function get_exportLog($get){
    $this->db->select('*');
    $this->db->join('useradmin','userAdmin.UserAdminId = activity.UserId');
    $this->db->join('adminrole','adminrole.AdminRoleId = useradmin.AdminRoleId');
    $this->db->join('activitytype','activitytype.ActivityTypeId = activity.ActivityTypeId');

    if (isset($get['start']) && $get['start'] != "") {
      $this->db->where('DATE(activity.ActivityDate) >=', $get['start']);
    }

    if (isset($get['end']) && $get['end'] != "") {
      $this->db->where('DATE(activity.ActivityDate) <=', $get['end']);
    }

    $query = $this->db->get('activity');

    if ($query->num_rows() > 0) {
          return $query->result_array();
      } else {
          return array(); // Return an empty array if no data is found
      }
    }

  }

 ?>
