<?php
session_start();

if (!isset($_SESSION['user'])) {

        // echo "Cookie named user is not set!";
        //role 1 : admin
        //role 2: saleUser
        if(isset($redirect)){
            header("location: ../admin/login.php?redirect=$redirect");
        }else{
            header('location: ../admin/login.php');
        }
    }
