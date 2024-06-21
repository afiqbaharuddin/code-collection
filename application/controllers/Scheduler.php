<?php
  /**
   *
   */
  class Scheduler extends CI_Controller
  {

    function __construct()
    {
      parent:: __construct();
      $this->load->model('scheduler_Model');
      $this->load->model('logs/ActivityLog_Model');
      $this->load->model('logs/VoucherLog_Model');
      $this->load->model('App_logger_model');

      //CSRF PROTECTION\\
      $this->global_data['csrf'] = [
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
      ];
      //CSRF PROTECTION\\

      $this->global_data['UserId']      = $this->session->userdata('UserId');
      $this->global_data['Fullname']    = $this->session->userdata('Fullname');
      $this->global_data['Role']        = $this->session->userdata('Role');

      $this->global_data['AppType']      = 2;
      $this->global_data['CreatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['CreatedBy']    = $this->global_data['UserId'];
      $this->global_data['UpdatedDate']  = date("Y-m-d H:i:s", time() );
      $this->global_data['UpdatedBy']    = $this->global_data['UserId'];
      $this->output->enable_profiler(TRUE);
    }

    //Store
    //expired card in card store
    function schedularExpired() {

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_EndCard();
      $card         = [];

      foreach ($checkExpired as $row) {
        if ($row->EndDate != null || $row->EndDate !="") {
          if ($today > date('Y-m-d', strtotime($row->EndDate))) {
            $card[] = [
              'CardStoreId' => $row->CardStoreId,
              'StatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Card Expiry for Card Store.";
      }

      if (!empty($card)) {
        $this->scheduler_Model->update_Card($card);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
      }

    //store close date -works
    function schedulerCloseStore(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today      = date('Y-m-d');
      $checkClose = $this->scheduler_Model->get_closeStore();
      $store      = [];

      foreach ($checkClose as $row) {
        if ($row->StoreInactiveDate != null || $row->StoreInactiveDate !="") {
          if ($today > date('Y-m-d', strtotime($row->StoreInactiveDate))) {
            $store[] = [
              'StoreId' => $row->StoreId,
              'StoreStatusId' => 2,
            ];
          }
          // print_r($store);die;
        }
        $actmsg = "Scheduler Update Closing Date for Store.";
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      if (!empty($store)) {
        $this->scheduler_Model->update_storestatus($store);
      }
      $this->ActivityLog_Model->insert_activity($act);
    }

//=================================================================================================================================

    //User
    //inactive user -works
    function schedulerInactiveUser(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_inactiveUser();
      $user         = [];

      foreach ($checkExpired as $row) {
        if ($row->InactiveDate != null || $row->InactiveDate != "") {
          if ($today <= date('Y-m-d', strtotime($row->InactiveDate))) {
            $user[] = [
              'UserId' => $row->UserId,
              'StatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Inactivity Date for User.";
      }

      if (!empty($user)) {
        $this->scheduler_Model->update_userstatus($user);
        $this->scheduler_Model->update_userstorestatus($user);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    // assign MOD
    function schedularStartMOD(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkStart   = $this->scheduler_Model->get_StartDateManager();
      $manager      = [];

      foreach ($checkStart as $row) {
        if ($row->StartDate != null || $row->StartDate != "") {
          if ($today == date('Y-m-d', strtotime($row->StartDate)) && ($row->StatusId == 4 || $row->StatusId == 5)) {
            $manager[] = [
              'ManagerDutyId' => $row->ManagerDutyId,
              'StatusId' => 4,
            ];
          }
        }
      }
      $actmsg = "Scheduler Activate Users as Manager on Duty.";

      if (!empty($manager)) {
        $this->scheduler_Model->update_MOD($manager);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    // assign MOD to user, user store table
    function updateMOD(){

      $status = $this->scheduler_Model->get_statusManager();

      $manager = [];

      foreach ($status as $row) {
        if ($row->StatusId != null || $row->StatusId != "") {
            if ($row->UserRoleId != 104) {

              $manager[] = [
                'UserId'     => $row->UserId,
                'UserRoleId' => 104,
              ];
              $this->scheduler_Model->update_User_MOD($manager);
              $this->scheduler_Model->update_UserStore_MOD($manager);
            }
        }
      }
    }

    //manager on duty expired
    function schedularExpiredMOD(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_EndDateManager();
      $manager      = [];

      foreach ($checkExpired as $row) {
        if ($row->EndDate != null || $row->EndDate != "") {
          if ($today > date('Y-m-d', strtotime($row->EndDate))) {
            $manager[] = [
              'ManagerDutyId' => $row->ManagerDutyId,
              'StatusId' => 3,
            ];
            $mgr[] = [
              'UserId'     => $row->UserId,
              'UserRoleId' => $row->UserRoleId,
            ];
            $this->scheduler_Model->updateToOldRoleUser($mgr);
            $this->scheduler_Model->updateToOldRoleStore($mgr);
          }
        }
      }
      $actmsg = "Scheduler Update Expiry Date for Manager on Duty.";

      if (!empty($manager)) {
        $this->scheduler_Model->update_EndDateManager($manager);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //manager on duty inactive to expired
    // function schedularInactiveExpiredMOD(){
    //
    //   $data['IpAddress']       = $this->input->ip_address();
    //   $data['OperatingSystem'] = $this->agent->platform();
    //   $data['Browser']         = $this->agent->version();
    //
    //   $today        = date('Y-m-d');
    //   $checkExpired = $this->scheduler_Model->get_EndDateManagerInactive();
    //   $manager      = [];
    //
    //   foreach ($checkExpired as $row) {
    //     if ($row->EndDate != null || $row->EndDate != "") {
    //       if ($today > date('Y-m-d', strtotime($row->EndDate))) {
    //         $manager[] = [
    //           'ManagerDutyId' => $row->ManagerDutyId,
    //           'StatusId' => 3,
    //         ];
    //       }
    //     }
    //   }
    //   $actmsg = "Scheduler Update Expiry Date for Inactive Status for Manager on Duty.";
    //
    //   if (!empty($manager)) {
    //     $this->scheduler_Model->update_EndDateManager($manager);
    //   }
    //
    //   $act = [
    //     'UserId'           => 2,
    //     'ActivityTypeId'   => 33,
    //     'ActivityDetails'  => $actmsg,
    //     'ActiveDate'       => date('Y-m-d H:i:s'),
    //     'IpAddress'        => $data['IpAddress'],
    //     'OperatingSystem'  => $data['OperatingSystem'],
    //     'Browser'          => $data['Browser'],
    //   ];
    //
    //   $this->ActivityLog_Model->insert_activity($act);
    // }

    //after MOD expired, user will update to their old Role
    //belum task scheduler -works
    // function updateCurrentRole(){
    //
    //   $data['IpAddress']       = $this->input->ip_address();
    //   $data['OperatingSystem'] = $this->agent->platform();
    //   $data['Browser']         = $this->agent->version();
    //
    //   $today        = date('Y-m-d');
    //   $status = $this->scheduler_Model->get_statusManager2();
    //   $manager = [];
    //
    //   foreach ($status as $row) {
    //     // if ($row->StatusId != null || $row->StatusId != "") {
    //       if ($today > date('Y-m-d', strtotime($row->EndDate))) {
    //
    //         $manager[] = [
    //           'UserId'     => $row->UserId,
    //           'UserRoleId' => $row->UserRoleId,
    //         ];
    //         $this->scheduler_Model->updateToOldRoleUser($manager);
    //         $this->scheduler_Model->updateToOldRoleStore($manager);
    //       }
    //     // }
    //     $actmsg = "Scheduler Update User in Manager on Duty to User default Role.";
    //   }
    //
    //   $act = [
    //     'UserId'           => 2,
    //     'ActivityTypeId'   => 33,
    //     'ActivityDetails'  => $actmsg,
    //     'ActiveDate'       => date('Y-m-d H:i:s'),
    //     'IpAddress'        => $data['IpAddress'],
    //     'OperatingSystem'  => $data['OperatingSystem'],
    //     'Browser'          => $data['Browser'],
    //   ];
    //
    //   $this->ActivityLog_Model->insert_activity($act);
    // }

    //VOUCHER
    //inactive date voucher type
    // function schedulerVoucherType(){
    //
    //   //$data = array_merge($this->global_data);
    //
    //   $get = $this->input->post();
    //
    //   $today = date('Y-m-d');
    //
    //   $checkInactive = $this->scheduler_Model->get_inactiveVoucherType();
    //   $voucherType = [];
    //
    //   foreach ($checkInactive as $row) {
    //     if ($today >= date('Y-m-d', strtotime($row->InactivateDate))) {
    //       $voucherType[] = [
    //         'VoucherTypeId' => $row->VoucherTypeId,
    //         'StatusId' => 3,
    //       ];
    //     }
    //   }
    //   $this->scheduler_Model->update_VoucherType($voucherType);
    // }


//==============================================================================================================================

    //INACTIVE GIFT VOUCHERS (Active to Inactive)
    function schedular_InactiveGift(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkActive  = $this->scheduler_Model->get_giftvouchers();
      $gift_voucher = [];

      foreach ($checkActive as $row) {
        if ($row->InactiveDate != null || $row->InactiveDate != "") {
          if ($today > date('Y-m-d', strtotime($row->InactiveDate))) {
            $gift_voucher[] = [
              'GiftVouchersId'  => $row->GiftVouchersId,
              'VoucherStatusId' => 9,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Inactive Status for Inactive Gift Vouchers.";


      if (!empty($gift_voucher)) {
        $this->scheduler_Model->update_giftvouchers($gift_voucher);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //edit voucher type inactive
    function schedularExpiredVoucherType(){


      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today        = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_statusVoucherType();
      $voucher      = [];

      foreach ($checkExpired as $row) {
        if ($row->InactivateDate != null || $row->InactivateDate != '') {
          if ($today > date('Y-m-d', strtotime($row->InactivateDate))) {
            $voucher[] = [
              'VoucherTypeId' => $row->VoucherTypeId,
              'StatusId'      => 3,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Voucher Type.";
      }

      if (!empty($voucher)) {
        $this->scheduler_Model->update_statusVoucherTyp($voucher);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //Extend VOUCHERS (Active to Extend)
    function schedulerExtendVouchers(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $checkActive = $this->scheduler_Model->get_vouchers();
      $vouchers    = [];

      foreach ($checkActive as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
            $vouchers[] = [
              'VoucherId'       => $row->VoucherId,
              'VoucherStatusId' => 3,
            ];
        }
      }
      $actmsg = "Scheduler Update Extend Date for Gift Vouchers.";

      if (!empty($vouchers)) {
        $this->scheduler_Model->update_vouchers($vouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //Extend GIFT Voucher (Active to Extend)
    function schedulerExtendGift(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $checkActive  = $this->scheduler_Model->get_statusActiveGift();
      $giftvouchers = [];

      foreach ($checkActive as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
            $giftvouchers[] = [
              'GiftVouchersId'  => $row->GiftVouchersId,
              'VoucherStatusId' => 3,
            ];
        }
      }
      $actmsg = "Scheduler Update Extend Date for Gift Vouchers.";

      if (!empty($giftvouchers)) {
        $this->scheduler_Model->update_statusGiftVouchers($giftvouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //vouchers expired
    function schedularExpiredVouchers(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_statusVouchers();
      $vouchers = [];

      foreach ($checkExpired as $row) {
        if ($row->ExpDate != null || $row->ExpDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExpDate))) {
            $vouchers[] = [
              'VoucherId' => $row->VoucherId,
              'VoucherStatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Vouchers.";
      }

      if (!empty($vouchers)) {
        $this->scheduler_Model->update_statusVouchers($vouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //vouchers block/cancel -works
    function schedularvoucher(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkdate = $this->scheduler_Model->get_vouchers();
      $vouchers = [];

      foreach ($checkdate as $row) {
        //block
        if ($row->BlockDate != null || $row->BlockDate != "") {
          if ($today > date('Y-m-d', strtotime($row->BlockDate))) {

            $vouchers[] = [
              'VoucherId'              => $row->VoucherId,
              'VoucherStatusId'        => 6,
            ];
          }
          //cancel
        }elseif ($row->CancelDate != null || $row->CancelDate != "") {
          if ($today > date('Y-m-d', strtotime($row->CancelDate))) {
            $vouchers[] = [
              'VoucherId'              => $row->VoucherId,
              'VoucherStatusId'        => 5,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Block and Cancel Date for Vouchers.";

      if (!empty($vouchers)) {
        $this->scheduler_Model->update_vouchers($vouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //gift vouchers block/cancel
    function schedular_giftvoucher(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkdate = $this->scheduler_Model->get_giftvouchers();
      $vouchers = [];

      foreach ($checkdate as $row) {
        //block
        if ($row->BlockDate != null || $row->BlockDate != "") {
          if ($today > date('Y-m-d', strtotime($row->BlockDate))) {

            $vouchers[] = [
              'GiftVouchersId'         => $row->GiftVouchersId,
              'VoucherStatusId'        => 6,
            ];
          }
          //cancel
        }elseif ($row->CancelDate != null || $row->CancelDate != "") {
          if ($today > date('Y-m-d', strtotime($row->CancelDate))) {
            $vouchers[] = [
              'GiftVouchersId'         => $row->GiftVouchersId,
              'VoucherStatusId'        => 5,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Block and Cancel Date for Gift Vouchers.";

      if (!empty($vouchers)) {
        $this->scheduler_Model->update_giftvouchers($vouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //gift Voucher expired
    function schedularExpiredGiftVouchers(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_statusGiftVouchers();
      $giftvouchers = [];

      foreach ($checkExpired as $row) {
        if ($row->ExpDate != null || $row->ExpDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExpDate))) {
            $giftvouchers[] = [
              'GiftVouchersId' => $row->GiftVouchersId,
              'VoucherStatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Gift Vouchers.";
      }

      if (!empty($giftvouchers)) {
        $this->scheduler_Model->update_statusGiftVouchers($giftvouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //expired extend Vouchers
    function schedularExpiredExtendVoucher(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_statusExtendVouchers();
      $vouchers = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExtendDate))) {
            $vouchers[] = [
              'VoucherId' => $row->VoucherId,
              'VoucherStatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Extend Vouchers.";
      }

      if (!empty($vouchers)) {
        $this->scheduler_Model->update_statusExtendVouchers($vouchers);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //expired extend gift Vouchers
    function schedularExpiredExtendGift(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_statusExtendGift();
      // echo "<pre>";
      // print_r($checkExtend);
      // echo "<pre>";
      $gift_voucher = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExtendDate))) {
            $gift_voucher[] = [
              'GiftVouchersId' => $row->GiftVouchersId,
              'VoucherStatusId' => 2,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Extend Gift Vouchers.";
      }

      if (!empty($gift_voucher)) {
        $this->scheduler_Model->update_statusExtendGift($gift_voucher);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

//======================================================================================================================================

    //CAMPAIGN
    //campaign expired
    function schedularExpiredCampaign(){

      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_statusCampaign();
      $campaign = [];

      foreach ($checkExpired as $row) {
        if ($row->EndDate != null || $row->EndDate != "") {
          if ($today > date('Y-m-d', strtotime($row->EndDate))) {
            $campaign[] = [
              'CampaignId' => $row->CampaignId,
              'CampaignStatusId' => 4,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Expiry Date for Campaign";

      if (!empty($campaign)) {
        $this->scheduler_Model->update_statusCampaign($campaign);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //campaign extend
    function schedularExtendCampaign(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_statusCampaignExtend();
      $campaign = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExtendDate))) {
            $campaign[] = [
                'CampaignId' => $row->CampaignId,
                'CampaignStatusId' => 3,
              ];
          }
        }
      }
      $actmsg = "Scheduler Update Extend Date for Campaign";

      if (!empty($campaign)) {
        $this->scheduler_Model->update_statusCampaignExtend($campaign);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);

    }

    //expired extend campaign
    function schedularExpiredExtendCampaign(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_statusExtendCampaign();
      $campaign = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today > date('Y-m-d', strtotime($row->ExtendDate))) {
            $campaign[] = [
              'CampaignId' => $row->CampaignId,
              'CampaignStatusId' => 4,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Expiry Date for Extend Campaign.";

      if (!empty($campaign)) {
        $this->scheduler_Model->update_statusExtendCampaign($campaign);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //Campaign Inactive
    function schedulerInactiveCampaign(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkInactive = $this->scheduler_Model->get_inactiveCampaign();
      $campaign = [];

      foreach ($checkInactive as $row) {
        if ($row->InactiveDate != null || $row->InactiveDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->InactiveDate))) {
            $campaign[] = [
              'CampaignId' => $row->CampaignId,
              'CampaignStatusId' => 2,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Inactivity Date for Campaign";

      if (!empty($campaign)) {
        $this->scheduler_Model->update_campaign($campaign);
        $this->ActivityLog_Model->insert_activity($act);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //campaign store
    //expired campaign store
    function schedulerExpiredCampaignStore(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_expiredCampaignStore();
      $campaignStore = [];

      foreach ($checkExpired as $row) {
        if ($row->EndDate != null || $row->EndDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->EndDate))) {
            $campaignStore[] = [
              'CampaignStoreId'  => $row->CampaignStoreId,
              'CampaignStatusId' => 4,
            ];
          }
        }
        $actmsg = "Scheduler Update Expiry Date for Campaign Store";
      }

      if (!empty($campaignStore)) {
        $this->scheduler_Model->update_expiredCampaignStore($campaignStore);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //extend campaign store
    function schedulerExtendCampaignStore(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_extendCampaignStore();
      $campaignStore = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->ExtendDate))) {
            $campaignStore[] = [
              'CampaignStoreId' => $row->CampaignStoreId,
              'CampaignStatusId' => 4,
            ];
          }
        }
        $actmsg = "Scheduler Update Extend Date for Campaign Store";
      }

      if (!empty($campaignStore)) {
        $this->scheduler_Model->update_extendCampaignStore($campaignStore);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //card campaign
    //expired campaign cards
    function schedulerExpiredCampaignCard(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExpired = $this->scheduler_Model->get_expiredCampaignCard();
      $campaignCard = [];

      foreach ($checkExpired as $row) {

        if ($row->EndDate != null || $row->EndDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->EndDate))) {
            $campaignCard[] = [
              'CardCampaignId' => $row->CardCampaignId,
              'CampaignStatusId' => 4,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Expiry Date for Campaign Cards ";

      if (!empty($campaignCard)) {
        $this->scheduler_Model->update_expiredCampaignCard($campaignCard);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    //extend campaign card
    function schedulerExtendCampaignCard(){

      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $checkExtend = $this->scheduler_Model->get_extendCampaignCard();
      $campaignCard = [];

      foreach ($checkExtend as $row) {
        if ($row->ExtendDate != null || $row->ExtendDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->ExtendDate))) {
            $campaignCard[] = [
              'CardCampaignId' => $row->CardCampaignId,
              'CampaignStatusId' => 4,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Extend Date for Campaign Cards ";

      if (!empty($campaignCard)) {
        $this->scheduler_Model->update_extendCampaignCard($campaignCard);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    function schedulerTerminateCampaignCard(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today = date('Y-m-d');
      $TerminateDate = $this->scheduler_Model->get_TerminateCampaignCard();
      $campaignCard = [];

      foreach ($checkTerminate as $row) {
        if ($row->TerminateDate != null || $row->TerminateDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->TerminateDate))) {
            $campaignCard[] = [
              'CardCampaignId' => $row->CardCampaignId,
              'CampaignStatusId' => 5,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Terminate Date for Campaign Cards ";

      if (!empty($campaignCard)) {
        $this->scheduler_Model->update_terminateCampaignCard($campaignCard);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

    function schedulerTerminateCampaignStore(){
      $data['IpAddress']       = $this->input->ip_address();
      $data['OperatingSystem'] = $this->agent->platform();
      $data['Browser']         = $this->agent->version();

      $today         = date('Y-m-d');
      $TerminateDate = $this->scheduler_Model->get_TerminateCampaignStore();
      $campaignStore = [];

      foreach ($checkTerminate as $row) {
        if ($row->TerminateDate != null || $row->TerminateDate != "") {
          if ($today >= date('Y-m-d', strtotime($row->TerminateDate))) {
            $campaignStore[] = [
              'CardCampaignId'   => $row->CardCampaignId,
              'CampaignStatusId' => 5,
            ];
          }
        }
      }
      $actmsg = "Scheduler Update Terminate Date for Campaign Store ";

      if (!empty($campaignStore)) {
        $this->scheduler_Model->update_terminateCampaignStore($campaignStore);
      }

      $act = [
        'UserId'           => 2,
        'ActivityTypeId'   => 33,
        'ActivityDetails'  => $actmsg,
        'ActiveDate'       => date('Y-m-d H:i:s'),
        'IpAddress'        => $data['IpAddress'],
        'OperatingSystem'  => $data['OperatingSystem'],
        'Browser'          => $data['Browser'],
      ];

      $this->ActivityLog_Model->insert_activity($act);
    }

//====================================================================================================================
//Import Gift Voucher by Scheduler

function schedulerImportGiftVoucher(){
  $pendingImport  = $this->scheduler_Model->get_pendingImport();

  foreach ($pendingImport as $import) {
    $scheduledTime      = strtotime($import->ScheduledImportTime);
    $currentTime        = time();
    $scheduledTimePlus  = $scheduledTime + (5 * 60); // 5 minutes after scheduled time
    $scheduledTimeMinus = $scheduledTime - (5 * 60); // 5 minutes before scheduled time

    if ($currentTime >= $scheduledTimeMinus && $currentTime <= $scheduledTimePlus) {
      if ($import->ImportStatusId == 2) {
        $this->copyData($import);
      }
    }
  }
}

function copyData($import) {
  $data = array_merge($this->global_data);

  $data['IpAddress']       = $this->input->ip_address();
  $data['OperatingSystem'] = $this->agent->platform();
  $data['Browser']         = $this->agent->version();

  $csvFileId       = $import->CSVFileId;
  $pendingVouchers = $this->scheduler_Model->get_pending_vouchers_by_csv_file_id($csvFileId);
  $giftVoucherData = [];
  $error_log       = array();
  $errorCount      = 0;
  $actmsg          = '';
  $csvfile         = true;
  $voucherno       = [];
  $storeid         = $this->scheduler_Model->getStoreId();

  if ($get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data)) {

    foreach ($pendingVouchers as $pending) {
      $voucherno[] = $pending->VouchersNumber;
    }
    $existingVoucherNumber = $this->scheduler_Model->get_giftvoucherno($voucherno);

    foreach ($pendingVouchers as $pending) {

      if (in_array($pending->VouchersNumber,$existingVoucherNumber)) {
        $error_log[] = array(
          'CSVFileId'     => $import->CSVFileId,
          'VoucherNumber' => $pending->VouchersNumber,
          'ImportDate'    => date('Y-m-d H:i:s'),
          'ErrorMessage'  => 'Voucher already exist. The Batch Number is ' .$pending->BatchNumber.'.'
        );
        $csvfile = false;
        $actmsg  = " Scheduler is trying to Import Existing Gift Voucher. Failed.";
        $errorCount++;
        continue;
      }

      $searchFor = $pending->StoreId;
      $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
        return isset($element->StoreId) && $element->StoreId == $searchFor;
      }));

      $searchFor   = $pending->RedeemStore;
      $redeemStore = array_values(array_filter($storeid, function($element) use($searchFor){
        return isset($element->StoreCode) && $element->StoreCode == $searchFor;
      }));

      if (!empty($storecode)) {
        if ($pending->BatchNumber == null) {
          $error_log[] = array(
            'CSVFileId'     => $import->CSVFileId,
            'VoucherNumber' => $pending->VouchersNumber,
            'ImportDate'    => date('Y-m-d H:i:s'),
            'ErrorMessage'  => 'Batch Number cannot be Empty!'
          );
          $csvfile  = false;
          $actmsg   = " Scheduler is trying to Import Gift Voucher without Batch Number. Failed.";
          $errorCount++;
        } else {
          if ($pending->VoucherValueGift != 0 || $pending->VoucherValueGift != "0" || $pending->VoucherValueGift != "0.0") {
            if (empty($pending->RedeemStore) || !empty($redeemStore)) {
              if (empty($pending->RedeemStore) || $redeemStore[0]->StoreStatusId == 1) {
                $giftVoucherData[] = array(
                  'StoreId'            => $storecode[0]->StoreId,
                  'IssuedDate'         => $pending->IssuedDate,
                  'VouchersNumber'     => $pending->VouchersNumber,
                  'VoucherValueGift'   => $pending->VoucherValueGift,
                  'RedeemStore'        => $pending->RedeemStore,
                  'ExpDate'            => $pending->ExpDate,
                  'BatchNumber'        => $pending->BatchNumber,
                  'VoucherStatusId'    => $pending->VoucherStatusId,
                  'VoucherTypeId'      => $pending->VoucherTypeId,
                  'VoucherIssuanceId'  => $pending->VoucherIssuanceId,
                  'AppLoggerId'        => $get['loggerid'],
                );
                $importActivity[] = $pending->VouchersNumber;
              }else {
                $error_log[] = array(
                  'CSVFileId'     => $import->CSVFileId,
                  'VoucherNumber' => $pending->VouchersNumber,
                  'ImportDate'    => date('Y-m-d H:i:s'),
                  'ErrorMessage'  => 'Redeem store not active!'
                );
                $csvfile  = false;
                $actmsg   = " Scheduler is trying to Import Gift Voucher. Inactive Redeem Store.";
                $errorCount++;
              }
            }else {
              $error_log[] = array(
                'CSVFileId'     => $import->CSVFileId,
                'VoucherNumber' => $pending->VouchersNumber,
                'ImportDate'    => date('Y-m-d H:i:s'),
                'ErrorMessage'  => 'Redeem store does not exist!'
              );
              $csvfile  = false;
              $actmsg   = " Scheduler is trying to Import Gift Voucher. Redeem Store not Exist.";
              $errorCount++;
            }
          } else {
            $error_log[] = array(
              'CSVFileId'     => $import->CSVFileId,
              'VoucherNumber' => $pending->VouchersNumber,
              'ImportDate'    => date('Y-m-d H:i:s'),
              'ErrorMessage'  => 'Not allowed to import with Voucher Amount 0.'
            );
            $csvfile = false;
            $actmsg  = " Scheduler is trying to Import Vouchers with Amount RM 0. Failed.";
            $errorCount++;
          }
        }
      } else {
        $error_log[] = array(
          'CSVFileId'     => $import->CSVFileId,
          'VoucherNumber' => $pending->VouchersNumber,
          'ImportDate'    => date('Y-m-d H:i:s'),
          'ErrorMessage'  => 'Store not exist!'
        );
        $csvfile = false;
        $actmsg  = " Scheduler is trying to Import Gift Voucher. Store not Exist.";
        $errorCount++;
      }
    }
  }

  // Insert the data into the gift_voucher table
  if (!empty($giftVoucherData)) {
   $actmsg  = "Scheduler Import Gift Voucher CSV File.";
   $this->scheduler_Model->insert_gift_voucher($giftVoucherData);
  }

  //update error count to import_csvFile table.
  $this->scheduler_Model->updateImportError($import->CSVFileId,$errorCount);

  // Update the import status to 1 (completed) in the scheduler import table
  $this->scheduler_Model->updateImportStatus($import->CSVFileId);
  $this->scheduler_Model->deleteData($csvFileId);

  if (!empty($error_log)) {
    $this->scheduler_Model->schedulerImportErrorLog($import->CSVFileId,$error_log);
  }

  $act = [
    'UserId'           => 2,
    'ActivityTypeId'   => 33,
    'ActivityDetails'  => $actmsg,
    'ActiveDate'       => date('Y-m-d H:i:s'),
    'IpAddress'        => $data['IpAddress'],
    'OperatingSystem'  => $data['OperatingSystem'],
    'Browser'          => $data['Browser'],
  ];

  $this->ActivityLog_Model->insert_activity($act);

  if (!empty($importActivity)) {
    $act2 = [];
    foreach ($importActivity as $voucherNumber) {
      $act2[] = [
        'UserId'                      => $data['UserId'],
        'VoucherActivityCategoryId'   => 14,
        'Details'                     => 'Scheduler import Gift Voucher '.$voucherNumber,
        'ActivityDate'                => date('Y-m-d H:i:s'),
        // 'VouchersId'                  => $voucherMapping[$voucherNumber],
        'VouchersNumber'              => $voucherNumber,
        'VoucherTypeId'               => 1,
        'Source'                      => 2,
      ];
    }
    if (!empty($act2)) {
      $this->VoucherLog_Model->insert_batch_activity($act2);
    }
  }

  $result['csv']          = $csvfile;
  $result['token']        = $data['csrf']['hash'];
  echo json_encode($result);
}


// function importCSVFile($import){
//   $data = array_merge($this->global_data);
//
//   $filename = $import->CSVFileUrl;
//   $handle   = fopen($filename,"r");
//
//   $data['IpAddress']       = $this->input->ip_address();
//   $data['OperatingSystem'] = $this->agent->platform();
//   $data['Browser']         = $this->agent->browser();
//
//   $data['UserId'] = $this->session->userdata('UserId');
//
//   $actmsg     = '';
//   $error_log  = array();
//   $csvfile    = true;
//   $errorCount = 0;
//
//   $vouchernumber    = $this->scheduler_Model->getGiftVoucherNumber();
//   $storeid          = $this->scheduler_Model->getStoreId();
//   $voucherDataBatch = array();
//
//   if ($handle) {
//     $row_num = 0;
//     while (($row = fgetcsv($handle,1000,","))!== FALSE) {
//       $row_num++;
//       if ($row_num > 2) {
//         if (count($row) >= 8) {
//           $searchFor = $row[2];
//           $gvNumber = array_values(array_filter($vouchernumber, function($element) use($searchFor){
//             return isset($element->VouchersNumber) && $element->VouchersNumber == $searchFor;
//           }));
//
//           $searchFor = $row[0];
//           $storecode = array_values(array_filter($storeid, function($element) use($searchFor){
//             return isset($element->StoreCode) && $element->StoreCode == $searchFor;
//           }));
//
//           $searchFor = $row[4];
//           $redeemStore = array_values(array_filter($this->scheduler_Model->getStoreId(), function($element) use($searchFor){
//             return isset($element->StoreCode) && $element->StoreCode == $searchFor;
//           }));
//
//           $loggerid = $this->App_logger_model->insert_app_logger_data($data);
//
//           if ($loggerid) {
//             if (!empty($storecode))
//             {
//               if ($storecode[0]->StoreStatusId == 1)
//               {
//                 if (!empty($gvNumber))
//                 {
//                   $error_log[] = array(
//                     'CSVFileId' => $import->CSVFileId,
//                     'VoucherNumber' => $row[2],
//                     'ImportDate'    => date('Y-m-d H:i:s'),
//                     'ErrorMessage'  => 'Voucher already exist. Please key in Action to update this voucher.'
//                   );
//                   $csvfile = false;
//                   $actmsg  = " is trying to Import Existing Gift Voucher. Failed.";
//                   $errorCount++;
//                 }elseif ($row[6] == null) {
//                   $error_log[] = array(
//                     'CSVFileId'     => $import->CSVFileId,
//                     'VoucherNumber' => $row[2],
//                     'ImportDate'    => date('Y-m-d H:i:s'),
//                     'ErrorMessage'  => 'Batch Number cannot be Empty!'
//                   );
//                   $csvfile  = false;
//                   $actmsg   = " is trying to Import Gift Voucher without Batch Number. Failed.";
//                   $errorCount++;
//                 }else {
//                   if ($row[3]!= 0 || $row[3]!= "0" || $row[3]!= "0.0")
//                   {
//                     if (isset($redeemStore[0])) {
//                       $redeemStore = $redeemStore[0]->StoreCode;
//                     }else {
//                       $redeemStore = '';
//                     }
//
//                     if ($row[5]!= null) {
//                       $voucherData = array(
//                         'StoreId'            => $storecode[0]->StoreId,
//                         'IssuedDate'         => $this->convertDateFormat($row[1]),
//                         'VouchersNumber'     => $row[2],
//                         'VoucherValueGift'   => $row[3],
//                         'RedeemStore'        => $redeemStore,
//                         'ExpDate'            => $this->convertDateFormat($row[5]),
//                         'BatchNumber'        => $row[6],
//                         'VoucherStatusId'    => 1,
//                         'VoucherTypeId'      => 1,
//                         'VoucherIssuanceId'  => 0,
//                         'AppLoggerId'        => $loggerid,
//                       );
//                     }else {
//                       $voucherData = array(
//                         'StoreId'            => $storecode[0]->StoreId,
//                         'IssuedDate'         => $this->convertDateFormat($row[1]),
//                         'VouchersNumber'     => $row[2],
//                         'VoucherIssuanceId'  => 0,
//                         'VoucherValueGift'   => $row[3],
//                         'RedeemStore'        => $redeemStore,
//                         'BatchNumber'        => $row[6],
//                         'VoucherStatusId'    => 10,
//                         'VoucherTypeId'      => 1,
//                         'AppLoggerId'        => $loggerid,
//                       );
//                     }
//                     $voucherDataBatch[] = $voucherData;
//                   }else {
//                     $error_log[] = array(
//                       'CSVFileId' => $import->CSVFileId,
//                       'VoucherNumber' => $row[2],
//                       'ImportDate'    => date('Y-m-d H:i:s'),
//                       'ErrorMessage'  => 'Not allowed to import with Voucher Amount 0.'
//                     );
//                     $csvfile = false;
//                     $actmsg  = " is trying to Import Vouchers with Amount RM 0. Failed.";
//                     $errorCount++;
//                   }
//                 }
//               }
//               else
//               {
//                 $error_log[] = array(
//                   'CSVFileId' => $import->CSVFileId,
//                   'VoucherNumber' => $row[2],
//                   'ImportDate'    => date('Y-m-d H:i:s'),
//                   'ErrorMessage'  => 'Store not active'
//                 );
//                 $csvfile = false;
//                 $actmsg  = " is trying to Import Gift Voucher. Inactive Store.";
//                 $errorCount++;
//               }
//             }
//             else
//             {
//               $error_log[] = array(
//                 'CSVFileId'     => $import->CSVFileId,
//                 'VoucherNumber' => $row[2],
//                 'ImportDate'    => date('Y-m-d H:i:s'),
//                 'ErrorMessage'  => 'Store not exist!'
//               );
//               $csvfile = false;
//               $actmsg  = " is trying to Import Gift Voucher. Store not Exist.";
//               $errorCount++;
//             }
//           }
//         }
//       }
//     }
//     fclose($handle);
//   }
//
//   //insert gv data to gv table using batch insert
//   if (!empty($voucherDataBatch)) {
//     $this->scheduler_Model->insertImportGift($voucherDataBatch);
//   }
//
//   //update error count to import_csvFile table.
//   $this->scheduler_Model->updateImportError($import->CSVFileId,$errorCount);
//
//   //Update import status
//   $this->scheduler_Model->updateImportStatus($import->CSVFileId);
//
//   if (!empty($error_log)) {
//     $this->db->insert_batch('scheduler_import_errorlog',$error_log);
//   }
//
//   $act = [
//     'UserId'           => 2,
//     'ActivityTypeId'   => 33,
//     'ActivityDetails'  => $actmsg,
//     'ActiveDate'       => date('Y-m-d H:i:s'),
//     'IpAddress'        => $data['IpAddress'],
//     'OperatingSystem'  => $data['OperatingSystem'],
//     'Browser'          => $data['Browser'],
//     ];
//
//     $this->ActivityLog_Model->insert_activity($act);
//
//   $result['csv']          = $csvfile;
//   $result['token']        = $data['csrf']['hash'];
//   echo json_encode($result);
// }

function convertDateFormat($date) {
  if (strlen($date) > 0) {
    $dateParts = explode("/", $date);
    if (count($dateParts) == 3) {
      $day   = $dateParts[0];
      $month = $dateParts[1];
      $year  = '20'. $dateParts[2];
      return "$year-$month-$day";
    } else {
      return null;
    }
  } else {
    return date('Y-m-d');
  }
}

//====================================================================================================================
//Manual Scheduler

    function manual_user(){
      $this->schedulerInactiveUser();
    }

    function manual_mod(){
      $this->schedularStartMOD();
      $this->updateMOD();
      $this->schedularExpiredMOD();
    }

    function manual_store(){
      $this->schedularExpired();
      $this->schedulerCloseStore();
    }

    function manual_vouchers(){
      $this->schedulerExtendVouchers();
      $this->schedularExpiredVoucherType();
      $this->schedularExpiredVouchers();
      $this->schedularExpiredExtendVoucher();
      $this->schedularvoucher();
    }

    function manual_gift(){
      $this->schedulerExtendGift();
      $this->schedular_InactiveGift();
      $this->schedularExpiredGiftVouchers();
      $this->schedular_giftvoucher();
      $this->schedularExpiredExtendGift();
    }

    function manual_gift_import(){
      $this->schedulerImportGiftVoucher();
    }

    function manual_campaign(){
      $this->schedularExpiredCampaign();
      $this->schedularExtendCampaign();
      $this->schedulerInactiveCampaign();
      $this->schedulerExpiredCampaignStore();
      $this->schedulerExtendCampaignStore();
      $this->schedulerExpiredCampaignCard();
      $this->schedulerExtendCampaignCard();
      $this->schedularExpiredExtendCampaign();
      $this->schedulerTerminateCampaignCard();
      $this->schedulerTerminateCampaignStore();
    }

    // Bulk delete function
    public function deletegv() {
      $this->delete();
    }

    // Function to delete multiple IDs
    public function delete() {
      $this->scheduler_Model->deletegv();
    }
  }
 ?>
