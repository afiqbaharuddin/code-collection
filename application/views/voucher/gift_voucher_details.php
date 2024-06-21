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
    <title>Gift Voucher Details</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">GIFT VOUCHER DETAILS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item" >
                            <a href="<?php echo base_url(); ?>voucher/Voucher/giftlist"><u>Gift Vouchers</u></a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" > Gift Voucher Details</li>
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
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#editVoucher-pills" aria-controls="editVoucher-pills" aria-selected="true" id="editVoucher-tab">
                          Edit Voucher
                        </button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#voucherDetails-pills" aria-controls="voucherDetails-pills" aria-selected="false" id="voucherDetails-tab">
                          Voucher Details
                        </button>
                      </li>
                    </ul>

                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="editVoucher-pills" role="tabpanel">
                        <div class="card-header d-flex align-items-center justify-content-between">
                          <h5 class="mb-0">Edit  Voucher</h5>
                        </div>

                        <!-- Tab 1 edit gift voucher -->
                        <div class="card-body">
                          <form id="giftEditForm" action="<?php echo base_url(); ?>voucher/VoucherDetails/giftEdit" method="post">
                            <input type="hidden" name="giftId" value="<?php echo $giftEdit->GiftVouchersId;  ?>">

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="vouchernum">Voucher Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" name="vouchernum" id="vouchernum" value="<?php echo $giftEdit->VouchersNumber; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="datecreated">Date Created</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" name="datecreated" id="datecreated" value="<?php echo $giftEdit->createddate; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="issueddate">Issued Date</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" name="issueddate" id="issueddate" value="<?php echo $giftEdit->IssuedDate; ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="vouchervalue">Voucher Value (RM)</label>
                              <div class="col-sm-10">
                                <input type="text" id="vouchervalue" name="vouchervalue" class="form-control" value="<?php echo $giftEdit->VoucherValueGift; ?>"disabled >
                              </div>
                            </div>

                            <?php if (!empty($giftEdit->RedeemStore)): ?>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="batchnumber">Redeem Store</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redeemstore" name="redeemstore" class="form-control" value="<?php echo $giftEdit->RedeemStore; ?>"disabled >
                                </div>
                              </div>
                            <?php endif; ?>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="batchnumber">Batch Number</label>
                              <div class="col-sm-10">
                                <input type="text" id="batchnumber" name="batchnumber" class="form-control" value="<?php echo $giftEdit->BatchNumber; ?>"disabled >
                              </div>
                            </div>

                                <?php
                                $permissionSubmitDetails = $this->voucher_Model->voucherPermissionEditGiftVoucher($GiftVouchersId);
                                $action = $this->RolePermission_Model->submenu_master(8);

                                if ($permissionSubmitDetails == 1 || $permissionSubmitDetails == 3 || $permissionSubmitDetails == 10) {
                                  if ($action->Update == 1) { ?>
                                    <div class="row mb-0">
                                      <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                                      <div class="col-sm-10">
                                        <?php if ($giftEdit->VoucherStatusId == 6) {
                                         if ($permission->UnblockVouchersCheck == 0) { ?>
                                          <input type="text" name="" class="form-control" value="Block" disabled>
                                        <?php }
                                         if ($permission->UnblockVouchersCheck == 1) { ?>
                                          <select id="status" name="status" class="form-select">
                                            <option value="" hidden>Block</option>
                                            <?php foreach ($statusUnblockPermission as $row) { ?>
                                              <option value="<?php echo $row->VoucherStatusId; ?>"><?php echo $row->VoucherStatusName; ?></option>
                                            <?php } ?>
                                          </select>
                                        <?php } ?>
                                      <?php  } ?>

                                      <?php if ($giftEdit->VoucherStatusId == 1 || $giftEdit->VoucherStatusId == 3){ ?>
                                        <select id="status" name="status" class="form-select">
                                          <option value="">Select Status</option>

                                          <?php
                                            $nonclick = [2,4,7,8,10,11];
                                             foreach ($status as $row) {
                                              if ($row->VoucherStatusId == $giftEdit->VoucherStatusId){
                                                $select = 'selected';
                                              }else {
                                                $select = '';
                                              }
                                              if ($giftEdit->VoucherStatusId == 6) {
                                                $unblockallow = 1;
                                                if ($unblockallow == 1) {
                                                  $nonclick = [2,4,7,8,10,11];
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
                                        </select>
                                      <?php }elseif ($giftEdit->VoucherStatusId == 10) { ?>
                                        <select id="status" name="status" class="form-select">
                                          <option value="">Select Status</option>

                                          <?php
                                            $nonclick = [1,2,3,4,7,8,9,11];
                                             foreach ($status as $row) {
                                              if ($row->VoucherStatusId == $giftEdit->VoucherStatusId){
                                                $select = 'selected';
                                              }else {
                                                $select = '';
                                              }

                                              if (in_array($row->VoucherStatusId,$nonclick)){
                                                $diss = 'disabled';
                                              }else {
                                                $diss = '';
                                              }

                                                ?>
                                              <option value="<?php echo $row->VoucherStatusId; ?>"<?php echo $select.' '.$diss; ?>><?php echo $row->VoucherStatusName; ?></option>
                                            <?php } ?>
                                        </select>
                                      <?php  }?>
                                    </div>
                                  </div>

                                  <?php if ($giftEdit->VoucherStatusId == 10){ ?>
                                    <div class="row mb-3 mt-4" id="expireddate">
                                      <label class="col-sm-2 col-form-label" for="expireddate">Expired Date</label>
                                      <div class="col-sm-10">
                                        <input type="date" id="expireddate" name="expireddate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" class="form-control" value="<?php echo $giftEdit->ExpDate; ?>">
                                      </div>
                                    </div>
                                  <?php }else { ?>
                                    <div class="row mb-3 mt-4" id="expireddate">
                                      <label class="col-sm-2 col-form-label" for="expireddate">Expired Date</label>
                                      <div class="col-sm-10">
                                        <input type="date" id="expireddate" name="expireddate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" class="form-control" value="<?php echo $giftEdit->ExpDate; ?>" readonly/>
                                      </div>
                                    </div>
                                  <?php  }?>

                                  <div class="row mb-3 mt-4" id="inactivedate_field">
                                    <label class="col-sm-2 col-form-label" for="inactivedate">Inactive Date</label>
                                    <div class="col-sm-10">
                                      <input type="date" id="inactivedate" name="inactivedate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" max="<?php echo date('Y-m-d',strtotime($giftEdit->ExpDate)); ?>" class="form-control" value="<?php if(isset($history))echo $history->InactiveDate; ?>" >
                                    </div>
                                  </div>

                                  <?php if ($permission->NumExtendCheck == 1 ){ ?>
                                    <div class="row mb-3 mt-4"  id="extendDate_field">
                                        <label class="col-sm-2 col-form-label" for="extend-date">Extend Date</label>
                                        <div class="col-sm-10">
                                          <input type="date" id="extendDate" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d',strtotime('+'.$permission->NumExtendDay.' day')); ?>"
                                           name="extendDate" value="<?php if(isset($history)){ echo $history->ExtendDate;} ?>"/>
                                        </div>
                                    </div>
                                  <?php } ?>

                                  <?php if ($permission->NumExtendCheck == 0): ?>
                                    <input type="text" id="block-date" class="form-control" name="extenddate" value="" hidden/>
                                  <?php endif; ?>

                                  <?php if ($permission->BlockVouchersCheck == 1): ?>
                                    <div  id="blockDate_field" >
                                      <div class="row mt-3">
                                          <label class="col-sm-2 col-form-label" for="block-date">Block Date</label>
                                          <div class="col-sm-10">
                                            <input type="date" id="block-date" class="form-control"  min="<?php echo date('Y-m-d'); ?>" max="<?php echo $giftEdit->ExpDate ?>" placeholder="E.g: 3/3/2022"
                                            aria-label="3/3/2022" name="blockDate" value="<?php if (isset($history) && $history->BlockDate != null) echo date('Y-m-d', strtotime($history->BlockDate)); ?>"/>
                                          </div>
                                      </div>

                                      <div class="row mt-3">
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
                                            <input type="date" id="canceldate" class="form-control"  min="<?php echo date('Y-m-d'); ?>" max="<?php echo $giftEdit->ExpDate ?>" placeholder="E.g: 3/3/2022"
                                            aria-label="3/3/2022" name="canceldate" value="<?php if (isset($history) && $history->CancelDate != null) echo date('Y-m-d', strtotime($history->CancelDate)); ?>"/>
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

                                  <?php if ($permission->CancelVoucherCheck == 0): ?>
                                    <input type="input" class="form-control" name="canceldate" value="" hidden/>
                                    <input type="input" class="form-control" name="cancelReason" value="" hidden/>
                                  <?php endif; ?>

                              <?php } ?>
                            <?php } ?>

                            <?php
                              $action = $this->RolePermission_Model->submenu_master(8);

                            if ($action->Update == 0 || ($permissionSubmitDetails == 2 || $permissionSubmitDetails == 4 || $permissionSubmitDetails == 5 || $permissionSubmitDetails == 6 || $permissionSubmitDetails == 7 || $permissionSubmitDetails == 8 || $permissionSubmitDetails == 9)): ?>

                              <?php if ($giftEdit->VoucherStatusId != 2){ ?>
                                <div class="row mb-0">
                                  <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                                  <div class="col-sm-10">
                                    <input type="text" name="status" class="form-control" value="<?php echo $giftEdit->VoucherStatusName; ?>" disabled>
                                  </div>
                              </div>
                              <?php } else { ?>
                                <div class="row mb-0">
                                    <label class="col-sm-2 col-form-label" for="status">Voucher Status</label>
                                    <div class="col-sm-10">
                                      <select id="status" name="status" class="form-select">
                                        <?php foreach ($status as $row) {
                                          if ($row->VoucherStatusId == 2 || $row->VoucherStatusId == 3) {
                                            if ($row->VoucherStatusId == $giftEdit->VoucherStatusId){
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
                              <?php   } ?>

                              <div class="row mb-3 mt-4" id="expireddate">
                                <label class="col-sm-2 col-form-label" for="expireddate">Expired Date</label>
                                <div class="col-sm-10">
                                  <input type="date" id="expireddate" name="expireddate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" class="form-control" value="<?php echo $giftEdit->ExpDate; ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3 mt-4" id="inactivedate_field">
                                <label class="col-sm-2 col-form-label" for="inactivedate">Inactive Date</label>
                                <div class="col-sm-10">
                                  <input type="date" id="inactivedate" name="inactivedate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" max="<?php echo date('Y-m-d',strtotime($giftEdit->ExpDate)); ?>" class="form-control" value="<?php if(isset($history))echo $history->InactiveDate; ?>" >
                                </div>
                              </div>

                              <?php if ($permission->NumExtendCheck == 1){ ?>
                                <?php if ($giftEdit->VoucherStatusId == 2) { ?>
                                  <div class="row mt-3"  id="extendDate_field">
                                      <label class="col-sm-2 col-form-label" for="extend-date">Extend Date</label>
                                      <div class="col-sm-10">
                                        <input type="date" id="extendDate" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d',strtotime('+'.$permission->NumExtendDay.' day')); ?>"
                                        name="extendDate" value="<?php if (isset($history->ExtendDate)) {echo $history->ExtendDate;} ?>" />
                                      </div>
                                  </div>
                                <?php } else { ?>
                                  <div class="row mt-3"  id="extendDate_field">
                                      <label class="col-sm-2 col-form-label" for="extend-date">Extend Date</label>
                                      <div class="col-sm-10">
                                        <input type="date" id="extendDate" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d',strtotime('+'.$permission->NumExtendDay.' day')); ?>"
                                        name="extendDate" value="<?php if (isset($history->ExtendDate)) {echo $history->ExtendDate;} ?>" />
                                      </div>
                                  </div>
                                <?php } ?>
                              <?php }else{ ?>
                                <div class="row mt-3"  id="extendDate_field">
                                    <label class="col-sm-2 col-form-label" for="extend-date">Extend Date</label>
                                    <div class="col-sm-10">
                                      <input type="date" id="extendDate" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d',strtotime('+'.$permission->NumExtendDay.' day')); ?>"
                                      name="extendDate" value="" disabled/>
                                    </div>
                                </div>
                              <?php } ?>

                              <div  id="blockDate_field" >
                                <div class="row mt-3">
                                    <label class="col-sm-2 col-form-label" for="block-date">Block Date</label>
                                    <div class="col-sm-10">
                                      <input type="date" id="block-date" class="form-control" name="blockDate" value="<?php if (isset($history) && $history->BlockDate != null) echo date('Y-m-d', strtotime($history->BlockDate)); ?>" disabled/>
                                    </div>
                                </div>

                                <div class="row mt-3">
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
                                      <input type="date" id="canceldate" class="form-control"  min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" name="canceldate" value="<?php if (isset($history) && $history->CancelDate != null) echo date('Y-m-d', strtotime($history->CancelDate)); ?>" disabled/>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3" id="cancel-reason-field">
                                  <label class="col-sm-2 col-form-label" for="cancelReason">Cancel Reasons</label>
                                  <div class="col-sm-10">
                                  <input type="text" name="cancelReason" class="form-control" value="<?php if (isset($history->ReasonName)) echo $history->ReasonName; ?>" disabled>
                                </div>
                              </div>
                            </div>
                          <?php endif; ?>

                          <?php if ($giftEdit->VoucherStatusId != 10 && $giftEdit->VoucherIssuanceId != 0) { ?>
                            <div class="row mt-3 mb-3">
                              <label class="col-sm-2 col-form-label" for="payload">Payload</label>
                              <div class="col-sm-10">
                                <textarea rows="10" type="text" id="payload" class="form-control" disabled><?php echo json_encode(json_decode($giftEdit2->Payload),JSON_PRETTY_PRINT); ?></textarea>
                              </div>
                            </div>
                          <?php }?>

                          <div class="row mb-3 mt-3">
                            <label class="col-sm-2 col-form-label" for="remark">Remarks</label>
                            <?php if ($action->Update == 0 || ($permissionSubmitDetails == 2 || $permissionSubmitDetails == 4 || $permissionSubmitDetails == 5 || $permissionSubmitDetails == 6 || $permissionSubmitDetails == 7 || $permissionSubmitDetails == 8)){ ?>
                              <div class="col-sm-10">
                                <textarea id="remark" name="remark" class="form-control" readonly><?php echo $giftEdit->Remarks; ?></textarea>
                              </div>
                            <?php }else { ?>
                              <div class="col-sm-10">
                                <textarea id="remark" name="remark" class="form-control"><?php echo $giftEdit->Remarks; ?></textarea>
                              </div>
                          <?php  } ?>
                          </div>


                            <div class="row justify-content-end mt-3">
                              <div class="col-sm-10">
                                <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $giftEdit->AppLoggerId; ?>">
                                <input type="hidden" name="vouchertypeid" value="<?php echo $giftEdit->VoucherTypeId; ?>">
                                <input type="hidden" name="giftvoucherid" value="<?php echo $giftEdit->GiftVouchersId; ?>">
                                <input type="hidden" name="vouchersnumber" value="<?php echo $giftEdit->VouchersNumber; ?>">

                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                $action     = $this->RolePermission_Model->submenu_master(8);

                                  if ($permissionSubmitDetails == 1 || $permissionSubmitDetails == 2 || $permissionSubmitDetails == 3 ||$permissionSubmitDetails == 10) {
                                    if ($action->Update == 1) { ?>
                                    <button type="button" id="btnEdit" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                <?php }  ?>
                              <?php }  ?>
                               </div>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!-- second tab voucher details -->
                      <div class="tab-pane fade" id="voucherDetails-pills" role="tabpanel">
                        <div class="card-header d-flex align-items-center justify-content-between">
                          <h5 class="mb-0">Gift Voucher Details</h5>
                        </div>

                        <div class="divider">
                          <div class="divider-text" style="font-weight:bolder">Current Voucher Details</div>
                        </div>

                        <div class="card-body">
                          <form id="giftDetailsForm" action="<?php echo base_url(); ?>voucher/VoucherDetails/giftdetailsform" method="post">
                            <input type="hidden" name="giftDetails" value="<?php echo $giftEdit->GiftVouchersId;  ?>">

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="voucher-num">Voucher Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="voucher-num" value="<?php if (isset($giftEdit->VouchersNumber)) {echo $giftEdit->VouchersNumber;} ?>" disabled>
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

                            <?php if ($giftEdit->VoucherStatusId == 7) { ?>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="pos-num">Redemption POS Number</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="pos-num" value="<?php if (isset($original->POSNumber)) {echo $original->POSNumber;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-store">Redemption Store</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionStore" class="form-control" value="<?php if (isset($giftEdit->RedemptionStore)) {echo $giftEdit->RedemptionStore;} ?>" disabled>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-date">Redemption Date/Time</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionDate" class="form-control" value="<?php if (isset($giftEdit->RedemptionDateTime)) {echo $giftEdit->RedemptionDateTime;} ?>" disabled >
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="redemption-by">Redemption By</label>
                                <div class="col-sm-10">
                                  <input type="text" id="redemptionBy" class="form-control"  value="<?php if (isset($giftEdit->RedemptionBy)) {echo $giftEdit->RedemptionBy;} ?>" disabled>
                                </div>
                              </div>
                            <?php } ?>

                            <div class="divider">
                              <div class="divider-text" style="font-weight:bolder">Voucher Activation Details</div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-num">Receipt Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="receipt-num" value="<?php if (isset($giftEdit2->ReceiptNumber)) {echo $giftEdit2->ReceiptNumber;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="receipt-datetime">Receipt Date/Time</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="receipt-datetime" value="<?php if (isset($giftEdit2->ReceiptDateTime)) {echo $giftEdit2->ReceiptDateTime;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="pos-num">POS Number</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="pos-num" value="<?php if (isset($giftEdit2->POSNumber)) {echo $giftEdit2->POSNumber;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="issuance-date">Voucher Created Date</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="issuance-date" value="<?php if (isset($giftEdit->createddate)) {echo $giftEdit->createddate;} ?>" disabled="">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="terminal-id">Issued Terminal ID</label>
                              <div class="col-sm-10">
                                <input type="text" id="terminalId" class="form-control" value="<?php if (isset($giftEdit2->POSNumber)) {echo $giftEdit2->POSNumber;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="terminal-id">Source</label>
                              <div class="col-sm-10">
                                <input type="text" id="source" class="form-control" value="<?php if (isset($giftEdit->Source)) {echo $giftEdit->Source;} ?>" disabled>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="remark">Remarks</label>
                              <div class="col-sm-10">
                                <textarea id="remark" name="remark" class="form-control"><?php echo $giftEdit->Remarks; ?></textarea>
                              </div>
                            </div>

                            <div class="row justify-content-end">
                              <div class="col-sm-10">
                                <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $giftEdit->AppLoggerId; ?>">
                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                  $permissionSubmitDetails = $this->voucher_Model->voucherPermissionEditGiftVoucher($GiftVouchersId);
                                  $action     = $this->RolePermission_Model->submenu_master(8);

                                  if ($permissionSubmitDetails !=2) {
                                    if ($action->Update == 1) { ?>
                                      <button type="submit" id="btnEdit" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                <?php }  ?>
                              <?php } ?>
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

      $('#btnEdit').on('click', function(){
        $("#giftEditForm").submit();
      });

//jquery for hide/show field

      $('#blockDate_field').hide();
      $('#cancel_field').hide();
      $('#extendDate_field').hide();
      $('#inactivedate_field').hide();

      $('#status').on('change', function() {
        if(this.value == 9) {
          $('#inactivedate_field').show(400);
        } else {
          $('#inactivedate_field').hide(400);
        }
      });

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

      var voucherstatus = <?php echo $giftEdit->VoucherStatusId; ?>;
      if (voucherstatus == 3) {
        $("#extendDate_field").show(400);
      }

      var voucherstatus = <?php echo $giftEdit->VoucherStatusId; ?>;
      if (voucherstatus == 9) {
        $("#inactivedate_field").show(400);
      }

      var voucherstatus = <?php echo $giftEdit->VoucherStatusId; ?>;
      if (voucherstatus == 5) {
        $("#cancel_field").show();
      }

      var voucherstatus = <?php echo $giftEdit->VoucherStatusId; ?>;
      if (voucherstatus == 6) {
        $("#blockDate_field").show();
      }

//==============================================================================================
//permission

      //permission block
      <?php if ($permission->BlockVouchersCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='6')
        {
          alert("You are not eligible to block the Voucher");
          $('#status').val("<?php echo $giftEdit->VoucherStatusId ?>");
        }
      });
      <?php endif ?>

      //permission Unblock
      // <?php if ($permission->UnblockVouchersCheck == 1): ?>
      // $('#status').on('change', function(){
      //   if(this.value =='6')
      //   {
      //     // $('#status').val("10").hide(400);
      //   }
      // });
      // <?php endif ?>

      //permission Cancel
      <?php if ($permission->CancelVoucherCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='5')
        {
          alert("You are not eligible to Cancel the Voucher");
          $('#status').val("<?php echo $giftEdit->VoucherStatusId ?>");
        }
      });
      <?php endif ?>

      //permission extend
      <?php if ($permission->NumExtendCheck == 0): ?>
      $('#status').on('change', function(){
        if(this.value=='3')
        {
          alert("You are not eligible to Extend the Voucher");
          $('#status').val("<?php echo $giftEdit->VoucherStatusId ?>");
        }
      });
      <?php endif ?>


//=======================================================================================
//edit gift voucher tab 1

        //Edit gift tab1
        $("#giftEditForm").unbind('submit').bind('submit', function() {
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

//=======================================================================================
//edit gift voucher tab 2

        //gift details tab2
        $("#giftDetailsForm").unbind('submit').bind('submit', function() {
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

    });

  </script>
  </body>
</html>
