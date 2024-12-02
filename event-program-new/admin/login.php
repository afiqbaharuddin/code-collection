<?php
require('../db_conn.php');
session_start();
if(isset($_SESSION['user'])){
  header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Initiative Program | Synergy Esco (M) sdn Bhd</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .w-50 {
            width: 50%;
        }

        .h-100 {
            height: 100%;
        }

        body {
            min-height: 100vh !important;
        }

        .stick-bot {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body class="h-100">
    <header>
        <nav class="navbar navbar-expand-lg fixed-top border-0 bg-transparent">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <!-- <img src="/images/logo/synery group logo -.png" alt="" class="img-fluid logo"> -->
                    <img src="../images/logo/new-logo.png" alt="test" class="img-fluid logo">
                </a>
                <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <div id="line1"></div>
                    <div id="line2"></div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" href="/event-program/">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/event-program/form-registration.php?id=<?= $id ?>">Registration</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="../">Dashboard</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="https://synergy-group.com.my/green-initiative.html">Back To Green Initiative Page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <section class="py-5 vh-100">
        <div class="container">
            <div class="row align-items-center">
                <!-- <div class="col-lg-6" style="background: url('/assets/images/bg/georgie-cobbs-bKjHgo_Lbpo-unsplash.jpg') no-repeat center; background-size: cover;"></div>
                style="background: url('/assets/images/bg/oleg-laptev-7jQh3EiS8Bs-unsplash.jpg') no-repeat left center; background-size: cover;" -->
                <div class="col-lg-6">
                    <h1 class="text-primary">Welcome.</h1>
                    <p class="text-muted mb-3">Login and export event information for free.</p>
                </div>
                <div class="col-lg-4 mx-auto">
                    <div class="card card-table">
                        <div class="card-body p-5">
                            <form action="auth.php" method="post">
                                <h4 class="">Login</h4>
                                <p class="mb-4 text-muted">Enter your username and password to login</p>
                                <div class="form-group mb-3">
                                    <!-- <label for="exampleInputEmail1">Username:</label> -->
                                    <input name="username" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username">
                                </div>
                                <div class="form-group mb-2">
                                    <!-- <label for="exampleInputPassword1">Password:</label> -->
                                    <input id=password name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="check">
                                    <label class="form-check-label" for="exampleCheck1">Show Password</label>
                                </div>
                                <?php
                                if(isset($_GET['redirect'])){
                                    ?>
                                    <input type="hidden" name="redirect" value="<?= $_GET['redirect']?>">
                                    <?php
                                }
                                ?>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- <footer class="stick-bot">
        <div class="container">
            <div class="row py-3">
                <h6 class="mb-0 col-lg-6">© 2023 Synergy (ESCO) Malaysia. All Rights Reserved.</h6>
                <p class="col-lg-6 text-lg-end text-start">
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms & Conditions</a>
                </p>
            </div>
        </div>
    </footer> -->

    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <script>
        $("#check").click(function(){
            var check = $("#password").attr('type');
            if(check == "password"){
                $("#password").attr('type','text');
            }else{
                $("#password").attr('type','password');
            }

        })
    </script>

</body>

</html>
