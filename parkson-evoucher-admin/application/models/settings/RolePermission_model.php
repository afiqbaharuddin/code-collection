<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RolePermission_Model extends CI_Model
{
  function __construct(){
	   parent::__construct();
  }

  // public function role_list(){
  //   $this->db->select('*');
  //   // $this->db->select('adminrole.AdminRoleId, adminrole.AdminRoleName, adminrole.AdminRoleStatusId, adminrole.AppLoggerId, applogger.CreatedDate, userAdmin.FullName, status.StatusColor, status.StatusId, status.StatusName');
  //   $this->db->join('user_role','user_role.UserRoleId = authorize_permission_menu.UserRoleId');
  //   $this->db->join('status','status.StatusId = user_role.StatusId');
  //   $this->db->join('applogger','applogger.AppLoggerId = user_role.AppLoggerId');
  //   // $this->db->join('userAdmin','userAdmin.UserAdminId = applogger.CreatedBy','inner');
  //   $query = $this->db->get('authorize_permission_menu');
  //   return $query->result();
  // }

  // public function role_status($obj){
  //   $data = array(
  //    'AdminRoleStatusId' => $obj['value'],
  //   );
  //   $this->db->where('AdminRoleId',$obj['id']);
  //   return $this->db->update('adminrole', $data);
	// }
  //
  // public function insert_role($data){
  //   $this->db->insert('adminrole', $data);
  //   return $this->db->insert_id();
	// }
  //
  // public function update_role($data,$roleid){
  //   $this->db->where('AdminRoleId',$roleid);
  //   return $this->db->update('adminrole', $data);
	// }

  public function user_role(){
    $array=[0,99];
    $this->db->where_not_in('UserRoleId ', $array);
    $this->db->where('StatusId',1);
    $query = $this->db->get('user_role');
    return $query->result();
  }

  function authorization($UserRoleId)
  {
      $this->db->select("*");
      $this->db->where('StatusId', 1);
      $this->db->order_by('role_permission_menu.RolePermissionMenuId','asc');
      $query = $this->db->get('role_permission_menu');
      $output = '';

      $i = 1;
      foreach($query->result() as $row)
      {

          $sql ="SELECT * FROM authorize_permission_menu WHERE UserRoleId = $UserRoleId AND RolePermissionMenuId = $row->RolePermissionMenuId";

          $query = $this->db->query($sql);
          $permission = $query->row_array();

          if (isset($permission['View'])) {
              $Read   = $permission['View'];
          }else{
              $Read   = 0;
          }

          if (isset($permission['Update'])) {
              $Write    = $permission['Update'];
          }else{
              $Write    = 0;
          }

          if (isset($permission['Create'])) {
              $Create = $permission['Create'];
          }else{
              $Create = 0;
          }

          if (isset($permission['Delete'])) {
              $Delete = $permission['Delete'];
          }else{
              $Delete = 0;
          }

          if (isset($permission['AuthorizePermissionMenuId'])) {
              $ID     = $permission['AuthorizePermissionMenuId'];
          }else{
              $ID     = 0;
          }

          if ($row->MenuView   == 1) { $disableread   = " "; }else{ $disableread = "disabled"; }
          if ($row->MenuUpdate == 1) { $disablewrite  = " "; }else{ $disablewrite = "disabled"; }
          if ($row->MenuCreate == 1) { $disablecreate = " "; }else{ $disablecreate = "disabled"; }
          if ($row->MenuDelete == 1) { $disabledelete = " "; }else{ $disabledelete = "disabled"; }

          //Check for more
          $sql1     = "SELECT * FROM role_permission_submenu WHERE RolePermissionMenuId = $row->RolePermissionMenuId";
          $query2   = $this->db->query($sql1);
          $moremenu = $query2->num_rows();

          $output .= '<tr>
                          <th scope="row">'.$row->MenuName.'</th>';

          if ($Read == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="read'.$i.'" '.$disableread.' value="1" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="read'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="read'.$i.'" '.$disableread.' value="1" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="read'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Write == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="write'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="write'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="write'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="write'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Create == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="create'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="create'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="create'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="create'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Delete == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="delete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="delete'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="delete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->RolePermissionMenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="delete'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($moremenu > 0) {
              $output .= '<td class="text-center">
                            <a href="javascript:void(0);" class="more" id="'.$row->RolePermissionMenuId.'"><small>More</small></a>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                          <small>No</small>
                          </td>';
          }

      $i++;}
      return $output .='</tr>';
  }

//get permission menu sidebar
  function menu_master($id){
    $this->db->select("*");
    $this->db->where('RolePermissionMenuId', $id);
    $this->db->where('UserRoleId', $this->session->userdata('RoleId'));
    $query = $this->db->get('authorize_permission_menu');
    return $query->row();
  }

  //get permission submenu sidebar
  function submenu_master($id){
    $this->db->select("*");
    $this->db->where('RolePermissionSubmenuId', $id);
    $this->db->where('UserRoleId', $this->session->userdata('RoleId'));
    $query = $this->db->get('authorize_permission_submenu');
    return $query->row();
  }

  function get_more($obj)
  {
      $UserRoleId = $obj['UserRoleId'];
      $this->db->select("*");
      $this->db->where('RolePermissionMenuId', $obj['MenuMasterId']);
      $this->db->where('StatusId', 1);
      $this->db->order_by('role_permission_submenu.RolePermissionSubmenuId','asc');
      $query = $this->db->get('role_permission_submenu');
      $output = '';

      $i = 1;
      foreach($query->result() as $row)
      {

          $sql ="SELECT * FROM authorize_permission_submenu WHERE UserRoleId = $UserRoleId AND RolePermissionSubmenuId = $row->RolePermissionSubmenuId";

          $query = $this->db->query($sql);
          $permission = $query->row_array();

          if (isset($permission['View'])) {
              $Read   = $permission['View'];
          }else{
              $Read   = 0;
          }

          if (isset($permission['Update'])) {
              $Write    = $permission['Update'];
          }else{
              $Write    = 0;
          }

          if (isset($permission['Create'])) {
              $Create = $permission['Create'];
          }else{
              $Create = 0;
          }

          if (isset($permission['Delete'])) {
              $Delete = $permission['Delete'];
          }else{
              $Delete = 0;
          }

          if (isset($permission['AuthorizePermissionSubmenuId'])) {
              $ID     = $permission['AuthorizePermissionSubmenuId'];
          }else{
              $ID     = 0;
          }

          if ($row->SubmenuView   == 1) { $disableread   = " "; }else{ $disableread = "disabled"; }
          if ($row->SubmenuUpdate == 1) { $disablewrite  = " "; }else{ $disablewrite = "disabled"; }
          if ($row->SubmenuCreate == 1) { $disablecreate = " "; }else{ $disablecreate = "disabled"; }
          if ($row->SubmenuDelete == 1) { $disabledelete = " "; }else{ $disabledelete = "disabled"; }

          $output .= '<tr>
                          <th scope="row">'.$row->SubmenuName.'</th>';

          if ($Read == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subread'.$i.'" '.$disableread.' value="1" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subread'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subread'.$i.'" '.$disableread.' value="1" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subread'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Write == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subwrite'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subwrite'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subwrite'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subwrite'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Create == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subcreate'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subcreate'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subcreate'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subcreate'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Delete == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subdelete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subdelete'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subdelete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->RolePermissionSubmenuId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subdelete'.$i.'"></label>
                            </div>
                          </td>';
          }

      $i++;}
      return $output .='</tr>';
  }

  function check_default_permission_by_row($obj){

       $this->db->select("*");
       $this->db->where("UserRoleId", $obj['UserRoleId']);
       $this->db->where("RolePermissionMenuId", $obj['MasterId']);
       $query = $this->db->get('authorize_permission_menu');

       foreach ($query->result() as $row)
      {
         return $row;
      }
  }

  function save_permission($obj){

       $this->load->helper('url');

          $data = array(
           'UserRoleId'   => $obj['UserRoleId'],
           'RolePermissionMenuId' => $obj['MasterId'],
           $obj['allow']  => $obj['Check'],
           'AppLoggerId'  => $obj['AppLoggerId']
          );

       $this->db->insert('authorize_permission_menu', $data);
       $last_id = $this->db->insert_id();
       return $last_id;
 }

 function update_permission($obj){

       $this->load->helper('url');

          $data = array(
           $obj['allow'] => $obj['Check']
          );

       $this->db->where('AuthorizePermissionMenuId', $obj['PermissionId']);
       $this->db->update('authorize_permission_menu', $data);
 }

 function check_more_permission_by_row($obj){

      $this->db->select("*");
      $this->db->where("UserRoleId", $obj['UserRoleId']);
      $this->db->where("RolePermissionSubmenuId", $obj['MasterId']);
      $query = $this->db->get('authorize_permission_submenu');

      foreach ($query->result() as $row)
     {
        return $row;
     }
 }

 function save_permission_more($obj){

      $this->load->helper('url');

         $data = array(
          'UserRoleId'   => $obj['UserRoleId'],
          'RolePermissionSubmenuId' => $obj['MasterId'],
          $obj['allow']  => $obj['Check'],
          'AppLoggerId'  => $obj['AppLoggerId']
         );

      $this->db->insert('authorize_permission_submenu', $data);
      $last_id = $this->db->insert_id();
      return $last_id;
  }

  function update_permission_more($obj){

        $this->load->helper('url');

           $data = array(
            $obj['allow'] => $obj['Check']
           );

        $this->db->where('AuthorizePermissionSubmenuId', $obj['PermissionId']);
        $this->db->update('authorize_permission_submenu', $data);
  }
}
