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

  <title>Voucher Issuance</title>
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
            <h5 class="content-header-title float-left pr-1 mb-0">
              <span class="text-muted fw-light">Forms/</span>VOUCHER ISSUANCE
            </h5>
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb p-0 mb-0">
                <li class="breadcrumb-item">
                  <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                    <i class="bx bx-home-alt"></i>
                  </a>
                </li>
                <li class="breadcrumb-item">Vouchers</li>
                <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Voucher Issuance</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Manual Issuance Form -->
    <form class="card-body needs-validation" id="issuancevoucherForm" action="<?php echo base_url(); ?>voucher/IssuanceVoucher/manualIssuance" method="post">
      <?php if ($issuanceValidation->StoreVoucherIssuanceCheck == 1 && ($issuanceValidation->StoreVoucherIssuanceDate <= date('Y-m-d') && date('Y-m-d') <= $issuanceValidation->StoreVoucherIssuanceDateEnd)){ ?>
      <div class="card mb-4" id="salesDetails">
        <div class="card-body needs-validation">

        <input type="hidden" name="userroleid" id="userroleid" value="<?php if(isset($data->Role)){ echo $data->Role; } ?>">
        <input type="hidden" name="voucherissuanceid">

        <div class="divider">
          <div class="divider-text"><h6>Sales Details</h6></div>
        </div>
        <h6 class="fw-normal">1. Insert Sales Details</h6>
        <div class="row g-3">

        <div class="col-md-6">
          <?php if (isset($data->UserRoleId)) {
             if($data->UserRoleId == 1) { ?>
              <label class="form-label" for="multicol-storecode">Store Code (Admin)</label>
                <select class="form-select" id="storecodeadmin" name="storecodeadmin" required>
                  <option value="">Select Store</option>
                  <?php foreach ($adminstore as $row) { ?>
                    <option value="<?php echo $row->StoreCode; ?>"><?php echo $row->StoreCode." - ".$row->StoreName; ?></option>
                  <?php } ?>
                </select>
            <?php } else { ?>

            <label class="form-label" for="multicol-storecode">Store</label>
            <input type="hidden" name="storecodeadmin" value="<?php echo $data->StoreCode; ?>">
            <input class="form-control firstform" type="text" value="<?php echo $data->StoreCode; ?> - <?php echo $data->StoreName; ?>" id="storecodeuser" disabled>
          <?php } }else {
            if ($Role == 'Super Admin') { ?>
              <label class="form-label" for="multicol-storecode">Store Code (Admin)</label>
                <select class="form-select" id="storecodeadmin" name="storecodeadmin" size="" required>
                  <option value="">Select Store</option>
                  <?php foreach ($adminstore as $row) { ?>
                    <option value="<?php echo $row->StoreCode; ?>"><?php echo $row->StoreCode." - ".$row->StoreName; ?></option>
                  <?php } ?>
                </select>
            <?php }else { ?>
              <label class="form-label" for="multicol-storecode">Store</label>
              <input class="form-control firstform" type="text" value="Not defined" id="storecodeuser" disabled>
            <?php } ?>
          <?php } ?>
        </div>

        <div class="col-md-6">
          <label class="form-label" for="pos">POS Number</label>
          <select class="form-select posField" id="pos" name="pos" required>
            <option value="">Select POS Number</option>
            <?php foreach ($posissuance as $row) { ?>
              <option value="<?php echo $row->POSNumber; ?>"><?php echo $row->POSNumber; ?></option>
            <?php } ?>
          </select>
          <div class="invalid-feedback firstform">*This field is required. Please enter Voucher Value.</div>
        </div>

        <div class="col-md-6">
          <label for="html5-datetime-local-input" class="form-label">Receipt Date</label>
          <div class='input-group date col-md-12' id='date'>
            <input type="date" class="form-control firstform" name="date" value="<?php echo date('Y-m-d');?>" readonly>
          </div>
        </div>

        <div class="col-md-6">
          <label for="html5-datetime-local-input" class="form-label">Receipt Time</label>
          <div class='input-group date col-md-12' id='time'>
            <input type="time" class="form-control firstform" name="time" value="<?php echo date('Y-m-d');?>"/>
          </div>
        </div>

      <?php if($data->UserRoleId == 1) { ?>
        <div class="col-md-6">
          <label class="form-label">Receipt Number</label>
            <div class="input-group">
              <div class="col-sm-1">
                <strong><label style="margin-top:5px; margin-left:5px">T</label></strong>
              </div>
              <input class="form-control" type="text" name="receiptstorecode" id="receiptstorecode" maxlength="6" readonly/>
                <div class="col-sm-1">
                  <strong><label style="margin-top:5px; margin-left:18px">-</label></strong>
                </div>
                <input class="form-control" type="text" name="receiptposnumber" id="receiptposnumber" maxlength="3" readonly/>
                <input class="form-control" type="text" name="receiptNumber" id="receiptNumber" maxlength="9" style="width:30%" dir='rtl'/>
            </div>
        </div>
      <?php } else { ?>
        <div class="col-md-6">
          <label class="form-label">Receipt Number</label>
            <div class="input-group">
              <div class="col-sm-1">
                <strong><label style="margin-top:5px; margin-left:5px">T</label></strong>
              </div>
              <input class="form-control" type="text" name="receiptstorecode" id="receiptstorecode" maxlength="6" value="000<?php echo $data->StoreCode ?>" readonly/>
                <div class="col-sm-1">
                  <strong><label style="margin-top:5px; margin-left:18px">-</label></strong>
                </div>
                <input class="form-control" type="text" name="receiptposnumber" id="receiptposnumber" maxlength="3" readonly/>
                <input class="form-control" type="text" name="receiptNumber" id="receiptNumber" maxlength="9" style="width:30%" dir='rtl'/>
            </div>
        </div>
      <?php } ?>

      <?php if ($campaignValidation->CampaignValidationCheck == 'N' || $campaignValidation->CampaignValidationCheck == 'Y' && date('Y-m-d') < $campaignValidation->CampaignValidationDate): ?>
        <div class="col-md-6">
          <label class="form-label" for="redeemtype">Redeem Type</label>
          <select class="form-select redeemField firstform" id="redeemtypee" name="redeemtype" required>
            <!-- <option value="0">Select Redeem Type</option> -->
            <?php foreach ($issuanceredeem as $row) { ?>
              <option value="<?php echo $row->RedeemTypeId; ?>"><?php echo $row->RedeemTypeName; ?></option>
            <?php } ?>
          </select>
        </div>
      <?php endif; ?>

      <?php if ($campaignValidation->CampaignValidationCheck == 'Y' && $campaignValidation->CampaignValidationDate <= date('Y-m-d')): ?>
        <div class="col-md-6">
          <label class="form-label campaignField" id="campaignlabel" for="bs-validation-campaignType">Campaign</label>
          <select class="form-select campaignField firstform" id="campaign" name="campaign">
            <option value="">Select Campaign</option>
            <?php foreach ($campaign as $row) { ?>
              <option value="<?php echo $row->CampaignId; ?>"><?php echo $row->CampaignName; ?></option>
            <?php } ?>
          </select>
        </div>
      <?php endif; ?>
        </div>

        <div class="pt-4">
          <button type="button"  class="btn btn-primary me-sm-3 me-1" id="submitDetails" style="background-color:#122620; border-color:#122620" >Confirm</button>
          <button type="reset" class="btn btn-label-secondary resetform" id="resetform">Reset</button>
        </div>
      </div>
      </div>

    <div class="card mb-4" id="voucherRequest">
      <div class="card-body" >
        <div class="divider">
          <div class="divider-text"><h6>Voucher Request</h6></div>
        </div>
        <h6 class="fw-normal">2. Insert Voucher Request Details</h6>
        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label" for="multicol-first-name">Total Sales(MYR)</label>
            <input type="text" class="form-control" id="sno" name="totalsales" class="form-control totalsales" placeholder=""  onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  event.charCode == 46 || event.charCode == 0 ">
          </div>

          <div class="col-md-6">
            <label class="form-label" for="multicol-cosmeticSales">Cosmetic Sales (MYR)</label>
            <input type="text" class="form-control" id="sno" name="cosmeticsale" value="0"  onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||  event.charCode == 46 || event.charCode == 0 ">
          </div>
        </div>

        <input type="hidden" name="totalrow" id="totalrow" value="1">

        <div id="show_value">
          <div id="addVoucher_1" class="addvoucherrow">
            <div class="row g-3 pt-4">

              <div class="col-md-3">
                <label class="form-label mb-2" for="multicol-voucherType">Voucher Type</label>
                <select class="form-select voucherType" id="vouchertype1"  name="vouchertype1" required>
                  <option value="">Select Voucher Type</option>
                  <?php foreach ($voucherdb as $row) { ?>
                    <option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="form-label mb-2" for="multicol-last-name">Voucher Value</label>
                 <select class="form-select" id="vouchervalue1" name="vouchervalue1" required>
                 </select>
              </div>

              <div class="col-md-3">
                <label class="form-label mb-2" for="multicol-quantity">Quantity</label>
                <input type="text" id="multicol-quantity" class="form-control dob-picker quantity-value" name="quantity1"/>
              </div>

              <div class="col-md-3">
                <label class="form-label mb-2" for="multicol-action">Action</label>
                <div class="d-buttons" id="addVoucherField_1">
                  <button class="btn btn-primary me-sm-3 me-1 addVoucherField" data-rownum="1" type="button" style="background-color:#122620; border-color:#122620">
                    <span>Add Line<span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-4">
          <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <button type="button" id="submitbtn" class="btn btn-primary me-sm-3 me-1 issuance-form" style="background-color:#122620; border-color:#122620">
              <span class="spinner-border me-1" id="spinner_border" role="status" aria-hidden="true"></span>
              Submit
            </button>
          <button type="reset" class="btn btn-label-secondary" id="cancelbtn">Cancel</button>
        </div>
      </div>
    </div>
    <?php } else{ ?>
        <div class="card text-center mb-3">
          <div class="card-body">
            <h5 class="card-title">This Store is not allowed to Issued Voucher</h5>
            <p class="card-text">Please Check with your Admin and enable for Store Voucher Issuance </p>
          </div>
        </div>
      <?php } ?>
      <input type="hidden" name="inputvalidation" id="inputvalidation" value="no">
    </form>
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

  <!-- Form Validation Script-->
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>
  <!-- / Form Validation Script-->

  <script src="<?php echo base_url(); ?>assets/vendor/libs/flatpickr/flatpickr.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/form-validation.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/form-layouts.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <script>

      $(document).ready(function(){

        $('#storecodeadmin').on('change', function()  {
          var storecode = $(this).val();
          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_pos",
            data: {storecode: storecode, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('#pos').html(data.result);
              get_selected_pos();
            }
          });
          <?php if ($campaignValidation->CampaignValidationCheck == 'Y' && $campaignValidation->CampaignValidationDate <= date('Y-m-d')): ?>
            get_campaign(storecode);
          <?php endif; ?>
        });

        function get_campaign(storecode){
          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_campaign",
            data: {storecode: storecode, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('#campaign').html(data.result);
              get_voucher_type();
            }
          });
        }

        $('#campaign').on('change', function(){
          get_voucher_type();
        });

        function get_voucher_type(){
          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();
          var campaign = $('#campaign').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_vouchertype",
            data: {campaign: campaign, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('.voucherType').html(data.result);
              get_value();
            }
          });
        }

        function get_voucher_type_click(num){
          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();
          var campaign = $('#campaign').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_vouchertype",
            data: {campaign: campaign, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('#vouchertype'+num).html(data.result);
              value(num);
            }
          });
        }

        $('#vouchertype1').on('change', function()  {
          var vouchertype = $(this).val();
          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_value",
            data: {vouchertype: vouchertype, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('#vouchervalue1').html(data.result);
            }
          });
        });

        $('#removeField').hide();
        $('#spinner_border').hide();

        $('#submitbtn').on('click', function(){
          $('#issuancevoucherForm').submit();
          $('#spinner_border').show();
        });

        // numbers only field
        $(".quantity-value").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        $("#receiptNumber").on('input', function(e){
          $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        function setTwoNumberDecimal(event) {
          this.value = parseFloat(this.value).toFixed(2);
        }

        //mannual issuance form
        $("#issuancevoucherForm").unbind('submit').bind('submit', function() { //input
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
              if (data.message == 'Receipt number valid') {
                $("#voucherRequest").show(400);
                $('#inputvalidation').val('yes');
              }else {
                snack("Succesfully Issued", 'success');
                window.location ='<?php echo base_url()?>voucher/IssuanceVoucher/list/'+ data.successreprint;
              }
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

      $('#redeemtypee').on('change',function(){
        if([this.value == 1 ]&&[this.value == 2])
        {
          $("#bs-validation-campaignType").hide(400);
          $("#campaignlabel").hide(400);
        }else {
          $("#bs-validation-campaignType").show(400);
          $("#campaignlabel").show(400);
        }
      });

      $("#voucherRequest").hide(400);
      $('#submitDetails').on('click', function() {
        confirmation();
      });

      function confirmation(){
      $.confirm({
        theme: 'dark',
        title: false,
        content: 'Are you sure want to continue?',
        typeAnimated: true,
        buttons: {
          confirm: {
            text: 'Yes',
            action : function(){
              $('#issuancevoucherForm').submit();
            }
          },
          cancel: {
            text: 'No',
            btnClass: 'btn-red',
            action: function(){
              snack("Sales Details confirmation cancelled", 'danger');
              }
            },
            },
          })
        };

      $('#resetform').click(function(){
        $('#inputvalidation').val("no");
        $("#voucherRequest").hide(400);
        $(".campaignField").show();
        $("#issuancevoucherForm")[0].reset();
        $('.addvoucherrow').reset();
      });

      $('#cancelbtn').click(function(){
        $("#voucherRequest").hide(400);
        $('#inputvalidation').val("no");
      });

      $('#show_value').on('click','.addVoucherField',function() {
        var num = parseInt($(this).data('rownum')) + 1;
        var totalrow = parseInt($("#totalrow").val());
        var remove = '<button class="btn btn-primary removeField" data-rownum="'+totalrow+'" style="background-color:#122620; border-color:#122620">Remove</button>';
        $('#addVoucherField_'+totalrow).html(remove);
        totalrow = totalrow + 1;
        $("#totalrow").val(totalrow);

        addRow=
        '<div id="addVoucher_'+totalrow+'" class="addvoucherrow">'+
          '<div class="row g-3 pt-4">'+
            '<div class="col-md-3">'+
              '<label class="form-label mb-2" for="multicol-voucherType">Voucher Type</label>'+
              '<select class="form-select voucherType" data-id="'+totalrow+'" id="vouchertype'+totalrow+'" name="vouchertype'+totalrow+'" required>'+
                '<option value="">Select Voucher Type</option>'+
                '<?php foreach ($voucherdb as $row) { ?>'+
                  '<option value="<?php echo $row->VoucherTypeId; ?>"><?php echo $row->VoucherName; ?></option>'+
                '<?php } ?>'+
              '</select>'+
            '</div>'+

            '<div class="col-md-3">'+
              '<label class="form-label mb-2" for="multicol-last-name">Voucher Value</label>'+
              '<select class="form-select vouchervalue" data-id="'+totalrow+'" id="vouchervalue'+totalrow+'" name="vouchervalue'+totalrow+'" required>'+
              '</select>'+
            '</div>'+

            '<div class="col-md-3">'+
              '<label class="form-label mb-2" for="multicol-quantity">Quantity</label>'+
              '<input type="text" id="multicol-quantity" class="form-control dob-picker" name="quantity'+totalrow+'">'+
            '</div>'+

            '<div class="col-md-3">'+
              '<label class="form-label mb-2" for="multicol-action">Action</label>'+
              '<div class="d-buttons" id="addVoucherField_'+totalrow+'">'+
                '<button class="btn btn-primary me-sm-3 me-1 addVoucherField" data-rownum="'+totalrow+'" type="button" style="background-color:#122620; border-color:#122620">'+
                  '<span>Add Line<span>'+
                '</button>'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>';

        $('#show_value').append(addRow);

        $('#vouchertype'+totalrow).on('change', function()  {
          var voucherTypenew = $(this).val();

          var csrfName = $('.txt_csrfname').attr('name');
          var csrfHash = $('.txt_csrfname').val();

          $.ajax({
            type: "post",
            url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_value_new_row",
            data: {voucherTypenew: voucherTypenew, [csrfName]: csrfHash},
            dataType: 'json',
            success: function(data)
            {
              $('.txt_csrfname').val(data.token);
              $('#vouchervalue'+totalrow).html(data.result);
            }
          });
        });

        <?php if ($campaignValidation->CampaignValidationCheck == 'Y' && $campaignValidation->CampaignValidationDate <= date('Y-m-d')): ?>
          get_voucher_type_click(num);
        <?php endif; ?>
      });

      $('#show_value').on('click','.removeField',function() {
        var rownum = $(this).data('rownum');
        $("#addVoucher_"+rownum).remove();
      });

      $("#pos").change(function(){
        get_selected_pos();
      });

      function get_selected_pos(){
        var value = $("#pos").val().substr($("#pos").val().length - 3);
        $('#receiptposnumber').val(value);
      }

      $("#storecodeadmin").change(function(){
        var value = $("#storecodeadmin option:selected");
        $("#receiptstorecode").val('000' + value.text().substr($("#storecodeadmin option:selected").length - 1,3));
      });

      $("#storecodeuser").change(function(){
        var value = $(this).val();
        $('#receiptstorecode').val('000'+ value);
      });

      function get_value(){
        var total = $('#totalrow').val();

        total = parseInt(total) + 1;

        for (var i = 1; i < total; i++) {
          value(i);
        }
      }

      function value(num){
        var voucherTypenew = $('#vouchertype'+num).val();

        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();

        $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>voucher/IssuanceVoucher/get_value_new_row",
          data: {voucherTypenew: voucherTypenew, [csrfName]: csrfHash},
          dataType: 'json',
          success: function(data)
          {
            $('.txt_csrfname').val(data.token);
            $('#vouchervalue'+num).html(data.result);
          }
        });
      }
    });
      </script>
    </body>
</html>
