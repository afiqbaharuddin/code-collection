<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url();  ?>assets/"
  data-template="vertical-menu-template"
>
  <head>
    <title>Account settings</title>
    <?php echo $header;?>
    <!-- Page CSS -->
    <link rel="stylesheet" href="<?php echo base_url();  ?>assets/vendor/css/pages/page-account-settings.css" />
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
      <?php echo $sidebar;?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <?php echo $topbar;?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">Account Settings /</span> Security
              </h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url();?>profile/Profile">
                        <i class="bx bx-user me-1"></i> Account</a>
                    </li>
                    <?php if ($ownPass->OwnPassCheck == 1): ?>
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);">
                        <i class="bx bx-lock-alt me-1"></i> Security</a>
                    </li>
                    <?php endif; ?>
                  </ul>

                  <!-- Change Password -->
                  <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                      <form id="formAccountSettings" action="<?php echo base_url(); ?>profile/ProfileSecurity/security" method="POST">
                        <div class="row">
                          <div class="mb-3 mt-4 col-md-6 form-password-toggle">
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <div class="input-group input-group-merge">
                              <!-- <input type="hidden" name="" value="<?php echo $security ->Password ?>"> -->
                              <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="mb-4 mt-4 col-md-6 form-password-toggle">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                              <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>

                          <div class="mb-4 mt-4 col-md-6 form-password-toggle">
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                              <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>

                          <div class="col-12 mt-4">
                            <input type="hidden" name="userid" value="<?php echo $security->UserId; ?>">
                            <input type="hidden" name="loggerid" id="loggerid" >

                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary me-2" style="background-color:#122620; border-color:#122620">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!--/ Change Password -->

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

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/js/pages-account-settings-security.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/modal-enable-otp.js"></script> -->

    <script type="text/javascript">

    $("#formAccountSettings").unbind('submit').bind('submit', function() {
      var form = $(this);
      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success:function(data)
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
    </script>
  </body>
</html>
