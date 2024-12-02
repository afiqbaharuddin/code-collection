<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;

require('db_conn.php');
$id = $_GET['id'];

$select = selectall($conn, 'pax', "event_id = $id");

$eventname = selectall($conn, 'event', "id = $id");

$eventname = $eventname[0]['name'];

$name = "event" . date('y-m-d');
foreach ($select as $key => $value) {
    $ciid = $value['city_council'];
    $selcity = selectall($conn,"city_council","id=$ciid");
    
   
    $selcity = $selcity[0]['city_council'];
    $select[$key]['city_council'] = $selcity;
    if($value['status'] == "1"){
        $select[$key]['status'] = "Attend";
    }else{
        $select[$key]['status'] = "Not Attend";
    }
    if($value['walkin'] == "1"){
        $select[$key]['walkin'] = "Walkin";
    }else{
        $select[$key]['walkin'] = "Online register";
    }
    $select[$key]['event_id'] = "";
 
}

$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');
$heading = array("ID", "Full Name:", "Email Address:", "City Council/Company:", "Designation:", "Telephone No:", "Name of Strata Building (Eg. Mira Condo)/Facility Management/Property Management:", "Starta Status:", "Number of lights:", "210lm Proposal", "Light Count", "%2nd 5Yrs", "%LED/ Total", "PROPOSAL - PIC");
array_unshift($select, $heading);

$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
            'color' => ['argb' => '00000000'],
        ],
    ],
];

$spreadsheet->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);

$let = letter_rep("A", "Q");
foreach ($let as $key => $value) {
    $spreadsheet->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
}

$spreadsheet->getActiveSheet()
    ->fromArray(
        $select,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
    );

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;


function letter_rep($start, $end)
{
    $letters = array();
    $letter = $start;
    while ($letter <= $end) {
        $letters[] = $letter++;
    }
    return $letters;
}
