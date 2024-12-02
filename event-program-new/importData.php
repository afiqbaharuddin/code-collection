<?php

// Load the database configuration file 
include_once 'db_conn.php';

// Include PhpSpreadsheet library autoloader 
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['importSubmit'])) {

    // Allowed mime types 
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    // Validate whether selected file is a Excel file 
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)) {

        // If the file is uploaded 
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet_arr = $worksheet->toArray();


            // Remove header row 
            unset($worksheet_arr[0]);

            foreach ($worksheet_arr as $row) {
                //odl
                // $name = $row[1]; 
                // $email = $row[2];
                // $cityc = $row[3];
                //new
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

                

                $findcityc =  "SELECT * FROM city_council where city_council LIKE'" . $cityc . "'";
                $resultc = $conn->query($findcityc);
                if ($resultc->num_rows > 0) {
                    while ($row = $resultc->fetch_assoc()) {
                        $cityc = $row['id'];
                    }
                } else {
                    $cityc = NULL;
                }
                
                if($lightcount == null || $lightcount = "" || $lightcount == 0){
                    $lightcount = 0;
                }
                
                



                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT id FROM pax WHERE name = '" . $name . "'";
                $prevResult = $conn->query($prevQuery);

                if ($prevResult->num_rows > 0) {

                    // Update member data in the database 
                    // $conn->query("UPDATE members SET first_name = '".$first_name."', last_name = '".$last_name."', email = '".$email."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'"); 
                } else {
                    // Insert member data in the database 
                    // $conn->query("INSERT INTO pax (name, email,) VALUES ('".$name."', '".$email."'"); 
                    // $sql = "UPDATE pax SET status=1 WHERE id=".$id;
                    if($cityc != NULL){
                        $sql = "INSERT INTO `pax` (`id`, `name`, `email`, `city_council`,`designation`,`tel`,`building_name`,`starta_status`,`number_lights`,`210lm_proposal`,`light_count`,`scnd_5yrs`,`led_total`,`proposal_pic`, `status`) VALUES (NULL,'$name', '$email', $cityc,' $designation','$tel','$building','$starta','$numberlight','$prop210',$lightcount,'$scnd5yrs','$led','$prop', '0')  ";

                    }else{
                        $sql = "INSERT INTO `pax` (`id`, `name`, `email`,`designation`,`tel`,`building_name`,`starta_status`,`number_lights`,`210lm_proposal`,`light_count`,`scnd_5yrs`,`led_total`,`proposal_pic`, `status`) VALUES (NULL,'$name', '$email',' $designation','$tel','$building','$starta','$numberlight','$prop210',$lightcount,'$scnd5yrs','$led','$prop', '0')  ";
                        
                    }
                    $conn->query($sql);
                   
                }
            }

            $qstring = '?status=succ';
        } else {
            $qstring = '?status=err';
        }
    } else {
        $qstring = '?status=invalid_file';
    }
} 
 
// Redirect to the listing page 
header("Location: index.php".$qstring);
