<?php
require('../db_conn.php');

if (isset($_POST)) {
  //for update participant data in table pax
  $names          = $_POST['name'];
  $phone_nos      = $_POST['phone_no'];
  $building_names = $_POST['building_name'];
  $city_councils  = $_POST['city_council'];
  $walkins        = $_POST['walkin'];
  $statuss        = $_POST['status'];

  $id     = $_POST['id'];
  $allpax = selectall($conn, 'pax', "event_id = $id");

  foreach ($allpax as $index => $pax) {
    $name          = $names[$index];
    $phone_no      = $phone_nos[$index];
    $building_name = $building_names[$index];
    $city_council  = $city_councils[$index];
    $walkin        = $walkins[$index];
    $status        = $statuss[$index];

    $query = "UPDATE pax SET
              name          = '$name',
              tel           = '$phone_no',
              building_name = '$building_name',
              city_council  = '$city_council',
              walkin        = '$walkin',
              status        = '$status'
              WHERE id      = " . $pax['id'];

    $result = mysqli_query($conn, $query);

    if (!$result) {
      echo "Error updating pax data: ".mysqli_error($conn);
      exit;
    }
  }

  //for update participant data in table participant
  $participant_names          = $_POST['participant_name'];
  $participant_phone_nos      = $_POST['participant_phone_no'];
  $participant_building_names = $_POST['participant_building_name'];
  $participant_city_councils  = $_POST['participant_city_council'];
  $participant_walkins        = $_POST['participant_walkin'];
  $participant_statuss        = $_POST['participant_status'];

  $participant_id = $_POST['participant_id'];
  $allparticipant = selectall($conn, 'participant', "event_id = $id");

  foreach ($allparticipant as $index => $participant) {
    $name          = $participant_names[$index];
    $phone_no      = $participant_phone_nos[$index];
    $building_name = $participant_building_names[$index];
    $city_council  = $participant_city_councils[$index];
    $walkin        = $participant_walkins[$index];
    $status        = $participant_statuss[$index];

    $query = "UPDATE participant SET
              name                 = '$name',
              phone                = '$phone_no',
              building_name        = '$building_name',
              city_council         = '$city_council',
              walkin               = '$walkin',
              status               = '$status'
              WHERE participant_id = " . $participant['participant_id'];

    $result = mysqli_query($conn, $query);

    if (!$result) {
      echo "Error updating participant data: ".mysqli_error($conn);
      exit;
    }
  }

  header('Location:'.$_SERVER['HTTP_REFERER']);
  exit;
}


 ?>
