<?php
// If you need to parse XLS files, include php-excel-reader
//require('spreadsheet-reader/php-excel-reader/excel_reader2.php');

use SimpleExcel\SimpleExcel;
include_once 'db_conn.php';
//require('spreadsheet-reader/SpreadsheetReader.php');
require('fexcel/src/SimpleExcel/SimpleExcel.php');


// print_r($excel->parser->getField());  

// unset($excel->parser->getField()[0]);




// $excel->convertTo('JSON');
// $excel->writer->addRow(array('add', 'another', 'row'));
// $excel->writer->saveFile('example');

if (isset($_POST['importSubmit'])) {
    if ($_FILES['file']['name'] != "") {
        $path = $_FILES['file']['name'];
        $pathto = "upload/" . $path;

        // move_uploaded_file( $_FILES['file']['tmp_name'],$pathto) or die( "Could not copy file!");
        rename($_FILES["file"]["tmp_name"], "TEST123");
        $tempfile = $_FILES['file']['tmp_name'];
        $get = file_get_contents($tempfile);
        $get2 = $tempfile;
        $get3 = file_get_contents($get2);
        rename($get2, $get2 .= '.csv');
    } else {
        
    }

    // Validate whether selected file is a Excel file 
    if (!empty($_FILES['file']['name'])) {
        $pathto = $get2;
        $excel = new SimpleExcel('CSV');
        $excel->parser->loadFile($pathto);

        // print_r($excel->parser->getField());
        $reader = $excel->parser->getField();
        // unset($Reader[0]);

        foreach ($reader as $i => $row) {
            if ($i < 1) {
                unset($row);
            } else {
                
                $name = $row[2];
                $designation = $row[3];
                $tel = $row[4];
                $email = $row[5];
                $building = $row[6];
                $starta = $row[7];
                $cityc = $row[8];

                $numberlight = $row[9];
                $prop210 = $row[10];
                $lightcount = $row[11];
                $scnd5yrs = $row[12];
                $led = $row[13];
                $prop = $row[14];

                //die("$name $designation $tel $email $building $starta $cityc $numberlight $prop210 $lightcount $scnd5yrs $led $prop");

                //start
                $findcityc =  "SELECT * FROM city_council where city_council LIKE'" . $cityc . "'";
                $resultc = $conn->query($findcityc);




                if ($resultc->num_rows > 0) {
                    while ($row = $resultc->fetch_assoc()) {
                        $cityc = $row['id'];
			
                    }
                } else {
                    $cityc = NULL;
                }

                if ($lightcount == null || $lightcount = "" || $lightcount == 0) {
                    $lightcount = 0;
                }





                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT id FROM pax WHERE email = '" . $email . "'";
                $prevResult = $conn->query($prevQuery);
		
                if ($prevResult->num_rows > 0) {

                    // Update member data in the database 
                    // $conn->query("UPDATE members SET first_name = '".$first_name."', last_name = '".$last_name."', email = '".$email."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'"); 
                } else {
                //die("$name $designation $tel $email $building $starta $cityc $numberlight $prop210 $lightcount $scnd5yrs $led $prop");

                    // Insert member data in the database 
                    // $conn->query("INSERT INTO pax (name, email,) VALUES ('".$name."', '".$email."'"); 
                    // $sql = "UPDATE pax SET status=1 WHERE id=".$id;
                    if ($cityc != NULL) {
                        if($email == "" || empty($email)){
                            continue;
                        }else{
                            $sql = "INSERT INTO `pax` (`id`, `name`, `email`, `city_council`,`designation`,`tel`,`building_name`,`starta_status`,`number_lights`,`210lm_proposal`,`light_count`,`scnd_5yrs`,`led_total`,`proposal_pic`, `status`,`event_id`) VALUES (NULL,'$name', '$email', $cityc,' $designation','$tel','$building','$starta','$numberlight','$prop210',$lightcount,'$scnd5yrs','$led','$prop', '0','2')  ";
			
                        }
                    } else {
                        if($email == "" || empty($email)){
                            continue;
                        }else{
			
                        $sql = "INSERT INTO `pax` (`id`, `name`, `email`,`designation`,`tel`,`building_name`,`starta_status`,`number_lights`,`210lm_proposal`,`light_count`,`scnd_5yrs`,`led_total`,`proposal_pic`, `status`,`event_id`) VALUES (NULL,'$name', '$email',' $designation','$tel','$building','$starta','$numberlight','$prop210',$lightcount,'$scnd5yrs','$led','$prop', '0','2')  ";
                        }
                    }
                    $conn->query($sql);
                }
                //end
            }
        }
                $qstring = '?status=succ';
		header("Location: listpax.php?id=2");
    } else {
        $qstring = '?status=invalid_file';
    }
}
