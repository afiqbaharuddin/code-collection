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

  <title>User Activity Logs</title>
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
                    <h5 class="content-header-title float-left pr-1 mb-0">ACTIVITY LOGS</h5>
                    <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb breadcrumb-style1 p-0 mb-0">
                        <li class="breadcrumb-item">
                          <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                            <i class="bx bx-home-alt"></i>
                          </a>
                        </li>
                        <li class="breadcrumb-item">Logs</li>
                        <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >Activity Logs</li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              <div class="row" style="margin-bottom:35px;">
                  <div class="col-md-12 col-12">
                    <div class="card">
                    <div class="card-body">

                      <h4>Search Logs</h4>
                      <form class="dt_adv_search" method="POST">
                        <div class="row">
                          <div class="col-12">
                            <div class="row g-3">

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Role:</label>
                                  <select id="filter_role" name="filter_role" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach ($role as $row) {
                                      if ($this->session->userdata('Filter_Role') != null && $this->session->userdata('Filter_Role') == $row->UserRoleId) {
                                        $selectrole = 'selected';
                                      }else {
                                        $selectrole = '';
                                      }
                                      ?>
                                      <option value="<?php echo $row->UserRoleId; ?>"  <?php echo $selectrole; ?>><?php echo $row->Role; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">User:</label>
                                <input type="text" id="filter_name" name="filter_name" class="form-control dt-input" data-column="2" placeholder="Eg: Ahmad"
                                 value="<?php if ($this->session->userdata('Filter_Name') != null) echo $this->session->userdata('Filter_Name'); ?>" data-column-index="1">
                              </div>

                              <?php if ($permission->UserRoleId == 1 || $permission->StoreId == 0) { ?>
                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Store:</label>
                                  <select id="filter_store" name="filter_store" class="form-control">
                                    <!-- <option value="0">All</option> -->
                                    <?php foreach ($store as $row) {
                                      if ($this->session->userdata('Filter_Store_Activity') != null && $this->session->userdata('Filter_Store_Activity') == $row->StoreId) {
                                        $selectstore = 'selected';
                                      }else {
                                        $selectstore = '';
                                      }
                                       ?>
                                      <option value="<?php echo $row->StoreId; ?>" <?php echo $selectstore ?>><?php echo $row->StoreName; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>
                            <?php } ?>

                              <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">Category:</label>
                                  <select id="filter_category" name="filter_category" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach ($category as $row) {
                                      if ($this->session->userdata('Filter_Category') != null && $this->session->userdata('Filter_Category') == $row->ActivityTypeId) {
                                        $selectrole = 'selected';
                                      }else {
                                        $selectrole = '';
                                      }
                                       ?>
                                      <option value="<?php echo $row->ActivityTypeId; ?>" <?php echo $selectrole ?>><?php echo $row->ActivityTypeName; ?></option>
                                    <?php } ?>
                                  </select>
                              </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                  <label class="form-label">Filter Range Date Between:</label>
                                      <input type="date" id="date_from" class="form-control" name="date_from"
                                      value="<?php if ($this->session->userdata('Filter_DateFrom') != null) echo $this->session->userdata('Filter_DateFrom'); ?>">
                                </div>

                              <div class="col-12 col-sm-6 col-lg-4 pt-4">
                                  <input type="date" id="date_to" class="form-control" name="date_to"
                                  value="<?php if ($this->session->userdata('Filter_DateTo') != null) echo $this->session->userdata('Filter_DateTo'); ?>">
                              </div>

                                  <div class="col-12 col-sm-6 col-lg-4" style="margin-top:15px; margin-right:20px">
                                    <button type="button" id="btn-search" class="btn btn-primary mb-1" style="background-color:#122620; border-color:#122620">Search</button>
                                    <button type="reset" id="btn-reset" class="btn btn-label-secondary mb-1" style="margin-left:10px">Reset</button>

                                  </div>
                            </div>
                          </div>
                        </div>
                      </form>
                   </div>
                 </div>
               </div>
             </div>

            <!-- Users List Table -->
            <div class="card">
              <div class="card-header border-bottom">
                <div class="d-flex justify-content-between">
                  <div class="">
                    <h4>User Activity</h4>
                  </div>

                  <?php
                  $export = $this->RolePermission_Model->submenu_master(20);
                  if ($export->View == 1 ) { ?>
                  <div class="dt-buttons">
                    <button type="button" class="dt-button buttons-collection btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                      style="margin-left:10px; margin-right:10px">
                      <span>
                        <i class="bx bx-upload me-2">
                        </i>Export
                      </span>
                    </button>

                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="<?php echo base_url('logs/activitylog/export_userlog_csv'); ?>">
                        <i class="bx bx-file me-2"></i>
                        Csv
                      </a>
                    </li>
                    </ul>
                   </div>
                   <?php }  ?>
                </div>

              <div class="card-datatable table-responsive" >
                <table class="datatables-stores table border-top" id="activitylogtable">
                  <thead style="background-color:#122620">
                    <tr>
                      <th style="color:white">User</th>
                      <th style="color:white">Role</th>
                      <th class="col-1" style="color:white">Date</th>
                      <th style="color:white">Category</th>
                      <th style="color:white">Details</th>
                      <th style="color:white">Ip Address</th>
                      <th style="color:white">Browser</th>
                    </tr>
                  </thead>
                </table>
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

<script type="text/javascript">
$(document).ready(function(){
  $.fn.dataTable.ext.errMode = 'none';

    // activity log table
    get_datatables();
    function get_datatables() {
      var activitylogtable = $('#activitylogtable').DataTable({
      //   columnDefs: [
      //     {targets: [-1], orderable: false}
      //   ],
        "ordering": false,
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>logs/ActivityLog/activityloglisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash           = $('.txt_csrfname').val();
              // console.log(csrfHash);
              var filter_role        = $('#filter_role').val();
              var filter_name        = $('#filter_name').val();
              var filter_category    = $('#filter_category').val();
              var filter_store       = $('#filter_store').val();
              var date_from          = $('#date_from').val();
              var date_to            = $('#date_to').val();

              data.<?= $csrf['name']; ?> = csrfHash;
              data.filter_role           = filter_role;
              data.filter_name           = filter_name;
              data.filter_category       = filter_category;
              data.filter_store          = filter_store;
              data.date_from             = date_from;
              data.date_to               = date_to;
            }
        },
        "bDestroy": true,
      });
      activitylogtable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if (json != null) {
          $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
          var csrfHash = $('.txt_csrfname').val();
        }else {
          getToken();
        }
      });
    }

    $('#btn-search').click(function() {
      get_datatables();

      var filter_role        = $('#filter_role').val();
      var filter_name        = $('#filter_name').val();
      var filter_category    = $('#filter_category').val();
      var filter_store       = $('#filter_store').val();
      var date_from          = $('#date_from').val();
      var date_to            = $('#date_to').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('logs/activitylog/filteractivitylog'); ?>',
        type: 'POST',
        data: {filter_role: filter_role, filter_name: filter_name, filter_category: filter_category, date_from: date_from,
              date_to: date_to,filter_store:filter_store, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $('#btn-reset').click(function() {
      $('#filter_role').val('');
      $('#filter_name').val('');
      $('#filter_category').val('');
      $('#filter_store').val('');
      $('#date_from').val('');
      $('#date_to').val('');
      $('#filter_role').prop('selectedIndex',0);
      $('#filter_category').prop('selectedIndex',0);
      get_datatables();
      location.reload(true);
    });
  })
</script>
</body>
</html>
