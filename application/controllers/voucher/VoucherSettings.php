<?php

  class VoucherSettings extends CI_Controller
  {

    public function __construct() {
  		parent::__construct();
      $this->load->model('voucher/settingStore_Model');
      $this->load->model('voucher/settingUser_Model');
      $this->load->model('App_logger_model');

      if ($this->session->userdata('is_logged_in') == false) {
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

    //voucher setting page
    function index()
    {
      $data = array_merge($this->global_data);

      $data['header']=$this->load->view('templates/main-header',"",true);
      $data['topbar']=$this->load->view('templates/main-topbar',"",true);
      $data['sidebar']=$this->load->view('templates/main-sidebar',"",true);
      $data['footer']=$this->load->view('templates/main-footer',"",true);
      $data['bottom']=$this->load->view('templates/main-bottom',"",true);

      $this->load->view('voucher/voucher_settings', $data);
    }

    //store voucher setting function
    public function storevoucherlisting(){
      $data = array_merge($this->global_data);

      $post     = $this->input->post();
      $list     = $this->settingStore_Model->get_datatables($post);
      $allStore = $this->settingStore_Model->get_datastore();

      $permission = $this->settingStore_Model->get_permission($this->session->userdata('UserId'));
      $userid     = $this->settingStore_Model->get_userStore($this->session->userdata('UserId'));


      $table = array();
      $no    = $post['start'];
      $k     = 1;

      $row   = array();
      // $row[] = $no;

      if ($permission->UserRoleId == 1 || $permission->UserId == 1) {
        $row[] = '<b>All Store<b>';

        $row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid extend-switch-on reprintCheckAll onchangeAll" name="reprintCheckAll" id="reprintCheckAll" />
                    <span class="switch-toggle-slider">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>
                  <input type="text" class="extend-number-fill reprintNumAll none"  name="reprintNumAll" id="reprintNumAll" style="width:60px; margin-left:30px"></input>
                  <input type="hidden" name="reprintclick" id="reprintclick" value="0"></input>';

        $row[] = '<label class="switch">
                    <input type="checkbox"  name="issuanceCheckAll" id="issuanceCheckAll" class=" onchangeAll switch-input is-valid issuanceCheckAll" />
                      <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                    </label>
                    <input type="hidden" name="issuanceclick" id="issuanceclick" value="0"></input>
                    <input class="issuanceNumAll1 onchangeAll1 none" name="issuanceNumAll" id="issuanceNumAll1" type="date" style="width:130px; margin-left:40px; margin-right:10px; border:none" value="'.date('Y-m-d').'"></input>
                    <span> to </span>
                    <input class="issuanceNumAll2 onchangeAll2 none" name="issuanceNumAll" id="issuanceNumAll2" type="date" style="width:130px; margin-left:10px; border:none" value="'.date('Y-m-d').'"></input>
                  </label>';
      }

      $table[] = $row;

      foreach ($list as $field) {
        $no++;

        $checked = $this->settingStore_Model->get_checkedReprint($field->StoreId);

        if (isset($checked)) {
          if ($checked->NumReprintCheck == 1) {
            $checkReprint ="checked";
            $showReprint ="";
            $numreprint  = $checked->NumReprint;
          }
          else
          {
            $checkReprint ="";
            $showReprint ="none";
            $numreprint  = 0;
          }
        }
        else
        {
          $checkReprint ="";
          $showReprint ="none";
          $numreprint  = 0;
        }

        $checkedd = $this->settingStore_Model->get_checkedIssuance($field->StoreId);

        if (isset($checkedd)) {
          if ($checkedd->StoreVoucherIssuanceCheck == 1) {
            $checkIssuance ="checked";
            $showIssuance1 ="";
            $showIssuance2 ="";
            $numissuancedate1 = $checkedd->StoreVoucherIssuanceDate;
            $numissuancedate2 = $checkedd->StoreVoucherIssuanceDateEnd;
          }
          else
          {
            $checkIssuance ="";
            $showIssuance1 ="none";
            $showIssuance2 ="none";
            $numissuancedate1 = date('Y-m-d');
            $numissuancedate2 = '';
          }
        }
        else
        {
          $checkIssuance ="";
          $showIssuance1 ="none";
          $showIssuance2 ="none";
          $numissuancedate1 = date('Y-m-d');
          $numissuancedate2 = '';
        }

        $row   = array();
  			// $row[] = $no;
        $row[] = $field->StoreName;

        if ($permission->UserRoleId != 1 ) {
          if ($permission->StoreId == $field->StoreId) {

              $row[] = ' <label class="switch">
                          <input type="checkbox" class="switch-input is-valid extend-switch-on onchangeInd reprintCheck " name="reprintCheck" id="reprintCheck_'.$k.'" data-num="'.$k.'" data-type="reprint" data-storeid="'.$field->StoreId.'" '.$checkReprint.' />
                          <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                          </span>
                        </label>
                        <input type="text" class="extend-number-fill reprintNum '.$showReprint.'" data-storeid="'.$field->StoreId.'" name="reprintNum" id="reprintNum_'.$k.'" data-num="'.$k.'" style="width:60px; margin-left:30px" value="'.$numreprint.'" ></input>';

              $row[] = '<label class="switch">
                          <input type="checkbox" data-storeid="'.$field->StoreId.'" name="issuanceCheck" class="onchangeInd switch-input is-valid issuanceCheck" data-type="issuance" id="issuanceCheck_'.$k.'" data-num="'.$k.'" '.$checkIssuance.' />
                            <span class="switch-toggle-slider">
                              <span class="switch-on"></span>
                              <span class="switch-off"></span>
                            </span>
                          </label>
                          <input class="issuanceNum '.$showIssuance1.'" data-num="'.$k.'" name="issuanceNum" id="issuanceNum1_'.$k.'"  data-storeid="'.$field->StoreId.'" type="date" style="width:130px; margin-left:40px; border:none" value="'.$numissuancedate1.'"/>
                          <span> to </span>
                          <input class="issuanceNum '.$showIssuance2.'" data-num="'.$k.'" name="issuanceNum" id="issuanceNum2_'.$k.'"  data-storeid="'.$field->StoreId.'" type="date" style="width:130px; margin-left:40px; border:none" value="'.$numissuancedate2.'"/>

                        </label>';
          }
        }else {
          $row[] = ' <label class="switch">
                      <input type="checkbox" class="switch-input is-valid extend-switch-on onchangeInd reprintCheck " name="reprintCheck" id="reprintCheck_'.$k.'" data-num="'.$k.'" data-type="reprint" data-storeid="'.$field->StoreId.'" '.$checkReprint.' />
                      <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                    </label>
                    <input type="text" class="extend-number-fill reprintNum '.$showReprint.'" data-storeid="'.$field->StoreId.'" name="reprintNum" id="reprintNum_'.$k.'" data-num="'.$k.'" style="width:60px; margin-left:30px" value="'.$numreprint.'" ></input>';

          $row[] = '<label class="switch">
                      <input type="checkbox" data-storeid="'.$field->StoreId.'" name="issuanceCheck" class="onchangeInd switch-input is-valid issuanceCheck" data-type="issuance" id="issuanceCheck_'.$k.'" data-num="'.$k.'" '.$checkIssuance.' />
                        <span class="switch-toggle-slider">
                          <span class="switch-on"></span>
                          <span class="switch-off"></span>
                        </span>
                      </label>
                      <input class="dateissuance1 issuanceNum1 '.$showIssuance1.'" data-num="'.$k.'" name="issuanceNum" id="issuanceNum1_'.$k.'"  data-storeid="'.$field->StoreId.'" style="width:130px; margin-left:40px; margin-right:10px; border:none" value="'.$numissuancedate1.'"/>
                      <span> to </span>
                      <input class="dateissuance2 issuanceNum2 '.$showIssuance2.'" data-num="'.$k.'" name="issuanceNum" id="issuanceNum2_'.$k.'"  data-storeid="'.$field->StoreId.'" style="width:130px; margin-left:10px; border:none" value="'.$numissuancedate2.'"/>
                    </label>';
        }

        $k++;
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->settingStore_Model->count_all($post),
        "recordsFiltered"     => $this->settingStore_Model->count_filtered($post),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function storesetting_all(){

      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $status    = true;//allow

        if ($get['checkreprint'] == 1) {
          if ($get['valuereprint'] == "" || $get['valuereprint'] == 0) { //bukak toggle tapi tak input value
            $status    = false;
            $response  = 'Reprint value must be bigger than 0';
          }
        }

        if ($get['checkissue'] == 1) {
            if ($get['valueissue'] == "" ) {  //only required for star date
              $status    = false;
              $response  = 'Issuance date is required';
            }
          }

      if ($status == true) {
        $status           = array('StatusId' => 2);
        $this->settingStore_Model->changeStatus($status);

        $allstore = $this->settingStore_Model->getAllStore();

          foreach ($allstore as $store) {

            $checkstore = $this->settingStore_Model->check_store_setting($store->StoreId);

            if (!isset($checkstore)) {
              $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data); //wajib letak applogger for create

              if (isset($get['reprintclick']) && $get['reprintclick'] == 1) {
                $insert = array(
                              'StoreId'                   => $store->StoreId,
                              'NumReprintCheck'           => $get['checkreprint'],
                              'NumReprint'                => $get['valuereprint'],//beza user put date on voucher issuance, so it will be update
                              'AppLoggerId'               => $get['loggerid'],
                              'StatusId'                  => 1,
                         );
                $this->settingStore_Model->storesetting_all($insert);
              }

              if (isset($get['issuanceclick']) && $get['issuanceclick'] == 1) {
                $insert = array(
                              'StoreId'                      => $store->StoreId,
                              'StoreVoucherIssuanceCheck'    => $get['checkissue'],
                              'StoreVoucherIssuanceDate'     => $get['valueissue'], //beza user put date on voucher issuance, so it will be update
                              'StoreVoucherIssuanceDateEnd'  => $get['valueissue2'],
                              'AppLoggerId'                  => $get['loggerid'],
                              'StatusId'                     => 1,
                         );
                $this->settingStore_Model->storesetting_all($insert);
              }
            }else {

              if (isset($get['issuanceclick']) && $get['issuanceclick'] == 1) {
                $update = array(
                              'StoreId'                      => $store->StoreId,
                              'StoreVoucherIssuanceCheck'    => $get['checkissue'],
                              'StoreVoucherIssuanceDate'     => $get['valueissue'], //beza user put date on voucher issuance, so it will be update
                              'StoreVoucherIssuanceDateEnd'  => $get['valueissue2'],
                              'StatusId'                     => 1,
                         );
                $this->settingStore_Model->storesetting_all_update($update);
              }

              if (isset($get['reprintclick']) && $get['reprintclick'] == 1) {
                $update = array(
                              'StoreId'                   => $store->StoreId,
                              'NumReprintCheck'           => $get['checkreprint'],
                              'NumReprint'                => $get['valuereprint'],//beza user put date on voucher issuance, so it will be update
                              'StatusId'                  => 1,
                         );
                $this->settingStore_Model->storesetting_all_update($update);
              }
            }
          }
          $status    = true;
          $response  = "Store Voucher Settings succesfully saved.";
      }
      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    function storesetting_individu(){

      $data = array_merge($this->global_data);
      $get  = $this->input->post();

      $status    = true;//allow

      if ($get['checkreprint'] == 1) {
        if ($get['valuereprint'] == "" || $get['valuereprint'] == 0) {
          $status    = false;
          $response  = 'Reprint value must be bigger than 0';
        }
      }elseif ($get['checkissue'] == 1) {
        if ($get['valueissue'] == "") {
          $status    = false;
          $response  = 'Issuance date is required';
        }
      }

      if ($status == true) {
        $status           = [
          'NumReprintCheck'             => $get['checkreprint'],
          'NumReprint'                  => $get['valuereprint'],
          'StoreVoucherIssuanceCheck'   => $get['checkissue'],
          'StoreVoucherIssuanceDate'    => $get['valueissue'],
          'StoreVoucherIssuanceDateEnd' => $get['valueissue2'],
        ];

        $checkstore = $this->settingStore_Model->check_store_setting($get['storeid']);

        if (isset($checkstore)) {
          $this->settingStore_Model->changeStatus_ind($status,$get['storeid']);
        } else {
          $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data);//wajib letak applogger for create

            $insert = array(
                          'StoreId'                      => $get['storeid'],
                          'NumReprintCheck'              => $get['checkreprint'],
                          'NumReprint'                   => $get['valuereprint'],
                          'StoreVoucherIssuanceCheck'    => $get['checkissue'],
                          'StoreVoucherIssuanceDate'     => $get['valueissue'],//user fill all row
                          'StoreVoucherIssuanceDateEnd'  => $get['valueissue2'],
                          'AppLoggerId'                  => $get['loggerid'],
                          'StatusId'                     => 1,
                     );

          $this->settingStore_Model->storesetting_all($insert);
        }

        $status    = true;
        $response  = "Store Voucher Settings succesfully saved.";
      }
      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }


    //user voucher setting function
    public function uservoucherlisting(){
      $data = array_merge($this->global_data);
      $permission = $this->settingStore_Model->get_permission($this->session->userdata('UserId'));
      $userid     = $this->settingStore_Model->get_userStore($this->session->userdata('UserId'));

      $post = $this->input->post();
      $list  = $this->settingUser_Model->get_datatables($post,$userid, $permission->StoreId);
      $table = array();
      $no    = $post['start'];
      $n = 1;


      $row   = array();

      if ($permission->UserRoleId ==1) {

        $row[] = '<b>All User<b>';
        $row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid extend-switch-on allNumExtendCheckAll onchangeAll" id="allNumExtendCheckAll" name="allNumExtendCheckAll" />
                    <span class="switch-toggle-slider">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>
                  <input class="onchangeAll extend-number-fill allNumExtendAll none" type="text" id="allNumExtendAll" name="allNumExtendAll"  style="width:60px; margin-left:30px"></input>';

        $row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid block-switch-on onchangeAll allBlockersCheck" id="allBlockersCheck" name="allBlockersCheck" />
                    <span class="switch-toggle-slider" style="margin-left:10px;">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>';

        $row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid onchangeAll allUnblockersCheck" name="allUnblockersCheck"  id="allUnblockersCheck" />
                    <span class="switch-toggle-slider" style="margin-left:10px;">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>';

        $row[] = '  <label class="switch">
                      <input type="checkbox" class="switch-input is-valid onchangeAll allCancelCheck" name="allCancelCheck" id="allCancelCheck" />
                      <span class="switch-toggle-slider" style="margin-left:10px;">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                    </label>';
      }

      $table[] = $row;

      // up is all user, bawah individual user

      foreach ($list as $field) {

        $no++;
        $row   = array();

        $checked = $this->settingUser_Model->get_checkedExtend($field->UserId);

        if (isset($checked)) {
          if ($checked->NumExtendCheck == 1) {
              $checkExtend ="checked";
              $showExtend ="";
              $extendnum= $checked->NumExtendDay;
          }else {
            $checkExtend ="";
            $showExtend  ="none";
            $extendnum   = 0;
          }
        } else {
            $checkExtend ="";
            $showExtend ="none";
            $extendnum= 0;
        }

        if (isset($checked)) {
          if ($checked->BlockVouchersCheck == 1) {
            $checkBlock ="checked";
            $showBlock ="";
          }else {
            $checkBlock ="";
            $showBlock ="none";
          }
        }else {
          $checkBlock ="";
          $showBlock ="none";
        }

        if (isset($checked)) {
          if ($checked->UnblockVouchersCheck == 1) {
            $checkUnblock ="checked";
          }else {
            $checkUnblock ="";
          }
        }else {
            $checkUnblock ="";
          }

        if (isset($checked)) {
          if ($checked->CancelVoucherCheck == 1) {
            $checkCancel ="checked";
          }else {
            $checkCancel ="";
          }
        }else {
            $checkCancel ="";
          }

  			// $row[] = $no;
        $row[] = $field->Fullname;
  			$row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid extend-switch-on numExtendCheck onchangeInd" name="numExtendCheck" id="numExtendCheck_'.$n.'" data-type="extend" data-num="'.$n.'" data-userid="'.$field->UserId.'" '.$checkExtend.' />
                    <span class="switch-toggle-slider">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>
                  <input class="extend-number-fill numExtend '.$showExtend.'" type="text" name="numExtend" id="numExtend_'.$n.'"  data-num="'.$n.'" data-userid="'.$field->UserId.'" style="width:60px; margin-left:30px" value="'.$extendnum.'" />';

  			$row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid block-switch-on blockVouchercheck onchangeInd" data-type="block" name="blockVouchercheck" id="blockVouchercheck_'.$n.'"  data-userid="'.$field->UserId.'" data-num="'.$n.'" '.$checkBlock.' />
                    <span class="switch-toggle-slider" style="margin-left:10px;">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>';

        $row[] = '<label class="switch">
                    <input type="checkbox" class="switch-input is-valid unblockVouchercheck onchangeInd" data-type="unblock" id="unblockVouchercheck_'.$n.'" name="unblockVouchercheck_" data-userid="'.$field->UserId.'" data-num="'.$n.'" '.$checkUnblock.' />
                    <span class="switch-toggle-slider" style="margin-left:10px;">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                  </label>';

        $row[] = '  <label class="switch">
                      <input type="checkbox" class="switch-input is-valid cancelVouchercheck onchangeInd" data-type="cancel" name="cancelVouchercheck" id="cancelVouchercheck_'.$n.'" data-userid="'.$field->UserId.'" data-num="'.$n.'" '.$checkCancel.'/>
                      <span class="switch-toggle-slider" style="margin-left:10px;">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                    </label>';
                    $n++;
        $table[] = $row;
      }

      $output = array(
        "draw"                => $post['draw'],
        "recordsTotal"        => $this->settingUser_Model->count_all($post,$userid, $permission->StoreId),
        "recordsFiltered"     => $this->settingUser_Model->count_filtered($post,$userid, $permission->StoreId),
        "data"                => $table,
        $data['csrf']['name'] => $data['csrf']['hash']
      );
      echo json_encode($output);
    }

    function usersetting_all(){

      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $status    = true;//allow

      if (isset($get['checkextend'])) {
        if ($get['checkextend'] == 1) {
          if ($get['valueextend'] == "" || $get['valueextend'] == 0) { //bukak toggle tapi tak input value
            $status    = false;
            $response  = 'Days Extend value must be bigger than 0';
          }
        }
      }

      if ($status == true) {
        $status           = array('StatusId' => 2);
        $this->settingUser_Model->changeStatus($status);

        $alluser = $this->settingUser_Model->getAllUser();

          foreach ($alluser as $user){

            $checkuser = $this->settingUser_Model->check_user_setting($user->UserId);

            if (!isset($checkuser)) {
              $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data); //wajib letak applogger for create

              if (isset($get['checkextend'])) {
                echo "string";
                $insert = array(
                              'UserId'                   => $user->UserId,
                              'NumExtendCheck'           => $get['checkextend'],
                              'NumExtendDay'             => $get['valueextend'],
                              'AppLoggerId'              => $get['loggerid'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkblock'])) {
                $insert = array(
                              'UserId'                   => $user->UserId,
                              'BlockVouchersCheck'       => $get['checkblock'],
                              'AppLoggerId'              => $get['loggerid'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkunblock'])) {
                $insert = array(
                              'UserId'                   => $user->UserId,
                              'UnblockVouchersCheck'     => $get['checkunblock'],
                              'AppLoggerId'              => $get['loggerid'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkcancel'])) {
                $insert = array(
                              'UserId'                   => $user->UserId,
                              'CancelVoucherCheck'       => $get['checkcancel'],
                              'AppLoggerId'              => $get['loggerid'],
                              'StatusId'                 => 1,
                         );
              }
            $this->settingUser_Model->usersetting_all($insert);
            }else {
              if (isset($get['checkextend'])) {
                $update = array(
                              'UserId'                   => $user->UserId,
                              'NumExtendCheck'           => $get['checkextend'],
                              'NumExtendDay'             => $get['valueextend'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkblock'])) {
                $update = array(
                              'UserId'                   => $user->UserId,
                              'BlockVouchersCheck'       => $get['checkblock'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkunblock'])) {
                $update = array(
                              'UserId'                   => $user->UserId,
                              'UnblockVouchersCheck'     => $get['checkunblock'],
                              'StatusId'                 => 1,
                         );
              }

              if (isset($get['checkcancel'])) {
                $update = array(
                              'UserId'                   => $user->UserId,
                              'CancelVoucherCheck'       => $get['checkcancel'],
                              'StatusId'                 => 1,
                         );
              }
              $this->settingUser_Model->usersetting_all_update($update);
            }
        }
          $status    = true;
          $response  = "User Voucher Settings succesfully saved.";
      }
      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }

    function usersetting_individu(){
      $data = array_merge($this->global_data);
      $get = $this->input->post();

      $status    = true;//allow

      if ($get['checkextend'] == 1) {
        if ($get['valueextend'] == "" || $get['valueextend'] == 0) {
          $status    = false;
          $response  = 'Extends days value must be bigger than 0';
        }
      }

      if ($status == true) {
        $status           = array('StatusId' => 2);
        $this->settingUser_Model->changeStatus_ind($status,$get['userid']);

        $checkuser = $this->settingUser_Model->check_user_setting($get['userid']);
        // print_r($checkuser);

        if (!isset($checkuser)) {
          $get['loggerid'] = $this->App_logger_model->insert_app_logger_data($data); //wajib letak applogger for create
            $insert = array(
                          'UserId'                   => $get['userid'],
                          'NumExtendCheck'           => $get['checkextend'],
                          'NumExtendDay'             => $get['valueextend'],
                          'BlockVouchersCheck'       => $get['checkblock'],
                          'UnblockVouchersCheck'     => $get['checkunblock'],
                          'CancelVoucherCheck'       => $get['checkcancel'],
                          'AppLoggerId'              => $get['loggerid'],
                          'StatusId'                 => 1,
                     );
        $this->settingUser_Model->usersetting_all($insert);
        }else {
            $update = array(
                          'UserId'                   => $get['userid'],
                          'NumExtendCheck'           => $get['checkextend'],
                          'NumExtendDay'             => $get['valueextend'],
                          'BlockVouchersCheck'       => $get['checkblock'],
                          'UnblockVouchersCheck'     => $get['checkunblock'],
                          'CancelVoucherCheck'       => $get['checkcancel'],
                          'StatusId'                 => 1,
                     );
        $this->settingUser_Model->usersetting_all_update($update);
        }

        $status    = true;
        $response  = "User Voucher Settings succesfully saved.";
      }
      $result['token']    = $data['csrf']['hash'];
      $result['status']   = $status;
      $result['message']  = $response;

      echo json_encode($result);
    }
  }

 ?>
