<?php 
require("../db_conn.php");
var_dump($_POST);
$id = $_POST['id'];
$delete = delete($conn,"event","$id");
header("location:index.php");
?>