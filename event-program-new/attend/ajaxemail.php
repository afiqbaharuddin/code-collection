<?php 

require('../db_conn.php');

if(isset($_GET['email'])){
    $email = $_GET['email'];
    $sel = selectall($conn, "pax", "email='$email'");
    if(!empty($sel[0]['email'])){
        $pax = 1;
    }else{
        $pax = 0;
    }
    
    echo $pax;
}

?>