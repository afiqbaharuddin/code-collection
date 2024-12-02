<?php
require("../db_conn.php");

$username = $_POST['username'];
$password = $_POST['password'];

//find user 
$auth = selectall($conn, 'admin', "username = '$username'");
if (count($auth) > 0) {
    $hashpass = $auth[0]['password'];
    if (password_verify($password, $hashpass)) {
        echo 'Password is valid!';
        
        session_start();
        $_SESSION["user"] = $auth[0]['username'];
        $_SESSION["role"] = $auth[0]['role'];

        // $value = $auth[0]['username'];
        // setcookie("user", $value,time()+1200);
        if(isset($_POST['redirect'])){
            switch ($_POST['redirect']) {
                case 'attend':
                    # code...
                    header("location: ../attend/index.php");
                    break;
                
                default:
                    # code...
                    break;
            }
        }else{
            header("location: index.php");
        }
        
    } else {
        echo 'Invalid password.';
        header("location: index.php");
    }
    die("exist");
} else {
    header("location: index.php");
    die("unexist");
}
// SELECT * FROM `admin` WHERE `username` = "adminevent"

var_dump($auth[0]);
die("TEST POST $username $password");
