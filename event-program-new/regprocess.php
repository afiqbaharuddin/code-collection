<?php

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

    $direct = "TRUE";
    require('db_conn.php');
    require('controller/emailController.php');
    require('controller/WhatsappController.php');
    require('path.php');

    $name = $_POST['firstname']." ".$_POST['lastname'];

    if($_POST['designation'] == "NULL"){
      $designation = NULL;
    }elseif($_POST['designation'] == "Others"){
      $designation = $_POST['designation2'];
    }else{
      $designation = $_POST['designation'];
    }

    $email        = $_POST['email'];
    $tel          = $_POST['tel'];
    $building     = $_POST['building'];
    $city_council = $_POST['city_council'];

    if($city_council == "7"){
      $PMCMC = $_POST['othercom'];
      //insert new company name
      insert($conn,"city_council","city_council","'$PMCMC'");
      $lastid = selectall($conn,'city_council ORDER BY id DESC');
      $city_council = $lastid[0]['id'];
    }else{
      $city_council = $_POST['city_council'];
    }

    if($city_council == "NULL"){
      $city_council = null;
    }

    $light         = $_POST['light'];
    $starta_status = $_POST['starta_status'];
    $event_id      = $_POST['event_id'];

    $event    = selectall($conn,'event',"id = $event_id");
    $event    = $event[0]['date'];
    $currdate = date('Y-m-d');

    if($currdate >= $event){
      var_dump("Walkin");
      $walk   = 1;
      $status = 1;
    }else if($currdate < $event){
      var_dump("online register");
      $walk = 0;
      $status = 0;
    }

    $nickname  = $_POST['nickname'];
    $noresunit = $_POST['no_residents_unit'];

    // $typelight = $_POST['type_light'];
    // $no_blocks = $_POST['no_blocks'];ss
    // $parking   = $_POST['parking'];
    // $tariff    = $_POST['tariff'];
    // $contact_by = $_POST['contact_by'];
    // var_dump($tariff);

    if(empty($_POST['user_interest'])){
      $user_interest = "";
    }else{
      $user_interest = $_POST['user_interest'];
    }

    if(!empty($_POST['addParticipant'])){
      $participant = $_POST['addParticipant'] + 1;
    }else{
      $participant = 1;
    }

    //find email
    $femail = selectall($conn, 'pax', "email = '$email' AND event_id = $event_id");

    //check email duplication and redierct back if exist
    if(count($femail) < 1){

  		if($city_council == NULL){
  			$insert = insert($conn,'pax','name,nickname,designation,email,tel,building_name ,number_lights, type_light,no_residents_unit,no_blocks,parking,tariff, starta_status,walkin,event_id,status,contact_by,user_interest,registered',"
                  '$name'  ,'$nickname', '$designation'  , '$email'  , '$tel'  , '$building'    , '$light', '$typelight','$noresunit','$no_blocks','$parking','$tariff', '$starta_status','$walk','$id',$status,'$contact_by','$user_interest','$participant'");
  		}else{
  			$insert = insert($conn,'pax','name,nickname,designation,email,tel,building_name,city_council ,number_lights,no_residents_unit,starta_status,walkin,event_id,status,user_interest,registered',"
                  '$name'  ,'$nickname', '$designation'  , '$email'  , '$tel'  , '$building','$city_council','$light','$noresunit','$starta_status','$walk','$event_id',$status,'$user_interest','$participant'");
  		}

  		if($insert == 1){
  			die("ERROR");
  		}else if($insert == 0){

			$selast    = selectall($conn,'pax ORDER BY id DESC');
			$selast    = $selast[0];
      $pax_id    = $selast['id'];
			$eventname = selectall($conn,"event","id = $event_id");
			$event     = $eventname[0]['name'];
			$address   = $eventname[0]['address'];
			$maps      = $eventname[0]['maps'];
			$flyer     = $eventname[0]['thumbnail_img'];
			$banner    = $eventname[0]['banner_img'];
			$view      = "invite";

			if($walk == 0){
				//if not walk in status send email invitation
				$status   = sendmail($email,$selast['id'],$selast['name'],$event,$address,$maps,$path,$view,null,NULL,"true",$event_id,$flyer,$banner);
				if($status == true){
          // WhatsApp message
          notifyUser($pax_id, $tel);  // Pass the $tel variable
					update($conn, 'pax', 'email_sent = 1', $pax_id);
					// header("location: tq.php?id=$id");
				}
			}else{
				// header("location: tq.php?id=$id");
			}

      $participantCount = $_POST['addParticipant'];
      // Loop through additional participants
      for ($i = 0; $i < $participantCount; $i++) {
          $participantName  = isset($_POST['participantName' . $i]) ? $_POST['participantName' . $i] : null;
          $participantPhone = isset($_POST['participantPhone' . $i]) ? $_POST['participantPhone' . $i] : null;
          $designation      = isset($_POST['designation' . $i]) ? $_POST['designation' . $i] : null;

          // Insert only if both name and phone number are provided
          if (!empty($participantName) && !empty($participantPhone)) {
              // Insert participant data and get participant ID
              $participantId = insert($conn, 'participant',
                  'pax_id, name, phone, designation, event_id, building_name, city_council, starta_status, walkin',
                  "'$pax_id', '$participantName', '$participantPhone', '$designation', '$event_id', '$building', '$city_council', '$starta_status', '$walk'"
              );

              // WhatsApp message to additional participants
              // Use the main participant's pax_id for the URL, but use the participant's phone number and participantId
              notifyUser($pax_id, $participantPhone);  // Still pass the main pax_id for URL, but use participantPhone for messaging
          }
      }

		}
	}else{

		session_start();
		$error = "Email already exist try register with different email";
		$_SESSION['error'] = $error;
		header("location: form-registration.php?id=$id");
	}
?>
