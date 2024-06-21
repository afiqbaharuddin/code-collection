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

    <title>User Role</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">USER ROLE</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Users</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">User Role</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header">

                  <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                    <?php
                    $role     = $this->RolePermission_Model->submenu_master(4);

                    if ($role->Create == 1) { ?>
                    <form class="" id="createRoleForm" action="<?php echo base_url(); ?>user/UserRole/createRole" method="post">

                      <div class="col-md-4 user_role" style="margin-bottom:20px">
                        <input type="text" id="UserRole" name="rolename" class="form-control" data-allow-clear="true" placeholder="Enter Role">
                      </div>

                      <div class="col-md-4 user_status" style="margin-bottom:20px">
                        <select id="UserStatus" class="form-select text-capitalize" name="status" data-allow-clear="true">
                          <option value="">Select Status</option>
                          <?php foreach ($userrolestatus as $row) { ?>
                            <option value="<?php echo $row->StatusId; ?>"><?php echo $row->StatusName; ?></option>
                          <?php  }?>
                        </select>
                      </div>

                      <div class="col-md-4 create_role" style="margin-bottom:20px">
                        <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                          <button class="dt-button add-new btn btn-success" tabindex="0" aria-controls="DataTables_Table_0" type="submit" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
                          <span><i class="bx bx-plus me-0 me-lg-2"></i>
                            <span class="d-none d-lg-inline-block">Create Role</span>
                          </span>
                        </button>
                      </div>
                    <?php }  ?>
                    </form>

              <!-- User Role Datatable -->
                <!-- <h5 class="card-header">User Permission</h5> -->
                <div class="card-datatable text-nowrap">
                  <table id="userroletable" class="table">
                    <thead style="background-color:#122620">
                      <tr>
                        <th style="color:white">No</th>
                        <th style="color:white">Role Name</th>
                        <th style="color:white">Created Date</th>
                        <th style="color:white">Status</th>

                        <?php
                        $action     = $this->RolePermission_Model->submenu_master(4);

                        if ($action->Update == 1) { ?>
                        <th style="color:white">Action</th>
                        <?php }  ?>
                      </tr>
                    </thead>
                    <input type="hidden" name="loggerid" id="loggerid" value="">
                  </table>
                </div>
                </div>
              </div>
            </div>
              <!--/ Basic Bootstrap Table -->
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

    <script type="text/javascript">

      $(document).ready(function(){
        $.fn.dataTable.ext.errMode = 'none';

        //user role datatable
        var userroletable = $('#userroletable').DataTable({
          columnDefs: [
              {targets: [-1], orderable: false}],
          "searching": true,
          "processing": true,
          "serverSide": true,
          "scrollX": true,
          "ajax": {
              "url": "<?php echo base_url(); ?>user/userrole/listing",
              "type": "POST",
              "data": function(data) {
                var csrfHash  = $('.txt_csrfname').val();
                data.<?= $csrf['name']; ?> = csrfHash;
              }
          },
          "bDestroy": true,
        });
        userroletable.on('xhr.dt', function ( e, settings, json, xhr ) {
          if (json != null) {
            $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
          }else {
            getToken();
          }
        });

        //  Add user role form
        $("#createRoleForm").unbind('submit').bind('submit', function() { //input
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
              userroletable.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
              snack(data.message,  'success');
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

      //jquery for toggle switch at status
      $('#userroletable').on('change', '.toggle', function()  {
        var num = $(this).data('num');
        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();
        // alert("say");

        if($(this).is(":checked")){
          $('#toggle_'+num).val('1');
          $('#showstatusname_'+num).html('<span class="badge bg-label-success">Active</span>');
        } else{
          $('#toggle_'+num).val('2');
          $('#showstatusname_'+num).html('<span class="badge bg-label-danger">Inactive</span>');
        }

        var status     = $(this).val();
        var userroleId = $('#rolestatus_'+num).val();
        var loggerid   = $('#loggerid').val();
        // alert(status);

        $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>user/userrole/get_rolestatus",
          // data: {status: status, userroleId:userroleId, [csrfName]: csrfHash},
          data: {status: status, userroleId:userroleId, loggerid:loggerid, <?= $csrf['name']; ?> : csrfHash},
          dataType: 'json',
          success: function(data)
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
