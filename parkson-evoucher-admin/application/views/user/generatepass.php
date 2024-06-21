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
    <?php echo $header; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/css/pages/app-calendar.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/fullcalendar/fullcalendar.css" /> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/libs/select2/select2.css" />
  </head>
  <style media="screen">
        :root {
      --teal: #06b6d4;
      --blue: #3b82f6;
      --lightgray: #cbd5e1;
      --gradient: linear-gradient(to right,var(--teal), var(--blue))
      }

      body {
      background-image: var(--gradient);
      font-family: "Lexend", sans-serif;
      accent-color: var(--blue);
      -webkit-font-smoothing: antialiased;
      }

      .container {
      max-width: 390px;
      margin: 5rem auto;
      padding: 26px;
      border-radius: 20px;
      background: white;
      box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      }

      .result-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      }

      #result {
      flex: 1;
      font-family: Monaco, mono;
      background: #f4f4f4;
      }

      .copy-result {
      background-image: var(--gradient);
      border: none;
      padding: 14px 18px;
      color: white;
      border-radius: 25px;
      margin-left: 16px;
      position: absolute;
      right: 12px;
      font-weight: 600;
      cursor: pointer;
      z-index: 50;
      font-size: .8rem;
      }

      .copy-result:hover {
      background: var(--blue);
      }

      input[type="text"] {
      padding: 20px 24px;
      border: 1px solid var(--lightgray);
      border-radius: 50px;
      }

      input[type="range"] {
      padding: 8px 10px;
      background: #f8f8f8;
      flex: 1;
      margin-left: 1rem;
      margin-right: 1rem;
      }

      input[type="text"]:focus,
      input[type="number"]:focus {
      border: 1px solid var(--teal);
      outline: none;
      }

      input[type="checkbox"] {
      width: 16px;
      height: 16px;
      }

      .settings {
      margin-top: 3rem;
      }

      .input-group {
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      }

      .generate-btn {
      background-image: var(--gradient);
      padding: 14px 24px;
      border: none;
      font-weight: 600;
      color: white;
      display: block;
      width: 100%;
      border-radius: 25px;
      cursor: pointer;
      font-size: 1.25rem;
      }

      .generate-btn:hover,
      .copy-btn:hover {
      box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

      background: var(--blue);
      }

      .alert {
      position: fixed;
      top: 6px;
      right: 6px;
      padding: 10px;
      border-radius: 4px;
      background: rgba(0,0,0, .4);
      color: white;
      font-size: 20px;
      }
  </style>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container">
              <h2>Password Generator</h2>
              <div class="">

                <div class="row">
                  <label for="" class="form-label">Chosen Password:</label>
                  <input type="text" name="" value="" id="result">
                </div>
                <div class="row mt-3">
                  <div class="">
                    <span id="result1" class="results me-3"></span>
                    <span id="result2" class="results me-3"></span>
                    <span id="result3" class="results me-3"></span>
                  </div>
                </div>

              </div>
              <div class="settings">
                <div class="">
                  <label for="" class="form-label">Password Length Status: </label>
                  <span id="lengthstatus"></span>
                </div>
                <div class="">
                  <label for="" class="form-label">Password Length: </label>
                  <span id="lengthnum"></span>
                </div>
                <hr>
                <div class="">
                  <label for="" class="form-label">Uppercase Status: </label>
                  <span id="upstatus"></span>
                </div>
                <div class="">
                  <label for="" class="form-label">Uppercase Length: </label>
                  <span id="upnum"></span>
                </div>
                <hr>
                <div class="">
                  <label for="" class="form-label">Lowercase Status: </label>
                  <span id="lowstatus"></span>
                </div>
                <div class="">
                  <label for="" class="form-label">Lowercase Length: </label>
                  <span id="lownum"></span>
                </div>
                <hr>
                <div class="">
                  <label for="" class="form-label">Number Status: </label>
                  <span id="numstatus"></span>
                </div>
                <div class="">
                  <label for="" class="form-label">Number Length: </label>
                  <span id="numbernum"></span>
                </div>
                <br>
              </div>
              <button class="generate-btn" id="generate">
                Generate
              </button>
            </div>
            <!--/ Content -->

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
    <?php echo $footer; ?>
    <?php echo $bottom; ?>

    <script type="text/javascript">

    const lengthstatus = '<?php echo $MinPassCheck ?>';
    const numberstatus = 1;
    const uppercasestatus = 1;
    const lowercasestatus = 1;

    const length = 6;
    const number = 2;
    const uppercase = 1;
    const lowercase = 2;

    $('#lengthstatus').html(lengthstatus);
    $('#lengthnum').html(length);
    $('#upstatus').html(uppercasestatus);
    $('#upnum').html(uppercase);
    $('#lowstatus').html(lowercasestatus);
    $('#lownum').html(lowercase);
    $('#numstatus').html(numberstatus);
    $('#numbernum').html(number);

    $('#generate').on('click', function() {
      $('#result1').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#result2').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));
      $('#result3').html(generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase));

    });

    function generatePassword(lengthstatus,numberstatus,uppercasestatus,lowercasestatus,length,number, uppercase, lowercase) {
      let generatedPassword = '';
      // let variationsCount = [number, uppercase, lowercase].length
      if (lengthstatus == 0 ) {
        length = 8;
      }
      for(let i = 0; i < length; ) {
        let cont = 11; //if 11 get random lowercase
        if (numberstatus == 1) {
          if (number > 0) {
            generatedPassword += getRandomNumber(number)
            --number;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }

        }
        if (uppercasestatus == 1) {
          if (uppercase > 0) {
            generatedPassword += getRandomUpper(uppercase)
            --uppercase;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }

        }
        if (lowercasestatus == 1) {
          if (lowercase > 0) {
            generatedPassword += getRandomLower(lowercase)
            --lowercase;
            ++i;
            cont = 99; // if 99 dont get random lowercase
          }
        }
        if (cont == 11) {
          generatedPassword += getRandomLowerAll()
          ++i;
        }

      }

      const finalPassword = generatedPassword.slice(0, length);

      return finalPassword;
    }

    function getRandomLowerAll() {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
    }

    function getRandomLower(lowercase) {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
    }

    function getRandomUpper(uppercase) {
      return String.fromCharCode(Math.floor(Math.random() * 26) + 65);
    }

    function getRandomNumber(number) {
      return String.fromCharCode(Math.floor(Math.random() * 10) + 48);
    }

    function getRandomSymbol() {
      const symbols = '!@#$%^&*(){}[]=<>/,.'
      return symbols[Math.floor(Math.random() * symbols.length)];
    }

    $('.results').click(function() {
      $('#result').val($(this).html());
      $('.results').hide();
    });
    </script>
  </body>
</html>
