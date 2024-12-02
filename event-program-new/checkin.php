<?php

require('db_conn.php');
$id = $_GET['id'];
$event_id = $_GET['event'];
// die("$id  $event_id");
update($conn,'pax',"status = 1",$id);

header("location:listpax.php?id=$event_id");