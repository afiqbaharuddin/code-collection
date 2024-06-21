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
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <title>Card</title>
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
                      <h5 class="content-header-title float-left pr-1 mb-0">CARD PREFIX</h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Card</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Card Prefix</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--  List Table -->

              <div class="card">
                <div class="card-header border-bottom">

                  <div class="d-flex justify-content-between">
                    <div class="card-title">
                      <h4>Card Prefix List</h4>
                    </div>

                    <?php
                    $create  = $this->RolePermission_Model->menu_master(6);

                    if ($create->Create == 1) { ?>
                    <div class="dt-action-buttons">
                      <button type="button" name="button"  tabindex="0" class="dt-button add-new btn btn-success" data-bs-toggle="modal" data-bs-target="#PrefixModal" style="margin-left:15px">
                        <span><i class="bx bx-plus me-0 me-lg-2"></i>
                        <span class="d-none d-lg-inline-block">Add Card Prefix</span>
                      </span>
                      </button>
                    </div>
                    <?php }  ?>
                  </div>

                <div class="card-datatable table-responsive">
                  <table class="datatables-users table border-top" id="cardprefixtable">
                    <thead style="background-color:#122620">
                      <tr>
                        <th  class="col-2" style="color:white">Card Type ID</th>
                        <th  class="col-2" style="color:white">Prefix Number</th>
                        <th  class="col-3" style="color:white">Card Name</th>
                        <th  class="col-3" style="color:white">Card Status</th>

                        <?php
                        $updateCard     = $this->RolePermission_Model->menu_master(6);

                        if ($updateCard->Update == 1) { ?>
                        <th  class="col-2" style="color:white">Action</th>
                        <?php }  ?>
                      </tr>
                    </thead>
                    <input type="hidden" name="loggerid" id="loggerid" value="">
                  </table>
                  </div>
                </div>
              </div>

                  <!-- Modal Add Card Prefix -->
                  <div class="modal fade" id="PrefixModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="addPrefixLabel">Add Card Prefix</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form  id="addCardForm" action="<?php echo base_url(); ?>cards/Cards/createCards" method="post" >
                          <div class="modal-body">
                            <div class="row">
                              <div class="col mb-3">
                                <label for="addPrefix" class="form-label bs-validation-prefixNumber">Prefix Number</label>
                                <!-- <span style="color:red">*</span> -->
                                <input type="hidden" name="cardId" value="">
                                <input type="text" id="addPrefix" class="form-control bs-validation-prefixNumber" name="PrefixNumber" placeholder="Enter Prefix Number" />
                                <!-- <div class="invalid-feedback">*This field is required. Please enter Prefix Number.</div> -->
                              </div>
                            </div>

                            <div class="row">
                              <div class="col mb-3">
                                <label for="addCardName" class="form-label bs-validation-cardName">Card Name</label>
                                <span style="color:red">*</span>
                                <input type="text" id="addCardName" class="form-control bs-validation-cardName" name="cardName" placeholder="Enter Card Name" required/>
                                <div class="invalid-feedback">*This field is required. Please enter Card Name.</div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col mb-3">
                                <label for="cardStatus" class="form-label bs-validation-status">Status</label>
                                <span style="color:red">*</span>
                                <select class="form-select  bs-validation-status" id="cardStatus" name="cardstatus" required>
                                  <option value="">Select Status</option>
                                  <?php foreach ($cardstatus as $row) { ?>
                                    <option value="<?php echo $row->StatusId; ?>"><?php echo $row->StatusName; ?></option>
                                  <?php } ?>
                                </select>
                                <div class="invalid-feedback">*This field is required. Please select Card Status.</div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary data-submit" style="background-color:#122620; border-color:#122620">Add</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- / Modal Add Card Prefix -->

                  <!-- Modal Edit Card Prefix -->
                  <div class="modal fade" id="editcard" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel2">Edit Card Prefix</h5>
                          <button
                            type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                          <div class="modal-body">
                          <form id="editCardForm" action="<?php echo base_url(); ?>cards/Cards/editCards" method="post">
                            <div class="row">
                              <div class="col mb-3">
                                <label for="nameSmall" class="form-label">Prefix Number</label>
                                <input type="text" class="form-control" value="" name="prefixnumber" id="prefixnumber" />
                              </div>
                            </div>

                            <div class="row">
                              <div class="col mb-3">
                                <label for="nameSmall" class="form-label">Card Name</label>
                                <input type="text" name="editcardname" id="editcardname" class="form-control" value="" />
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <input type="hidden" name="loggerid" id="loggerid" value="">
                            <input type="hidden" name="cardId" id="cardId" value="">

                            <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary data-submit" style="background-color:#122620; border-color:#122620">Edit</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                  <!-- / Modal Edit Card Prefix -->

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

      <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
  <?php echo $bottom; ?>

  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

  <script>
    $(document).ready(function(){
      $.fn.dataTable.ext.errMode = 'none';

      // card prefix datatable
      var cardprefixtable = $('#cardprefixtable').DataTable({
        columnDefs: [
          {targets: [-1], orderable: false}],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>cards/cards/cardprefixlisting",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
            }
        },
        "bDestroy": true,
      });
      cardprefixtable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if (json != null) {
          $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        }else {
          getToken();
        }
      });

      //  Add Card prefix form
      $("#addCardForm").unbind('submit').bind('submit', function(){ //input
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
            $('#PrefixModal').modal('hide');
            $("#addCardForm")[0].reset();
            cardprefixtable.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
            snack(data.message, 'success');
            confirmation();
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

    //Edit Card Prefix forms
    $("#editCardForm").unbind('submit').bind('submit', function() {
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
            $('#editcard').modal('hide');
            $("#editCardForm")[0].reset();
            cardprefixtable.ajax.reload();
            snack(data.message, 'success');
            // confirmation();
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

    function confirmation(){
      $.confirm({
        theme: 'dark',
        title: false,
        content: 'Do you want to add another card?    ',
        typeAnimated: true,
        buttons: {
          confirm: {
              text: 'Yes',
              action: function(){
                $('#PrefixModal').modal('show');
              }
          },
          cancel: {
              text: 'No',
              btnClass: 'btn-red',
              action: function(){
              }
          },
        }
      });
    };

      //ajax for carry id to edit form
      $('#cardprefixtable').on('click','.editCard',function() {
        var cardid   = $(this).data('cardid');
        var csrfHash = $('.txt_csrfname').val();
        $.ajax({
          url: "<?php echo base_url(); ?>cards/cards/cardDetails",
          type: "POST",
          data: {cardid:cardid, <?= $csrf['name']; ?> : csrfHash},
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);

            $('#loggerid').val(data.details.AppLoggerId);
            $('#editcardname').val(data.details.CardName);
            $('#prefixnumber').val(data.details.CardPrefix);
            $('#cardId').val(data.details.CardId);

            $('#editcard').modal('show');
          },
          error: function(xhr, status, error) {
            snack('Something went wrong. Please try again later','danger');
          },
        });
      });

      //jquery for toggle switch at Status
      $('#cardprefixtable').on('change', '.toggle', function()  {
        var num      = $(this).data('num');
        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();
        // alert("say");

        if($(this).is(":checked")){
          $('#toggle_'+num).val('1');
          $('#showstatuscard_'+num).html('<span class="badge bg-label-success"  style="margin-left:35px">Active</span>');
        } else{
          $('#toggle_'+num).val('2');
          $('#showstatuscard_'+num).html('<span class="badge bg-label-danger"  style="margin-left:35px">Inactive</span>');
        }

        var status     = $(this).val();
        var cardId     = $('#updatecardstatus_'+num).val();
        var loggerid   = $('#loggerid').val();

        $.ajax({
          type: "post",
          url: "<?php echo base_url(); ?>cards/cards/get_showstatus",
          data: {status: status, cardId:cardId, loggerid:loggerid,  <?= $csrf['name']; ?> : csrfHash},
          dataType: 'json',
          success: function(data)
          {
            $('.txt_csrfname').val(data.token);
          }
        });
      });
    });
  </script>
  </body>
</html>
