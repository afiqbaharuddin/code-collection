<?php

class EditStore extends CI_Controller
{

  function __construct()
  {
    parent:: __construct();
    $this->load->model('store/EditStore_Model');
    $this->load->model('App_logger_model');
    $this->load->model('logs/ActivityLog_Model');

    if ($this->session->userdata('is_logged_in') == false) {
      redirect();
    }

    //CSRF PROTECTION\\
    $this->global_data['csrf'] = [
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
    ];
    //CSRF PROTECTION\\

    $this->global_data['UserId']       = $this->session->userdata('UserId');
    $this->global_data['Fullname']     = $this->session->userdata('Fullname');
    $this->global_data['Role']         = $this->session->userdata('Role');

    $this->global_data['CreatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['CreatedBy']    = $this->global_data['UserId'];
    $this->global_data['AppType']      = 2;
    $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
    $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
  }

  function edit()
  {
    $data = array_merge($this->global_data);

    $data['header']             = $this->load->view('templates/main-header',"",true);
    $data['topbar']             = $this->load->view('templates/main-topbar',"",true);
    $data['sidebar']            = $this->load->view('templates/main-sidebar',"",true);
    $data['footer']             = $this->load->view('templates/main-footer',"",true);
    $data['bottom']             = $this->load->view('templates/main-bottom',"",true);

    $data['addcarddb']          = $this->EditStore_Model->get_addcard()->result(); //fetch card from db (dropdown activate card)
    $data['storestatus_edit']   = $this->EditStore_Model->get_storestatus_edit()->result(); //dropdown store status edit form

    $data['POSId']              = $this->uri->segment(4);
    $storeid                    = $this->uri->segment(4);

    $data['details']            = $this->EditStore_Model->get_storedetails($storeid);

    $data['cards']              = $this->EditStore_Model->get_cardstore($storeid);
    $data['removedcard']        = $this->EditStore_Model->get_removedcard($storeid);

    $this->load->view('store/edit_store', $data);
  }

  //function for POS table
  public function poslisting(){
    $data = array_merge($this->global_data);

    $post  = $this->input->post();
    $id    = $this->uri->segment(4); //bawak specific pos store

    $list  = $this->EditStore_Model->get_pos_datatables($post,$id);
    $table = array();
    $no    = $post['start'];

    $k = 1;

    foreach ($list as $field) {

      $no++;
      $row   = array();
      $row[] = '<div class="removePos_'.$k.'" value="'.$field->POSId.'">'.$field->POSNumber.'</div>';

      $row[] = '<input type="hidden" class="removePos_'.$k.'" name="removePos" value="'.$field->POSId.'"/>
                <button class="btn btn-sm btn-icon removePos" data-posid="'.$field->POSId.'" data-storeid="'.$field->StoreId.'" data-num="'.$k.'" type="button">
                  <i class="fas fa-trash mt-2" style="margin-left:9px; cursor:pointer"></i>
                  <span style="margin-left:10px;">Remove<span>
                </button>';
      $table[] = $row;

      $k++;
    }

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->EditStore_Model->count_all($post,$id),
      "recordsFiltered"     => $this->EditStore_Model->count_filtered($post,$id),
      "data"                => $table,
      $data['csrf']['name'] => $data['csrf']['hash']
    );
    echo json_encode($output);
  }

  //function for Manager tables
  public function managerlisting(){
    $data = array_merge($this->global_data);

    $post = $this->input->post();
    $id = $this->uri->segment(4); //bawak specific MOD

    $list  = $this->EditStore_Model->get_manager_datatables($post,$id);
    $table = array();
    $no    = $post['start'];

    $z = 1;

    foreach ($list as $field) {
      $currentposition = $this->EditStore_Model->current_position($field->UserId);

      if (isset($currentposition )) {
        $current = $currentposition->Role;
      }else {
        $current ='';
      }

      if($field->StatusId == 4)
      {
        $check = 'checked';
      }elseif ($field->StatusId == 3) {
        $check = 'disabled';
      }else {
        $check = '';
      }

      $no++;

      $row   = array();
      // $row[] = $no;
      $row[] = $field->Fullname;
      $row[] = $field->StaffId;
      $row[] = $current;
      $row[] = $field->Role;
      $row[] = date('Y-m-d', strtotime($field->StartDate));
      $row[] = date('Y-m-d', strtotime($field->EndDate));

      $row[] = '<td>
                  <input type="hidden" id="modstatus_'.$z.'" name="modstatus" value="'.$field->ManagerDutyId.'"/>
                  <label class="switch">
                    <input type="checkbox" class="switch-input is-valid toggle" id="toggle_'.$z.'" data-num="'.$z.'" value="'.$field->StatusId.'" '.$check.'/>
                      <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                  </label>
                  <span id="showmanagerstatus_'.$z.'" style="margin-left:35px">
                    <span class="badge bg-label-'.$field->StatusColor.'">'.$field->StatusName.'</span>
                  </span>
                </td>';

      $table[] = $row;

      $z++;
    }

    $output = array(
      "draw"                => $post['draw'],
      "recordsTotal"        => $this->EditStore_Model->count_manager_all($post,$id),
      "recordsFiltered"     => $this->EditStore_Model->count_manager_filtered($post,$id),
      "data"                => $table,
      $data['csrf']['name'] => $data['csrf']['hash']
    );
    echo json_encode($output);
  }

  //To validate edit store form
  public function editstore() {

    $data = array_merge($this->global_data);

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    $get     = $this->input->post();
    $id      = $get['storeid'];

    $this->form_validation->set_rules('editstorename', 'editstorename', 'required');
    $this->form_validation->set_rules('editstorestatus', 'editstorestatus', 'required');

    if ($get['editstorestatus'] == 2 ) {
      $this->form_validation->set_rules('storecloseddate', 'Closed Date ', 'required');
    }

    for ($i=0; $i <$get['totalcard']+1 ; $i++) {
      if (!empty(($get['activatecard_'.$i]))) {
        $this->form_validation->set_rules('startDate_'.$i, 'Start Date', 'required');
        $this->form_validation->set_rules('endDate_'.$i, 'End Date', 'required');
      }
    }

    if($this->form_validation->run() == TRUE) {

      $data['AppLoggerId'] = $get['loggerid'];

      $duplicate = false;
      for ($j=1; $j < $get['totalcard']+ 1 ; $j++)
      {
        if (isset($get['activatecard_'.$j])) {
          $cardid = $get['activatecard_'.$j];

          $checkcard = $this->EditStore_Model->check_card($id,$cardid);
          if (isset($checkcard)) {
            $duplicate = true;
          }
        }
      }

      if ($duplicate == false) {
        $cardchecking = [];
        for ($j=1; $j < $get['totalcard']+ 1 ; $j++)
        {
          if (isset($get['activatecard_'.$j])) {

            if (!in_array($get['activatecard_'.$j],$cardchecking)) {
              $cardchecking[] = $get['activatecard_'.$j];

              $cardid        = $get['activatecard_'.$j];
              $cardstart     = $get['startDate_'.$j];
              $cardend       = $get['endDate_'.$j];

              if ($cardid != "")
              {
                if ($cardstart > date('Y-m-d'))
                {
                  $status = 2;
                }
                else
                {
                  $status = 1;
                }
                if ($this->EditStore_Model->addCard($cardid,$id,$cardstart,$cardend,$status))
                {
                  $storename  = $this->EditStore_Model->get_storename($get['storeid']);
                  $cardname   = $this->EditStore_Model->get_cardname($get['activatecard_'.$j]);

                  $status     = true;
                  $response   = "Card has been added.";
                  $errorcode  = 200;
                  $actmsg     = " Add ".$cardname." for ".$storename;
                } else {
                  $status     = false;
                  $response   = [
                    "type"    => "authentication",
                    "error"   => array('error' => 'Something went wrong. Please try again later.'),
                  ];
                  $errorcode  = 500;
                  $actmsg     = " is trying to add Store Card. Failed.";
                }
                $act = [
                  'UserId'           => $data['UserId'],
                  'ActivityTypeId'   => 36,
                  'ActivityDetails'  => $data['Fullname'].$actmsg,
                  'ActiveDate'       => date('Y-m-d h:i:s'),
                  'IpAddress'        => $data['IpAddress'],
                  'OperatingSystem'  => $data['OperatingSystem'],
                  'Browser'          => $data['Browser'],
                ];

                $this->ActivityLog_Model->insert_activity($act);
              }
            }
          }
        }
        for ($k=1; $k < $get['totaloldcard']+ 1 ; $k++)
        {
          if (isset($get['oldcardid_'.$k])) {
            $cardstoreid  = $get['cardstoreid_'.$k];
            $oldcardid    = $get['oldcardid_'.$k];
            $oldcardend   = $get['oldendDate_'.$k];

            if ($cardstoreid != "")
            {
              if (isset($get['oldstartDate_'.$k]))
              {
                $oldcardstart = $get['oldstartDate_'.$k];

                if ($oldcardstart > date('Y-m-d'))
                {
                  $oldstatus  = 2;
                }
                else
                {
                  $oldstatus  = 1;
                }
                $this->EditStore_Model->editCard_startdate($cardstoreid,$id,$oldcardstart,$oldcardend,$oldstatus);
              }
              else
              {
                $this->EditStore_Model->editCard_nostartdate($cardstoreid,$id,$oldcardend);
              }
            }
          }
        }

        if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->EditStore_Model->editStorename($get,$id))
          {
            $storename  = $this->EditStore_Model->get_storename($get['storeid']);

            $status     = true;
            $response   = "Store has been updated.";
            $errorcode  = 200;
            $actmsg     = " update Store, ".$storename;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Store, ".$storename.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Store, ".$storename.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 2,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d h:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }else {
        $status     = false;
        $response   = "Failed to save. Duplicate card!";
        $errorcode  = 500;
      }
    }else {
      $error        = $this->form_validation->error_array();
      $showerror    = '';
      foreach ($error as $err => $errval) {
        $showerror .= $errval.'<br>';
      }
      $data['update'] = $error;
      $status     = false;
      $response   = $showerror;
      $errorcode  = 400;
    }

    $result['id']       = $id;
    $result['get']      = $get;
    $result['token']    = $data['csrf']['hash'];
    $result['status']   = $status;
    $result['message']  = $response;
    echo json_encode($result);
  }

  public function removeCard() {

    $data   = array_merge($this->global_data);
    $get    = $this->input->post();
    $id     = $get['storeid'];
    $cardid = $get['cardid'];

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    // example for update logger
    $data['AppLoggerId'] = $get['loggerid'];
    $this->App_logger_model->update_app_logger_data($data);

    if ($this->EditStore_Model->remove_card($get,$id))
    {
      $storename  = $this->EditStore_Model->get_storename($get['storeid']);
      $cardname   = $this->EditStore_Model->get_cardname($get['cardid']);

      $result['token']    = $data['csrf']['hash'];
      $result['status']   = true;
      $result['message']  = 'Success';
      $actmsg             = "User Remove ".$cardname." in " .$storename;
    } else {
      $result['token']    = $data['csrf']['hash'];
      $result['status']   = false;
      $result['message']  = 'Something went wrong';
    }

    $act = [
      'UserId'           => $data['UserId'],
      'ActivityTypeId'   => 35,
      'ActivityDetails'  => $actmsg,
      'ActiveDate'       => date('Y-m-d H:i:s'),
      'IpAddress'        => $data['IpAddress'],
      'OperatingSystem'  => $data['OperatingSystem'],
      'Browser'          => $data['Browser'],
    ];

    echo json_encode($result);

    $this->ActivityLog_Model->insert_activity($act);
  }

  function get_modstatus()   {

    $data   = array_merge($this->global_data);
    $get    = $this->input->post();
    $userid = $get['managerdutyId'];
    $status = $get['status'];

    $data['IpAddress']        = $this->input->ip_address();
    $data['OperatingSystem']  = $this->agent->platform();
    $data['Browser']          = $this->agent->browser();

    $status         = $this->input->post('status');
    $managerdutyId  = $this->input->post('managerdutyId');

    $data['AppLoggerId'] = $get['loggerid'];

    if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->EditStore_Model->update_modStatus($status, $managerdutyId))
          {
            $username       = $this->EditStore_Model->get_username($get['managerdutyId']);
            $currentstatus  = $this->EditStore_Model->get_status($get['status']);

            $status     = true;
            $response   = "Manager on Duty has been updated.";
            $errorcode  = 200;
            $actmsg     = " update Manager on Duty Status to " .$currentstatus. " for ".$username;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Manager on Duty Status for ".$username.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Manager on Duty ID ".$managerdutyId.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 9,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d h:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);

        $result['token']  = $data['csrf']['hash'];
        echo json_encode($result);
  }
}
?>
