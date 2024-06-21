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
    <title>Assign Manager on Duty</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <?php echo $header; ?>
  </head>

  <style>
    body {
     background-color:#F1EDE3 ;
    }
    .ui-datepicker {
        background: #333;
        border: 1px solid #555;
        color: #EEE;
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
                        <span class="text-muted fw-light">Forms/</span>USER DETAILS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Users</li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>user/User/UserList"><u>List of Users</u></a>
                          </li>
                          <li class="breadcrumb-item">Edit User</li>
                          <li class="breadcrumb-item">Assign Manager on Duty</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">User Details</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Multi Column with Form Separator -->
              <div class="row">
                <div class="col-xl-6">
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-target="#navs-pills-assignManager"
                        aria-controls="navs-pills-assignManager" aria-selected="true">Assign Manager on Duty
                        </button>
                      </li>
                    </ul>

                    <div class="tab-content" style="width:200%">
                      <div class="tab-pane fade show active" id="navs-pills-assignManager" role="tabpanel">
                        <div class="col-xxl">
                            <h5 class="card-header">Assign Manager on Duty</h5><br>

                            <div class="card-body">

                              <form class="needs-validation" id="assignManager" action="<?php echo base_url(); ?>user/assignuser/assignmanager" method="post" id="AssignManagerForm">
                                <input type="hidden" name="userid" value="<?php echo $details->UserId ?>">
                                <input type="hidden" name="storeid" value="<?php echo $details->StoreId ?>">

                                  <?php if(isset($checking)){ ?>
                                    <!-- active status MOD -->
                                    <input type="hidden" name="statusMOD" value="<?php echo $details->ManagerDutyId; ?>">
                                    <div class="">
                                      <h6 class="alert alert-success" style="width:40%" id="success">This User is current Active status Manager on Duty</h6>
                                    </div>

                                    <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="staff-name">Staff Name</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="staff-name" name="staffname" value="<?php echo $details->Fullname ?>" disabled>
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="staff-id">Staff ID</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="staff-id" name="staffid" value="<?php echo $details->StaffId ?>" readonly>
                                    </div>
                                  </div>

                                  <?php if (isset($currentrole)){ ?>
                                    <div class="row mb-3">
                                     <label class="col-sm-2 col-form-label" for="currentrole">Current Role</label>
                                     <div class="col-sm-10">
                                       <input type="text" class="form-control" id="currentrole" name="currentrole" value="<?php echo $currentrole->Role ?>"  readonly>
                                     </div>
                                   </div>
                                 <?php } else { ?>
                                   <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="currentrole">Current Role</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="currentrole" name="currentrole" value="<?php echo $details->Role ?>"  readonly>
                                    </div>
                                  </div>
                                <?php } ?>

                                  <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" for="store-status">Temporary Role</label>
                                  <div class="col-sm-10">
                                    <input type="text" class="form-control" id="temp-role" name="temprole" value=" Store Manager" readonly>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="startdate">Start Date</label>
                                <div class="col-sm-10">
                                  <input id="datepicker1" name="start_date" class="form-control txtFromDate" min="<?php echo date('Y-d-m',strtotime('+1 day')); ?>" value="<?php echo $details->StartDate ?>">
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="enddate">End Date</label>
                                <div class="col-sm-10">
                                  <input id="datepicker2" name="end_date" value="<?php echo $details->EndDate ?>"class="form-control txtToDate">
                                </div>
                              </div>
                            <?php } else{?>
                                    <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="staff-name">Staff Name</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="staff-name" name="staffname" value="<?php echo $details->Fullname ?>" disabled>
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="staff-id">Staff ID</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="staff-id" name="staffid" value="<?php echo $details->StaffId ?>" readonly>
                                    </div>
                                  </div>

                                  <?php if (isset($currentrole)){ ?>
                                    <!-- before submit form -->
                                    <div class="row mb-3">
                                     <label class="col-sm-2 col-form-label" for="currentrole">Current Role</label>
                                     <div class="col-sm-10">
                                       <input type="hidden" name="currentid" value="<?php echo $details->UserRoleId ?>">
                                       <input type="text" class="form-control" id="currentrole" name="currentrole" value="<?php echo $currentrole->Role ?>"  readonly>
                                     </div>
                                   </div>
                                 <?php }else { ?>
                                   <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="currentrole">Current Role</label>
                                    <div class="col-sm-10">
                                      <input type="hidden" name="currentid" value="<?php echo $details->UserRoleId ?>">
                                      <input type="text" class="form-control" id="currentrole" name="currentrole" value="<?php echo $details->Role ?>"  readonly>
                                    </div>
                                  </div>
                                <?php } ?>

                                  <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="store-status">Temporary Role</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="temp-role" name="temprole" value=" Store Manager" readonly>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" for="startdate">Start Date</label>
                                  <div class="col-sm-10">
                                    <input id="datepicker1" name="start_date" class="form-control txtFromDate" min="<?php echo date('Y-d-m',strtotime('+1 day')); ?>">
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" for="enddate">End Date</label>
                                  <div class="col-sm-10">
                                    <input id="datepicker2" name="end_date" class="form-control txtToDate">
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" for="managerstatus">Status</label>
                                  <div class="col-sm-10">
                                    <select class="form-select" id="managerStatus" name="managerstatus">
                                      <option value="">Select Status</option>
                                      <!-- <?php foreach ($managerstatus as $row) { ?>
                                        <option value="<?php echo $row->StatusId; ?>"><?php echo $row->StatusName; ?></option>
                                      <?php } ?> -->
                                      <option value="4">Active</option>
                                      <option value="5">Inactive</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" for="basic-default-remark">Remarks</label>
                                  <div class="col-sm-10">
                                    <textarea id="basic-default-remark" class="form-control" name="remark" ></textarea>
                                  </div>
                                </div>

                                <?php
                                $permission = $this->RolePermission_Model->submenu_master(19);

                                if ($permission->Create == 1 ) { ?>
                                <div class="row justify-content-end">
                                  <div class="col-sm-10">
                                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    <button type="button"  id="submitbtn" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                    <button type="button"  id="backpage" class="btn btn-primary ms" style="background-color:#122620; border-color:#122620">Back to Main Page</button>
                                  </div>
                                </div>
                                <?php }  ?>
                              <?php } ?>
                              </form>
                            </div>
                        </div>
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

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script type="text/javascript">
      $(document).ready(function()  {

          $("#assignManager").unbind('submit').bind('submit', function() { //input
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

        //End date jquery
        $(".txtFromDate").datepicker({
        numberOfMonths: 1,
        dateFormat: 'yy-mm-dd',
        minDate: new Date(),
            onSelect: function(selected) {
              $(".txtToDate").datepicker("option","minDate", selected)
            }
        });
        $(".txtToDate").datepicker({
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',
            onSelect: function(selected) {
               $(".txtFromDate").datepicker("option","maxDate", selected)
            }
        });

        $('#submitbtn').one('click', function() {
          $('#assignManager').submit();
          $('#backpage').show(400);
        })

        $('#backpage').hide();
        $('#backpage').one('click', function() {
          window.location = '<?php echo base_url() ?>user/User/UserList';
        })
    });
    </script>
  </body>
</html>
