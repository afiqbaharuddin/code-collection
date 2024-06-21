<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url();  ?>assets/"
  data-template="vertical-menu-template"
>
  <head>
    <title>Edit Campaign</title>
    <?php echo $header;?>
    <!-- Page CSS -->
    <link rel="stylesheet" href="<?php echo base_url();  ?>assets/vendor/css/pages/page-account-settings.css" />
  </head>

  <body style="background-color:#F1EDE3">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
      <?php echo $sidebar;?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <?php echo $topbar;?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="content-header-left col-12 mb-2 mt-1">
                  <div class="row breadcrumbs-top">
                    <div class="col-12">
                      <h5 class="content-header-title float-left pr-1 mb-0">
                        <span class="text-muted fw-light">Forms/</span>EDIT CAMPAIGN
                      </h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Campaign</li>
                          <li class="breadcrumb-item">
                            <a href="http://localhost/work-office/parkson-evoucher-admin/campaign/Campaign">List of Campaign</a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">List of Store</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url();?>campaign/EditCampaign/editcampaign"
                        ><i class="bx bx-edit me-1"></i>Edit Campaign</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" href="javascript:void(0);" style="background-color:#122620; border-color:#122620"
                        ><i class="bx bx-store-alt me-1"></i> List of Store</a
                      >
                    </li>
                  </ul>
                </div>
              </div>

              <div class="card">
                <div class="card-header border-bottom">

                  <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">

                      <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start
                        d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">

                        <div class="add-new-store" style="margin-right:15px">
                        <button class="dt-button add-new btn btn-success" tabindex="0"
                          type="button" style="margin-left:15px">
                          <span><i class="bx bx-plus me-0 me-lg-2"></i>
                            <span class="d-none d-lg-inline-block">Add New Store</span>
                          </span>
                        </button>
                         </div>
                       </div>
                    </div>

                <div class="card-datatable table-responsive">
                  <table class="datatables-stores table border-top" id="storeList">
                    <thead style="background-color:#122620">
                      <tr>
                        <th style="color:white">Store Code</th>
                        <th style="color:white">Name</th>
                        <th style="color:white">Date Joined</th>
                        <th style="color:white">Date Disjoined</th>
                        <th style="color:white">Status</th>
                        <th style="color:white">Action</th>
                      </tr>
                    </thead>

                    <tbody id="tbody" class="table-border-bottom-0">
                      <tr id="tr">
                        <td>001</td>
                        <td>Parkson Aeon Maluri</td>
                        <td>1-10-2022</td>
                        <td>1-11-2022</td>
                        <td>
                          <span class="badge bg-label-secondary">Inactive</span>
                        </td>
                        <td>
                          <a href="">
                          <div class="d-inline-block- text-nowrap" >
                            <button id="trash" class="btn btn-sm btn-icon" style="margin-right:5px">
                              <i class='bx bxs-trash'></i>
                            </button>Remove
                          </div>
                        </a>
                        </td>
                      </tr>

                      <tr>
                        <td>002</td>
                        <td>Parkson Mytown Cheras</td>
                        <td>1-12-2022</td>
                        <td>1-1-2023</td>
                        <td>
                          <span class="badge bg-label-success">Active</span>
                        </td>
                        <td>
                          <a href="">
                          <div class="d-inline-block- text-nowrap" >
                            <button class="btn btn-sm btn-icon" style="margin-right:5px">
                              <i class='bx bxs-trash'></i>
                            </button>Remove
                          </div>
                        </a>
                        </td>
                      </tr>

                      <tr>
                        <td>003</td>
                        <td>Parkson Sunway Velocity</td>
                        <td>1-2-2023</td>
                        <td>1-3-2023</td>
                        <td>
                          <span class="badge bg-label-secondary">Inactive</span>
                        </td>
                        <td>
                          <a href="">
                          <div class="d-inline-block- text-nowrap" >
                            <button class="btn btn-sm btn-icon" style="margin-right:5px">
                              <i class='bx bxs-trash'></i>
                            </button>Remove
                          </div>
                        </a>
                        </td>
                      </tr>

                      <tr>
                        <td>004</td>
                        <td>Parkson Pavilion</td>
                        <td>1-4-2023</td>
                        <td>1-5-2023</td>
                        <td>
                          <span class="badge bg-label-success">Active</span>
                        </td>
                        <td>
                          <a href="">
                          <div class="d-inline-block- text-nowrap" >
                            <button class="btn btn-sm btn-icon" style="margin-right:5px">
                              <i class='bx bxs-trash'></i>
                            </button>Remove
                          </div>
                        </a>
                        </td>
                      </tr>
                  </table>
                  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                  <script>

                    $(document).ready(function ()
                    {
                      //     $('#store').DataTable();
                  var table = $('#store').DataTable( {
                    columnDefs: [
                        {targets: [-1], orderable: false},
                      ],
                      "paging": false
                      });

                      var table =$('#storeList').DataTable();
                      $('#storeList tbody').on('click', '#trash', function(){
                        table
                        .row($(this).parents('#tr'))
                        .remove()
                        .draw();
                      });
                    });
                  </script>
                </div>


                <!-- Offcanvas to add new store -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddStore" aria-labelledby="offcanvasAddStoreLabel" style="width: 50%;">
                  <div class="offcanvas-header border-bottom">
                    <h6 id="offcanvasAddStoreLabel" class="offcanvas-title">Add New Store</h6>
                    <button
                      type="button"
                      class="btn-close text-reset"
                      data-bs-dismiss="offcanvas"
                      aria-label="Close"
                    ></button>
                  </div>

                  <div class="offcanvas-body mx-0 flex-grow-0">
                    <form class="add-new-user pt-0" id="addNewStoreForm" onsubmit="return false">
                      <div class="mb-3">
                        <label class="form-label" for="add-store-code">Store Code</label>
                        <input
                          type="text"
                          class="form-control"
                          id="add-store-code"
                          placeholder="E.g: 020"
                          name="storeCode"
                          aria-label="E.g: 020"
                        />
                      </div>

                      <!-- <div class="mb-3">
                        <label class="form-label" for="add-store-code">Store Code</label>
                        <select id="add-store-code" class="form-select">
                          <option>Choose Store</option>
                          <option value="123">123</option>
                          <option value="124">124</option>
                          <option value="125">125 </option>
                      </select>
                      </div> -->

                      <div class="mb-3">
                        <label class="form-label" for="add-store-name">Store Name</label>
                        <input
                          type="text"
                          id="add-store-name"
                          class="form-control"
                          placeholder="E.g: Parkson Melawati Mall"
                          aria-label="E.g: Parkson Melawati Mall"
                          name="storeName"
                        />
                      </div>

                      <!-- <div class="mb-3">
                        <label class="form-label" for="add-store-name">Store Name</label>
                            <select id="add-store-name" class="form-select">
                              <option>Choose Store</option>
                              <option value="melawati">Parkson Melawati Mall</option>
                              <option value="pavilion">Parkson Elite Pavilion</option>
                              <option value="maluri">Parkson Aeon Maluri</option>
                          </select>
                      </div> -->

                      <div class="mb-4">
                        <label class="form-label" for="select2MultipleCard">Card</label>
                            <select id="select2MultipleCard" class="select2 form-select select2-hidden-accessible" multiple=""
                            data-select2-id="select2MultipleCard" tabindex="-1" aria-hidden="true">
                                <option value="bonuslink" >Bonuslink</option>
                                <option value="mastercard" >Mastercard</option>
                                <option value="visa" >Visa</option>
                                <option value="goldCard" >Parkson Gold Card</option>
                                <option value="silverCard" >Parkson Silver Card</option>
                            </select>
                      </div>

                      <div class="mb-4">
                        <label class="form-label" for="add-store-status">Status</label>
                        <select id="add-store-status" class="form-select">
                          <option>Choose Status</option>
                          <option value="open">Open</option required>
                          <option value="close">Close</option required>
                        </select>
                      </div>

                      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit"style="background-color:#122620; border-color:#122620"
                      >Submit</button>
                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                  </div>
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

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/js/pages-account-settings-security.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/modal-enable-otp.js"></script>


  </body>
</html>
