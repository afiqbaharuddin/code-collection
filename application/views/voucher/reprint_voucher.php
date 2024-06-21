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
    <title>Reprint Voucher</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">REPRINT VOUCHER</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Reprint Voucher</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xxl">
                <div class="card mb-4">
                  <?php if ($reprintAllowVoucherSetting->NumReprintCheck == 1){ ?>
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Reprint Voucher Form</h5>
                  </div>

                    <div class="card-body">
                      <form class="reprintform" id="reprintvoucherform" method="post" action="<?php echo base_url(); ?>voucher/ReprintVoucher/reprintvoucherForm">
                        <input type="hidden" name="layoutid" value="<?php if(isset($reprint->LoginId)){ echo $reprint->LoginId; } ?>">

                        <?php if ($permission->UserRoleId != 1) { ?>

                          <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="storeCode">Store Code</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" name="storecode" id="storecode" value="<?php if(isset($reprint->StoreId)){ echo $reprint->StoreCode; } ?>" disabled>
                                <input type="hidden" name="storeid" value="<?php if(isset($reprint->StoreId)){ echo $reprint->StoreId; } ?>">
                            </div>
                          </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="storeName">Store Name</label>
                            <div class="col-sm-10">
                              <input type="text" id="storename" name="storename" class="form-control" value="<?php if(isset($reprint->StoreName)){ echo $reprint->StoreName; } ?>" disabled />
                            </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="posNumber">POS Number</label>
                          <div class="col-sm-10">
                            <select class="form-select" id="posNum" name="posNumber">
                              <option value="">Select POS Number</option>
                              <?php foreach ($posdb as $row) { ?>
                                <option value="<?php echo $row->POSId; ?>"><?php echo $row->POSNumber; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <?php }else { ?>
                          <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="storeCode">Store</label>
                              <div class="col-sm-10">
                                <select class="form-control select2" name="storecode" id="storecode">
                                  <?php foreach ($store as $row) { ?>
                                    <option value="<?php echo $row->StoreId; ?>"><?php echo $row->StoreCode." -".$row->StoreName; ?></option>
                                  <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="storeid" id="storeid" value="<?php if(isset($reprint->StoreId)){ echo $reprint->StoreId; } ?>">
                          </div>

                          <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="posNumber">POS Number</label>
                            <div class="col-sm-10">
                              <select class="form-select" id="posNumber" name="posNumber">
                                <option value="">Select POS Number</option>
                                <?php foreach ($posdb as $row) { ?>
                                  <option value="<?php echo $row->POSId; ?>"><?php echo $row->POSNumber; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        <?php } ?>

                      <!-- <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="issuedDate">Issued Date</label>
                        <div class="col-sm-10">
                          <input type="date" name="issuedDate" id="issuedDate" class="form-control" placeholder="Eg: 2/3/2023">
                        </div>
                      </div> -->

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="issuedDate">Issued Date</label>
                        <div class="col-sm-10">
                          <input type="date" name="issuedDate" id="issuedDate" class="form-control" value="<?php echo date("Y-m-d") ?>" disabled>
                        </div>
                      </div>

                      <?php if ($permission->UserRoleId != 1){ ?>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="receiptNumber">Receipt Number</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <div class="col-sm-1">
                                <strong><label style="margin-top:5px; margin-left:5px">T</label></strong>
                              </div>
                              <input class="form-control" type="text" name="receiptstorecode" id="" value="000<?php if(isset($reprint->StoreCode)){ echo $reprint->StoreCode; } ?>" maxlength="6" style="margin-left:-45px"  readonly/>
                              <div class="col-sm-1">
                                <strong><label style="margin-top:5px; margin-left:20px">-</label></strong>
                              </div>
                              <input class="form-control" type="text" name="receiptposnumber" id="receiptposnumber" maxlength="3" style="margin-left:-30px" readonly/>
                              <input class="form-control" type="text" name="receiptNumber" id="receiptNumber" maxlength="9" style="width:50%"/>
                            </div>
                          </div>
                        </div>
                      <?php } else { ?>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="receiptNumber">Receipt Number</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <div class="col-sm-1">
                                <strong><label style="margin-top:5px; margin-left:5px">T</label></strong>
                              </div>
                              <input class="form-control" type="text" name="receiptstorecode" id="receiptstorecode" value="000<?php if(isset($reprint->StoreCode)){ echo $reprint->StoreCode; } ?>" maxlength="6" style="margin-left:-45px"  readonly/>
                              <div class="col-sm-1">
                                <strong><label style="margin-top:5px; margin-left:20px">-</label></strong>
                              </div>
                              <input class="form-control" type="text" name="receiptposnumber" id="receiptposnumber" maxlength="3" style="margin-left:-30px" readonly/>
                              <input class="form-control" type="text" name="receiptNumber" id="receiptNumber" maxlength="9" style="width:50%"/>
                            </div>
                          </div>
                        </div>
                        <?php  } ?>

                        <div class="row justify-content-end pt-2">
                          <div class="col-sm-10">
                            <a>
                            <input type="hidden" name="loggerid" id="loggerid" value="<?php if(isset($reprint->AppLoggerId)){ echo $reprint->AppLoggerId; } ?>">
                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                              <button type="button" id="submitbtn" class="btn btn-primary me-sm-3 me-1" style="background-color:#122620; border-color:#122620">Submit</button>
                            </a>
                              <button type="reset" class="btn btn-label-secondary">Cancel</button>
                          </div>
                        </div>
                      </form>
                    <?php } else{ ?>
                      <div class="card-body text-center mb-3">
                        <h5 class="card-title">This Store is not allowed to Reprint Voucher</h5>
                        <p class="card-text">Please Check with your Admin and enable for this Store to Reprint Vouchers. </p>
                      </div>
                    <?php } ?>
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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom; ?>

    <script type="text/javascript">

      $(document).ready(function(){

        $('#submitbtn').on('click', function(){
          $("#reprintvoucherform").submit();
        });

          //reprint voucher  form
          $("#reprintvoucherform").unbind('submit').bind('submit', function() { //input
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
                  window.location ='<?php echo base_url()?>voucher/IssuanceVoucher/reprint/'+ data.successreprint;
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

          $("#posNumber").change(function(){
            var value = $("#posNumber option:selected");
            $("#receiptposnumber").val(value.text().substr($("#posNumber option:selected").length - 4));
          });

          $("#posNum").change(function(){
            var value = $("#posNum option:selected");
            $("#receiptposnumber").val(value.text().substr($("#posNum option:selected").length - 4));
          });

          $("#receiptNumber").on('input', function(e){
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
          });

          $('#storecode').on('change', function()  {
            get_store();
          });

          get_store();

          function get_store(){
            var storecode = $('#storecode').val();
            var csrfName  = $('.txt_csrfname').attr('name');
            var csrfHash  = $('.txt_csrfname').val();

            $.ajax({
              type: "post",
              url: "<?php echo base_url(); ?>voucher/ReprintVoucher/get_pos",
              data: {storecode: storecode, [csrfName]: csrfHash},
              dataType: 'json',
              success: function(data)
              {
                $('.txt_csrfname').val(data.token);
                $('#posNumber').html(data.result);
                // $('#storeid').val(data.id);
                // alert($('#storeid').val(data.id));
                var value = $("#posNumber option:selected");
                $("#receiptposnumber").val(value.text().substr($("#posNumber option:selected").length - 4));

                // var value = $("#posNum option:selected");
                // $("#receiptposnumber").val(value.text().substr($("#posNum option:selected").length - 4));
              }
            });

            var value = $("#storecode option:selected");
            $("#receiptstorecode").val('000' + value.text().substr($("#storecode option:selected").length - 1,3));
          }

          // $("#storecode").change(function(){
          //   var value = $(this).val();
          //   $('#receiptstorecode').val('000'+ value);
          // });

      });
    </script>
  </body>
</html>
