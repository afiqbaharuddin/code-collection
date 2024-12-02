<?php
require('../db_conn.php');
if (isset($_GET['countatt'])) {
    $id  = $_GET['id'];
    $cob = $_GET['cob'];


    $data = countall($conn, 'pax', "status=1 AND event_id = $id AND city_council = $cob");
    $dataall = countall($conn, 'pax', "event_id = $id AND city_council = $cob");
    echo "$data / $dataall";

    return $data;
} else if (isset($_GET['paxlist'])) {

    $id = $_GET['id'];

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    }

    if (isset($_GET['cob'])) {
        if (isset($_GET['filter'])) {
            $filter = $_GET['filter'];

            $cob = $_GET['cob'];
            switch ($filter) {
                case '1':
                    $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob");
                    break;
                case '2':
                    $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob AND status = 1");
                    break;
                case '3':
                    $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob AND status = 0");
                    break;

                default:
                    break;
            }
        } else {
            $cob = $_GET['cob'];
            $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob");
        }
    } else {

        if (isset($_GET['filter'])) {

            $filter = $_GET['filter'];
            switch ($filter) {
              case '1':
                  $paxattend = selectall($conn, 'pax', "event_id = $id ");
                  break;
              case '2':
                  $paxattend = selectall($conn, 'pax', "event_id = $id  AND status = 1");
                  break;
              case '3':
                  $paxattend = selectall($conn, 'pax', "event_id = $id  AND status = 0");
                  break;

              default:
              break;
            }
        } else {
          $paxattend = selectall($conn, 'pax', "event_id = $id ");
        }
    }

    foreach ($paxattend as $key => $value) {

        $key++;
        $ciid = $value['city_council'];

        if ($ciid != null) {
            $citycon = selectall($conn, 'city_council', "id =$ciid");
            $citycon = $citycon[0]['city_council'];
        } else {
            $citycon = "";
        }

        $name     = $value['name'];
        $building = $value['building_name'];

        echo "<tr>";
        echo "<td>$key</td>
            <td>$name</td>
            <td>$building</td>
            <td>$citycon</td>";

        if ($value['walkin'] == 1) {
            echo  "<td>Walkin</td>";
        } else {
            echo  "<td>online register</td>";
        }

        if ($value['status'] == 1) {
            echo "<td><i class=\"bi bi-circle-fill text-primary\"></i> Attend </td>";
        } else {
            echo "<td><i class=\"bi bi-circle-fill text-danger\"></i> Absent</td>";
        }

        echo "</tr>";
    }

    return $paxattend;


    // foreach ($data as $key => $value) {
    //     $key++;
    //     $name = $value['name'];
    //     $build = $value['building_name'];
    //     $cc = $value['city_council'];
    //     if($cc == null){
    //         $cc = $cc;

    //     }else{
    //         $cc = selectall($conn,'city_council',"id = $cc");

    //         $cc = $cc[0]['city_council'];
    //     }



    // $walk = $value['walkin'];
    // if($walk == 1){
    // $walk = "Walk-in";
    //     }else{
    // $walk = "Online register";	}

    //     echo "<tr>";
    //     echo "<td> $key</td>
    //     <td>$name</td>
    //     <td>$build</td>
    //     <td>$cc</td>
    // <td>$walk</td>";
    //     echo "</tr>";
    // }
} else if (isset($_GET['paxcount'])) {
  $id = $_GET['id'];
  $selecatt = countall($conn, 'pax', "event_id = $id AND status = 1");
  $selecabs = countall($conn, 'pax', "event_id = $id AND status = 0");

  if (isset($_GET['filter'])) {
      $filter = $_GET['filter'];
      switch ($filter) {
          case '1':
              $data = "$selecatt,$selecabs";
              break;
          case '2':
              $data = "$selecatt";
              break;
          case '3':
              $data = "$selecabs";
              break;

          default:
              break;
      }
  }else{
      $data = "not found";
  }


  echo $data;
}

////////////////////////////////////////////////////////////////////////////////
//New filter fetch

if (isset($_GET['id'])) {
    $id     = $_GET['id'];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 1;

    // Base query for main participants (pax) and linked additional participants
    $query = "SELECT p.id AS pax_id, p.name AS pax_name, p.building_name, p.city_council, p.walkin, p.status AS pax_status,
                     pt.participant_id AS participant_id, pt.name AS participant_name, pt.status AS participant_status
              FROM pax p
              LEFT JOIN participant pt ON p.id = pt.pax_id
              WHERE p.event_id = $id";

    // Adjust the query based on the filter
    if ($filter == 2) {
        $query .= " AND ((p.status = 1) OR (pt.status = 1))";
    } elseif ($filter == 3) {
        $query .= " AND ((p.status = 0) OR (pt.status = 0))";
    }

    // Execute the query
    $result = $conn->query($query);

    // Display the results
    if ($result->num_rows > 0) {
        $key = 0;

        echo "<table class='table w-100 nowrap'>";
        echo "<thead>
                <tr class='att'>
                    <th>#</th>
                    <th>Name</th>
                    <th>Building</th>
                    <th>City Council</th>
                    <th>Registration Type</th>
                    <th>Status</th>
                </tr>
              </thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {

            // Get city council name
            $ciid = $row['city_council'];
            if ($ciid != null) {
                $cityconResult = selectall($conn, 'city_council', "id = $ciid");
                $citycon       = $cityconResult[0]['city_council'];
            } else {
                $citycon = "";
            }

            // Determine registration type
            $walkin = $row['walkin'] == 1 ? "Walkin" : "Online Register";

            // Always display participants if "Show All" is selected
            if ($filter == 1 || ($filter == 2 && $row['pax_status'] == 1)) {

                $key++;
                // Show main participant
                $status = $row['pax_status'] == 1 ? "<i class=\"bi bi-circle-fill text-primary\"></i> Attend" : "<i class=\"bi bi-circle-fill text-danger\"></i> Absent";

                echo "<tr>";
                echo "<td>$key</td>";
                echo "<td>{$row['pax_name']}</td>";
                echo "<td>{$row['building_name']}</td>";
                echo "<td>$citycon</td>";
                echo "<td>$walkin</td>";
                echo "<td>$status</td>";
                echo "</tr>";
            }

            if ($filter == 1 || ($filter == 2 && $row['participant_status'] == 1)) {

              $key++;
              $status = $row['participant_status'] == 1 ? "<i class=\"bi bi-circle-fill text-primary\"></i> Attend" : "<i class=\"bi bi-circle-fill text-danger\"></i> Absent";

              echo "<tr>";
              echo "<td>$key</td>";
              echo "<td>{$row['participant_name']}</td>";
              echo "<td>{$row['building_name']}</td>";
              echo "<td>$citycon</td>";
              echo "<td>$walkin</td>";
              echo "<td>$status</td>";
              echo "</tr>";
            }

            // Handle Absent filter
            if ($filter == 3 && $row['pax_status'] == 0) {

              $key++;
              $status = "<i class=\"bi bi-circle-fill text-danger\"></i> Absent";
              echo "<tr>";
              echo "<td>$key</td>";
              echo "<td>{$row['pax_name']}</td>";
              echo "<td>{$row['building_name']}</td>";
              echo "<td>$citycon</td>";
              echo "<td>$walkin</td>";
              echo "<td>$status</td>";
              echo "</tr>";
            }

            if ($filter == 3 && $row['participant_status'] == 0) {
              $key++;
              $status = "<i class=\"bi bi-circle-fill text-danger\"></i> Absent";
              echo "<tr>";
              echo "<td>$key</td>";
              echo "<td>{$row['participant_name']}</td>";
              echo "<td>{$row['building_name']}</td>";
              echo "<td>$citycon</td>";
              echo "<td>$walkin</td>";
              echo "<td>$status</td>";
              echo "</tr>";
          }
        }

        echo "</tbody>";
        echo "</table>";
    } else {
      echo "<table class='table w-100 nowrap'>";
      echo "<thead>
              <tr class='att'>
                  <th>#</th>
                  <th>Name</th>
                  <th>Building</th>
                  <th>City Council</th>
                  <th>Registration Type</th>
                  <th>Status</th>
              </tr>
            </thead>";
      echo "<tbody>";
      echo "<tr>
              <td colspan='6' style=\"text-align:center\">No participants found.</td>
            </tr>";
      echo "</tbody>";
      echo "</table>";
    }
}
