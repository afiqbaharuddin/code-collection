<?php
require('db_conn.php');
$id = $_GET['id'];
$where = $_GET['where'];
switch ($where) {
    case '1':
        # code...
        $where = "";
        break;
    case '2':
        # code...
        $where = "AND status = 1";
        break;
    case '3':
        # code...
        $where = "AND status = 0";
        break;

    default:
        # code...
        break;
}

$select = selectall($conn, 'pax', "event_id = $id $where");

foreach ($select as $key => $value) {
    # code...

    $select[$key]['id'] = $key;
    $cid = $value['city_council'];
    $stra = $value['starta_status'];
    if ($cid != null) {
        $cc = selectall($conn, 'city_council', " id =$cid");
        if (count($cc) < 1) {
            $cc = "NONE";
        } else {
            $cc = $cc[0]['city_council'];
        }
    } else {
        $cc = "";
    }
    if ($stra == "NULL") {
        $stra = "";
    }
    switch ($value['status']) {
        case "0":
            $select[$key]['status'] = "Absent";
            break;

        case "1":
            $select[$key]['status'] = "Attend";
            break;
        default:
            # code...
            break;
    }

    $select[$key]['city_council'] = $cc;
    $select[$key]['starta_status'] = $stra;

    unset($select[$key]['id']);
    unset($select[$key]['walkin']);
    unset($select[$key]['event_id']);
    unset($select[$key]['email_sent']);
    unset($select[$key]['nickname']);
    unset($select[$key]['210lm_proposal']);
    unset($select[$key]['light_count']);
    unset($select[$key]['scnd_5yrs']);
    unset($select[$key]['led_total']);
    unset($select[$key]['proposal_pic']);
    unset($select[$key]['created_by']);
}
// var_dump($select);
// die();

$eventname = selectall($conn, 'event', "id = $id");
$eventname = $eventname[0]['name'];
$name = "event" . date('y-m-d');
require('fexcel/src/SimpleExcel/SimpleExcel.php');

use SimpleExcel\SimpleExcel;

$heading = array("Full Name:", "Email Address:", "City Council/Company:", "Designation:", "Telephone No:", "Name of Strata Building (Eg. Mira Condo)/Facility Management/Property Management:", "Starta Status:", "Number of lights:","Type Light","No Residents Unit","No Blocks","Parking","Tariff","Status");
$excel = new SimpleExcel('CSV');
array_unshift($select, $heading);
$excel->writer->setData($select);
$excel->writer->saveFile($name . ".csv");
