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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />

    <title>User Role</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">USER ROLE</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="http://localhost/work-office/parkson-evoucher-admin/dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Users</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">User Role</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <?php
                  echo 'Min Length: '.$password->MinPassCheck;
                  echo "<br>";
                  echo 'Min Length Value: '.$password->MinPassValue;
                  echo "<br>";
                  echo 'Uppercase: '.$password->UppercaseCheck;
                  echo "<br>";
                  echo 'Uppercase value: '.$password->UppercaseValue;
                  echo "<br>";
                  echo 'LowerCase: '.$password->LowercaseCheck;
                  echo "<br>";
                  echo 'LowerCase Value: '.$password->LowercaseValue;
                  echo "<br>";
                  echo 'Numbers: '.$password->NumbersCheck;
                  echo "<br>";
                  echo 'Numbers Value: '.$password->NumbersValue;
                  echo "<br>";
                   ?>

                   <br>
                   <form class="" action="<?php echo base_url(); ?>user/User/insertPass" id="passForm" method="post">
                     Password
                     <input type="text" name="pass" value="" class="form-control">
                     <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                     <button type="submit" name="button" class="btn btn-light mt-2">submit</button>
                   </form>



              </div>
            </div>
              <!--/ Basic Bootstrap Table -->
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

    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom; ?>

    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

    <script type="text/javascript">

      $(document).ready(function(){

        //  Add user role form
        $("#passForm").unbind('submit').bind('submit', function() { //input
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

      });


    </script>
  </body>
</html>
