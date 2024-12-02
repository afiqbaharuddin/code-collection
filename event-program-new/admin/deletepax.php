<?php

require("../db_conn.php");



$id = $_GET['id'];
$eventid = $_GET['eventid'];
// die("$id  $eventid");
delete($conn,'pax',$id);
// header("location: listpax.php?id=$eventid");
header("location: ../attend/index.php?id=$eventid");
