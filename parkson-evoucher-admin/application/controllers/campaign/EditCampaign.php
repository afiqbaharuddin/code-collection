<?php
  class EditCampaign extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('campaign/EditCampaign_Model');
      $this->load->model('campaign/Cardlist_model');
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

      $this->global_data['UserId']        = $this->session->userdata('UserId');
      $this->global_data['Fullname']      = $this->session->userdata('Fullname');
      $this->global_data['Role']          = $this->session->userdata('Role');

      $this->global_data['AppType']       = 2;
      $this->global_data['CreatedDate']   = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']     = $this->global_data['UserId'];
      $this->global_data['UpdatedDate']   = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']     = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function editcampaign()
    {
       $data = array_merge($this->global_data);

       $data['header']              = $this->load->view('templates/main-header',"",true);
       $data['topbar']              = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar']             = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']              = $this->load->view('templates/main-footer',"",true);
       $data['bottom']              = $this->load->view('templates/main-bottom',"",true);

       $data['CampaignId']          = $this->uri->segment(4);

       $data['editstatus']          = $this->EditCampaign_Model->get_editstatus()->result();
       $data['terminate_status']    = $this->EditCampaign_Model->terminate_status($data['CampaignId'] );

       $storecampaign               = $this->EditCampaign_Model->get_campaign_store($data['CampaignId']);

       $storearray = [];
       foreach ($storecampaign as $storekey) {
         $storearray[] = $storekey->StoreId;
       }
       $data['storecode']           = $this->EditCampaign_Model->get_campaign_storecode($storearray)->result();

       $data['campaignstatus']      = $this->EditCampaign_Model->get_campaignstatus()->result();
       $data['editstorestatus']     = $this->EditCampaign_Model->get_storestatus()->result();
       $data['vouchertype']         = $this->EditCampaign_Model->get_vouchertype()->result();
       $data['campaignvouchertype'] = $this->EditCampaign_Model->get_campaignvouchertype($data['CampaignId']);
       $data['cvtarray']            = [];

       foreach ($data['campaignvouchertype'] as $cvt) {
         $data['cvtarray'][] = $cvt->VoucherTypeId;
       }

       $cardCampaign                = $this->Cardlist_model->get_campaign_card($data['CampaignId']);
       $cardarray = [];
       foreach ($cardCampaign  as $cardkey) {
           $cardarray[] = $cardkey->CardId;
       }

       $data['addcard']             = $this->Cardlist_model->get_addcard($cardarray)->result();
       $data['cardstatus']          = $this->Cardlist_model->get_cardstatus()->result();
       $data['editcardstatus']      = $this->Cardlist_model->get_edit_cardstatus()->result();

       //bawa ke view edit campaign
       $data['campaign']            = $this->EditCampaign_Model->get_campaigndetails($data['CampaignId'] );

       $this->load->view('campaign/edit_campaign', $data);
    }

    //table store list function
    public function storelisting(){

      $data  = array_merge($this->global_data);
      $post  = $this->input->post();

      $id    = $this->uri->segment(4);

      $list  = $this->EditCampaign_Model->get_datatables($post,$id);
      $table = array();
      $no    = $post['start'];


      foreach ($list as $field) {

        $no++;
        $row   = array();


        // $row[] = $no;
        $row[] = $field->StoreCode;
        $row[] = $field->StoreName;
        $row[] = date('Y-m-d', strtotime($field->StartDate));

        //allow null in db
        if ($field->EndDate != null) {
          $enddate = date('Y-m-d', strtotime($field->EndDate));
        }else {
          $enddate = '';
        }

        if ($field->CampaignStatusId == 3)
        {
          $extendDate = $this->EditCampaign_Model->get_extendDate($field->CampaignStoreId);

          if (isset($extendDate))
          {
            $row[] =  date('Y-m-d', strtotime($extendDate->ExtendDate));
          }else
          {
            $row[] = '';
          }
        }else
          {
            $row[] = $enddate;
          }

        $row[] = '<span>
                    <span class="badge bg-label-'.$field->CampaignStatusColor.'">'.$field->Status.'</span>
                  </span>';

        $row[] = '<div class="d-inline-block- text-nowrap" >
                    <button class="btn btn-sm btn-icon editStore" data-storeid="'.$field->StoreId.'" type="button" data-bs-toggle="offcanvas" data-bs-target="#EditCampaignStore">
                      <i class="bx bxs-edit"></i>
                    </button>
                  </div>';
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->EditCampaign_Model->count_all($post, $id),
        "recordsFiltered"     => $this->EditCampaign_Model->count_filtered($post, $id),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    //table card list function
    public function cardlisting(){

      $data = array_merge($this->global_data);

      $post = $this->input->post();
      $id = $this->uri->segment(4);

      $list  = $this->Cardlist_model->get_datatables($post,$id);
      $table = array();
      $no    = $post['start'];

      foreach ($list as $field) {

        $no++;
        $row   = array();
        // $row[] = $no;
        $row[] = $field->CardName;
        $row[] = date('Y-m-d', strtotime($field->StartDate));

        if ($field->EndDate != null) {
          $date = date('Y-m-d', strtotime($field->EndDate));
        }else {
          $date = '';
        }

        if ($field->CampaignStatusId == 3) {
          $extendDate = $this->Cardlist_model->get_extendDate($field->CardCampaignId);

          if (isset($extendDate)) {
            $row[] =  date('Y-m-d', strtotime($extendDate->ExtendDate));
          }else {
            $row[] = '';
          }
        }else {
          $row[] = $date;
        }

        $row[] = '<span>
                    <span class="badge bg-label-'.$field->CampaignStatusColor.'" >'.$field->Status.'</span>
                  </span>';

        $row[] = '<div class="d-inline-block- text-nowrap" >
                    <button class="btn btn-sm btn-icon editCard" type="button" data-cardid="'.$field->CardId.'"data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditCard">
                      <i class="bx bxs-edit"></i>
                    </button>
                  </div>';
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->Cardlist_model->count_all($post,$id),
        "recordsFiltered"     => $this->Cardlist_model->count_filtered($post,$id),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
  }

//================================================================================================================//
//Edit Store Section**

    //add store campaign (edit)
    function createStore(){

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $this->form_validation->set_rules('campaignStoreCode[]', 'campaign store code', 'required');
      $this->form_validation->set_rules('startDate', 'start Date', 'required');
      $this->form_validation->set_rules('endDate', 'End Date', 'required');

      $get = $this->input->post();
      $result['get'] = $get;

      if($this->form_validation->run() == TRUE){

        $campaign['CampaignId'] = $this->uri->segment(4);
        $id = $this->uri->segment(4);

        foreach ($get['campaignStoreCode'] as $store => $val) {

        if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
        {
          if ($storecampaign = $this->EditCampaign_Model->insertStoreCampaign($val,$get,$id))
          {
            $campname   = $this->EditCampaign_Model->get_storecampname($storecampaign);
            $storename  = $this->EditCampaign_Model->storename($val);

            $status     = true;
            $response   = "Store Campaign has been added.";
            $errorcode  = 200;
            $actmsg     = " add Store Campaign, " .$storename. " to ".$campname;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to add Store Campaign, ".$campname.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to add Store Campaign, ".$campname.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 25,
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

    //display store details for edit
    function storeDetails() {
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $result['details']  = $this->EditCampaign_Model->get_store($get['storeid'], $get['campaignid']);
      $result['token']    = $data['csrf']['hash'];
      echo json_encode($result);
    }

    function editCampaignForm(){

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();
      $id  = $get['campaignid'];

      $this->form_validation->set_rules('editcampaignstatus', 'Status', 'required');

      if ($get['editcampaignstatus'] == 2 ) {
        $this->form_validation->set_rules('inactiveDate', 'Inactive Date', 'required');
      }

      if ($get['saveallterminate'] == 'true' ) {
        $this->form_validation->set_rules('terminatedate', 'Terminate Date', 'required');
        $this->EditCampaign_Model->editCampaignTerminateCard($get,$id);
        $this->EditCampaign_Model->editCampaignTerminateStore($get,$id);
      }

      if ($get['saveall'] == 'true' ) {
        $this->form_validation->set_rules('extenddate', 'Extend Date', 'required');
        $this->EditCampaign_Model->editCampaignExtendCard($get,$id);
        $this->EditCampaign_Model->editCampaignExtendStore($get,$id);
      }

      if($this->form_validation->run() == TRUE){

        $data['AppLoggerId'] = $get['loggerid'];

        if ($this->App_logger_model->update_app_logger_data($data))
          {
            if ($this->EditCampaign_Model->editCampaign($get, $id))
            {
              $campname      = $this->EditCampaign_Model->get_campname($get['campaignid']);
              $currentstatus = $this->EditCampaign_Model->currentstatus($get['editcampaignstatus']);

              $status     = true;
              $response   = "Campaign has been updated.";
              $errorcode  = 200;
              $actmsg     = " Update Campaign Status to " .$currentstatus. " for " .$campname;
            } else {
              $status     = false;
              $response   = [
                "type"    => "authentication",
                "error"   => array('error' => 'Something went wrong. Please try again later.'),
              ];
              $errorcode  = 500;
              $actmsg     = " is trying to update Campaign, ".$campname.". Failed.";
            }
          } else
          {
            $status    = false;
            $response  = [
              "type"   => "authentication",
              "error"  => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode = 500;
            $actmsg    = " is trying to update Campaign, ".$campname.". Applogger update failed.";
          }
          $act = [
            'UserId'           => $data['UserId'],
            'ActivityTypeId'   => 6,
            'ActivityDetails'  => $data['Fullname'].$actmsg,
            'ActiveDate'       => date('Y-m-d H:i:s'),
            'IpAddress'        => $data['IpAddress'],
            'OperatingSystem'  => $data['OperatingSystem'],
            'Browser'          => $data['Browser'],
          ];

          $this->ActivityLog_Model->insert_activity($act);
      }
      else{
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

    //edit campaign store(edit)
    function editCampaignStore() {

      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();
      $id  = $get['storeid'];

      $this->form_validation->set_rules('editStoreStatus', 'EditStoreStatus', 'required');

      if ($get['editStoreStatus'] == 2) {
        $this->form_validation->set_rules('inactivestoredate', 'Inactive Store Date', 'required');
      }

      if ($get['editStoreStatus'] == 3) {
        $this->form_validation->set_rules('editStoreExtendDate', 'Extend Store Date', 'required');
      }


      if($this->form_validation->run() == TRUE){

        $data['AppLoggerId'] = $get['loggerid'];

        if ($this->App_logger_model->update_app_logger_data($data))
        {
          if ($this->EditCampaign_Model->editCampaignStore($get, $id))
          {
            $campstore     = $this->EditCampaign_Model->get_campstore($get['storeid']);
            $currentstatus = $this->EditCampaign_Model->currentstatus($get['editStoreStatus']);
            $campname      = $this->EditCampaign_Model->get_campname($get['campaignid']);

            $status     = true;
            $response   = "Stores has been updated.";
            $errorcode  = 200;
            $actmsg     = " update Store Campaign Status to " .$currentstatus. " for ".$campstore. " in " . $campname;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to update Store Campaign ".$campstore.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to update Store Campaign ".$campstore.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 7,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }
      else {
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

//=================================================================================================================================//
//Card Edit Section**

  //add card campaign (edit)
  function addcard() {

    $data = array_merge($this->global_data);

    $data['IpAddress']       = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser']         = $this->agent->browser();

    $this->form_validation->set_rules('campaigncardname[]', 'campaign card name', 'required');
    $this->form_validation->set_rules('cardStart', 'card Start', 'required');
    $this->form_validation->set_rules('cardEnd', 'card End', 'required');
    $this->form_validation->set_rules('cardStatus', 'card Status', 'required');

    $get = $this->input->post();
    $result['get'] = $get;

    if($this->form_validation->run() == TRUE){

      $campaign['CampaignId'] = $this->uri->segment(4);
      $id = $this->uri->segment(4);

      foreach ($get['campaigncardname'] as $card => $val) {

      if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
        {
          if ( $cardcampaign = $this->EditCampaign_Model->insertCardCampaign($val,$get,$id))
          {
            $cardname   = $this->EditCampaign_Model->get_cardname($cardcampaign);
            $campname   = $this->EditCampaign_Model->get_campname($campaign['CampaignId']);

            $status     = true;
            $response   = "Card Campaign has been added.";
            $errorcode  = 200;
            $actmsg     = " add " .$cardname. " for Card Campaign in ".$campname;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to add Card Campaign ID ".$cardcampaign.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to add Card Campaign ID ".$cardcampaign.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 26,
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

  //display card details for edit
  function cardDetails() {
    $data = array_merge($this->global_data);
    $get = $this->input->post();

    $result['details']  = $this->Cardlist_model->get_card($get['cardid'], $get['campaignid']);
    $result['token']    = $data['csrf']['hash'];

    echo json_encode($result);
  }

  function editCardCampaign() {

    $data = array_merge($this->global_data);
    $get  = $this->input->post();
    $id   = $get['cardid'];

    $data['IpAddress'] = $this->input->ip_address();
    $data['OperatingSystem'] = $this->agent->platform();
    $data['Browser'] = $this->agent->browser();

    $this->form_validation->set_rules('editCardStatus', 'EditCardStatus', 'required');

    if ($get['editCardStatus'] == 2) {
      $this->form_validation->set_rules('inactiveCardDate', 'InactiveCardDate', 'required');
    }

    if ($get['editCardStatus'] == 3) {
      $this->form_validation->set_rules('extendCardDate', 'ExtendCardDate', 'required');
    }

    if($this->form_validation->run() == TRUE){

      $data['AppLoggerId'] = $get['loggerid'];

      if ($this->App_logger_model->update_app_logger_data($data))
      {
        if ($this->Cardlist_model->editCardCampaign($get, $id))
        {

          $cardname      = $this->EditCampaign_Model->get_cardname($get['cardid']);
          $currentstatus = $this->EditCampaign_Model->currentstatus($get['editCardStatus']);
          $campname      = $this->EditCampaign_Model->get_campname($get['campaignid']);

          $status     = true;
          $response   = "Card has been updated.";
          $errorcode  = 200;
          $actmsg     = " update Card Status to " .$currentstatus. " for " .$cardname. " in " .$campname;
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
        $actmsg    = " is trying to update Card Status ".$cardname.". Applogger update failed.";
      }
      $act = [
        'UserId'           => $data['UserId'],
        'ActivityTypeId'   => 8,
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
}
 ?>
