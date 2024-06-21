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
    <title>Create Voucher</title>
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
                        <span class="text-muted fw-light">Forms/</span>VOUCHER TYPE
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
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Voucher Type</li>
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
                      <h5 class="card-header">Insert Voucher Details</h5>
                      <div class="card-body">
                        <form id="vouchertype" class="needs-validation" action="<?php echo base_url(); ?>voucher/Voucher/createVoucherTypeForm" method="post">
                          <div class="mb-3">
                            <label class="form-label" for="vouchername">Voucher Name</label>
                            <span style="color:red">*</span>
                            <input type="text" class="form-control" id="vouchername" name="vouchername" placeholder="Eg: Promotion Voucher" required/>
                            <!-- <div class="valid-feedback">Looks good!</div> -->
                            <div class="invalid-feedback">*This field is required. Please enter Voucher Name.</div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label" for="vouchershortname">Voucher Shortname</label>
                            <span style="color:red">*</span>
                            <input type="text" class="form-control" id="vouchershortname" name="vouchershortname" placeholder="Eg: PV" required/>
                            <div class="invalid-feedback">*This field is required. Please enter Voucher Shortname.</div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label" for="issuanceType">Issuance Type</label>
                            <span style="color:red">*</span>
                            <select class="form-select" id="issuanceType" name="issuanceType" required>
                              <?php foreach ($issuancedb as $row) { ?>
                                <option value="<?php echo $row->IssuanceTypeId; ?>"><?php echo $row->IssuanceTypeName; ?></option>
                              <?php } ?>
                            </select>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">*This field is required. Please select Issuance Type</div>
                          </div>

                            <div class="row mb-3">
                              <div class="col-md-2 col-2 mb-0">
                                <label for="bs-validation-prefix" class="form-label">Prefix (Range) :</label>
                                <span style="color:red">*</span>
                              </div>
                              <span class="col-md-2 col-2 mb-0">
                                <div class="input-group input-prefixrange">
                                  <input type="text" id="prefix1" placeholder="Eg: 22" name="prefix1" maxlength="5" class="form-control" required />
                                  <span class="input-group-text"><strong>-</strong></span>
                                  <input type="text" placeholder="Eg: 24" id="prefix2" name="prefix2" maxlength="5" class="form-control" />
                                  <div class="invalid-feedback">*Please fill in the Prefix range.</div>
                                </div>
                              </span>
                           </div>


                          <div class="row mb-3">
                            <div class="col-md-2 col-2 mb-0">
                              <label class="form-label">Serialize / Non-Serialize:</label>
                            </div>
                            <span class="col-md-2 col-2 mb-0">
                              <select class="form-select" name="serializeselect" id="serializeselect" required>
                                <?php foreach ($serialize as $row) { ?>
                                  <option value="<?php echo $row->VoucherSerializeId; ?>"><?php echo $row->VoucherSerializeType; ?></option>
                                <?php  } ?>
                              </span>
                              </select>
                          </div>

                          <div class="row mb-3" id="aa">
                            <div class="col-md-2 col-2 mb-0">
                              <label class="form-label mt-2" for="position">Position :</label>
                            </div>
                            <span class="col-md-2 col-2 mb-0">
                              <select class="form-select" name="position" id="position">
                                <?php foreach ($position as $row) { ?>
                                  <option value="<?php echo $row->VoucherTypePositionId; ?>"><?php echo $row->VoucherTypeValue; ?></option>
                                <?php  } ?>
                              </select>
                            </span>
                          </div>

                          <div class="row mb-3">
                            <div class="col-md-2 col-2 mb-0">
                              <label class="form-label mt-2" for="serialize-number">Number :</label>
                            </div>
                            <span class="col-md-2 col-2 mb-0">
                              <input type="text" class="form-control"  id="unserializePrefixnumber" name="unserializenum" maxlength="5"/>
                            </span>
                          </div>

                          <div class="mb-3">
                            <input type="hidden" name="prefixx" id="prefixx">
                            <label class="form-label" for="bs-validation-prefix">Prefix Position</label>
                            <span style="color:red">*</span>

                            <div class="col-md-10">
                                  <div class="row prefixposition" id="bs-validation-prefix">
                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="prefixposition1">
                                            <span class="custom-option-header">
                                              <span class="h6 mb-0">1</span>
                                             </span>
                                          <input class="form-check-input prefix prefix1" type="checkbox" name="prefixposition[]"  id="prefixposition1" value="1" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition2">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">2</span>
                                           </span>
                                          <input class="form-check-input prefix prefix2" type="checkbox" name="prefixposition[]" id="prefixposition2" value="2" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition3">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">3</span>
                                           </span>
                                          <input class="form-check-input prefix prefix3" type="checkbox" name="prefixposition[]" id="prefixposition3" value="3" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition4">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">4</span>
                                           </span>
                                          <input class="form-check-input prefix prefix4" type="checkbox" name="prefixposition[]" id="prefixposition4"  value="4" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition5">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">5</span>
                                           </span>
                                          <input class="form-check-input prefix prefix5" type="checkbox" name="prefixposition[]" id="prefixposition5" value="5" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition6">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">6</span>
                                           </span>
                                          <input class="form-check-input prefix prefix6" type="checkbox" name="prefixposition[]" id="prefixposition6" value="6" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition7">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">7</span>
                                           </span>
                                          <input class="form-check-input prefix prefix7" type="checkbox" name="prefixposition[]" id="prefixposition7" value="7" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition8">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">8</span>
                                           </span>
                                          <input class="form-check-input prefix prefix8" type="checkbox" name="prefixposition[]" id="prefixposition8" value="8" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="prefixposition9">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">9</span>
                                           </span>
                                          <input class="form-check-input prefix prefix9" type="checkbox" name="prefixposition[]" id="prefixposition9" value="9" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 mb-md-0 mb-2">
                                        <div class="d-buttons pt-2">
                                          <a>
                                            <button type="button" class="btn btn-primary btn-submit" id="submitbtn1" style="background-color:#122620; border-color:#122620">Confirm Position</button>
                                          </a>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                          </div>

                          <!-- try id satu2 untyk disabled kan yang bawah -->
                          <div class="mb-3"  id="bs-validation-serial">
                            <label class="form-label" for="bs-validation-serial">Serial Number Position</label>
                            <span style="color:red">*</span>

                            <div class="col-sm-10">
                                  <div class="row">
                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check try1  custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox1">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">1</span>
                                           </span>
                                          <input class="form-check-input serial serial1" type="checkbox" id="serialCheckbox1" name="serialCheckbox[]"  value="1" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="serialCheckbox2">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">2</span>
                                           </span>
                                          <input class="form-check-input serial serial2" type="checkbox" name="serialCheckbox[]" id="serialCheckbox2" value="2" style="display:none" required/>
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox3">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">3</span>
                                           </span>
                                          <input class="form-check-input serial serial3" type="checkbox" name="serialCheckbox[]" id="serialCheckbox3" value="3" style="display:none" required />
                                     </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="serialCheckbox4">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">4</span>
                                           </span>
                                          <input class="form-check-input serial serial4" type="checkbox" name="serialCheckbox[]" id="serialCheckbox4" value="4" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option custom-option-content" for="serialCheckbox5">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">5</span>
                                           </span>
                                          <input class="form-check-input serial serial5" type="checkbox" name="serialCheckbox[]" id="serialCheckbox5" value="5" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox6">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">6</span>
                                           </span>
                                          <input class="form-check-input serial serial6" type="checkbox" name="serialCheckbox[]" id="serialCheckbox6" value="6" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox7">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">7</span>
                                           </span>
                                          <input class="form-check-input serial serial7" type="checkbox" name="serialCheckbox[]" id="serialCheckbox7" value="7" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox8">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">8</span>
                                           </span>
                                          <input class="form-check-input serial serial8" type="checkbox" name="serialCheckbox[]" id="serialCheckbox8" value="8" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md mb-md-0 mb-2">
                                      <div class="form-check custom-option-icon">
                                        <label class="form-check-label custom-option  custom-option-content" for="serialCheckbox9">
                                          <span class="custom-option-header">
                                            <span class="h6 mb-0">9</span>
                                           </span>
                                          <input class="form-check-input serial serial9" type="checkbox" name="serialCheckbox[]" id="serialCheckbox9" value="9" style="display:none" required />
                                        </label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 mb-md-0 mb-2">
                                        <div class="d-buttons pt-2">
                                          <button type="button" id="resetfield" class="btn btn-primary resetfield" style="background-color:#122620; border-color:#122620">Reset</button>
                                      </div>
                                    </div>
                                  </div>
                            <div class="invalid-feedback">*This field is required. Please tick the Serial Number.</div>
                          </div>
                        </div>

                        <input type="hidden" name="totalvalue"  id="totalvalue" value="1">
                        <div id="show_voucher">
                          <div id="addVoucherValue_1">
                            <div class=" row mb-3">
                              <div class="col-md-3">
                                <label class="form-label mb-2 mt-2" for="bs-validation-value2">Voucher Value</label>
                                  <span style="color:red">*</span>
                                <input type="text" id="vouchervalue_" name="value_1" placeholder="Eg: 30" class="form-control" required/>
                              <div class="invalid-feedback">*This field is required. Please enter Voucher Value.</div>
                              </div>

                              <div class="col-md-3">
                                <label class="form-label mb-2 mt-2" for="multicol-action">Action</label>
                                <div class="d-buttons" id="addValue_1">
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
                          <span style="color:red">*</span>
                          <select class="form-select" name="storecredit" id="storecredit" required>
                            <option value="">Select Store Credit Option</option>
                            <?php foreach ($storecredit as $row) { ?>
                              <option value="<?php echo $row->StoreCreditId; ?>"><?php echo $row->StoreCreditName; ?></option>
                            <?php } ?>
                          </select>
                        </div>

                        <div class="mb-3 col-sm-3" id="additionalprefix">
                          <label class="form-label" for="addprefix">Additional Prefix Position</label>
                          <span style="color:red">*</span>
                          <select class="form-select" name="addprefix" id="addprefix" required>
                            <option value="">Select Prefix Position</option>
                            <?php for ($i=1; $i<=18 ; $i++) { ?>
                              <option value=<?php echo $i ?>><?php echo $i ?></option>
                            <?php } ?>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="bs-validation-status">Status</label>
                          <span style="color:red">*</span>
                          <select class="form-select" name="voucherstatus" id="voucherstatus" required>
                            <option value="">Select Status</option>
                            <?php foreach ($voucherstatus_create as $row) { ?>
                              <option value="<?php echo $row->StatusId; ?>"><?php echo $row->StatusName; ?></option>
                            <?php } ?>
                          </select>
                          <div class="invalid-feedback">*This field is required. Please select Status</div>
                        </div>

                        <div class="mb-3">
                          <label for="remark" class="form-label">Remarks</label>
                          <div class="col-sm-10">
                            <textarea class="form-control" name="remarks" rows="3" cols="80"></textarea>
                          </div>
                        </div>

                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                          <button type="button" id="submitbtn" class="btn btn-primary me-sm-3 me-1 data-submit mt-4" style="background-color:#122620; border-color:#122620">Create</button>
                          <button type="reset" class="btn btn-label-secondary mt-4" data-bs-dismiss="offcanvas">Cancel</button>
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

    <!-- Form Validation Script-->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>
    <!-- / Form Validation Script-->

    <script>

      $(document).ready(function() {

        function resetFunction()
        {
          document.getElementById("vouchertype").reset();
        }

        //thanks afiq
        $('#submitbtn').on('click', function(){
          $("#vouchertype").submit();
        });

        //only number allow on prefix position number
        $("#unserializePrefixnumber").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        //only number allow on prefix range 1
        $("#prefix1").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        //only number allow on prefix range 2
        $("#prefix2").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        //only number allow on voucher value
        $("#vouchervalue_").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        //prefix position select
        $('.bs-validation-prefix').on('click', function(){
          var array = [];
          $('.bs-validation-prefix:checked').each(function() {
             array.push(this.value);
          });
        });

        $("#removeValue").hide();
        $("#additionalprefix").hide();

        $('#storecredit').on('change',function(){
          if(this.value == 2) {
            $("#additionalprefix").show(400);
          }else{
            $("#additionalprefix").hide(400);
          }
        });

          //create voucher type form
          $("#vouchertype").unbind('submit').bind('submit', function() { //input
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
          $("#show_voucher").on('click', '.addValue', function(){
            var totalvalue = parseInt($("#totalvalue").val());
            var remove = '<button class="btn btn-primary removeValue" data-valuenum="'+totalvalue+'" style="background-color:#122620; border-color:#122620">Remove</button>'
            $('#addValue_'+totalvalue).html(remove);
            totalvalue = totalvalue + 1;
            $('#totalvalue').val(totalvalue);

            addVoucher=
            '<div id="addVoucherValue_'+totalvalue+'">'+
              '<div class=" row mb-3">'+
                '<div class="col-md-3">'+
                  '<label class="form-label mb-2 mt-2" for="bs-validation-value2">Voucher Value</label>'+
                    '<span style="color:red">*</span>'+
                  '<input type="text" id="vouchervalue_'+totalvalue+'"  name="value_'+totalvalue+'" placeholder="Eg: 30" class="form-control" value="" required/>'+
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

            $('#show_voucher').append(addVoucher);

            //only number allow on voucher value
            $("#vouchervalue_"+totalvalue).on('input', function(e){
              $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
          });

          $('#show_voucher').on('click','.removeValue',function() {
            var valuenum = $(this).data('valuenum');
            $("#addVoucherValue_"+totalvalue).remove();
          });


          //jquery for disabled prefix/serial Position
          $('.prefix1').change(function() {
            if(this.checked) {
              $('.serial1').attr('disabled', !$("serial1").attr('disabled'));
            } else {
              $('.serial1').removeAttr('disabled', !$("serial1").attr('disabled'));
            }
          });

          $('.prefix2').change(function() {
            if(this.checked) {
              $('.serial2').attr('disabled', !$("serial2").attr('disabled'));
            } else {
              $('.serial2').removeAttr('disabled', !$("serial2").attr('disabled'));
            }
          });

          $('.prefix3').change(function() {
            if(this.checked) {
              $('.serial3').attr('disabled', !$("serial3").attr('disabled'));
            } else {
              $('.serial3').removeAttr('disabled', !$("serial3").attr('disabled'));
            }
          });

          $('.prefix4').change(function() {
            if(this.checked) {
              $('.serial4').attr('disabled', !$("serial4").attr('disabled'));
            } else {
              $('.serial4').removeAttr('disabled', !$("serial4").attr('disabled'));
            }
          });

          $('.prefix5').change(function() {
            if(this.checked) {
              $('.serial5').attr('disabled', !$("serial5").attr('disabled'));
            } else {
              $('.serial5').removeAttr('disabled', !$("serial5").attr('disabled'));
            }
          });

          $('.prefix6').change(function() {
            if(this.checked) {
              $('.serial6').attr('disabled', !$("serial6").attr('disabled'));
            } else {
              $('.serial6').removeAttr('disabled', !$("serial6").attr('disabled'));
            }
          });

          $('.prefix7').change(function() {
            if(this.checked) {
              $('.serial7').attr('disabled', !$("serial7").attr('disabled'));
            } else {
              $('.serial7').removeAttr('disabled', !$("serial7").attr('disabled'));
            }
          });

          $('.prefix8').change(function() {
            if(this.checked) {
              $('.serial8').attr('disabled', !$("serial8").attr('disabled'));
            } else {
              $('.serial8').removeAttr('disabled', !$("serial8").attr('disabled'));
            }
          });

          $('.prefix9').change(function() {
            if(this.checked) {
              $('.serial9').attr('disabled', !$("serial9").attr('disabled'));
            } else {
              $('.serial9').removeAttr('disabled', !$("serial9").attr('disabled'));
            }
          });

          //checking for prefix value to array
          $('.prefix').on('click', function() {
            var prefixx = '';
            var k = 1;
            $('input[name="prefixposition[]"]:checked').each(function() {
              if (k != 1) {
                var coma = ',';
              }else {
                var coma = '';
              }
              prefixx += coma+''+this.value;
              ++k;
            })
            console.log(prefixx);
            $('#prefixx').val(prefixx);
          });


          $('#bs-validation-serial').hide();

          $('#resetfield').click(function() {
            $('#bs-validation-serial').hide(400);
            $('#position').prop('selectedIndex',0);
            $('#unserializePrefixnumber').val('');
            $('.prefix').removeAttr('disabled', !$("prefix").attr('disabled'));
            $('.custom-option').removeClass('checked');
          });

          $('#position').on('change', function() {
            if(this.value == 1)
            {
              $('.prefix1').attr('disabled', !$("prefix1").attr('disabled'));
              $('.serial1').attr('disabled', !$("serial1").attr('disabled'));
            } else {
              $('.prefix1').removeAttr('disabled', !$("prefix1").attr('disabled'));
              $('.serial1').removeAttr('disabled', !$("serial1").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 2)
            {
              $('.prefix2').attr('disabled', !$("prefix2").attr('disabled'));
              $('.serial2').attr('disabled', !$("serial2").attr('disabled'));
            } else {
              $('.prefix2').removeAttr('disabled', !$("prefix2").attr('disabled'));
              $('.serial2').removeAttr('disabled', !$("serial2").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 3)
            {
              $('.prefix3').attr('disabled', !$("prefix3").attr('disabled'));
              $('.serial3').attr('disabled', !$("serial3").attr('disabled'));
            } else {
              $('.prefix3').removeAttr('disabled', !$("prefix3").attr('disabled'));
              $('.serial3').removeAttr('disabled', !$("serial3").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 4)
            {
              $('.prefix4').attr('disabled', !$("prefix4").attr('disabled'));
              $('.serial4').attr('disabled', !$("serial4").attr('disabled'));
            } else {
              $('.prefix4').removeAttr('disabled', !$("prefix4").attr('disabled'));
              $('.serial4').removeAttr('disabled', !$("serial4").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 5)
            {
              $('.prefix5').attr('disabled', !$("prefix5").attr('disabled'));
              $('.serial5').attr('disabled', !$("serial5").attr('disabled'));
            } else {
              $('.prefix5').removeAttr('disabled', !$("prefix5").attr('disabled'));
              $('.serial5').removeAttr('disabled', !$("serial5").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 6)
            {
              $('.prefix6').attr('disabled', !$("prefix6").attr('disabled'));
              $('.serial6').attr('disabled', !$("serial6").attr('disabled'));
            } else {
              $('.prefix6').removeAttr('disabled', !$("prefix6").attr('disabled'));
              $('.serial6').removeAttr('disabled', !$("serial6").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 7)
            {
              $('.prefix7').attr('disabled', !$("prefix7").attr('disabled'));
              $('.serial7').attr('disabled', !$("serial7").attr('disabled'));
            } else {
              $('.prefix7').removeAttr('disabled', !$("prefix7").attr('disabled'));
              $('.serial7').removeAttr('disabled', !$("serial7").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 8)
            {
              $('.prefix8').attr('disabled', !$("prefix8").attr('disabled'));
              $('.serial8').attr('disabled', !$("serial8").attr('disabled'));
            } else {
              $('.prefix8').removeAttr('disabled', !$("prefix8").attr('disabled'));
              $('.serial8').removeAttr('disabled', !$("serial8").attr('disabled'));
            }
          });

          $('#position').on('change', function() {
            if(this.value == 9)
            {
              $('.prefix9').attr('disabled', !$("prefix9").attr('disabled'));
              $('.serial9').attr('disabled', !$("serial9").attr('disabled'));
            } else {
              $('.prefix9').removeAttr('disabled', !$("prefix9").attr('disabled'));
              $('.serial9').removeAttr('disabled', !$("serial9").attr('disabled'));
            }
          });

          $('#unserializePrefixnumber').prop('disabled', true);
            $('#position').on('change', function() {
              if(this.value == 0)
              {
                $('#unserializePrefixnumber').prop('disabled', true);
              } else {
                $('#unserializePrefixnumber').prop('disabled', false);
              }
            });


          //checking for prefix position value
          $('.prefix').on('change', function() {
            var currentprefix = this.value;
            var serialize     = $('#position').val();

            if(currentprefix > serialize)  {
              for (var i = 1; i < serialize; i++) {
                $('.prefix'+i).attr('disabled', !$("prefix"+i).attr('disabled'));
                // console.log(i);
              }
            } else {
              for (var i = serialize; i <10; i++) {
                $('.prefix'+i).attr('disabled', !$("prefix"+i).attr('disabled'));
              }
            }
          });

          $('.serial').on('change', function() {
            var currentserial = this.value;
            var serialize     = $('#position').val();

            if(currentserial > serialize)  {
              for (var i = 1; i < serialize; i++) {
                $('.serial'+i).attr('disabled', !$("serial"+i).attr('disabled'));
                // console.log(i);
              }
            } else {
              for (var i = serialize; i <10; i++) {
                $('.serial'+i).attr('disabled', !$("serial"+i).attr('disabled'));
              }
            }
          });


          //checking for prefix range length
          $('#submitbtn1').on('click', function() {
            var prefix1 = $('#prefix1').val().length;
            var prefix2 = $('#prefix2').val().length;

            if(prefix1 >= prefix2) {
              var length = prefix1;
            } else {
              var length = prefix2;
            }

            var prefixcheck = $('input[name="prefixposition[]"]:checked').length
             if(prefixcheck < length) {
               snack('Insufficient prefix position selected','danger');
             } else {
               $('#bs-validation-serial').show(400);
               $('.prefix').attr('disabled', !$("prefix").attr('disabled'));
             }
          });
      });
      </script>
  </body>
</html>
