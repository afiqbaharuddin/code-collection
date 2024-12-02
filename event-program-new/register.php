<?php
require('db_conn.php');
session_start();

$pid        = $_GET['id'];
$pax        = selectall($conn, 'pax', "id = $pid");
$pax        = $pax[0];
$citycon    = $pax['city_council'];
$cityc      = selectall($conn, 'city_council', "id = $citycon");
$cityc      = $cityc[0]['city_council'];
$event_id   = $pax['event_id'];
$event      = selectall($conn, 'event', "id = $event_id");
$event_date = $event[0]['date'];
$eventdet   = $event[0];
$time       = strtotime($eventdet['date']);
$date       = date('j F Y', $time);
$day        = date('l', $time);


// Fetch participants associated with this pax
$participants = selectall($conn, 'participant', "pax_id = $pid");

// Check if check-in was successful
$checkinSuccess = isset($_SESSION['checkin_success']) ? $_SESSION['checkin_success'] : false;

// Clear the success message after displaying it
unset($_SESSION['checkin_success']);

//enable check in button if day today == event day
$today       = date('Y-m-d');
$eventDate   = date('Y-m-d', $time);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Initiative Program | Synergy Esco (M) sdn Bhd</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-register {
            border: 2px solid #07a479;
        }

        @media (max-width: 991px) {
            .event-details {
                order: 2;
                margin-top: 2rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg border-bottom">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <img src="/images/logo/synery group logo -.png" alt="" class="img-fluid logo">
                </a>
                <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <div id="line1"></div>
                    <div id="line2"></div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../green-initiative.html">Back To Green Initiative</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="py-5">
        <div class="container">
            <?php
            $now = date('Y-m-d');
            $time = strtotime($eventdet['date']);
            $date = date('j F Y', $time);
            $day = date('l', $time);

            $start_t = strtotime($eventdet['start_time']);
            $start_t = date('g:i a', $start_t);
            $end_t = strtotime($eventdet['end_time']);
            $end_t = date('g:i a', $end_t);

            $merge_se = "$start_t - $end_t";
            ?>

            <h1 class="text-gip">Green Initiative Program</h1>
            <h6 class="mb-5 fw-800 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</h6>
            <div class="row mb-4">
                <div class="col-lg-5 event-details pe-md-5">
                    <p class="text-justify mb-3">Program for the residential buildings to ease the burden of high electricity bills by achieving 80% of savings on lighting and reduce carbon footprint. REGISTER TO SAVE YOUR SEAT NOW!!</p>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-event border-bottom">
                                <div class="card-body d-flex">
                                    <i class="bi bi-calendar3"></i>
                                    <div>
                                        <h6 class="mb-0"><?= $date ?></h6>
                                        <p><small><?= $day ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-event border-bottom">
                                <div class="card-body d-flex">
                                    <i class="bi bi-clock"></i>
                                    <div>
                                        <h6 class="mb-0"><?= $merge_se ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-event">
                        <div class="card-body d-flex">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <a target="_blank" href="<?= $eventdet['maps'] ?>">
                                    <h6 class="mb-0"><?= $eventdet['location_name'] ?></h6>
                                    <h4 class="mb-0"><?= $eventdet['location_room'] ?></h4>
                                    <p><small><?= $eventdet['address'] ?></small></p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-register">
                        <div class="card-header">
                            <h6>Participant Details</h6>
                        </div>
                        <div class="card-body">
                            <p>Name          : <span class="fw-bold"><?= $pax['name'] ?></span></p>
                            <p>Email         : <span class="fw-bold"><?= $pax['email'] ?></span></p>
                            <p>Building Name : <span class="fw-bold"><?= $pax['building_name'] ?></span></p>
                            <p>City Council  : <span class="fw-bold"><?= $cityc ?></span></p>

                            <h6 class="mt-3">Participants:</h6>

                            <form class="mt-1" action="process.php" method="post">
                                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="event_id" value="<?= $event_id ?>">
                                <ul>
                                  <?php
                                  // Checkbox for the main participant (pax)
                                  $paxChecked = $pax['status'] == 1 ? 'checked' : '';
                                  echo "<li>" . $pax['name'] . " (" . $pax['tel'] . ") <input type=\"checkbox\" name=\"pax_status\" value=\"$pid\" $paxChecked></li>";

                                  // Checkbox for each additional participant
                                  foreach ($participants as $participant) {
                                    $participantChecked = $participant['status'] == 1 ? 'checked' : '';
                                    echo "<li>" . $participant['name'] . " (" . $participant['phone'] . ")
                                          <input type=\"checkbox\" name=\"participant_status[]\" value=\"" . $participant['participant_id'] . "\" $participantChecked></li>";
                                  }
                                  ?>
                                </ul>
                                <?php if ($eventDate == $today) { ?>
                                  <input type="submit" class="btn btn-primary mt-3" value="Check-in">
                                <?php }else { ?>
                                  <input type="submit" class="btn btn-primary mt-3" value="Check-in" disabled>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row py-3">
                <h6 class="mb-0 col-lg-6">© 2023 Synergy (ESCO) Malaysia. All Rights Reserved.</h6>
                <p class="col-lg-6 text-lg-end text-start">
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms & Conditions</a>
                </p>
            </div>
        </div>
    </footer>

		<!-- Bootstrap Modal -->
    <div class="modal fade" id="checkinSuccessModal" tabindex="-1" aria-labelledby="checkinSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkinSuccessModalLabel">Check-in Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You have successfully checked in.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="js/bootstrap/bootstrap.min.js"></script>

<script>
	 document.addEventListener('DOMContentLoaded', function() {
		 // Show the check-in success modal if the session variable is set
		 var checkinSuccess = <?= json_encode($checkinSuccess) ?>;
		 if (checkinSuccess) {
			 var successModal = new bootstrap.Modal(document.getElementById('checkinSuccessModal'));
			 successModal.show();
		 }
	 });
 </script>
