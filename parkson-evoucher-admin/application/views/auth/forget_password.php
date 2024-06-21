<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/"
  data-template="vertical-menu-template"
>
  <head>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/css/pages/page-auth.css" />
    <title>Forgot Password</title>
    <?php echo $header; ?>
  </head>

  <body style="background:#D6AD60">
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center" style="margin-left:70px">
                <a href="index.html" class="app-brand-link gap-2">
                   <img class="app-brand-text demo menu-text fw-bold ms-2" src="http://localhost/work-office/parkson-evoucher-admin/assets//img/branding/logo3.png" style="width:75%"></img>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2" style="margin-top:20px">Forgot Password? </h4>
              <p class="mb-4" style="color:#4e5000">Enter your email and we'll send you instructions to reset your password</p>
              <form id="formAuthentication" class="mb-3" action="auth-reset-password-basic.html" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label" style="font-weight:600">Email</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    autofocus
                  />
                </div>
                <button class="btn btn-primary d-grid w-100" style="background-color:#231f20; border-color:#231f20">Send Reset Link</button>
              </form>
              <div class="text-center">
                <a href="<?php echo base_url();?>auth/Login" class="d-flex align-items-center justify-content-center" style="color:#4e5000">
                  <i class="bx bx-chevron-left scaleX-n1-rtl"></i>
                  Back to login
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom; ?>
    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/js/pages-auth.js"></script>
  </body>
</html>
