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
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />

    <title>Import Gift Voucher by Scheduler</title>
    <?php echo $header; ?>
  </head>

  <style media="screen">
    body {
     background-color:#F1EDE3;}
  </style>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <?php echo $sidebar;?>

        <div class="layout-page">

          <?php echo $topbar; ?>

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="content-header-row">
                <div class="content-header-left col-12 mb-4 mt-1">
                  <div class="row breadcrumbs-top">
                    <div class="col-12">
                      <h5 class="content-header-title float-left pr-1 mb-0">MANUAL IMPORT GIFT VOUCHER ERROR LOG</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Manual Import Gift Voucher Error Log</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h5>Manual Import Gift Voucher Error Log</h5>
                </div>
                <div class="card-datatable">
                  <table class="table" id="giftVoucher_errorLog">
                    <thead style="background-color:#122620">
                      <tr>
                        <th style="color:white">Voucher Number</th>
                        <th style="color:white">Import Date Time</th>
                        <th style="color:white">Error Message</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>

            <?php echo $footer;?>

            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
      <div class="layout-overlay layout-menu-toggle"></div>
      <div class="drag-target"></div>
      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    </div>

    <?php echo $bottom; ?>

    <!--Datatables-->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>
    <!-- / Datatables-->

    <script type="text/javascript">

    var giftVoucher_errorLog = $('#giftVoucher_errorLog').DataTable({
      "ordering": false,
      "searching": true,
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "ajax": {
          "url": "<?php echo base_url(); ?>voucher/Voucher/manual_giftvoucher_errorLog",
          "type": "POST",
          "data": function(data) {
            var csrfHash  = $('.txt_csrfname').val();
            data.<?= $csrf['name']; ?> = csrfHash;
          }
      },
      "bDestroy": true,
    });
    giftVoucher_errorLog.on('xhr.dt', function ( e, settings, json, xhr ) {
      if(json != null) {
        $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
      }else {
        getToken();
      }
    });
    </script>
  </body>
</html>
