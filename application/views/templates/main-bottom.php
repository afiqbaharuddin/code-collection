<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="<?php echo base_url(); ?>assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/libs/popper/popper.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="<?php echo base_url(); ?>assets/vendor/libs/hammer/hammer.js"></script>

<script src="<?php echo base_url(); ?>assets/vendor/libs/i18n/i18n.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.js"></script>

<script src="<?php echo base_url(); ?>assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<!-- Main JS -->
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/snackbar/js-snackbar.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/jquery-confirm/jquery-confirm.js"></script>

<!-- Page JS -->

<script type="text/javascript">

function enable_loading(){
  $(document).ajaxStart(function() {
      getLoading("Please wait..");
  });

  $(document).ajaxStop(function() {
      stopLoading("Please wait..");
  });
}

function getLoading(words="Please wait.."){
  $('#text-spin').html(words);
  $(".loading-page-wrapper").addClass("center-loading");
  $(".loading-page-wrapper").fadeIn(300);
}

function stopLoading(words="Please wait.."){
  $('#text-spin').html(words);
  setTimeout(function(){
    $(".loading-page-wrapper").fadeOut(300);
  },1500);
}

var snack = function(text,status) {
  SnackBar({
    message: text,
    position: "bc",
    status: status,
    timeout: 8000,
  })
}

function getToken(){
  $.ajax({
    url: "<?php echo base_url(); ?>auth/login/getToken",
    type: "GET",
    dataType: 'json',
    success:function(data)
    {
      $('.txt_csrfname').val(data.token);
    },
  });
}
</script>
