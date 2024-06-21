<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RolePermission extends CI_Controller
  {
    function __construct() {

      parent:: __construct();
      $this->load->model('settings/RolePermission_Model');
      $this->load->model('App_logger_model');
      // $this->global_data['active'] = $this->uri->segment(1);

      if ($this->session->userdata('is_logged_in') == false) {
        redirect();
    }

    //CSRF PROTECTION\\
    $this->global_data['csrf'] = [
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
    ];
    //CSRF PROTECTION\\

    $this->global_data['UserId']        = $this->session->userdata('UserId');
    $this->global_data['Fullname']      = $this->session->userdata('Fullname');
    $this->global_data['Role']          = $this->session->userdata('Role');

    $this->global_data['CreatedDate']   = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']     = $this->global_data['UserId'];
    $this->global_data['AppType']       = 2;
    $this->global_data['UpdatedDate']   = date("Y-m-d H:i:s", time() ); //for edit part
    $this->global_data['UpdatedBy']     = $this->global_data['UserId'];
    // $this->output->enable_profiler(TRUE);
    }

    function rolepermission()
    {
      $data             = array_merge($this->global_data);
      $data['title']    = "Role Permission Settings";
      $data['sub']      = "System";

      $data['header']   = $this->load->view('templates/main-header',"",true);
      $data['topbar']   = $this->load->view('templates/main-topbar',"",true);
      $data['sidebar']  = $this->load->view('templates/main-sidebar',"",true);
      $data['footer']   = $this->load->view('templates/main-footer',"",true);
      $data['bottom']   = $this->load->view('templates/main-bottom',"",true);

      $data['role']     = $this->RolePermission_Model->user_role();

      $this->load->view('settings/role_permission', $data);
    }

    // function role_list() {
    //   $role = $this->RolePermission_Model->role_list();
    //
    //   $result['role'] = '';
    //   foreach ($role as $row) {
    //     if($row->UserRoleId == 1) {
    //       $checked = 'checked';
    //     } else {
    //       $checked = '';
    //     }
    //     $result ['role'] .='<tr>
    //                           <td class="text-bold-600 text-center">
    //                             <a href="#" class="edit" data-id="'.$row->AdminRoleId.'" data-name="'.$row->AdminRoleName.'" data-status="'.$row->AdminRoleStatusId.'" data-logger="'.$row->AppLoggerId.'">
    //                               #'.$row->AdminRoleId.'
    //                             </a>
    //                           </td>
    //                           <td class="text-center">'.$row->AdminRoleName.'</td>
    //                           <td class="text-center">
    //                             '.date('d-m-Y G:i A',strtotime($row->CreatedDate)).' | '.ucwords($row->FullName).'
    //                           </td>
    //                           <td>
    //                             <i class="bx bxs-circle '.$row->StatusColor.' font-small-1 mr-50"></i>'.$row->StatusName.'
    //                           </td>
    //                           <td class="text-center">
    //                             <div class="custom-control custom-switch custom-switch-glow custom-control-inline">
    //                                 <input type="checkbox" class="custom-control-input" '.$checked.' id="'.$row->AdminRoleId.'" value="'.$row->AdminRoleStatusId.'">
    //                                 <label class="custom-control-label" for="'.$row->AdminRoleId.'">
    //                                 </label>
    //                             </div>
    //                           </td>
    //                       </tr>';
    //   }
    //   echo json_encode($result['role']);
    // }

    function role_status() {
      $data = array_merge($this->global_data);
      $get  = $this->input->post();

      $this->RolePermission_Model->role_status($get);

      $result['token'] = $this->security->get_csrf_hash();
      echo json_encode($result);
    }

    function save_role(){
      $data = array_merge($this->global_data);
      $get  = $this->input->post();

      if ($get['roleid'] == "") {
        $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data);
        $send = [
          'Role'         => $get['rolename'],
          'StatusId'     => $get['rolestatus'],
          'AppLoggerId'  => $get['loggerid'],
        ];
        $this->RolePermission_Model->insert_role($send);
      }else {
        $data['AppLoggerId'] = $get['loggerid'];
        $this->App_logger_model->update_app_logger_data($data);
        $send = [
          'AdminRoleName'     => $get['rolename'],
          'AdminRoleStatusId' => $get['rolestatus'],
        ];
        $this->Permission_Model->update_role($send,$get['roleid']);
      }
      $result['token'] = $this->security->get_csrf_hash();
      echo json_encode($result);
    }

    function authorization() {

      $data['UserRoleId'] = $this->input->post('UserRoleId');

  		if ($data['UserRoleId'] == "") {
  			$UserRoleId     = 0;
  		}else{
  			$UserRoleId     = $this->input->post('UserRoleId');
  		}
      $result['result'] = $this->RolePermission_Model->authorization($UserRoleId);
      $result['token']  = $this->security->get_csrf_hash();
  		echo json_encode($result);
  	}

    function get_more() {

      $data['MenuMasterId'] = $this->input->post('MenuMasterId');

      if ($this->input->post('UserRoleId') == "") {
        $data['UserRoleId'] = 0;
      }else{
        $data['UserRoleId'] = $this->input->post('UserRoleId');
      }
      $result['result']     = $this->RolePermission_Model->get_more($data);
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
  			$data['allow'] = "View";
  		}

  		if ($data['Type'] == 2) {
  			$data['allow'] = "Update";
  		}

  		if ($data['Type'] == 3) {
  			$data['allow'] = "Create";
  		}

  		if ($data['Type'] == 4) {
  			$data['allow'] = "Delete";
  		}

  		$check_default =  $this->RolePermission_Model->check_default_permission_by_row($data);

  		if ($check_default == "") {

  			if ($last_id = $this->App_logger_model->insert_app_logger_data($data)) {
  	          $data['AppLoggerId'] 	= $last_id;
  	          $this->RolePermission_Model->save_permission($data);

  	          $data['error']	= "success";
  	          $data['result']	= "Permission successfully added";
  	        }

  		}else{

  			if ($this->App_logger_model->update_app_logger_data($data)) {

  			  $this->RolePermission_Model->update_permission($data);

  	          $data['error']	= "success";
  	          $data['result']	= "Permission successfully updated";
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
  			$data['allow'] = "View";
  		}

  		if ($data['Type'] == 2) {
  			$data['allow'] = "Update";
  		}

  		if ($data['Type'] == 3) {
  			$data['allow'] = "Create";
  		}

  		if ($data['Type'] == 4) {
  			$data['allow'] = "Delete";
  		}

  		$check_default =  $this->RolePermission_Model->check_more_permission_by_row($data);

  		if ($check_default == "") {

  			if ($last_id = $this->App_logger_model->insert_app_logger_data($data)) {
  	          $data['AppLoggerId'] 	= $last_id;
  	          $this->RolePermission_Model->save_permission_more($data);

  	          $data['error']   = "success";
  	          $data['result']  = "Permission successfully added";
  	        }
  		}else {

  			if ($this->App_logger_model->update_app_logger_data($data)) {

  			  $this->RolePermission_Model->update_permission_more($data);

  	          $data['error']	= "success";
  	          $data['result']	= "Permission successfully updated";
  	        }

  		}
      $data['token'] = $this->security->get_csrf_hash();
  		echo json_encode($data);
  	}
  }
 ?>
