<?php
session_start();
require('db_conn.php');

// $id = $_POST['id'];
//
// echo $id;
//
// $sql = "UPDATE pax SET status=1 WHERE id=".$id;
//
// if ($conn->query($sql) === TRUE) {
//   echo "Record updated successfully";
//   header("Location: attend/index.php?status=success");
// } else {
//   echo "Error updating record: " . $conn->error;
//   header("Location: attend/index.php?status=error");
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pax_id  = $_POST['id'];
  $eventid = $_POST['event_id'];

  // Update pax status
  $statusPax = isset($_POST['pax_status']) ? 1 : 0;
  $updatePax = $conn->prepare("UPDATE pax SET status = ? WHERE id = ?");

  $updatePax->bind_param("ii", $statusPax, $pax_id);
  if (!$updatePax->execute()) {
    // Handle query error
    header("Location: attend/index.php?status=error");
    exit();
  }

  // Update all participants to status 0 first for the given pax_id
  $resetParticipants = $conn->prepare("UPDATE participant SET status = 0 WHERE pax_id = ?");

  $resetParticipants->bind_param("i", $pax_id);
  if (!$resetParticipants->execute()) {
    // Handle query error
    header("Location: attend/index.php?status=error");
    exit();
  }

  //update only the checked participants to status 1
  if (isset($_POST['participant_status'])) {
    foreach ($_POST['participant_status'] as $participantId) {

      $updateParticipant = $conn->prepare("UPDATE participant SET status = 1 WHERE participant_id = ?");

      $updateParticipant->bind_param("i", $participantId);
      if (!$updateParticipant->execute()) {
        // Handle query error
        header("Location: attend/index.php?status=error");
        exit();
      }
    }
  }

  // Count participants with status = 1 in pax table
  $countPaxQuery = $conn->prepare("SELECT COUNT(*) as count FROM pax WHERE id = ? AND status = 1");

  $countPaxQuery->bind_param("i", $pax_id);
  $countPaxQuery->execute();

  $resultPax = $countPaxQuery->get_result();
  $countPax  = $resultPax->fetch_assoc()['count'];

  // Count participants with status = 1 in participant table
  $countParticipantQuery = $conn->prepare("SELECT COUNT(*) as count FROM participant WHERE pax_id = ? AND status = 1");

  $countParticipantQuery->bind_param("i", $pax_id);
  $countParticipantQuery->execute();

  $resultParticipant = $countParticipantQuery->get_result();
  $countParticipant  = $resultParticipant->fetch_assoc()['count'];

  // Update attended column in pax table
  $attendedCount     = $countPax + $countParticipant;
  $updatePaxAttended = $conn->prepare("UPDATE pax SET attended = ? WHERE id = ?");

  $updatePaxAttended->bind_param("ii", $attendedCount, $pax_id);
  if (!$updatePaxAttended->execute()) {
    // Handle query error
    header("Location: attend/index.php?status=error");
    exit();
  }

  // Set the session variable to trigger the modal on the next page load
  $_SESSION['checkin_success'] = true;

  // Redirect to the event page
  header('Location:'.$_SERVER['HTTP_REFERER']);
  exit();
} else {
  header("Location: attend/index.php?status=error");
  exit();
}

$conn->close();

?>
