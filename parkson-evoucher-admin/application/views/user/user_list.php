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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />

    <title>User List</title>
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
          <!-- /topbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">

              <div class="row col-12">
                <div class="content-header-row">
                  <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                      <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">LIST OF USERS</h5>
                        <div class="breadcrumb-wrapper col-12">
                          <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                            <li class="breadcrumb-item">
                              <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                                <i class="bx bx-home-alt"></i>
                              </a>
                            </li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Users</li>
                          </ol>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php if ($userCount->UserId == 1 || $userCount->UserRoleId == 1){ ?>
                <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between" style="position: relative;">
                        <div class="d-flex align-items-center gap-3">
                          <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-user bx-sm fs-4"></i></span>
                          </div>
                          <div class="card-info">
                            <span class="text">Total Users</span>
                            <h5 class="card-title mb-0 me-2"><?php echo $totalUser ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between" style="position: relative;">
                        <div class="d-flex align-items-center gap-3">
                          <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-user bx-sm fs-4"></i></span>
                          </div>
                          <div class="card-info">
                            <span class="text">Active Users</span>
                            <h5 class="card-title mb-0 me-2"><?php echo $activeUser ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between" style="position: relative;">
                        <div class="d-flex align-items-center gap-3">
                          <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-user bx-sm fs-4"></i></span>
                          </div>
                          <div class="card-info">
                            <span class="text">Inactive Users</span>
                            <h5 class="card-title mb-0 me-2"><?php echo $inactiveUser ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php }else {?>
              <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between" style="position: relative;">
                        <div class="d-flex align-items-center gap-3">
                          <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-user bx-sm fs-4"></i></span>
                          </div>
                          <div class="card-info">
                            <span class="text">Total Users</span>
                            <h5 class="card-title mb-0 me-2"><?php echo $totalUserStore ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between" style="position: relative;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-user bx-sm fs-4"></i></span>
                        </div>
                        <div class="card-info">
                          <span class="text">Active Users</span>
                          <h5 class="card-title mb-0 me-2"><?php echo $activeUserStore ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-4 col-sm-4 mb-4">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between" style="position: relative;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-user bx-sm fs-4"></i></span>
                        </div>
                        <div class="card-info">
                          <span class="text">Inactive Users</span>
                          <h5 class="card-title mb-0 me-2"><?php echo $inactiveUserStore ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>

              <!-- User list Datatable -->
              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <div class="">
                      <!-- <h4>List of User</h4> -->
                    </div>

                    <div class="dt-buttons">
                      <?php
                      $export = $this->RolePermission_Model->submenu_master(2);
                      if ($export->View == 1 ) { ?>
                      <button type="button" class="dt-button buttons-collection btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                        style="margin-left:10px; margin-right:10px">
                        <span>
                          <i class="bx bx-upload me-2">
                          </i>Export
                        </span>
                      </button>

                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="<?php echo base_url('reports/Reports/export_user_report'); ?>">
                            <i class="bx bx-file me-2"></i>
                            Csv
                          </a>
                      </li>
                    </ul>
                      <?php }  ?>

                      <?php
                      $createuser = $this->RolePermission_Model->submenu_master(3);
                      if ($createuser->Create == 1) { ?>
                      <button class="dt-button add-new btn btn-success" tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#AddUser">
                        <span><i class="bx bx-plus me-0 me-lg-2"></i>
                          <span class="d-none d-lg-inline-block">Add New User</span>
                        </span>
                      </button>
                      <?php }  ?>
                     </div>
                 </div>
                </div>

                <div class="card-datatable text-nowrap">
                  <table id="usertable" class="table">
                    <thead style="background-color:#122620">
                      <tr>
                        <th style="color:white">User</th>
                        <th style="color:white">Role</th>
                        <th style="color:white">Store Name</th>
                        <th style="color:white">Created By</th>
                        <th style="color:white">Created Date</th>
                        <th class="col-1"style="color:white">Inactive Date</th>
                        <th style="color:white">Status</th>

                        <?php
                        $permission = $this->RolePermission_Model->submenu_master(3);

                        if ($permission->Update == 1 ) { ?>
                        <th style="color:white">Action</th>
                        <?php }  ?>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <!-- / User list Datatable -->
            </div>

                <!-- Offcanvas to create new user -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="AddUser" aria-labelledby="offcanvasAddUserLabel"style="width: 50%;">
                  <div class="offcanvas-header border-bottom">
                    <h4 id="offcanvasAddUserLabel" class="offcanvas-title" style="padding-left: 40%;">Create User Form</h4>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body mx-0 flex-grow-0">

                      <!--  Create user form -->
                      <div class="col-md">
                        <div class="card">
                          <h5 class="card-header" style="padding-bottom: 5%; padding-left: 40%;">Insert User Details</h5>
                          <div class="card-body">
                            <h6 class="mb-3 fw-normal">1. User Details</h6>
                            <form class="needs-validation" action="<?php echo base_url(); ?>user/User/createUser" method="post" id="AddUserForm">
                              <div class="modal-body">
                                <div class="mb-3">
                                  <label class="form-label" for="bs-validation-id">Staff ID</label>
                                  <span style="color:red">*</span>
                                  <input type="text" class="form-control"  name="staffId" placeholder="P01234" maxlength="6" required/>
                                  <div class="invalid-feedback">*This field is required. Please enter valid Staff ID.</div>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="bs-validation-loginId">Login ID</label>
                                  <span style="color:red">*</span>
                                  <input type="text" class="form-control" name="loginId" placeholder="P01" required/>
                                  <div class="invalid-feedback">*This field is required. Please enter valid Login ID.</div>
                                </div>

                                <div class="mb-3 form-password-toggle">
                                  <label class="form-label" for="alignment-password">Password</label>
                                  <span style="color:red">*</span>
                                  <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                      aria-describedby="alignment-password2" autocomplete="new-password" required/>
                                    <span class="input-group-text cursor-pointer" id="alignment-password2">
                                      <i class="bx bx-hide"></i>
                                    </span>
                                    <div class="col-sm-1">
                                        <strong><label style="margin-top:5px; margin-left:18px">OR</label></strong>
                                    </div>
                                    <button type="button" onclick="disable()" class="btn btn-primary me-sm-2 me-1" id="generate" style="background-color:#122620; border-color:#122620; margin-left:20px">Generate Password</button>
                                    <div class="invalid-feedback"> *This field is required. Please enter or generate your Password.</div>
                                  </div>

                                  <!-- password suggestion -->
                                    <div class="pt-2">
                                      <div class="row mb-3">
                                        <div class="col-md-6" >
                                          <label for="" class="form-label show_suggest">Suggestion:</label>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <a href="#" class="suggest_pass results" id="result1" data-val=""></a>
                                            </div>
                                            <div class="col-md-4">
                                              <a href="#" class="suggest_pass results" id="result2" data-val=""></a>
                                            </div>
                                            <div class="col-md-4">
                                              <a href="#" class="suggest_pass results" id="result3" data-val=""></a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>

                                  <input type="hidden" name="flagConfirm" id="flagConfirm" value="2">
                                  <div class="mb-3 form-password-toggle" id="addConfirm">
                                    <label class="form-label" for="addConfirmPassword" >Confirm Password</label>
                                    <span style="color:red">*</span>
                                    <div class="input-group input-group-merge">
                                      <input type="password" name="confirmPass" class="form-control"
                                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                      aria-describedby="alignment-confirmPassword" required/>
                                      <span class="input-group-text cursor-pointer" id="alignment-confirmPassword">
                                        <i class="bx bx-hide"></i>
                                      </span>
                                    </div>
                                  </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="bs-validation-code">Store Code</label>
                                      <span style="color:red">*</span>
                                      <select class= "form-select" name="storeCode" id="storeCode" required>
                                        <option value="">Select Store Code</option>
                                        <?php foreach ($storecode_create as $row) { ?>
                                          <option id="activestore" value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode; ?></option>
                                        <?php } ?>
                                      </select>
                                      <div class="invalid-feedback">*This field is required. Please select Store Code from the list.</div>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="bs-validation-store">Store Name</label>
                                        <select class="form-control" name="storeName" id="storeName" disabled>
                                          <option value=""></option>
                                          <?php foreach ($storecode_create as $row) { ?>
                                            <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                          <?php } ?>
                                        </select>
                                    </div>

                                    <hr class="my-4 mx-n4" />
                                    <h6 class="mb-3 fw-normal">2. Personal Info</h6>

                                    <div class="mb-3">
                                      <label class="form-label" for="bs-validation-name">Full Name</label>
                                      <span style="color:red">*</span>
                                        <input type="text" id="bs-validation-name" class="form-control" name="fullname" placeholder="John Doe" required/>
                                        <div class="invalid-feedback">*This field is required. Please enter Full Name.</div>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="email">Email</label>
                                      <input type="email" id="email" class="form-control" name="email" placeholder="example@email.com" />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="userRole">User Role</label>
                                      <span style="color:red">*</span>
                                      <select class="form-select" name="userRole" id="userRole" required>
                                        <option value="">Select Role</option>
                                        <?php foreach ($userrole_create as $row) { ?>
                                          <option value="<?php echo $row->UserRoleId; ?>"><?php echo $row->Role; ?></option>
                                        <?php } ?>
                                      </select>
                                      <div class="invalid-feedback">*This field is required. Please select User Role from the list.</div>
                                    </div>

                                    <div class="row">
                                      <label class="form-label" for="phoneNo">Phone No</label>
                                      <div class="input-group">
                                        <span class="input-group-text">MY(+60)</span>
                                        <input type="text" id="phoneNo" maxlength="10" class="form-control phone-number-mask" name="phonenum" placeholder="12 234 5678"/>
                                      </div>
                                    </div>

                                    <div class="pt-4">
                                      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                      <button type="button" id="submitadd" class="btn btn-primary me-sm-2 me-1 data-submit" style="background-color:#122620;
                                      border-color:#122620">Submit</button>
                                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                    </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>

                <!-- Offcanvas to edit user -->
                  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel" style="width: 40%;">
                    <div class="offcanvas-header border-bottom">
                      <h4 id="offcanvasEditUserLabel" class="offcanvas-title" style="padding-left: 40%;">Edit User Form</h4>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0">

                      <!--edit user form-->
                      <div class="col-md">
                        <div class="card">
                          <h5 class="card-header" style="padding-bottom: 5%; padding-left: 40%;">Insert User Details</h5>
                          <div class="card-body">

                            <form class="needs-validation" id="updateuserform" action="<?php echo base_url(); ?>user/User/editUser" method="post" >
                              <input type="hidden" name="role" value="<?php echo $user_role->UserRoleId; ?>">
                              <?php if ($user_role->UserRoleId == 1){ ?>
                                <h6 class="mb-3 fw-normal">1. User Details</h6>

                                <div class="mb-3">
                                  <label class="form-label" for="editId">Staff ID</label>
                                  <input type="text" class="form-control" id="editId"  name="editId" disabled/>
                                  <div class="invalid-feedback">Please enter Staff ID.</div>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="loginId">Login ID</label>
                                  <input type="text" class="form-control" id="loginId" name="loginid" required disabled/>
                                </div>

                                <div class="mb-3 form-password-toggle">
                                  <label class="form-label" for="editPassword">Password</label>
                                  <span style="color:red">*</span>
                                    <div class="input-group input-group-merge">
                                      <input type="password" name="password" value="" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        id="resetpass" class="form-control" aria-describedby="alignment-password2" required/>
                                      <span class="input-group-text cursor-pointer" id="alignment-password2">
                                        <i class="bx bx-hide"></i>
                                      </span>
                                      <div class="invalid-feedback">This field is required. Please enter valid Password</div>
                                    </div>

                                    <div class="form-check" style="margin-top:5px">
                                      <input id="reset_password" class="form-check-input" type="checkbox">
                                      <label class="form-check-label" for="reset_password">Reset Password</label>
                                    </div>

                                  <div class="pt-2">
                                    <div class="row mb-3">
                                      <div class="col-md-6">
                                        <label for="" class="form-label show_suggestEdit">Suggestion:</label>
                                        <div class="row" id="passSuggest">
                                          <div class="col-md-4">
                                            <a href="#" class="suggest_passEdit resultreset" id="resultreset1"></a>
                                          </div>
                                          <div class="col-md-4">
                                            <a href="#" class="suggest_passEdit resultreset" id="resultreset2"></a>
                                          </div>
                                          <div class="col-md-4">
                                            <a href="#" class="suggest_passEdit resultreset" id="resultreset3"></a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="mb-3 form-password-toggle confirmfield">
                                    <label class="form-label" for="alignment-confirmPassword" >Confirm Password</label>
                                    <span style="color:red">*</span>
                                      <div class="input-group input-group-merge">
                                        <input type="password" name="confirmpass" id="confirmpass" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="alignment-confirmPassword"/>
                                        <span class="input-group-text cursor-pointer" id="alignment-confirmPassword">
                                          <i class="bx bx-hide"></i>
                                        </span>
                                      </div>
                                  </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="editStoreCode">Store Code</label>
                                    <span style="color:red">*</span>
                                      <select class="form-control" name="editStoreCode" value="" id="editStoreCode">
                                        <option value="">Select Store Code</option>
                                        <?php foreach ($storecode_edit as $row) { ?>
                                          <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode; ?></option>
                                        <?php } ?>
                                      </select>
                                  <div class="invalid-feedback">Please select store code</div>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="editStoreName">Store Name</label>
                                    <select class="form-control" name="editStoreName" id="editStoreName" disabled>
                                      <?php foreach ($storecode_edit as $row) { ?>
                                        <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreName; ?></option>
                                      <?php } ?>
                                    </select>
                                </div>

                                  <hr class="my-4 mx-n4" />
                                  <h6 class="mb-3 fw-normal">2. Personal Info</h6>

                                  <div class="mb-3">
                                      <label class="form-label" for="editName">Full Name</label>
                                      <span style="color:red">*</span>
                                        <input type="text" id="editName" class="form-control" name="fullname" value="" />
                                      <div class="invalid-feedback">Please enter Full Name</div>
                                  </div>

                                  <div class="mb-3">
                                    <label class="form-label" for="editEmail">Email</label>
                                      <input type="email" name="email" id="editEmail" value="" class="form-control"/>
                                  </div>

                                  <div class="mb-3">
                                    <label class="form-label" for="editRole">User Role</label>
                                    <span style="color:red">*</span>
                                    <select class="form-control" name="editRole" id="editRole" required>
                                      <!-- <option value="">Select Role</option> -->
                                      <?php foreach ($userrole_edit as $row) { ?>
                                        <option value="<?php echo $row->UserRoleId; ?>"><?php echo $row->Role; ?></option>
                                      <?php  }?>
                                    </select>
                                    <div class="invalid-feedback">Please select role</div>
                                  </div>

                                  <div class="row">
                                    <label class="form-label" for="editPhoneNo">Phone No</label>
                                      <div class="input-group">
                                        <span class="input-group-text">MY(+60)</span>
                                        <input type="text" maxlength="10"  id="editPhoneNo" name="phoneNo" class="form-control phone-number-mask" value=""/>
                                      </div>
                                  </div>

                                  <?php if ($unlockmanuallyadmin->UnlockManuallyCheck== 'Y'): ?>
                                    <div class="mb-3 pt-3">
                                      <label class="form-label" for="status">Status</label>
                                      <span style="color:red">*</span>
                                        <select id="status" class="select3 form-select" name="status">
                                          <!-- <option value="">Select Status</option> -->
                                          <?php foreach ($userstatus_edit as $row) { ?>
                                            <option value="<?php echo $row->StatusId; ?>"><?php echo $row->StatusName; ?></option>
                                          <?php } ?>
                                        </select>
                                        <div class="invalid-feedback">*This field is required. Please select User Status.</div>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" id="inactive-label" for="inactive-date">Inactive Date On</label>
                                      <input class="form-control inactiveDate" id="inactiveDate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" type="date" name="inactiveDate" value="" required/>
                                      <div class="invalid-feedback">*This field is required. Please select Inactive Date.</div>
                                    </div>
                                  <?php endif; ?>

                              <?php }else { ?>
                                <h6 class="mb-3 fw-normal">Personal Info</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="editName">Full Name</label>
                                    <span style="color:red">*</span>
                                      <input type="text" id="editName" class="form-control" name="fullname" value="" />
                                    <div class="invalid-feedback">Please enter Full Name</div>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="editEmail">Email</label>
                                    <input type="email" name="email" id="editEmail" value="" class="form-control"/>
                                </div>

                                <div class="row">
                                  <label class="form-label" for="editPhoneNo">Phone No</label>
                                    <div class="input-group">
                                      <span class="input-group-text">MY(+60)</span>
                                      <input type="text" maxlength="10"  id="editPhoneNo" name="phoneNo" class="form-control phone-number-mask" value=""/>
                                    </div>
                                </div>
                              <?php } ?>

                                <div class="pt-4">
                                  <input type="hidden" name="loggerid" id="loggerid" value="">
                                  <input type="hidden" name="userid" id="userid" value="">

                                  <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                  <button type="button" id="submitbtn" class="btn btn-primary me-sm-2 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                                  <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>


                                  <div style="float: right">
                                    <?php
                                    $permission = $this->RolePermission_Model->submenu_master(19);
                                    if ($permission->View == 1 ) { ?>
                                        <button  type="button" id="mod" class="btn btn-primary me-sm-2 me-1">Assign Manager on Duty</button>
                                    <?php }  ?>

                                    <!-- reset password button -->
                                      <?php if ($user_role->UserRoleId == 1): ?>
                                          <button type="button" name="button"  tabindex="0" class="dt-button add-new btn btn-secondary resetPass me-sm-2 me-1" data-bs-toggle="modal" data-bs-target="#resetPass">Reset Default Password</button>
                                      <?php endif; ?>
                                  </div>
                                </div>

                                <!-- <div class="pt-2"style="float: left;">
                                  <?php
                                  $permission = $this->RolePermission_Model->submenu_master(19);
                                  if ($permission->View == 1 ) { ?>
                                  <div id="mod">
                                    <a>
                                      <button  type="button" class="btn btn-primary">Assign Manager on Duty</button>
                                    </a>
                                  </div>
                                  <?php }  ?> -->

                                  <!-- reset password button -->
                                  <!-- <div class="pt-2">
                                    <?php if ($user_role->UserRoleId == 1): ?>
                                      <div class="dt-action-buttons" >
                                        <button type="button" name="button"  tabindex="0" class="dt-button add-new btn btn-secondary resetPass" data-bs-toggle="modal" data-bs-target="#resetPass">
                                          <span class="d-none d-lg-inline-block">Reset Default Password</span>
                                        </button>
                                      </div>
                                    <?php endif; ?>
                                  </div>
                                </div> -->
                              <!--edit user form-->
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                <!-- Offcanvas to edit user -->

                <!-- Modal Reset Old Password -->
                <div class="modal fade" id="resetPass" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Reset Default / Old Password</h5>
                        <button type="button" id="resetPassButton" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form  id="resetPassForm" action="<?php echo base_url(); ?>user/User/updateDefaultPass" method="post" >
                        <div class="modal-body">
                          <div class="mb-3 form-password-toggle">
                            <label class="form-label">Old Password</label>
                              <div class="input-group input-group-merge">
                                <input type="password" name="defaultpass" id="defaultpass" class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                <span class="input-group-text cursor-pointer" id="alignment-defaultpass">
                                  <i class="bx bx-hide"></i>
                                </span>
                              </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <input type="hidden" name="userid" id="userid1">

                          <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                          <button type="submit" class="btn btn-primary data-submit" style="background-color:#122620; border-color:#122620">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- / Modal Reset Old Password -->
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

    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <!-- / Layout wrapper -->

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

    //user datatable
    var usertable = $('#usertable').DataTable({
      columnDefs: [
          {targets: [-1], orderable: false},
        ],
      "searching": true,
      "processing": true,
      "serverSide": true,
      "scrollX": true,
      "ajax": {
          "url": "<?php echo base_url(); ?>user/user/listing",
          "type": "POST",
          "data": function(data) {
            var csrfHash  = $('.txt_csrfname').val();
            data.<?= $csrf['name']; ?> = csrfHash;
          }
      },
      "bDestroy": true,
    });
    usertable.on('xhr.dt', function ( e, settings, json, xhr ) {
      if (json != null) {
        $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
      }else {
        getToken();
      }
    });

    //create user form
    $("#AddUserForm").unbind('submit').bind('submit', function()
    { //input
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
            $('#AddUser').offcanvas('hide');
            $("#AddUserForm")[0].reset();
            usertable.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
            snack(data.message, 'success');
          }
          else
          {
            $('#AddUser').offcanvas('show');
            snack(data.message,'danger');
          }
        },
        error: function(xhr, status, error) {
          snack('Something went wrong. Please try again later','danger');
        },
      });
      return false;
    });

  $('#submitadd').on('click', function(){
    $("#AddUserForm").submit();
  });

  $('#mod').click(function() {
    var userid = $('#userid').val();
    window.location.href = "<?php echo base_url('user/AssignUser/assign/'); ?>"+ userid;
  });


/////////////////////////////////////////////////////////////////////////////
  //keluarkan edit input fields
  $('#usertable').on('click','.viewuser', function() {
    var userid = $(this).data('userid');
    $('#userid').val(userid);

    var csrfName = $('.txt_csrfname').attr('name');
    var csrfHash = $('.txt_csrfname').val();

    $.ajax({
            type: "post",
            url: "<?php echo base_url('user/User/userdetails'); ?>",
            data: {staff: userid, [csrfName]: csrfHash},
            dataType: "JSON",
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);

              $('#loggerid').val(data.user.AppLoggerId);
              $('#editId').val(data.user.StaffId);
              $('#loginId').val(data.user.LoginId);
              $('#editStoreCode').val(data.user.StoreId).change();
              $('#editStoreName').val(data.user.StoreId).change();
              $('#editName').val(data.user.Fullname);
              $('#editEmail').val(data.user.Email);
              $('#editRole').val(data.user.UserRoleId);

              if (data.user.UserRoleId ==104) {
                $('#mod').hide();
              }else {
                $('#mod').show();
              }

              $('#editPhoneNo').val(data.user.PhoneNo);
              $('#status').val(data.user.userStatus).change();
              $('#inactiveDate').val(data.user.InactiveDate);

            }
          });
        })

      $('#submitbtn').on('click', function(){
        $("#updateuserform").submit();
      });

      //edit user Details
      $("#updateuserform").unbind('submit').bind('submit', function() {
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
            $('#offcanvasEditUser').modal('hide');
            usertable.ajax.reload();
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

    //reset old password
    $('.resetPass').on('click',function() {
      var userid   = $('#userid').val();
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: "<?php echo base_url(); ?>user/User/resetPassDetails",
        type: "POST",
        data: {userid:userid, <?= $csrf['name']; ?> : csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
          $('#userid1').val(data.userid);
          $('#defaultpass').val(data.decrypt);
        },
        error: function(xhr, status, error) {
          snack('Something went wrong. Please try again later','danger');
        },
      });
    });

    //Update default password forms
    $("#resetPassForm").unbind('submit').bind('submit', function() {
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
            $('#resetPass').modal('hide');
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

    //jquery for add edit user form
    $("#bs-validation-code").change(function(){
      var value = $(this).val();
      $('#bs-validation-store').val(value);
    });

    $("#storeCode").change(function(){
      var value = $(this).val();
      $('#storeName').val(value);
    });

    $("#editStoreCode").change(function(){
      var value = $(this).val();
      // console.log(value);
      $('#editStoreName').val(value);
    });

    //hide inactive date for user first
    $('#inactive-label').hide(400);
    $('#inactiveDate').hide(400);

    $("#status").on('change', function(){
      var value = $(this).val();
      $('#status').val(value);

      if(this.value== 2)
      {
        $('#inactive-label').show(400);
        $('#inactiveDate').show(400);
      }else
      {
        $('#inactive-label').hide(400);
        $('#inactiveDate').hide(400);
      }
    });

    $("#status").on('change', function(){
      var value = $(this).val();
      $('#status').val(value);

      if(this.value== 104)
      {
        $('#mod').hide(400);
      }else
      {
        $('#mod').show(400);
      }
    });

    //only phone number on form user
    $("#editPhoneNo").on('input', function(e){
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $("#phoneNo").on('input', function(e){
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    //hide button MOD on edit modal

    $("#editRole").on('change', function(){
      var value = $(this).val();
      $('#editRole').val(value);
        if(this.value == 104)
        {
          $('#mod').hide();
        }else {
          $('#mod').show();

        }
    });

//generate password jquery

    const lengthstatus    = '<?php echo $password->MinPassCheck ?>';
    const numberstatus    = '<?php echo $password->NumbersCheck ?>';
    const uppercasestatus = '<?php echo $password->UppercaseCheck ?>';
    const lowercasestatus = '<?php echo $password->LowercaseCheck ?>';

    const length          = '<?php echo $password->MinPassValue ?>';
    const number          = '<?php echo $password->NumbersValue ?>';
    const uppercase       = '<?php echo $password->UppercaseValue ?>';
    const lowercase       = '<?php echo $password->LowercaseValue ?>';

    // var generateReset;
    $('#passSuggest').on('click', function() {
      // $(".confirmfield").prop("disabled", true);
      if (generateReset =='1') {
        // $('.confirmfield').hide();
        // $('#addConfirm').disabled();
      // $("#addConfirm").prop("disabled", true);
      }
    });

    $('#generate').on('click', function() {
      $('#result1').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#result2').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#result3').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
    });

    $('#reset_password').on('click', function() {
      // alert('haha');
      $('#resultreset1').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#resultreset2').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#resultreset3').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
    });

    function generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase) {
      let generatedPassword = '';
      // let variationsCount = [number, uppercase, lowercase].length
      if (lengthstatus == 0 ) {
        length = 8;
      }
      for(let i = 0; i < length; ) {
        let cont = 11; //if 11 get random lowercase
        if (numberstatus == 1) {
          if (number > 0) {
            generatedPassword += getRandomNumber(number)
            --number;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }

        }
        if (uppercasestatus == 1) {
          if (uppercase > 0) {
            generatedPassword += getRandomUpper(uppercase)
            --uppercase;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }

        }
        if (lowercasestatus == 1) {
          if (lowercase > 0) {
            generatedPassword += getRandomLower(lowercase)
            --lowercase;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }
        }
        if (cont == 11) {
          generatedPassword += getRandomLowerAll()
          ++i;
        }

      }

      const finalPassword = generatedPassword.slice(0, length);

      return finalPassword;
    }

    function getRandomLowerAll() {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
    }

    function getRandomLower(lowercase) {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
    }

    function getRandomUpper(uppercase) {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 65);
    }

    function getRandomNumber(number) {
      return String.fromCharCode(Math.floor(Math.random() * 10) + 48);
    }

    function getRandomSymbol() {
      const symbols = '!@#$%^&*(){}[]=<>/,.'
      return symbols[Math.floor(Math.random() * symbols.length)];
    }

    $('.results').click(function() {
      $('#password').val($(this).html());
      $('.results').hide();
      $('#addConfirm').hide();
      $('#flagConfirm').val(1);

    });

       $("#password").on('keyup',function(){
         $('#flagConfirm').val(2);
         $('#addConfirm').show();

       });

    $('.resultreset').click(function() {
      $('#resetpass').val($(this).html());
      $('.resultreset').hide();
    });
    //===========================================

    $('.show_suggest').hide();
    $('#generate').click(function() {
      $('.show_suggest').show();
      $('.results').show();
    });

    $('.suggest_pass').click(function() {
      var value = $(this).data('val');
      $('.results').val(value);
      $('.show_suggest').hide();
    })

    $('.show_suggestEdit').hide();
    $('#reset_password').change(function() {
      if($(this).is(":checked")) {
        $('.show_suggestEdit').show();
        $('.resultreset').show();
      } else {
        $('.show_suggestEdit').hide();
        $('.resultreset').hide();
      }
    });

    $('.suggest_passEdit').click(function() {
      var value = $(this).data('val');
      $('.resultreset').val(value);
      $('.show_suggestEdit').hide();
    });
  });
      </script>
    </body>
  </html>
