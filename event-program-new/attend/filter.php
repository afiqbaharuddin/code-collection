<?php

require('../db_conn.php');

$filter   = $_POST['filter'];
$id       = $_POST['id'];
$cob      = $_POST['cob'];
$from     = $_POST['from'];
$to       = $_POST['to'];
$eventdet = selectall($conn, 'event', "id = $id");
$spe      = $eventdet[0]['spec_event'];

if(isset($cob)){
    if($spe != 2){
        header("location: index.php?id=$id&cob=$cob&filter=$filter");
    }else{
        header("location: index.php?id=$id&cob=$cob&filter=$filter&from=$from&to=$to");
    }

}else{
    if($spe != 2){
        header("location: index.php?id=$id&filter=$filter");
    }else{
        header("location: index.php?id=$id&filter=$filter&from=$from&to=$to");
    }
}

?>
