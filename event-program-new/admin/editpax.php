<?php
require('../db_conn.php');
$redirect = "attend";
require('../admin/protect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $selid = selectall($conn, 'event', 'date = CURDATE()');
    if (count($selid) < 1) {
        $selid = selectall($conn, 'event', "date < CURRENT_DATE() ORDER BY date DESC");
        $selid = $selid[0]['id'];
    } else {
        $selid = $selid[0]['id'];
    }

    $id = $selid;
}

$eventname = selectall($conn, 'event', "id = $id");
$eventname = $eventname[0]['name'];

if (isset($_GET["cob"])) {

  $cob     = $_GET["cob"];
  $listcon = selectall($conn, 'city_council');
  $distcob = distict($conn, 'pax', "city_council.city_council", 0, "event_id = $id", "city_council ON pax.city_council = city_council.id");

  if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    switch ($_GET['filter']) {
      case '1':
          $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob");
          break;
      case '2':
          $paxattend = selectall($conn, 'pax', "status = 1 AND event_id = $id AND city_council = $cob");
          break;
      case '3':
          $paxattend = selectall($conn, 'pax', "status = 0 AND event_id = $id AND city_council = $cob");
          break;

      default:
          $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob");
          break;
    }
  } else {
    $filter = "1";
    $paxattend = selectall($conn, 'pax', "event_id = $id AND city_council = $cob");
  }
} else {
  $listcon   = selectall($conn, 'city_council');
  $distcob   = distict($conn, 'pax', "city_council.city_council", 0, "event_id = $id", "city_council ON pax.city_council = city_council.id");
  $paxattend = selectall($conn, 'pax', "event_id = $id");

  if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    switch ($_GET['filter']) {
      case '1':
              $paxattend = selectall($conn, 'pax', "event_id = $id ");
          break;
      case '2':
          $paxattend = selectall($conn, 'pax', "status = 1 AND event_id = $id ");
          break;
      case '3':
          $paxattend = selectall($conn, 'pax', "status = 0 AND event_id = $id ");
          break;

      default:
          $paxattend = selectall($conn, 'pax', "event_id = $id ");
          break;
    }
  } else {
    $filter = "1";
    $paxattend = selectall($conn, 'pax', "event_id = $id ");
  }
}

$allevent    = selectall($conn, 'event');
$countevent  = countall($conn, 'event');
$eventdet    = selectall($conn, 'event', "id = $id");
$role        = $_SESSION['role'];
$publicevent = $eventdet[0]['spec_event'];

$start_t  = strtotime($eventdet[0]['start_time']);
$start_t  = date('g:i a', $start_t);
$end_t    = strtotime($eventdet[0]['end_time']);
$end_t    = date('g:i a', $end_t);
$merge_se = "$start_t - $end_t";

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

    <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        .gap-x-3{
            column-gap: 1rem;
        }
        .gap-y-3{
            row-gap: 1rem;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#pax').DataTable({
                    pageLength: 10,
                    "columnDefs": [{
                        "width": "8%",
                        "targets": 5
                    }],
                    "oLanguage": {
                        "sSearch": "",
                        "sInfoFiltered": ""
                    },
                    "fnDrawCallback": function(oSettings) {
                        $('.dataTables_filter input').attr("id", "sSearch");
                    },
                    'serverMethod': 'post',
                    'ajax': {
                        'url': 'ajaxcalledit.php?id=<?= $id ?><?php if (isset($filter)) {
                                                                echo "&filter=$filter";
                                                            }
                                                            if (isset($cob)) {
                                                                echo "&cob=$cob";
                                                            }
                                                            if(isset($from)){
                                                                echo "&from=$from";
                                                            }
                                                            if(isset($to)){
                                                                echo "&to=$to";
                                                            }
                                                            if ($role == 1) {
                                                                echo "&role=$role   ";
                                                            }
                                                            ?>'
                    },
                    'columns': [{
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'building_name'
                        },
                        {
                            data: 'city_council'
                        },
                        {
                            data: 'walkin'
                        },
                        {
                            data: 'status'
                        }
                    ]

                },

            );

            function fetch() {
                <?php
                if (isset($_GET['filter'])) {
                    $filter = $_GET['filter'];
                } else {
                    $filter = "1";
                }

                foreach ($distcob as $key => $value) {
                    $cobnm = $value['city_council'];

                    $ciid = selectall($conn, 'city_council', "city_council = '$cobnm'");
                    $ciid = $ciid[0]['id'];
                ?>
                    $.get("fetch.php?countatt=true&id=<?= $id ?>&cob=<?= $ciid ?>", function(data, status) {
                        var str = data;

                        $("#att<?= $ciid ?>").html(str);
                        $("#att2<?= $ciid ?>").html(str);
                    });
                <?php
                }
                ?>
                $.get("ajaxmodal.php?id=<?= $id ?><?php if (isset($cob)) {
                                                        echo "&cob=$ciid";
                                                    } ?>", function(data, status) {
                    var str = data;
                    // console.log(str);
                    $("#attmodal").html(str);
                });
            }
            fetch();
            setInterval(function() {
                table.ajax.reload(null, false);
                //console.log("RELOD");
                var smodal = 0;
                <?php
                foreach($paxattend as $value){
                    ?>
                    if ($('#modalinfouser<?= $value['id']?>').css('display') == 'block') {
                    console.log("STILL OPEN");
                        if(smodal == 0 ){
                            smodal = 1;
                        }else{
                            smodal = smodal;
                        }
                }
                    <?php
                }
                ?>
                //console.log(smodal);
                if(smodal == 0){
                    fetch();
                }
            }, 5000);
            var filter = "hide";
            $("#filter-table").click(function() {
                $("#filter").slideToggle("slow");
            })
            var myLength = $("#sSearch").val()
        });
    </script>
</head>

<style>
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
                <a href="/" class="navbar-brand">
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
                        <li class="nav-item">
                          <a class="nav-link" href="/event-program/form-registration.php?id=<?= $id ?>">Event formv1</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/event-program/formevent.php?id=<?= $id ?>">Event formv2</a>
                        </li>
                      <?php endif; ?>

                      <?php if ($role == "1" && $publicevent != "0"): ?>
                        <li class="nav-item">
                          <a class="nav-link" href="/event-program/formevent.php?id=<?= $id ?>">Generic Form</a>
                        </li>
                      <?php endif; ?>

                      <?php if ($role == "1"): ?>
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
                <div>
                    <h1 class="text-gip">Green Initiative Program</h1>
                    <h6 class="fw-800 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</h6>
                </div>
            </div>
            <div class="row mt-3 mb-5">
                <div class="col-lg-6">
                    <div class="card card-info-event px-3">
                        <div class="card-body d-flex">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <h6 class="mb-0"><?= $eventdet[0]['name'] ?></h6>
								                <h6 class="mb-0"><?= $eventdet[0]['location_name'] ?></h6>
                                <p>
                                    <small><?= $eventdet[0]['address'] ?></small>
                                </p>
                            </div>
                            <!-- <div>
                                <h6 class="mb-0 text-primary">Dorsett Grand Subang Hotel</h6>
                                <p>
                                    <small>Dorsett Grand Subang, Jalan SS 12/1, Ss 12, 47500 Subang Jaya, Selangor</small>
                                </p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-info-event px-3">
                        <div class="card-body d-flex">
                            <i class="bi bi-calendar3"></i>
                            <div>
                                <?php
                                    $time = strtotime($eventdet[0]['date']);
                                    $date = date('j F Y', $time);
                                    $day  = date('l', $time);
                                ?>
                                <h6 class="mb-0"><?= $date ?></h6>
                                <p><small><?= $day ?></small></p>
                            </div>
                            <!-- <div>
                                <h6 class="mb-0">4 November 2023</h6>
                                <p><small>Saturday</small></p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-info-event px-3">
                        <div class="card-body d-flex">
                            <i class="bi bi-clock"></i>
                            <div>
                               <h6 class="mb-0"><?= $merge_se?></h6>

                                <p><small><?= $eventdet[0]['food']?></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="cob" class="col-lg-3 collapse show">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <!-- <a href="#"> -->
							                  <a href="index.php?id=<?= $id ?>&filter=<?= $filter ?>">
                                <p class="mb-0">All COB</p>
                            </a>
                        </li>
                        <div id="attendC"></div>
                        <?php
                        foreach ($distcob as $key => $value) {
                            $cobnm = $value['city_council'];

                            $ciid = selectall($conn, 'city_council', "city_council = '$cobnm'");
                            $ciid = $ciid[0]['id'];

                            $countcob = countall($conn, 'pax', "event_id =$id AND city_council = $ciid");
                            $attcob   = countall($conn, 'pax', "event_id =$id AND city_council = $ciid AND status = 1");
                        ?>
                            <?php
                            if (!isset($cob)) {
                            ?>
                                <li class="list-group-item">
                                    <?php
                                } else {
                                    if ($cob == $ciid) {
                                    ?>
                                <li class="list-group-item active">
                                <?php
                                    } else {
                                ?>
                                <li class="list-group-item">
                                    <?php
                                    }
                                }

                                    ?><?php

                                        if (isset($cob)) {
                                            if ($cob == $ciid) {
                                                if (isset($filter)) {
                                        ?><a href="index.php?id=<?= $id ?>&filter=<?= $filter ?>"> <?php
                                                } else {
                                                    ?><a href="index.php?id=<?= $id ?>"> <?php
                                                }
                                            } else {
                                            if (isset($filter)) {
                                                ?><a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>&filter=<?= $filter ?>"> <?php
                                            } else {
                                                ?><a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>"> <?php
                                              }
                                            }
                                            } else {
                                                        ?><a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>"> <?php
                                            }
                                                ?>
                                <p class="mb-0 text-truncate"><?= $value['city_council'] ?></p>
                                <div class="custom-badge">
                                    <p id="att2<?= $ciid ?>" class="mb-0"></p>
                                </div>
                                <!-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="popover" data-bs-title="Popover title" data-bs-content="And here's some amazing content. It's very engaging. Right?"><i class="bi bi-info-circle"></i></button> -->
                                </a>
                            <?php
                          }
                          ?>
                      </li>
                    </ul>
                </div>
                <div class="col-lg-9">
                    <form action="save_editpax.php">
                        <div class="card card-table">
                            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn me-3" data-bs-toggle="collapse" data-bs-target="#cob"><i class="bi bi-fullscreen"></i><i class="bi bi-fullscreen-exit"></i></button>
                                    <h6>List Pax</h6>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="paxedit" class="table w-100 nowrap valign-middle">
                                        <thead>
                                            <tr class="att">
                                                <th class="text-center">No</th>
                                                <th>Name</th>
                                                <th>Building Name</th>
                                                <th>COB</th>
                                                <th>Registration</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <!-- For datatable global search -->
                                                    <span class="d-none">Muhd Aaaa</span>
                                                    <input type="text" class="form-control" name="name[]" value="Muhd Aaaa">
                                                </td>
                                                <td>
                                                <!-- For datatable global search -->
                                                    <span class="d-none">Pangsapuri Seroja</span>
                                                    <input type="text" class="form-control" name="building_name[]" value="Pangsapuri Seroja">
                                                </td>
                                                <td>
                                                <!-- For datatable global search -->
                                                    <span class="d-none">COB1</span>
                                                    <select class="form-select" name="city_council[]" id="">
                                                        <option value="" selected>COB1</option>
                                                        <option value="">COB2</option>
                                                        <option value="">COB3</option>
                                                        <option value="">COB4</option>
                                                        <option value="">COB5</option>
                                                    </select>
                                                </td>
                                                <td>
                                                <!-- For datatable global search -->
                                                    <span class="d-none">Walkin</span>
                                                    <select class="form-select" name="walkin[]" id="">
                                                      <option value="" selected>Online</option>
                                                        <option value="">Walkin</option>
                                                    </select>
                                                </td>
                                                <td>
                                                <!-- For datatable global search -->
                                                    <span class="d-none">Attend</span>
                                                    <select class="form-select" name="status[]" id="">
                                                        <option value="1"selected>Attend</option>
                                                        <option value="2">Absent</option>
                                                        <option value="3">Not started</option>
                                                    </select>
                                                </td>
                                            </tr>
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
    </div>


    <!-- <script src="../js/bootstrap/bootstrap.min.js"></script> -->
    <script src="../js/bootstrap/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap.min.js"></script> -->

    <script>
        $(document).ready(function() {
            new DataTable('#paxedit').DataTable({

            });

            $('#modalinfouser825').click(function(){
                alert("CLIK")
            })
        });
    </script>

    <script>
        $("#toggleFilter").change(function() {
            $("#pax_length").toggle('slow');
            $("#pax_filter").toggle('slow');
        });
    </script>

</body>

</html>
