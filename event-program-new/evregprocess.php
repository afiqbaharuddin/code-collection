<?php

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

$direct = "TRUE";
    require('db_conn.php');
    require('controller/emailController.php');
    require('path.php');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    // if($event == now())
    // var_dump($_POST['user_interest']);
    // var_dump($_POST['name']);
    // var_dump($_POST['email']);
    // var_dump($_POST['tel']);
    // var_dump($_POST['rep']);
    // var_dump($_POST['id']);
    // var_dump($_POST['contact_by']);

    $name          = $_POST['name'];
    $email         = $_POST['email'];
    $tel           = $_POST['tel'];
    $tel           = "+60".$tel;
    $rep           = $_POST['rep'];
    $id            = $_POST['id'];
    $stname        = $_POST['str_name'];
    $no_unit       = $_POST['no_units'];
    $parking       = $_POST['parking'];
    $shine         = $_POST['shinesalad'];
    //contact by condition
    //email - 1  mobile -2

    $eventdet    = selectall($conn, 'event', "id = $id");
    $publicevent = $eventdet[0]["spec_event"];

    // var_dump($eventdet[0]["spec_event"]);
    // var_dump($name);

    if($publicevent == "2"){
      $user_interest = $_POST['user_interest'];
      $fullname      = $_POST['fname']." ".$_POST['lname'];
      $nickname      = $_POST['nickname'];
      $designation   = $_POST['designation'];
      $date_ev       = $_POST['date_ev'];
      $name          = $_POST['fname']." ".$_POST['lname'];
      $build_name    = $_POST['building'];
      $cc            = $_POST['city_council'];
      $starta_stts   = $_POST['starta_status'];
      $contact_by    = $_POST['contact_by'];

      if(isset($_POST['designation2'])){
          $newdesig    = $_POST['designation2'];
          $designation = $newdesig;
      }

      if(isset($_POST['othercom'])){
          $PMCMC = $_POST['othercom'];
          //insert new company name
          insert($conn,"city_council","city_council,is_pmc","'$PMCMC','1'");
          $lastid       = selectall($conn,'city_council ORDER BY id DESC');
          $city_council = $lastid[0]['id'];
          $cc           = $city_council;
      }

      $insert = insert($conn,'pax','name,nickname,designation,email,tel,building_name ,number_lights, type_light,no_residents_unit,no_blocks,parking,tariff, starta_status,walkin,event_id,status,starta_name,contact_by,date_event,city_council,user_interest',"'$fullname'  ,'$nickname', '$designation'  , '$email'  , '$tel'  , '$build_name', '', '','$no_unit','','$parking','', '$starta_stts',0,'$id',0,'$stname','$contact_by','$date_ev','$cc','$user_interest'");

    }else{
      $insert = insert($conn,'pax','name,nickname,designation,email,tel,building_name ,number_lights, type_light,no_residents_unit,no_blocks,parking,tariff, starta_status,walkin,event_id,status,starta_name,user_interest,shine_interest',"'$name'  ,'', '$rep'  , '$email'  , '$tel'  , ''    , '', '','$no_unit','','$parking','', '',0,'$id',0,'$stname','','$shine'");
}

    header("location: https://www.synergy-group.com.my/green-initiative.html");





?>
