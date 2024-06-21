<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url();?>assets/"
  data-template="vertical-menu-template"
>
  <head>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- <title>Dashboard</title> -->
    <?php echo $header;?>
  </head>

  <style>
    body {
     background-color:#F1EDE3 ;
    }
    .loadcard{
      width: 23px;
      height: 23px;
      border: 3px solid #C8D3DA;
      border-radius: 50%;
      display: inline-block;
      box-sizing: border-box;
      position: relative;
      animation: pulse 1s linear infinite;
    }
    .loadcard:after{
      content: '';
      position: absolute;
      width: 23px;
      height: 23px;
      border: 3px solid #C8D3DA;
      border-radius: 50%;
      display: inline-block;
      box-sizing: border-box;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      animation: scaleUp 1s linear infinite;
    }
    @keyframes scaleUp {
      0% { transform: translate(-50%, -50%) scale(0) }
      60% , 100% { transform: translate(-50%, -50%)  scale(1)}
    }
    @keyframes pulse {
      0% , 60% , 100%{ transform:  scale(1) }
      80% { transform:  scale(1.2)}
    }
  </style>

  <body>


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
              <div class="row">

                <div class=" col-12">
                  <div class="row">

                    <!-- Statistics Cards -->
                    <?php if ($user_role->UserRoleId == 1 || $store->StoreId == 0){ ?>

                      <!-- total voucher issue all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Vouchers Issued</span>
                                  <h5 id="voucher_issued" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Refund Vouchers all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Refund Vouchers</span>
                                  <h5 id="voucher_refund" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Vouchers Redeemed all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Vouchers Redeemed</span>
                                  <h5 id="voucher_redeem" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- total Gift Voucher Issued all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Gift Vouchers Issued</span>
                                  <h5 id="giftvoucher_issued" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Gift Voucher Void Vouchers all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Void Gift Vouchers</span>
                                  <h5 id="giftvoucher_void" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Voucher</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Gift Voucher Redeemed all -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Gift Vouchers Redeemed</span>
                                  <h5 id="giftvoucher_redeem" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Voucher</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php  } else{ ?>

                      <!-- total voucher issue by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Vouchers Issued</span>
                                  <h5 id="vouchers_issued_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Refund Vouchers  by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Refund Vouchers</span>
                                  <h5 id="vouchers_refund_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Vouchers Redeemed  by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Vouchers Redeemed</span>
                                  <h5 id="vouchers_redeem_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- total gift voucher issue by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Gift Vouchers Issued</span>
                                  <h5 id="giftvouchers_issued_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Void Gift Vouchers  by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Void Gift Vouchers</span>
                                  <h5 id="giftvouchers_void_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Total Gift Vouchers Redeemed  by store -->
                      <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between" style="position: relative;">
                              <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-coupon fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                  <span class="text">Total Gift Vouchers Redeemed</span>
                                  <h5 id="giftvouchers_redeem_store" class="card-title mb-0 me-2">
                                    <span class="loadcard mt-1"></span>
                                  </h5>
                                  <small>Gift Vouchers</small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    <?php  } ?>

                    <!-- Active Campaign -->
                    <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between" style="position: relative;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bxs-offer fs-4" style="color:#008080"></i></span>
                              </div>
                              <div class="card-info">
                                <span class="text">Active Campaign</span>
                                <h5 class="card-title mb-0 me-2"><?php echo $campaign ?></h5>
                                <small class="card-title mb-0 me-2"></small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Open Stores -->
                    <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between" style="position: relative;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-store fs-4" style="color:#008080"></i></span>
                              </div>
                              <div class="card-info">
                                <span class="text">Open Stores</span>
                                <h5 class="card-title mb-0 me-2"><?php echo $openStore; ?></h5>
                                <small class="card-title mb-0 me-2"></small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Close Stores -->
                    <div class="col-lg-4 col-md-4 col-sm-4 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between" style="position: relative;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-dark"><i class="bx bx-store fs-4" style="color:#008080"></i></span>
                              </div>
                              <div class="card-info">
                                <span class="text">Close Stores</span>
                                <h5 class="card-title mb-0 me-2"><?php echo $closeStore; ?></h5>
                                <small class="card-title mb-0 me-2"></small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
                <!--/ Statistics Cards -->

              <!-- Vouchers Analytics ghraph all -->
              <?php if ($user_role->UserRoleId == 1 || $store->StoreId == 0){ ?>
                <div class=" col-md-12 mb-4 order-0">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Daily Vouchers Analytics</h5>
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-around align-items-center flex-wrap mb-4 pb-1">
                        <div class="user-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Redeemed Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="success" data-series="35"></div>
                            <h3 id="voucher_redeem_today_graph" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                        <div class="sessions-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Issued Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="primary" data-series="76"></div>
                            <h3 id="voucher_issued_today_graph" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                        <div class="sessions-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Void Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="primary" data-series="76"></div>
                            <h3 id="voucher_void_today" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                      </div>
                      <div id="analyticsBarChart"></div>
                    </div>
                  </div>
                </div>
              <?php  }else { ?>
                <div class=" col-md-12 mb-4 order-0">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Daily Vouchers Analytics</h5>
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-around align-items-center flex-wrap mb-4 pb-1">
                        <div class="user-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Redeemed Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="success" data-series="35"></div>
                            <h3 id="vouchers_redeem_today_store_graph" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                        <div class="sessions-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Issued Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="primary" data-series="76"></div>
                            <h3 id="vouchers_issued_today_store_graph" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                        <div class="sessions-analytics text-center me-2">
                          <i class="bx bxs-coupon me-1"></i>
                          <span>Vouchers Void Today</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="primary" data-series="76"></div>
                            <h3 id="vouchers_void_today_store" class="mb-0">
                              <span class="loadcard mt-1"></span>
                            </h3>
                          </div>
                        </div>

                      </div>
                      <div id="analyticsBarChart"></div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <!-- / Content -->

      <!-- First time Change Password Form Modal -->
        <div class="modal backDropModal fade" id="firstimemodal" data-bs-backdrop="static" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" id="changePasswordFirst" >
              <div class="modal-header">
                <input type="hidden" name="userid" value="<?php echo $firstLogin->UserId;?>">
                <h5 class="modal-title" id="backDropModalTitle">PASSWORD CHANGE ALERT</h5>
              </div>
              <div class="modal-body"><strong style="color:orange">New User is required to change your password.</strong>
                <div class="row mt-3">
                  <div class="mb-3 col-md-6 form-password-toggle">
                    <label class="form-label" for="currentPassword">Current Password</label>
                    <div class="input-group input-group-merge">
                      <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                  </div>
                </div>

                <div class="row g-2">
                  <div class="mb-3 col-md-6 form-password-toggle">
                    <label class="form-label" for="newPassword">New Password</label>
                    <div class="input-group input-group-merge">
                      <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                  </div>

                  <div class="mb-3 col-md-6 form-password-toggle">
                    <label class="form-label" for="confirmPassword">Confirm New Password </label>
                    <div class="input-group input-group-merge">
                      <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <input type="hidden" name="loggerid" id="loggerid">
                <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <button type="submit" class="btn btn-primary" style="background-color:#122620; border-color:#122620">Save Changes</button>
              </div>

            </form>
          </div>
        </div>
      <!--/ Change Password Form -->

      <!-- Force Change Password after certain days -->
      <div class="modal backDropModal fade" id="forceChangePass" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <form class="modal-content" id="forceChangePassform" >
            <div class="modal-header">
              <input type="hidden" name="userid" value="<?php echo $firstLogin->UserId;?>">
              <h5 class="modal-title" id="backDropModalTitle">PASSWORD CHANGE ALERT</h5>
            </div>
            <div class="modal-body"><strong style="color:blue">System required for you to change to a new Password. Please change to a new Password</strong>
              <div class="row mt-3">
                <div class="mb-3 col-md-6 form-password-toggle">
                  <label class="form-label" for="currentPassword">Current Password</label>
                  <div class="input-group input-group-merge">
                    <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
              </div>
              <div class="row g-2">
                <div class="mb-3 col-md-6 form-password-toggle">
                  <label class="form-label" for="newPassword">New Password</label>
                  <div class="input-group input-group-merge">
                    <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3 col-md-6 form-password-toggle">
                  <label class="form-label" for="confirmPassword">Confirm New Password </label>
                  <div class="input-group input-group-merge">
                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="loggerid" id="loggerid">
              <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
              <button type="submit" class="btn btn-primary" style="background-color:#122620; border-color:#122620">Save Changes</button>
            </div>
          </form>
        </div>
      </div>

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
    <!-- </div> -->
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php echo $bottom;?>

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/dashboards-ecommerce.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>


    <script type="text/javascript">
      $(document).ready(function(){

      $('#forceChangePass').modal({
        backdrop: 'static',
        keyboard: false
      })

      $('#firstimemodal').modal({
        backdrop: 'static',
        keyboard: false
      })

      var initialOneTimePass = '<?php echo $oneTimePass->IntialLogOneTimePassCheck ?>';
      var firsttime = '<?php echo $firstLogin->FirstTimeLogin; ?>';
      // alert(firsttime);
      if (initialOneTimePass == 1) {
        if (firsttime == 1) {
          $('#firstimemodal').modal('show');
        }else {
            $('#firstimemodal').modal('hide');
        }
      }

      $("#changePasswordFirst").unbind('submit').bind('submit', function() {
        var form = $(this);
        $.ajax({
          url: '<?php echo base_url('dashboard/Dashboard/login'); ?>',
          type: 'POST',
          data: form.serialize(),
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
            if (data.status == true) {
              $('#firstimemodal').modal('hide');
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

      <?php if ($oneTimePass != null || $oneTimePass != "") {
         if ($oneTimePass->PasswordAgeCheck == 1): ?>
          <?php if (date('Y-m-d') >= date('Y-m-d', strtotime($passexpirycheck->CreatedDate." + ".$oneTimePass->PasswordAgeValue." days"))) { ?>
              $('#forceChangePass').modal('show');
            <?php } ?>
        <?php endif ?>
    <?php  } ?>


      $("#forceChangePassform").unbind('submit').bind('submit', function() {
        var form = $(this);
        $.ajax({
          url: '<?php echo base_url('dashboard/Dashboard/forceChangePass'); ?>',
          type: 'POST',
          data: form.serialize(),
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
            if (data.status == true) {
              $('#forceChangePass').modal('hide');
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

      functionName();
      function functionName() {

        var csrfName = $('.txt_csrfname').attr('name');
        var csrfHash = $('.txt_csrfname').val();

        $.ajax({
          url: '<?php echo base_url('dashboard/Dashboard/graphVouchers'); ?>',
          type: 'POST',
          data: {[csrfName]: csrfHash},
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
            loadgraph(data.dataA,data.dataB,data.dataC);
          }
        });
        return false;
      }

      function loadgraph(dataA,dataB,dataC){

        if (isDarkStyle) {
          cardColor = config.colors_dark.cardColor;
          headingColor = config.colors_dark.headingColor;
          labelColor = config.colors_dark.textMuted;
          legendColor = config.colors_dark.bodyColor;
          borderColor = config.colors_dark.borderColor;
          shadeColor = 'dark';
        } else {
          cardColor = config.colors.cardColor;
          headingColor = config.colors.headingColor;
          labelColor = config.colors.textMuted;
          legendColor = config.colors.bodyColor;
          borderColor = config.colors.borderColor;
          shadeColor = 'light';
        }

        const analyticsBarChartEl = document.querySelector('#analyticsBarChart'),
          analyticsBarChartConfig = {
            chart: {
              height: 260,
              type: 'bar',
              toolbar: {
                show: false
              }
            },
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '20%',
                borderRadius: 3,
                startingShape: 'rounded'
              }
            },
            dataLabels: {
              enabled: false
            },
            colors: ['#0a4158', '#ff4500', '#670305'],
            series: [
              {
                name: 'Issued',
                data: dataA
              },
              {
                name: 'Redeemed',
                data: dataB
              },
              {
                name: 'Void',
                data: dataC
              }
            ],
            grid: {
              borderColor: borderColor
            },
            xaxis: {

              // categories: ['00', '01', '02', '03', '04', '05', '06', '07', '08','09','10','11','12'],
              title:{
                text: 'Hours'
              }

            },
            yaxis: {
              title:{
                text: 'Total of Vouchers'
              }
            },
            legend: {
              show: false
            },
            tooltip: {
              y: {
                formatter: function (val) {
                  return val + ' Vouchers';
                }
              }
            }
          };
        if (typeof analyticsBarChartEl !== undefined && analyticsBarChartEl !== null) {
          const analyticsBarChart = new ApexCharts(analyticsBarChartEl, analyticsBarChartConfig);
          analyticsBarChart.render();
        }
      }

      setTimeout(function(){
        $.ajax({
          url: '<?php echo base_url('dashboard/Dashboard/card_allstore'); ?>',
          type: 'get',
          dataType: 'json',
          success:function(data)
          {
            //PV and DV
            $('#voucher_issued').html(data.voucher_issued);
            $('#voucher_refund').html(data.voucher_refund);
            $('#voucher_redeem').html(data.voucher_redeem);
            $('#voucher_void_today').html(data.voucher_void_today);

            //GV
            $('#giftvoucher_issued').html(data.giftvoucher_issued);
            $('#giftvoucher_redeem').html(data.giftvoucher_redeem);
            $('#giftvoucher_void').html(data.giftvoucher_void);

            //for graph
            $('#voucher_issued_today_graph').html(data.voucher_issued_today_graph);
            $('#voucher_redeem_today_graph').html(data.voucher_redeem_today_graph);
          }
        });
      },5000);

      setTimeout(function(){
        $.ajax({
          url: '<?php echo base_url('dashboard/Dashboard/card_bystore'); ?>',
          type: 'get',
          dataType: 'json',
          success:function(data)
          {
            //pv and dv
            $('#vouchers_issued_store').html(data.vouchers_issued_store);
            $('#vouchers_refund_store').html(data.vouchers_refund_store);
            $('#vouchers_redeem_store').html(data.vouchers_redeem_store);
            $('#vouchers_issued_today_store').html(data.vouchers_issued_today_store);
            $('#vouchers_redeem_today_store').html(data.vouchers_redeem_today_store);
            $('#vouchers_void_today_store').html(data.vouchers_void_today_store);

            //gv
            $('#giftvouchers_issued_store').html(data.giftvouchers_issued_store);
            $('#giftvouchers_redeem_store').html(data.giftvouchers_redeem_store);
            $('#giftvouchers_void_store').html(data.giftvouchers_void_store);

            //for graph
            $('#vouchers_issued_today_store_graph').html(data.vouchers_issued_today_store_graph);
            $('#vouchers_redeem_today_store_graph').html(data.vouchers_redeem_today_store_graph);
          }
        });
      },5000);

      });
    </script>
  </body>
      <!-- Force Change Password after certain days -->
</html>
