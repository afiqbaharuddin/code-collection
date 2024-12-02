<?php 
require('db_conn.php');
    if(isset($_GET['countatt'])){
       $id = $_GET['id'];
	

        $data = countall($conn, 'pax', "status=1 AND event_id = $id");
	        echo $data;
        return $data;
    }
    else if(isset($_GET['paxlist'])){
 $id = $_GET['id'];

        $data = selectall($conn, 'pax', "status=1 AND event_id = $id");
        $PBT = selectall($conn, 'pax', "status=1 AND event_id = $id","`city_council` ON `pax`.`city_council` = `city_council`.`id`");
      
        foreach ($data as $key => $value) {
            $key++;
            $name = $value['name'];
            $build = $value['building_name'];
            $cc = $value['city_council'];
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
		$walk = "Online register";	}
                       
            echo "<tr>";
            echo "<td> $key</td>
            <td>$name</td>
            <td>$build</td>
            <td>$cc</td>
	    <td>$walk</td>";
            echo "</tr>";
        }

        return $PBT;
    }

?>