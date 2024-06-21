<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {

  public function __construct() {
		parent::__construct();

    //menu active based on url
    $this->global_data['active'] = $this->uri->segment(1);
    if ($this->session->userdata('is_logged_in') == false) {
      redirect();
    }

    $this->load->model('customer/Customer_model');
    $this->load->model('settings/Permission_model');

    $this->global_data['UserId']      = $this->session->userdata('UserId');
    $this->global_data['UserTypeId']  = $this->session->userdata('UserTypeId');

    $this->global_data['user']    	  = $this->Customer_model->select_user_info($this->global_data['UserId'],$this->global_data['UserTypeId']);

    $this->global_data['CreatedUserTypeId'] = 1;
    $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']   = $this->global_data['UserId'];
    $this->global_data['EditedUserTypeId'] = 1;
    $this->global_data['EditedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['EditedBy']    = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);
	}

  public function permission()
	{
    $data          = array_merge($this->global_data);
    $data['title'] = "Permission Settings";
    $data['sub']   = "System";

    $data['header']  = $this->load->view('templates/main-header',$data,true);
    $data['topbar']  = $this->load->view('templates/main-topbar',$data,true);
    $data['sidebar'] = $this->load->view('templates/main-sidebar','',true);
    $data['footer']  = $this->load->view('templates/main-footer','',true);

    $data['role'] = $this->Permission_model->user_role();

    $this->load->view('settings/permission-setting',$data);
	}

  public function user_role()
	{
    $data          = array_merge($this->global_data);
    $data['title'] = "User Role";
    $data['sub']   = "System";

    $data['header']  = $this->load->view('templates/main-header',$data,true);
    $data['topbar']  = $this->load->view('templates/main-topbar',$data,true);
    $data['sidebar'] = $this->load->view('templates/main-sidebar','',true);
    $data['footer']  = $this->load->view('templates/main-footer','',true);

    $this->load->view('settings/user-role',$data);
	}

  function role_list(){
    $role = $this->Permission_model->role_list();

    $result['role'] = '';
    foreach ($role as $row) {
      if ($row->AdminRoleStatusId == 1) {
        $checked = 'checked';
      }else {
        $checked = '';
      }
      $result['role'] .= '<tr>
                            <td class="text-bold-600 text-center">
                              <a href="#" class="edit" data-id="'.$row->AdminRoleId.'" data-name="'.$row->AdminRoleName.'" data-status="'.$row->AdminRoleStatusId.'" data-logger="'.$row->AppLoggerId.'">
                                #'.$row->AdminRoleId.'
                              </a>
                            </td>
                            <td class="text-center">'.$row->AdminRoleName.'</td>
                            <td class="text-center">
                              '.date('d-m-Y G:i A',strtotime($row->CreatedDate)).' | '.ucwords($row->FullName).'
                            </td>
                            <td>
                              <i class="bx bxs-circle '.$row->StatusColor.' font-small-1 mr-50"></i>'.$row->StatusName.'
                            </td>
                            <td class="text-center">
                              <div class="custom-control custom-switch custom-switch-glow custom-control-inline">
                                  <input type="checkbox" class="custom-control-input" '.$checked.' id="'.$row->AdminRoleId.'" value="'.$row->AdminRoleStatusId.'">
                                  <label class="custom-control-label" for="'.$row->AdminRoleId.'">
                                  </label>
                              </div>
                            </td>
                        </tr>';
    }
    // $result['token'] = $this->security->get_csrf_hash();
    echo json_encode($result['role']);
  }

  function role_status(){
    $data = array_merge($this->global_data);
    $get  = $this->input->post();

    $this->Permission_model->role_status($get);

    $result['token'] = $this->security->get_csrf_hash();
    echo json_encode($result);
  }

  function save_role(){
    $data = array_merge($this->global_data);
    $get = $this->input->post();

    if ($get['roleid'] == "") {
      $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data);
      $send = [
        'AdminRoleName' => $get['rolename'],
        'AdminRoleStatusId' => $get['rolestatus'],
        'AppLoggerId' => $get['loggerid'],
      ];
      $this->Permission_model->insert_role($send);
    }else {
      $data['AppLoggerId'] = $get['loggerid'];
      $this->App_logger_model->update_app_logger_data($data);
      $send = [
        'AdminRoleName' => $get['rolename'],
        'AdminRoleStatusId' => $get['rolestatus'],
      ];
      $this->Permission_model->update_role($send,$get['roleid']);
    }
    $result['token'] = $this->security->get_csrf_hash();
    echo json_encode($result);
  }

  function authorization() {

    $data['UserRoleId'] = $this->input->post('UserRoleId');

		if ($data['UserRoleId'] == "") {
			$UserRoleId         = 0;
		}else{
			$UserRoleId         = $this->input->post('UserRoleId');
		}
    $result['result']     = $this->Permission_model->authorization($UserRoleId);
    $result['token']      = $this->security->get_csrf_hash();
		echo json_encode($result);
	}

  function get_more() {

    $data['MenuMasterId'] = $this->input->post('MenuMasterId');

		if ($this->input->post('UserRoleId') == "") {
			$data['UserRoleId'] = 0;
		}else{
			$data['UserRoleId'] = $this->input->post('UserRoleId');
		}
    $result['result']               = $this->Permission_model->get_more($data);
    $result['token']      = $this->security->get_csrf_hash();
		echo json_encode($result);
	}

  function save_authorization() {
		$data = array_merge($this->global_data);

		$data['PermissionId']        = $this->input->post('PermissionId');
    $data['UserRoleId']          = $this->input->post('UserRoleId');
    $data['MasterId']            = $this->input->post('MasterId');
    $data['Type']                = $this->input->post('Type');
    $data['Check']               = $this->input->post('Check');
    $data['UserId']              = $this->input->post('UserId');
    $data['AppLoggerId']         = $this->input->post('AppLoggerId');

		if ($data['Type'] == 1) {
			$data['allow'] = "Read";
		}

		if ($data['Type'] == 2) {
			$data['allow'] = "Write";
		}

		if ($data['Type'] == 3) {
			$data['allow'] = "Create";
		}

		if ($data['Type'] == 4) {
			$data['allow'] = "Delete";
		}

		$check_default =  $this->Permission_model->check_default_permission_by_row($data);

		if ($check_default == "") {

			if ($last_id = $this->App_logger_model->insert_app_logger_data($data)) {
	          $data['AppLoggerId'] 	= $last_id;
	          $this->Permission_model->save_permission($data);

	          $data['error']					= "success";
	          $data['result']					= "Permission successfully added";
	        }

		}else{

			if ($this->App_logger_model->update_app_logger_data($data)) {

			  $this->Permission_model->update_permission($data);

	          $data['error']					= "success";
	          $data['result']					= "Permission successfully updated";
	        }

		}
    $data['token']      = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

  function save_authorization_more() {
		$data = array_merge($this->global_data);

		$data['PermissionId']        = $this->input->post('PermissionId');
    $data['UserRoleId']          = $this->input->post('UserRoleId');
    $data['MasterId']            = $this->input->post('MasterId');
    $data['Type']                = $this->input->post('Type');
    $data['Check']               = $this->input->post('Check');
    $data['UserId']              = $this->input->post('UserId');
    $data['AppLoggerId']         = $this->input->post('AppLoggerId');

		if ($data['Type'] == 1) {
			$data['allow'] = "Read";
		}

		if ($data['Type'] == 2) {
			$data['allow'] = "Write";
		}

		if ($data['Type'] == 3) {
			$data['allow'] = "Create";
		}

		if ($data['Type'] == 4) {
			$data['allow'] = "Delete";
		}

		$check_default =  $this->Permission_model->check_more_permission_by_row($data);

		if ($check_default == "") {

			if ($last_id = $this->App_logger_model->insert_app_logger_data($data)) {
	          $data['AppLoggerId'] 	= $last_id;
	          $this->Permission_model->save_permission_more($data);

	          $data['error']					= "success";
	          $data['result']					= "Permission successfully added";
	        }

		}else{

			if ($this->App_logger_model->update_app_logger_data($data)) {

			  $this->Permission_model->update_permission_more($data);

	          $data['error']					= "success";
	          $data['result']					= "Permission successfully updated";
	        }

		}
    $data['token']      = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

}
