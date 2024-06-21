<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model
{
  function __construct(){
	   parent::__construct();
  }

  public function role_list(){
    $this->db->select('adminrole.AdminRoleId, adminrole.AdminRoleName, adminrole.AdminRoleStatusId, adminrole.AppLoggerId, applogger.CreatedDate, userAdmin.FullName, status.StatusColor, status.StatusId, status.StatusName');
    $this->db->join('status','status.StatusId = adminrole.AdminRoleStatusId');
    $this->db->join('applogger','applogger.AppLoggerId = adminrole.AppLoggerId');
    $this->db->join('userAdmin','userAdmin.UserAdminId = applogger.CreatedBy','inner');
    $query = $this->db->get('adminrole');
    return $query->result();
  }

  public function role_status($obj){
    $data = array(
     'AdminRoleStatusId' => $obj['value'],
    );
    $this->db->where('AdminRoleId',$obj['id']);
    return $this->db->update('adminrole', $data);
	}

  public function insert_role($data){
    $this->db->insert('adminrole', $data);
    return $this->db->insert_id();
	}

  public function update_role($data,$roleid){
    $this->db->where('AdminRoleId',$roleid);
    return $this->db->update('adminrole', $data);
	}

  public function user_role(){
    $this->db->where('AdminRoleStatusId',1);
    $query = $this->db->get('adminrole');
    return $query->result();
  }

  function authorization($UserRoleId)
  {
      $this->db->select("*");
      $this->db->where('StatusId', 1);
      $this->db->where('IsDeleted', 1);
      $this->db->order_by('menumaster.MenuMasterId','asc');
      $query = $this->db->get('menumaster');
      $output = '';

      $i = 1;
      foreach($query->result() as $row)
      {

          $sql ="SELECT * FROM authorizemenu WHERE UserRoleId = $UserRoleId AND MenuMasterId = $row->MenuMasterId";

          $query = $this->db->query($sql);
          $permission = $query->row_array();

          if (isset($permission['Read'])) {
              $Read   = $permission['Read'];
          }else{
              $Read   = 0;
          }

          if (isset($permission['Write'])) {
              $Write    = $permission['Write'];
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

          if (isset($permission['AuthorizeMenuId'])) {
              $ID     = $permission['AuthorizeMenuId'];
          }else{
              $ID     = 0;
          }

          if ($row->MenuRead == 1) { $disableread = " "; }else{ $disableread = "disabled"; }
          if ($row->MenuWrite == 1) { $disablewrite = " "; }else{ $disablewrite = "disabled"; }
          if ($row->MenuCreate == 1) { $disablecreate = " "; }else{ $disablecreate = "disabled"; }
          if ($row->MenuDelete == 1) { $disabledelete = " "; }else{ $disabledelete = "disabled"; }

          //Check for more
          $sql1 ="SELECT * FROM submenumaster WHERE MenuMasterId = $row->MenuMasterId";
          $query2   = $this->db->query($sql1);
          $moremenu = $query2->num_rows();

          $output .= '<tr>
                          <th scope="row">'.$row->MenuMasterName.'</th>';

          if ($Read == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="read'.$i.'" '.$disableread.' value="1" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="read'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="read'.$i.'" '.$disableread.' value="1" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="read'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Write == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="write'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="write'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="write'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="write'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Create == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="create'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="create'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="create'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="create'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Delete == 1) {
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="delete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="delete'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="delete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->MenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="delete'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($moremenu > 0) {
              $output .= '<td class="text-center">
                            <a href="#" class="more" id="'.$row->MenuMasterId.'">More</a>
                          </td>';
          }else{
              $output .= '<td class="text-center">
                            No
                          </td>';
          }

      $i++;}
      return $output .='</tr>';
  }

  function get_more($obj)
  {
      $UserRoleId = $obj['UserRoleId'];
      $this->db->select("*");
      $this->db->where('MenuMasterId', $obj['MenuMasterId']);
      $this->db->where('StatusId', 1);
      $this->db->where('IsDeleted', 1);
      $this->db->order_by('submenumaster.SubMenuMasterId','asc');
      $query = $this->db->get('submenumaster');
      $output = '';

      $i = 1;
      foreach($query->result() as $row)
      {

          $sql ="SELECT * FROM authorizesubmenu WHERE UserRoleId = $UserRoleId AND SubMenuMasterId = $row->SubMenuMasterId";

          $query = $this->db->query($sql);
          $permission = $query->row_array();

          if (isset($permission['Read'])) {
              $Read   = $permission['Read'];
          }else{
              $Read   = 0;
          }

          if (isset($permission['Write'])) {
              $Write    = $permission['Write'];
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

          if (isset($permission['AuthorizeSubMenuId'])) {
              $ID     = $permission['AuthorizeSubMenuId'];
          }else{
              $ID     = 0;
          }

          if ($row->SubMenuRead == 1) { $disableread = " "; }else{ $disableread = "disabled"; }
          if ($row->SubMenuWrite == 1) { $disablewrite = " "; }else{ $disablewrite = "disabled"; }
          if ($row->SubMenuCreate == 1) { $disablecreate = " "; }else{ $disablecreate = "disabled"; }
          if ($row->SubMenuDelete == 1) { $disabledelete = " "; }else{ $disabledelete = "disabled"; }

          $output .= '<tr>
                          <th scope="row">'.$row->SubMenuMasterName.'</th>';

          if ($Read == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subread'.$i.'" '.$disableread.' value="1" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subread'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subread'.$i.'" '.$disableread.' value="1" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subread'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Write == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subwrite'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subwrite'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subwrite'.$i.'" '.$disablewrite.' value="2" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subwrite'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Create == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subcreate'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subcreate'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subcreate'.$i.'" '.$disablecreate.' value="3" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
                                <label class="custom-control-label" for="subcreate'.$i.'"></label>
                            </div>
                          </td>';
          }

          if ($Delete == 1) {
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subdelete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'" checked>
                                <label class="custom-control-label" for="subdelete'.$i.'"></label>
                            </div>
                          </td>';
          }else{
              $output .= '<td class="text-center" class="text-center">
                            <div class="custom-control custom-switch custom-switch-success">
                                <input type="checkbox" class="custom-control-input check" id="subdelete'.$i.'" '.$disabledelete.' value="4" data-id="'.$row->SubMenuMasterId.'" data-logger="'.$row->AppLoggerId.'" data-permission="'.$ID.'">
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
       $this->db->where("MenuMasterId", $obj['MasterId']);
       $query = $this->db->get('authorizemenu');

       foreach ($query->result() as $row)
      {
         return $row;
      }
  }

  function save_permission($obj){

       $this->load->helper('url');

          $data = array(
           'UserRoleId'   => $obj['UserRoleId'],
           'MenuMasterId' => $obj['MasterId'],
           $obj['allow']  => $obj['Check'],
           'AppLoggerId'  => $obj['AppLoggerId']
          );

       $this->db->insert('authorizemenu', $data);
       $last_id = $this->db->insert_id();
       return $last_id;
 }

 function update_permission($obj){

       $this->load->helper('url');

          $data = array(
           $obj['allow'] => $obj['Check']
          );

       $this->db->where('AuthorizeMenuId', $obj['PermissionId']);
       $this->db->update('authorizemenu', $data);
 }

 function check_more_permission_by_row($obj){

      $this->db->select("*");
      $this->db->where("UserRoleId", $obj['UserRoleId']);
      $this->db->where("SubMenuMasterId", $obj['MasterId']);
      $query = $this->db->get('authorizesubmenu');

      foreach ($query->result() as $row)
     {
        return $row;
     }
 }

 function save_permission_more($obj){

      $this->load->helper('url');

         $data = array(
          'UserRoleId'   => $obj['UserRoleId'],
          'SubMenuMasterId' => $obj['MasterId'],
          $obj['allow']  => $obj['Check'],
          'AppLoggerId'  => $obj['AppLoggerId']
         );

      $this->db->insert('authorizesubmenu', $data);
      $last_id = $this->db->insert_id();
      return $last_id;
  }

  function update_permission_more($obj){

        $this->load->helper('url');

           $data = array(
            $obj['allow'] => $obj['Check']
           );

        $this->db->where('AuthorizeSubMenuId', $obj['PermissionId']);
        $this->db->update('authorizesubmenu', $data);
  }
}
