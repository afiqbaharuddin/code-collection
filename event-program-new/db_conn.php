<?php
 //production details
 // $servername = "192.168.0.14:3306";
 // $username = "icleanic15";
 // $password = "Password1234!!";
 // $db = "synergy";

$servername = "localhost";
$username   = "root";
$password   = "";
$db         = "synergy";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//sql select statments
// function selectall($conn, $table, $where = null, $join = null) {
//
//   $arr = [];
//   if ($where != null && $join != null) {
//     $sql = "SELECT * FROM $table JOIN $join" . " where " . $where;
//     $result = $conn->query($sql);
//   }
//
//   if ($where == null) {
//     $sql = "SELECT * FROM $table";
//     $result = $conn->query($sql);
//   } else {
//     $sql = "SELECT * FROM $table" . " where " . $where;
//     $result = $conn->query($sql);
//   }
//
//   if ($result->num_rows > 0) {
//     // output data of each row
//     while ($row = $result->fetch_assoc()) {
//       array_push($arr, $row);
//     }
//   } else {
//   }
//   return $arr;
// }

function selectall($conn, $table, $where = null, $join = null) {

  $arr = [];
  $sql = "";

  if ($where != null && $join != null) {
    $sql = "SELECT * FROM $table JOIN $join" . " where " . $where;
  } elseif ($where != null) {
    $sql = "SELECT * FROM $table" . " where " . $where;
  } else {
    $sql = "SELECT * FROM $table";
  }

  $result = $conn->query($sql);

  if (!$result) {
    die("Query failed: " . $conn->error);
  }

  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      array_push($arr, $row);
    }
  } else {
    // You might want to add some error handling here, e.g. return an empty array or throw an exception
  }
  return $arr;
}

//count function
function countall($conn, $table, $where = null)
{
  $count = 0;
  if ($where == null) {
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
  } else {
    $sql = "SELECT * FROM $table" . " where " . $where;

    $result = $conn->query($sql);
  }

  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      // echo "id: " . $row["id"] . " - Name: " . $row["name"] . " " . $row["email"] . "<br>";
      $count++;
    }
  } else {
  }
  return $count;
}

//get a distinct value from sql
function distict($conn, $table, $column, $count = 0, $where = null, $join = null)
{

  $counter = 0;
  if ($join != null) {

    if ($where != null) {
      $sql = "SELECT DISTINCT $column FROM `$table` JOIN $join WHERE $where";
    } else {
      $sql = "SELECT DISTINCT $column FROM `$table` JOIN $join";

    }
  } else if ($where != null) {
    $sql = "SELECT DISTINCT `$column` FROM `$table` WHERE $where";
  } else {
    $sql = "SELECT DISTINCT `$column` FROM `$table`";
  }

  $result = $conn->query($sql);

  if ($count == 1) {

    if ($result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        // echo "id: " . $row["id"] . " - Name: " . $row["name"] . " " . $row["email"] . "<br>";
        $counter++;
      }
    } else {
    }
    return $counter;
  } else {
    $arr = [];
    if ($result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        // echo "id: " . $row["id"] . " - Name: " . $row["name"] . " " . $row["email"] . "<br>";
        array_push($arr, $row);
      }
    }

    return $arr;
  }
}

function countspecial($conn, $where = null)
{
  if ($where != null) {
    $sql = "SELECT DISTINCT `pax`.`city_council` FROM `pax` JOIN
    `city_council` ON `city_council`.`id` = `pax`.`city_council` WHERE $where;";
  } else {
    $sql = "SELECT DISTINCT `pax`.`city_council` FROM `pax` JOIN
    `city_council` ON `city_council`.`id` = `pax`.`city_council`;";
  }


  $counter = 0;
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      // echo "id: " . $row["id"] . " - Name: " . $row["name"] . " " . $row["email"] . "<br>";

      $counter++;
    }
  } else {
  }

  return $counter;
}


//global insert statments
function insert($conn, $table, $column, $value)
{

  $sql = "INSERT INTO $table ($column) VALUES ($value)";
  $error = 1;
  if ($conn->query($sql) === TRUE) {
    $error = 0;

    // echo "New record created successfully";

  } else {
    $error = 1;
    echo "Error: " . $sql . "<br>" . $conn->error;

  }
  return $error;
}

//global update statments
function update($conn, $table, $column, $id)
{
  // $sql = "UPDATE MyGuests SET lastname='Doe' WHERE id=2";

  $sql = "UPDATE $table SET $column WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }
}

//global delete statments
function delete($conn, $table, $id)
{
  $sql = "DELETE FROM $table WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }
}

function countall_registered($conn, $table, $where) {
    $sql    = "SELECT SUM(registered) AS total_registered FROM $table WHERE $where";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    return $row['total_registered'] ? $row['total_registered'] : 0;
}

function countall_attended($conn, $table, $where) {
    $sql    = "SELECT SUM(attended) AS total_attended FROM $table WHERE $where";
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    return $row['total_attended'] ? $row['total_attended'] : 0;
}
