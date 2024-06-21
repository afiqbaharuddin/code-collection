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

    <title>Voucher Logs</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">VOUCHER LOGS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Logs</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >Voucher Logs</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="margin-bottom:35px;">
                  <div class="col-md-12 col-12">
                    <div class="card">
                    <div class="card-body">

                      <h4>Search Logs</h4>
                      <form class="dt_adv_search" method="POST">
                        <div class="row">
                          <div class="col-12">
                            <div class="row g-3">

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Role:</label>
                                  <select id="filter_role" name="filter_role" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach ($role as $row) {
                                      if ($this->session->userdata('Filter_Role') !=null && $this->session->userdata('Filter_Role') == $row->UserRoleId) {
                                        $selectrole = 'selected';
                                      } else {
                                        $selectrole = '';
                                      }
                                      ?>
                                      <option value="<?php echo $row->UserRoleId; ?>" <?php echo $selectrole; ?>><?php echo $row->Role; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">User:</label>
                                <input type="text" id="filter_name" name="filter_name" class="form-control dt-input" data-column="2" placeholder="Eg: Ahmad"
                                  value="<?php if ($this->session->userdata('Filter_Name') !=null) echo $this->session->userdata('Filter_Name'); ?>" data-column-index="1">
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Category:</label>
                                  <select id="filter_category" name="filter_category" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach ($category as $row) {
                                      if ($this->session->userdata('Filter_Category') != null && $this->session->userdata('Filter_Category') == $row->VoucherActivityCategoryId) {
                                        $selectrole = 'selected';
                                      }else {
                                        $selectrole = '';
                                      }
                                      ?>
                                      <option value="<?php echo $row->VoucherActivityCategoryId; ?>" <?php echo $selectrole ?>><?php echo $row->VoucherActivityCategoryName; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Voucher Number:</label>
                                <input type="text" id="filter_voucherno" name="filter_voucherno" class="form-control dt-input" data-column="2" placeholder="Enter Voucher Number"
                                  value="<?php if ($this->session->userdata('Filter_VoucherNumber') !=null) echo $this->session->userdata('Filter_VoucherNumber'); ?>" data-column-index="1">
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Filter Range Date Between:</label>
                                    <input type="date" id="date_from" class="form-control" name="date_from"
                                    value="<?php if ($this->session->userdata('Filter_DateFrom') != null) echo $this->session->userdata('Filter_DateFrom'); ?>">
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4" style="margin-top:40px">
                                  <input type="date" id="date_to" class="form-control" name="date_to"
                                  value="<?php if ($this->session->userdata('Filter_DateTo') != null) echo $this->session->userdata('Filter_DateTo'); ?>">
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4" style="margin-top:15px; margin-right:20px">
                                <button type="button" id="btnsearch" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Search</button>
                                <button type="reset" id="btn-reset" class="btn btn-label-secondary mb-1" style="margin-left:10px">Reset</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                   </div>
                 </div>
               </div>
             </div>

              <!-- Voucher List Table -->
              <div class="card">
                <div class="card-header border-bottom">
                  <div class="d-flex justify-content-between">
                    <div class="">
                      <h4>Voucher Logs</h4>
                    </div>

                    <?php
                    $export = $this->RolePermission_Model->submenu_master(20);
                    if ($export->View == 1 ) { ?>

                    <div class="dt-buttons">
                      <button type="button" class="dt-button buttons-collection btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                        style="margin-left:10px; margin-right:10px">
                        <span>
                          <i class="bx bx-upload me-2">
                          </i>Export
                        </span>
                      </button>

                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo base_url('logs/voucherlog/export_voucherlog_csv'); ?>">
                          <i class="bx bx-file me-2"></i>
                          Csv
                        </a>
                      </li>
                      </ul>
                     </div>
                   <?php }  ?>
                  </div>

                <div class="card-datatable table-responsive" >
                  <table class="datatables-stores table border-top" id="voucherlogtable">
                    <thead style="background-color:#122620">
                      <tr>
                        <th  style="color:white">User</th>
                        <th  style="color:white">Role</th>
                        <th  class="col-1" style="color:white">Activity Date</th>
                        <th  style="color:white">Category</th>
                        <th  style="color:white">Details</th>
                        <th  style="color:white">Voucher Number</th>
                        <th  style="color:white">Voucher Type</th>
                      </tr>
                    </thead>
                  </table>
                  </div>
                  </div>
                </div>
              </div>

              <!-- Modal for Voucher Number Details -->
              <div class="modal fade show" id="voucherDetailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="voucherDetailsLabel">Voucher Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="divider">
                        <div class="divider-text" style="font-weight:bolder">Current Voucher Details</div>
                      </div>
                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="voucherNumber">Voucher Number</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="voucherNumber" disabled="">
                        </div>
                      </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="receiptNumber">Receipt Number</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="receiptNumber" disabled>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="receiptDatetime">Receipt Date/Time</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="receiptDatetime"  disabled>
                          </div>
                        </div>

                        <div class="divider" id="redeemdivider">
                          <div class="divider-text" style="font-weight:bolder">Redemption Details</div>
                        </div>

                        <div class="row mb-3" id="redemption">
                          <div class="row mb-3" id="receiptredeem">
                            <label class="col-sm-2 col-form-label" for="redeemreceipt">Redemption Receipt Number</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="redeemreceipt"  disabled>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="posNumber">Redemption POS Number</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="posNumber"  disabled>
                            </div>
                          </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="redemptionStore">Redemption Store</label>
                          <div class="col-sm-10">
                            <input type="text" id="redemptionStore" class="form-control" disabled>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="redemptionDate">Redemption Date/Time</label>
                          <div class="col-sm-10">
                            <input type="text" id="redemptionDate" class="form-control"  disabled>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="redemptionBy">Redemption By</label>
                          <div class="col-sm-10">
                            <input type="text" id="redemptionBy" class="form-control"  disabled>
                          </div>
                        </div>
                      </div>

                      <div class="divider">
                        <div class="divider-text" style="font-weight:bolder">Issuance Voucher Details</div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="receiptNumber">Receipt Number</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="originalreceiptNumber" disabled>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="originalreceiptDatetime">Receipt Date/Time</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="originalreceiptDatetime"  disabled>
                        </div>
                      </div>

                      <!-- <div class="row mb-3" id="issuedcard"> -->
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="cardnumber">Issued Card Number</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="cardnumber" disabled="">
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="cardid">Issued Card ID</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="cardid" disabled="">
                          </div>
                        </div>
                      <!-- </div> -->

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="activationTime">Voucher Created Date</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="activationTime" disabled>
                        </div>
                      </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="terminalId">Issued Terminal ID</label>
                          <div class="col-sm-10">
                            <input type="text" id="terminalId" class="form-control" disabled>
                          </div>
                        </div>
                      </div>

                    <div class="modal-footer">
                      <input type="hidden" name="loggerid" id="loggerid" value="">
                      <input type="hidden" name="voucherissuanceid" id="voucherIssuanceId" value="">
                      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Close
                      </button>
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

      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

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
    $.fn.dataTable.ext.errMode = 'none';

    // voucher log table
    get_datatables();
    function get_datatables() {
      var voucherlogtable = $('#voucherlogtable').DataTable({
      //   columnDefs: [
      //     {targets: [-1], orderable: false}],
        "ordering": false,
        "searching": false,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "language": {
            "infoFiltered": ""
        },
        "ajax": {
            "url": "<?php echo base_url(); ?>logs/voucherlog/voucherloglisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash           = $('.txt_csrfname').val();
              // console.log(csrfHash);
              var filter_role        = $('#filter_role').val();
              var filter_name        = $('#filter_name').val();
              var filter_category    = $('#filter_category').val();
              var filter_voucherno   = $('#filter_voucherno').val();
              var date_from          = $('#date_from').val();
              var date_to            = $('#date_to').val();

              data.<?= $csrf['name']; ?> = csrfHash;
              data.filter_role           = filter_role;
              data.filter_name           = filter_name;
              data.filter_category       = filter_category;
              data.filter_voucherno      = filter_voucherno;
              data.date_from             = date_from;
              data.date_to               = date_to;
            }
        },
        "bDestroy": true,
      });
        voucherlogtable.on('xhr.dt', function ( e, settings, json, xhr ) {
          if (json != null) {
            $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
            var csrfHash = $('.txt_csrfname').val();
          }else {
            getToken();
          }
        });
      }

      $('#btnsearch').click(function() {
        get_datatables();

        var filter_role        = $('#filter_role').val();
        var filter_name        = $('#filter_name').val();
        var filter_category    = $('#filter_category').val();
        var filter_voucherno   = $('#filter_voucherno').val();
        var date_from          = $('#date_from').val();
        var date_to            = $('#date_to').val();

        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();

        $.ajax({
          url: '<?php echo base_url('logs/voucherlog/filtervoucherlog'); ?>',
          type: 'POST',
          data: {filter_role: filter_role, filter_name: filter_name, filter_category: filter_category, date_from: date_from,
                date_to: date_to, filter_voucherno:filter_voucherno, [csrfName]: csrfHash},
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
          }
        });
        return false;
      });

      $('#btn-reset').click(function() {
        $('#filter_name').val('');
        $('#filter_voucherno').val('');
        $('#date_from').val('');
        $('#date_to').val('');
        $('#filter_role').prop('selectedIndex',0);
        $('#filter_category').prop('selectedIndex',0);
        get_datatables();
        // location.reload(true);
      });

        //ajax for carry id to edit form
        $('#voucherlogtable').on('click','.details',function() {
          var voucherissuanceid = $(this).data('voucherissuanceid');
          var type              = $(this).data('type');
          var csrfHash          = $('.txt_csrfname').val();

          $.ajax({
            url: "<?php echo base_url(); ?>logs/voucherlog/voucherDetails",
            type: "POST",
            data: {voucherissuanceid:voucherissuanceid,type:type, <?= $csrf['name']; ?> : csrfHash},
            dataType: 'json',
            success:function(data)
            {
              $('.txt_csrfname').val(data.token);

              $('#loggerid').val(data.details.AppLoggerId);
              $('#voucherNumber').val(data.details.VouchersNumber);
              $('#receiptNumber').val(data.details.RealReceiptNumber);
              $('#redeemreceipt').val(data.details.ReceiptNumber);
              $('#receiptDatetime').val(data.details.RealReceiptDateTime);
              $('#originalreceiptNumber').val(data.details.OriginalReceiptNumber);
              $('#originalreceiptDatetime').val(data.details.OriginalReceiptDateTime);
              $('#posNumber').val(data.details.RealPOSNumber);
              $('#redemptionStore').val(data.details.RedemptionStore);
              $('#activationTime').val(data.details.CreatedDate);
              $('#redemptionDate').val(data.details.RedemptionDateTime);
              $('#redemptionBy').val(data.details.RedemptionBy);
              $('#cardnumber').val(data.details.CardNumber);
              $('#cardid').val(data.details.CardId);
              $('#terminalId').val(data.details.OriginalPOSNumber);
              $('#reactivedate').val(data.details.ReactiveDate);
              $('#voucherIssuanceId').val(data.details.VoucherIssuanceId);

              $('#voucherDetailsModal').modal('show');

              if (data.details.VoucherActivityCategoryId == 3) {
                $('#redemption').show();
                $('#redeemdivider').show();
              } else {
                $('#redemption').hide();
                $('#redeemdivider').hide();
              }
            },
            error: function(xhr, status, error) {
              snack('Something went wrong. Please try again later','danger');
            },
          });
        });
      });
  </script>
  </body>
</html>
