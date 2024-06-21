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
    <title>Issued Voucher List</title>
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
            <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Content -->
            <div class="content-header-row">
              <div class="content-header-left col-12 mb-4 mt-1">
                <div class="row breadcrumbs-top">
                  <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">ISSUED VOUCHER LIST</h5>
                    <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb p-0 mb-0">
                        <li class="breadcrumb-item">
                          <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                            <i class="bx bx-home-alt"></i>
                          </a>
                        </li>
                        <li class="breadcrumb-item">Vouchers</li>
                        <li class="breadcrumb-item">
                          <a href="<?php echo base_url(); ?>/voucher/IssuanceVoucher/issuancevoucher"><u>Manual Voucher Issuance</u></a>
                        </li>
                        <li class="breadcrumb-item active" style="color:#D6AD60">Issued Voucher List</li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
              </div>

              <div class="card">
                <div class="card-header border-bottom">

                  <div class="d-flex justify-content-between">
                    <div class="">
                      <h4>Issued Voucher List</h4>
                    </div>
                    <a target="_blank" href="<?php echo base_url(); ?>voucher/VoucherLayout/voucher_details/<?php echo $reprintid; ?>">
                      <button type="button" name="print" class="btn btn-primary" id="print" style="background-color:#122620; border-color:#122620">Print</button>
                    </a>

                 </div>
                        <h6 class="alert alert-success" style="width:25%" id="success">Voucher Insuance Succesful</h6>
                        <input type="hidden" name="reprintid" value="<?php echo $reprintid; ?>">

                          <div class="pt-2">
                           <div class="table-responsive">
                             <table class="table mb-0">
                               <thead style="background-color:#122620">
                                 <tr>
                                   <th  style="color:white">Voucher Number</th>
                                   <th  style="color:white">Type</th>
                                   <th  style="color:white">Value</th>
                                   <th  style="color:white">Start Date</th>
                                   <th  style="color:white">Expiry Date</th>
                                 </tr>
                               </thead>

                               <tbody>
                                 <?php foreach ($sucessReprint as $row): ?>
                                   <tr>
                                     <td><?php echo $row->VouchersNumber ?></td>
                                     <td><?php echo $row->VoucherShortName ?></td>
                                     <td><?php echo $row->VouchersValue ?></td>
                                     <td><?php echo $row->CreatedDate ?></td>
                                     <td><?php echo $row->ExpDate ?></td>
                                   </tr>
                                 <?php endforeach; ?>
                               </tbody>
                             </table>
                           </div>
                         </div>
                      </div>
                    </div>
                    </div>
                  </div>
        <!-- / Issuance Voucher-->
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

    <script type="text/javascript">
    // var $id = $('.reprintid').val();
    //
    //   $('#print').click(function() {
    //         window.location.href = '<?php echo base_url() ?>voucher/VoucherLayout/voucher_details/'+ $id;
    //   });
    </script>


  </body>
</html>
