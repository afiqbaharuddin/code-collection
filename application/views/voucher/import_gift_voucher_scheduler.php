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
                      <h5 class="content-header-title float-left pr-1 mb-0">IMPORT GIFT VOUCHER BY SCHEDULER</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Import Gift Voucher by Scheduler</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card">
                <form id="schedulerImportForm" action="<?php echo base_url(); ?>voucher/Voucher/scheduler_import_giftvoucher" method="post" enctype="multipart/form-data">
                  <div class="card-body">
                    <h5>Import Gift Voucher</h5>
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label" for="">CSV File Name</label>
                        <input type="text" id="filename" name="filename" class="form-control" placeholder="Enter CSV File Name"/>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="">Select CSV File to be Upload</label>
                        <input type="file" id="importfile" name="importfile" class="form-control"/>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="">Scheduled Import Time</label>
                        <input class="form-control" type="time" id="importTime" name="importTime" />
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-primary submit-btn" type="button" id="submit-btn" style="background-color:#122620; border-color:#122620">Submit</button>
                  </div>
                </form>
              </div>

              <div class="card mt-3">
                <div class="card-header">
                  <h5>Import Gift Voucher by Scheduler List</h5>
                </div>
                <div class="card-datatable">
                  <table class="table" id="import_giftvoucher_table">
                    <thead style="background-color:#122620">
                      <tr>
                        <th style="color:white">Csv File</th>
                        <th style="color:white">Scheduled Import Time</th>
                        <th style="color:white">Import Error</th>
                        <th style="color:white">Status</th>
                        <th style="color:white">Action</th>
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

    var import_giftvoucher_table = $('#import_giftvoucher_table').DataTable({
      "ordering": false,
      "searching": true,
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "ajax": {
          "url": "<?php echo base_url(); ?>voucher/Voucher/import_giftvoucher_listing",
          "type": "POST",
          "data": function(data) {
            var csrfHash  = $('.txt_csrfname').val();
            data.<?= $csrf['name']; ?> = csrfHash;
          }
      },
      "bDestroy": true,
    });
    import_giftvoucher_table.on('xhr.dt', function ( e, settings, json, xhr ) {
      if(json != null) {
        $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
      }else {
        getToken();
      }
    });

    //ajax submit import gift voucher
    $('.submit-btn').unbind('click').bind('click', function () {
      var form       = $('#schedulerImportForm');
      var formUp     = $('#schedulerImportForm')[0];
      var dataForm   = new FormData(formUp);

      $.ajax({
        contentType: 'multipart/form-data',
        url: form.attr('action'),
        type: form.attr('method'),
        data: dataForm,
        dataType: 'json',
        contentType: false,
        processData: false,
        async:  true,
        success: function(data) {
            $('.txt_csrfname').val(data.token);
            if (data.csv == true) {
              snack(data.message, 'success');
              $('#import_giftvoucher_table').DataTable().ajax.reload();
            }else {
              snack(data.message,'danger');
            }
        },
        error: function(xhr, status, error) {
            snack('Something went wrong. Please try again later', 'danger');
        },
      });
    });

    </script>
  </body>
</html>
