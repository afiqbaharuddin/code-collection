<?php

require('../db_conn.php');
require('../admin/protect.php');

$redirect    = "attend";
$id          = $_GET['id'];
$role        = $_SESSION['role'];

$eventdet    = selectall($conn, 'event', "id = $id");
$publicevent = $eventdet[0]['spec_event'];
$allevent    = selectall($conn, 'event');
$countevent  = countall($conn, 'event');

$allpax         = selectall($conn, 'pax', "event_id = $id");
$allparticipant = selectall($conn, 'participant', "event_id = $id");

$start_t  = strtotime($eventdet[0]['start_time']);
$start_t  = date('g:i a', $start_t);
$end_t    = strtotime($eventdet[0]['end_time']);
$end_t    = date('g:i a', $end_t);
$merge_se = "$start_t - $end_t";

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Initiative Program | Synergy Esco (M) sdn Bhd</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <style media="screen">
    .gap-x-3{
        column-gap: 1rem;
    }
    .gap-y-3{
        row-gap: 1rem;
    }
    .pagination a {
        color: #444;
    }
    .active>.page-link,
    .page-link.active {
        background-color: #07a479 !important;
        color: white !important;
        border-color: #07a479 !important;
    }
    .text-primary {

        color: #07a479 !important;
    }
    .list-group li {
        padding: .75rem 1.25rem;
        border-bottom: none;
        border-radius: 8px;
        border: none;
    }

    .list-group li:hover {
        background: #f9f9f9;
        border-top: 2px solid #fff;
    }

    a {
        text-decoration: none;
        color: #404040;
    }

    a:hover {
        color: #333;
    }

    .bg-primary {
        background: #07a479 !important;
    }
    .card-table {
        border-radius: 10px;
    }
    .card-header .dropdown .dropdown-toggle::after {
        display: none;
    }

    #pax_length,
    #pax_filter,
    #pax_info {
        display: none;
    }

    .card-register {
        border: 2px solid #07a479;
    }
    .valign-middle {
        verticle-align: middle;
    }

    #cob:not(.show) + .col-lg-9 {
        width: 100%;
        transition: 1s ease-in-out;
    }

    #cob:not(.show) + .col-lg-9 .card .card-header .btn i.bi-fullscreen {
        display: none;
    }

    #cob.show + .col-lg-9 .card .card-header .btn i.bi-fullscreen-exit {
        display: none;
    }
  </style>

  <body class="h-100">
    <header>
      <nav class="navbar navbar-expand-lg border-bottom">
        <div class="container">
            <a href="/event-program/admin/dashboard.php" class="navbar-brand">
                <img src="../images/logo/new-logo.png" alt="dlogo" class="img-fluid logo">
            </a>
            <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <div id="line1"></div>
                <div id="line2"></div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <?php if (($role == "1" || $role == "3") && $publicevent == "0"): ?>
                      <li class="nav-item">
                          <a class="nav-link active" href="/event-program/admin/dashboard.php">Dashboard</a>
                      </li>
                    <?php endif; ?>

                    <?php if (($role == "1" || $role == "3") && $publicevent == "0"): ?>
                      <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="eventDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Event Forms
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="eventDropdown">
                          <li><a class="dropdown-item" href="/event-program/form-registration.php?id=<?= $id ?>">Invitation Form</a></li>
                          <li><a class="dropdown-item" href="/event-program/formevent.php?id=<?= $id ?>">Generic Form</a></li>
                        </ul>
                      </li>
                    <?php endif; ?>

                    <?php if ($role == "1" && $publicevent != "0"): ?>
                      <li class="nav-item">
                        <a class="nav-link" href="/event-program/formevent.php?id=<?= $id ?>">Generic Form</a>
                      </li>
                    <?php endif; ?>

                    <?php if ($role == "1" || $role == "3"): ?>
                      <li class="nav-item">
                        <a class="nav-link" href="/event-program/admin/listevent.php">List Event</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/event-program/admin/addevent.php">Add New Event</a>
                      </li>
                    <?php endif; ?>

              <!-- <li class="nav-item">
                        <a class="nav-link" href="/event-program/form-registration.php?id=<?= $id ?>">Registration</a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/green-initiative.html">Back To Green Initiative</a>
                    </li> -->
                    <?php
                    if (isset($_SESSION['user'])) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/logout.php">Logout</a>
                        </li>
                    <?php
                    }

                    ?>
                </ul>
            </div>
        </div>
      </nav>
    </header>

    <section class="py-5">
      <div class="container mb-5">

        <div class="d-flex justify-content-between align-items-center mb-5">
          <div class="">
            <h1 class="text-gip">Green Initiative Program</h1>
            <h6 class="fw-800 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</h6>
          </div>
        </div>

        <div class="row mt-3 mb-5">
          <div class="col-lg-6">
            <div class="card card-info-event px-3">
              <div class="card-body d-flex">
                <i class="bi bi-geo-alt-fill"></i>
                <div class="">
                  <h6 class="mb-0"><?= $eventdet[0]['name'] ?></h6>
                  <h6 class="mb-0"><?= $eventdet[0]['location_name'] ?></h6>
                  <p><small><?= $eventdet[0]['address'] ?></small></p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="card card-info-event px-3">
              <div class="card-body d-flex">
                <i class="bi bi-calendar3"></i>
                <div class="">
                  <?php
                      $time = strtotime($eventdet[0]['date']);
                      $date = date('j F Y', $time);
                      $day  = date('l', $time);
                  ?>
                  <h6 class="mb-0"><?= $date ?></h6>
                  <p><small><?= $day ?></small></p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="card card-info-event px-3">
              <div class="card-body d-flex">
                <i class="bi bi-clock"></i>
                <h6 class="mb-0"><?= $merge_se?></h6>
                <p><small><?= $eventdet[0]['food']?></small></p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <form class="" action="save_editpax.php?id=<?= $id ?>" method="post">
              <input type="hidden" name="id" value="<?= $id ?>">
              <input type="hidden" name="participant_id" value="<?= $participant_id ?>">
              <div class="card card-table">

                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                  <h6>List Pax</h6>
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                    Save
                  </button>
                </div>

                <div class="card-body p-0">
                  <div class="table-response">
                    <table id="paxedit" class="table w-100 nowrap valign-middle">
                      <thead>
                        <tr class="att">
                          <th class="text-center">No</th>
                          <th>Name</th>
                          <th>Phone No.</th>
                          <th>Building Name</th>
                          <th>COB</th>
                          <th>Registration</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody id="allpaxlist">

                        <!-- fetch participant name from table pax -->
                        <?php
                        $count = 1;
                        foreach ($allpax as $pax) {

                          $name         = $pax['name'];
                          $phone_no     = $pax['tel'];
                          $building     = $pax['building_name'];
                          $city_council = $pax['city_council'];
                          $registration = $pax['walkin'];
                          $status       = $pax['status'];

                          if ($city_council != null) {
                            $city_c = selectall($conn, 'city_council', "id = $city_council");
                            $city_c = $city_c[0]['city_council'];
                          }else {
                              $city_c = "";
                          }

                          if ($pax['walkin'] == 1) {
                              $registration = "Walkin";
                          } else {
                              $registration = "Online";
                          }

                          if ($pax['status'] == 1) {
                            $status = "<div class=\"d-flex\"><small><i class=\"bi bi-circle-fill text-primary me-1\"></i> Attend </small></div>";
                          }else {
                            $status = "<div class=\"d-flex\"><small><i class=\"bi bi-circle-fill text-danger me-1\"></i> Absent </small></div>";
                          }

                          $city_councils = selectall($conn, 'city_council');
                          ?>

                          <tr>
                            <td class="text-center"><?= $count++; ?></td>
                            <td>
                              <span class="d-none"><?= $name ?></span>
                              <input type="text" class="form-control" name="name[]" value="<?= $name ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $phone_no ?></span>
                              <input type="text" class="form-control" name="phone_no[]" value="<?= $phone_no ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $building ?></span>
                              <input type="text" class="form-control" name="building_name[]" value="<?= $building ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $city_c ?></span>
                              <select class="form-select" name="city_council[]">
                                <?php foreach ($city_councils as $city_council_option) { ?>
                                  <option value="<?= $city_council_option['id'] ?>" <?= ($city_council_option['id'] == $city_council) ? 'selected' : '' ?>><?= $city_council_option['city_council'] ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td>
                              <span class="d-none"><?= $registration ?></span>
                              <select class="form-select" name="walkin[]">
                                <option value="1" <?= ($pax['walkin'] == 1) ? 'selected' : '' ?>>Walkin</option>
                                <option value="0" <?= ($pax['walkin'] == 0) ? 'selected' : '' ?>>Online</option>
                              </select>
                            </td>
                            <td>
                              <span class="d-none"><?= $status ?></span>
                              <select class="form-select" name="status[]">
                                <option value="1" <?= ($pax['status'] == 1) ? 'selected' : '' ?>>Attend</option>
                                <option value="0" <?= ($pax['status'] == 0) ? 'selected' : '' ?>>Absent</option>
                              </select>
                            </td>
                          </tr>
                        <?php } ?>

                        <!-- fetch participant name from table participant -->
                        <?php
                        $count = 1;
                        foreach ($allparticipant as $participant) {

                          $participant_name         = $participant['name'];
                          $participant_phone_no     = $participant['phone'];
                          $participant_building     = $participant['building_name'];
                          $participant_city_council = $participant['city_council'];
                          $participant_registration = $participant['walkin'];
                          $participant_status       = $participant['status'];
                          ?>

                          <tr>
                            <td class="text-center"><?= $count++; ?></td>
                            <td>
                              <span class="d-none"><?= $participant_name ?></span>
                              <input type="text" class="form-control" name="participant_name[]" value="<?= $participant_name ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $participant_phone_no ?></span>
                              <input type="text" class="form-control" name="participant_phone_no[]" value="<?= $participant_phone_no ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $participant_building ?></span>
                              <input type="text" class="form-control" name="participant_building_name[]" value="<?= $participant_building ?>">
                            </td>
                            <td>
                              <span class="d-none"><?= $participant_city_council ?></span>
                              <select class="form-select" name="participant_city_council[]">
                                <?php foreach ($city_councils as $city_council_option) { ?>
                                  <option value="<?= $city_council_option['id'] ?>" <?= ($city_council_option['id'] == $participant_city_council) ? 'selected' : '' ?>><?= $city_council_option['city_council'] ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td>
                              <span class="d-none"><?= $participant_registration ?></span>
                              <select class="form-select" name="participant_walkin[]">
                                <option value="1" <?= ($participant['walkin'] == 1) ? 'selected' : '' ?>>Walkin</option>
                                <option value="0" <?= ($participant['walkin'] == 0) ? 'selected' : '' ?>>Online</option>
                              </select>
                            </td>
                            <td>
                              <span class="d-none"><?= $participant_status ?></span>
                              <select class="form-select" name="participant_status[]">
                                <option value="1" <?= ($participant['status'] == 1) ? 'selected' : '' ?>>Attend</option>
                                <option value="0" <?= ($participant['status'] == 0) ? 'selected' : '' ?>>Absent</option>
                              </select>
                            </td>
                          </tr>
                        <?php }?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <footer class="stick-bot">
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


    <script src="../js/bootstrap/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
      $(document).ready(function(){

        new DataTable('#paxedit', {
          paging      : false,
          searching   : false,
          lengthChange: false,
          info        : true
        });

        $("form").on('submit', function(e) {
          e.preventDefault();

          var formData = $(this).serialize();

          $.ajax({
            type: "POST",
            url : $(this).attr('action'),
            data: formData,
            success: function(response) {
              alert("Data saved successfully!");
            },
            error: function() {
              alert("Error saving data.");
            }
          });
        });
      });
    </script>
  </body>
</html>
