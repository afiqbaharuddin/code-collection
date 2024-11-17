<?php

session_start();

function connDB(){
    $host     = 'localhost';
    $user     = 'root';
    $password = '';
    $dbname   = 'perkeso_task';
    $conn     = new mysqli($host,$user,$password,$dbname);

    if ($conn->connect_error) {
        die('Connection Failed' .$conn->connect_error);
    }

    return $conn;
}

function validateUser($username, $password) {
    $file = fopen("users.txt", "r");
    while (($line = fgets($file)) !== false) {
        list($user, $pass, $name) = explode('|', trim($line));
        if ($user === $username && $pass === $password) {
            fclose($file);
            return $name;
        }
    }
    fclose($file);
    return false;
}

function validateUser2($username, $password) {
    $conn = connDB();
    $stmt = $conn->prepare("SELECT name FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return $name ? $name : false;
}

function checkLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}

?>
