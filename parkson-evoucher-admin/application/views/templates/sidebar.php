<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo" style="background-color:#E0D7C7">
    <a href="<?php echo base_url();?>index.php/dashboard/Dashboard" class="app-brand-link">
      <img class="app-brand-text demo menu-text fw-bold ms-2" src="<?php echo base_url(); ?>assets/img/branding/logo3.png" style="width:90%; height:100%;"></img>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle" style="color:#008080"></i>
      <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
    </a>
  </div>

  <!-- <div class="menu-divider mt-0"></div> -->

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1" style="background:#D6AD60">
    <!-- Dashboards -->
    <li class="menu-item" >
      <a href="<?php echo base_url();?>dashboard/Dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx">
          <lord-icon
            src="https://cdn.lordicon.com/osuxyevn.json"
            trigger="hover"
            style="width:25px;height:25px">
          </lord-icon>
        </i>
        <!-- <i class="menu-icon tf-icons bx bx-home-circle" style="color:#122620"></i> -->
        <div data-i18n="Dashboard" style="color:#122620; margin-left:7px">Dashboard</div>
      </a>
    </li>


    <!-- User -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle" style=" border-radius:1px">
        <i class="menu-icon tf-icons bx">
          <lord-icon
              src="https://cdn.lordicon.com/dxjqoygy.json"
              trigger="hover"
              colors="primary:#121331,secondary:#000000"
              stroke="120"
              state="hover-nodding"
              style="width:25px;height:25px">
          </lord-icon>
        </i>
        <!-- <i class="menu-icon tf-icons bx bx-user" style="color:#122620"></i> -->
        <div style="color:#122620; margin-left:7px" data-i18n="Users">Users</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item">
          <a href="<?php echo base_url();?>user/User/UserList" class="menu-link">
            <div>List of User</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="<?php echo base_url(); ?>user/UserRole/UserRole" class="menu-link">
            <div>User Role</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Store-->
    <li class="menu-item">
      <a href="<?php echo base_url();?>store/Store/StoreList" class="menu-link">
        <i class="menu-icon tf-icons bx">
          <lord-icon
              src="https://cdn.lordicon.com/uitzjnpu.json"
              trigger="hover"
              style="width:27px;height:27px">
          </lord-icon>
        </i>
        <!-- <i class="menu-icon tf-icons bx bx-store" style="color:#122620"></i> -->
        <div data-i18n="Store" style="color:#122620; margin-left:7px">Store</div>
      </a>
    </li>

    <!-- Campaign & Vouchers -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text" style="font-weight:800; color:#122620" >Campaign & Vouchers</span></li>


        <!-- Vouchers -->
        <li class="menu-item">
          <a href="javascript:void(0)" class="menu-link menu-toggle" style="border-radius:1px">
            <i class="menu-icon tf-icons bx">
              <lord-icon
                  src="https://cdn.lordicon.com/itnlluqc.json"
                  trigger="hover"
                  style="width:25px;height:25px">
              </lord-icon>
            </i>
            <!-- <i class='menu-icon bx bxs-coupon' style="color:#122620"></i> -->
            <div style="color:#122620; margin-left:10px">Vouchers</div>
          </a>

          <ul class="menu-sub">
            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/PredefinedVoucher" class="menu-link">
                <div>Voucher Type</div>
              </a>
            </li>


            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/Voucher/giftlist" class="menu-link">
                <div>Gift Vouchers</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/Voucher/promotionlist" class="menu-link">
                <div>Vouchers</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/Voucher/reprintvoucher" class="menu-link">
                <div>Reprint Voucher</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/IssuanceVoucher" class="menu-link">
                <div>Manual Voucher Issuance</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="<?php echo base_url(); ?>voucher/VoucherSettings" class="menu-link">
                <div>Voucher Settings</div>
              </a>
            </li>
          </ul>
        </li>

        <!-- Campaign -->
        <li class="menu-item">
          <a href="<?php echo base_url();?>campaign/Campaign" class="menu-link">
            <i class="menu-icon tf-icons bx">
              <lord-icon
                  src="https://cdn.lordicon.com/qrbokoyz.json"
                  trigger="hover"
                  colors="primary:#121331,secondary:#000000"
                  stroke="100"
                  style="width:28px;height:28px">
              </lord-icon>
            </i>
            <!-- <i class="menu-icon tf-icons bx bxs-offer" style="color:#122620"></i> -->
            <div data-i18n="Campaign" style="color:#122620; margin-left:7px">Campaign</div>
          </a>
        </li>

    <!-- Coupon -->
    <!-- <li class="menu-item">
      <a href="javascript:void(0)" class="menu-link menu-toggle" style="border-radius:1px">
        <i class='menu-icon bx bxs-discount' style="color:#122620"></i>
        <div style="color:#122620">Coupon</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item">
          <a href="<?php echo base_url(); ?>" class="menu-link">
            <div>Gift Coupon</div>
          </a>
        </li>

        <li class="menu-item">
          <a href="<?php echo base_url(); ?>" class="menu-link">
            <div>Promotion & Discount Coupon</div>
          </a>
        </li>

        <li class="menu-item">
          <a href="<?php echo base_url(); ?>" class="menu-link">
            <div>Create Coupon</div>
          </a>
        </li>

        <li class="menu-item">
          <a href="<?php echo base_url(); ?>" class="menu-link">
            <div>Coupon Settings</div>
          </a>
        </li>
      </ul>
    </li> -->

    <!-- Others-->
    <li class="menu-header small text-uppercase"><span class="menu-header-text" style="font-weight:800; color:#122620">Others</span></li>

    <!-- Cards -->
    <li class="menu-item">
      <a href="<?php echo base_url();?>cards/Cards" class="menu-link">
        <i class="menu-icon tf-icons bx">
          <script src="https://cdn.lordicon.com/fudrjiwc.js"></script>
          <lord-icon
              src="https://cdn.lordicon.com/zqxjldws.json"
              trigger="hover"
              style="width:25px;height:25px">
          </lord-icon>
        </i>
        <!-- <i class="menu-icon tf-icons bx bxs-offer" style="color:#122620"></i> -->
        <div data-i18n="Card" style="color:#122620; margin-left:7px">Card</div>
      </a>
    </li>
    <!--/ Cards -->

    <!--Report-->
    <li class="menu-item">
      <a href="<?php echo base_url();?>reports/Reports" class="menu-link">
        <i class="menu-icon tf-icons bx">
          <script src="https://cdn.lordicon.com/fudrjiwc.js"></script>
          <script src="https://cdn.lordicon.com/fudrjiwc.js"></script>
          <lord-icon
              src="https://cdn.lordicon.com/iiixgoqp.json"
              trigger="hover"
              style="width:25px;height:25px">
          </lord-icon>
        </i>
        <!-- <i class="menu-icon tf-icons bx bxs-offer" style="color:#122620"></i> -->
        <div data-i18n="Report" style="color:#122620; margin-left:7px">Report</div>
      </a>
    </li>
    <!-- /Report -->

    <!--Logs-->
    <li class="menu-item">
      <a href="javascript:void(0)" class="menu-link menu-toggle" style="border-radius:1px">
        <i class="menu-icon tf-icons bx">
          <lord-icon
              src="https://cdn.lordicon.com/kulwmpzs.json"
              trigger="hover"
              style="width:25px;height:25px">
          </lord-icon>
        </i>
        <!-- <i class='menu-icon bx bx-spreadsheet' style="color:#122620"></i> -->
        <div style="color:#122620; margin-left:7px">Logs</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item">
          <a href="<?php echo base_url(); ?>index.php/logs/ActivityLog/activity" class="menu-link">
            <div>User Activity Log</div>
          </a>
        </li>

        <li class="menu-item">
          <a href="<?php echo base_url(); ?>index.php/logs/ActivityLog/voucher" class="menu-link">
            <div>Voucher Log</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- / Logs -->


  <!-- Settings-->
  <li class="menu-item">
    <a href="javascript:void(0)" class="menu-link menu-toggle" style="border-radius:1px">
      <i class="menu-icon tf-icons bx">
        <lord-icon
            src="https://cdn.lordicon.com/hwuyodym.json"
            trigger="hover"
            colors="primary:#121331"
            state="hover-1"
            style="width:25px;height:25px">
        </lord-icon>
      </i>
      <!-- <i class='menu-icon bx bx-cog' style="color:#122620"></i> -->
      <div style="color:#122620; margin-left:7px">Settings</div>
    </a>

    <ul class="menu-sub">
      <li class="menu-item">
        <a href="<?php echo base_url(); ?>settings/GeneralSettings" class="menu-link">
          <div>General Settings</div>
        </a>
      </li>

      <li class="menu-item">
        <a href="<?php echo base_url();?>settings/PassSettings" class="menu-link">
          <div>Password Settings</div>
        </a>
      </li>

      <li class="menu-item">
        <a href="<?php echo base_url();?>settings/RolePermission/RolePermission" class="menu-link">
          <div>Role Permission</div>
        </a>
      </li>
    </ul>
  </li>
  <!-- / Settings -->
  <!-- / Others -->

<script src="https://cdn.lordicon.com/qjzruarw.js"></script>
</aside>
