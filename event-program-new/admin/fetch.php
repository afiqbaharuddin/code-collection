<?php
require('../db_conn.php');

    if(isset($_GET['countatt'])){
      $id = $_GET['id'];
      $data = countall($conn, 'pax', "status=1 AND event_id = $id");
      echo $data;
      return $data;
    } else if(isset($_GET['paxlist'])){

      $id   = $_GET['id'];
      $data = selectall($conn, 'pax', "status=1 AND event_id = $id");
      $PBT  = selectall($conn, 'pax', "status=1 AND event_id = $id","`city_council` ON `pax`.`city_council` = `city_council`.`id`");

      foreach ($data as $key => $value) {

        $key++;
        $name  = $value['name'];
        $build = $value['building_name'];
        $cc    = $value['city_council'];

        if($cc == null){
          $cc = $cc;
        }else{
          $cc = selectall($conn,'city_council',"id = $cc");
          $cc = $cc[0]['city_council'];
        }

        $walk = $value['walkin'];
        if($walk == 1){
  	      $walk = "Walk-in";
        }else{
    	    $walk = "Online register";
        }

        echo "<tr>";
        echo "<td> $key</td>
        <td>$name</td>
        <td>$build</td>
        <<td>$cc</td>
         <td>$walk</td>";
            echo "</tr>";
        }
        return $PBT;
      }

      if (isset($_GET['attCount'])) {
        $id   = $_GET['id'];
        $data = countall_attended($conn,'pax',"event_id = $id");
        echo $data;
        return $data;
      }elseif (isset($_GET['attList'])) {

        $id = $_GET['id'];

        $paxData         = selectall($conn, 'pax', "status = 1 AND event_id = $id");
        $participantData = selectall($conn, 'participant', "status=1 AND pax_id IN (SELECT id FROM pax WHERE event_id = $id)");

        // Initialize the key counter
        $key = 0;

        // Display main participants from the pax table
        foreach ($paxData as $value) {
          $key++;
          $name  = $value['name'];
          $build = $value['building_name'];
          $cc    = $value['city_council'];

          if ($cc !== null) {
            $cc = selectall($conn, 'city_council', "id = $cc");
            $cc = $cc[0]['city_council'];
          }

          $walk = $value['walkin'] == 1 ? "Walk-in" : "Online register";

          echo "<tr>";
          echo "<td>$key</td>
                <td>$name</td>
                <td>$build</td>
                <td>$cc</td>
                <td>$walk</td>";
          echo "</tr>";
        }

        // Display additional participants from the participant table
        foreach ($participantData as $value) {
          $key++;
          $name      = $value['name'];
          $pax_id    = $value['pax_id'];
          $linkedPax = selectall($conn, 'pax', "id = $pax_id");
          $build     = $linkedPax[0]['building_name'];
          $cc        = $linkedPax[0]['city_council'];
          $walk      = $linkedPax[0]['walkin'] == 1 ? "Walk-in" : "Online register";

          if ($cc !== null) {
              $cc = selectall($conn, 'city_council', "id = $cc");
              $cc = $cc[0]['city_council'];
          }

          echo "<tr>";
          echo "<td>$key</td>
                <td>$name</td>
                <td>$build</td>
                <td>$cc</td>
                <td>$walk</td>";
          echo "</tr>";
        }
      }elseif (isset($_GET['regCount'])) {
        $id   = $_GET['id'];
        $data = countall_registered($conn,'pax',"event_id = $id");
        echo $data;
        return $data;
      }

?>
