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
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <title>Edit Voucher</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">
                        <span class="text-muted fw-light">Forms/</span>EDIT VOUCHER
                      </h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>voucher/PredefinedVoucher/predefinedlist"><u>Pre-Defined Voucher</u></a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Edit Voucher</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Multi Column with Form Separator -->
                <div class="row">
                  <!-- Form Label Alignment -->
                  <div class="col-md">
                    <div class="card">
                      <h5 class="card-header">Edit Voucher Details</h5>
                      <div class="card-body">
                        <form id="editVoucherType" class="needs-validation" action="<?php echo base_url(); ?>voucher/Voucher/editVoucherTypeForm" method="post">
                          <input type="hidden" name="editid" value="<?php echo $editdata->VoucherTypeId; ?>">

                          <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">Voucher Name</label>
                            <input type="text" class="form-control" id="bs-validation-name" value="<?php echo $editdata->VoucherName; ?>" required disabled/>
                            <!-- <div class="valid-feedback">Looks good!</div> -->
                            <!-- <div class="invalid-feedback">*This field is required. Please enter Voucher Name.</div> -->
                          </div>

                          <div class="mb-3">
                            <label class="form-label" for="bs-validation-short">Voucher Shortname</label>
                            <input type="text" class="form-control" id="bs-validation-short" value="<?php echo $editdata->VoucherShortName;?>" required disabled/>
                            <!-- <div class="valid-feedback">Looks good!</div> -->
                            <div class="invalid-feedback">*This field is required. Please enter Voucher Shortname.</div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label" for="bs-validation-issuance">Issuance Type</label>
                            <input type="text" class="form-control" id="bs-validation-issuance" value="<?php echo $editdata->IssuanceTypeName;?>" required disabled/>
                          </div>

                          <div class="row mb-3">
                            <div class="col-md-2 col-2 mb-0">
                              <label for="bs-validation-prefix" class="form-label">Prefix (Range) :</label>
                            </div>
                            <span class="col-md-2 col-2 mb-0">
                              <div class="input-group input-prefixrange">
                                <input type="text" id="bs-validation-prefix" value="<?php echo $editdata->PrefixRangeFirst;?>" name="prefix1" class="form-control" required disabled />
                                <span class="input-group-text"><strong>-</strong></span>
                                <input type="text" value="<?php echo $editdata->PrefixRangeSecond;?>" name="prefix2" class="form-control" required disabled/>
                                <div class="invalid-feedback">*Please fill in the Prefix range.</div>
                              </div>
                            </span>
                         </div>


                        <div class="row mb-3">
                          <div class="col-md-2 col-2 mb-0">
                            <label class="form-label">Serialize / Non-Serialize:</label>
                          </div>
                          <span class="col-md-2 col-2 mb-0">
                            <input type="text" value="<?php echo $editdata->VoucherSerializeType;?>" name="" class="form-control" required disabled/>
                        </div>

                        <div class="row mb-3">
                          <div class="col-md-2 col-2 mb-0">
                            <label class="form-label mt-2" for="position-select">Position :</label>
                          </div>
                          <span class="col-md-2 col-2 mb-0">
                            <input type="text" value="<?php echo $editdata->VoucherTypeValue;?>" name="" class="form-control" required disabled/>
                          </span>
                        </div>

                        <div class="row mb-3">
                          <div class="col-md-2 col-2 mb-0">
                            <label class="form-label mt-2" for="serialize-number">Number :</label>
                          </div>
                          <span class="col-md-2 col-2 mb-0">
                            <input type="text" class="form-control" value="<?php echo $editdata->SerializeNumber;?>" id="unserialize" name="unserializeno" disabled/>
                          </span>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="bs-validation-prefix">Prefix Position</label>
                          <span style="color:red">*</span>
                          <?php
                                $first = $editdata->PrefixPositionStart;
                                $last=  $editdata->PrefixPositionEnd;

                                $array=[];
                                for ($i=$first; $i < $last+1 ; $i++) {
                                  $array[]=$i;
                                }
                           ?>
                          <div class="col-md-8">
                                <div class="row" id="bs-validation-prefix">
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="prefixposition1">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">1</span>
                                           </span>
                                           <?php if (in_array(1,$array)){
                                             $check='checked';
                                           }else {
                                             $check='';
                                           }
                                           ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="1" id="prefixposition1" name="prefixposition[]" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition2">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">2</span>
                                         </span>
                                         <?php if (in_array(2,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="2" id="prefixposition2" name="prefixposition[]" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition3">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">3</span>
                                         </span>
                                         <?php if (in_array(3,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="3" id="prefixposition3" name="prefixposition[]" style="display:none" disabled />
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="customCheckboxIcon4">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">4</span>
                                         </span>
                                         <?php if (in_array(4,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="4" id="customCheckboxIcon4" name="prefixposition[]" style="display:none" disabled />
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition5">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">5</span>
                                         </span>
                                         <?php if (in_array(5,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="5" id="prefixposition5" name="prefixposition[]" style="display:none" disabled />
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition6">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">6</span>
                                         </span>
                                         <?php if (in_array(6,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="6" id="prefixposition6" name="prefixposition[]" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition7">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">7</span>
                                         </span>
                                         <?php if (in_array(7,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="7" id="prefixposition7" name="prefixposition[]" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition8">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">8</span>
                                         </span>
                                         <?php if (in_array(8,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="8" id="prefixposition8" name="prefixposition[]" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="prefixposition9">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">9</span>
                                         </span>
                                         <?php if (in_array(8,$array)){
                                           $check='checked';
                                         }else {
                                           $check='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $check; ?> value="9" id="prefixposition9" name="prefixposition[]" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>
                                </div>
                          </div>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="bs-validation-prefix">Serial Number</label>
                          <span style="color:red">*</span>
                          <?php
                                $firstSerial = $editdata->SerialNumberPositionStart;
                                $lastSerial=  $editdata->SerialNumberPositionEnd;

                                $array=[];
                                for ($i=$firstSerial; $i < $lastSerial+1 ; $i++) {
                                  $array[]=$i;
                                }
                           ?>

                          <div class="col-sm-8">
                                <div class="row" id="bs-validation-serial">
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check  custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon1">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">1</span>
                                         </span>
                                         <?php if (in_array(1,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="1" id="serialCheckboxIcon1" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="serialCheckboxIcon2">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">2</span>
                                         </span>
                                         <?php if (in_array(2,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="2" id="serialCheckboxIcon2" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon3">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">3</span>
                                         </span>
                                         <?php if (in_array(3,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="3" id="serialCheckboxIcon3" style="display:none"  disabled/>
                                   </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="serialCheckboxIcon4">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">4</span>
                                         </span>
                                         <?php if (in_array(4,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="4" id="serialCheckboxIcon4" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option custom-option-content" for="serialCheckboxIcon5">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">5</span>
                                         </span>
                                         <?php if (in_array(5,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial ?> value="5" id="serialCheckboxIcon5" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon6">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">6</span>
                                         </span>
                                         <?php if (in_array(6,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" value="6" <?php echo $checkSerial; ?> id="serialCheckboxIcon6" style="display:none"  disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon7">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">7</span>
                                         </span>
                                         <?php if (in_array(7,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="7" id="serialCheckboxIcon7" style="display:none" disabled/>
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon8">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">8</span>
                                         </span>
                                         <?php if (in_array(8,$array)){
                                           $checkSerialcheckSerial='checked';
                                         }else {
                                           $checkSerialcheckSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="8" id="serialCheckboxIcon8" style="display:none" disabled />
                                      </label>
                                    </div>
                                  </div>

                                  <div class="col-md">
                                    <div class="form-check custom-option-icon">
                                      <label class="form-check-label custom-option  custom-option-content" for="serialCheckboxIcon9">
                                        <span class="custom-option-header">
                                          <span class="h6 mb-0">9</span>
                                         </span>
                                         <?php if (in_array(8,$array)){
                                           $checkSerial='checked';
                                         }else {
                                           $checkSerial='';
                                         }
                                         ?>
                                        <input class="form-check-input" type="checkbox" <?php echo $checkSerial; ?> value="9" id="serialCheckboxIcon9" style="display:none"disabled/>
                                      </label>
                                    </div>
                                  </div>
                                </div>
                          <div class="invalid-feedback">*This field is required. Please tick the Serial Number.</div>
                        </div>
                      </div>


                      <div id="show_edit">
                        <?php
                        $value = explode(',',$editdata->VoucherValue);

                        if (!empty($value)) {
                          $count = count($value) + 1;
                        }else {
                          $count = 1;
                        }

                        $i = 1;
                        foreach ($value as $key => $value) { ?>
                          <div id="editValueVoucher_<?php echo $i.$value; ?>">
                            <div class="row">
                              <div class="col-md-3">
                                <label class="form-label mb-2 mt-2" for="vouchervalue">Voucher Value</label>
                                <input type="text" id="editvouchervalue_" name="value_<?php echo $i; ?>"  class="form-control editvouchervalue" value="<?php echo $value; ?>"/>
                              <div class="invalid-feedback">*This field is required. Please enter Voucher Value.</div>
                              </div>

                              <div class="col-md-3">
                                <label for="" class="form-label mb-2">Action</label>
                                <div class="d-buttons" id="addCard_<?php echo $i; ?>">
                                  <button class="btn btn-primary me-sm-3 me-1 removeValue" data-valuenum="<?php echo $i.$value; ?>" type="button" name="button" style="background-color:#122620; border-color:#122620">
                                    <span>Remove</span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php $i++; } ?>

                        <div id="editValueVoucher_1">
                          <div class=" row mb-3">
                            <div class="col-md-3">
                              <label class="form-label mb-2 mt-2" for="vouchervalue">Voucher Value</label>
                              <input type="text" id="editvouchervalue_" name="value_<?php echo $count; ?>"  class="form-control editvouchervalue" value=""/>
                            <div class="invalid-feedback">*This field is required. Please enter Voucher Value.</div>
                            </div>

                            <div class="col-md-3">
                              <label class="form-label mb-2 mt-2" for="multicol-action">Action</label>
                              <div class="dt-buttons" id="addValue_<?php echo $count; ?>">
                                <button class="btn btn-primary me-sm-3 me-1 addValue" type="button" name="button" style="background-color:#122620; border-color:#122620">
                                  <span>Add Line</span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-3 col-sm-3">
                        <label class="form-label" for="storecredit">Store Credit</label>
                        <select class="form-select" name="storecredit" id="storecredit" disabled>
                          <option value="">Select Store Credit Option</option>
                          <?php foreach ($storecredit as $row) {
                            if ($row->StoreCreditId == $editdata->StoreCreditId) {
                              $select = 'selected';
                            }else {
                              $select = '';
                            }?>
                            <option value="<?php echo $row->StoreCreditId; ?>" <?php echo $select; ?>><?php echo $row->StoreCreditName; ?></option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="mb-3 col-sm-3" id="additionalprefix">
                        <label class="form-label" for="addprefix">Additional Prefix Position</label>
                        <select class="form-select" name="addprefix" id="addprefix" disabled>
                          <option value="">Select Prefix Position</option>
                          <?php for ($i=1; $i<=18 ; $i++) {
                            if ($i == $editdata->AdditionalPrefix) {
                              $select = 'selected';
                            }else {
                              $select = '';
                            }?>
                            <option value="<?php echo $i ?>" <?php echo $select; ?>><?php echo $i ?></option>
                          <?php } ?>
                        </select>
                      </div>

                      <input type="hidden" name="totalvalue" id="totalvalue" value="<?php echo $count; ?>">
                      <div class="mb-3">
                        <label class="form-label" for="bs-validation-status">Status</label>
                        <span style="color:red">*</span>
                        <select class="form-select bs-validation-status" name="voucherstatus" id="voucherStatus">
                          <option value="">Select Status</option>
                          <?php foreach ($voucherstatus_edit as $row) {
                            if ($row->StatusId == $editdata->StatusId) {
                              $select = 'selected';
                            }else {
                              $select = '';
                            }
                            ?>
                            <option value="<?php echo $row->StatusId; ?>" <?php echo $select; ?>><?php echo $row->StatusName; ?></option>
                          <?php } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Status</div>
                      </div>

                      <div class="mb-3" id="inactivedatefield">
                        <label class="form-label" id="inactivelabel" for="inactiveDate">Voucher Inactive Date</label>
                        <input class="form-control inactiveDate" id="inactiveDate" type="date" name="inactiveDate"  value="<?php echo $editdata->InactivateDate; ?>" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" />
                      </div>

                      <div class="mb-3">
                        <label for="remark" class="form-label">Remarks</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="remarks" rows="3" cols="80"><?php echo $editdata->Remarks; ?></textarea>
                        </div>
                      </div>

                      <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $editdata->AppLoggerId; ?>">
                      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                      <?php
                        $permissionSubmit = $this->voucher_Model->voucherPermissionEdit($VoucherTypeId);
                        if ($permissionSubmit !=3) {  ?>
                          <button type="button" id="btnEdit" class="btn btn-primary me-sm-3 me-1 mt-4" style="background-color:#122620; border-color:#122620">Submit</button>
                          <button type="reset" class="btn btn-label-secondary mt-4" data-bs-dismiss="offcanvas">Cancel</button>
                          <?php }  ?>
                    </form>
                  </div>
                </div>
              </div>
              </div>
          </div>
        </div>
        <!-- / Content -->

        <!-- Footer -->
        <?php echo $footer;?>
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
<?php echo $bottom;?>


    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>

    <script>

      $(document).ready(function(){

        $('#btnEdit').on('click', function(){
          $("#editVoucherType").submit();
        });

          //only number allow on voucher value
        $(".editvouchervalue").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        //Edit voucher type
        $("#editVoucherType").unbind('submit').bind('submit', function() {
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
                window.location = '<?php echo base_url() ?>voucher/PredefinedVoucher/predefinedlist';
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

        //jquery for voucher value
        $("#show_edit").on('click', '.addValue', function(){
          var totalvalue = parseInt($("#totalvalue").val());
          var remove = '<button class="btn btn-primary removeValue" data-valuenum="'+totalvalue+'" style="background-color:#122620; border-color:#122620">Remove</button>'
          $('#addValue_'+totalvalue).html(remove);
          totalvalue = totalvalue + 1;
          $('#totalvalue').val(totalvalue);

          editValue=
          '<div id="editValueVoucher_'+totalvalue+'">'+
            '<div class=" row mb-3">'+
              '<div class="col-md-3">'+
                '<label class="form-label mb-2 mt-2" for="bs-validation-value2">Voucher Value</label>'+
                  '<span style="color:red">*</span>'+
                '<input type="text" id="editvouchervalue_'+totalvalue+'" name="value_'+totalvalue+'" placeholder="Eg: 30" class="form-control editvouchervalue" value="" required/>'+
              '<div class="invalid-feedback">*This field is required. Please enter Voucher Value.</div>'+
              '</div>'+

              '<div class="col-md-3">'+
                '<label class="form-label mb-2 mt-2" for="multicol-action">Action</label>'+
                '<div class="d-buttons" id="addValue_'+totalvalue+'">'+
                  '<button class="btn btn-primary me-sm-3 me-1 addValue" data-valuenum="'+totalvalue+'" type="button" name="button" style="background-color:#122620; border-color:#122620">'+
                    '<span>Add Line</span>'+
                  '</button>'+
                '</div>'+
              '</div>'+
            '</div>'+
          '</div>';

          $('#show_edit').append(editValue);

          //only number allow on voucher value
          $("#editvouchervalue_"+totalvalue).on('input', function(e){
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
          });
        });

        $("#removeValue").hide();
        $("#additionalprefix").hide();

        var storecredit = <?php echo $editdata->StoreCreditId; ?>;
        if (storecredit == 2) {
          $("#additionalprefix").show(400);
        }

        $('#storecredit').on('change',function(){
          if(this.value == 2){
            $("#additionalprefix").show(400);
          }else {
            $("#additionalprefix").hide(400);
          }
        });

        $('#show_edit').on('click','.removeValue',function() {
          var valuenum = $(this).data('valuenum');
          $("#editValueVoucher_"+valuenum).remove();
        });

        $('.editvouchervalue').on('keyup', function(){
          var value = this.value;
          var name  = this.name;
          getval(value,name);
        });

        $('#show_edit').on('keyup','.editvouchervalue', function(){
          var value = this.value;
          var name  = this.name;
          getval(value,name);
        });

        function getval(val,name){
          $('.editvouchervalue').each(function(){
            // console.log(this);
            if (val == this.value && name != this.name) {
              snack("The value is alraedy exist", 'danger');
            }
          });
        }

        //jquery for voucher value and status
        $("#inactivedatefield").hide();
        $("#removeEdit").show();

        $('#voucherStatus').on('change',function(){
          if(this.value == 2)
          {
            $("#inactivedatefield").show(400);
          }else
          {
            $("#inactivedatefield").hide(400);
          }
        });

        var voucherstatus = <?php echo $editdata->StatusId; ?>;
        if (voucherstatus == 2) {
          $("#inactivedatefield").show(400);
        }
      });
      </script>


  </body>
</html>
