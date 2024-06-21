<?php
  class Cards extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('card/CardPrefix_Model');
      $this->load->model('logs/ActivityLog_Model');
      $this->load->model('App_logger_model');

      if ($this->session->userdata('is_logged_in') == false)
      {
        redirect();
      }

      //CSRF PROTECTION\\
      $this->global_data['csrf'] = [
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
      ];

      //CSRF PROTECTION\\

      $this->global_data['UserId']            = $this->session->userdata('UserId');
      $this->global_data['Fullname']          = $this->session->userdata('Fullname');
      $this->global_data['Role']              = $this->session->userdata('Role');

      $this->global_data['CreatedDate']       = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']         = $this->global_data['UserId'];
      $this->global_data['AppType']           = 2;
      $this->global_data['UpdatedDate']       = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']         = $this->global_data['UserId'];
      $this->global_data['UserRoleId']        = $this->session->userdata('UserRoleId');
      $this->global_data['Role']              = $this->session->userdata('Role');
      // $this->output->enable_profiler(TRUE);
    }

     function index()
    {
       $data = array_merge($this->global_data);

       $data['header']          = $this->load->view('templates/main-header',"",true);
       $data['topbar']          = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']         = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']          = $this->load->view('templates/main-footer',"",true);
       $data['bottom']          = $this->load->view('templates/main-bottom',"",true);

       $data['cardstatus']      = $this->CardPrefix_Model->get_cardstatus()->result();

       $this->load->view('cards/cards_prefix', $data);
    }

    public function cardprefixlisting(){

      $data  = array_merge($this->global_data);
      $id    = $this->uri->segment(3);
      $post  = $this->input->post();
      $list  = $this->CardPrefix_Model->get_datatables($post,$id);
      $table = array();
      $no    = $post['start'];

      $j     = 1;

      foreach ($list as $field) {

        if($field->StatusId == 1) {
          $check = 'checked';
        } else {
          $check = '';
        }

        $no++;
        $row   = array();
        $row[] = $field->CardId;
        $row[] = $field->CardPrefix;
        $row[] = $field->CardName;

        $statuspermission     = $this->RolePermission_Model->menu_master(6);
        if ($field->StatusId) {
          if ($statuspermission->Update == 1) {

            $switch= '<label class="switch" >
                        <input type="checkbox" class="switch-input is-valid toggle" id="toggle_'.$j.'" data-num='.$j.' value="'.$field->StatusId.'" '.$check.'/>
                          <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                          </span>
                      </label>';
          }else {
            $switch= '';
          }
        }

        $row[] = '<td>
                    <input type="hidden" id="updatecardstatus_'.$j.'" name="updatecardstatus" value="'.$field->CardId.'"/>

                    '.$switch.'

                    <span id="showstatuscard_'.$j.'">
                      <span class="badge bg-label-'.$field->StatusColor.'" style="margin-left:35px" >'.$field->StatusName.'</span>
                    </span>
                  </td>';

        $row[] = '<div class="d-inline-block- text-nowrap">
                    <button class="btn btn-sm btn-icon editCard" data-cardid="'.$field->CardId.'" type="button" >
                      <i class="bx bx-edit"></i>
                    </button>
                  </div>';
        $table[] = $row;

        $j++;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->CardPrefix_Model->count_all($post,$id),
        "recordsFiltered"     => $this->CardPrefix_Model->count_filtered($post,$id),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function createCards()
    {
      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('cardName', 'Enter Card Name', 'required');

      $get = $this->input->post();
      $prefix = $get['PrefixNumber'];

      if($this->form_validation->run() == TRUE){

        if ($get['PrefixNumber'] != '') {
              $count = $this->CardPrefix_Model->get_prefix($get['PrefixNumber']);

              if ($count != 0) {
                $message = ' Prefix Card Number is already Exist!';

                $status    = false;
                $response  = $message;
                $errorcode = 400;

              } else {
                if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
                {
                  if ($id = $this->CardPrefix_Model->insertCard($get))
                  {
                    $cardname   = $this->CardPrefix_Model->get_cardname($id);
                    $prefix     = $this->CardPrefix_Model->get_cardprefix($id);

                    $status     = true;
                    $response   = "Card has been created.";
                    $errorcode  = 200;
                    $actmsg     = " create Card, ".$cardname. "($prefix)";
                  } else {
                    $status     = false;
                    $response   = [
                      "type"    => "authentication",
                      "error"   => array('error' => 'Something went wrong. Please try again later.'),
                    ];
                    $errorcode  = 500;
                    $actmsg     = " is trying to create Card, ".$cardname.". Failed.";
                  }
                } else
                {
                  $status    = false;
                  $response  = [
                    "type"   => "authentication",
                    "error"  => array('error' => 'Something went wrong. Please try again later.'),
                  ];
                  $errorcode = 500;
                  $actmsg    = " is trying to create Card, ".$cardname.". Applogger update failed.";
                }
                $act = [
                  'UserId'           => $data['UserId'],
                  'ActivityTypeId'   => 20,
                  'ActivityDetails'  => $data['Fullname'].$actmsg,
                  'ActiveDate'       => date('Y-m-d H:i:s'),
                  'IpAddress'        => $data['IpAddress'],
                  'OperatingSystem'  => $data['OperatingSystem'],
                  'Browser'          => $data['Browser'],
                ];

                $this->ActivityLog_Model->insert_activity($act);
              }
          }
          else {
            if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
            {
              if ($id = $this->CardPrefix_Model->insertCard($get))
              {
                $cardname   = $this->CardPrefix_Model->get_cardname($id);
                $prefix     = $this->CardPrefix_Model->get_cardprefix($id);

                $status     = true;
                $response   = "Card has been created.";
                $errorcode  = 200;
                $actmsg     = " create Card, ".$cardname. "($prefix)";
              } else {
                $status     = false;
                $response   = [
                  "type"    => "authentication",
                  "error"   => array('error' => 'Something went wrong. Please try again later.'),
                ];
                $errorcode  = 500;
                $actmsg     = " is trying to create Card, ".$cardname.". Failed.";
              }
            } else
            {
              $status    = false;
              $response  = [
                "type"   => "authentication",
                "error"  => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode = 500;
              $actmsg    = " is trying to create Card, ".$cardname.". Applogger update failed.";
            }
            $act = [
              'UserId'           => $data['UserId'],
              'ActivityTypeId'   => 20,
              'ActivityDetails'  => $data['Fullname'].$actmsg,
              'ActiveDate'       => date('Y-m-d H:i:s'),
              'IpAddress'        => $data['IpAddress'],
              'OperatingSystem'  => $data['OperatingSystem'],
              'Browser'          => $data['Browser'],
            ];

            $this->ActivityLog_Model->insert_activity($act);
          }

      }
      else
      {
        $error = $this->form_validation->error_array();
        if (isset($error['PrefixNumber'])) {
          if ($error['PrefixNumber'] == "error.") {
            $message = "error error.";
          }else {
            $message = 'Please key in Prefix Number fields!';
          }
        }
        else {
          $message = 'Please key in Prefix Number fields!!';
        }
        $status    = false;
        $response  = $message;
        $errorcode = 400;
      }

      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    function cardDetails()
    {
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $result['details'] = $this->CardPrefix_Model->get_carddetails($get['cardid']);

      $result['token']   = $data['csrf']['hash'];

      echo json_encode($result);
    }

    function editCards() {

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('prefixnumber', 'PrefixNumber', 'required');
      $this->form_validation->set_rules('editcardname', 'EditCardName', 'required');

      $get = $this->input->post();
      $id  = $get['cardId'];

      if($this->form_validation->run() == TRUE)
      {

        $data['AppLoggerId'] = $get['loggerid'];

        if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->CardPrefix_Model->editCards($get, $id))
          {
            $cardname   = $this->CardPrefix_Model->get_cardname($id);
            $prefix     = $this->CardPrefix_Model->get_cardprefix($id);

            $status     = true;
            $response   = "Card has been updated.";
            $errorcode  = 200;
            $actmsg     = " update Card, ".$cardname. "($prefix)";
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Card, ".$cardname.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Card, ".$cardname.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 5,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }
      else
      {
        $error = $this->form_validation->error_array();
        $salah = '';
        foreach ($error as $keye) {
          $salah .= $keye.'<br>';
        }

        $status    = false;
        $response  = $salah;
        $errorcode = 400;
      }
        $result['token']    = $data['csrf']['hash'];
        $result['status']   = $status;
        $result['message']  = $response;

        echo json_encode($result);
    }

    function get_showstatus()   {

      $data   = array_merge($this->global_data);
      $get    = $this->input->post();

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $status = $this->input->post('status');
      $cardId = $this->input->post('cardId');

      $data['AppLoggerId'] = $get['loggerid'];

      if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->CardPrefix_Model->update_cardStatus($status, $cardId))
          {
            $cardname   = $this->CardPrefix_Model->get_cardname($cardId);
            $prefix     = $this->CardPrefix_Model->get_cardprefix($cardId);
            $statusname = $this->CardPrefix_Model->get_status($status);

            $status     = true;
            $response   = "Card has been updated.";
            $errorcode  = 200;
            $actmsg     = " update Card Status, ".$cardname. "($prefix) to " .$statusname;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Card Status ".$cardname.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Card Status".$cardname.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 15,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
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
