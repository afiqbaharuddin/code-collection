
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/"
  data-template="vertical-menu-template"
>
  <head>
    <title>General Settings</title>
    <?php echo $header; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>

  <style>
    body {
     background-color:#F1EDE3 ;
    }

    .custom-loader {
      width:50px;
      height:50px;
      border-radius:50%;
      border:8px solid;
      border-color:#766DF4 #0000;
      animation:s1 1s infinite;
    }
    @keyframes s1 {to{transform: rotate(.5turn)}}

  </style>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

      <!-- Menu sidebar -->
      <?php echo $sidebar; ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

          <!-- topbar -->
        <?php echo $topbar; ?>
          <!-- / topbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="content-header-row">
                <div class="content-header-left col-12 mb-2 mt-1">
                  <div class="row breadcrumbs-top">
                    <div class="col-12">
                      <h5 class="content-header-title float-left pr-1 mb-0">GENERAL SETTINGS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Settings</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >General Settings</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <div class="card-header border-bottom">
                  <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">

                      <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start
                        d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">

                       </div>
                    </div>

                <div class="table-responsive">
                  <table id="table-extended-transaction" class="table mb-0">
                    <thead style="background-color:#122620">
                      <tr>
                        <th class="col-3" style="color:white">Settings</th>
                        <th class="col-2" style="color:white">Action</th>
                      </tr>
                    </thead>
                    <form id="generalsettingsform"  method="post">
                      <tbody>
                      <tr class="border-bottom">
                        <td>Allow Campaign Validation</td>
                        <td>
                          <label class="switch" >
                            <?php if($general->CampaignValidationCheck == "Y"){
                                $validation_check = 'checked';
                              } else{
                                $validation_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" id="validationCheck" name="validationCheck" <?php echo $validation_check ?>/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                          <input class="set_value" type="date" style="margin-left:40px; border:hidden" min="<?php echo date('Y-m-d'); ?>" id="validation" name="validation" value="<?php echo $general->CampaignValidationDate ?>" ></input>
                        </td>
                      </tr>

                      <tr>
                        <td>Campaign Set Due (Days)</td>
                        <td>
                          <label class="switch" >
                            <?php if($general->CampaignSetDueCheck == "Y"){
                                $setdue_check = 'checked';
                              } else{
                                $setdue_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" id="campaignCheck" name="campaignCheck" <?php echo $setdue_check ?> />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                          <input class="set_value" type="text" style="width:60px; margin-left:40px" id="campaign" name="campaign" value="<?php echo $general->CampaignSetDueDays ?>"></input>
                        </td>
                      </tr>

                      <!-- <tr>
                        <td>Limit Attempt User Log In</td>
                        <td>
                        <label class="switch" >
                          <?php if($general->LimitUserLogCheck == "Y"){
                              $limit_check = 'checked';
                            } else{
                              $limit_check = '';
                            } ?>
                            <input type="checkbox" class="switch-input is-valid" id="limitCheck" name="limitCheck" <?php echo $limit_check ?>/>
                            <span class="switch-toggle-slider">
                              <span class="switch-on"></span>
                              <span class="switch-off"></span>
                            </span>
                          </label>
                          <input class="set_value" type="text" style="width:60px; margin-left:40px" id="limit" name="limit" value="<?php echo $general->LimitUserLogNum ?>"></input>
                        </td>
                      </tr> -->

                      <tr>
                        <td>Idle Timeout User Logout (Mins)</td>
                        <td>
                          <label class="switch" >
                            <?php if($general->IdleTimeoutCheck == "Y"){
                                $timeout_check = 'checked';
                              } else{
                                $timeout_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" id="timeCheck" name="timeCheck" <?php echo $timeout_check ?>/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                          <input class="set_value" type="text" style="width:60px; margin-left:40px" id="time" name="time" value="<?php echo $general->IdleTimeoutNum ?>"></input>
                        </td>
                      </tr>

                      <tr>
                        <td>Number of failed login before account lockout</td>
                        <td>
                        <label class="switch">
                          <?php if($general->NumFailedLoginCheck == "Y"){
                              $failedlogin_check = 'checked';
                            } else{
                              $failedlogin_check = '';
                            } ?>

                            <input type="checkbox" class="switch-input is-valid" id="lockoutCheck" name="lockoutCheck" <?php echo $failedlogin_check ?>/>
                            <span class="switch-toggle-slider">
                              <span class="switch-on"></span>
                              <span class="switch-off"></span>
                            </span>
                          </label>
                            <input class="set_value" type="text" style="width:60px; margin-left:40px" id="lockout" name="lockout" value="<?php echo $general->NumFailedLoginNum ?>"></input>
                        </td>
                      </tr>

                      <tr>
                        <td>Unlock account manually by administrator</td>
                        <td>
                        <label class="switch">
                          <?php if($general->UnlockManuallyCheck == 'Y'){
                              $unlock_manually = 'checked';
                            } else{
                              $unlock_manually = '';
                            } ?>
                            <input type="checkbox" class="switch-input is-valid"  name="unlockmannual" id="unlockmannual" <?php echo $unlock_manually ?>/>
                            <span class="switch-toggle-slider">
                              <span class="switch-on"></span>
                              <span class="switch-off"></span>
                            </span>
                          </label>
                        </td>
                      </tr>

                      <tr>
                        <td>Change Return Policy (Days)</td>
                        <td>
                          <label class="switch" >
                              <input type="checkbox" class="switch-input is-valid" id="policycheck" name="policycheck" checked disabled/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                          <input class="set_value" type="text" style="width:60px; margin-left:40px" id="policyreturn" name="policyreturn" value="<?php echo $general->PolicyReturn ?>"></input>
                        </td>
                      </tr>

                      <tr>
                        <td>Manual Scheduler
                          <i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="This button will update Expiry/Extend/Terminate/Cancel/Inactive Status and dates following the module inside"></i>
                        </td>
                        <td>
                          <button type="button" class="btn btn-primary" id="schedulerbtn" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;" data-bs-toggle="modal" data-bs-target="#schedulermodal">Open</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <?php
                  $action     = $this->RolePermission_Model->submenu_master(18);
                  if ($action->Update == 1) { ?>
                  <div class="pt-4">
                      <div class="col-sm-12">
                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="button" class="btn btn-label-secondary" id="cancelbtn" style="float:right">Reset All</button>
                        <button type="button" id="submitbtn" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620;
                        border-color:#122620; float:right">Submit</button>
                      </div>
                  </div>
                  <?php }  ?>
                </form>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>
          </div>

          <!--- Modal for Scheduler -->
          <div class="modal fade" id="schedulermodal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel2">Scheduler List</h5>
                  <button
                    type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" method="post">
                  <div class="modal-body">
                    <div class="table-responsive">
                      <table id="table-extended-transaction" class="table mb-0">
                        <thead style="background-color:#122620">
                          <tr>
                            <th class="col-1" style="color:white">List</th>
                            <th class="col-3" style="color:white">Details</th>
                            <th class="col-1" style="color:white">Action</th>
                          </tr>
                        </thead>

                          <tbody>
                            <tr>
                              <td>User</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Inactive User
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary btnscheduleruser" id="btnscheduleruser" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="userspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Manager On Duty</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Start Manager On Duty
                                </p>
                                <p class="text-muted mb-0">
                                  Expired Manager On Duty
                                </p>
                                <p class="text-muted mb-0">
                                  Update to Old Role
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary schedulermod" id="schedulermod" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="modspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Store</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Close Store
                                </p>
                                <p class="text-muted mb-0">
                                  Expired Card in Store
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary schedulerstore" id="schedulerstore" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="storespinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Voucher</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Inactive Voucher Type
                                </p><br>

                                <p class="text-muted mb-0">
                                  Expired (Active) Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Expired (Extended) Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Block Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Cancel Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Extend Vouchers
                                </p>

                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary schedulervoucher" id="schedulervoucher" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="voucherspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Gift Vouchers</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Expired (Active) Gift Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Expired (Extended) Gift Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Block Gift Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Cancel Gift Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Extend Gift Vouchers
                                </p>
                                <p class="text-muted mb-0">
                                  Inactive Gift Vouchers
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary schedulergift" id="schedulergift" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="giftspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Import Gift Vouchers</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Import Gift Voucher CSV File
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary importGiftVoucher" id="importGiftVoucher" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="importgiftspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>

                            <tr>
                              <td>Campaign</td>
                              <td>
                                <p class="text-muted mb-0">
                                  Expired Campaign
                                </p>
                                <p class="text-muted mb-0">
                                  Extend Campaign
                                </p>
                                <p class="text-muted mb-0">
                                  Inactive Campaign
                                </p>
                                <p class="text-muted mb-0">
                                  Expired Campaign Store
                                </p>
                                <p class="text-muted mb-0">
                                  Extend Campaign Store
                                </p>
                                <p class="text-muted mb-0">
                                  Expired Campaign Card
                                </p>
                                <p class="text-muted mb-0">
                                  Extend Campaign Card
                                </p>
                                <p class="text-muted mb-0">
                                  Terminate Campaign Card
                                </p>
                                <p class="text-muted mb-0">
                                  Terminate Campaign Store
                                </p>
                              </td>
                              <td class="demo-inline-spacing">
                                <button type="button" class="btn btn-primary schedulercampaign" id="schedulercampaign" name="button" style="background-color:#122620; border-color:#122620; width:120px; height:40px;">
                                <span class="spinner-border me-1" id="campaignspinner_border" role="status" aria-hidden="true"></span>
                                Run
                                </button>
                              </td>
                            </tr>
                        </tbody>
                      </table>
                  </div>
                  </div>

                  <div class="modal-footer">
                    <input type="hidden" name="loggerid" id="loggerid" value="">
                    <input type="hidden" name="cardId" id="cardId" value="">
                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!--- /Modal for Scheduler -->
        </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php echo $footer; ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js" integrity="sha512-zP5W8791v1A6FToy+viyoyUUyjCzx+4K8XZCKzW28AnCoepPNIXecxh9mvGuy3Rt78OzEsU+VCvcObwAMvBAww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script type="text/javascript">

      $(document).ready(function(){

        //Limit times of User Login
        var limit = '<?php echo $general->LimitUserLogCheck; ?>';
        if (limit ==  "Y") {
          $("#limit").show(400);
        }else {
          $("#limit").hide(400);
        }

        $("#limitCheck").change(function(){
          if($(this).is(":checked")){
            $("#limit").show(400);
            $(this).prop('checked',true);
          } else
          {
            $("#limit").hide(400);
            $(this).prop('checked',false);
          }
        });

        //Timeout user logout
        var time = '<?php echo $general->IdleTimeoutCheck; ?>';
        if (time ==  "Y") {
          $("#time").show(400);
        }else {
          $("#time").hide(400);
        }

          $("#timeCheck").change(function(){
            if($(this).is(":checked")){
              $("#time").show(400);
              // $(this).prop('checked',true);
            } else
            {
              // $(this).prop('checked',false);
              $("#time").hide(400);
            }
          });

        //Campaign Set Due
        var campaign = '<?php echo $general->CampaignSetDueCheck; ?>';
        if (campaign ==  "Y") {
          $("#campaign").show(400);
        }else {
          $("#campaign").hide(400);
        }

          $("#campaignCheck").change(function(){
            if($(this).is(":checked")){
              $("#campaign").show(400);
              // $(this).prop('checked',true)
              ;
            } else
            {
              $("#campaign").hide(400);
              // $(this).prop('checked',false);
            }
          });

        //Account Lockout
        var lockout = '<?php echo $general->NumFailedLoginCheck; ?>';
        if (lockout ==  "Y") {
          $("#lockout").show(400);
        }else {
          $("#lockout").hide(400);
        }
          $("#lockoutCheck").change(function(){
            if($(this).is(":checked")){
              // $(this).prop('checked',true);
              $("#lockout").show(400);
            } else
            {
              // $(this).prop('checked',false);
              $("#lockout").hide(400);
            }
          });

        //Allow Campaign validation
        var validation = '<?php echo $general->CampaignValidationCheck; ?>';
        if (validation ==  "Y") {
          $("#validation").show(400);
        }else {
          $("#validation").hide(400);
        }

          $("#validationCheck").change(function(){
            if($(this).is(":checked")){
              $("#validation").show(400);
            }else {
              $("#validation").hide(400);
            }
          });

      // Unlock account manually by administrator
        $("#unlockmannual").change(function(){
          var manuallock = '<?php echo $general->UnlockManuallyCheck; ?>';
          if (manuallock ==  "Y") {
            if($(this).is(":checked")){
                $(this).prop('checked',true);
              }else {
                $(this).prop('checked',false);
              }
          }

          if($(this).is(":checked")){
              $(this).prop('checked',true);
            }else {
              $(this).prop('checked',false);
          }
        });

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Form submit

      function submit_form(){
        //submit
        $('#generalsettingsform').unbind('submit').bind('submit', function(e){
          $.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>settings/GeneralSettings/settings",
            data: $('#generalsettingsform').serialize(),
            dataType: 'json',
            success:function(data){
              $('.txt_csrfname').val(data.token);
              if (data.status == true) {
                snack(data.message,'success');
              }else {
                snack(data.message,'danger');
              }
            },
            error: function(xhr, status, error) {
              snack('Something went wrong. Please try again later','danger');
            },
          });
          e.preventDefault();
        });

        $("#generalsettingsform").submit();
      }

      $('#submitbtn').on('click', function(){
        confirmation();
      });

      function confirmation(){
      $.confirm({
        theme: 'dark',
        title: false,
        content: 'Are you sure want to update the General Settings?',
        typeAnimated: true,
        buttons: {
          confirm: {
            text: 'Yes',
            action : function(){
              submit_form();
            }
          },
          cancel: {
            text: 'No',
            btnClass: 'btn-red',
            action: function(){
              snack("Update General Settings cancelled", 'danger');
              }
            },
            },
          })
        };

        function submit_cancelform(){
          //cancel
          $('#generalsettingsform').unbind('submit').bind('submit', function(e){
            $.ajax({
              type:"post",
              url:"<?php echo base_url(); ?>settings/GeneralSettings/CancelSettings",
              data: $('#generalsettingsform').serialize(),
              dataType: 'json',
              success:function(data){
                $('.txt_csrfname').val(data.token);
                if (data.status == true) {
                  snack(data.message,'success');
                }else {
                  snack(data.message,'danger');
                }
              },
              error: function(xhr, status, error) {
                snack('Something went wrong. Please try again later','danger');
              },
            });
            e.preventDefault();
          });

          $("#generalsettingsform").submit();
        }


      $('#cancelbtn').on('click', function(){
        confirmationCancel();
      });

      function confirmationCancel(){
      $.confirm({
        theme: 'dark',
        title: false,
        content: 'Are you sure want to Cancel/Reset all General Settings?',
        typeAnimated: true,
        buttons: {
          confirm: {
            text: 'Yes',
            action : function(){
              submit_cancelform()
            }
          },
          cancel: {
            text: 'No',
            btnClass: 'btn-red',
            action: function(){
              snack("Update General Settings cancelled", 'danger');
              }
            },
            },
          })
        };

        $('#cancelbtn').click(function(){
          $('.set_value').hide(400);
          $('.switch-input').prop('checked', false)
        });

        // manual scheduler
        // User
        $('#userspinner_border').hide();
        $('.btnscheduleruser').on('click', function () {

        $('#userspinner_border').show();
        $('#userspinner_border').hide(800);
        snack("Scheduler for User Running Successfully", 'success');

        var scheduleruser = $('#scheduleruser').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_user'); ?>",
          type: "post",
          data: {user: scheduleruser},
          dataType: 'json',
        });
      });

      //mod
      $('#modspinner_border').hide();
      $('.schedulermod').on('click', function () {

      $('#modspinner_border').show();
      $('#modspinner_border').hide(800);
      snack("Scheduler for Manager on Duty Running Successfully", 'success');

      var schedulermod = $('#schedulermod').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_mod'); ?>",
          type: "get",
          dataType: 'json',
        });
      });

      //store
      $('#storespinner_border').hide();
      $('.schedulerstore').on('click', function () {

        $('#storespinner_border').show();
        $('#storespinner_border').hide(800);
        snack("Scheduler for Store Running Successfully", 'success');

        var schedulerstore = $('#schedulerstore').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_store'); ?>",
          type: "get",
          dataType: 'json',
        });
      });

      //vouchers
      $('#voucherspinner_border').hide();
      $('.schedulervoucher').on('click', function () {

        $('#voucherspinner_border').show();
        $('#voucherspinner_border').hide(800);
        snack("Scheduler for Vouchers Running Successfully", 'success');

        var schedulervoucher = $('#schedulervoucher').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_vouchers'); ?>",
          type: "get",
          dataType: 'json',
        });
      });

      //gift vouchers
      //vouchers
      $('#giftspinner_border').hide();
      $('.schedulergift').on('click', function () {

        $('#giftspinner_border').show();
        $('#giftspinner_border').hide(800);
        snack("Scheduler for Gift Vouchers Running Successfully", 'success');

        var schedulergift = $('#schedulergift').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_gift'); ?>",
          type: "get",
          dataType: 'json',
        });
      });

      //import gift Voucher
      $('#importgiftspinner_border').hide();
      $('.importGiftVoucher').on('click', function () {

        $('#importgiftspinner_border').show();
        $('#importgiftspinner_border').hide(800);
        snack("Scheduler for Import Gift Vouchers CSV Running Successfully", 'success');

        var importGiftVoucher = $('#importGiftVoucher').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_gift_import'); ?>",
          type: "get",
          dataType: 'json',
        });
      });

      //campaign
      $('#campaignspinner_border').hide();
      $('.schedulercampaign').on('click', function () {

        $('#campaignspinner_border').show();
        $('#campaignspinner_border').hide(800);
        snack("Scheduler for Campaign Running Successfully", 'success');

        var schedulercampaign = $('#schedulercampaign').val();

        $.ajax({
          url: "<?php echo base_url('Scheduler/manual_campaign'); ?>",
          type: "get",
          dataType: 'json',
        });
      });
      });
    </script>
  </body>
</html>
