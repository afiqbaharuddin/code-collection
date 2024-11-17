<?php

include 'includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = validateUser($username, $password);

    if ($name) {
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $name;
        header("Location: private.php");
    } else {
        $error = "Login Failed! Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
  	<title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
  	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel="stylesheet" href="assets/login/css/style.css">
	</head>

  <style media="screen">
    body {
     background-color:#0A0708 !important;
    }
    .submitBtn{
      background-color:#fd680e !important;
    }
  </style>

	<body class="img js-fullheight">
  	<section class="ftco-section">
  		<div class="container">
        <div class="row justify-content-center">
  				<div class="col-md-6 text-center mb-5">
  					<h2 class="heading-section">Login</h2>
  				</div>
  			</div>
  			<div class="row justify-content-center">
  				<div class="col-md-6 col-lg-4">
  					<div class="login-wrap p-0">
  		      	<form action="#" class="signin-form" method="post">
  		      		<div class="form-group">
  		      			<input type="text" name="username" class="form-control" placeholder="Username" required>
  		      		</div>
  	            <div class="form-group">
  	              <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
  	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
  	            </div>
                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
  	            <div class="form-group">
  	            	<button type="submit" class="form-control btn px-3 submitBtn">Login</button>
  	            </div>
  	          </form>
  		      </div>
  				</div>
  			</div>
  		</div>
  	</section>

  	<script src="assets/login/js/jquery.min.js"></script>
    <script src="assets/login/js/popper.js"></script>
    <script src="assets/login/js/bootstrap.min.js"></script>
    <script src="assets/login/js/main.js"></script>

	</body>
</html>
