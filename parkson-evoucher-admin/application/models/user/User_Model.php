<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
  //User list table**
  var $table         = 'user'; //DATABASE TABLE NAME
	var $column_order  = array('Fullname', 'user_role.Role','store.StoreName',null,'app_logger.CreatedDate','status.StatusName',null); //FIELD IN TABLE
	var $column_search = array('Fullname', 'user_role.Role','store.StoreName','app_logger.CreatedDate','status.StatusName','Email','StaffId'); //FIELD FOR SEARCHING PURPOSES
  var $order         = array('user.UserId' => 'desc'); // DATA ORDERING


  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_datatables_query($post)
	{
		$i = 0;
		foreach ($this->column_search as $item) // loop column
		{
			if($post['search']['value']) // if datatable send POST for search
			{
        if ($item == 'app_logger.CreatedDate') {
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

		if(isset($post['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$post['order']['0']['column']], $post['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
    $this->db->where('user.StatusId !=',4);
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**

  //CHANGE ONLY ON THIS PART**
  function _extra_datatables($post,$userid,$permission){
    $array = [0,2];

    $this->db->select("*");
    $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
    $this->db->join("user_role","user_role.UserRoleId = ".$this->table.".UserRoleId");
    $this->db->join("status","status.StatusId = ".$this->table.".StatusId");
    $this->db->join("user_store","user_store.UserId = ".$this->table.".UserId");
    $this->db->join("store","store.StoreId = user_store.StoreId");
    $this->db->where_not_in('user.UserId',$array);

    if ($permission->UserRoleId != 1 || $permission->UserId != 1) {
      $this->db->where("user_store.StoreId", $userid);
    }
    $this->db->order_by('app_logger.CreatedDate', 'desc');
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

  function getuserid($userid)
  {
    $this->db->where('UserId',$userid);
    $query = $this->db->get('user');
    return $query->row();
  }

  function get_inactivedate($inactive)
  {
    $this->db->where('user.InactiveDate',$inactive);
    $query = $this->db->get('user');
    return $query->row();
  }
  function userStore($id){
    // $this->db->select("StoreId");
    $this->db->where("userId", $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //User Role table**
  var $roletable         = 'user_role'; //DATABASE TABLE NAME
	var $rolecolumn_order  = array('UserRoleId', 'Role','app_logger.CreatedDate','status.StatusName',null); //FIELD IN TABLE
	var $rolecolumn_search = array('UserRoleId', 'Role','app_logger.CreatedDate','status.StatusName'); //FIELD FOR SEARCHING PURPOSES
	var $roleorder         = array('app_logger.CreatedDate' => 'desc'); // DATA ORDERING

  //START AJAX DATATABLES
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  private function _get_role_datatables_query($post)
	{
		$i = 0;
		foreach ($this->rolecolumn_search as $item) // loop column
		{
			if($post['search']['value']) // if datatable send POST for search
			{
        if ($item == 'app_logger.CreatedDate' ) {
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

        }else {
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
				if(count($this->rolecolumn_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($post['order'])) // here order processing
		{
			$this->db->order_by($this->rolecolumn_order[$post['order']['0']['column']], $post['order']['0']['dir']);
		}
		else if(isset($this->roleorder))
		{
			$order = $this->roleorder;
			$this->db->role_order_by(key($order), $order[key($order)]);
		}
    $this->db->where('user_role.StatusId !=',4);
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**

  //CHANGE ONLY ON THIS PART**
  function _extra_role_datatables($post){

    $this->db->select("*");
    $this->db->join("app_logger","app_logger.AppLoggerId = ".$this->roletable.".AppLoggerId");
    $this->db->join("status","status.StatusId = ".$this->roletable.".StatusId");
  }
  //CHANGE ONLY ON THIS PART**

  //NO NEED TO CHANGE. JUST COPY AND PASTE**
	function get_role_datatables($post)
	{
    $this->db->from($this->roletable);
		if($post['length'] != -1)
    $this->_extra_role_datatables($post);
		$this->db->limit($post['length'], $post['start']);
		$this->_get_role_datatables_query($post);
		$query = $this->db->get();
		return $query->result();
	}

	function count_role_filtered($post)
	{
    $this->db->from($this->roletable);
    $this->_extra_role_datatables($post);
		$this->_get_role_datatables_query($post);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_role_all($post)
	{
		$this->db->from($this->roletable);
    $this->_extra_role_datatables($post);
		return $this->db->count_all_results();
	}
  //NO NEED TO CHANGE. JUST COPY AND PASTE**
  //END AJAX DATATABLES
  //User Role list table**

  function get_userCount($userid)
  {
    $this->db->where('user_store.UserId',$userid);
    $query = $this->db->get('user_store');
    return $query->row();
  }

//cards on user page
function get_totaluser()
{
  $array = [0,1];
  $this->db->where_not_in('UserId', $array);
  $query = $this->db->get('user');
  return $query->num_rows();
}

function get_totalActiveuser()
{
  $array = [0,1];
  $this->db->where_not_in('UserId', $array);
  $this->db->where('StatusId',1);
  $query = $this->db->get('user');
  return $query->num_rows();
}

function get_totalInactiveuser()
{
  $array = [0,1];
  $this->db->where_not_in('user.UserId', $array);
  $this->db->where('StatusId',2);
  $query = $this->db->get('user');
  return $query->num_rows();
}

//cards on user page based on store assigned
function get_totaluserStore($id)
{
  $array = [0,1];

  $this->db->join("user_store","user_store.UserId = user.UserId");
  $this->db->where_not_in('user.UserId ', $array);
  $this->db->where('user_store.StoreId', $id);
  $query = $this->db->get('user');
  return $query->num_rows();
}

function get_totalActiveuserStore($id)
{
  $array = [0,1];

  $this->db->join("user_store","user_store.UserId = user.UserId");
  $this->db->where_not_in('user.UserId ', $array);
  $this->db->where('user.StatusId',1);
  $this->db->where('user_store.StoreId', $id);
  $query = $this->db->get('user');
  return $query->num_rows();

}

function get_totalInactiveuserStore($id)
{
  $array = [0,1];

  $this->db->join("user_store","user_store.UserId = user.UserId");
  $this->db->where_not_in('user.UserId ', $array);
  $this->db->where('user_store.StoreId', $id);
  $this->db->where('user.StatusId',2);
  $query = $this->db->get('user');
  return $query->num_rows();
}

function get_user_list(){
  $this->db->select("*,user.Fullname as UserName, user.UserId as UserNameId, user.StaffId as UserStaffId, newuser.Fullname as CreatedName");
  $this->db->join("app_logger","app_logger.AppLoggerId = user.AppLoggerId");
  $this->db->join("user newuser","newuser.UserId = app_logger.CreatedBy");
  $this->db->join("user_role","user_role.UserRoleId = user.UserRoleId");
  $this->db->join("status","status.StatusId = user.StatusId");
  $this->db->join("user_store","user_store.UserId = user.UserId");
  $this->db->join("store","store.StoreId = user_store.StoreId");
  $query = $this->db->get('user');
  return $query->result();
}

  function updateResetGenerateFlag($notGenerate){
    $this->db->where('GenerateResetPassword', 1);
    $this->db->update('user', $notGenerate);
  }

  function get_user($userid)
  {
    $this->db->select("*");
    $this->db->select("user.StatusId as userStatus");
    $this->db->join("user_store","user_store.UserId = user.UserId");
    $this->db->where('user.UserId',$userid);
    $query = $this->db->get('user');
    return $query->row();
  }

  //insert User forrm
  function insertUser($data)
  {
      $result= $this->db->insert('user',$data);
      return $this->db->insert_id();
  }

  // for insert User forrm
  function inserusertStore($userid,$get)
  {
    $data = array(
                  'StoreId'     => $get['storeCode'],
                  'UserId'      => $userid,
                  'UserRoleId'  =>$get['userRole'],
                  'StatusId'    => 1,
                  'AppLoggerId' => $get['loggerid'],
             );
            $result = $this->db->insert('user_store', $data);
            return $this->db->insert_id();
  }

  function insert_userPassLog($userid, $encryptedpassword)
  {
    $data = array(
                  'UserId'      => $userid,
                  'PasswordLogValue' =>$encryptedpassword ,
                  'CreatedDate'  =>date('Y-m-d H:i:s'),

             );
            $result = $this->db->insert('password_log', $data);
            return $this->db->insert_id();
  }

  function insert_permissionVoucherSetting($userid,$get){
    $data = array(
                  'UserId'                => $userid,
                  'NumExtendCheck'        =>0,
                  'BlockVouchersCheck'    =>0,
                  'UnblockVouchersCheck'  =>0,
                  'CancelVoucherCheck'    =>0,
                  'StatusId'              => 1,
                  'AppLoggerId'           => $get['loggerid'],
             );
            $result = $this->db->insert('user_voucher_settings', $data);
            return $this->db->insert_id();
  }


  //update user
  function updateUser($get,$id)
  {
    if ($get['role'] == 1) {
      if ($get['password']) {
        if ($get['status'] == 2) {
          $data = array(
                        'Password'     => encrypt($get['password'],ENCRYPTION_KEY),
                        'Fullname'     => $get['fullname'],
                        'Email'        => $get['email'],
                        'UserRoleId'   => $get['editRole'],
                        'PhoneNo'      => $get['phoneNo'],
                        'StatusId'     => 1,
                        'InactiveDate' => $get['inactiveDate'],
                   );
        }else {
          $data = array(
                        'Password'     => encrypt($get['password'],ENCRYPTION_KEY),
                        'Fullname'     => $get['fullname'],
                        'Email'        => $get['email'],
                        'UserRoleId'   => $get['editRole'],
                        'PhoneNo'      => $get['phoneNo'],
                        'StatusId'     => 1,
                   );
        }
      }
      // elseif ($get['password'] != null && $get['inactiveDate'] == null) {
      //   $data = array(
      //                 'Password'     => encrypt($get['password'],ENCRYPTION_KEY),
      //                 'Fullname'     => $get['fullname'],
      //                 'Email'        => $get['email'],
      //                 'UserRoleId'   => $get['editRole'],
      //                 'PhoneNo'      => $get['phoneNo'],
      //                 'StatusId'     => 1,
      //            );
      // }
      elseif($get['password'] == null && $get['status'] == 2) {
        if (isset($get['status'])) {
          $data = array(
                        'Fullname'     => $get['fullname'],
                        'Email'        => $get['email'],
                        'UserRoleId'   => $get['editRole'],
                        'PhoneNo'      => $get['phoneNo'],
                        'StatusId'     => 1,
                        'InactiveDate' => $get['inactiveDate'],
                   );
        }else {
          $data = array(
                        'Fullname'     => $get['fullname'],
                        'Email'        => $get['email'],
                        'UserRoleId'   => $get['editRole'],
                        'PhoneNo'      => $get['phoneNo'],
                        'StatusId'     => 1,
                   );
        }
      }else {
        $data = array(
                      'Fullname'     => $get['fullname'],
                      'Email'        => $get['email'],
                      'UserRoleId'   => $get['editRole'],
                      'PhoneNo'      => $get['phoneNo'],
                      'StatusId'     => 1,
                 );
      }
    }else {
      $data = array(
                    'Fullname'     => $get['fullname'],
                    'Email'        => $get['email'],
                    'PhoneNo'      => $get['phoneNo'],
               );
    }

    $this->db->where('UserId', $id); //first column
    return $this->db->update('user',$data);
  }

  function insertPasswordLog($get)
  {
    $this->db->insert('password_log',$get);
  }

  //update user
  function updateusertStore($get, $id)
  {
    $data = array(
                  'StoreId'    => $get['editStoreCode'],
                  'UserRoleId' =>$get['editRole'],

             );
             $this->db->where('UserId', $id); //first column
             $this->db->update('user_store',$data);
    }

    function get_resetPassword($id)
    {
      $this->db->where('UserId',$id);
      $this->db->order_by('PasswordLogId', 'asc');
      $query = $this->db->get('password_log');
      return $query->row();
    }

    function updateDefaultPass($id, $encryptedpassword )
    {
      $this->db->set('Password', $encryptedpassword);
      $this->db->where('UserId', $id);
      return $query = $this->db->update('user');
    }

      function updateDefaultPass_passLog($id, $encryptedpassword)
      {
        $data = array(
                      'UserId'      => $id,
                      'PasswordLogValue' =>$encryptedpassword ,
                 );

                $this->db->order_by('PasswordLogId', 'asc');
                $this->db->where('UserId', $id);
                $this->db->limit(1);
                return $query = $this->db->update('password_log', $data);
      }

  function get_role(){
    $query = $this->db->get('user_role');
    return $query->row();
  }

  function count_role($role){
    $this->db->where('Role', $role);
    $query = $this->db->get('user_role');
    return $query->num_rows();
  }

  function count_staffid($staffid){
    $this->db->where('StaffId', $staffid);
    $query = $this->db->get('user');
    return $query->num_rows();
  }

  function count_Login($id){
    $this->db->where('LoginId', $id);
    $query = $this->db->get('user');
    return $query->num_rows();
  }

  //insert role for user role page
  function insertRole($get)
  {
      $data = array(
        'Role'        => $get['rolename'],
        'StatusId'    => $get['status'],
        'AppLoggerId' => $get['loggerid'],
      );
      $result = $this->db->insert('user_role',$data);
      return $this->db->insert_id();
  }

  function insertRolePermission($id, $get)
  {
    $array = array('1','2','3','4','5','6','7','8','9');

    for ($i=0; $i < 9 ; $i++) {

      $data = array(
        'UserRoleId'   => $id,
        'RolePermissionMenuId'=> $array[$i],
        'View'         => 1,
        'Create'       => 0,
        'Update'       => 0,
        'Delete'       => 0,
        'AppLoggerId'  => $get['loggerid'],
      );
      $result = $this->db->insert('authorize_permission_menu',$data);
    }

    return $this->db->insert_id();
  }

  function insertRoleSubPermission($id, $get)
  {
    $array = array('2','3','4','5','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22');


    for ($i=0; $i < 20 ; $i++) {

    $data = array(
      'UserRoleId'   => $id,
      'RolePermissionSubmenuId' =>$array[$i],
      'View'         => 1,
      'Create'       => 0,
      'Update'       => 0,
      'Delete'       => 0,
      'AppLoggerId'  => $get['loggerid'],
    );
    $result = $this->db->insert('authorize_permission_submenu',$data);
    }
    return $this->db->insert_id();
  }

  function get_password_setting()
	{
    $this->db->where('StatusId',1);
		$query = $this->db->get('password_settings');
		return $query->row();
	}

  function get_password_log($userid,$pass)
  {
    $this->db->where('UserId',$userid);
    // $this->db->where('PasswordLogValue',$pass);
    $this->db->order_by('PasswordLogId','DESC');
    $this->db->limit($pass);
    $query = $this->db->get('password_log');
    return $query->result();
  }

  function get_password_history($userid,$pass)
	{
    $this->db->where('UserId',$userid);
    $this->db->where('PasswordLogValue',$pass);
    $this->db->order_by('CreatedDate','DESC');
		$query = $this->db->get('password_log');
		$history = $query->row();

    if ($history != null) {
      $this->db->where('UserId',$userid);
      $this->db->where('PasswordLogValue !=',$pass);
      $this->db->where('CreatedDate >=',$history->CreatedDate);
  		$query = $this->db->get('password_log');
  		return $query->num_rows();
    }else {
      return 0;
    }
	}

  //dropdown storecode create user
  function get_storecode_create() {
    $this->db->join("store_status","store_status.StoreStatusId = store.StoreStatusId");
    $this->db->where("store_status.StoreStatusId" , 1);
    $this->db->order_by('StoreCode', 'asc');
    $query = $this->db->get('store');
    return $query;
  }

  //dropdown user role create user
  function get_userrole_create() {
    $array=[0,99];

    $query = $this->db->join("status","status.StatusId = user_role.StatusId");
    $query = $this->db->where("status.StatusId" , 1);
    $this->db->where_not_in('UserRoleId',$array);
    $this->db->order_by('user_role.Role', 'asc');
    $query = $this->db->get('user_role');
    return $query;
  }

  //dropdown store code edit user
  function get_storecode_edit() {
    $query = $this->db->join("store_status","store_status.StoreStatusId = store.StoreStatusId");
    $this->db->order_by('StoreCode', 'asc');
    $query = $this->db->where("store_status.StoreStatusId" , 1);
    $query = $this->db->get('store');
    return $query;
  }

  //dropdown user role edit user
  function get_userrole_edit() {
    $array=[0,99];

    $query = $this->db->join("status","status.StatusId = user_role.StatusId");
    $query = $this->db->where("status.StatusId" , 1);
    $this->db->where_not_in('UserRoleId',$array);
    // $this->db->where('UserRoleId !=',0);
    $this->db->order_by('user_role.Role', 'asc');
    $query = $this->db->get('user_role');
    return $query;
  }

  //dropdown user status edit
  function get_userstatus_edit() {
    $array = [3,4,5];
    $this->db->where_not_in('StatusId', $array);
    $query = $this->db->get('status');
    return $query;
  }

  //dropdown user_role status edit
  function get_userrole_status(){
    $this->db->where('StatusId !=', 3);
    $query = $this->db->get('status');
    return $query;
  }

  function updateRoleStatus($status, $userroleId) {

    $this->db->set('StatusId', $status);
    $this->db->where('UserRoleId', $userroleId);
    return $this->db->update('user_role');
  }

  function generatepass() {
    $this->db->where("StatusId" , 1);
    $query = $this->db->get('password_settings');
    return $query;
  }

  function get_permission($id){
    $query = $this->db->join("user","user.UserRoleId = user_store.UserRoleId");
    // $query = $this->db->join("store","store.StoreId = user_store.StoreId");
    $this->db->where("user_store.UserId" , $id);
    $query = $this->db->get('user_store');
    return $query->row();
  }

  function get_username($id) {
    $this->db->where('user.UserId',$id);
    $query = $this->db->get('user');
    return $query->row()->Fullname;
  }

  function get_rolename($id) {
    $this->db->where('user_role.UserRoleId',$id);
    $query = $this->db->get('user_role');
    return $query->row()->Role;
  }

  function get_user_role($id){
    $this->db->join("user","user.UserRoleId = user_role.UserRoleId");
    $this->db->where('user.UserId',$id);
    $query = $this->db->get('user_role');
    return $query->row();
  }

  function unlockmanuallyadmin(){
    $this->db->order_by('GeneralSettingId', 'desc');
    $query = $this->db->get('general_Settings');
    return $query->row();
  }

  function get_currentstatus($id) {
    $this->db->where('status.StatusId',$id);
    $query = $this->db->get('status');
    return $query->row()->StatusName;
  }
}
?>
