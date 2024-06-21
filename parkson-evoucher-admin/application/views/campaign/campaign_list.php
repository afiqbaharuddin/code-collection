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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <title>Campaign List</title>
    <?php echo $header; ?>

    <style>
      .ui-datepicker {
          background: #333;
          border: 1px solid #555;
          color: #fff;
      }
    </style>
  </head>

  <body style="background-color:#F1EDE3">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu sidebar -->
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
                      <h5 class="content-header-title float-left pr-1 mb-0">CAMPAIGN</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Campaign</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Campaign</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row g-4 mb-4">
                <?php ($cardcampaign); foreach ($cardcampaign as $cards): ?>
                  <?php if (($cards->ExtendDate != null && $cards->CampaignStatusId == 3) || ($cards->ExtendDate != "" && $cards->CampaignStatusId == 3) ){

                    if ($expirydate >= $cards->ExtendDate) { ?>
                    <div class="col-sm-6 col-xl-3">
                      <div class="card">
                        <div class="card-body" style="background:#fff; border-radius:8px; box-shadow:0px 0px 25px 0 rgba(25, 42, 70, 0.13)">
                          <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                              <span style="font-weight:450; color:#2F4F4F"><?php echo $cards->CampaignName ?></span>
                              <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2" style="color:#2F4F4F"><?php

                                  $origin = new DateTime(date('Y-m-d'));
                                  $target = new DateTime(date('Y-m-d', strtotime($cards->ExtendDate)));
                                  $interval = $origin->diff($target);
                                  echo $interval->format('%R%a days');
                                 ?></h4>
                              </div>
                              <small style="color:#FC2E20; font-weight:700; font-size:14px">Due Soon</small>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                              <i class="bx bxs-offer bx-sm" style="color:#5a8dee;"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } } elseif (($cards->EndDate != null && $cards->CampaignStatusId == 1) || ($cards->EndDate != "" && $cards->CampaignStatusId == 1)) {
                    if ($expirydate >= $cards->ExtendDate) { ?>
                    <div class="col-sm-6 col-xl-3">
                      <div class="card">
                        <div class="card-body" style="background:#fff; border-radius:8px; box-shadow:0px 0px 25px 0 rgba(25, 42, 70, 0.13)">
                          <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                              <span style="font-weight:450; color:#2F4F4F"><?php echo $cards->CampaignName ?></span>
                              <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2" style="color:#2F4F4F"><?php

                                  $origin = new DateTime(date('Y-m-d'));
                                  $target = new DateTime(date('Y-m-d', strtotime($cards->EndDate)));
                                  $interval = $origin->diff($target);
                                  echo $interval->format('%R%a days');
                                 ?></h4>
                              </div>
                              <small style="color:#FC2E20; font-weight:700; font-size:14px">Due Soon</small>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                              <i class="bx bxs-offer bx-sm" style="color:#5a8dee;"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } } ?>
                <?php endforeach; ?>
              </div>



              <!--  List Table -->
              <!-- <h4>List of Campaign</h4> -->

              <div class="card">
                <div class="card-header border-bottom">

                  <div class="d-flex justify-content-between">
                    <div class="card-title">
                      <h4>List of Campaign</h4>
                    </div>

                    <?php
                    $createcampaign     = $this->RolePermission_Model->menu_master(5);

                    if ($createcampaign->Create == 1) { ?>
                    <div class="add-new-campaign">
                      <button type="button" name="button"  tabindex="0" class="dt-button add-new btn btn-success" data-bs-toggle="offcanvas" data-bs-target="#Campaign">
                        <span><i class="bx bx-plus me-0 me-lg-2"></i>
                        <span class="d-none d-lg-inline-block">Add New Campaign</span>
                      </span>
                      </button>
                    </div>
                    <?php }  ?>
                  </div>

                <div class="card-datatable table-responsive">
                  <table class="datatables-users table border-top" id="campaigntable" style="width:100%">
                    <thead style="background-color:#122620">
                      <tr>
                        <th  style="color:white">Campaign ID</th>
                        <th  style="color:white">Name</th>
                        <th  style="color:white">VoucherType</th>
                        <th  style="color:white">Start Date</th>
                        <th  style="color:white">End Date</th>
                        <th  style="color:white">Extend Date</th>
                        <th  style="color:white">Created By</th>
                        <th  style="color:white">Status</th>
                        <?php
                        $action     = $this->RolePermission_Model->menu_master(5);

                        if ($action->Update == 1) { ?>
                        <th style="color:white">Action</th>
                        <?php }  ?>
                      </tr>
                    </thead>
                  </table>
                  </div>
                </div>
              </div>


                <!-- Offcanvas to Create new campaign -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="Campaign" aria-labelledby="offcanvasNewCampaignLabel" style="width:50%">
                  <div class="offcanvas-header border-bottom">
                    <h5 id="offcanvasNewCampaignLabel" class="offcanvas-title" style="padding-left: 40%;">Create New Campaign</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>

                  <div class="offcanvas-body mx-0 flex-grow-0">
                    <form class="" action="<?php echo base_url(); ?>campaign/Campaign/create" method="post" id="CreateCampaignForm">
                      <div class="mb-4">
                        <label class="form-label" for="bs-validation-campaignType">Campaign Type</label>
                        <span style="color:red">*</span>
                        <select id="bs-validation-campaignType" name="camptype" class="form-select campaign-type" required>
                          <?php foreach ($camptype as $row) { ?>
                            <option value="<?php echo $row->CampaignTypeId; ?>"><?php echo $row->CampaignTypeName; ?></option>
                          <?php  } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Campaign Type.</div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label" for="bs-validation-campaignName">Campaign Name</label>
                        <span style="color:red">*</span>
                        <input type="text" class="form-control" id="bs-validation-campaignName" placeholder="E.g: Raya Sales Campaign" name="campaignname" aria-label="Raya Sales Campaign" required/>
                        <div class="invalid-feedback">*This field is required. Please enter Campaign Name.</div>
                      </div>

                      <div class="mb-4">
                        <label for="select2Multiple" for="bs-validation-voucherType" class="form-label">Voucher Type</label>
                        <span style="color:red">*</span>
                        <select id="bs-validation-voucherType" name="vouchertype[]" class="select2 form-select" multiple required>
                          <?php foreach ($vouchertype as $row) { ?>
                            <option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>
                          <?php  } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Voucher Type.</div>
                      </div>

                      <div class="mb-4">
                        <label for="select2Multiple" for="bs-validation-store" class="form-label">Store</label>
                        <span style="color:red">*</span>
                        <select id="bs-validation-store" name="store[]" class="select2 form-select" multiple required>
                          <?php foreach ($store as $row) { ?>
                            <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                          <?php  } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Store.</div>
                      </div>

                    <div class="mb-3" id="campaign-start-date">
                      <label class="form-label" for="campaign-start-date">Start Date</label>
                      <span style="color:red">*</span>
                      <input type="date" class="form-control" name="startDate"  min="<?php echo date('Y-m-d',strtotime('+1 days')); ?>" required/>
                      <div class="invalid-feedback">*This field is required. Please select the Start Date for this Campaign.</div>
                    </div>

                    <div class="mb-3" id="campaign-end-date" >
                      <label class="form-label" for="campaign-end-date">End Date</label>
                      <span style="color:red">*</span>
                      <input type="date" class="form-control" name="endDate" min="<?php echo date('Y-m-d', strtotime('+1 days')); ?>"required/>
                      <div class="invalid-feedback">*This field is required. Please select the End Date for this Campaign.</div>
                    </div>

                      <div class="mb-4">
                        <!-- <input type="hidden" name="storetest" value="123"> -->
                        <label class="form-label" for="bs-validation-redeemType">Redeem Type</label>
                        <span style="color:red">*</span>
                        <select id="bs-validation-redeemType" name="redeemtype" class="form-select redeem-type" required>
                          <option value="">Select Redeem  Type</option>
                          <?php foreach ($redeemtype as $row) { ?>
                            <option value="<?php echo $row->RedeemTypeId; ?>"><?php echo $row->RedeemTypeName; ?></option>
                          <?php  } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Redeem Type.</div>
                      </div>

                      <div class="mb-4" id="card-list">
                        <label for="select2Multiple" for="bs-validation-card" class="form-label">Card</label>
                        <span style="color:red">*</span>
                        <select id="bs-validation-card" name="card[]" class="select2 form-select" multiple required>
                          <?php foreach ($card as $row) { ?>
                            <option value="<?php echo $row->CardId; ?>"><?php echo $row->CardName; ?></option>
                          <?php  } ?>
                        </select>
                        <div class="invalid-feedback">*This field is required. Please select Card.</div>
                      </div>

                       <div class="mb-3">
                        <label class="form-label" for="remark-status">Remark</label>
                        <textarea name="remark" id="remarkStatus" class="form-control" rows="3"></textarea>
                      </div>

                      <div class="pt-4">
                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="button" class="btn btn-primary me-sm-2 me-1 data-submit" style="background-color:#122620; border-color:#122620" id="submitbtn">Submit</button>
                            <button type="reset" id="reset_addcampaign_form" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                      </div>
                    </form>
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

  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

  <script src="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $.fn.dataTable.ext.errMode = 'none';

      $("#card-list").show();

      // datatable campaign
      var campaigntable = $('#campaigntable').DataTable({
        columnDefs: [
          {targets: [-1], orderable: false}],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>campaign/Campaign/campaignlisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
            }
        },
        "bDestroy": true,
      });
      campaigntable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if (json != null) {
          $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        }else {
          getToken();
        }
      });

      $('#submitbtn').on('click', function(){
        $("#CreateCampaignForm").submit();
      });

      //  Create Campaign
      $("#CreateCampaignForm").unbind('submit').bind('submit', function() { //input
      var form = $(this);
      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
          if (data.status == true)
          {
            $("#CreateCampaignForm")[0].reset();
            $('#Campaign').offcanvas('hide');
            campaigntable.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
            snack(data.message,'success');

            if (data.storeError !='')
            {
              $('#Campaign').offcanvas('show');
              snack(data.storeError,'danger');
            }
          }else {
            $('#Campaign').offcanvas('show');
            snack(data.message,'danger');
          }
        },
        error: function(xhr, status, error) {
          // snack('Something went wrong. Please try again later','danger');
        },
      });
      return false;
    });

        $('.campaign-type').on('change', function(){
          if(this.value ==1)
          {
            $("#card-list").show(400);
          }
          else{
            $("#card-list").hide(400);
          }
        });

          var table = $('#campaign').DataTable( {
             columnDefs: [
             {targets: [-1], orderable: false},
          ],
          "paging": false
          });

          //End date/start date jquery
          $(".campaign_startDate").datepicker({
          numberOfMonths: 1,
          dateFormat: 'yy-mm-dd',
          minDate: new Date(),
              onSelect: function(selected) {
                $(".campaign_endDate").datepicker("option","minDate", selected)
              }
          });
          $(".campaign_endDate").datepicker({
              numberOfMonths: 1,
              dateFormat: 'yy-mm-dd',
              onSelect: function(selected) {
                 $(".campaign_startDate").datepicker("option","maxDate", selected)
              }
          });

          //reset form
          $('#reset_addcampaign_form').click(function(){
            $('#bs-validation-voucherType option:selected').remove();
            $('#bs-validation-store option:selected').remove();
            $('#bs-validation-card option:selected').remove();
            location.reload(true);
          });
    });
  </script>
  </body>
</html>
