<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
  />

  <title>Parkson Admin System</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon/icon4.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet"
  />

  <!-- Icons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
  <!-- Vendor -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/css/pages/page-auth.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/third-party/loading.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/third-party/snackbar/js-snackbar.css">

  <style media="screen">
    .table-responsive {
      /* min-height: .01%; */
      overflow-x: hidden;
    }
  </style>

  <!-- Helpers -->
  <script src="<?php echo base_url(); ?>assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="<?php echo base_url(); ?>assets/vendor/js/template-customizer.js"></script>
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="<?php echo base_url(); ?>assets/js/config.js"></script>
</head>

<div class="loading-page-wrapper">
  <div class="loading-page">
    <!-- <div class="spinner"></div><br>
    <span class="text-spin" id="text-spin">Please wait...</span> -->

    <div class="loader">
        <svg viewBox="0 0 80 80">
            <circle id="test" cx="40" cy="40" r="32"></circle>
        </svg>
    </div>
    <!-- <div class="loader triangle">
        <svg viewBox="0 0 86 80">
            <polygon points="43 8 79 72 7 72"></polygon>
        </svg>
    </div>
    <div class="loader">
        <svg viewBox="0 0 80 80">
            <rect x="8" y="8" width="64" height="64"></rect>
        </svg>
    </div> -->
    <!-- <br>
    <br>
    <span class="text-spin" id="text-spin">Please wait...</span> -->

  </div>
</div>
