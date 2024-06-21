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

    <title>Store List</title>
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
                      <h4 class="content-header-title float-left pr-1 mb-0">STORE</h4>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Store</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Stores</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                          <span>Open Stores</span>
                          <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2"><?php echo $openStore; ?></h4>
                            <!-- <small class="text-success">(+29%)</small> -->
                          </div>
                          <small>Total Stores</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                          <i class="bx bx-store bx-sm"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                          <span>Closed Stores</span>
                          <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2"><?php echo $closeStore; ?></h4>
                            <!-- <small class="text-success">(+18%)</small> -->
                          </div>
                          <small>Total Stores</small>
                        </div>
                        <span class="badge bg-label-danger rounded p-2">
                          <i class="bx bx bx-store bx-sm"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- List of store Table -->
              <!-- <h4>List of Stores</h4> -->
              <div class="card">
                <div class="card-header">

                  <div class="d-flex justify-content-between">
                    <div class="card-title">
                      <h4>List of Stores</h4>
                    </div>

                    <?php
                    $store     = $this->RolePermission_Model->menu_master(3);

                    if ($store->Create == 1) { ?>
                    <div class="add-new-store">
                      <button type="button" name="button"  tabindex="0" class="dt-button add-new btn btn-success" data-bs-toggle="offcanvas" data-bs-target="#AddStore" style="margin-left:15px">
                        <span><i class="bx bx-plus me-0 me-lg-2"></i>
                        <span class="d-none d-lg-inline-block">Add New Store</span>
                      </span>
                      </button>
                    </div>
                    <?php }  ?>
                  </div>

                  <div class="card-datatable table-responsive">
                    <table id="storetable" class="datatables-users table border-top">
                      <thead style="background-color:#122620">
                        <tr>
                          <th style="color:white">Store Code</th>
                          <th style="color:white">Name</th>
                          <th style="color:white">Created By</th>
                          <th style="color:white">Total Active User</th>
                          <th style="color:white">Status</th>

                          <?php
                          $permission = $this->RolePermission_Model->menu_master(3);

                          if ($permission->Update == 1 ) { ?>
                          <th style="color:white">Action</th>
                          <?php }  ?>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>

                <!--add new store-->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="AddStore" aria-labelledby="offcanvasAddStoreLabel"
                      style="width: 50%;">
                  <div class="offcanvas-header border-bottom">
                    <h5 id="offcanvasAddStoreLabel" class="offcanvas-title" style="padding-left: 40%;">Add New Store Form</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body mx-0 flex-grow-0">

                      <!--create store form-->
                      <div class="col-md">
                        <div class="card">
                          <h5 class="card-header" style="padding-bottom: 5%; padding-left: 40%;">Insert Store Details</h5>
                          <div class="card-body">
                            <form class="" id="AddStoreForm" method="post" action="<?php echo base_url(); ?>store/Store/create_store">
                              <div class="mb-3">
                                <label class="form-label" for="bs-validation-code">Store Code</label>
                                  <span style="color:red">*</span>
                                <input type="text" name="storecode" class="form-control" id="bs-validation-code" placeholder="Eg: 020"/>
                                <div class="invalid-feedback">*This field is required. Please enter valid Store Code.</div>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="bs-validation-name">Store Name</label>
                                  <span style="color:red">*</span>
                                <input type="text" name="Storename" class="form-control" id="bs-validation-name" placeholder="Eg: Parkson Melawati Mall"/>
                                <div class="invalid-feedback">*This field is required. Please enter Store Name.</div>
                              </div>

                              <input type="hidden" name="totalpos" id="totalpos" value="1">
                              <div id="show_pos">
                                <div id="posNumber_1">
                                  <div class="row mb-3">
                                    <div class="col-md-6">
                                      <label for="bs-validation-pos"class="form-label mb-2">POS Number</label>
                                      <input class="form-control" id="bs-validation-pos" type="text" name="posno_1" placeholder="Eg: 102" />
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label mb-2">Action</label>
                                        <div class="d-buttons" id="addPos_1">
                                          <button class="btn btn-primary me-sm-3 me-1 addPos" type="button" style="background-color:#122620; border-color:#122620">
                                            <span>Add Line</span>
                                          </button>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                                <div class="mb-3">
                                  <label for="select2Multiple" class="form-label bs-validation-card">Card</label>
                                  <!-- <span style="color:red">*</span> -->
                                  <select id="bs-validation-card" name="card[]" class="select2 form-select" multiple >
                                    <?php foreach ($carddb as $row){ ?>
                                      <option value="<?php echo $row->CardId; ?>"><?php echo $row->CardName; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>

                              <div class="mb-3">
                                <label class="form-label" for="bs-validation-status">Status</label>
                                <span style="color:red">*</span>
                                  <select id="bs-validation-status" name="status" class="form-select"  required>
                                    <?php foreach ($storestatusdb as $row){ ?>
                                      <option value ="<?php echo $row->StoreStatusId; ?>"><?php echo $row->StoreStatusName; ?></option>
                                    <?php } ?>
                                  </select>
                                  <div class="invalid-feedback">*This field is required. Please select Status. </div>
                              </div>

                                <div class="pt-4">
                                  <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                      <button type="submit" class="btn btn-primary me-sm-2 me-1" style="background-color:#122620; border-color:#122620" >Submit</button>
                                      <button type="reset" id="resetform" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- / add new store -->

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



  <!-- Form validation script-->
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>
  <!-- / Form validation script-->

  <!--Datatable script-->
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>
  <!-- / Datatable script-->

  <script src="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>

  <script type="text/javascript">
    $(document).ready(function (){
      $.fn.dataTable.ext.errMode = 'none';

      storeList();
      //store datatable
      function storeList() {
        var storetable = $('#storetable').DataTable({
          columnDefs: [
              {targets: [-1], orderable: false}],
          "searching": true,
          "processing": true,
          "serverSide": true,
          "scrollX": true,
          "ajax": {
              "url": "<?php echo base_url(); ?>store/store/listing",
              "type": "POST",
              "data": function(data) {
                var csrfHash  = $('.txt_csrfname').val();
                data.<?= $csrf['name']; ?> = csrfHash;
              }
          },
          "bDestroy": true,
        });
        storetable.on('xhr.dt', function ( e, settings, json, xhr ) {
          if(json != null) {
            $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
          }else {
            getToken();
          }
        });
      };

      //  Add Store form
      $("#AddStoreForm").unbind('submit').bind('submit', function() { //input
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
            $('#AddStore').offcanvas('hide');
            $("#AddStoreForm")[0].reset();
            snack(data.message,'success');
            storeList();
          }
          else
          {
            $('#AddStore').offcanvas('show');
            snack(data.message,'danger');
          }
        },
        error: function(xhr, status, error) {
          snack('Something went wrong. Please try again later','danger');
        },
      });
      return false;
    });

      //add pos number field
      $('#removePos').hide();

      $('#show_pos').on('click','.addPos', function(){
      var totalpos = parseInt($("#totalpos").val());
      var remove = '<button class="btn btn-primary removePos" data-posnum="'+totalpos+'" style="background-color:#122620; border-color:#122620">Remove</button>';
      $('#addPos_'+totalpos).html(remove);
      totalpos = totalpos + 1;
      $("#totalpos").val(totalpos);

      addPos=
      '<div id="posNumber_'+totalpos+'">'+
        '<div class="row mb-3">'+
          '<div class="col-md-6">'+
            '<label for="bs-validation-pos"class="form-label mb-2">POS Number</label>'+
            '<span style="color:red;">*</span>'+
            '<input class="form-control" id="bs-validation-pos" type="text" placeholder="Eg: 102" name="posno_'+totalpos+'" required/>'+
          '</div>'+
          '<div class="col-md-4">'+
              '<label for="" class="form-label mb-2"></label>'+
              '<div class="d-buttons" id="addPos_'+totalpos+'">'+
                '<button class="btn btn-primary me-sm-3 me-1 addPos" data-posnum="'+totalpos+'" type="button" style="background-color:#122620; border-color:#122620">'+
                  '<span>Add Line</span>'+
                '</button>'+
              '</div>'+
          '</div>'+
        '</div>'+
      '</div>';

      $('#show_pos').append(addPos);
    });

      $('#show_pos').on('click', '.removePos', function(){
        var posnum = $(this).data('posnum');
        $("#posNumber_"+posnum).remove();
      });

      $('#resetform').click(function() {
        $('#bs-validation-card option:selected').remove();
      });
    });
    </script>
  </body>
</html>
