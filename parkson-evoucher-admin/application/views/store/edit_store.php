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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <title>Edit Store</title>
    <?php echo $header; ?>

    <style media="screen">
      .dataTables_scrollHeadInner{
        width: 100%!important;
      }
      .ui-datepicker {
          background: #333;
          border: 1px solid #555;
          color: #fff;
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
                      <h4 class="content-header-title float-left pr-1 mb-0">
                        <span class="text-muted fw-light">Forms/</span>EDIT STORE DETAILS
                      </h4>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb  breadcrumb-style1 p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Store</li>
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>store/Store/StoreList"><u>List of Stores</u></a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px;" >Edit Store Details</li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60 "><?php echo $details->StoreName ?></li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            <!--form edit store details-->
            <div class="row">
              <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                  <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                      <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#EditStore" aria-controls="navs-pills-editStore" aria-selected="true">
                        Edit Store
                      </button>
                    </li>
                    <li class="nav-item">
                      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-pos" aria-controls="navs-pills-pos" aria-selected="false">
                        List of POS
                      </button>
                    </li>
                    <li class="nav-item">
                      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-dutyList" aria-controls="navs-pills-dutyList" aria-selected="false">
                        Manager on Duty List
                      </button>
                    </li>
                  </ul>

                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="EditStore" role="tabpanel">
                      <div class="col-md">
                          <h5>Edit Store Details</h5>
                              <div class="row g-3s">

                                <form class="edit-store-details pt-0" id="EditStoreForm" action="<?php echo base_url(); ?>store/editstore/editstore" method="post">
                                  <div class="mb-3">
                                    <input type="hidden" name="storeid" value="<?php echo $details->StoreId ?>">
                                    <label class="form-label" for="edit-store-code">Store Code</label>
                                    <input type="text" class="form-control" id="editstorecode" placeholder="" value="<?php echo $details->StoreCode; ?>" name="storeCode" aria-label="" disabled  />
                                  </div>

                                  <div class="mb-3">
                                    <label class="form-label" for="edit-store-name">Store Name</label>
                                    <input type="text" id="editstorename" class="form-control" placeholder=""
                                        value="<?php echo $details->StoreName ?>" aria-label="" name="editstorename"/>
                                  </div>

                                  <!-- Remove card display -->
                                  <?php foreach ($removedcard as $removed): ?>
                                    <div class="row mb-3 ">
                                      <div class="col-md-3">
                                        <label for="" class="form-label mb-2">Removed Card</label>
                                        <input class="form-control mb-2" type="text" name="removedcard" value="<?php echo $removed->CardName ?>" disabled>
                                      </div>

                                      <div class="col-md-3">
                                        <label for="" class="form-label mb-2">Start Date</label>
                                        <input type="date" name="removestartdate" class="form-control mb-2" value="<?php echo $removed->StartDate ?>" disabled>
                                      </div>

                                      <div class="col-md-3">
                                        <label for="" class="form-label mb-2">End Date</label>
                                        <input type="date" name="removeenddate" class="form-control mb-2" value="<?php echo $removed->EndDate ?>" disabled>
                                      </div>

                                      <div class="col-md-3">
                                        <label for="" class="form-label mb-2">Action</label>
                                        <p class="text-danger mt-1">Removed</p>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                  <hr>
                                  <!-- end Remove card display -->

                                  <input type="hidden" name="totaloldcard" value="<?php echo count($cards); ?>">
                                  <?php $jj = 1; foreach ($cards as $card): ?>
                                    <div id="cardlist_<?php echo $card->CardId; ?>" >
                                      <div class="row">
                                        <div class="col-md-3">
                                          <label for="" class="form-label mb-2">Activated Card</label>
                                          <input type="text" name="oldcard_<?php echo $jj; ?>" value="<?php echo $card->CardName; ?>" class="form-control mb-2" disabled>
                                          <input type="hidden" name="oldcardid_<?php echo $jj; ?>" value="<?php echo $card->CardId; ?>">
                                          <input type="hidden" name="cardstoreid_<?php echo $jj; ?>" value="<?php echo $card->CardStoreId; ?>">
                                        </div>

                                        <div class="col-md-3">
                                          <label for="" class="form-label mb-2">Start Date</label>
                                          <?php if ($card->StartDate <= date('Y-m-d')) {
                                            $disablestart = 'disabled';
                                          }else {
                                            $disablestart = '';
                                          } ?>
                                          <input id="oldstartDate_<?php echo $jj; ?>" <?php echo $disablestart; ?> name="oldstartDate_<?php echo $jj; ?>" value="<?php echo $card->StartDate; ?>" class="form-control mb-2 oldstartDate_<?php echo $jj; ?>">
                                        </div>

                                        <div class="col-md-3">
                                          <label for="" class="form-label mb-2">End Date</label>
                                          <input id="oldendDate_<?php echo $jj; ?>" name="oldendDate_<?php echo $jj; ?>" value="<?php echo $card->EndDate; ?>" class="form-control mb-2 oldendDate_<?php echo $jj; ?>">
                                        </div>

                                        <div class="col-md-3">
                                          <label for="" class="form-label mb-2">Action</label>
                                          <div class="d-buttons">
                                            <button class="btn btn-primary confirmRemove" type="button" data-cardstore="<?php echo $card->CardStoreId; ?>" data-cardid="<?php echo $card->CardId; ?>" data-storeid="<?php echo $card->StoreId; ?>" style="background-color:#122620; border-color:#122620">Remove</button>

                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  <?php $jj++; endforeach; ?>

                                  <input type="hidden" name="loggerid" id="loggerid" value="<?php echo $details->AppLoggerId; ?>">

                                    <input type="hidden" name="totalcard" id="totalcard" value="1">
                                    <div id="show_card">
                                      <div id="addNewCard_1">
                                        <div class="row">
                                          <div class="col-md-3">
                                            <label for="" class="form-label mb-2">Activate New Card</label>
                                            <select class="form-control mb-2" name="activatecard_1">
                                              <option value="" >Select Card</option>
                                              <?php foreach ($addcarddb as $row)  {?>
                                                <option value="<?php echo $row->CardId; ?>"><?php echo $row->CardName; ?></option>
                                              <?php } ?>
                                            </select>
                                          </div>

                                          <div class="col-md-3">
                                            <label for="" class="form-label mb-2">Start Date</label>
                                            <input id="datepicker3" name="startDate_1" value="" class="form-control mb-2 startDate_">
                                          </div>

                                          <div class="col-md-3">
                                            <label for="" class="form-label mb-2">End Date</label>
                                            <input id="datepicker4" name="endDate_1" value="" class="form-control mb-2 endDate_">
                                          </div>

                                          <div class="col-md-3">
                                            <label for="" class="form-label mb-2">Action</label>
                                            <div class="d-buttons" id="addCard_1">
                                              <button class="btn btn-primary me-sm-3 me-1 addCard" type="button" name="button" style="background-color:#122620; border-color:#122620">
                                                <span>Add Line</span>
                                              </button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="mb-3" style="margin-top:15px;">
                                    <label class="form-label editStoreStatus" for="editstorestatus">Status</label>
                                    <select id="editstorestatus" name="editstorestatus" class="form-select editStoreStatus">
                                      <?php foreach ($storestatus_edit as $row) {
                                        if ($row->StoreStatusId == $details->StoreStatusId) {
                                          $select = 'selected';
                                        }else {
                                          $select = '';
                                        }
                                        ?>
                                        <option value="<?php echo $row->StoreStatusId; ?>" <?php echo $select; ?>><?php echo $row->StoreStatusName; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>

                                  <div class="mb-3">
                                  <label class="form-label" id="closedlabel" for="storecloseddate">Store Closed Date</label>
                                  <?php if ($details->StoreInactiveDate != null) {
                                    $closedate = $details->StoreInactiveDate;
                                  }else {
                                    $closedate = '';
                                  } ?>
                                    <input class="form-control closedDate" id="storecloseddate" type="date" name="storecloseddate" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" value="<?php echo $closedate; ?>"/>
                                  </div>

                                  <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                  <button type="button" id="edit-submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620">Submit</button>
                                  <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                </form>
                        </div>
                    </div>

                    <!-- POS List Tab -->
                    <div class="tab-pane fade" id="navs-pills-pos" role="tabpanel">
                      <div class="card-header">

                        <div class="d-flex justify-content-between">
                          <div class="">
                            <h4>List of POS</h4>
                          </div>
                          <div class="">
                            <button class="dt-button add-new btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#pos" style="margin-bottom: 20px;">
                              <span>
                                <i class="bx bx-plus me-0 me-lg-2"></i>
                                <span class="d-none d-lg-inline-block">Add New POS</span>
                              </span>
                            </button>
                          </div>
                        </div>

                        <!-- Pos datatable-->
                        <div class="card-datatable text-nowrap">
                          <input type="hidden" name="loggerid" id="loggerid" value="">
                          <table id="postable" class="table">
                            <thead style="background-color:#122620">
                              <tr>
                                <th style="color:white">POS Number</th>
                                <?php
                                $store     = $this->RolePermission_Model->menu_master(3);
                                
                                if ($store->Delete == 1) { ?>
                                  <th style="color:white">Action</th>
                                <?php  } ?>
                              </tr>
                            </thead>
                          </table>
                        </div>
                        <!-- / Pos datatable-->
                      </div>
                    </div>
                    <!-- / POS List Tab -->

                      <!-- Create POS  Modal -->
                        <div class="modal fade" id="pos" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Add New POS</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <form id="POSform" action="<?php echo base_url(); ?>store/POS/create" method="post">
                                <input type="hidden" name="storeid" value="<?php echo $POSId ?>">
                                <div class="row g-3">
                                  <div class="col-sm-4">
                                    <label for="nameSmall" class="form-label">POS Number</label><br>
                                    <input type="text" name="storecode" class="form-control" value="<?php echo $details->StoreCode ?>" readonly />
                                  </div>
                                  <div class="col-sm-7 pt-4">
                                    <input type="text" name="posNumber" class="form-control" placeholder="Enter POS Number" />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <button type="button" id="submitbtn" class="btn btn-primary" style="background-color:#122620; border-color:#122620">Add</button>
                              </div>
                            </form>
                            </div>
                          </div>
                        </div>

                    <!-- Manager on Duty Tab -->
                    <div class="tab-pane fade" id="navs-pills-dutyList" role="tabpanel">

                      <!--List of Manager on Duty-->
                      <div class="card-datatable table-responsive">
                        <table id="managertable" class="datatables table mb-0" style="width:100%">
                          <thead style="background-color:#122620;">
                            <tr>
                              <th class="col-2" style="color:white">Name</th>
                              <th class="col-1" style="color:white">ID</th>
                              <th class="col-2" style="color:white">Current Position</th>
                              <th class="col-2" style="color:white">Temporary Role</th>
                              <th class="col-1" style="color:white">Start Date</th>
                              <th class="col-1" style="color:white">End Date</th>
                              <th class="col-3" style="color:white">Status</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!-- / Manager on Duty Tab -->
                  </div>
                </div>
              </div>
              </div>
            </div>
            <!--/form create store-->
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

    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom;?>

    <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/forms-extras.js"></script>

    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tables-datatables-advanced.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script type="text/javascript">
      $(document).ready(function(){

//=======================================================================================================//
//Edit POS Number section query and Ajax

      //POS List datatable
      var postable = $('#postable').DataTable({
        columnDefs: [
            {targets: [-1], orderable: false},
              ],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "order": [[0, 'desc']],

        "ajax": {
            "url": "<?php echo base_url(); ?>store/editstore/poslisting/<?php echo $POSId; ?>",
            "type": "POST",
            "data": function(data) {
              var csrfHash  = $('.txt_csrfname').val();
              data.<?= $csrf['name']; ?> = csrfHash;
            }
          },
          "bDestroy": true,
        });
        postable.on('xhr.dt', function ( e, settings, json, xhr ) {
           $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);
        });

        $('#submitbtn').on('click', function(){
          $('#POSform').submit();
        });


        //  create POS form
        $("#POSform").unbind('submit').bind('submit', function() { //input
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
              $('#pos').modal('hide');
              $("#POSform")[0].reset();
              postable.ajax.reload(); //nama data table yang nak di refresh after add/process (kena tukar)
              snack(data.message, 'success');
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

      //remove pos number
      $("#postable").on('click','.removePos', function() {
        var posid     = $(this).data('posid');
        var storeid   = $(this).data('storeid');
        var loggerid  = $('#loggerid').val();
        var num       = $(this).data('num');
        var csrfHash  = $('.txt_csrfname').val();

        $.confirm({
          theme: 'dark',
          title: false,
          content: 'Do you want to remove this POS Number?',
          typeAnimated: true,
          buttons: {
            confirm: {
                text: 'Yes',
                action: function(){
                  $.ajax({
                    url: "<?php echo base_url(); ?>store/POS/removePos",
                    type: "POST",
                    data: {storeid:storeid,posid:posid,loggerid:loggerid, <?= $csrf['name']; ?> : csrfHash},
                    dataType: 'json',
                    success:function(data)
                    {
                      $('.txt_csrfname').val(data.token);
                      if (data.status == true) {
                        snack(data.message,'success');
                        // $(".removePos").remove();
                        postable.ajax.reload();
                      }else {
                        snack(data.message,'danger');
                      }
                    },
                    error: function (xhr, status, error) {
                      // snack('Something went wrong. Please try again later','danger');
                    },
                  });
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
    });

//================================================================================================================//
//Manager on Duty section query and ajax

        //Manager on Duty List datatable
        var managertable = $('#managertable').DataTable({
          columnDefs: [
              {targets: [-1], orderable: false},
                ],
          "sScrollXInner": "100%",
          "searching": true,
          "processing": true,
          "serverSide": true,
          "scrollX": true,
          "ajax": {
              "url": "<?php echo base_url(); ?>store/editstore/managerlisting/<?php echo $POSId ?>",
              "type": "POST",
              "data": function(data) {
                var csrfHash  = $('.txt_csrfname').val();
                data.<?= $csrf['name']; ?> = csrfHash;
              }
          },
          "bDestroy": true,
        });
        managertable.on('xhr.dt', function ( e, settings, json, xhr ) {
           $('.txt_csrfname').val(json.<?= $csrf['name']; ?>);

        });

        $("#closedlabel").hide();
        $("#storecloseddate").hide();
        var storestatus = <?php echo $details->StoreStatusId; ?>;
        if (storestatus == 2) {
          $("#closedlabel").show(400);
          $("#storecloseddate").show(400);
        }

        //edit user closed date field jquery
          $('#editstorestatus').on('change',function(){
            if(this.value == 2)
            {
              $("#closedlabel").show(400);
              $("#storecloseddate").show(400);
            }else
            {
              $("#closedlabel").hide(400);
              $("#storecloseddate").hide(400);
            }
          });

          $("#active1").hide();
          $("#inactive1").hide();
          $("#active2").hide();
          $("#inactive2").hide();
          $("#active3").hide();
          $("#inactive3").hide();
          $("#removeCard").hide();

          $(function(){
            $("#toggle1").change(function(){
              if($(this).is(":checked")){
                $("#active1").show();
                $("#inactive1").hide();
              }
              else
              {
                $("#active1").hide();
                $("#inactive1").show();
              }
            });

            $("#toggle2").change(function(){
              if($(this).is(":checked")){
                $("#active2").show();
                $("#inactive2").hide();
              }
              else
              {
                $("#active2").hide();
                $("#inactive2").show();
              }
            });

            $("#toggle3").change(function(){
              if($(this).is(":checked")){
                $("#active3").show();
                $("#inactive3").hide();
              }
              else
              {
                $("#active3").hide();
                $("#inactive3").show();
              }
            });
          });

          //jquery for toggle switch at status
          $('#managertable').on('change', '.toggle', function()  {
            var num = $(this).data('num');
            var csrfName = $('.txt_csrfname').attr('name');
            var csrfHash = $('.txt_csrfname').val();
            // alert("say");

            if($(this).is(":checked")){
              $('#toggle_'+num).val('4');
              $('#showmanagerstatus_'+num).html('<span class="badge bg-label-success">Enable</span>');
            } else{
              $('#toggle_'+num).val('5');
              $('#showmanagerstatus_'+num).html('<span class="badge bg-label-danger">Disable</span>');
            }

            var status         = $(this).val();
            var managerdutyId  = $('#modstatus_'+num).val();
            var loggerid       = $('#loggerid').val();

            // alert(status);

            $.ajax({
              type: "post",
              url: "<?php echo base_url(); ?>store/editstore/get_modstatus",
              data: {status: status, managerdutyId:managerdutyId, loggerid:loggerid,  <?= $csrf['name']; ?> : csrfHash},
              dataType: 'json',
              success: function(data)
              {
                $('.txt_csrfname').val(data.token);
              }
            });
          });

//==============================================================================================================================================//
//Edit Store Section Query and Ajax

          //Add activated card field jquery
          $('#show_card').on('click', '.addCard', function(){
             var totalcard = parseInt($("#totalcard").val());
             var remove = '<button class="btn btn-primary removeCard" data-card ="'+totalcard+'" style="background-color:#122620; border-color:#122620">Remove</button>'
             $('#addCard_'+totalcard).html(remove);
             totalcard = totalcard + 1;
             $("#totalcard").val(totalcard);

             addCard=
             '<div id="addNewCard_'+totalcard+'">'+
               '<div class="row">'+
                 '<div class="col-md-3">'+
                   '<label for="" class="form-label mb-2">Activate New Card</label>'+
                   '<select class="form-control mb-2" name="activatecard_'+totalcard+'">'+
                     '<option value="" >Select Card</option>'+
                     '<?php foreach ($addcarddb as $row)  {?>'+
                       '<option value="<?php echo $row->CardId; ?>"><?php echo $row->CardName; ?></option>'+
                     '<?php } ?>'+
                   '</select>'+
                 '</div>'+

                 '<div class="col-md-3">'+
                   '<label for="" class="form-label mb-2">Start Date</label>'+
                   '<input id="datepickerstart_'+totalcard+'" name="startDate_'+totalcard+'" value="" class="form-control mb-2 startDate_">'+
                 '</div>'+

                 '<div class="col-md-3">'+
                   '<label for="" class="form-label mb-2">End Date</label>'+
                   '<input id="datepickerend_'+totalcard+'" name="endDate_'+totalcard+'" value="" class="form-control mb-2 endDate_">'+
                 '</div>'+

                 '<div class="col-md-3">'+
                   '<label for="" class="form-label mb-2">Action</label>'+
                   '<div class="d-buttons" id="addCard_'+totalcard+'">'+
                     '<button class="btn btn-primary me-sm-3 me-1 addCard" data-cardnum="'+totalcard+'" type="button" name="button" style="background-color:#122620; border-color:#122620">'+
                       '<span>Add Line</span>'+
                     '</button>'+
                   '</div>'+
                 '</div>'+
               '</div>'+
             '</div>';

             $("#show_card").append(addCard);

             $('#show_card').on('click', '.removeCard', function(){
               var cardnum = $(this).data('card');
               // alert(cardnum);
               $("#addNewCard_"+cardnum).remove();
             });

             $("#datepickerstart_"+totalcard).datepicker({ //newStartDate datepicker
             numberOfMonths: 1,
             dateFormat: 'yy-mm-dd',
             minDate: new Date(),
                 onSelect: function(selected) {
                   $("#datepickerend_"+totalcard).datepicker("option","minDate", selected)
                 }
             });
             $("#datepickerend_"+totalcard).datepicker({ //newEndDate datepicker
                 numberOfMonths: 1,
                 dateFormat: 'yy-mm-dd',
                 onSelect: function(selected) {
                    $("#datepickerstart_"+totalcard).datepicker("option","maxDate", selected)
                 }
             });
           });

          //edit store form
          $("#EditStoreForm").unbind('submit').bind('submit', function()
          { //input
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
                  // window.location.reload();
                }
                else {
                  snack(data.message,'danger');
                }
              },
              error: function(xhr, status, error) {
                snack('Something went wrong. Please try again later','danger');
              },
            });
            return false;
        });

        $(".confirmRemove").on('click',function() {
          var cardstore = $(this).data('cardstore');
          var cardid    = $(this).data('cardid');
          var storeid   = $(this).data('storeid');
          var loggerid  = $('#loggerid').val();
          var csrfHash  = $('.txt_csrfname').val();

          $.confirm({
            theme: 'dark',
            title: false,
            content: 'Do you want to remove card?',
            typeAnimated: true,
            buttons: {
              confirm: {
                  text: 'Yes',
                  action: function(){
                    $.ajax({
                      url: "<?php echo base_url(); ?>store/EditStore/removeCard",
                      type: "POST",
                      data: {cardstore:cardstore,storeid:storeid,cardid:cardid,loggerid:loggerid, <?= $csrf['name']; ?> : csrfHash},
                      dataType: 'json',
                      success:function(data)
                      {
                        $('.txt_csrfname').val(data.token);
                        if (data.status == true) {
                          snack(data.message,'success');
                          $("#cardlist_"+cardid).remove();
                        }else {
                          snack(data.message,'danger');
                        }
                      },
                      error: function (xhr, status, error) {
                        // snack('Something went wrong. Please try again later','danger');
                      },
                    });
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
      });

      $('#edit-submit').on('click', function(){
        $("#EditStoreForm").submit();
      });

      $(".startDate_").datepicker({ //newStartDate datepicker
      numberOfMonths: 1,
      dateFormat: 'yy-mm-dd',
      minDate: new Date(),
          onSelect: function(selected) {
            console.log(selected);
            $(".endDate_").datepicker("option","minDate", selected)
          }
      });

      $(".endDate_").datepicker({ //newEndDate datepicker
          numberOfMonths: 1,
          dateFormat: 'yy-mm-dd',
          onSelect: function(selected) {
             $(".startDate_").datepicker("option","maxDate", selected)
          }
      });

      <?php $jj = 1; foreach ($cards as $card): ?>

          $("#oldstartDate_<?php echo $jj; ?>").datepicker({ //oldstartDate datepicker
          numberOfMonths: 1,
          dateFormat: 'yy-mm-dd',
          minoldDate: new Date(),
              onSelect: function(selected) {
                $("#oldendDate_<?php echo $jj; ?>").datepicker("option","minDate", selected)
              }
          });
          $("#oldendDate_<?php echo $jj; ?>").datepicker({ //oldendDate datepicker
              numberOfMonths: 1,
              dateFormat: 'yy-mm-dd',
              onSelect: function(selected) {
                 $("#oldstartDate_<?php echo $jj; ?>").datepicker("option","maxDate", selected)
              }
          });

          $(".oldendDate_<?php echo $jj; ?>").datepicker("option","minDate", "<?php echo date('Y-m-d', strtotime($card->StartDate.'+ 1 day')); ?>");
          $(".oldstartDate_<?php echo $jj; ?>").datepicker("option","maxDate", "<?php echo date('Y-m-d', strtotime($card->EndDate.'+ 1 day')); ?>");

      <?php $jj++; endforeach; ?>

    });
    </script>
  </body>
</html>
