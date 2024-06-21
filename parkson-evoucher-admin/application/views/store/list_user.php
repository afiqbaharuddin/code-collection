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

    <title>In Store User List</title>
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

          <!-- Navbar -->
        <?php echo $topbar; ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="content-header-row">
                <div class="content-header-left col-12 mb-2 mt-1">
                  <div class="row breadcrumbs-top">
                    <div class="col-12">

                      <!-- <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Users</span>
                                  <h5 class="card-title mb-0 me-2"></h5>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> -->

                      <!-- <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Active Users</span>
                                  <h5 class="card-title mb-0 me-2"><?php echo $totaluser; ?></h5>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> -->

                      <h4 class="content-header-title float-left pr-1 mb-0">IN STORE USERS</h4>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Store</li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>store/Store/StoreList"><u>List of Stores</u></a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px;" >In Store Users</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60 "><?php echo $details->StoreName ?></li>

                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Users List Table -->
              <!-- <h4>In Store Users</h4> -->
              <div class="card">
                <div class="card-header border-bottom">

                <div class="card-datatable table-responsive">
                  <div class="">
                    <!-- <h6>Total User:</h6>
                    <h6>Active User:</h6> -->
                  </div>


                  <table class="datatables-stores table border-top table mb-0" id="storeusertable">
                    <thead style="background-color:#122620">
                      <tr>
                        <th class="col-3" style="color:white">Staff ID</th>
                        <th class="col-3" style="color:white">Name</th>
                        <th class="col-2" style="color:white">Role</th>
                        <th class="col-2" style="color:white">Status</th>
                        <th class="col-2" style="color:white">Action</th>
                      </tr>
                    </thead>
                    <input type="hidden" name="loggerid" id="loggerid" value="">
                    </table>
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

    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
  <?php echo $bottom; ?>

  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

  <script>

    $(document).ready(function(){

      //in store user datatable
      var storeusertable = $('#storeusertable').DataTable({
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>store/listuser/listing/<?php echo $StoreId; ?>",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
            }
        },
        "bDestroy": true,
      });
      storeusertable.on('xhr.dt', function ( e, settings, json, xhr ) {
         $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
      });

      //action and status jquery
      // $('.activeLock1').hide();
      // $('.activeStatus1').hide();
      // $('.activeLock2').hide();
      // $('.activeStatus2').hide();
      // $('.activeLock3').hide();
      // $('.activeStatus3').hide();
      // $('.activeLock4').hide();
      // $('.activeStatus4').hide();
      //
      // //row 1
      // $('.inactiveLock1').click(function(){
      //   $('.activeLock1').show();
      //   $('.activeStatus1').show();
      //   $('.inactiveLock1').hide();
      //   $('.inactiveStatus1').hide();
      // });
      //
      // $('.activeLock1').click(function(){
      //   $('.activeLock1').hide();
      //   $('.activeStatus1').hide();
      //   $('.inactiveLock1').show();
      //   $('.inactiveStatus1').show();
      // });
      //
      // //row 2
      // $('.inactiveLock2').click(function(){
      //   $('.activeLock2').show();
      //   $('.activeStatus2').show();
      //   $('.inactiveLock2').hide();
      //   $('.inactiveStatus2').hide();
      // });
      //
      // $('.activeLock2').click(function(){
      //   $('.activeLock2').hide();
      //   $('.activeStatus2').hide();
      //   $('.inactiveLock2').show();
      //   $('.inactiveStatus2').show();
      // });
      //
      // //row 3
      // $('.inactiveLock3').click(function(){
      //   $('.activeLock3').show();
      //   $('.activeStatus3').show();
      //   $('.inactiveLock3').hide();
      //   $('.inactiveStatus3').hide();
      // });
      //
      // $('.activeLock3').click(function(){
      //   $('.activeLock3').hide();
      //   $('.activeStatus3').hide();
      //   $('.inactiveLock3').show();
      //   $('.inactiveStatus3').show();
      // });
      //
      // //row 4
      // $('.inactiveLock4').click(function(){
      //   $('.activeLock4').show();
      //   $('.activeStatus4').show();
      //   $('.inactiveLock4').hide();
      //   $('.inactiveStatus4').hide();
      // });
      //
      // $('.activeLock4').click(function(){
      //   $('.activeLock4').hide();
      //   $('.activeStatus4').hide();
      //   $('.inactiveLock4').show();
      //   $('.inactiveStatus4').show();
      // });


      //jquery for toggle switch at status
      $('#storeusertable').on('change', '.toggle', function()  {
        var num = $(this).data('num');
        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();
        // alert("say");

        if($(this).is(":checked")){
          $('#toggle_'+num).val('1');
          $('#showuserstatus_'+num).html('<span class="badge bg-label-success">Active</span>');
        } else{
          $('#toggle_'+num).val('2');
          $('#showuserstatus_'+num).html('<span class="badge bg-label-danger">Inactive</span>');
        }

        var status      = $(this).val();
        var userstoreId = $('#updateuserstatus_'+num).val();
        var loggerid    = $('#loggerid').val();
        // alert(status);

        $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>store/listuser/get_userstatus",
          data: {status: status, userstoreId:userstoreId, loggerid:loggerid, <?= $csrf['name']; ?> : csrfHash},
          dataType: 'json',
          success: function(data)
          {
            $('.txt_csrfname').val(data.token);
          }
        });
      });
    });
  </script>
  </body>
</html>
