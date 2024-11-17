<?php

include 'includes.php';
checkLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Perkeso Task - Dashboard</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/landing/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/landing/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/landing/assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/landing/assets/css/main.css" rel="stylesheet">
</head>

<style media="screen">
  .sitename{
    color: #EAEFF2 !important;}
  h2,p{
    color: #EAEFF2 !important;}
</style>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <h1 class="sitename">Perkeso Task</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="logout.php" class="active">Logout</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">
    <section id="hero" class="hero section">
      <img src="assets/landing/assets/img/peakpx.jpg" alt="" data-aos="fade-in" class="">
      <div class="container text-center" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
            <p><a class="btn-get-started" href="report.php">View Report</a></p>
            <p><a class="btn-get-started" href="logout.php">Logout</a></p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/landing/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/landing/assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/landing/assets/vendor/aos/aos.js"></script>

  <!-- Main JS File -->
  <script src="assets/landing/assets/js/main.js"></script>
</body>
</html>
