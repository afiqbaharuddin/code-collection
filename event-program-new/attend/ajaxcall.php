<?php
require('../db_conn.php');
require('../admin/protect.php');

$id   = $_GET['id'];
$role = $_SESSION['role'];

// if(isset($_GET['role'])){
//     $role = $_GET['role'];
// }

if (isset($_GET['cob'])) {
    $cob = $_GET['cob'];
    $cob = "AND city_council = $cob";
}else{
    $cob = "";
}
if (isset($_GET['filter'])) {
  $status = $_GET['filter'];
  switch ($status) {
    case '2':
        $filter = "AND status = 1";
        break;
    case '3':
        $filter = "AND status = 0";
        break;
    default:
        $filter = "";
        break;
  }
}else{
    $filter = "";
}

if (!empty($_GET['from'])) {
    $from = $_GET['from'];
    $from = "AND date_event >= '$from'";
}else{
    $from="";

}
if (!empty($_GET['to'])) {
    $to = $_GET['to'];
    $to = "AND date_event <= '$to'";
}else{
    $to="";

}
$combine = "$cob $filter $from $to";

$sel       = selectall($conn, "pax", "event_id = $id $combine");
$selevent  = selectall($conn, "event", "id = $id");
$nowdate   = date("y-m-d");
$nowdate   = strtotime($nowdate);
$selevent  = selectall($conn, "event", "id = $id");
$nowdate   = date("y-m-d");
$nowdate   = strtotime($nowdate);
$eventdate = $selevent[0]['date'];
$eventdate = strtotime($eventdate);

$participantSel = selectall($conn, "participant", "pax_id IN (SELECT id FROM pax WHERE event_id = $id $combine)");

if ($nowdate < $eventdate) {
    $absts = "Not Started";
} else {
    $absts = "Absent";
}

foreach ($sel as $key => $value) {
    $pid    = $value['id'];
    $cobval = $value['city_council'];

    if ($cobval != null) {
        $citycon = selectall($conn, 'city_council', "id =$cobval");
        $citycon = $citycon[0]['city_council'];
    } else {
        $citycon = "";
    }

    if ($value['walkin'] == 1) {
        $sel[$key]['walkin'] = "Walkin";
    } else {
        $sel[$key]['walkin'] = "Online";
    }

    $participantStatus = 0;
    foreach ($participantSel as $participant) {
      if ($participant['pax_id'] == $pid && $participant['status'] == 1 ){
        $participantStatus = 1;
        break;
      }
    }

    if ($value['status'] == 1 || $participantStatus == 1) {
        $sel[$key]['status'] = "<div class=\"d-flex\"><small><i class=\"bi bi-circle-fill text-primary me-1\"></i> Attend ($value[attended]/$value[registered])</small><a class=\"ms-3\" data-bs-toggle=\"modal\" data-bs-target=\"#modalinfouser$pid\" href=\"../register.php?id=$pid\"><i class=\"bi bi-info-circle text-primary\"></i></a></div>";
    } else {
        $sel[$key]['status'] = "<div class=\"d-flex\"><small><i class=\"bi bi-circle-fill text-danger me-1\"></i> $absts ($value[attended]/$value[registered])</small><a class=\"ms-3\" data-bs-toggle=\"modal\" data-bs-target=\"#modalinfouser$pid\" href=\"../register.php?id=$pid\"><i class=\"bi bi-info-circle text-primary\"></i></a></div>";
    }

    $sel[$key]['city_council'] = $citycon;
    if(isset($_GET['admin']) && $_GET['admin'] == "true"){
        $sel[$key]['tesval'] = "<a class=\"btn btn-primary\" href=\"../register.php?id=$pid\"><i class=\"bi bi-info-circle text-primary\"></i></a>";
    }
    $sel[$key]['id'] = $key+1;

  }

  $response = array(
      "aaData" => $sel
  );

  $response = json_encode($response);
  echo $response;
