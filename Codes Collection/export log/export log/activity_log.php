<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/"
  data-template="vertical-menu-template">
  <head>
    <!-- <style>
      .paginate_button{
         background-color: #adc9c5 !important;
      }
    </style> -->
    <title>Activity Log</title>
    <?php echo $top; ?>
  </head>

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
          <?php echo $navbar; ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y" >
              <h4 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">List/</span> Activity Log
              </h4>

              <!-- filter log -->
              <div class="col-12 mb-4">
                <div class="card">
                  <h5 class="card-header">Filter Log</h5>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <p>Start Date</p>
                          <input type="date" class="form-control" id="date_from" name="date_from" value="<?php if ($this->session->userdata('Filter_DateFrom') != null) echo $this->session->userdata('Filter_DateFrom'); ?>">
                        </div>
                        <div class="col-md-4">
                          <p>End Date</p>
                          <input type="date" class="form-control" id="date_to" name="date_to"  value="<?php if ($this->session->userdata('Filter_DateTo') != null) echo $this->session->userdata('Filter_DateTo'); ?>">
                        </div>
                          <div class="col-md-4" style="margin-top:38px;">
                            <div class="btn-group" role="group" aria-label="Basic example">
                              <button type="button" id="btn-search" name="search" class="btn" style="background-color:#62CCC0;color:#FFFF">SEARCH</button>
                              <button type="reset" id="btn-reset" name="reset" class="btn" style="background-color:#adc9c5;color:#FFFF">RESET</button>
                              <button type="submit" onclick="exportToExcel()" class="btn"style="background-color:#B6E2D3;color:#00000" >EXPORT</button>
                            </div>
                      </div>
                    </div>
                </div>
              </div>
              <!-- filter log -->

              <!-- Table -->
              <div style="margin-top:50px" class="card">
                <h5 class="card-header">Log Listing</h5>

                <div class="card-datatable text-nowrap">
                  <table class="datatables-ajax table table-bordered" id="logtable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Activity Date</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Browser</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <!--/ Ajax Sourced Server-side -->
            </div>
            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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
    <?php echo $bottom; ?>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/flatpickr/flatpickr.js"></script>

    <script>
    logtable();
    function logtable(){
      var logtable = $('#logtable').DataTable({

        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>log/ActivityLog/ActivityLogList",
            "type": "POST",
            "data": function(data) {

              var date_from = $('#date_from').val();
              var date_to   = $('#date_to').val();
              var csrfHash  = $('.txt_csrfname').val();

              data.<?= $csrf['name']; ?> = csrfHash;
              data.date_from             = date_from;
              data.date_to               = date_to;
            }
        },
        "bDestroy": true,
        });

      logtable.on('xhr.dt', function ( e, settings, json, xhr ) {
      if (json != null) {
        $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        var csrfHash = $('.txt_csrfname').val();
      }else {
        getToken();
      }
      });
    }

      function exportToExcel() {
        // Redirect to the export URL with a query parameter
        window.location.href = "<?php echo base_url('log/ActivityLog/export'); ?>";
    }

    $('#btn-search').click(function() {
      logtable();

      var date_from          = $('#date_from').val();
      var date_to            = $('#date_to').val();

      var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();

      $.ajax({
        url: '<?php echo base_url('log/activityLog/ActivityLogList'); ?>',
        type: 'POST',
        data: {date_from: date_from,date_to: date_to, [csrfName]: csrfHash},
        dataType: 'json',
        success:function(data)
        {
          $('.txt_csrfname').val(data.token);
        }
      });
      return false;
    });

    $('#btn-reset').click(function() {
      $('#date_from').val('');
      $('#date_to').val('');

      logtable();
      location.reload(true);
    });
    </script>
  </body>
</html>
