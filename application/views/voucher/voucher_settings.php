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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <title>Voucher Settings</title>
    <?php echo $header; ?>

    <style media="screen">
      .dataTables_scrollHeadInner{
        width: 100%!important;
      }

      .none{
        display: none;
      }
    </style>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">VOUCHER SETTINGS</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Vouchers</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60" >Voucher Settings</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Voucher Details -->

              <div class="row">
                <div class="col-xl-12">
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#storeSettings-pills" aria-controls="storeSettings-pills" aria-selected="true">
                          Store
                        </button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#userSettings-pills" aria-controls="userSettings-pills" aria-selected="false">
                          User
                        </button>
                      </li>
                    </ul>

                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="storeSettings-pills" role="tabpanel">
                        <div class="d-flex justify-content-between">
                          <div class="col-md-4">
                            <h4>Store Voucher Settings</h4>
                          </div>
                        </div>

                        <form id="voucherSettings">
                        <div class="card-datatable text-nowrap">
                          <table id="storesettingtable" class="table">
                            <thead style="background-color:#122620">
                              <tr>
                                <th class="col-2" style="color:white">Store</th>
                                <th class="col-2" style="color:white">Num of reprint allowed</th>
                                <th class="col-3" style="color:white">Store Voucher Issuance</th>
                              </tr>
                            </thead>
                          </table>
                          <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                          <!-- <div class="pt-4 col-12">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1" style="background-color:#122620; border-color:#122620;">Submit</button>
                          </div> -->
                        </div>
                      </form>
                      </div>

                      <!--/////////////////////////////////////////////////////////////////////////////////////////////////////// -->

                      <div class="tab-pane fade" id="userSettings-pills" role="tabpanel">
                        <div class="d-flex justify-content-between">
                          <div class="col-md-4">
                            <h4>User Voucher Settings</h4>
                          </div>
                        </div>

                        <div class="card-datatable text-nowrap">
                          <table id="usersettingtable" class="table">
                            <thead style="background-color:#122620">
                              <tr>
                                <th class="col-2" style="color:white">User</th>
                                <th class="col-3" style="color:white">Num of days extend allowed</th>
                                <th class="col-2" style="color:white">Block Vouchers</th>
                                <th class="col-2" style="color:white">Unblock Vouchers</th>
                                <th class="col-2" style="color:white">Cancel Vouchers</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      <div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

            <!-- Footer -->
          <?php echo $footer; ?>
            <!-- / Footer -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
    </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>

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

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>
  $(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'none';

    $("#storesettingtable").on('input','.reprintNumAll',function(e){
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
      });

      $("#storesettingtable").on('input','.reprintNum',function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

      //store setting datatable
      var storesettingtable = $('#storesettingtable').DataTable({
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>voucher/vouchersettings/storevoucherlisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
              setTimeout(function() {

                $(".dateissuance1").datepicker({ //newStartDate datepicker
                numberOfMonths: 1,
                dateFormat: 'yy-mm-dd',
                minDate: new Date(),
                    onSelect: function(selected) {
                      var num = $(this).data('num');
                      var storeid = $(this).data('storeid');
                      // console.log(selected);
                      $("#issuanceNum2_"+num).datepicker("option","minDate", selected);

                      saving(num,storeid);
                    }
                });

                $(".dateissuance2").datepicker({ //newEndDate datepicker
                    numberOfMonths: 1,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(selected) {
                      var num = $(this).data('num');
                      var storeid = $(this).data('storeid');
                      // console.log(selected);
                       $("#issuanceNum1_"+num).datepicker("option","maxDate", selected);
                       saving(num,storeid);
                    }
                });

              }, 2000);
            }
        },
        "bDestroy": true,
      });
      storesettingtable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if (json != null) {
          $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        }else {
          getToken();
        }
      });

    //reprint all store
    //give same value to all store
   $("#storesettingtable").on('keyup','.reprintNumAll',function(){
     var reprintclick = 1;
     var valuereprint  = $('#reprintNumAll').val();
     $(".reprintNum").val(valuereprint);
     var valueissue    = $('#issuanceNumAll1').val();
     var valueissue2   = $('#issuanceNumAll2').val();

     var csrfName      = $('.txt_csrfname').attr('name');
     var csrfHash      = $('.txt_csrfname').val();

       if ($('#issuanceCheckAll').is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheckAll').is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_all",
         data: {reprintclick:reprintclick, checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   $("#storesettingtable").on('change','.reprintCheckAll',function(){
     var reprintclick = 1;
     var status;
     if($(this).is(":checked")){
        $('.reprintCheck').prop("checked", true);
        $(".reprintNumAll").removeClass('none');
        $(".reprintNum").removeClass('none');
        $('#reprintCheckAll').val(1);
     } else
     {
        $('.reprintCheck').prop("checked", false);
        $(".reprintNumAll").addClass('none');
        $(".reprintNum").addClass('none');
        $('#reprintCheckAll').val(0);
     }

     var valuereprint  = $('#reprintNumAll').val();
     $(".reprintNum").val(valuereprint);

     var valueissue   = $('#issuanceNumAll1').val();
     var valueissue2  = $('#issuanceNumAll2').val();

     var csrfName   = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

       if ($('#issuanceCheckAll').is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheckAll').is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_all",
         data: {reprintclick:reprintclick, checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
         }
       });
   });

   //send all data from all to store (start date)
   $("#storesettingtable").on('change','.issuanceNumAll1',function(){
     var issuanceclick = 1;
     var valuereprint  = $('#reprintNumAll').val();
     var valueissue    = $('#issuanceNumAll1').val();
     var valueissue2   = $('#issuanceNumAll2').val();
     $(".issuanceNum1").val(valueissue);
     $(".issuanceNum2").val(valueissue2);

     var csrfName   = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

       if ($('#issuanceCheckAll').is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheckAll').is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_all",
         data: {issuanceclick:issuanceclick, checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   //send all data from all to store (end date)
   $("#storesettingtable").on('change','.issuanceNumAll2',function(){
     var issuanceclick = 1;
     var valuereprint  = $('#reprintNumAll').val();
     var valueissue    = $('#issuanceNumAll1').val();
     var valueissue2   = $('#issuanceNumAll2').val();
     $(".issuanceNum1").val(valueissue);
     $(".issuanceNum2").val(valueissue2);

     var csrfName     = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

       if ($('#issuanceCheckAll').is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheckAll').is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_all",
         data: {issuanceclick:issuanceclick, checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   //for hide and show for all stores
   $("#storesettingtable").on('change','.issuanceCheckAll',function(){
    var issuanceclick = 1;
    var status;
     if($(this).is(":checked")){
        $('.issuanceCheck').prop("checked", true);
        $(".issuanceNumAll1").removeClass('none');
        $(".issuanceNumAll2").removeClass('none');
        $(".issuanceNum1").removeClass('none');
        $(".issuanceNum2").removeClass('none');
        $('#issuanceCheck').val(1);
     } else
     {
       $('.issuanceCheck').prop("checked", false);
       $(".issuanceNumAll1").addClass('none');
       $(".issuanceNumAll2").addClass('none');
       $(".issuanceNum1").addClass('none');
       $(".issuanceNum2").addClass('none');
       $('#issuanceCheck').val(0);
     }

     var valuereprint  = $('#reprintNumAll').val();
     var checkreprint  = $('#reprintCheckAll').val();

     var valueissue   = $('#issuanceNumAll1').val();
     var valueissue2  = $('#issuanceNumAll2').val();
     $(".issuanceNum1").val(valueissue);
     $(".issuanceNum2").val(valueissue2);
     var checkissue   = $('#issuanceCheckAll').val();

     var csrfName     = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

       if ($('#issuanceCheckAll').is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheckAll').is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_all",
         data: {issuanceclick:issuanceclick,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   //individual store reprint
   $("#storesettingtable").on('change','.reprintCheck',function(){
       var num = $(this).data("num");
       if($(this).is(":checked")){
         $('#reprintNum_'+num).removeClass('none');  //bukak input boxaddClass
       } else
       {
         $('#reprintNum_'+num).addClass('none'); //hide input box
       }
     });


   //individual store inssuance
  $("#storesettingtable").on('change','.issuanceCheck',function(){
       var num = $(this).data("num");
       if($(this).is(":checked")){
         $('#issuanceNum1_'+num).removeClass('none');
         $('#issuanceNum2_'+num).removeClass('none');
       } else
       {
         $('#issuanceNum1_'+num).addClass('none');
         $('#issuanceNum2_'+num).addClass('none');
       }
     });

   //now is allow 1 store, then user allow all store, so all store will be allow.
   // then user disallow one store, automatically disallow all store.
   $("#storesettingtable").on('change','.onchangeInd',function(){
     var num     = $(this).data('num');
     var storeid = $(this).data('storeid');
     var type    = $(this).data('type');

     if (type == 'reprint') {
       $('.reprintCheckAll').prop("checked", false);
       $(".reprintNumAll").addClass('none');
     }
     else if (type == 'issuance') {
       $('.issuanceCheckAll').prop("checked", false);
       $(".issuanceNumAll1").addClass('none');
       $(".issuanceNumAll2").addClass('none');
     }

     var valuereprint  = $('#reprintNum_'+num).val();
     var valueissue    = $('#issuanceNum1_'+num).val();
     var valueissue2   = $('#issuanceNum2_'+num).val();

     var csrfName      = $('.txt_csrfname').attr('name');
     var csrfHash      = $('.txt_csrfname').val();

       if ($('#issuanceCheck_'+num).is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheck_'+num).is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }

     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_individu",
         data: {storeid:storeid,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   function saving(num,storeid){
     var valuereprint  = $('#reprintNum_'+num).val();
     var valueissue    = $('#issuanceNum1_'+num).val();
     var valueissue2   = $('#issuanceNum2_'+num).val();

     var csrfName      = $('.txt_csrfname').attr('name');
     var csrfHash      = $('.txt_csrfname').val();

       if ($('#issuanceCheck_'+num).is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheck_'+num).is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }

     $.ajax({
       type: "post",
       url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_individu",
       data: {storeid:storeid,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
       dataType: 'json',
       success: function(data)
       {
         $('.txt_csrfname').val(data.token);
         if (data.status == true) {
           snack(data.message, 'success');
         }else {
           snack(data.message,'danger');
         }
       }
     });
   }

   $("#storesettingtable").on('change','.issuanceNum1',function(){
     var num     = $(this).data('num');
     var storeid = $(this).data('storeid');

     var valuereprint  = $('#reprintNum_'+num).val();
     var valueissue    = $('#issuanceNum1_'+num).val();
     var valueissue2   = $('#issuanceNum2_'+num).val();

     var csrfName      = $('.txt_csrfname').attr('name');
     var csrfHash      = $('.txt_csrfname').val();

       if ($('#issuanceCheck_'+num).is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheck_'+num).is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }

     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_individu",
         data: {storeid:storeid,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

   $("#storesettingtable").on('change','.issuanceNum2',function(){

     var num     = $(this).data('num');
     var storeid = $(this).data('storeid');

     var valuereprint  = $('#reprintNum_'+num).val();
     var valueissue    = $('#issuanceNum1_'+num).val();
     var valueissue2   = $('#issuanceNum2_'+num).val();

     var csrfName      = $('.txt_csrfname').attr('name');
     var csrfHash      = $('.txt_csrfname').val();

       if ($('#issuanceCheck_'+num).is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheck_'+num).is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }

     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_individu",
         data: {storeid:storeid,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });


   $("#storesettingtable").on('keyup','.reprintNum',function(){
     var num = $(this).data('num');
     var storeid = $(this).data('storeid');

     var valuereprint  = $('#reprintNum_'+num).val();
     var valueissue    = $('#issuanceNum1_'+num).val();
     var valueissue2   = $('#issuanceNum2_'+num).val();

     var csrfName   = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

       if ($('#issuanceCheck_'+num).is(":checked")) {
         var checkissue = 1;
       }else {
         var checkissue = 0;
       }

       if ($('#reprintCheck_'+num).is(":checked")) {
         var checkreprint = 1;
       }else {
         var checkreprint = 0;
       }

     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/storesetting_individu",
         data: {storeid:storeid,checkissue:checkissue,valueissue:valueissue,valueissue2:valueissue2,checkreprint: checkreprint,valuereprint: valuereprint,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
   });

/////////////////////////////////////////////////////////////////////////////////

   //user setting datatable
   $("#usersettingtable").on('input','.allNumExtendAll',function(e)
   {
       $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    //datatable user voucher
   var usersettingtable = $('#usersettingtable').DataTable(
     {
     "searching": true,
     "processing": true,
     "serverSide": true,
     "scrollX": true,
     "ajax": {
         "url": "<?php echo base_url(); ?>voucher/vouchersettings/uservoucherlisting",
         "type": "POST",
         "data": function(data) {
           var csrfHash  = $('.txt_csrfname').val();
           data.<?= $csrf['name']; ?> = csrfHash;
         }
     },
     "bDestroy": true,
   });
   usersettingtable.on('xhr.dt', function ( e, settings, json, xhr ){
     if (json != null) {
       $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
     }else {
       getToken();
     }
   });

   //give same value to all user
 $("#usersettingtable").on('keyup','.allNumExtendAll',function()
 {
   var num = $(this).data('num');

   var valueextend  = $('#allNumExtendAll').val();
   $(".numExtend").val(valueextend);

   var csrfName   = $('.txt_csrfname').attr('name');
   var csrfHash   = $('.txt_csrfname').val();

   if ($('#allNumExtendCheckAll').is(":checked")) {
     var checkextend = 1;
   }else {
     var checkextend = 0;
   }

   if ($('#allBlockersCheck').is(":checked")) {
     var checkblock = 1;
   }else {
     var checkblock = 0;
   }

   if ($('#allUnblockersCheck').is(":checked")) {
     var checkunblock = 1;
   }else {
     var checkunblock = 0;
   }

   if ($('#allCancelCheck').is(":checked")) {
     var checkcancel = 1;
   }else {
     var checkcancel = 0;
   }

   $.ajax({
       type: "post",
       url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_all",
       data: {checkextend:checkextend,valueextend:valueextend,[csrfName]:csrfHash },
       dataType: 'json',
       success: function(data)
       {
         $('.txt_csrfname').val(data.token);
         if (data.status == true) {
           snack(data.message, 'success');
         }else {
           snack(data.message,'danger');
         }
       }
     });
 });

   //hide and show input all user
  $("#usersettingtable").on('change','.allNumExtendCheckAll',function()
  {
    var status;
     if($(this).is(":checked")){
        $('.numExtendCheck').prop("checked", true);
        $(".allNumExtendAll").removeClass('none');
        $(".numExtend").removeClass('none');
        $('#allNumExtendCheckAll').val(1);
     } else
     {
       $('.numExtendCheck').prop("checked", false);
       $(".allNumExtendAll").addClass('none');
       $(".numExtend").addClass('none');
       $('#allNumExtendCheckAll').val(0);
     }

     var valueextend  = $('#allNumExtendAll').val();
     $(".numExtend").val(valueextend);

     var csrfName     = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

     if ($('#allNumExtendCheckAll').is(":checked")) {
       var checkextend = 1;
     }else {
       var checkextend = 0;
     }

     if ($('#allBlockersCheck').is(":checked")) {
       var checkblock = 1;
     }else {
       var checkblock = 0;
     }

     if ($('#allUnblockersCheck').is(":checked")) {
       var checkunblock = 1;
     }else {
       var checkunblock = 0;
     }

     if ($('#allCancelCheck').is(":checked")) {
       var checkcancel = 1;
     }else {
       var checkcancel = 0;
     }

     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_all",
         data: {checkextend:checkextend,valueextend:valueextend,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
  });

  $("#usersettingtable").on('change','.onchangeInd',function()
  {
    var num = $(this).data('num');
    var userid = $(this).data('userid');
    var type = $(this).data('type');

    if (type == 'extend') {
        $('#allNumExtendCheckAll').prop("checked", false);
        $("#allNumExtendAll").addClass('none');
    }else if (type == 'block') {
      $('#allBlockersCheck').prop("checked", false);
    }else if (type == 'unblock') {
      $('#allUnblockersCheck').prop("checked", false);
    }else if (type == 'cancel') {
      $('#allCancelCheck').prop("checked", false);
    }

    var valueextend  = $('#numExtend_'+num).val();

    var csrfName     = $('.txt_csrfname').attr('name');
    var csrfHash   = $('.txt_csrfname').val();

    //single extend check
    if ($('#numExtendCheck_'+num).is(":checked")) {
      var checkextend = 1;
    }else {
      var checkextend = 0;
    }

    //single block
    if ($('#blockVouchercheck_'+num).is(":checked")) {
      var checkblock = 1;
    }else {
      var checkblock = 0;
    }

    //single cancel
    if ($('#cancelVouchercheck_'+num).is(":checked")) {
      var checkcancel = 1;
    }else {
      var checkcancel = 0;
    }

    //single uunblock
    if ($('#unblockVouchercheck_'+num).is(":checked")) {
      var checkunblock = 1;
    }else {
      var checkunblock = 0;
    }

    $.ajax({
        type: "post",
        url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_individu",
        data: {userid:userid,checkextend:checkextend,valueextend:valueextend,checkblock:checkblock,checkunblock:checkunblock,checkcancel:checkcancel,[csrfName]:csrfHash },
        dataType: 'json',
        success: function(data)
        {
          $('.txt_csrfname').val(data.token);
          if (data.status == true) {
            snack(data.message, 'success');
          }else {
            snack(data.message,'danger');
          }
        }
      });
  });

  //individual extend days user checked
  $("#usersettingtable").on('change','.numExtendCheck',function(){

    var num = $(this).data("num");
    // alert(userid);

    if($(this).is(":checked")){
      $('#numExtend_'+num).removeClass('none');  //bukak input box
    } else
    {
      $('#numExtend_'+num).addClass('none'); //hide input box
    }
  });

  $("#usersettingtable").on('keyup','.numExtend',function()
  {
     var num    = $(this).data('num');
     var userid = $(this).data('userid');

     var valueextend  = $('#numExtend_'+num).val();

     var csrfName     = $('.txt_csrfname').attr('name');
     var csrfHash   = $('.txt_csrfname').val();

     if ($('#allNumExtendCheckAll').is(":checked")) {
       var checkextend = 1;
     }else {
       var checkextend = 0;
     }

     //single extend check
     if ($('#numExtendCheck_'+num).is(":checked")) {
       var checkextend = 1;
     }else {
       var checkextend = 0;
     }

     if ($('#allBlockersCheck').is(":checked")) {
       var checkblock = 1;
     }else {
       var checkblock = 0;
     }

     //single block
     if ($('#blockVouchercheck_'+num).is(":checked")) {
       var checkblock = 1;
     }else {
       var checkblock = 0;
     }

     if ($('#allUnblockersCheck').is(":checked")) {
       var checkunblock = 1;
     }else {
       var checkunblock = 0;
     }

     if ($('#allCancelCheck').is(":checked")) {
       var checkcancel = 1;
     }else {
       var checkcancel = 0;
     }

     //single block
     if ($('#cancelVouchercheck_'+num).is(":checked")) {
       var checkcancel = 1;
     }else {
       var checkcancel = 0;
     }
     // alert(check);
     $.ajax({
         type: "post",
         url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_individu",
         data: {userid:userid,checkextend:checkextend,valueextend:valueextend,checkblock:checkblock,checkunblock:checkunblock,checkcancel:checkcancel,[csrfName]:csrfHash },
         dataType: 'json',
         success: function(data)
         {
           $('.txt_csrfname').val(data.token);
           if (data.status == true) {
             snack(data.message, 'success');
           }else {
             snack(data.message,'danger');
           }
         }
       });
  });

   //block vouchers allow all
   $("#usersettingtable").on('change','.allBlockersCheck',function(){
      if($(this).is(":checked")){
         $('.blockVouchercheck').prop("checked", true);
         $('#blockVouchercheck').val(1);
      } else
      {
        $('.blockVouchercheck').prop("checked", false);
        $('#blockVouchercheck').val(0);
      }

      // var valueextend  = $('#allNumExtendAll').val();
      // $(".numExtend").val(valueextend);

      var csrfName     = $('.txt_csrfname').attr('name');
      var csrfHash   = $('.txt_csrfname').val();

      if ($('#allNumExtendCheckAll').is(":checked")) {
        var checkextend = 1;
      }else {
        var checkextend = 0;
      }

      if ($('#allBlockersCheck').is(":checked")) {
        var checkblock = 1;
      }else {
        var checkblock = 0;
      }


      if ($('#allUnblockersCheck').is(":checked")) {
        var checkunblock = 1;
      }else {
        var checkunblock = 0;
      }

      if ($('#allCancelCheck').is(":checked")) {
        var checkcancel = 1;
      }else {
        var checkcancel = 0;
      }

      $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_all",
          data: {checkblock:checkblock,[csrfName]:csrfHash },
          dataType: 'json',
          success: function(data)
          {
            $('.txt_csrfname').val(data.token);
            if (data.status == true) {
              snack(data.message, 'success');
            }else {
              snack(data.message,'danger');
            }
          }
        });
    });

    //unblock vouchers allow all
    $("#usersettingtable").on('change','.allUnblockersCheck',function()
    {
       if($(this).is(":checked")){
          $('.unblockVouchercheck').prop("checked", true);
          $('#unblockVouchercheck').val(1);
       } else
       {
         $('.unblockVouchercheck').prop("checked", false);
         $('#unblockVouchercheck').val(0);
       }

       // var valueextend  = $('#allNumExtendAll').val();
       // $(".numExtend").val(valueextend);

       var csrfName     = $('.txt_csrfname').attr('name');
       var csrfHash   = $('.txt_csrfname').val();

       if ($('#allNumExtendCheckAll').is(":checked")) {
         var checkextend = 1;
       }else {
         var checkextend = 0;
       }

       if ($('#allBlockersCheck').is(":checked")) {
         var checkblock = 1;
       }else {
         var checkblock = 0;
       }

       if ($('#allUnblockersCheck').is(":checked")) {
         var checkunblock = 1;
       }else {
         var checkunblock = 0;
       }

       if ($('#allCancelCheck').is(":checked")) {
         var checkcancel = 1;
       }else {
         var checkcancel = 0;
       }

       $.ajax({
           type: "post",
           url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_all",
           data: {checkunblock:checkunblock,[csrfName]:csrfHash },
           dataType: 'json',
           success: function(data)
           {
             $('.txt_csrfname').val(data.token);
             if (data.status == true) {
               snack(data.message, 'success');
             }else {
               snack(data.message,'danger');
             }
           }
         });
    });

     //cancel vouchers allow all
     $("#usersettingtable").on('change','.allCancelCheck',function(){
       // var num = $(this).data('num');
       // var userid = $(this).data('userid');
       //
        if($(this).is(":checked")){
           $('.cancelVouchercheck').prop("checked", true);
           $('#cancelVouchercheck').val(1);
        } else
        {
          $('.cancelVouchercheck').prop("checked", false);
          $('#cancelVouchercheck').val(0);
        }

        // var valueextend  = $('#allNumExtendAll').val();
        // $(".numExtend").val(valueextend);

        var csrfName     = $('.txt_csrfname').attr('name');
        var csrfHash   = $('.txt_csrfname').val();

        if ($('#allNumExtendCheckAll').is(":checked")) {
          var checkextend = 1;
        }else {
          var checkextend = 0;
        }

        if ($('#allBlockersCheck').is(":checked")) {
          var checkblock = 1;
        }else {
          var checkblock = 0;
        }


        if ($('#allUnblockersCheck').is(":checked")) {
          var checkunblock = 1;
        }else {
          var checkunblock = 0;
        }

        if ($('#allCancelCheck').is(":checked")) {
          var checkcancel = 1;
        }else {
          var checkcancel = 0;
        }

        $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/VoucherSettings/usersetting_all",
            data: {checkcancel:checkcancel,[csrfName]:csrfHash },
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              if (data.status == true) {
                snack(data.message, 'success');
              }else {
                snack(data.message,'danger');
              }
            }
          });
      });

      $('#storesettingtable').on('click','.hasDatepicker', function(){
        // var num = $(this).data('num');
        //
        // var date1 = $("#issuanceNum1_"+num).val();
        // var date2 = $("#issuanceNum2_"+num).val();
        //
        // console.log(date1);
        // console.log(date2);
        //
        // $("#issuanceNum1_"+num).datepicker("option","maxDate", date2);
        // $("#issuanceNum2_"+num).datepicker("option","minDate", date1);


        // $("#issuanceNum1_"+num).datepicker({ //newStartDate datepicker
        // numberOfMonths: 1,
        // dateFormat: 'yy-mm-dd',
        // minDate: new Date(),
        //     onSelect: function(selected) {
        //       console.log(selected);
        //       $("#issuanceNum2_"+num).datepicker("option","minDate", selected)
        //     }
        // });
        // $("#issuanceNum2_"+num).datepicker({ //newEndDate datepicker
        //     numberOfMonths: 1,
        //     dateFormat: 'yy-mm-dd',
        //     onSelect: function(selected) {
        //       console.log(selected);
        //        $("#issuanceNum1_"+num).datepicker("option","maxDate", selected)
        //     }
        // });
      });

 });
    </script>
  </body>
</html>
