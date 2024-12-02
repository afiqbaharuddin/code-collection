<?php
// Database configuration
$host     = "localhost";
$db       = "synergy";
$username = "root"; // Adjust if your username is different
$password = "";

// Create a connection
$conn = new mysqli($host, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_pax($conn){
  $sql    = "SELECT id, nickname, email, tel FROM pax";
  $result = $conn->query($sql);

  $pax = array();

   if($result->num_rows > 0){
     while ($row = $result->fetch_assoc()){
       $pax[] = $row;
     }
   }

   header('Content-Type: application/json');
   echo json_encode($pax);
}

get_pax($conn);

 ?>
