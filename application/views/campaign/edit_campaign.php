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
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <title>Edit Campaign</title>
    <?php echo $header; ?>

    <style media="screen">
      .dataTables_scrollHeadInner{
        width: 100%!important;
      }
      .ui-datepicker {
          background: #333;
          border: 1px solid #555;
          color: #EEE;
      }
    </style>
  </head>

  <body style="background-color:#F1EDE3">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

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
                        <span class="text-muted fw-light">Forms/</span>EDIT CAMPAIGN
                      </h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Campaign</li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>campaign/Campaign"><u>List of Campaign</u></a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px;">Edit Campaign</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60 "><?php echo $campaign->CampaignName  ?></li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                      <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#editCampaign-pills"
                          aria-controls="editCampaign-pills" aria-selected="true" id="editCampaign-tab">Edit Campaign
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#storeList-pills"
                           aria-controls="storeList-pills" aria-selected="false" id="storeList-tab">List of Stores
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cardList-pills"
                            aria-controls="cardList-pills" aria-selected="false" id="cardList-tab">List of Cards
                          </button>
                        </li>
                      </ul>

                      <div class="tab-content">
                        <div class="tab-pane fade show active" id="editCampaign-pills" role="tabpanel">
                            <h5 class="card-header">Edit Campaign details</h5>
                            <div class="card-body" >
                                <!-- edit campaign form -->
                                  <form class="edit-campaign pt-0" id="editCampaignForm" action="<?php echo base_url(); ?>campaign/EditCampaign/editCampaignForm" method="post">
                                    <input type="hidden" name="" value="<?php echo $terminate_status->CampaignStatusId ?>">
                                    <input type="hidden" name="campaignid" value="<?php echo $campaign->CampaignId; ?>">

                                    <div class="mb-3">
                                      <label class="form-label" for="editCampaignName" style="margin-top:20px">Campaign Name</label>
                                      <input type="text" class="form-control" id="editCampaignName" name="editCampaignName" value="<?php echo $campaign->CampaignName; ?>" disabled/>
                                    </div>

                                    <div class="mb-4">
                                      <label class="form-label" for="editcampaigntype">Campaign Type</label>
                                      <input type="text" class="form-control" id="editcampaigntype" name="editcampaigntype" value="<?php echo $campaign->CampaignTypeName; ?>" disabled/>
                                    </div>

                                    <div class=" mb-4">
                                    <label for="selectpickerMultiple" class="form-label">Voucher Type</label>
                                    <div class="dropdown bootstrap-select show-tick w-100">
                                      <select disabled id="selectpickerMultiple" name="vouchertype[]" class="selectpicker w-100" data-style="btn-default" multiple data-icon-base="bx" data-tick-icon="bx-check text-primary">
                                          <?php foreach ($vouchertype as $row) {
                                            if (in_array($row->VoucherTypeId,$cvtarray)) {
                                              $selectcvt = 'selected';
                                            }else {
                                              $selectcvt = '';
                                            }
                                             ?>
                                            <option value="<?php echo $row->VoucherTypeId; ?>" <?php echo $selectcvt ; ?>><?php echo $row->VoucherName; ?></option>
                                          <?php } ?>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="editStartDate" >Start Date</label>
                                      <input type="date" id="editStartDatet" class="form-control"  name="editStartDate" value="<?php echo $campaign->StartDate; ?>" disabled/>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="editEndDate">End Date</label>
                                      <input type="date" id="editEndDate" class="form-control" name="editEndDate" value="<?php echo $campaign->EndDate; ?>" disabled />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="redeemtype">Redeem Type</label>
                                      <input type="text" id="redeemtype" class="form-control"  name="redeemtype" value="<?php echo $campaign->RedeemTypeName; ?>" disabled/>
                                    </div>

                                    <?php
                                      $permissionSubmitDetails = $this->EditCampaign_Model->SubmitPermissionCampaign($CampaignId);
                                      $today = date('Y-m-d');
                                      if ($permissionSubmitDetails->CampaignStatusId == 4 || $terminate_status->CampaignStatusId == 5){ ?>

                                        <div class="mb-3">
                                          <label class="form-label" for="editcampaignstatus">Status</label>
                                          <input type="text" name="" value="<?php echo $campaign->Status; ?>" class="form-control" disabled>
                                        </div>

                                        <?php if ( $campaign->ExtendDate != null || $campaign->ExtendDate != ""){ ?>
                                          <div class="mb-3" >
                                              <label class="form-label" for="extenddate">Extend Date</label>
                                              <input type="date" class="form-control" name="extenddate" value="<?php echo $campaign->ExtendDate; ?>" disabled/>
                                          </div>
                                        <?php } ?>

                                        <?php if ($campaign->InactiveDate != null || $campaign->InactiveDate != "" ): ?>
                                          <div class="mb-3">
                                              <label class="form-label" for="inactivedate">Inactive Date</label>
                                              <input type="date" class="form-control" name="inactiveDate" value="<?php echo $campaign->InactiveDate; ?>" disabled/>
                                          </div>
                                        <?php endif; ?>

                                        <div class="mb-3"  id="terminatedate" >
                                            <label class="form-label" for="inactivedate">Termination Date</label>
                                            <input type="date" class="form-control" name="terminatedate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" value="<?php echo $campaign->TerminateDate; ?>" disabled/>
                                        </div>

                                        <div class="mb-3">
                                          <label class="form-label" for="remark">Remark</label>
                                          <textarea id="remark" name="remark" class="form-control" rows="3" disabled><?php echo $campaign->Remark; ?></textarea>
                                        </div>

                                    <?php } else { ?>

                                      <div class="mb-3">
                                        <label class="form-label" for="editcampaignstatus">Status</label>
                                        <select id="editcampaignstatus" name="editcampaignstatus" class="form-select">
                                          <?php foreach ($editstatus as $row) {
                                            if ($row->CampaignStatusId == $campaign->CampaignStatusId) {
                                              $select = 'selected';
                                            }else {
                                              $select = '';
                                            }
                                            ?>
                                            <option value="<?php echo $row->CampaignStatusId; ?>" <?php echo $select; ?>><?php echo $row->Status; ?></option>
                                          <?php } ?>
                                        </select>
                                      </div>

                                      <div class="mb-3"  id="extenddate" >
                                          <label class="form-label" for="extenddate">Extend Date</label>
                                          <input type="date" class="form-control"  name="extenddate" min="<?php echo date('Y-m-d',strtotime($campaign->EndDate.'+'.'+1 day')); ?>" value="<?php echo $campaign->ExtendDate; ?>"/>
                                      </div>

                                      <div class="mb-3"  id="inactivedate" >
                                          <label class="form-label" for="inactivedate">Inactive Date</label>
                                          <input type="date" class="form-control" name="inactiveDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate.'+'.'+1 day')); ?>" value="<?php echo $campaign->InactiveDate; ?>"/>
                                      </div>

                                      <div class="mb-3"  id="terminatedate" >
                                          <label class="form-label" for="inactivedate">Termination Date</label>
                                          <input type="date" class="form-control" name="terminatedate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" value="<?php echo $campaign->TerminateDate; ?>"/>
                                      </div>

                                      <div class="mb-3">
                                        <label class="form-label" for="remark">Remark</label>
                                        <textarea id="remark" name="remark" class="form-control" rows="3" ><?php echo $campaign->Remark; ?></textarea>
                                      </div>
                                    <?php } ?>

                                    <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $campaign->AppLoggerId; ?>">
                                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                    <?php
                                      if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5) {  ?>
                                        <button type="button" id="submitbtnEdit" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                    <?php }  ?>

                                    <!-- extend -->
                                    <input type="hidden" name="saveall" id="saveall" value="false">
                                    <!-- terminate -->
                                    <input type="hidden" name="saveallterminate" id="saveallterminate" value="false">

                                    <!-- <div class="pt-4">
                                      <button type="button" id="approvebtn" class="btn btn-primary me-sm-3 me-1">Approve Campaign Request</button>
                                      <button type="button" class="btn btn-label-secondary">Reject</button>
                                    </div> -->
                                  </form>
                                  <!-- edit campaign form -->
                          </div>
                        </div>

                        <div class="tab-pane fade" id="storeList-pills" role="tabpanel">
                          <div class="card-header">
                            <div class="d-flex justify-content-between">
                              <div class="card-title">
                                <h5>Edit Campaign Store</h5>
                              </div>
                              <?php
                              $permissionSubmitDetails = $this->EditCampaign_Model->SubmitPermissionCampaign($CampaignId);
                              if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5): ?>
                              <div class="add-new-store">
                                <button class="dt-button add-new btn btn-success" tabindex="0"
                                  type="button" data-bs-toggle="offcanvas" data-bs-target="#AddCampaignStore">
                                  <span><i class="bx bx-plus me-0 me-lg-2"></i>
                                    <span class="d-none d-lg-inline-block">Add New Store</span>
                                  </span>
                                </button>
                              </div>
                            <?php endif; ?>
                            </div>

                            <!--List of Store page table-->
                            <div class="card-datatable table-responsive">
                              <table class="datatables-stores table border-top" id="tablestorelist">
                                <thead style="background-color:#122620">
                                  <tr>
                                    <th style="color:white">Store Code</th>
                                    <th style="color:white">Name</th>
                                    <th style="color:white">Start Date</th>
                                    <th style="color:white">End Date</th>
                                    <th style="color:white">Status</th>
                                    <th style="color:white">Action</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                          </div>
                          <!-- / List of Store page table-->

                        <!-- Offcanvas to edit campaign ->  add new store -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="AddCampaignStore" aria-labelledby="offcanvasAddStoreLabel" style="width: 50%;">
                          <div class="offcanvas-header border-bottom">
                            <h6 id="offcanvasAddStoreLabel" class="offcanvas-title">Add New Store for Campaign</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>

                          <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="needs-validation" id="addNewStoreForm" action="<?php echo base_url('campaign/EditCampaign/createStore/'.$CampaignId); ?>" method="post">
                            <div class="mb-3">
                              <label for=" bs-validation-storeCode" class="form-label">Store Code</label>
                              <span style="color:red">*</span>
                              <select id="campaignStoreCode" name="campaignStoreCode[]" class="select2 form-select" multiple required>
                                <?php foreach ($storecode as $row) { ?>
                                  <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode; ?></option>
                                <?php } ?>
                              </select>

                              <div class="form-check" style="margin-top:5px">
                                <input id="allstore" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="allstore">Select All Store</label>
                              </div>
                            </div>

                            <div class="mb-3">
                              <label for="campaignStoreName" class="form-label">Store Name</label>
                              <select id="campaignStoreName" name="campaignStoreName[]" class="select2 form-select" disabled multiple>
                                <?php foreach ($storecode as $row) { ?>
                                  <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                <?php } ?>
                              </select>
                            </div>

                            <div class="mb-3" id="editStoreCampaign_startDate">
                              <label class="form-label" for="startDate">Start Date</label>
                              <span style="color:red">*</span>
                              <input type="date" class="form-control" placeholder="yy-mm-dd" name="startDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>"/>
                            </div>

                            <?php if ($terminate_status->CampaignStatusId == 3){ ?>
                              <div class="mb-3" id="editStoreCampaign_editDate">
                                <label class="form-label" for="endDate">End Date</label>
                                <span style="color:red">*</span>
                                <input type="date" class="form-control" placeholder="yy-mm-dd"  name="endDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->ExtendDate)); ?>"/>
                              </div>

                            <?php }else { ?>
                              <div class="mb-3" id="editStoreCampaign_editDate">
                                <label class="form-label" for="endDate">End Date</label>
                                <span style="color:red">*</span>
                                <input type="date" class="form-control" placeholder="yy-mm-dd"  name="endDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>"/>
                              </div>
                            <?php } ?>


                            <div class="mb-4">
                              <label class="form-label" for="bs-validation-status">Status</label>
                              <select id="bs-validation-status" name="bs-validation-status" class="form-select" >
                                <option value="">Select Status</option>
                                <?php foreach ($campaignstatus as $row) { ?>
                                  <option value="<?php echo $row->CampaignStatusId; ?>"><?php echo $row->Status; ?></option>
                                <?php } ?>
                              </select>
                            </div>

                            <?php if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5): ?>
                              <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                              <button type="button" id="submitAddStore" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620">Submit</button>
                              <button type="reset" id="reset_addstore_form" class="btn btn-label-secondary">Cancel</button>
                            <?php endif; ?>
                            </form>
                            <!-- Offcanvas to add new store -->
                          </div>
                        </div>
                        <!-- / Offcanvas to add new campaign store -->

                        <!-- Offcanvas to edit store -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="EditCampaignStore" aria-labelledby="offcanvasAddStoreLabel" style="width: 50%;">
                          <div class="offcanvas-header border-bottom">
                            <h6 id="offcanvasAddStoreLabel" class="offcanvas-title">Edit Store Campaign Details</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>

                          <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="needs-validation" id="editCampaignStoreForm" action="<?php echo base_url(); ?>campaign/editcampaign/editCampaignStore" method="post">
                              <input type="hidden" value="<?php echo $campaign->CampaignStatusId; ?>">

                              <div class="mb-3">
                                <label class="form-label" for="editstorecode">Store Code</label>
                                <span style="color:red">*</span>
                                <input type="text" class="form-control" name="editstorecode" id="editstorecode" disabled/>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="editstorename">Store Name</label>
                                  <input type="text" id="editstorename" class="form-control"  disabled />
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="editStoreStartDate">Start Date</label>
                                <input type="date" id="editStoreStartDate" class="form-control" name="editStoreStartDate" disabled/>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="editStoreEndDate">End Date</label>
                                <span style="color:red">*</span>
                                <input type="date" id="editStoreEndDate" class="form-control endstore" name="editStoreEndDate" disabled/>
                              </div>

                              <?php if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5 ){ ?>
                                <div class="mb-3">
                                  <label class="form-label" for="editStoreStatus">Status</label>
                                  <span style="color:red">*</span>
                                  <select id="editStoreStatus"  name="editStoreStatus" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <?php foreach ($editstorestatus as $row) {?>
                                      <option value="<?php echo $row->CampaignStatusId; ?>"><?php echo $row->Status; ?></option>
                                    <?php } ?>
                                  </select>
                                  <div class="invalid-feedback">*This field is required. Please select Status.</div>
                                </div>

                              <?php }else { ?>
                                <div class="mb-3">
                                  <label class="form-label" for="editStoreStatus">Status</label>
                                  <span style="color:red">*</span>
                                  <input type="text" name="" value="<?php echo $row->Status; ?>" class="form-select" disabled>
                                </div>
                              <?php } ?>

                              <div class="mb-4"  id="inactivestoredate-field">
                                  <label class="form-label" for="inactivestoredate">Inactive Date</label>
                                  <input type="date" class="form-control" id="inactivestoredate" name="inactivestoredate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate.'+'.'+1 day')); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>"/>
                              </div>

                              <?php if ($campaign->CampaignStatusId == 1){ ?>
                                <div class="mb-4"  id="extendstoredate-field">
                                    <label class="form-label" for="editStoreExtendDate">Extend Date</label>
                                    <input type="date" class="form-control" placeholder="yy-mm-dd" id="editStoreExtendDate" name="editStoreExtendDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate.'+'.'+1 day')); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>" />
                                </div>
                              <?php  }elseif ($campaign->CampaignStatusId == 3) { ?>
                                <div class="mb-4"  id="extendstoredate-field">
                                    <label class="form-label" for="editStoreExtendDate">Extend Date</label>
                                    <input type="date" class="form-control" placeholder="yy-mm-dd" id="editStoreExtendDate" name="editStoreExtendDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate.'+'.'+1 day')); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->ExtendDate)); ?>" />
                                </div>
                              <?php } ?>

                              <div class="pt-4">
                                <input type="hidden" name="loggerid" id="loggerid">
                                <input type="hidden" name="storeid" id="storeid">
                                <input type="hidden" name="campaignid" id="campaignid" value="<?php echo $CampaignId; ?>">
                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                  $permissionSubmitDetails = $this->EditCampaign_Model->SubmitPermissionCampaign($CampaignId);

                                  if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5 ) {  ?>
                                    <button type="button" id="btnEditStore" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                <?php }  ?>
                              </div>
                            </form>
                            <!-- Offcanvas to edit store -->
                          </div>
                        </div>
                        </div>

                      <!--card tab page-->
                      <div class="tab-pane fade" id="cardList-pills" role="tabpanel">
                        <div class="card-header ">
                          <div class="d-flex justify-content-between">
                            <div class="card-title">
                              <h5>Edit Campaign Card</h5>
                            </div>

                            <?php
                            $permissionSubmitDetails = $this->EditCampaign_Model->SubmitPermissionCampaign($CampaignId);

                            if ($permissionSubmitDetails->CampaignStatusId != 4 && $permissionSubmitDetails->CampaignStatusId != 5): ?>
                            <div class="add-new-card">
                              <button class="dt-button add-new btn btn-success" tabindex="0"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCard" style="margin-left:15px">
                                <span><i class="bx bx-plus me-0 me-lg-2"></i>
                                  <span class="d-none d-lg-inline-block">Add New Card</span>
                                </span>
                              </button>
                             </div>
                            <?php endif; ?>
                          </div>

                        <!--Table List of Card-->
                        <div class="card-datatable table-responsive">
                          <table class="datatables-stores table border-top" id="tablecardlist">
                            <thead style="background-color:#122620">
                              <tr>
                                <th style="color:white">Card Name</th>
                                <th style="color:white">Start Date</th>
                                <th style="color:white">End Date</th>
                                <th style="color:white">Status</th>
                                <th style="color:white">Action</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>

                        <!--offcanvas add new card-->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCard" aria-labelledby="offcanvasAddCardLabel" style="width: 50%;">
                          <div class="offcanvas-header border-bottom">
                            <h6 id="offcanvasAddCardLabel" class="offcanvas-title">Add New Card for Campaign</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>

                          <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="" id="addNewCardForm" action="<?php echo base_url('campaign/editcampaign/addcard/'.$CampaignId); ?>" method="post">

                              <div class="mb-3">
                                <label for="bs-validation-cardName" class="form-label">Card Name</label>
                                <span style="color:red">*</span>
                                <select id="bs-validation-cardName" name="campaigncardname[]" class="select2 form-select" multiple>
                                  <?php foreach ($addcard as $row) { ?>
                                    <option value="<?php echo $row->CardId; ?>"><?php echo $row->CardName; ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                              <div class="mb-3" id="cardStart">
                                <label class="form-label" for="bs-validation-cardStart">Start Date</label>
                                <span style="color:red">*</span>
                                <input type="date" class="form-control" name="cardStart" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>" />
                              </div>

                              <?php if ($terminate_status->CampaignStatusId == 3){ ?>
                                <div class="mb-3" id="cardEnd">
                                  <label class="form-label" for="bs-validation-cardEnd">End Date</label>
                                  <span style="color:red">*</span>
                                  <input type="date" class="form-control" placeholder="yy-mm-dd"  name="cardEnd" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->ExtendDate)); ?>"/>
                                </div>

                              <?php }else { ?>
                                <div class="mb-3" id="cardEnd">
                                  <label class="form-label" for="bs-validation-cardEnd">End Date</label>
                                  <span style="color:red">*</span>
                                  <input type="date" class="form-control" placeholder="yy-mm-dd"  name="cardEnd" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>"/>
                                </div>
                              <?php } ?>

                              <div class="mb-4">
                                <label class="form-label" for="bs-validation-cardStatus">Status</label>
                                <select id="bs-validation-cardStatus" name="cardStatus" class="form-select">
                                  <option value="">Select Status</option>
                                  <?php foreach ($cardstatus as $row) { ?>
                                    <option value="<?php echo $row->CampaignStatusId; ?>"><?php echo $row->Status; ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                              <div class="pt-4">
                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620"
                                >Submit</button>
                                <button type="reset" id="reset_addcard_form" class="btn btn-label-secondary">Cancel</button>
                              </div>
                            </form>
                          </div>
                        </div>
                        <!-- / Offcanvas add new card -->

                        <!-- Offcanvas edit card-->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditCard" aria-labelledby="offcanvasEditCardLabel" style="width: 50%;">
                          <div class="offcanvas-header border-bottom">
                            <h6 id="offcanvasEditCardLabel" class="offcanvas-title">Edit Card for Campaign</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>

                          <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="needs-validation" id="editCardForm" action="<?php echo base_url(); ?>campaign/editcampaign/editCardCampaign" method="post">
                              <input type="hidden"  value="<?php echo $campaign->CampaignStatusId; ?>">
                              <div class="mb-3">
                                <label class="form-label" for="editCardName">Card</label>
                                <input type="text" class="form-control" id="editCardName" name="editCardName" value="" disabled />
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="editCardStartDate">Start Date</label>
                                <input type="date" class="form-control" id="editCardStartDate" name="editCardStartDate" disabled/>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="editCardEndDate">End Date</label>
                                <input type="date" class="form-control" id="editCardEndDate" name="editCardEndDate" disabled/>
                              </div>

                              <?php if ($campaign->CampaignStatusId !=4 && $campaign->CampaignStatusId !=5 ){ ?>
                                <div class="mb-3">
                                  <label class="form-label" for="editCardStatus">Status</label>
                                  <select id="editCardStatus" name="editCardStatus" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <?php foreach ($editcardstatus as $row) { ?>
                                      <option value="<?php echo $row->CampaignStatusId; ?>"><?php echo $row->Status; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              <?php } ?>


                              <div class="mb-4" id="inactivedatefield">
                                  <label class="form-label" for="inactiveCardDate">Inactive Date</label>
                                  <input type="date" id="inactiveCardDate" class="form-control" name="inactiveCardDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>" />
                              </div>

                              <?php if ($campaign->CampaignStatusId == 1){ ?>
                                <div class="mb-4" id="extenddatefield">
                                    <label class="form-label" for="extendCardDate">Extend Date</label>
                                    <input type="date" id="extendCardDate" class="form-control"  name="extendCardDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->EndDate)); ?>"/>
                                </div>
                              <?php }elseif ($campaign->CampaignStatusId == 3) { ?>
                                <div class="mb-4" id="extenddatefield">
                                    <label class="form-label" for="extendCardDate">Extend Date</label>
                                    <input type="date" id="extendCardDate" class="form-control"  name="extendCardDate" min="<?php echo date('Y-m-d',strtotime($campaign->StartDate)); ?>" max="<?php echo date('Y-m-d',strtotime($campaign->ExtendDate)); ?>"/>
                                </div>
                              <?php } ?>

                              <div class="pt-4" id="hideSubmit">
                                <input type="hidden" name="loggerid" id="loggerid" value="">
                                <input type="hidden" name="cardid" id="cardid" value="">
                                <input type="hidden" name="campaignid" id="campaignid" value="<?php echo $CampaignId; ?>">
                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                <?php
                                  $permissionSubmitDetails = $this->EditCampaign_Model->SubmitPermissionCampaign($CampaignId);

                                  if ($permissionSubmitDetails->CampaignStatusId !=4 && $permissionSubmitDetails->CampaignStatusId != 5) {
                                    ?>
                                      <button type="button" id="btnEditCard" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                    <?php
                                 }  ?>
                              </div>
                            </form>
                          </div>
                        </div>
                        <!-- / Offcanvas edit card-->
                      </div>

                    </div>
                  </div>
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
    <!-- <script src="<?php echo base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script> -->

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

    <!-- Form Validation Script -->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>
    <!-- / Form Validation -->

    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
     <script>
       $(document).ready(function(){
         $.fn.dataTable.ext.errMode = 'none';
  //=============================================================================================================//
  //Ajax and query section for List of Store

       //table store list
       var tablestorelist = $('#tablestorelist').DataTable({
         responsive:true,
         columnDefs: [
           {targets: [-1], orderable: false}],
         "searching": true,
         "processing": true,
         "serverSide": true,
         "scrollX": true,
         "ajax": {
             "url": "<?php echo base_url(); ?>campaign/editcampaign/storelisting/<?php echo $CampaignId; ?>",
             "type": "POST",
             "data": function(data) {
               var csrfHash  = $('.txt_csrfname').val();
               data.<?= $csrf['name']; ?> = csrfHash;
             }
         },
         "bDestroy": true,
       });
       tablestorelist.on('xhr.dt', function ( e, settings, json, xhr ) {
         if (json != null) {
           $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
         }else {
           getToken();
         }
       });

         //create store campaign (in edit campaign)
         //form
         $("#addNewStoreForm").unbind('submit').bind('submit', function() {
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
               $('#AddCampaignStore').offcanvas('hide');
               $("#addNewStoreForm")[0].reset();
               tablestorelist.ajax.reload();
               snack(data.message, 'success');
             }
             else
             {
               $('#AddCampaignStore').offcanvas('show');
               snack(data.message,'danger');
             }
           },
           error: function(xhr, status, error) {
             snack('Something went wrong. Please try again later','danger');
           },
         });
         return false;
       });

       $('#submitAddStore').on('click', function(){
         $("#addNewStoreForm").submit();
       });


       //Edit store campaign (in edit campaign)
       //form
       $("#editCampaignStoreForm").unbind('submit').bind('submit', function() {
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
               $('#EditCampaignStore').offcanvas('hide');
               $("#editCampaignStoreForm")[0].reset();
               tablestorelist.ajax.reload();
               snack(data.message, 'success');
             }
             else
             {
               $('#EditCampaignStore').offcanvas('show');
               snack(data.message,'danger');
             }
           },
           error: function(xhr, status, error) {
             snack('Something went wrong. Please try again later','danger');
           },
         });
         return false;
       });

       $('#btnEditStore').on('click', function(){
         $("#editCampaignStoreForm").submit();
       });


       //echo detail form edit campaign store using ajax
       $('#tablestorelist').on('click','.editStore',function() {
         var storeid    = $(this).data('storeid');
         $('#storeid').val(storeid);
         var campaignid = $('#campaignid').val();

         var csrfName = $('.txt_csrfname').attr('name');
         var csrfHash = $('.txt_csrfname').val();

         $.ajax({
           url: "<?php echo base_url(); ?>campaign/editcampaign/storeDetails",
           type: "POST",
           data: {storeid:storeid,campaignid:campaignid, <?= $csrf['name']; ?> : csrfHash},
           dataType: 'json',
           success:function(data)
           {
             $('.txt_csrfname').val(data.token);

             $('#loggerid').val(data.details.AppLoggerId);
             $('#editstorecode').val(data.details.StoreCode).change();
             $('#editstorename').val(data.details.StoreName).change();
             $('#editStoreStartDate').val(data.details.StartDate);
             $('#editStoreEndDate').val(data.details.EndDate);
             $('#editStoreStatus').val(data.details.CampaignStatusId).change();
             $('#inactivestoredate').val(data.details.InactiveDate).change();
             $('#editStoreExtendDate').val(data.details.ExtendDate).change();
             $('#storeId').val(data.details.StoreId);
           },
         });
       });

       //reset form
       $('#reset_addstore_form').click(function(){
         $('#campaignStoreCode option:selected').remove();
         $('#campaignStoreName option:selected').remove();
       });

       $("#campaignStoreCode").change(function(){
         var value = $(this).val();
         console.log(value);
         $('#campaignStoreName').val(value).change();
       });

       $("#allstore").click(function(){
          if($("#allstore").is(':checked')){
              $("#campaignStoreCode > option").prop("selected", "selected");
              $("#campaignStoreCode").trigger("change");
          } else {
              $("#campaignStoreCode > option").removeAttr("selected");
              $("#campaignStoreCode").trigger("change");
          }
      });

  //====================================================================================================//
  //Ajax and query section for Card

        //hide submit edit
        $('#editCardStatus').on('change', function(){
          if(this.value == 4)
          {
            $("#hideSubmit").hide(400);
          } else {
            $("#hideSubmit").show(400);
          }
        });

         //table card list
         var tablecardlist = $('#tablecardlist').DataTable({
           columnDefs: [
             {targets: [-1], orderable: false}],
           "searching": true,
           "processing": true,
           "serverSide": true,
           "scrollX": true,
           "ajax": {
               "url": "<?php echo base_url(); ?>campaign/editcampaign/cardlisting/<?php echo $CampaignId; ?>",
               "type": "POST",
               "data": function(data) {
                 var csrfHash  = $('.txt_csrfname').val();
                 data.<?= $csrf['name']; ?> = csrfHash;
               }
           },
           "bDestroy": true,
         });
         tablecardlist.on('xhr.dt', function ( e, settings, json, xhr ) {
           if (json != null) {
             $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
           }else {
             getToken();
           }
         });

         //create card campaign in (edit campaign)
         //input form
         $("#addNewCardForm").unbind('submit').bind('submit', function() {
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
               $('#offcanvasAddCard').offcanvas('hide');
               $("#addNewCardForm")[0].reset();
               tablecardlist.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
               snack(data.message,'success');
             }else {
               $('#offcanvasAddCard').offcanvas('show');
               snack(data.message,'danger');
             }
           },
           error: function(xhr, status, error) {
             snack('Something went wrong. Please try again later','danger');
           },
         });
         return false;
       });

       // echo detail  edit card campaign using ajax
       $('#tablecardlist').on('click','.editCard',function() {
         var cardid    = $(this).data('cardid');
         $('#cardid').val(cardid);
         var campaignid = $('#campaignid').val();

         var csrfName = $('.txt_csrfname').attr('name');
         var csrfHash = $('.txt_csrfname').val();

         $.ajax({
           url: "<?php echo base_url(); ?>campaign/editcampaign/cardDetails",
           type: "POST",
           data: {cardid:cardid,campaignid:campaignid, <?= $csrf['name']; ?> : csrfHash},
           dataType: 'json',
           success:function(data)
           {
             $('.txt_csrfname').val(data.token);

             $('#loggerid').val(data.details.AppLoggerId);
             $('#editCardName').val(data.details.CardName).change();
             $('#editCardStartDate').val(data.details.StartDate);
             $('#editCardEndDate').val(data.details.EndDate);
             $('#editCardStatus').val(data.details.CampaignStatusId).change();
             $('#inactiveCardDate').val(data.details.InactiveDate).change();
             $('#extendCardDate').val(data.details.ExtendDate).change();
             $('#cardid').val(data.details.CardId);
           },
         });
       });

       //Edit card campaign in (edit campaign)
       //input form
       $("#editCardForm").unbind('submit').bind('submit', function() {
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
               $('#offcanvasEditCard').offcanvas('hide');
               $("#editCardForm")[0].reset();
               tablecardlist.ajax.reload();
               snack(data.message, 'success');
             }else {
               $('#offcanvasEditCard').offcanvas('show');
               snack(data.message,'danger');
             }
           },
           error: function(xhr, status, error) {
             snack('Something went wrong. Please try again later','danger');
           },
         });
         return false;
       });

       $('#btnEditCard').on('click', function(){
         $("#editCardForm").submit();
       });

       //reset form
       $('#reset_addcard_form').click(function(){
         $('#bs-validation-cardName option:selected').remove();
       });

  // end ==================================================================================================== end //
  //Ajax and query section for Store

            //edit campaign
           //jquery for add/edit campaign forms
           $("#inactivedate").hide();
           $("#extenddate").hide();
           $("#terminatedate").hide();
           $("#extendstoredate-field").hide();
           $("#inactivestoredate-field").hide();
           $("#inactivedatefield").hide();
           $("#extenddatefield").hide();

           var campaignstatus = <?php echo $campaign->CampaignStatusId; ?>;
           var campaignid     = <?php echo $campaign->CampaignId; ?>;

           if (campaignstatus == 2) {
             $("#inactivedate").show(400);
             $("#inactivedate").show(400);
           }

           if (campaignstatus == 3) {
             $("#extenddate").show(400);
             $("#extenddate").show(400);
           }

           if (campaignstatus == 5) {
             $("#terminatedate").show(400);
             $("#terminatedate").show(400);
           }

           // main edit campaign inactive
           $('#editcampaignstatus').on('change', function(){
             if(this.value == 2)
             {
               $("#inactivedate").show(400);
             } else {
               $("#inactivedate").hide(400);
             }
           });

           // main edit campaign extend
           $('#editcampaignstatus').on('change', function(){
             if(this.value == 3)
             {
               $("#extenddate").show(400);
             } else {
               $("#extenddate").hide(400);
             }
           });

           $('#editcampaignstatus').on('change', function(){
             if(this.value == 5)
             {
               $("#terminatedate").show(400);
             } else {
               $("#terminatedate").hide(400);
             }
           });

            //extend for edit store
             $('#editStoreStatus').on('change', function(){
               if(this.value == 3)
               {
                 $("#extendstoredate-field").show(400);
               }
               else{
                 $("#extendstoredate-field").hide(400);
               }
             });

             //inactive for edit store
             $('#editStoreStatus').on('change', function(){
               if(this.value == 2)
               {
                 $("#inactivestoredate-field").show(400);
               }
               else{
                 $("#inactivestoredate-field").hide(400);
               }
             });

            // inactive for edit card
           $('#editCardStatus').on('change', function(){
             if(this.value == 2)
             {
               $("#inactivedatefield").show(400);
             }
             else{
               $("#inactivedatefield").hide(400);
             }
           });

           // extend for edit card
           $('#editCardStatus').on('change', function(){
             if(this.value == 3)
             {
               $("#extenddatefield").show(400);
             }
             else{
               $("#extenddatefield").hide(400);
             }
           });

            $('#submitbtnEdit').on('click', function(){
              if ($('#editcampaignstatus').val() == 3) {
                extend_confirmation();
              } else if ($('#editcampaignstatus').val() == 5) {
                terminate_confirmation();
              } else {
                $("#editCampaignForm").submit();
              }
            });

            $("#editCampaignForm").unbind('submit').bind('submit', function() {
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

            //edit all extend
           // confirmation();
             function extend_confirmation(){
               $.confirm({
                 theme: 'dark',
                 title: false,
                 content: 'Do you want to extend all Stores and Cards End Date inside this campaign?',
                 typeAnimated: true,
                 buttons: {
                   confirm: {
                       text: 'Yes',
                       action : function(){
                         $('#saveall').val('true');
                           $("#editCampaignForm").submit();
                            snack("All Stores and Cards are Extended for this Campaign", 'success');
                           }
                       },
                       cancel: {
                           text: 'No',
                           btnClass: 'btn-red',
                           action: function(){
                             snack("Only this Campaign is updated for Extend", 'success');
                           }
                       },
                   },
                 })
               };

            //edit confirmation campaign termination
            function terminate_confirmation(){
              $.confirm({
                theme: 'dark',
                title: false,
                content: 'Do you want to Terminate this Campaign?',
                typeAnimated: true,
                buttons: {
                  confirm: {
                      text: 'Yes',
                      action : function(){
                        $('#saveallterminate').val('true');
                          $("#editCampaignForm").submit();
                           snack("Campaign have been Terminated", 'success');
                          }
                      },
                      cancel: {
                          text: 'No',
                          btnClass: 'btn-red',
                          action: function(){
                            snack("Campaign Termination Cancelled", 'danger');
                            location.reload(true);
                          }
                      },
                  },
                })
              };
       });
     </script>
  </body>
</html>
