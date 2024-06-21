<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <?php echo $header; ?>
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns <?php echo get_cookie('mode'); ?> navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php echo $topbar; ?>

    <?php echo $sidebar; ?>

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0"><?php echo strtoupper($title); ?></h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url()."dashboard"; ?>"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item"><?php echo $sub; ?>
                                    </li>
                                    <li class="breadcrumb-item active"><?php echo $title; ?>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <!-- table Transactions start -->
                <section id="table-transactions">
                    <div class="card">
                        <div class="card-header">
                          <div class="row">
                            <div class="col-md-6">
                                <h6>User Role</h6>
                                <div class="form-group">
                                    <select class="select2 form-control" id="UserRole">
                                      <option value="">Select Role</option>
                                      <?php foreach ($role as $row) { ?>
                                        <option value="<?php echo $row->AdminRoleId; ?>"><?php echo $row->AdminRoleName; ?></option>
                                      <?php } ?>
                                    </select>
                                </div>
                            </div>
                          </div>
                        </div>
                        <!-- datatable start -->
                        <div class="table-responsive">
                            <table id="table-extended-transactions" class="table mb-0">
                                <thead class="bg-primary">
                                    <tr class="text-center">
                                        <th class="text-left">Module</th>
                                        <th>Read</th>
                                        <th>Write</th>
                                        <th>Create</th>
                                        <th>Delete</th>
                                        <th>More</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </section>
                <!-- table Transactions end -->
            </div>
        </div>

        <!--BorderLess Modal Modal -->
        <div class="modal fade text-left modal-borderless" id="morepermission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Manage Admin</h3>
                        <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                      <div class="table-responsive">
                          <table id="table-extended-transactions" class="table mb-0">
                              <thead class="bg-primary">
                                  <tr class="text-center">
                                      <th class="text-left">Module</th>
                                      <th>Read</th>
                                      <th>Write</th>
                                      <th>Create</th>
                                      <th>Delete</th>
                                  </tr>
                              </thead>
                              <tbody id="tbody-more">

                              </tbody>
                          </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- END: Content-->

    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <!-- END: Footer-->
    <?php echo $footer; ?>

    <script type="text/javascript">

      $(document).ready(function() {

        authorization();

        $('#UserRole').change(function(){
          authorization();
        });

        function authorization() {

          var csrfName    = $('.txt_csrfname').attr('name');
          var csrfHash    = $('.txt_csrfname').val();
          var UserRoleId  = $("#UserRole").val();

          $.ajax({
              type : "POST",
              url:"<?php echo base_url(); ?>settings/permission/authorization",
              dataType : "JSON",
              data:{UserRoleId:UserRoleId, [csrfName]: csrfHash},
              success:function(data)
              {
               $('.txt_csrfname').val(data.token);
               $('#tbody').html(data.result);
              }
           });
        }

        $('#tbody').on('change','.check',function(){

          var UserRoleId  = $("#UserRole").val();

          if ($(this).is(':checked')) {
            var MasterId              = $(this).data('id');
            var ApppLoggerId          = $(this).data('logger');
            var PermissionId          = $(this).data('permission');
            var Type                  = $(this).val();
            var Check                 = 1;
          }else{
            var MasterId              = $(this).data('id');
            var ApppLoggerId          = $(this).data('logger');
            var PermissionId          = $(this).data('permission');
            var Type                  = $(this).val();
            var Check                 = 0;
          }

          if (UserRoleId == 0) {
            toastr.error("Please select user role first", 'Error!', {positionClass: 'toast-top-center', containerId: 'toast-top-center'});
          }else{

            var csrfName    = $('.txt_csrfname').attr('name');
            var csrfHash    = $('.txt_csrfname').val();

            $.ajax({
                type : "POST",
                url:"<?php echo base_url(); ?>settings/permission/save_authorization",
                dataType : "JSON",
                data:{UserRoleId:UserRoleId, MasterId:MasterId, Type:Type, Check:Check, PermissionId:PermissionId, ApppLoggerId:ApppLoggerId, [csrfName]: csrfHash},
                success:function(data)
                {
                 $('.txt_csrfname').val(data.token);
                 authorization();
                 toastr.success(data.result, {positionClass: 'toast-top-right', containerId: 'toast-top-right'});
                }
            });

          }

        });

        $('#tbody').on('click','.more',function(){

          var UserRoleId   = $("#UserRole").val();
          var MenuMasterId = $(this).attr('id');

          if (UserRoleId == 0) {
            toastr.error("Please select user role first", 'Error!', {positionClass: 'toast-top-center', containerId: 'toast-top-center'});
          }else{

            var csrfName    = $('.txt_csrfname').attr('name');
            var csrfHash    = $('.txt_csrfname').val();

            $.ajax({
                type : "POST",
                url:"<?php echo base_url(); ?>settings/permission/get_more",
                dataType : "JSON",
                data:{UserRoleId:UserRoleId, MenuMasterId:MenuMasterId, [csrfName]: csrfHash},
                success:function(data)
                {
                  $('.txt_csrfname').val(data.token);
                  $('#tbody-more').html(data.result);
                  $('#morepermission').modal('show');
                }
            });
          }

        });

        $('#tbody-more').on('change','.check',function(){

          var UserRoleId  = $("#UserRole").val();

          if ($(this).is(':checked')) {
            var MasterId              = $(this).data('id');
            var ApppLoggerId          = $(this).data('logger');
            var PermissionId          = $(this).data('permission');
            var Type                  = $(this).val();
            var Check                 = 1;
          }else{
            var MasterId              = $(this).data('id');
            var ApppLoggerId          = $(this).data('logger');
            var PermissionId          = $(this).data('permission');
            var Type                  = $(this).val();
            var Check                 = 0;
          }

          var csrfName    = $('.txt_csrfname').attr('name');
          var csrfHash    = $('.txt_csrfname').val();

          $.ajax({
              type : "POST",
              url:"<?php echo base_url(); ?>settings/permission/save_authorization_more",
              dataType : "JSON",
              data:{UserRoleId:UserRoleId, MasterId:MasterId, Type:Type, Check:Check, PermissionId:PermissionId, ApppLoggerId:ApppLoggerId, [csrfName]: csrfHash},
              success:function(data)
              {
               $('.txt_csrfname').val(data.token);
               authorization();
               toastr.success(data.result, {positionClass: 'toast-top-right', containerId: 'toast-top-right'});
              }
          });

        });

      });

    </script>

</body>
<!-- END: Body-->

</html>
