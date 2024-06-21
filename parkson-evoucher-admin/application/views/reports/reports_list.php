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
    <title>Report</title>
    <?php echo $header; ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
                      <h4 class="content-header-title float-left pr-1 mb-0">REPORT</h4>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Report</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >List of Reports</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Users Report -->
              <?php
              $userReport = $this->RolePermission_Model->submenu_master(24);
              if ($userReport->View == 1) { ?>
                <div class="card">
                  <div class="card-header border-bottom">
                    <div class="col-md-12 col-12">
                      <h5>Users Report</h5>
                        <h8><strong>Filter</strong></h8>

                        <form class="dt_adv_search" method="POST" id="userfilter">
                          <div class="row">
                            <div class="col-12">
                              <div class="row g-3">
                                  <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Filter Date In Between:</label>
                                        <input type="date" id="user_datefrom" class="form-control" name="user_datefrom" placeholder="yyyy/mm/dd" value="<?php if ($this->session->userdata('User_Filter_Startdate') != null) echo $this->session->userdata('User_Filter_Startdate'); ?>">
                                  </div>

                                  <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px">
                                      <input type="date" id="user_dateto" class="form-control" name="user_dateto" placeholder="yyyy/mm/dd" value="<?php if ($this->session->userdata('User_Filter_Enddate') != null) echo $this->session->userdata('User_Filter_Enddate'); ?>">
                                  </div>

                                    <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px; margin-right:20px">
                                      <button id="btn_filter_user" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Filter</button>
                                      <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>
                                    </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>

                    <div class="pt-4">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-stores table border-top table mb-0" id="user">
                          <thead style="background-color:#122620">
                            <tr>
                              <th style="color:white">Report</th>
                              <th class="col-2" style="color:white">Action</th>
                            </tr>
                          </thead>

                          <tbody class="table-border-bottom-1">
                            <tr>
                              <td>Users</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/export_user_report'); ?>">Download Report</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- / Users Report -->
              <?php } ?>

              <!-- Voucher Report -->
              <div class="pt-4">
                <?php
                $showVoucherReport = false;
                $vouchers = [
                  $this->RolePermission_Model->submenu_master(25),
                  $this->RolePermission_Model->submenu_master(26),
                  $this->RolePermission_Model->submenu_master(27),
                  $this->RolePermission_Model->submenu_master(28),
                  $this->RolePermission_Model->submenu_master(29),
                  $this->RolePermission_Model->submenu_master(30),
                  $this->RolePermission_Model->submenu_master(31)
                ];

                foreach ($vouchers as $voucher) {
                  if ($voucher->View == 1) {
                    $showVoucherReport = true;
                    break;
                  }
                }

                if ($showVoucherReport) { ?>
                  <div class="card">
                    <div class="card-header border-bottom">
                      <div class="col-md-12 col-12">
                        <h5>Vouchers Report</h5>
                          <h8><strong>Filter</strong></h8>

                          <form class="dt_adv_search" method="POST">
                            <div class="row">
                              <div class="col-12">
                                <div class="row g-4">
                                <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                  <label class="form-label">Filter By Voucher Type:</label>
                                  <select class="form-select" name="vouchertypefilter" id="vouchertypefilter">
                                    <option value="0">All</option>
                                    <?php foreach ($filtertype as $row) { ?>
                                      <option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                  <label class="form-label">Filter By Voucher Status:</label>
                                    <select class="form-select" name="voucherstatusfilter" id="voucherstatusfilter">
                                      <option value="0">All</option>
                                      <?php foreach ($filterstatus as $row) { ?>
                                        <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                      <?php } ?>
                                    </select>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                  <label class="form-label">Filter By Reasons:</label>
                                  <select class="form-select" name="reasonfilter" id="reasonfilter">
                                    <option value="0">All</option>
                                    <?php foreach ($filterreason as $row) { ?>
                                      <option value="<?php echo $row->ReasonId; ?>"><?php echo $row->ReasonName; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                  <label class="form-label" for="receiptnofilter">Filter by Receipt Number</label>
                                  <input type="text" id="receiptnofilter" name="receiptnofilter" class="form-control dt-input" data-column="2" placeholder="Enter Receipt Number" data-column-index="1"
                                  value="<?php if($this->session->userdata('Voucher_Filter_ReceiptNo') != null) echo $this->session->userdata('Voucher_Filter_ReceiptNo'); ?>">
                                </div>
                              </div>

                              <div class="row mt-2">
                              <?php if ($permission->UserRoleId == 1 || $permission->StoreId == 0) { ?>
                                  <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Filter By Store:</label>
                                    <select class="form-select" name="storefilter" id="storefilter">
                                      <option value="0">All</option>
                                      <?php foreach ($filterstore as $row) { ?>
                                        <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                              <?php } ?>

                              <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label">Filter By Campaign Type:</label>
                                <select class="form-select" name="campaignfilter" id="campaignfilter">
                                  <option value="0">All</option>
                                  <?php foreach ($filtercampaign as $row) { ?>
                                    <option value="<?php echo $row->CampaignId; ?>"><?php echo $row->CampaignId. " - ".$row->CampaignName; ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label">Filter By Terminal ID:</label>
                                <select class="form-select" name="terminalfilter" id="terminalfilter">
                                  <option value="ALL">All</option>
                                  <option value="SYSTEM">SYSTEM</option>
                                  <option value="POS">POS</option>
                                </select>
                              </div>
                            </div>

                            <div class="row mt-2">
                              <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label">Filter Date In Between:</label>
                                    <input placeholder="dd / mm / yy" id="voucher_datefrom" class="form-control voucher_datefrom" name="voucher_datefrom" value="<?php if ($this->session->userdata('Voucher_Filter_Startdate') != null) echo $this->session->userdata('Voucher_Filter_Startdate'); ?>">
                              </div>

                              <div class="col-12 col-sm-6 col-lg-3" style="margin-top:3px">
                                <label class="form-label"></label>
                                  <input placeholder="dd / mm / yy" id="voucher_dateto" class="form-control voucher_dateto" name="voucher_dateto" value="<?php if ($this->session->userdata('Voucher_Filter_Enddate') != null) echo $this->session->userdata('Voucher_Filter_Enddate'); ?>">
                              </div>
                            </div>

                              <div class="col-12 col-sm-6 col-lg-3" style="margin-top:20px">
                                <button id="btn_filter_voucher" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Filter</button>
                                <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>

                      <div class="pt-4">
                        <div class="card-datatable table-responsive">
                          <table class="datatables-stores table border-top table mb-0" id="user">
                            <thead style="background-color:#122620">
                              <tr>
                                <th style="color:white">Report</th>
                                <th class="col-2" style="color:white">Action</th>
                              </tr>
                            </thead>

                            <tbody class="table-border-bottom-1">
                              <?php
                               if ($vouchers[0]->View == 1) { ?>
                               <tr>
                                 <td>Vouchers Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_voucher_report'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php }?>

                               <?php
                               if ($vouchers[1]->View == 1) { ?>
                               <tr>
                                 <td>Duplicate Receipt Over Issuing Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_duplicatereceipt_report'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>

                               <?php
                               if ($vouchers[2]->View == 1) { ?>
                               <tr>
                                 <td>Vouchers Duplicate Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_duplicatevoucher_report');  ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>

                               <?php
                               if ($vouchers[3]->View == 1) { ?>
                               <tr>
                                 <td>Reconciliation Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_reconciliation_report'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>

                               <?php
                               if ($vouchers[4]->View == 1) { ?>
                               <tr>
                                 <td>Unredeemed & Not Expired Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_notexpired_report'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>

                               <?php
                               if ($vouchers[5]->View == 1) { ?>
                               <tr>
                                 <td>Unredeemed & Expired Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_expired_report'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>

                               <?php
                               if ($vouchers[6]->View == 1) { ?>
                               <tr>
                                 <td>Voucher Issuance by Campaign Report</td>
                                 <td>
                                   <a href="<?php echo base_url('reports/Reports/export_voucherissuance_campaign'); ?>">Download Report</a>
                                 </td>
                               </tr>
                               <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
              <!-- / Voucher Report -->

            <!-- Gift Voucher Report -->
            <div class="pt-4">
              <?php
              $showGVReport = false;
              $giftvouchers = [
                $this->RolePermission_Model->submenu_master(32),
                $this->RolePermission_Model->submenu_master(33),
              ];

              foreach ($giftvouchers as $giftvoucher) {
                if ($giftvoucher->View == 1) {
                  $showGVReport = true;
                  break;
                }
              }
              if ($showGVReport) { ?>
                <div class="card">
                  <div class="card-header border-bottom">
                    <div class="col-md-12 col-12">
                      <h5>Gift Vouchers Report</h5>
                        <h8><strong>Filter</strong></h8>

                        <form class="dt_adv_search" method="POST">
                          <div class="row">
                          <div class="col-12">
                            <div class="row g-4">
                              <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                <label class="form-label">Filter By Voucher Status:</label>
                                  <select class="form-select" name="giftstatus" id="giftstatus">
                                    <option value="0">All</option>
                                    <?php foreach ($filterstatus as $row) { ?>
                                      <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-3 mt-4">
                                <label class="form-label">Filter By Reasons:</label>
                                <select class="form-select" name="giftreason" id="giftreason">
                                  <option value="0">All</option>
                                  <?php foreach ($filterreason as $row) { ?>
                                    <option value="<?php echo $row->ReasonId; ?>"><?php echo $row->ReasonName; ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label">Filter By Terminal ID:</label>
                                <select class="form-select" name="giftterminal" id="giftterminal">
                                  <option value="ALL">All</option>
                                  <option value="SYSTEM">SYSTEM</option>
                                  <option value="POS">POS</option>
                                </select>
                              </div>

                              <?php if ($permission->UserRoleId == 1 || $permission->StoreId == 0) { ?>
                                  <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Filter By Store:</label>
                                    <select class="form-select" name="giftstore" id="giftstore">
                                      <option value="0">All</option>
                                      <?php foreach ($filterstore as $row) { ?>
                                        <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                              <?php } ?>
                            </div>

                          <div class="row mt-2">
                            <div class="col-12 col-sm-6 col-lg-3 mt-2">
                              <label class="form-label" for="giftreceiptno">Filter by Receipt Number</label>
                              <input type="text" id="giftreceiptno" name="giftreceiptno" class="form-control dt-input" data-column="2" placeholder="Enter Receipt Number" data-column-index="1"
                              value="<?php if($this->session->userdata('Gift_Filter_ReceiptNo') != null) echo $this->session->userdata('Gift_Filter_ReceiptNo'); ?>">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3 mt-2">
                              <label class="form-label">Filter By Batch Number:</label>
                              <input type="text" id="batchnumber" name="batchnumber" class="form-control dt-input" placeholder="Enter Batch Number"
                              value="<?php if($this->session->userdata('Gift_Filter_BatchNumber') != null) echo $this->session->userdata('Gift_Filter_BatchNumber'); ?>">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3 mt-2">
                              <label class="form-label">Filter By Pos Number:</label>
                              <input type="text" id="posnumber" name="posnumber" class="form-control dt-input" placeholder="Enter POS Number"
                              value="<?php if($this->session->userdata('Gift_Filter_POSNumber') != null) echo $this->session->userdata('Gift_Filter_POSNumber'); ?>">
                            </div>
                          </div>

                          <div class="row mt-2">
                            <div class="col-12 col-sm-6 col-lg-3 mt-2">
                              <label class="form-label">Filter Date In Between:</label>
                                <input placeholder="dd / mm / yy" id="gift_datefrom" class="form-control gift_datefrom1" name="gift_datefrom" value="<?php if ($this->session->userdata('Gift_Filter_Startdate') != null) echo $this->session->userdata('Gift_Filter_Startdate'); ?>">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3" style="margin-top:10px">
                              <label class="form-label"></label>
                              <input placeholder="dd / mm / yy" id="gift_dateto" class="form-control gift_datefrom1" name="gift_dateto" value="<?php if ($this->session->userdata('Gift_Filter_Enddate') != null) echo $this->session->userdata('Gift_Filter_Enddate'); ?>">
                            </div>
                          </div>

                            <div class="col-12 col-sm-6 col-lg-3" style="margin-top:20px">
                              <button id="btn_filter_gift" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Filter</button>
                              <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                    <div class="pt-4">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-stores table border-top table mb-0" id="user">
                          <thead style="background-color:#122620">
                            <tr>
                              <th style="color:white">Report</th>
                              <th class="col-2" style="color:white">Action</th>
                            </tr>
                          </thead>

                          <tbody class="table-border-bottom-1">
                            <?php
                            if ($giftvouchers[0]->View == 1) { ?>
                            <tr>
                              <td>Gift Vouchers Upload</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/export_giftvoucherupload_report'); ?>">Download Report</a>
                              </td>
                            </tr>
                            <?php } ?>

                            <?php
                            if ($giftvouchers[1]->View == 1) { ?>
                            <tr>
                              <td>Gift Vouchers Report</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/giftvoucher_report'); ?>">Download Report</a>
                              </td>
                            </tr>
                            <?php } ?>

                            <tr>
                              <td>Duplicate Receipt Over Issuing Report</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/gift_duplicatereceipt_report'); ?>">Download Report</a>
                              </td>
                            </tr>

                            <!-- <tr>
                              <td>Gift Vouchers Duplicate Report</td>
                              <td>
                                <a href="<?php //echo base_url('reports/Reports/giftvoucher_duplicate_report');  ?>">Download Report</a>
                              </td>
                            </tr> -->

                            <tr>
                              <td>Gift Vouchers Reconciliation Report</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/gift_reconciliation_report'); ?>">Download Report</a>
                              </td>
                            </tr>

                            <tr>
                              <td>Unredeemed & Not Expired Report</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/gift_unredeemed_notexpired'); ?>">Download Report</a>
                              </td>
                            </tr>

                            <tr>
                              <td>Unredeemed & Expired Report</td>
                              <td>
                                <a href="<?php echo base_url('reports/Reports/gift_unredeemed_expired'); ?>">Download Report</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            <!-- / Gift Voucher Report -->

            <!-- Paper Vouchers Report -->
            <div class="pt-4">
            <div class="card">
              <div class="card-header border-bottom">
                <div class="col-md-12 col-12">
                  <h5>Paper Vouchers Report</h5>
                    <h8><strong>Filter</strong></h8>

                    <form class="dt_adv_search" method="POST">
                      <div class="row">
                      <div class="col-12">
                        <div class="row g-4">
                          <div class="col-12 col-sm-6 col-lg-3 mt-4">
                            <label class="form-label">Filter By Voucher Type:</label>
                            <select class="form-select" name="vouchertypefilter" id="vouchertypefilter">
                              <option value="0">All</option>
                              <?php foreach ($filtertype as $row) { ?>
                                <option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>
                              <?php } ?>
                            </select>
                          </div>

                          <div class="col-12 col-sm-6 col-lg-3 mt-4">
                            <label class="form-label">Filter By Voucher Status:</label>
                              <select class="form-select" name="voucherstatusfilter" id="voucherstatusfilter">
                                <option value="0">All</option>
                                <?php foreach ($filterstatus as $row) { ?>
                                  <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                <?php } ?>
                              </select>
                          </div>

                          <div class="col-12 col-sm-6 col-lg-3 mt-4">
                            <label class="form-label">Filter By Reasons:</label>
                            <select class="form-select" name="reasonfilter" id="reasonfilter">
                              <option value="0">All</option>
                              <?php foreach ($filterreason as $row) { ?>
                                <option value="<?php echo $row->ReasonId; ?>"><?php echo $row->ReasonName; ?></option>
                              <?php } ?>
                            </select>
                          </div>

                          <div class="col-12 col-sm-6 col-lg-3 mt-4">
                            <label class="form-label" for="receiptnofilter">Filter by Receipt Number</label>
                            <input type="text" id="receiptnofilter" name="receiptnofilter" class="form-control dt-input" data-column="2" placeholder="Enter Receipt Number" data-column-index="1"
                            value="<?php if($this->session->userdata('Voucher_Filter_ReceiptNo') != null) echo $this->session->userdata('Voucher_Filter_ReceiptNo'); ?>">
                          </div>
                        </div>

                      <div class="row mt-2">
                        <?php if ($permission->UserRoleId == 1 || $permission->StoreId == 0) { ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                              <label class="form-label">Filter By Store:</label>
                              <select class="form-select" name="storefilter" id="storefilter">
                                <option value="0">All</option>
                                <?php foreach ($filterstore as $row) { ?>
                                  <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        <?php } ?>

                        <div class="col-12 col-sm-6 col-lg-3">
                          <label class="form-label">Filter By Terminal ID:</label>
                          <select class="form-select" name="terminalfilter" id="terminalfilter">
                            <option value="ALL">All</option>
                            <option value="SYSTEM">SYSTEM</option>
                            <option value="POS">POS</option>
                          </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                          <label class="form-label">Filter Date In Between:</label>
                              <input placeholder="dd / mm / yy" id="paper_datefrom" class="form-control paper_datefrom" name="paper_datefrom" value="<?php if ($this->session->userdata('Paper_Filter_Startdate') != null) echo $this->session->userdata('Paper_Filter_Startdate'); ?>">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3" style="margin-top:3px">
                          <label class="form-label"></label>
                            <input placeholder="dd / mm / yy" id="paper_dateto" class="form-control paper_dateto" name="paper_dateto" value="<?php if ($this->session->userdata('Paper_Filter_Enddate') != null) echo $this->session->userdata('Paper_Filter_Enddate'); ?>">
                        </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3" style="margin-top:20px">
                          <button id="btn_filter_pvoucher" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Filter</button>
                          <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="pt-4">
                  <div class="card-datatable table-responsive">
                    <table class="datatables-stores table border-top table mb-0" id="user">
                      <thead style="background-color:#122620">
                        <tr>
                          <th style="color:white">Report</th>
                          <th class="col-2" style="color:white">Action</th>
                        </tr>
                      </thead>

                      <tbody class="table-border-bottom-1">
                        <tr>
                          <td>Paper Vouchers Report</td>
                          <td>
                            <a href="<?php echo base_url('reports/Reports/export_voucher_report'); ?>">Download Report</a>
                          </td>
                        </tr>

                        <tr>
                          <td>Duplicate Receipt Over Issuing Report</td>
                          <td>
                            <a href="<?php echo base_url('reports/Reports/export_duplicatereceipt_report'); ?>">Download Report</a>
                          </td>
                        </tr>

                        <tr>
                          <td>Paper Vouchers Duplicate Report</td>
                          <td>
                            <a href="<?php echo base_url('reports/Reports/export_duplicatevoucher_report');  ?>">Download Report</a>
                          </td>
                        </tr>

                        <tr>
                          <td>Unredeemed & Not Expired Report</td>
                          <td>
                            <a href="<?php echo base_url('reports/Reports/export_notexpired_report'); ?>">Download Report</a>
                          </td>
                        </tr>

                        <tr>
                          <td>Unredeemed & Expired Report</td>
                          <td>
                            <a href="<?php echo base_url('reports/Reports/export_expired_report'); ?>">Download Report</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- / Paper Vouchers Report -->

            <!-- User Logs Report -->
            <?php
            $activity = $this->RolePermission_Model->submenu_master(34);
             if ($activity->View == 1) { ?>
               <div class="pt-4">
               <div class="card">
                 <div class="card-header border-bottom">
                   <div class="col-md-12 col-12">
                     <h5>User Activity Log Report</h5>
                       <h8><strong>Filter</strong></h8>

                       <form class="dt_adv_search" method="POST">
                         <div class="row">
                           <div class="col-12">
                             <div class="row g-3">
                                 <div class="col-12 col-sm-6 col-lg-3">
                                   <label class="form-label">Filter Date In Between:</label>
                                       <input type="date" id="userlog_datefrom" class="form-control" name="userlog_datefrom" value="<?php if ($this->session->userdata('UserLog_Filter_Startdate') != null) echo $this->session->userdata('UserLog_Filter_Startdate'); ?>">
                                 </div>

                                 <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px">
                                     <input type="date" id="userlog_dateto" class="form-control" name="userlog_dateto" value="<?php if ($this->session->userdata('UserLog_Filter_Enddate') != null) echo $this->session->userdata('UserLog_Filter_Enddate'); ?>">
                                 </div>

                                   <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px; margin-right:20px">
                                     <button id="btn_filter_userlog" class="btn btn-primary mb-1"style="background-color:#122620; border-color:#122620">Filter</button>
                                     <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>

                                   </div>
                             </div>
                           </div>
                         </div>
                       </form>
                     </div>

                   <div class="pt-4">
                     <div class="card-datatable table-responsive">
                       <table class="datatables-stores table border-top table mb-0" id="user">
                         <thead style="background-color:#122620">
                           <tr>
                             <th style="color:white">Report</th>
                             <th class="col-2" style="color:white">Action</th>
                           </tr>
                         </thead>

                         <tbody class="table-border-bottom-1">
                           <tr>
                             <td>User Log Report</td>
                             <td>
                               <a href="<?php echo base_url('reports/Reports/export_userlog_report'); ?>">Download Report</a>
                             </td>
                           </tr>
                         </tbody>
                       </table>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
               <!-- / User Logs Report -->
            <?php } ?>

            <!-- Voucher Log Report -->
            <?php
            $voucherlog = $this->RolePermission_Model->submenu_master(35);
            if ($voucherlog->View == 1) { ?>
              <div class="pt-4">
              <div class="card">
                <div class="card-header border-bottom">
                  <div class="col-md-12 col-12">
                    <h5>Voucher Log Report</h5>
                      <h8><strong>Filter</strong></h8>

                      <form class="dt_adv_search" method="POST">
                        <div class="row">
                          <div class="col-12">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-lg-3">
                                  <label class="form-label">Filter Date In Between:</label>
                                      <input type="date" id="voucherlog_datefrom" class="form-control" name="voucherlog_datefrom" value="<?php if ($this->session->userdata('VoucherLog_Filter_Startdate') != null) echo $this->session->userdata('VoucherLog_Filter_Startdate'); ?>">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px">
                                    <input type="date" id="voucherlog_dateto" class="form-control" name="voucherlog_dateto" value="<?php if ($this->session->userdata('VoucherLog_Filter_Enddate') != null) echo $this->session->userdata('VoucherLog_Filter_Enddate'); ?>">
                                </div>
                              </div>

                                <div class="row g-3 mt-0">
                                  <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Filter By Category Type:</label>
                                    <select class="form-select" name="categoryfilter" id="categoryfilter">
                                      <option value="0">All</option>
                                      <?php foreach ($filtercategory as $row) { ?>
                                        <option value="<?php echo $row->VoucherActivityCategoryId; ?>"><?php echo $row->VoucherActivityCategoryName; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                  <div class="col-12 col-sm-6 col-lg-3" style="margin-top:40px; margin-right:20px">
                                    <button id="btn_filter_voucherlog" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Filter</button>
                                    <button id="btn-reset" class="btn btn-label-secondary mb-1 btn-reset" type="button" style="margin-left:10px">Reset</button>
                                  </div>
                                </div>
                          </div>
                        </div>
                      </form>
                    </div>

                  <div class="pt-4">
                    <div class="card-datatable table-responsive">
                      <table class="datatables-stores table border-top table mb-0" id="user">
                        <thead style="background-color:#122620">
                          <tr>
                            <th style="color:white">Report</th>
                            <th class="col-2" style="color:white">Action</th>
                          </tr>
                        </thead>

                        <tbody class="table-border-bottom-1">
                          <tr>
                            <td>Voucher Log Report</td>
                            <td>
                              <a href="<?php echo base_url('reports/Reports/export_voucherlog_report'); ?>">Download Report</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <!-- / Voucher Log Report -->
            <?php } ?>
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

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script type="text/javascript">

  $(document).ready(function (){
    $('.btn-reset').click(function() {

      //user
      $('#user_datefrom').val('');
      $('#user_dateto').val('');

      //user log
      $('#userlog_datefrom').val('');
      $('#userlog_dateto').val('');

      //voucher log
      $('#voucherlog_datefrom').val('');
      $('#voucherlog_dateto').val('');

      //vouchers
      $('#vouchertypefilter').prop('selectedIndex',0);
      $('#voucherstatusfilter').prop('selectedIndex',0);
      $('#reasonfilter').prop('selectedIndex',0);
      $('#campaignfilter').prop('selectedIndex',0);
      $('#storefilter').prop('selectedIndex',0);
      $('#categoryfilter').prop('selectedIndex',0);
      $('#terminalfilter').prop('selectedIndex',0);
      $('#receiptnofilter').val('');
      $('#voucher_datefrom').val('');
      $('#voucher_dateto').val('');

      //gv
      $('#gift_datefrom').val('');
      $('#gift_dateto').val('');
      $('#giftreceiptno').val('');
      $('#batchnumber').val('');
      $('#posnumber').val('');
      $('#giftstatus').prop('selectedIndex',0);
      $('#giftreason').prop('selectedIndex',0);
      $('#giftstore').prop('selectedIndex',0);
      $('#giftterminal').prop('selectedIndex',0);

    });

    $('#btn_filter_user').click(function() {
      var user_dateto   = $('#user_dateto').val();
      var user_datefrom = $('#user_datefrom').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('reports/reports/filteruser'); ?>',
        type: 'POST',
        data: {user_dateto:user_dateto,user_datefrom:user_datefrom, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $('#btn_filter_voucher').click(function() {
      var voucher_dateto        = $('#voucher_dateto').val();
      var voucher_datefrom      = $('#voucher_datefrom').val();
      var vouchertypefilter     = $('#vouchertypefilter').val();
      var voucherstatusfilter   = $('#voucherstatusfilter').val();
      var reasonfilter          = $('#reasonfilter').val();
      var campaignfilter        = $('#campaignfilter').val();
      var storefilter           = $('#storefilter').val();
      var terminalfilter        = $('#terminalfilter').val();
      var receiptnofilter       = $('#receiptnofilter').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('reports/reports/filtervoucher'); ?>',
        type: 'POST',
        data: {voucher_dateto: voucher_dateto, voucher_datefrom: voucher_datefrom, vouchertypefilter: vouchertypefilter, voucherstatusfilter: voucherstatusfilter,
              reasonfilter: reasonfilter, campaignfilter: campaignfilter, storefilter:storefilter, terminalfilter:terminalfilter, receiptnofilter:receiptnofilter, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $('#btn_filter_userlog').click(function() {
      var userlog_dateto   = $('#userlog_dateto').val();
      var userlog_datefrom = $('#userlog_datefrom').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('reports/reports/filteruserlog'); ?>',
        type: 'POST',
        data: {userlog_dateto:userlog_dateto, userlog_datefrom:userlog_datefrom, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $('#btn_filter_voucherlog').click(function() {
      var voucherlog_dateto   = $('#voucherlog_dateto').val();
      var voucherlog_datefrom = $('#voucherlog_datefrom').val();
      var categoryfilter      = $('#categoryfilter').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('reports/reports/filtervoucherlog'); ?>',
        type: 'POST',
        data: {voucherlog_dateto:voucherlog_dateto, voucherlog_datefrom:voucherlog_datefrom, categoryfilter:categoryfilter, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $(".voucher_datefrom").datepicker({ //newStartDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $('.voucher_dateto').val(selected);
        setTimeout(function() {
          $('.voucher_dateto').focus();
        });
        $(".voucher_dateto").datepicker("option","minDate", selected)
      }
    });

    $(".voucher_dateto").datepicker({ //newEndDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $(".voucher_dateto").datepicker( "hide" );
        $(".voucher_datefrom").datepicker("option","maxDate", selected)
      }
    });

    $("#gift_datefrom").datepicker({ //newStartDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $('#gift_dateto').val(selected);
        setTimeout(function() {
          $('#gift_dateto').focus();
        });
        $("#gift_dateto").datepicker("option","minDate", selected)
      }
    });

    $("#gift_dateto").datepicker({ //newEndDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $("#gift_dateto").datepicker( "hide" );
        $("#gift_datefrom").datepicker("option","maxDate", selected)
      }
    });

    $(".paper_datefrom").datepicker({ //newStartDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $('.paper_dateto').val(selected);
        setTimeout(function() {
          $('.paper_dateto').focus();
        });
        $(".paper_dateto").datepicker("option","minDate", selected)
      }
    });

    $(".paper_dateto").datepicker({ //newEndDate datepicker
    numberOfMonths: 1,
    dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
        $(".paper_dateto").datepicker( "hide" );
        $(".paper_datefrom").datepicker("option","maxDate", selected)
      }
    });

    $('#btn_filter_gift').click(function() {
      var gift_datefrom    = $('#gift_datefrom').val();
      var gift_dateto      = $('#gift_dateto').val();
      var giftstatus       = $('#giftstatus').val();
      var giftreceiptno    = $('#giftreceiptno').val();
      var giftreason       = $('#giftreason').val();
      var batchnumber      = $('#batchnumber').val();
      var posnumber        = $('#posnumber').val();
      var giftstore        = $('#giftstore').val();
      var giftterminal     = $('#giftterminal').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('reports/reports/filter_giftvoucher'); ?>',
        type: 'POST',
        data: {gift_dateto: gift_dateto, gift_datefrom: gift_datefrom, giftstatus: giftstatus, giftreceiptno: giftreceiptno, giftreason: giftreason, giftstore: giftstore,
              giftterminal: giftterminal, batchnumber: batchnumber, posnumber: posnumber, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });
  });
  </script>
  </body>
</html>
