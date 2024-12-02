<?php
session_start();
$id = $_GET['id'];
 ?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Synergy Thankyou page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<style>
  body {
    min-height: 100vh !important;
  }

  .h100 {
    min-height: 100vh !important;
  }

  .w-50 {
    width: 50%;
  }
  .btn-primary{
    background-color: #07A479 !important;
    border-color: #07A479 !important;
  }
</style>

<body class="h100">
  <div class="container mx-auto text-center d-flex flex-column justify-content-center h100 ">
    <h1>Thank You !</h1>
    <span class="mb-3">Your form has been submitted </span>
    <span class="mb-3">Thank you for your registration. We have sent you an email confirmation with QR code. Please present the QR code during on-site registration.</span>
    <span class="mb-3">
      Pendaftaran anda telah diterima. Kami telah menghantar email pengesahan beserta kod QR.
      Sila kemukakan kod QR semasa pendaftaran di majlis nanti.</span>
    <button class="btn btn-primary w-50 mx-auto"> <a class="text-decoration-none text-white" href="https://www.synergy-group.com.my/green-initiative.html">Go to website</a> </button>
    <a class="mt-2" href="form-registration.php?id=<?= $id ?>">Back to Event Registration Form</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php


// header( "refresh:5; url=https://www.synergy-group.com.my/green-initiative.html" );
?>
