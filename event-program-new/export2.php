<?php
require("vendor/php_XLSXWriter/xlsxwriter.class.php");

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
if(!empty($_GET['from'])){
    $from = $_GET['from'];
    $from = "AND date_event >= '$from'";
}else{
    $from = "";
}

if(!empty($_GET['to'])){
    $to = $_GET['to'];
    $to = "AND date_event <= '$to'";
}else{
    $to = "";
}

$where    = "$where $from $to";
$select   = selectall($conn, 'pax', "event_id = $id $where");
$eventdet = selectall($conn, 'event', "id = $id");
// $no = 0;

foreach ($select as $key => $value) {
    # code...
	// $no=$no+1;
	// echo "<pre>";
	// echo $eventdet[0]['spec_event'];
	// echo "</pre>";
	// die;
    $select[$key]['id'] = $key;
    // $select[$key]['no'] = $no;
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

    switch ($select[$key]['contact_by']) {
        case '1':
            # code...
            $select[$key]['contact_by'] = "EMAIL";
            break;
        case '2':
            $select[$key]['contact_by'] = "MOBILE";
            break;

        default:
            # code...
            break;
    }

    unset($select[$key]['id']);
    unset($select[$key]['walkin']);
    unset($select[$key]['event_id']);
    unset($select[$key]['email_sent']);
    // unset($select[$key]['nickname']);
    unset($select[$key]['210lm_proposal']);
    unset($select[$key]['light_count']);
    unset($select[$key]['scnd_5yrs']);
    unset($select[$key]['led_total']);
    unset($select[$key]['proposal_pic']);
    //unset($select[$key]['created_by']);
    // var_dump($select[$key]);
    // die();
    if($eventdet[0]['spec_event'] == 1){
    unset($select[$key]['nickname']);
    unset($select[$key]['city_council']);
    unset($select[$key]['building_name']);
    unset($select[$key]['starta_status']);
    unset($select[$key]['number_lights']);
    unset($select[$key]['type_light']);
    // unset($select[$key]['no_residents_unit']);
    unset($select[$key]['no_blocks']);
    // // unset($select[$key]['led_total']);
    // unset($select[$key]['parking']);
    unset($select[$key]['tariff']);
    unset($select[$key]['status']);
    // $select[$key]['status'] = "-";
    }else if($eventdet[0]['spec_event'] == 2){
    unset($select[$key]['starta_name']);
    unset($select[$key]['number_lights']);
    unset($select[$key]['type_light']);
    unset($select[$key]['no_blocks']);
    unset($select[$key]['tariff']);
    unset($select[$key]['status']);
    unset($select[$key]['parking']);
    unset($select[$key]['no_residents_unit']);

    }else{
		unset($select[$key]['starta_name']);
		unset($select[$key]['contact_by']);
        unset($select[$key]['user_interest']);
		unset($select[$key]['date_event']);

	}
    // echo "<pre>";
	// echo "</br>";
	// print_r($select);
	// echo "</br>";
	// echo "</pre>";
	// die;
}



ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
// echo "<pre>";
// echo "</br>";
// print_r($eventdet[0][spec_event]);
// echo "</br>";
// echo "</pre>";
// die;

$edate = $eventdet[0][date];
$edate = date("jM", strtotime($edate));
$dexport = date("Y-m-d H:i:s");
$dexport = date("ymd", strtotime($dexport));
$hexport = date("Y-m-d H:i:s");
$hexport = date("Hi", strtotime($hexport));
$filename = "Event_".$edate."_paxList_".$dexport."_".$hexport.".xlsx";

header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$heading = array("No:","Full Name:", "Email Address:", "City Council/Company:", "Designation:", "Telephone No:", "Name of Strata Building: ", "Starta Status:", "Number of lights:", "Type Light", "No Residents Unit", "No Blocks", "Parking", "Tariff", "Status" ,"Date");
// array_unshift($select, $heading);
if(isset($eventdet[0]['spec_event']) == 1){

    $header = array(
        "No" => "string",
        "Full Name" => "string",
        "Email Address" => "string",
        "Represents" => "string",
        "Telephone No" => "string",
        "Starta Name" => "String",
        "No. Units" => "String",
        "Indoor Carpark" => "String",
        "Date Time " => "string",

    );
}else if(isset($eventdet[0]['spec_event']) == 2){
    $header = array(
        "No" => "string",
        "Full Name" => "string",
        "Prefered Name" => "string",
        "Email Address" => "string",
        "City Council/Company" => "string",
        "Designation" => "string",
        "Telephone No" => "string",
        "Name of Strata Building" => "string",
        "Starta Status" => "string",
        "Contact By" => "string",
        "Date event" => "string",
        "Timestamp" => "string",
    );
}


else{
    $header = array(
        "No" => "string",
        "Full Name" => "string",
        "Prefered Name" => "string",
        "Email Address" => "string",
        "City Council/Company" => "string",
        "Designation" => "string",
        "Telephone No" => "string",
        "Name of Strata Building" => "string",
        "Starta Status" => "string",
        "Number of lights" => "string",
        "Type Light" => "string",
        "No Residents Unit" => "string",
        "No Blocks" => "string",
        "Parking" => "string",
        "Tariff" => "string",
        "Status" => "string",
        "Date" => "string",
    );
}

$writer = new XLSXWriter();

$writer->setAuthor('Some Author');
//header
$styles1 = array('widths' => 50, 'height' => 20, 'font' => 'Arial', 'font-size' => 10, 'halign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium');
$stylesatt = array('widths' => 50, 'height' => 20, 'font' => 'Arial', 'font-size' => 10, 'halign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium');
$headers = array('height' => 20, 'widths' => [4, 40, 15, 42, 40, 30, 14, 60, 15, 20, 28, 17, 11, 50, 22, 10, 20], 'font' => 'Arial', 'font-size' => 10, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium');

$writer->writeSheetHeader('Sheet1', $header, $col_options = $headers);
$no2=0;
foreach ($select as $key => $value) {
    # code...
	$no2 = $no2 + 1;
	$arrNo = array("No2"=>$no2);
    $temparr = array_merge($arrNo, array_values($value));
	// print_r(array_merge($a1,$a2));
    // $temparr = array_values($value);

    switch ($value['status']) {
        case 'Absent':
            # code...
            $writer->writeSheetRow('Sheet1', $temparr, $styles1);
            break;
        case 'Attend':
            # code...
            $writer->writeSheetRow('Sheet1', $temparr, $styles1);
            break;

        default:
            # code...
            $writer->writeSheetRow('Sheet1', $temparr, $styles1);
            break;
    }
    // var_dump(array_values($value));

}


// $writer->writeSheetRow('Sheet1', $heading,$styles1);
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);
// $excel->writer->saveFile($name . ".csv");
