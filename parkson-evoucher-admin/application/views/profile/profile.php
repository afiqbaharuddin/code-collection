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
    <title>Account settings</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.css" />

    <?php echo $header; ?>
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
        <!-- Menu -->

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
              <h4 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">Account Settings /</span> Account
              </h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                    </li>
                    <?php if ($ownPass->OwnPassCheck == 1): ?>
                      <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>profile/ProfileSecurity ">
                          <i class="bx bx-lock-alt me-1"></i> Security</a>
                      </li>
                    <?php endif; ?>

                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>

                    <!-- Account -->
                    <div class="card-body">

                    <hr class="my-0" />
                    <div class="card-body">
                      <form id="formAccountSettings" method="POST" onsubmit="return false">
                        <div class="row">

                          <div class="mb-3 col-md-6">
                            <input type="hidden" name="userid" value="<?php echo $userdetails->UserId ?>">

                            <label for="fullname" class="form-label">Fullname</label>
                            <input class="form-control" type="text" id="fullname" name="fullname" value="<?php echo $userdetails->Fullname ?>" autofocus/>
                          </div>

                          <div class="mb-3 col-md-6">
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="staffId" class="form-label">Staff ID</label>
                            <input class="form-control" type="text" name="staffId" id="staffId" value="<?php echo $userdetails->StaffId ?>" disabled=""/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="loginid" class="form-label">Login ID</label>
                            <input class="form-control" type="text" name="loginid" id="loginid" value="<?php echo $userdetails->LoginId ?>" disabled=""/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="email" id="email" name="email" value="<?php echo $userdetails->Email ?>"/>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Phone Number</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text">MY (+60)</span>
                              <input type="text" maxlength="10" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo $userdetails->PhoneNo ?>"/>
                            </div>
                          </div>


                        </div>
                        <div class="mt-2">
                          <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                          <button type="submit" class="btn btn-primary me-2" style="background-color:#122620; border-color:#122620">Save changes</button>
                          <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                      </form>
                    </div>
                    <!-- /Account -->
                  </div>

                </div>
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
    <script src="<?php echo base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/js/pages-account-settings-account.js"></script>


    <script type="text/javascript">

      $(document).ready(function(){
        $('#formAccountSettings').submit(function(e){
          $.ajax({
            type:"post",
            url: "<?php echo base_url(); ?>profile/profile/editprofile",
            data: $('#formAccountSettings').serialize(),
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              if (data.status == true) {
                snack(data.message, 'success');
              }else {
                snack(data.message,'danger');
              }
            },
            error: function(xhr, status, error) {
              snack('Something went wrong. Please try again later','danger');
            },
          });
          return false;
        });

        //only phone number on form
        $("#phoneNumber").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

      });
    </script>
  </body>
</html>
