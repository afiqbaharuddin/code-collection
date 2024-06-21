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
    <title>Password Settings</title>
    <?php echo $header; ?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"> -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css"
          integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer"
          />
  </head>

  <style>
    body {
     background-color:#F1EDE3 ;
    }
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
                      <h5 class="content-header-title float-left pr-1 mb-0">PASSWORD SETTINGS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Settings</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >Password Settings</li>
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
                  <table id="PassSettingTable" class="table mb-0">
                    <thead style="background-color:#122620">
                      <tr>
                        <th class="col-3" style="color:white">Settings</th>
                        <th class="col-2" style="color:white">Action</th>
                      </tr>
                    </thead>

                    <form id="passForm" method="post">
                      <tbody>
                        <tr>
                          <td>Minimum password length</td>
                          <td>
                          <label class="switch" >
                            <?php if($pass->MinPassCheck == 1){
                                $passlength_check = 'checked';
                              } else{
                                $passlength_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid toggle" name="minPassCheck" id="minPassCheck" <?php echo $passlength_check; ?>>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                              <input class="set_value" type="text" style="width:60px; margin-left:30px" name="minPassvalue" id="minPass" value="<?php echo $pass->MinPassValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Uppercase characters (A through Z)</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->UppercaseCheck == 1){
                                $uppercase_check = 'checked';
                              } else{
                                $uppercase_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid toggle" name="upCharCheck" id="upCharCheck" <?php echo $uppercase_check ?>/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                            <input class="set_value" type="text" style="width:60px; margin-left:30px" name="upCharValue" id="upChar" value="<?php echo $pass->UppercaseValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Lowercase characters (a through z)</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->LowercaseCheck == 1){
                                $lowercase_check = 'checked';
                              } else{
                                $lowercase_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" name="lowCharCheck" id="lowCharCheck" <?php echo $lowercase_check ?>/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                            <input  class="set_value" type="text" style="width:60px; margin-left:30px" name="lowCharValue" id="lowChar" value="<?php echo $pass->LowercaseValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Numbers</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->NumbersCheck == 1){
                                $numbers_check = 'checked';
                              } else{
                                $numbers_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" name="numberCheck" id="numberCheck" <?php echo $numbers_check ?> />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                              <input class="set_value" type="text" style="width:60px; margin-left:30px" name="numberValue" id="number" value="<?php echo $pass->NumbersValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Frequency of forced Password changes (Days)</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->PasswordAgeCheck == 1){
                                $passwordage_check = 'checked';
                              } else{
                                $passwordage_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" name="ageCheck" id="frequencyCheck" <?php echo $passwordage_check ?>/>
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                              <input class="set_value" type="text" style="width:60px; margin-left:30px" name="ageValue" id="frequency" value="<?php echo $pass->PasswordAgeValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Password History</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->PasswordHistoryCheck == 1){
                                $passwordhis_check = 'checked';
                              } else{
                                $passwordhis_check = '';
                              } ?>
                              <input type="checkbox" class="switch-input is-valid" name="historyCheck" id="historyCheck" <?php echo $passwordhis_check ?> />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                              <input class="set_value" type="text" style="width:60px; margin-left:30px" name="historyValue" id="history" value="<?php echo $pass->PasswordHistoryValue ?>"></input>
                          </td>
                        </tr>

                        <tr>
                          <td>Initial log-on uses a one-time password</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->IntialLogOneTimePassCheck == 1){
                                $timepass_check = 'checked';
                              } else{
                                $timepass_check = '';
                              } ?>
                              <input type="checkbox" name="initialonetime" class="switch-input is-valid" <?php echo $timepass_check ?>  />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
                          </td>
                        </tr>

                        <tr>
                          <td>Ability of users to assign their own password</td>
                          <td>
                          <label class="switch">
                            <?php if($pass->OwnPassCheck == 1){
                                $ownpass_check = 'checked';
                              } else{
                                $ownpass_check = '';
                              } ?>
                              <input type="checkbox" name="initialownps" class="switch-input is-valid" <?php echo $ownpass_check ?>  />
                              <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                            </label>
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
                        <button type="button" class="btn btn-label-secondary" id="resetbtn" style="float:right">Reset All</button>
                        <input type="button" class="btn btn-primary me-sm-3 me-1" id="submitbtn" style="background-color:#122620; border-color:#122620; float:right" value="Submit">
                      </div>
                  </div>
                  <?php }  ?>
                </form>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>
          </div>
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

    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"
      integrity="sha512-zP5W8791v1A6FToy+viyoyUUyjCzx+4K8XZCKzW28AnCoepPNIXecxh9mvGuy3Rt78OzEsU+VCvcObwAMvBAww=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer">
    </script>

    <script type="text/javascript">

      $(document).ready(function(){

      $('#resetbtn').click(function(){
        $('.set_value').hide(400);
        $('.switch-input').prop('checked', false)
      });

      $('#passForm').unbind('submit').bind('submit', function(e){
        $.ajax({
          type:"post",
          url:"<?php echo base_url(); ?>settings/PassSettings/settings",
          data: $('#passForm').serialize(),
          dataType: 'json',
          success:function(data){
            // alert('security check was updated');
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

       $('#submitbtn').on('click', function(){
        confirmation();
      });

        function confirmation(){
            $.confirm({
              theme: 'dark',
              title: false,
              content: 'Are you sure want to update the security authentication?',
              typeAnimated: true,
              buttons: {
                confirm: {
                    text: 'Yes',
                    action : function(){
                        $("#passForm").submit();
                        }
                    },
                    cancel: {
                        text: 'No',
                        btnClass: 'btn-red',
                        action: function(){
                          snack("Update Security Authentication cancelled", 'success');
                        }
                },
                },
              })
            };

        //minimum password days
        var min = '<?php echo $pass->MinPassCheck; ?>';
        if (min ==  1) {
          $("#minPass").show(400);
        }else {
          $("#minPass").hide(400);
        }

        $("#minPassCheck").change(function(){
          if($(this).is(":checked")){
            $("#minPass").show(400);
          }
          else
          {
            $("#minPass").hide(400);
          }
        });

        //upper char
        var upper = '<?php echo $pass->UppercaseCheck; ?>';
        if (upper ==  1) {
          $("#upChar").show(400);
        }else {
          $("#upChar").hide(400);
        }

          $("#upCharCheck").change(function(){
            if($(this).is(":checked")){
              $("#upChar").show(400);
            } else
            {
              $("#upChar").hide(400);
            }
          });

        //low char
        var low = '<?php echo $pass->LowercaseCheck; ?>';
        if (low ==  1) {
          $("#lowChar").show(400);
        }else {
          $("#lowChar").hide(400);
        }

          $("#lowCharCheck").change(function(){
            if($(this).is(":checked")){
              $("#lowChar").show(400);
            } else
            {
              $("#lowChar").hide(400);
            }
          });

        //number
        var number = '<?php echo $pass->NumbersCheck; ?>';
        if (number ==  1) {
          $("#number").show(400);
        }else {
          $("#number").hide(400);
        }

          $("#numberCheck").change(function(){
            if($(this).is(":checked")){
              $("#number").show(400);
            } else
            {
              $("#number").hide(400);
            }
          });

        //frequency
        var frequency = '<?php echo $pass->PasswordAgeCheck; ?>';
        if (frequency ==  1) {
          $("#frequency").show(400);
        }else {
          $("#frequency").hide(400);
        }

          $("#frequencyCheck").change(function(){
            if($(this).is(":checked")){
              $("#frequency").show(400);
            } else
            {
              $("#frequency").hide(400);
            }
          });

        //history
        var history = '<?php echo $pass->PasswordHistoryCheck; ?>';
        if (history ==  1) {
          $("#history").show(400);
        }else {
          $("#history").hide(400);
        }

          $("#historyCheck").change(function(){
            if($(this).is(":checked")){
              $("#history").show(400);
            } else
            {
              $("#history").hide(400);
            }
          });


          //Reset password settings
          function submit_resetform(){
            //reset
            $('#passForm').unbind('submit').bind('submit', function(e){
              $.ajax({
                type:"post",
                url:"<?php echo base_url(); ?>settings/PassSettings/ResetSettings",
                data: $('#passForm').serialize(),
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

            $("#passForm").submit();
          }


        $('#resetbtn').on('click', function(){
          confirmationReset();
        });

        function confirmationReset(){
        $.confirm({
          theme: 'dark',
          title: false,
          content: 'Are you sure want to Reset all Password Settings?',
          typeAnimated: true,
          buttons: {
            confirm: {
              text: 'Yes',
              action : function(){
                submit_resetform()
              }
            },
            cancel: {
              text: 'No',
              btnClass: 'btn-red',
              action: function(){
                snack("Update Password Settings reset", 'danger');
                }
              },
              },
            })
          };
      })
    </script>
  </body>
</html>
