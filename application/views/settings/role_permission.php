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

    <title>Role Permission</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">ROLE PERMISSION</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Settings</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Role Permission</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

                <!-- table Role Permission start -->
               <section id="table-rolepermission">
                   <div class="card">
                       <div class="card-header">
                         <div class="row">
                           <div class="col-md-6">
                               <h6>User Role</h6>
                               <div class="form-group">
                                   <select class="select2 form-control" id="UserRole">
                                     <option value="0">Select Role</option>
                                     <?php foreach ($role as $row) { ?>
                                       <option value="<?php echo $row->UserRoleId; ?>"><?php echo $row->Role; ?></option>
                                     <?php } ?>
                                   </select>
                               </div>
                           </div>
                         </div>
                       </div>

                       <!-- datatable start -->
                       <div class="table-responsive">
                           <table id="table-extended-rolepermission" class="table mb-0">
                               <thead style="background-color:#122620">
                                   <tr class="text-center">
                                       <th style="color:white" class="text-left">Module</th>
                                       <th style="color:white">View</th>
                                       <th style="color:white">Update</th>
                                       <th style="color:white">Create</th>
                                       <th style="color:white">Delete</th>
                                       <th style="color:white">More</th>
                                   </tr>
                               </thead>
                               <tbody id="tbody">

                               </tbody>
                           </table>
                       </div>
                       <!-- datatable ends -->
                   </div>
               </section>
               <!-- table Role Permission end -->
                 </div>
             </div>

             <!--BorderLess Modal Modal -->
             <div class="modal fade text-left modal-borderless" id="morepermission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h3 class="modal-title">More Settings</h3>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                           <div class="table-responsive">
                               <table id="table-extended-rolepermission" class="table mb-0">
                                   <thead style="background-color:#122620">
                                       <tr class="text-center">
                                           <th  style="color:white" class="text-left">Module</th>
                                           <th  style="color:white">View</th>
                                           <th  style="color:white">Update</th>
                                           <th  style="color:white">Create</th>
                                           <th  style="color:white">Delete</th>
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

             <!-- <div class="modal fade text-left modal-borderless" id="moreuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h3 class="modal-title">Manage Users</h3>
                             <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                                 <i class="bx bx-x"></i>
                             </button>
                         </div>
                         <div class="modal-body">
                           <div class="table-responsive">
                               <table id="table-extended-rolepermission" class="table mb-0">
                                   <thead style="background-color:#122620">
                                       <tr class="text-center">
                                           <th  style="color:white" class="text-left">Module</th>
                                           <th  style="color:white">View</th>
                                           <th  style="color:white">Update</th>
                                           <th  style="color:white">Create</th>
                                           <th  style="color:white">Delete</th>
                                       </tr>
                                   </thead>
                                   <tbody id="tbody-more">

                                   </tbody>
                               </table>
                           </div>
                         </div>
                     </div>
                 </div>
             </div> -->

         </div>
       </div>
      </div>
              <!-- </div> -->
            <!-- </div> -->
                  <!--/ Basic Bootstrap Table -->
              <!-- </div> -->
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
            url:"<?php echo base_url(); ?>settings/rolepermission/authorization",
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
          alert('Please select user role first');
        }else{

          var csrfName    = $('.txt_csrfname').attr('name');
          var csrfHash    = $('.txt_csrfname').val();

          $.ajax({
              type : "POST",
              url:"<?php echo base_url(); ?>settings/rolepermission/save_authorization",
              dataType : "JSON",
              data:{UserRoleId:UserRoleId, MasterId:MasterId, Type:Type, Check:Check, PermissionId:PermissionId, ApppLoggerId:ApppLoggerId, [csrfName]: csrfHash},
              success:function(data)
              {
               $('.txt_csrfname').val(data.token);
               authorization();
               alert('Update success')
              }
          });

        }

      });

      //more dashboard
      $('#tbody').on('click','.more',function(){

        var UserRoleId   = $("#UserRole").val();
        var MenuMasterId = $(this).attr('id');

        if (UserRoleId == 0) {
          alert('Please select user role first');
        }else{

          var csrfName    = $('.txt_csrfname').attr('name');
          var csrfHash    = $('.txt_csrfname').val();

          $.ajax({
              type : "POST",
              url:"<?php echo base_url(); ?>settings/rolepermission/get_more",
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

      //
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
            url:"<?php echo base_url(); ?>settings/rolepermission/save_authorization_more",
            dataType : "JSON",
            data:{UserRoleId:UserRoleId, MasterId:MasterId, Type:Type, Check:Check, PermissionId:PermissionId, ApppLoggerId:ApppLoggerId, [csrfName]: csrfHash},
            success:function(data)
            {
             $('.txt_csrfname').val(data.token);
             alert('Update success');
            }
        });
      });
    });

    </script>
  </body>
</html>
