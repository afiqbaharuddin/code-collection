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
    <title>Edit Campaign</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
      <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.css" /> -->

    <?php echo $header; ?>
  </head>

  <body style="background-color:#F1EDE3">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

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
                      <h5 class="content-header-title float-left pr-1 mb-0">
                        <span class="text-muted fw-light">Forms/</span>EDIT CAMPAIGN
                      </h5>
                      <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                          <li class="breadcrumb-item">
                            <a href="http://localhost/work-office/parkson-evoucher-admin/dashboard/Dashboard">
                              <i class="bx bx-home-alt"></i>
                            </a>
                          </li>
                          <li class="breadcrumb-item">Campaign</li>
                          <li class="breadcrumb-item">
                            <a href="http://localhost/work-office/parkson-evoucher-admin/campaign/Campaign">List of Campaign</a>
                          </li>
                          <li class="breadcrumb-item active" style="margin-bottom:10px; color:#D6AD60">Edit Campaign</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xl-6">
                    <div class="nav-align-top mb-4">
                      <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                          <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#editCampaign-pills"
                            aria-controls="editCampaign-pills"
                            aria-selected="true"
                            id="editCampaign-tab"
                          >
                            Edit Campaign
                          </button>
                        </li>
                        <li class="nav-item">
                          <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#storeList-pills"
                            aria-controls="storeList-pills"
                            aria-selected="false"
                            id="storeList-tab"
                          >
                            List of Stores
                          </button>
                        </li>
                        <li class="nav-item">
                          <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#cardList-pills"
                            aria-controls="cardList-pills"
                            aria-selected="false"
                            id="cardList-tab"
                          >
                            List of Cards
                          </button>
                        </li>
                      </ul>

                      <div class="tab-content" style="width:200%">
                        <div class="tab-pane fade show active" id="editCampaign-pills" role="tabpanel">
                            <h5 class="card-header">Campaign details</h5>
                            <form class="card-body" >

                                  <form class="edit-campaign pt-0" id="editCampaignForm" onsubmit="return false">
                                    <div class="mb-3">
                                      <label class="form-label" for="edit-campaign-name" style="margin-top:20px">Campaign Name</label>
                                      <input
                                        type="text"
                                        class="form-control"
                                        id="edit-campaign-name"
                                        placeholder="E.g: Raya Sales Campaign"
                                        name="editCampaignName"
                                        aria-label="Raya Sales Campaign"
                                        disabled=""
                                        value="Year End Sales Campaign"
                                      />
                                    </div>

                                    <div class="mb-4">
                                      <label class="form-label" for="edit-campaign-type">Campaign Type</label>
                                      <select id="edit-campaign-type" class="form-select" disabled>
                                        <option>Select type</option>
                                        <option value="season" selected>Voucher's Day</option>
                                        <option value="selected" >Default Campaign</option>
                                      </select>
                                    </div>

                                    <div class=" mb-4">
                                    <label for="selectpickerMultiple" class="form-label">Voucher Type</label>
                                    <div class="dropdown bootstrap-select show-tick w-100">
                                      <select id="selectpickerMultiple" class="selectpicker w-100" data-style="btn-default" multiple="" data-icon-base="bx" data-tick-icon="bx-check text-primary">
                                        <option>Select type</option>
                                        <option value="gift" >Gift Voucher</option>
                                        <option value="promotion" selected>Promotion Voucher</option>
                                        <option value="discount" selected>Discount Voucher</option>
                                      </select>
                                      </div>
                                    </div>

                                    <!-- <div class=" mb-4">
                                    <label for="selectpickerMultiples" class="form-label">Store</label>
                                    <div class="dropdown bootstrap-select show-tick w-100">
                                      <select id="selectpickerMultiples" class="selectpicker w-100" data-style="btn-default" multiple="" data-icon-base="bx" data-tick-icon="bx-check text-primary">
                                        <option value="all" selected>All Parkson Stores</option>
                                        <option value="pavilion">Parkson Pavilion Kuala Lumpur</option>
                                        <option value="mytown">Parkson MyTown Cheras</option>
                                        <option value="velocity">Parkson Sunway Velocity</option>
                                        <option value="melawati">Parkson Melawati Mall</option>
                                      </select>
                                      </div>
                                    </div> -->

                                    <div class="mb-3">
                                      <label class="form-label" for="edit-campaign-start">Start Date</label>
                                      <input
                                        type="date"
                                        id="edit-campaign-start"
                                        class="form-control"
                                        placeholder="E.g: 3/15/2023"
                                        aria-label="3/15/2023"
                                        name="editStartDate"
                                        value="2022-10-02"
                                      />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="edit-campaign-end">End Date</label>
                                      <input
                                        type="date"
                                        id="edit-campaign-end"
                                        class="form-control"
                                        name="editEndDate"
                                        value="2022-12-31"
                                      />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="edit-campaign-status">Status</label>
                                      <select id="edit-campaign-status" class="form-select">
                                        <option>Select Status</option>
                                        <option value="active" selected>Active</option>
                                        <option value="inactive" id="inactive">Inactive</option>
                                      </select>
                                      <div id="inactive" class="status" style="display:none"></div>
                                      <div id="active" class="status" style="display:none"></div>
                                    </div>

                                    <div class="mb-3 inactive-clicked" >
                                        <label class="form-label" for="inactive-date" id="date-label">Inactive Date</label>
                                        <input type="date" id="inactive-date" class="form-control" placeholder="E.g: 3/3/2022"
                                               aria-label="3/3/2022" name="inactiveDate" value="05-11-2022"
                                               />

                                          <label class="form-label" for="inactive-time" id="time-label">Time</label>
                                          <input type="text" id="inactiveTime" class="form-control" placeholder="E.g: 05.00pm"
                                                 aria-label="05.00pm" name="inactiveTime" value=""
                                                 />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="remark-status">Remark</label>
                                      <textarea id="remarkStatus" class="form-control" rows="3"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                  </form>
                        </div>

                        <div class="tab-pane fade" id="storeList-pills" role="tabpanel">
                              <div class="card-header border-bottom">

                                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">

                                    <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start
                                      d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">

                                      <div class="add-new-store" style="margin-right:15px">
                                      <button class="dt-button add-new btn btn-success" tabindex="0"
                                        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCampaignStore" style="margin-left:15px">
                                        <span><i class="bx bx-plus me-0 me-lg-2"></i>
                                          <span class="d-none d-lg-inline-block">Add New Store</span>
                                        </span>
                                      </button>
                                       </div>
                                     </div>
                                  </div>

                              <!--List of Store page table-->
                              <div class="card-datatable table-responsive">
                                <table class="datatables-stores table border-top" id="store">
                                  <thead style="background-color:#122620">
                                    <tr>
                                      <th style="color:white">Store Code</th>
                                      <th style="color:white">Name</th>
                                      <th style="color:white">Join Date</th>
                                      <th style="color:white">Disjoin Date</th>
                                      <th style="color:white">Status</th>
                                      <th style="color:white">Action</th>
                                    </tr>
                                  </thead>

                                  <tbody class="table-border-bottom-0" id="tbody">
                                    <tr id="tr">
                                      <td>001</td>
                                      <td>Parkson Aeon Maluri</td>
                                      <td>1-10-2022</td>
                                      <td>1-11-2022</td>
                                      <td>
                                        <span class="badge bg-label-secondary">Inactive</span>
                                      </td>
                                      <td>
                                        <div class="d-inline-block- text-nowrap"  >
                                          <button id="trash" class="btn btn-sm btn-icon" style="margin-right:5px">
                                            <i class='bx bxs-trash'></i>
                                          </button>Remove
                                        </div>
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
                              </div>


                              <!-- Offcanvas to add new store -->
                              <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCampaignStore" aria-labelledby="offcanvasAddStoreLabel" style="width: 50%;">
                                <div class="offcanvas-header border-bottom">
                                  <h6 id="offcanvasAddStoreLabel" class="offcanvas-title">Add New Store for Campaign</h6>
                                  <button
                                    type="button"
                                    class="btn-close text-reset"
                                    data-bs-dismiss="offcanvas"
                                    aria-label="Close"
                                  ></button>
                                </div>

                                <div class="offcanvas-body mx-0 flex-grow-0">
                                  <form class="add-new-store pt-0" id="addNewStoreForm" onsubmit="return false">
                                    <div class="mb-3">
                                      <label class="form-label" for="add-store-code selectpcikerMultiple">Store Code</label>
                                      <div class="dropdown bootstrap-select show-tick w-100">
                                        <select class="form-control selectpicker w-100" data-style="btn-default" multiple=""  data-icon-base="bx"
                                                data-tick-icon="bx-check text-primary" name="" id="storecode">
                                          <option value="Parkson Klang Parade">123</option>
                                          <option value="Parkson Melawati Mall">124</option>
                                          <option value="Parkson KLCC">125</option>
                                        </select>
                                      <!-- <input
                                        type="text"
                                        class="form-control"
                                        id="add-store-code"
                                        placeholder="E.g: 020"
                                        name="storeCode"
                                        aria-label="E.g: 020"
                                      /> -->
                                    </div>
                                  </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="add-store-name">Store Name</label>
                                        <input type="text" id="storename" class="form-control" disabled />
                                      <!-- <input
                                        type="text"
                                        id="add-store-name"
                                        class="form-control"
                                        placeholder="E.g: Parkson Melawati Mall"
                                        aria-label="E.g: Parkson Melawati Mall"
                                        name="storeName"
                                      /> -->
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="join-date">Join Date</label>
                                      <input
                                        type="date"
                                        id="join-date"
                                        class="form-control"
                                        placeholder=""
                                        aria-label=""
                                        name="joinDate"
                                      />
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label" for="disjoin-date">Disjoin Date</label>
                                      <input
                                        type="date"
                                        id="disjoin-date"
                                        class="form-control"
                                        placeholder=""
                                        aria-label=""
                                        name="disjoinDate"
                                      />
                                    </div>

                                    <div class="mb-4">
                                      <label class="form-label" for="add-store-status">Status</label>
                                      <select id="add-store-status" class="form-select">
                                        <option>Choose Status</option>
                                        <option value="active">Active</option required>
                                        <option value="inactive">Inactive</option required>
                                      </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620"
                                    >Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                        </div>

                        <!--card tab page-->
                        <div class="tab-pane fade" id="cardList-pills" role="tabpanel">
                          <div class="card-header border-bottom">

                            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">

                                <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start
                                  d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">

                                  <div class="add-new-store" style="margin-right:15px">
                                  <button class="dt-button add-new btn btn-success" tabindex="0"
                                    type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCard" style="margin-left:15px">
                                    <span><i class="bx bx-plus me-0 me-lg-2"></i>
                                      <span class="d-none d-lg-inline-block">Add New Card</span>
                                    </span>
                                  </button>
                                   </div>
                                 </div>
                              </div>


                          <!--Table List of Card-->
                          <div class="card-datatable table-responsive">
                            <table class="datatables-stores table border-top" id="card">
                              <thead style="background-color:#122620">
                                <tr>
                                  <th style="color:white">Card Name</th>
                                  <th style="color:white">Join Date</th>
                                  <th style="color:white">Disjoin Date</th>
                                  <th style="color:white">Status</th>
                                  <th style="color:white">Action</th>
                                </tr>
                              </thead>

                              <tbody class="table-border-bottom-0">
                                <tr>
                                  <td>Bonuslink</td>
                                  <td>1-9-2022</td>
                                  <td>1-10-2022</td>
                                  <td>
                                    <span class="badge bg-label-secondary">Inactive</span>
                                  </td>
                                  <td>
                                    <div class="d-inline-block- text-nowrap" >
                                      <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditCard">
                                        <i class='bx bxs-edit'></i>
                                      </button>
                                    </div>
                                  </td>
                                </tr>

                                <tr>
                                  <td>Tourist Card</td>
                                  <td>1-11-2022</td>
                                  <td>1-12-2022</td>
                                  <td>
                                    <span class="badge bg-label-success">Active</span>
                                  </td>
                                  <td>
                                    <a href="">
                                    <div class="d-inline-block- text-nowrap" >
                                      <button class="btn btn-sm btn-icon" style="margin-right:5px">
                                        <i class='bx bxs-edit'></i>
                                      </button>
                                    </div>
                                  </a>
                                  </td>
                                </tr>

                                <tr>
                                  <td>Parkson Platinum Card</td>
                                  <td>1-1-2023</td>
                                  <td>1-2-2023</td>
                                  <td>
                                    <span class="badge bg-label-secondary">Inactive</span>
                                  </td>
                                  <td>
                                    <a href="">
                                    <div class="d-inline-block- text-nowrap" >
                                      <button class="btn btn-sm btn-icon" style="margin-right:5px">
                                        <i class='bx bxs-edit'></i>
                                      </button>
                                    </div>
                                  </a>
                                  </td>
                                </tr>

                                <tr>
                                  <td>Parkson Silver Card</td>
                                  <td>1-3-2023</td>
                                  <td>1-4-2023</td>
                                  <td>
                                    <span class="badge bg-label-success">Active</span>
                                  </td>
                                  <td>
                                    <a href="">
                                    <div class="d-inline-block- text-nowrap" >
                                      <button class="btn btn-sm btn-icon" style="margin-right:5px">
                                        <i class='bx bxs-edit'></i>
                                      </button>
                                    </div>
                                  </a>
                                  </td>
                                </tr>
                                <tbody>
                            </table>
                          </div>

                          <!--offcanvas add new card-->
                          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCard" aria-labelledby="offcanvasAddCardLabel" style="width: 50%;">
                            <div class="offcanvas-header border-bottom">
                              <h6 id="offcanvasAddCardLabel" class="offcanvas-title">Add New Card for Campaign</h6>
                              <button
                                type="button"
                                class="btn-close text-reset"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"
                              ></button>
                            </div>

                            <div class="offcanvas-body mx-0 flex-grow-0">
                              <form class="add-new-card pt-0" id="addNewCardForm" onsubmit="return false">
                                <div class="mb-3">
                                  <label class="form-label" for="add-card-name">Card Name</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    id="add-card-name"
                                    placeholder="E.g: Mastercard"
                                    name="addCardName"
                                    aria-label="E.g: Mastercard"
                                  />
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="join-date">Start Date</label>
                                  <input
                                    type="date"
                                    id="start-date"
                                    class="form-control"
                                    placeholder=""
                                    aria-label=""
                                    name="startDate"
                                  />
                                </div>

                                <div class="mb-3">
                                  <label class="form-label" for="disjoin-date">End Date</label>
                                  <input
                                    type="date"
                                    id="end-date"
                                    class="form-control"
                                    placeholder=""
                                    aria-label=""
                                    name="endDate"
                                  />
                                </div>

                                <div class="mb-4">
                                  <label class="form-label" for="add-store-status">Status</label>
                                  <select id="add-store-status" class="form-select">
                                    <option>Choose Status</option>
                                    <option value="active">Active</option required>
                                    <option value="inactive">Inactive</option required>
                                  </select>
                                </div>

                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620"
                                >Submit</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                              </form>
                            </div>
                          </div>

                          <!-- Offcanvas edit card-->
                          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditCard" aria-labelledby="offcanvasEditCardLabel" style="width: 50%;">
                            <div class="offcanvas-header border-bottom">
                              <h6 id="offcanvasEditCardLabel" class="offcanvas-title">Edit Card for Campaign</h6>
                              <button
                                type="button"
                                class="btn-close text-reset"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"
                              ></button>
                            </div>

                            <div class="offcanvas-body mx-0 flex-grow-0">
                              <form class="edit-card pt-0" id="editCardForm" onsubmit="return false">
                                <div class="mb-3">
                                  <label class="form-label" for="edit-end-date">End Date</label>
                                  <input
                                    type="date"
                                    class="form-control"
                                    id="edit-end-date"
                                    placeholder=""
                                    name="editEndDate"
                                    aria-label=""
                                  />
                                </div>

                                <div class="mb-4">
                                  <label class="form-label" for="edit-store-status">Status</label>
                                  <select id="edit-store-status" class="form-select">
                                    <option>Choose Status</option>
                                    <option value="active">Active</option required>
                                    <option value="inactive">Inactive</option required>
                                  </select>
                                </div>

                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" style="background-color:#122620; border-color:#122620"
                                >Submit</button>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                              </form>
                            </div>
                          </div>

                        </div>
                      </div>

                      </div>
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
    <!-- <script src="<?php echo base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script> -->

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/js/pages-account-settings-account.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/forms-selects.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/forms-tagify.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

   <script>
     $(document).ready(function(){
       $("#date-label").hide();
       $("#time-label").hide();
       $("#inactive-date").hide();
       $("#inactiveTime").hide();
         $('#edit-campaign-status').on('change', function(){
           if(this.value =='inactive')
           {
             $("#date-label").show();
             $("#time-label").hide();
             $("#inactive-date").show();
             $("#inactiveTime").hide();
           }
           else{
             $("#date-label").hide();
             $("#time-label").hide();
             $("#inactive-date").hide();
             $("#inactiveTime").hide();
           }
         });

         var table = $('#card').DataTable( {
           columnDefs: [
               {targets: [-1], orderable: false},
             ],
             "paging": false
             });

             var table = $('#store').DataTable( {
               columnDefs: [
                   {targets: [-1], orderable: false},
                 ],
                 "paging": false
                 });

                 // function remove()
                 // {
                 //   $("#1").remove();
                 // }

          var table =$('#store').DataTable();
          $('#store tbody').on('click', '#trash', function(){
            table
            .row($(this).parents('#tr'))
            .remove()
            .draw();
          });

          var storename = $('#storecode').val();
          $('#storename').val(storename);
          $("#storecode").change(function(){
            var value = $(this).val();
            $('#storename').val(value);
          });

     })
   </script>
  </body>
</html>
