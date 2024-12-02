<?php
require("vendor/php_XLSXWriter/xlsxwriter.class.php");
require('db_conn.php');

$id    = $_GET['id'];
$where = $_GET['where'];

// Handle the "where" filter logic
switch ($where) {
    case '1':
        $where = "";
        break;
    case '2':
        $where = "AND status = 1";
        break;
    case '3':
        $where = "AND status = 0";
        break;
    default:
        $where = "";
        break;
}

$from  = !empty($_GET['from']) ? "AND date_event >= '{$_GET['from']}'" : "";
$to    = !empty($_GET['to']) ? "AND date_event <= '{$_GET['to']}'" : "";
$where = "$where $from $to";

$select   = selectall($conn, 'pax', "event_id = $id $where");
$eventdet = selectall($conn, 'event', "id = $id");

$participants = selectall($conn, 'participant', "event_id = $id");
$mergedData   = array();

foreach ($select as $key => $value) {

    $cid  = $value['city_council'];
    $stra = $value['starta_status'];

    // Fetch city council name
    $cc = $cid ? selectall($conn, 'city_council', "id = $cid")[0]['city_council'] ?? "NONE" : "";

    // Handle strata status
    $stra = ($stra === "NULL") ? "" : $stra;

    // Update values and replace status
    $select[$key]['status']        = ($value['status'] == 1) ? "Attend" : "Absent";
    $select[$key]['city_council']  = $cc;
    $select[$key]['starta_status'] = $stra;

    // Replace contact method
    switch ($value['contact_by']) {
      case '1':
          $select[$key]['contact_by'] = "EMAIL";
          break;
      case '2':
          $select[$key]['contact_by'] = "MOBILE";
          break;
      default:
          $select[$key]['contact_by'] = "";
    }

    // Removing unnecessary fields
    unset(
      $select[$key]['id'],
      $select[$key]['walkin'],
      $select[$key]['event_id'],
      $select[$key]['email_sent'],
      $select[$key]['210lm_proposal'],
      $select[$key]['light_count'],
      $select[$key]['scnd_5yrs'],
      $select[$key]['led_total'],
      $select[$key]['proposal_pic'],
      $select[$key]['registered'],
      $select[$key]['attended']
    );

    // Based on event type, unset additional fields
    if ($eventdet[0]['spec_event'] == 1) {
        unset(
          $select[$key]['nickname'],
          $select[$key]['city_council'],
          $select[$key]['building_name'],
          $select[$key]['starta_status'],
          $select[$key]['number_lights'],
          $select[$key]['type_light'],
          $select[$key]['no_blocks'],
          // $select[$key]['tariff'],
          $select[$key]['status']
        );
    } else if ($eventdet[0]['spec_event'] == 2) {
        unset(
          $select[$key]['starta_name'],
          $select[$key]['number_lights'],
          $select[$key]['type_light'],
          $select[$key]['no_blocks'],
          // $select[$key]['tariff'],
          $select[$key]['status'],
          $select[$key]['parking'],
          $select[$key]['no_residents_unit']
        );
    } else {
      unset(
        $select[$key]['starta_name'],
        $select[$key]['contact_by'],
        $select[$key]['user_interest'],
        $select[$key]['date_event'],
        $select[$key]['no_blocks']
      );
    }

    $mergedData[] = $select[$key];
}

foreach ($participants as $participant) {

  $participant_data = array(
      "No"                      => "",
      "Full Name"               => "",
      "Preferred Name"          => "",
      "Email Address"           => "",
      "City Council/Company"    => "",
      "Designation"             => "",
      "Telephone No"            => "",
      "Name of Strata Building" => "",
      "Starta Status"           => "",
      "Number of lights"        => "",
      // "Type Light"              => "",
      "No Residents Unit"       => "",
      // "No Blocks"               => "",
      "Status"                  => "",
      "Date"                    => "",
    );

  if (isset($participant['name'])) {
    $participant_data['Full Name'] = $participant['name'];
  }else {
    $participant_data['Full Name'] = 'N/A';
  }

  if (isset($participant['email'])) {
    $participant_data['Preferred Name'] = $participant['nickname'];
  }else {
    $participant_data['Preferred Name'] = 'N/A';
  }

  if (isset($participant['email'])) {
    $participant_data['Email Address'] = $participant['email'];
  }else {
    $participant_data['Email Address'] = 'N/A';
  }

  if (isset($participant['city_council'])) {
    $participant_data['City Council/Company'] = $cc;
  }else {
    $participant_data['City Council/Company'] = 'N/A';
  }

  if (isset($participant['designation'])) {
    $participant_data['Designation'] = $participant['designation'];
  }else {
    $participant_data['Designation'] = 'N/A';
  }

  if (isset($participant['phone'])) {
    $participant_data['Telephone No'] = $participant['phone'];
  }else {
    $participant_data['Telephone No'] = 'N/A';
  }

  if (isset($participant['building_name'])) {
    $participant_data['Name of Strata Building'] = $participant['building_name'];
  }else {
    $participant_data['Name of Strata Building'] = 'N/A';
  }

  if (isset($participant['starta_status'])) {
    $participant_data['Starta Status'] = $participant['starta_status'];
  }else {
    $participant_data['Starta Status'] = 'N/A';
  }

  if (isset($participant['number_lights'])) {
    $participant_data['Number of lights'] = $participant['number_lights'];
  }else {
    $participant_data['Number of lights'] = 'N/A';
  }

  if (isset($participant['no_residents_unit'])) {
    $participant_data['No Residents Unit'] = $participant['no_residents_unit'];
  }else {
    $participant_data['No Residents Unit'] = 'N/A';
  }

  // if (isset($participant['type_light'])) {
  //   $participant_data['Type Light'] = $participant['type_light'];
  // }else {
  //   $participant_data['Type Light'] = 'N/A';
  // }

  // if (isset($participant['no_blocks'])) {
  //   $participant_data['No Blocks']  = $participant['no_blocks'];
  // }else {
  //   $participant_data['No Blocks']  = 'N/A';
  // }

  if (isset($participant['status']) != 1) {
    $participant_data['Status'] = 'Attend';
  }else {
    $participant_data['Status'] = 'Absent';
  }

  if (isset($participant['created_by'])) {
    $participant_data['Date'] = $participant['created_by'];
  }else {
    $participant_data['Date'] = 'N/A';
  }

  $mergedData[] = $participant_data;
}

// Set file export properties
$edate    = date("jM", strtotime($eventdet[0]['date']));
$dexport  = date("ymd");
$hexport  = date("Hi");
$filename = "Event_".$edate."_paxList_".$dexport."_".$hexport.".xlsx";

// Set headers for file download
header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

//Define Excel headers based on event type
if ($eventdet[0]['spec_event'] == 1) {
    $header = array(
        "No"             => "string",
        "Full Name"      => "string",
        "Email Address"  => "string",
        "Represents"     => "string",
        "Telephone No"   => "string",
        "Starta Name"    => "String",
        "No. Units"      => "String",
        "Indoor Carpark" => "String",
        "DateTime"      => "string",
    );
} else if ($eventdet[0]['spec_event'] == 2) {
    $header = array(
        "No"                      => "string",
        "Full Name"               => "string",
        "Preferred Name"          => "string",
        "Email Address"           => "string",
        "City Council/Company"    => "string",
        "Designation"             => "string",
        "Telephone No"            => "string",
        "Name of Strata Building" => "string",
        "Starta Status"           => "string",
        "Contact By"              => "string",
        "Date event"              => "string",
        "Timestamp"               => "string",
    );
} else {
    $header = array(
        "No"                      => "string",
        "Full Name"               => "string",
        "Preferred Name"          => "string",
        "Email Address"           => "string",
        "City Council/Company"    => "string",
        "Designation"             => "string",
        "Telephone No"            => "string",
        "Name of Strata Building" => "string",
        "Starta Status"           => "string",
        "Number of lights"        => "string",
        // "Type Light"              => "string",
        "No Residents Unit"       => "string",
        // "No Blocks"               => "string",
        "Status"                  => "string",
        "DateTime"                => "string",
    );
}

// Create Excel file
$writer = new XLSXWriter();
$writer->setAuthor('Some Author');

//Define style and header format
$styles1 = array('widths' => 50, 'height' => 20, 'font' => 'Arial', 'font-size' => 10, 'halign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium');
$headers = array('height' => 20, 'widths' => [4, 40, 15, 42, 40, 30, 14, 60, 15, 20, 28, 17,25, 50, 22, 10, 20], 'font' => 'Arial', 'font-size' => 10,
          'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium');

// Write Excel header
$writer->writeSheetHeader('Sheet1', $header, $headers);

// Populate rows
$no2 = 0;
foreach ($mergedData as $key => $value) {
    $no2++;
    // Ignore rows with NULL values
    $cleanData = array_filter($value, fn($v) => !is_null($v) && $v !== '');
    $cleanData = array_merge(["No" => $no2], $cleanData); // Add row number
    $writer->writeSheetRow('Sheet1', $cleanData, $styles1);
}

// Output the Excel file
$writer->writeToStdOut();
exit(0);
?>
