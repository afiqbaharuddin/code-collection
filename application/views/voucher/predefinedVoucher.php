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

    <title>PreDefined Voucher</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">PRE-DEFINED VOUCHER</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Pre-Defined Vouchers</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!--  List Table -->
              <!-- <h4>List of predefined voucher</h4> -->

              <div class="card">
                <div class="card-header border-bottom">

                  <div class="d-flex justify-content-between">
                    <div class="">
                      <h4>Pre-Defined Voucher List</h4>
                    </div>

                    <?php
                    $role = $this->RolePermission_Model->submenu_master(7);

                    if ($role->Create == 1) { ?>
                    <div class="">
                      <a href="<?php echo base_url();?>voucher/Voucher/createvoucher">
                      <button class="dt-button add-new btn btn-success" tabindex="0" aria-controls="DataTables_Table_0"
                          type="button" style="margin-bottom: 20px;">
                            <span>
                              <i class="bx bx-plus me-0 me-lg-2"></i>
                              <span class="d-none d-lg-inline-block">Create Voucher Type</span>
                        </span>
                      </button>
                      </a>
                    </div>
                    <?php }  ?>
                  </div>

                  <div class="card-datatable text-nowrap">
                    <table id="predefinedtable" class="table">
                      <thead style="background-color:#122620">
                        <tr>
                          <th  style="color:white">Voucher Short Name</th>
                          <th  style="color:white">Voucher Name</th>
                          <th  style="color:white">Voucher Value (RM)</th>
                          <th style="color:white">Activate Date</th>
                          <th style="color:white">Inactivate Date</th>
                          <th  style="color:white">Voucher Status</th>

                          <?php
                          $action     = $this->RolePermission_Model->submenu_master(7);

                          if ($action->Update == 1) { ?>
                          <th style="color:white">Action</th>
                          <?php }  ?>

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

  <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/forms-tagify.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

  <!--Datatables-->
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>
  <!-- / Datatables-->

  <script>

    $(document).ready(function()  {
      $.fn.dataTable.ext.errMode = 'none';

      //PreDefined Voucher datatable
      var predefinedtable = $('#predefinedtable').DataTable({
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>voucher/predefinedvoucher/predefinedlisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
            }
        },
        "bDestroy": true,
      });
      predefinedtable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if(json != null) {
          $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        }else {
          getToken();
        }
      });


      //jquery for toggle switch at status
      $('#predefinedtable').on('change', '.toggle', function()  {
        var num = $(this).data('num');
        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();
        // alert("say");

        if($(this).is(":checked")){
          $('#toggle_'+num).val('1');
          $('#showstatusname_'+num).html('<span class="badge bg-label-success" style="margin-left:35px">Active</span>');
        } else{
          $('#toggle_'+num).val('2');
          $('#showstatusname_'+num).html('<span class="badge bg-label-danger" style="margin-left:35px">Inactive</span>');
        }

        var status           = $(this).val();
        var vouchershortname = $('#vouchershortname_'+num).val();
        var vouchertypeId    = $('#vouchertypeid_'+num).val();
        var loggerid         = $('#loggerid').val();
        // alert(status);

        $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>voucher/PredefinedVoucher/get_status",
          data: {status: status, vouchertypeId:vouchertypeId, vouchershortname:vouchershortname, loggerid:loggerid, <?= $csrf['name']; ?> : csrfHash},
          dataType: 'json',
          success: function(data)
          {
            $('.txt_csrfname').val(data.token);
            predefinedtable.ajax.reload();
          }
        });
      });


    });
  </script>
  </body>
</html>
