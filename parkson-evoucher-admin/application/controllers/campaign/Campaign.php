<?php
  class Campaign extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('user/User_Model');
      $this->load->model('campaign/Campaign_Model');
      $this->load->model('App_logger_model');
      $this->load->model('logs/ActivityLog_Model');

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

      $this->global_data['UserId']      = $this->session->userdata('UserId');
      $this->global_data['Fullname']    = $this->session->userdata('Fullname');
      $this->global_data['Role']        = $this->session->userdata('Role');

      $this->global_data['AppType']     = 2;
      $this->global_data['CreatedDate'] = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']   = $this->global_data['UserId'];
      $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
      // $this->output->enable_profiler(TRUE);
    }

    function index()
    {
       $data = array_merge($this->global_data);

       $data['header']  = $this->load->view('templates/main-header',"",true);
       $data['topbar']  = $this->load->view('templates/main-topbar',"",true);
       $data['sidebar'] = $this->load->view('templates/main-sidebar',"",true);
       $data['footer']  = $this->load->view('templates/main-footer',"",true);
       $data['bottom']  = $this->load->view('templates/main-bottom',"",true);

       //card top campaign
       $settingday      = $this->Campaign_Model->get_dateCampaign();
       $campaign_status = $this->Campaign_Model->get_status();

       $expireddate        = date('Y-m-d');
       $data['expirydate'] = date('Y-m-d', strtotime('+'.$settingday->CampaignSetDueDays.' days'));

       $data['cardcampaign']  = $this->Campaign_Model->get_cardCampaign($expireddate,$campaign_status);

       // echo "<pre>";
       // print_r($data['cardcampaign'] );die;
       // echo "</pre>";

       //create campaign
       $data['camptype']      = $this->Campaign_Model->get_camptype()->result();
       $data['vouchertype']   = $this->Campaign_Model->get_vouchertype()->result();
       $data['store']         = $this->Campaign_Model->get_store()->result();
       $data['redeemtype']    = $this->Campaign_Model->get_redeemtype()->result();
       $data['card']          = $this->Campaign_Model->get_card()->result();
       // print_r($data['card']  );

       $this->load->view('campaign/campaign_list', $data);
    }


    public function campaignlisting()
    {
      $data = array_merge($this->global_data);

      $post = $this->input->post();
      $list  = $this->Campaign_Model->get_datatables($post);
      $table = array();
      $no    = $post['start'];

      foreach ($list as $field) {

        $no++;
        $getuserid = $this->User_Model->getuserid($field->CreatedBy);
        $campaign  = $this->Campaign_Model->get_vouchertypename($field->CampaignId);

        $vouchertypename = '';
        foreach ($campaign as $row) {
          $vouchertypename .= $row->VoucherName.',';
        }

        if (isset($getuserid)) {
          $fullname = $getuserid->Fullname;
        }else {
          $fullname = '';
        }

        $row   = array();

        if ($field->EndDate != null) {
          $enddate = date('Y-m-d', strtotime($field->EndDate));
        }else {
          $enddate = '';
        }

        // $row[] = $no;
        $row[] = $field->CampaignId;
        $row[] = $field->CampaignName;
        $row[] = $vouchertypename;
        $row[] = date('Y-m-d', strtotime($field->StartDate));
        $row[] = $enddate;
        $row[] = $field->ExtendDate;
        $row[] = $fullname;               //createdby

        $row[] = '<span>
                    <span class="badge bg-label-'.$field->CampaignStatusColor.'"  style="margin-left:-15px">'.$field->Status.'</span>
                  </span>';

        $row[] = '<a href="'.base_url().'campaign/EditCampaign/editcampaign/'.$field->CampaignId.'">
                    <div class="d-inline-block- text-nowrap">
                    <button class="btn btn-sm btn-icon">
                    <i class="bx bx-edit"></i>
                    </button>
                    </div>
                  </a>';
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->Campaign_Model->count_all($post),
        "recordsFiltered"     => $this->Campaign_Model->count_filtered($post),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function create()
    {
      $data = array_merge($this->global_data);

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->browser();

      $get = $this->input->post();
      $result['get'] = $get;

      $this->form_validation->set_rules('camptype', 'camptype', 'required');
      $this->form_validation->set_rules('campaignname', 'campaignname', 'required');
      $this->form_validation->set_rules('vouchertype[]', 'voucher type', 'required');
      $this->form_validation->set_rules('store[]', 'store', 'required');
      $this->form_validation->set_rules('startDate', 'startDate', 'required');
      $this->form_validation->set_rules('endDate', 'endDate', 'required');
      $this->form_validation->set_rules('redeemtype', 'redeemtype', 'required');

      if ($get['camptype'] == 1) {
        $this->form_validation->set_rules('card[]', 'card', 'required');
      }

      if($this->form_validation->run() == TRUE){

        $showStoreError = '';
        if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data))
        {
          if ($campaignid = $this->Campaign_Model->insertCampaign($get))
          {
            $campname = $this->Campaign_Model->get_campname($campaignid);

            foreach ($get['store'] as $store => $val) {
              $checkStore = $this->Campaign_Model->storeCampaign_check($val);
              if ($checkStore != null) {
                $showStoreError .= 'Store '.$checkStore->StoreCode.' currently in active Campaign. Please deactivate current campaign for this store. <br>';
              }else {
                $this->Campaign_Model->storeCampaign($val,$campaignid,$get);
              }
            }

            if ($get['camptype'] == 1) {
              foreach ($get['card'] as $card => $val) {
                $this->Campaign_Model->cardCampaign($val,$campaignid,$get);
              }
            }

            foreach ($get['vouchertype'] as $vouchertype => $val) {
              $this->Campaign_Model->vouchertypeCampaign($val,$campaignid,$get);
            }

            $status     = true;
            $response   = "Campaign has been created.";
            $errorcode  = 200;
            $actmsg     = " create Campaign, ".$campname;
          } else {
            $status     = false;
            $response   = [
              "type"    => "authentication",
              "error"   => array('error' => 'Something went wrong. Please try again later.'),
            ];
            $errorcode  = 500;
            $actmsg     = " is trying to create Campaign, ".$campname.". Failed.";
          }
        } else
        {
          $status    = false;
          $response  = [
            "type"   => "authentication",
            "error"  => array('error' => 'Something went wrong. Please try again later.'),
          ];
          $errorcode = 500;
          $actmsg    = " is trying to create Campaign, ".$campname.". Applogger update failed.";
        }
        $act = [
          'UserId'           => $data['UserId'],
          'ActivityTypeId'   => 24,
          'ActivityDetails'  => $data['Fullname'].$actmsg,
          'ActiveDate'       => date('Y-m-d H:i:s'),
          'IpAddress'        => $data['IpAddress'],
          'OperatingSystem'  => $data['OperatingSystem'],
          'Browser'          => $data['Browser'],
        ];

        $this->ActivityLog_Model->insert_activity($act);
      }
      else {
        $showStoreError = '';
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
      $result['storeError']   = $showStoreError;

      echo json_encode($result);
    }
  }

 ?>
