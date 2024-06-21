<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/"
  data-template="vertical-menu-template"
>
  <head>
    <?php echo $header; ?>
  </head>

  <style>
    body {
     background-color:#D6AD60 ;
    }
  </style>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="py-4">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center" style=" margin-left:60px">
                <a href="" class="app-brand-link gap-2">
                  <img class="app-brand-text demo menu-text fw-bold ms-2" src="<?php echo base_url(); ?>assets/img/branding/logo3.png" style="width:75%;"></img>
                </a>
              </div>
              <!-- /Logo -->

              <h4 class="mb-2" style="margin-top:15px">User Login</h4 >
              <form id="loginform" class="mb-3" action="<?php echo base_url('auth/Login/validating'); ?>" method="POST">
              <input type="hidden" name="userid">
              <!-- <input type="text" name="" value="<?php echo $lockoutCheck->NumFailedLoginCheck; ?>">
              <input type="text" name="" value="<?php echo $lockoutCheck->NumFailedLoginNum; ?>"> -->

                <div class="mb-3">
                    <label for="LoginId" class="form-label">ID</label>
                    <input type="text" class="form-control" name="loginId" placeholder="Enter Login ID" autofocus/>
                    <span class="text-danger" id="loginId"></span>
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="Password">Password</label>
                  </div>

                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      class="form-control"
                      name="Password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <span class="text-danger" id="Password"></span>
                </div>

                <div class="mb-3">
                  <input type="hidden" name="count" id="count" value="">
                  <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                  <button class="btn btn-primary d-grid w-100" type="submit" style="background-color:#122620; border-color:#122620">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->
    <?php echo $footer; ?>

    <script type="text/javascript">
      $("#loginform").unbind('submit').bind('submit', function() {
        var form = $(this);
        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: form.serialize(),
          dataType: 'json',
          success:function(data)
          {
            $('.txt_csrfname').val(data.token);
            if (data.status == true)
            {
              window.location.href = "<?php echo base_url()."dashboard/Dashboard"; ?>";
            }else{
              if (typeof data.message === 'object') {
                $.each(data.message, function(key, value) {
                  $('#'+key).html(value);
                });
              }
              else {
                $('#count').html(data.count);
                snack(data.message);
              }
            }
          },
          error: function(xhr, status, error) {
            snack('Something went wrong. Please try again later');
          },
        });
        return false;
      });
  </script>

  </body>
</html>
