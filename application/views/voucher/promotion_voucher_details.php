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
    <title>Promotion & Discount Voucher Details</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">VOUCHER DETAILS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>voucher/Voucher/promotionlist">
                              <u>Vouchers</u>
                            </a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >Voucher Details</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Voucher Details -->

              <div class="row">
                <div class="col-xl-12">
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#editVoucher-pills" aria-controls="editVoucher-pills" aria-selected="true">
                          Edit Voucher
                        </button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#voucherDetails-pills" aria-controls="voucherDetails-pills" aria-selected="false">
                          Voucher Details
                        </button>
                      </li>
                    </ul>


                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="editVoucher-pills" role="tabpanel">
                        <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom:20px">
                          <h5 class="mb-0">Edit Voucher</h5>
                        </div>

                        <!-- tab 1 voucher edit -->
                        <div class="card-body">
                          <form id="voucherEditForm" action="<?php echo base_url(); ?>voucher/VoucherDetails/promotionEdit" method="post">
                            <input type="hidden" name="voucherId" value="<?php echo $voucher->VoucherId; ?>">

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="voucher-num">Voucher Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="voucher-num" value="<?php echo $voucher->VouchersNumber; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="date-created">Date Created</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="date-created" value="<?php echo $voucher->createddate; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="voucher-value">Voucher Value (RM)</label>
                              <div class="col-sm-10">
                                <input type="text" id="voucher-value" class="form-control" value="<?php echo $voucher->VouchersValue; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="redeemtype">Redeem Type</label>
                              <div class="col-sm-10">
                                <input type="text" id="redeemtype" name="redeemtype" class="form-control" value="<?php echo $voucher->RedeemTypeName; ?>" disabled>
                              </div>
                            </div>

                          <?php
                            $permissionSubmit = $this->voucher_Model->voucherPermissionEditVoucher($VoucherId);
                            $action     = $this->RolePermission_Model->submenu_master(9);

                            if ($permissionSubmit == 1 || $permissionSubmit == 3 ||  $permissionSubmit == 11){
                              if ($action->Update == 1) { ?>
                                <div class="row mb-0">
                                    <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                                    <div class="col-sm-10">
                                      <?php if ($voucher->VoucherStatusId == 6) {
                                         if ($permission->UnblockVouchersCheck == 0) { ?>
                                          <input type="text" name="" class="form-control" value="Block" disabled>
                                        <?php }
                                         if ($permission->UnblockVouchersCheck == 1) { ?>
                                          <select id="status" name="status" class="form-select">
                                            <option value="" hidden>Block</option>
                                          <?php foreach ($statusUnblockPermission as $row) { ?>
                                            <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                          <?php }
                                        }
                                      }
                                       if ($voucher->VoucherStatusId != 6): ?>
                                        <input type="hidden" name="voucherstatusid" id="voucherstatusid" value="<?php echo $voucher->VoucherStatusId; ?>">
                                        <select id="status" name="status" class="form-select">
                                        <option value="">Select Status</option>

                                        <?php
                                          $nonclick = [1,2,4,7,8,11];
                                           foreach ($status as $row) {
                                            if ($row->VoucherStatusId == $voucher->VoucherStatusId){
                                              $select = 'selected';
                                            }else {
                                              $select = '';
                                            }
                                            if ($voucher->VoucherStatusId == 6) {
                                              $unblockallow = 1;
                                              if ($unblockallow == 1) {
                                                $nonclick = [2,4,7,8,11];
                                                if (in_array($row->VoucherStatusId,$nonclick)){
                                                  $diss = 'disabled';
                                                }else {
                                                  $diss = '';
                                                }
                                              }else {
                                                if (in_array($row->VoucherStatusId,$nonclick)){
                                                  $diss = 'disabled';
                                                }else {
                                                  $diss = '';
                                                }
                                              }
                                            }else {
                                              if (in_array($row->VoucherStatusId,$nonclick)){
                                                $diss = 'disabled';
                                              }else {
                                                $diss = '';
                                              }
                                            }

                                              ?>
                                            <option value="<?php echo $row->VoucherStatusId; ?>"<?php echo $select.' '.$diss; ?>><?php echo $row->VoucherStatusName; ?></option>
                                          <?php } ?>

                                        <!-- <?php foreach ($status as $row) {
                                          if ($row->VoucherStatusId == $voucher->VoucherStatusId){
                                            $select = 'selected';
                                          }else {
                                            $select = '';
                                          }
                                            ?>
                                          <option value="<?php echo $row->VoucherStatusId; ?>"<?php echo $select; ?>><?php echo $row->VoucherStatusName; ?></option>
                                        <?php } ?>
                                      <?php endif; ?> -->
                                    </select>
                                  </div>
                                </div>

                                <?php if ($voucher->VoucherStatusId == 11){ ?>
                                  <div class="row mt-3 mb-3">
                                    <label class="col-sm-2 col-form-label">Reactive Date</label>
                                    <div class="col-sm-10">
                                      <input type="text"  class="form-control"  name="reactivedate" value="<?php if (isset($history) && $history->ReactiveDate != null) echo date('Y-m-d H:i:s', strtotime($history->ReactiveDate)); ?>" disabled/>
                                    </div>
                                  </div>
                                <?php } ?>

                                <div class="row mt-3 mb-3" id="expireddate_field">
                                  <label class="col-sm-2 col-form-label" for="expireddate">Expired Date</label>
                                  <div class="col-sm-10">
                                    <input type="date" id="expireddate" class="form-control"  min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" name="expireddate" value="<?php echo $voucher->ExpDate; ?>" disabled/>
                                  </div>
                                </div>

                                <?php if ($permission->NumExtendCheck == 1 ): ?>
                                <div class="row mt-3 mb-3"  id="extendDate_field">
                                    <label class="col-sm-2 col-form-label" for="extenddate">Extend Date</label>
                                    <div class="col-sm-10">
                                      <input type="date" id="extenddate" class="form-control"  min="<?php echo date('Y-m-d',strtotime($voucher->ExpDate)); ?>" max="<?php echo date('Y-m-d',strtotime($voucher->ExpDate.'+'.$permission->NumExtendDay.' day')); ?>"
                                      name="extenddate" value="<?php if (isset($history) && $history->ExtendDate != null) echo $history->ExtendDate; ?>"/>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($permission->NumExtendCheck == 0): ?>
                                  <input type="text" id="block-date" class="form-control" name="extenddate" value="" hidden/>
                                <?php endif; ?>

                                <?php if ($permission->BlockVouchersCheck == 1): ?>
                                <div id="blockDate_field">
                                  <div class="row mb-0">
                                      <label class="col-sm-2 col-form-label" for="block-date">Block Date</label>
                                      <div class="col-sm-10">
                                        <input type="date" id="block-date" class="form-control"  min="<?php echo date('Y-m-d'); ?>" max="<?php echo $voucher->ExpDate ?>" placeholder="E.g: 3/3/2022"
                                        aria-label="3/3/2022" name="blockDate" value="<?php if (isset($history) && $history->BlockDate != null) echo date('Y-m-d', strtotime($history->BlockDate)); ?>"/>
                                      </div>
                                  </div>

                                    <div class="row mt-3 mb-3">
                                      <label class="col-sm-2 col-form-label" for="block-reason">Block Reasons</label>
                                      <div class="col-sm-10">
                                      <select id="blockReason" name="blockReason" class="form-select">
                                        <?php foreach ($reasonBlock as $row) {
                                          if (isset($history) && $history->BlockReasons == $row->ReasonId) {
                                            $select = 'selected';
                                          }else {
                                            $select = '';
                                          }
                                          ?>
                                          <option value="<?php echo $row->ReasonId; ?>" <?php echo $select; ?>><?php echo $row->ReasonName; ?></option>
                                        <?php  } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($permission->BlockVouchersCheck == 0): ?>
                                  <input type="text" id="block-date" class="form-control" name="blockDate" value="" hidden/>
                                  <input type="text" id="block-date" class="form-control" name="blockReason" value="" hidden/>
                                <?php endif; ?>

                                <?php if ($permission->CancelVoucherCheck == 1): ?>
                                <div id="cancel_field">
                                  <div class="row mt-3 mb-3" id="cancel-expireddate-field">
                                    <label class="col-sm-2 col-form-label" for="canceldate">Cancel Date</label>
                                    <div class="col-sm-10">
                                      <input type="date" id="canceldate" class="form-control"  min="<?php echo date('Y-m-d'); ?>" max="<?php echo $voucher->ExpDate ?>" name="canceldate"
                                      value="<?php if (isset($history) && $history->CancelDate != null) echo date('Y-m-d', strtotime($history->CancelDate));  ?>"/>
                                    </div>
                                  </div>

                                  <div class="row mt-3 mb-3" id="cancel-reason-field">
                                    <label class="col-sm-2 col-form-label" for="cancelReason">Cancel Reasons</label>
                                    <div class="col-sm-10">
                                    <select id="cancelReason" name="cancelReason" class="form-select">
                                      <?php foreach ($reasonCancel as $row) {
                                        if (isset($history) && $history->CancelReasons == $row->ReasonId) {
                                          $select = 'selected';
                                        }else {
                                          $select = '';
                                        }
                                        ?>
                                        <option value="<?php echo $row->ReasonId; ?>" <?php echo $select; ?>><?php echo $row->ReasonName; ?></option>
                                      <?php  } ?>
                                    </select>
                                  </div>
                                </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($permission->CancelVoucherCheck == 0 ): ?>
                                  <input type="input" class="form-control" name="canceldate" value="" hidden/>
                                  <input type="input" class="form-control" name="cancelReason" value="" hidden/>
                                <?php endif; ?>
                              <?php } ?>
                            <?php } ?>

                        <?php
                        $action     = $this->RolePermission_Model->submenu_master(9);
                        //cannot edit data
                        if ($action->Update == 0 || ($permissionSubmit == 2 || $permissionSubmit == 4 || $permissionSubmit == 5 || $permissionSubmit == 6 || $permissionSubmit == 7 || $permissionSubmit == 8)){ ?>

                          <?php if ($voucher->VoucherStatusId != 2) { ?>
                            <div class="row mb-0">
                              <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                              <div class="col-sm-10">
                                <input type="text" name="status" class="form-control" value="<?php echo $voucher->VoucherStatusName; ?>" disabled>
                              </div>
                            </div>
                          <?php }else if ($voucher->VoucherStatusId == 2){ ?>
                            <div class="row mb-0">
                                <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                                <div class="col-sm-10">
                                  <select id="status" name="status" class="form-select">
                                    <?php foreach ($status as $row) {
                                      if ($row->VoucherStatusId == 2 || $row->VoucherStatusId == 3) {
                                        if ($row->VoucherStatusId == $voucher->VoucherStatusId){
                                          $select = 'selected';
                                        }else {
                                          $select = '';
                                        }
                                        ?>
                                        <option value="<?php echo $row->VoucherStatusId; ?>"<?php echo $select; ?>><?php echo $row->VoucherStatusName; ?></option>
                                      <?php } ?>
                                    <?php } ?>
                                </select>
                              </div>
                            </div>

                        <?php } ?>

                          <div class="row mt-3 mb-3" id="expireddate_field">
                            <label class="col-sm-2 col-form-label" for="expireddate">Expired Date</label>
                            <div class="col-sm-10">
                              <input type="date" id="expireddate" class="form-control" name="expireddate" value="<?php echo $voucher->ExpDate; ?>" disabled/>
                            </div>
                          </div>

                          <?php if ($permission->NumExtendCheck == 1 ){ ?>
                          <div class="row mt-3 mb-3"  id="extendDate_field">
                              <label class="col-sm-2 col-form-label" for="extenddate">Extend Date</label>
                              <div class="col-sm-10">
                                <input type="date" id="extenddate" class="form-control"  min="<?php echo date('Y-m-d',strtotime($voucher->ExpDate)); ?>" max="<?php echo date('Y-m-d',strtotime($voucher->ExpDate.'+'.$permission->NumExtendDay.' day')); ?>"
                                name="extenddate" value="<?php if (isset($history) && $history->ExtendDate != null) echo $history->ExtendDate; ?>"/>
                              </div>
                          </div>
                        <?php }else { ?>
                          <div class="row mt-3 mb-3"  id="extendDate_field">
                            <label class="col-sm-2 col-form-label" for="extenddate">Extend Date</label>
                            <div class="col-sm-10">
                              <input type="date" id="extenddate" class="form-control" name="extenddate" value="<?php if (isset($history) && $history->ExtendDate != null) echo $history->ExtendDate; ?> " disabled/>
                            </div>
                          </div>
                        <?php  } ?>

                          <div id="blockDate_field">
                            <div class="row mb-0">
                                <label class="col-sm-2 col-form-label" for="block-date">Block Date</label>
                                <div class="col-sm-10">
                                  <input type="date" id="block-date" class="form-control"  name="blockDate" value="<?php if (isset($history) && $history->BlockDate != null) echo date('Y-m-d', strtotime($history->BlockDate)); ?>" disabled/>
                                </div>
                            </div>

                            <div class="row mt-3 mb-3">
                              <label class="col-sm-2 col-form-label" for="block-reason">Block Reasons</label>
                              <div class="col-sm-10">
                                <input type="text" name="blockReason" class="form-control" value="<?php if (isset($history->ReasonName)) echo $history->ReasonName; ?>" disabled>
                              </div>
                            </div>
                          </div>

                          <div id="cancel_field">
                            <div class="row mt-3 mb-3" id="cancel-expireddate-field">
                                <label class="col-sm-2 col-form-label" for="canceldate">Cancel Date</label>
                                <div class="col-sm-10">
                                  <input type="date" id="canceldate" class="form-control" name="canceldate" value="<?php if (isset($history) && $history->CancelDate != null) echo date('Y-m-d', strtotime($history->CancelDate)); ?>" disabled/>
                                </div>
                            </div>

                            <div class="row mt-3 mb-3" id="cancel-reason-field">
                              <label class="col-sm-2 col-form-label" for="cancelReason">Cancel Reasons</label>
                              <div class="col-sm-10">
                                <input type="text" name="cancelReason" class="form-control"  value="<?php if (isset($history->ReasonName)) echo $history->ReasonName; ?>" disabled>
                              </div>
                          </div>
                        </div>
                      <?php }  ?>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="payload">Payload</label>
                        <div class="col-sm-10">
                          <textarea rows="10" type="text" id="payload" class="form-control" disabled><?php echo json_encode(json_decode($voucher->Payload),JSON_PRETTY_PRINT); ?></textarea>
                        </div>
                      </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="remark">Remarks</label>
                          <?php if ($action->Update == 0 || ($permissionSubmit == 2 || $permissionSubmit == 4 || $permissionSubmit == 5 || $permissionSubmit == 6 || $permissionSubmit == 7 || $permissionSubmit == 8)){ ?>
                            <div class="col-sm-10">
                              <textarea rows="3" id="remark" name="remark" class="form-control" readonly><?php echo $voucher->Remarks; ?></textarea>
                            </div>
                          <?php } else{ ?>
                            <div class="col-sm-10">
                              <textarea rows="3" id="remark" name="remark" class="form-control"><?php echo $voucher->Remarks; ?></textarea>
                            </div>
                          <?php }?>
                          </div>

                            <div class="row justify-content-end mt-3">
                              <div class="col-sm-10">
                                <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $voucher->AppLoggerId; ?>">
                                <input type="hidden" name="vouchertypeid" value="<?php echo $voucher->VoucherTypeId; ?>">
                                <input type="hidden" name="voucherid" value="<?php echo $voucher->VoucherId; ?>">
                                <input type="hidden" name="vouchersnumber" value="<?php echo $voucher->VouchersNumber; ?>">

                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                  // only allow when submit active, extend
                                  $action     = $this->RolePermission_Model->submenu_master(9);

                                  if ($permissionSubmit == 1 || $permissionSubmit == 2 || $permissionSubmit == 3 || $permissionSubmit == 11 ) {
                                     if ($action->Update == 1) { ?>
                                       <!-- if (($permission->NumExtendCheck == 1 || $voucher->VoucherStatusId == 1) || ($permission->NumExtendCheck == 1 && $voucher->VoucherStatusId == 2) || ($permission->NumExtendCheck == 1 || $voucher->VoucherStatusId == 3)) { ?> -->
                                         <button type="submit" id="btnEdit" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                         <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                  <?php }  ?>
                                <?php }  ?>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>


                      <!-- tab 2 voucher details -->
                      <div class="tab-pane fade" id="voucherDetails-pills" role="tabpanel">
                        <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom:20px">
                          <h5 class="mb-0">Voucher Details</h5>
                        </div>

                        <div class="divider">
                          <div class="divider-text" style="font-weight:bolder">Current Voucher Details</div>
                        </div>

                        <div class="card-body">
                          <form id="voucherDetailsForm" action="<?php echo base_url(); ?>voucher/VoucherDetails/editdetails2" method="post">
                            <input type="hidden" name="voucherId" value="<?php echo $voucher->VoucherId; ?>">

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="voucher-num">Voucher Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="voucher-num" value="<?php echo $voucher->VouchersNumber; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-num">Receipt Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="receipt-num" value="<?php if (isset($original->ReceiptNumber)) {echo $original->ReceiptNumber;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-datetime">Receipt Date/Time</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="receipt-datetime" value="<?php if (isset($original->ReceiptDateTime)) {echo $original->ReceiptDateTime;} ?>" disabled>
                              </div>
                            </div>

                            <?php if ($voucher->VoucherStatusId == 7) { ?>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="pos-num">Redemption POS Number</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="pos-num" value="<?php if (isset($original->POSNumber)) {echo $original->POSNumber;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-store">Redemption Store</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionStore" class="form-control"
                                  value="<?php if (isset($voucher->RedemptionStore)) {
                                    echo $voucher->RedemptionStore;
                                  } else {
                                    echo $original->StoreCode;
                                  }?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-date">Redemption Date/Time</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionDate" class="form-control"
                                  value="<?php if (!empty(isset($voucher->RedemptionDateTime))) {
                                    echo $voucher->RedemptionDateTime;
                                  } else {
                                    echo $original->ReceiptDateTime;
                                  }?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-by">Redemption By</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionBy" class="form-control"
                                  value="<?php if (!empty(isset($voucher->RedemptionBy))) {
                                    echo $voucher->RedemptionBy;
                                  } else {
                                    echo $original->CashierId;
                                  }?>" disabled>
                                </div>
                              </div>
                            <?php } ?>

                            <?php if ($voucher->VoucherStatusId == 11) { ?>
                              <div class="divider">
                                <div class="divider-text" style="font-weight:bolder">Redemption Details</div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="receipt-num">Redemption Receipt Number</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="receipt-num" value="<?php if (isset($redeemreactive->ReceiptNumber)) {echo $redeemreactive->ReceiptNumber;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="pos-num">Redemption POS Number</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="pos-num" value="<?php if (isset($original->POSNumber)) {echo $original->POSNumber;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-store">Redemption Store</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionStore" class="form-control" value="<?php if (isset($voucher->RedemptionStore)) {echo $voucher->RedemptionStore;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-date">Redemption Date/Time</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionDate" class="form-control" value="<?php if (isset($voucher->RedemptionDateTime)) {echo $voucher->RedemptionDateTime;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-by">Redemption By</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionBy" class="form-control" value="<?php if (isset($voucher->RedemptionBy)) {echo $voucher->RedemptionBy;} ?>" disabled>
                                </div>
                              </div>
                            <?php } ?>

                            <div class="divider">
                              <div class="divider-text" style="font-weight:bolder">Issuance Voucher Details</div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-num">Receipt Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="receipt-num" value="<?php echo $voucher->ReceiptNumber; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-datetime">Receipt Date/Time</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="originaldatetime" value="<?php echo $voucher->ReceiptDateTime; ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="originalpos">POS Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="originalpos" value="<?php echo $voucher->POSNumber ?>" disabled="">
                              </div>
                            </div>

                            <?php $i = 0;
                            $cardname = '';
                            $cardnum = '';
                            foreach ($card as $row) {
                              if ($i == 0) {
                                $cardname .= $row->CardId. '-' .$row->CardName;
                              }else {
                                $cardname .= ', '.$row->CardId. '-' .$row->CardName;
                              }

                              if ($i == 0) {
                                $cardnum .= $row->CardNumber;
                              }else {
                                $cardnum .= ', '.$row->CardNumber;
                              }
                             $i++; } ?>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="cardnumber">Issued Card Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="cardnumber" value="<?php echo $cardnum; ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="cardid">Issued Card ID</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="cardid" value="<?php echo $cardname; ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="cardid">Total Sales</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="totalSales" value="RM <?php echo $voucher->TotalSales; ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="cardid">Cosmetic Sales</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="cosmeticSales" value="RM <?php echo $voucher->Cosmeticsales; ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="issuance-time">Voucher Created Date</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="issuance-time" value="<?php echo $voucher->createddate ?>"  disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="terminal-id">Issued Terminal ID</label>
                              <div class="col-sm-10">
                                <input type="text" id="terminalId" class="form-control" value="<?php echo $voucher->POSNumber ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="remark">Remarks</label>
                              <?php if ($action->Update == 0 || ($permissionSubmit == 2 || $permissionSubmit == 4 || $permissionSubmit == 5 || $permissionSubmit == 6 || $permissionSubmit == 7 || $permissionSubmit == 8)){ ?>
                                <div class="col-sm-10">
                                  <textarea rows="3" id="remark" name="remark" class="form-control" disabled><?php echo $voucher->Remarks; ?></textarea>
                                </div>
                              <?php } else{ ?>
                                <div class="col-sm-10">
                                  <textarea rows="3" id="remark" name="remark" class="form-control"><?php echo $voucher->Remarks; ?></textarea>
                                </div>
                              <?php }?>
                              </div>

                            <div class="row justify-content-end">
                              <div class="col-sm-10">
                                <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $voucher->AppLoggerId; ?>">
                                <input type="hidden" name="vouchertypeid" value="<?php echo $voucher->VoucherTypeId; ?>">
                                <input type="hidden" name="vouchersnumber" value="<?php echo $voucher->VouchersNumber; ?>">

                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                  $action     = $this->RolePermission_Model->submenu_master(9);

                                  if ($permissionSubmit == 1 || $permissionSubmit == 3 || $permissionSubmit == 11 ) {
                                      if ($action->Update == 1) { ?>
                                        <button type="submit" id="btnEdit" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                    <?php } ?>
                                <?php }  ?>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
          <?php echo $footer; ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
    </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>

    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
  <?php echo $bottom; ?>


  <script type="text/javascript">

    $(document).ready(function(){

//jquery for hide/show field

      $('#blockDate_field').hide();
      $('#cancel_field').hide();


      $('#extendDate_field').hide();

      $('#status').on('change', function() {
        if(this.value == 3) {
          $('#extendDate_field').show(400);
        } else {
          $('#extendDate_field').hide(400);
        }
      });

      $('#status').on('change', function() {
        if(this.value == 5) {
          $('#cancel_field').show(400);
        } else {
          $('#cancel_field').hide(400);
        }
      });

      $('#status').on('change', function() {
        if(this.value == 6) {
          $('#blockDate_field').show(400);
        } else {
          $('#blockDate_field').hide(400);
        }
      });

      var voucherstatus = <?php echo $voucher->VoucherStatusId; ?>;
      if (voucherstatus == 3) {
        $("#extendDate_field").show(400);
      }

      var voucherstatus = <?php echo $voucher->VoucherStatusId; ?>;
      if (voucherstatus == 5) {
        $("#cancel_field").show();
      }

      var voucherstatus = <?php echo $voucher->VoucherStatusId; ?>;
      if (voucherstatus == 6) {
        $("#blockDate_field").show();
      }

//=====================================================================================================
//permission

      <?php if ($permission->BlockVouchersCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='6')
        {
          alert("You are not eligible to block the Voucher");
          $('#status').val("<?php echo $voucher->VoucherStatusId ?>");
        }
      });
      <?php endif ?>

      //permission Cancel
      <?php if ($permission->CancelVoucherCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='5')
        {
          alert("You are not eligible to Cancel the Voucher");
          $('#status').val("<?php echo $voucher->VoucherStatusId ?>");
        }
      });
      <?php endif ?>

      //permission extend
      <?php if ($permission->NumExtendCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='3')
        {
          alert("You are not eligible to Extend the Voucher");
          $('#status').val("<?php echo $voucher->VoucherStatusId ?>");
        }
      });
      <?php endif ?>

//======================================================================================================
//edit voucher tab 1

        //Edit voucher tab1
        $("#voucherEditForm").unbind('submit').bind('submit', function() {
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
                // confirmation();
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

//=======================================================================================================
//edit voucher tab 2

        //Edit voucher tab2
        $("#voucherDetailsForm").unbind('submit').bind('submit', function() {
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
                // confirmation();
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
    })

  </script>
  </body>
</html>
