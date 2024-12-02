<?php
require('../db_conn.php');
session_start();

$role     = $_SESSION['role'];
$eventid  = $_GET['id'];
$eventdet = selectall($conn,'event',"id = $eventid");
$eventdet = $eventdet[0];
$date     = $eventdet['date'];
$time     = strtotime($date);
$date     = date('j F Y', $time);
$day      = date('l', $time);

if(isset($_GET['cob'])){

}else{
    $select = selectall($conn,'pax',"event_id = $eventid");
}

//today date
$today = strtotime(date('Y-m-d'));

//check date pass event date
$pastEvent = $today > $time;

foreach ($select as $key => $value) {
    $id    = $value['id'];
    $name  = $value['name'];
    $email = $value['email'];
    $build = $value['building_name'];
    $cityc = $value['city_council'];
    $cityc = selectall($conn,'city_council',"id = $cityc");
    $cityc = $cityc[0]['city_council'];

    $participants = selectall($conn, 'participant', "pax_id = $id");

    echo "<div class=\"modal fade\" id=\"modalinfouser$id\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h1 class=\"modal-title fs-5\" id=\"exampleModalLabel\">Green Initiative Program</h1>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <p class=\"text-muted\">Program for the residential buildings to ease the burden of high electricity bills by achieving 80% of savings on lighting and reduce carbon footprint.</p>

                <div class=\"card card-event border-0 border-bottom\">
                    <div class=\"card-body d-flex\">
                        <i class=\"bi bi-calendar3\"></i>
                        <div>
                            <h6 class=\"mb-0\">$date</h6>
                            <p><small>$day</small></p>
                        </div>
                    </div>
                </div>
                <div class=\"card card-event border-0\">
                    <div class=\"card-body d-flex\">
                        <i class=\"bi bi-geo-alt-fill\"></i>
                        <div>
                            <h6 class=\"mb-0\">$eventdet[name]</h6>
                            <p><small>$eventdet[address]</small></p>
                        </div>
                    </div>
                </div>

                <div class=\"card card-register mt-3\">
                    <div class=\"card-header\">
                        <h6>Participant Details</h6>
                    </div>
                    <div class=\"card-body\">
                        <p>Name : <span class=\"fw-bold\">$name</span></p>
                        <p>Email : <span class=\"fw-bold\">$email</span></p>
                        <p>Building Name : <span class=\"fw-bold\">$build</span></p>
                        <p>City Council : <span class=\"fw-bold\">$cityc</span></p>

                        <h6 class=\"mt-3\">Participants:</h6>
                        <form class=\"mt-1\" action=\"../process.php\" method=\"post\">
                            <input type=\"hidden\" name=\"id\" value=\"$id\">
                            <input type=\"hidden\" name=\"event_id\" value=\"$eventid\">
                            <ul>";

                        if ($role != 1 && $role != 3) {
                          $readonly = 'onclick="return false;"';
                        } else {
                          $readonly = '';
                        }

                        // Checkbox for pax name
                        $paxChecked = $value['status'] == 1 ? 'checked' : '';
                        echo "<li>". $name . " (" . $value['tel']. ") <input type=\"checkbox\" name=\"pax_status\" value=\"$id\" $paxChecked $readonly></li>";

                        // Checkbox for each participant
                        foreach ($participants as $participant) {
                        $participantChecked = $participant['status'] == 1 ? 'checked' : '';
                        echo "<li>" . $participant['name'] . " (" . $participant['phone'] . ")
                              <input type=\"checkbox\" name=\"participant_status[]\" value=\"" . $participant['participant_id'] . "\" $participantChecked $readonly></li>";
                        }

                        echo "</ul>";

                        $disabled = $pastEvent ? 'disabled' : '';
                        if ($role == 1 || $role == 3) {
                          echo "<input type=\"submit\" class=\"btn btn-primary mt-3\" value=\"Check-in\" $disabled>";
                        }
                        if ($role == 3) {
                          echo "<button class=\"btn btn-danger font-sz mt-3 me-2 ms-2\"> <a href=\"../admin/deletepax.php?eventid=$eventid&id=$id\" class=\"text-white\" >Delete</a>  </button>";
                        }
                        echo "</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";
}
?>
