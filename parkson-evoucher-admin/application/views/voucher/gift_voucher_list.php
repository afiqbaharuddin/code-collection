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

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <title>Gift Vouchers</title>
    <?php echo $header; ?>
  </head>

  <style>
    body {
     background-color:#F1EDE3 ;
    }
    .ui-datepicker {
        background: #333;
        border: 1px solid #555;
        color: #fff;
    }
    .loadcard{
      width: 23px;
      height: 23px;
      border: 3px solid #C8D3DA;
      border-radius: 50%;
      display: inline-block;
      box-sizing: border-box;
      position: relative;
      animation: pulse 1s linear infinite;
    }
    .loadcard:after{
      content: '';
      position: absolute;
      width: 23px;
      height: 23px;
      border: 3px solid #C8D3DA;
      border-radius: 50%;
      display: inline-block;
      box-sizing: border-box;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      animation: scaleUp 1s linear infinite;
    }
    @keyframes scaleUp {
      0% { transform: translate(-50%, -50%) scale(0) }
      60% , 100% { transform: translate(-50%, -50%)  scale(1)}
    }
    @keyframes pulse {
      0% , 60% , 100%{ transform:  scale(1) }
      80% { transform:  scale(1.2)}
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
                      <h5 class="content-header-title float-left pr-1 mb-0">GIFT VOUCHER</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Gift Vouchers</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <?php if ($userCount->UserRoleId == 1 || $userCount->StoreId == 0){ ?>
                <div class="row g-4 mb-4">
                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Available Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_upload_today">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Issued Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_sold_today">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Void Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_void_today">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Redeemed Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_redeem_today">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-danger"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-success rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php }else{ ?>
                <div class="row g-4 mb-4">
                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Available Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_upload_today_store">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Issued Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_sold_today_store">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Void Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_void_today_store">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-success"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-6 col-xl-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                          <div class="content-left">
                            <span>Gift Vouchers Redeemed Today</span>
                            <div class="d-flex align-items-end mt-2">
                              <h4 class="mb-0 me-2" id="evouchers_redeem_today_store">
                                <span class="loadcard mt-1"></span>
                              </h4>
                              <small class="text-danger"></small>
                            </div>
                            <small>Vouchers</small>
                          </div>
                          <span class="badge bg-label-success rounded p-2">
                            <i class="bx bxs-coupon bx-sm"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>

              <!--  List Table -->
              <!-- <h5>List of Gift Vouchers</h5> -->
              <div class="row" style="margin-bottom:15px;">
                  <div class="col-md-12 col-12">
                    <div class="card">
                    <div class="card-body">
                      <h5>Search Filter</h5>

                      <form class="dt_adv_search" method="POST" id="filterform">
                        <div class="row">
                          <div class="col-12">
                            <div class="row g-3">

                              <div class="col-12 col-sm-6 col-lg-3">
                               <label for="select2Multiple" class="form-label">Filter By Store Code</label>
                                 <select class="form-select" name="StoreCode" id="StoreCode" placeholder="Select Store Code">
                                   <option value="">All</option>
                                   <?php foreach ($filterstore as $row) { ?>
                                     <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode.' - '.$row->StoreName; ?></option>
                                   <?php } ?>
                                 </select>
                             </div>

                             <div class="col-12 col-sm-6 col-lg-3">
                              <label for="select2Multiple" class="form-label">Filter by Voucher Status</label>
                                <select class="form-select" name="VoucherStatus" id="VoucherStatus">
                                  <option value="">All</option>
                                  <?php foreach ($filterstatus as $row) { ?>
                                    <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                               <div class="col-12 col-sm-6 col-lg-3">
                                 <label class="form-label" for="filterposnumber">Filter by POS Number</label>
                                 <input type="text" id="PosNumber" name="PosNumber" class="form-control" placeholder="Enter POS Number">
                               </div>

                               <div class="col-12 col-sm-6 col-lg-3">
                                 <label class="form-label" for="VoucherNumber">Filter by Voucher Number</label>
                                 <input type="text" id="VoucherNumber" name="VoucherNumber" class="form-control" placeholder="Enter Voucher Number">
                               </div>

                               <div class="col-12 col-sm-6 col-lg-3">
                                 <label class="form-label" for="ReceiptNumber">Filter by Receipt Number</label>
                                 <input type="text" id="ReceiptNumber" name="ReceiptNumber" class="form-control" placeholder="Enter Receipt Number">
                               </div>

                               <div class="col-12 col-sm-6 col-lg-3">
                                 <label class="form-label" for="BatchNumber">Filter by Batch Number</label>
                                 <input type="text" id="BatchNumber" name="BatchNumber" class="form-control" placeholder="Enter Batch Number">
                               </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                  <label value="all" class="form-label" id="allDate-label">Filter by Date Between:</label>
                                    <input type="datepicker" id="StartDate" class="form-control" name="StartDate" placeholder="<?php echo date('Y/m/d'); ?>">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px">
                                  <input type="datepicker" id="EndDate" class="form-control" name="EndDate" placeholder="<?php echo date('Y/m/d') ?>">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-6" style="margin-top:15px; margin-right:20px">
                                  <button id="btn-search" class="btn btn-primary mb-1" type="button" style="background-color:#122620; border-color:#122620">Search</button>
                                  <button id="btn-reset" class="btn btn-label-secondary mb-1" type="button" style="margin-left:10px">Reset</button>
                                </div>
                            </div>
                          </div>
                        </div>
                      </form>
                   </div>
                 </div>
               </div>
             </div>

              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <div class="">
                      <h4>List of Gift Voucher</h4>
                    </div>

                  <?php
                  $export = $this->RolePermission_Model->submenu_master(22);
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
                      <li><a class="dropdown-item" href="<?php echo base_url('voucher/Voucher/export_giftvoucher_csv'); ?>">
                        <i class="bx bx-file me-2"></i>
                        Csv
                      </a>
                    </li>
                    </ul>
                    <?php }  ?>

                    <?php
                    $import = $this->RolePermission_Model->submenu_master(21);
                    if ($import->View == 1 ) { ?>
                    <button class="dt-button add-new btn btn-dark" tabindex="0" aria-controls="DataTables_Table_0" type="button"
                             data-bs-toggle="dropdown" aria-expanded="false">
                      <span>
                        <i class="bx bx-plus me-0 me-lg-2"></i>
                        <span class="d-none d-lg-inline-block">Import</span>
                      </span>
                    </button>

                     <ul class="dropdown-menu">
                       <input type="hidden" name="importid">
                       <input type="hidden" name="loggerid">
                       <li>
                         <a class="dropdown-item" data-bs-target="#importmodal" data-bs-toggle="modal" href="">
                           <i class="bx bx-file me-2"></i>
                           Import Gift Voucher
                         </a>
                       </li>
                       <li>
                         <a class="dropdown-item" data-bs-target="#updatemodal" data-bs-toggle="modal" href="">
                           <i class="bx bx-file me-2"></i>
                           Update Gift Voucher
                         </a>
                       </li>
                       <li>
                         <a class="dropdown-item" href="<?php echo base_url('voucher/Voucher/import_giftvoucher_errorLog'); ?>">
                           <i class="bx bx-file me-2"></i>
                           Manual Import Gift Voucher Error Log
                         </a>
                       </li>
                       <li>
                         <a class="dropdown-item" href="<?php echo base_url('voucher/Voucher/import_giftvoucher_scheduler'); ?>">
                           <i class="bx bx-file me-2"></i>
                           Import Gift Voucher by Scheduler
                         </a>
                       </li>
                       <li>
                         <a class="dropdown-item" href="<?php echo base_url('voucher/Voucher/formatGiftReport'); ?>">
                         <i class="bx bx-file me-2"></i>
                         Download Voucher Format
                        </a>
                       </li>
                     </ul>
                     <?php }  ?>
                   </div>
                 </div>


                <div class="card-datatable table-responsive">
                  <table class="datatables table border-top" id="giftTable">
                    <thead style="background-color:#122620">
                      <tr>
                        <!-- <th></th> -->
                        <th style="color:white">Voucher num</th>
                        <th style="color:white">Upload Date</th>
                        <th style="color:white">Issued Date</th>
                        <th style="color:white">Voucher Type</th>
                        <th style="color:white">Voucher Value</th>
                        <th style="color:white">Exp Date</th>
                        <th style="color:white">Status</th>

                        <!-- <?php
                        $action     = $this->RolePermission_Model->submenu_master(8);

                        if ($action->Update == 1) { ?>
                        <th style="color:white">Action</th>
                        <?php }  ?> -->

                        <?php
                        $action     = $this->RolePermission_Model->menu_master(4);

                        if ($action->Update == 1) { ?>
                        <th style="color:white">Action</th>
                        <?php }  ?>
                      </tr>
                    </thead>
                  </table>
                  </div>
                </div>
              </div>

              <!--- Import Voucher Modal -->
              <div class="modal fade" id="importmodal" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <form class="modal-content" id="submitmodal" action="<?php echo base_url(); ?>voucher/voucher/import_giftvoucher_csv" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTopTitle">Import Gift Voucher</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col mb-3">
                          <label for="nameSlideTop" class="form-label">Upload Gift Voucher CSV File</label>
                          <input type="file" id="fileupload" name="file" class="form-control"/>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <button type="submit" id="import_btn" class="btn btn-primary data-submit" style="background-color:#122620; border-color:#122620">Import</button>
                      <span class="spinner-border me-1" id="spinner_border" role="status" aria-hidden="true"></span>
                    </div>

                    <div class="card card-header border-bottom">
                        <div class="table-responsive text-nowrap" id="errorline">
                        <h5 class="ms-6" style="text-align:center">ERROR FOUND</h5>
                        <table class="table border-top">
                          <thead style="background-color:#122620">
                            <tr>
                              <th style="color:white">Voucher Number</th>
                              <th style="color:white">Issued Store</th>
                              <th style="color:white">Reason</th>
                            </tr>
                          </thead>
                          <tbody id="errordata">

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <div class="modal fade" id="updatemodal" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <form class="modal-content" id="submitmodal" action="<?php echo base_url(); ?>voucher/voucher/update_csv" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTopTitle">Import Gift Voucher</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col mb-3">
                          <label for="nameSlideTop" class="form-label">Upload Gift Voucher CSV File</label>
                          <input type="file" id="fileupload" name="file" class="form-control"/>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                      <button type="submit" id="import_btn" class="btn btn-primary data-submit" style="background-color:#122620; border-color:#122620">Import</button>
                    </div>
                  </form>
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
    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
  <?php echo $bottom; ?>

  <script src="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>

  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>

    $(document).ready(function(){
      $.fn.dataTable.ext.errMode = 'none';

      $('#errorline').hide();

      // datatable vouchers
      get_datatables();
      function get_datatables() {
        var giftTable = $('#giftTable').DataTable({
          columnDefs: [
            {targets: [-1], orderable: false},
              ],
          "ordering": false,
          "searching": false,
          "processing": true,
          "serverSide": true,
          "scrollX": true,
          "language": {
              "infoFiltered": ""
          },
          "aaSorting": [],
          "ajax": {
              "url": "<?php echo base_url(); ?>voucher/Voucher/giftlisting",
              "type": "POST",
              "data": function(data) {
                var csrfHash       = $('.txt_csrfname').val();
                var StoreCode      = $('#StoreCode').val();
                var PosNumber      = $('#PosNumber').val();
                var ReceiptNumber  = $('#ReceiptNumber').val();
                var VoucherStatus  = $('#VoucherStatus').val();
                var StartDate      = $('#StartDate').val();
                var EndDate        = $('#EndDate').val();
                var VoucherNumber  = $('#VoucherNumber').val();
                var BatchNumber    = $('#BatchNumber').val();


                data.<?= $csrf['name']; ?> = csrfHash;
                data.StoreCode       = StoreCode;
                data.PosNumber       = PosNumber;
                data.ReceiptNumber   = ReceiptNumber;
                data.VoucherStatus   = VoucherStatus;
                data.StartDate       = StartDate;
                data.EndDate         = EndDate;
                data.VoucherNumber   = VoucherNumber;
                data.BatchNumber     = BatchNumber;
              }
          },
          "bDestroy": true,
        });
        giftTable.on('xhr.dt', function ( e, settings, json, xhr ) {
          if (json != null) {
            $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
          }else {
            getToken();
          }

          if (json.data.length != 0) {
            $('#giftTable_paginate').show();
          }else {
            $('#giftTable_paginate').hide();
          }
        });
      }

      $('#btn-search').click(function() {
        get_datatables();
      });

      $('#btn-reset').click(function() {

        var $dates = $('#StartDate, #EndDate').datepicker();
        $('#VoucherStatus').val(null).trigger('change');
        $('#StoreCode').val(null).trigger('change');
        $('#PosNumber').val('');
        $('#ReceiptNumber').val('');
        $('#VoucherNumber').val('');
        $('#BatchNumber').val('');

        $dates.datepicker('setDate', null);
        $dates.datepicker("option", {
          minDate: null,
          maxDate: null
        });

        get_datatables();
      });

      $('#spinner_border').hide();
      $('#import_btn').on('click', function(){
        $('#spinner_border').show();
      });

      $("#submitmodal").unbind('submit').bind('submit', function() {
       var form     = $(this);
       var formUp   = $(this)[0];
       var dataForm = new FormData(formUp);

       $.ajax({
          contentType: 'multipart/form-data',
          url: form.attr('action'),
          type: form.attr('method'),
          data: dataForm,
          dataType: 'json',
          contentType: false,
          processData: false,
          async:true,
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
            if (data.csv == true) {
              snack(data.message, 'success');
              $('#giftTable').DataTable().ajax.reload();
              $('#importmodal').modal('hide');
              $('#updatemodal').modal('hide');
            }else {
              snack(data.message, 'danger');
              snack('File has been uploaded. Total Line is ' + data.successImport, 'success');
              $('#errorline').show();
              $('#errordata').html(data.errorimport);
              $('#spinner_border').hide();
            }
          },
          error: function(xhr, status, error) {
            snack('Something went wrong. Please try again later','danger');
          },
        });
        return false;
      });


      $("#EndDate").datepicker({ //newEndDate datepicker
      numberOfMonths: 1,
      dateFormat: 'yy/mm/dd',
        onSelect: function(selected) {
          $("#EndDate").datepicker( "hide" );
          $("#StartDate").datepicker("option","maxDate", selected);
        }
      });

      $("#StartDate").datepicker({ //newStartDate datepicker
      numberOfMonths: 1,
      dateFormat: 'yy/mm/dd',
        onSelect: function(selected) {
          $('#EndDate').val(selected);
          setTimeout(function() {
            $('#EndDate').focus();
          },0);
          $("#EndDate").datepicker("option","minDate", selected);
        }
      });

      setTimeout(function(){
        $.ajax({
          url: '<?php echo base_url('voucher/Voucher/gift_infocard'); ?>',
          type: 'get',
          dataType: 'json',
          success:function(data)
          {
            //all store
            $('#evouchers_sold_today').html(data.evouchers_sold_today);
            $('#evouchers_void_today').html(data.evouchers_void_today);
            $('#evouchers_redeem_today').html(data.evouchers_redeem_today);
            $('#evouchers_upload_today').html(data.evouchers_upload_today);

            //by store
            $('#evouchers_sold_today_store').html(data.evouchers_sold_today_store);
            $('#evouchers_void_today_store').html(data.evouchers_void_today_store);
            $('#evouchers_redeem_today_store').html(data.evouchers_redeem_today_store);
            $('#evouchers_upload_today_store').html(data.evouchers_upload_today_store);
          }
        });
      },5000);
    });
  </script>
  </body>
</html>
