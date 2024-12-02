<?php
require('../db_conn.php');
require('../admin/protect.php');
$redirect = "attend";

$role1  = $_SESSION['user'];
$role   = $_SESSION['role'];

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

$eventdet    = selectall($conn, 'event', "id = $id");
$publicevent = $eventdet[0]['spec_event'];
$distcob     = distict($conn, 'pax', "city_council.city_council", 0, "event_id = $id", "city_council ON pax.city_council = city_council.id");
$allevent    = selectall($conn, 'event');
$countevent  = countall($conn, 'event');
$paxattend   = selectall($conn, 'pax', "event_id = $id");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Initiative Program | Synergy Esco (M) sdn Bhd</title>

    <link rel="icon" type="image/x-icon" href="/assets/images/logo/favicon.png">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  </head>

  <style>
    .gap-x-3{
        column-gap: 1rem;}
    .gap-y-3{
        row-gap: 1rem;}
    .pagination a {
        color: #444;}
    .active>.page-link,
    .page-link.active {
        background-color: #07a479 !important;
        color: white !important;
        border-color: #07a479 !important;}
    .text-primary {
        color: #07a479 !important;}
    .list-group li {
        padding: .75rem 1.25rem;
        border-bottom: none;
        border-radius: 8px;
        border: none;}
    .list-group li:hover {
        background: #f9f9f9;
        border-top: 2px solid #fff;}
    a {
        text-decoration: none;
        color: #404040;}
    a:hover {
        color: #333;}
    .bg-primary {
        background: #07a479 !important;}
    .dropdown-menu {
     max-height:600px;
     overflow:scroll;}
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;}
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;}
    .dropdown-content a:hover {background-color: #ddd;}
    .dropdown:hover .dropdown-content {display: block;}
    .dropdown:hover .dropbtn {background-color: #3e8e41;}
    .card-table {
        border-radius: 10px;}
    .card-header .dropdown .dropdown-toggle::after {
        display: none;}

    #pax_length,
    #pax_filter,
    #pax_info {
        display: none;}

    .card-register {
        border: 2px solid #07a479;}
  </style>

  <body>
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

              <?php if (isset($_SESSION['user'])) { ?>
                <li class="nav-item">
                  <a class="nav-link" href="../admin/logout.php">Logout</a>
                </li>
              <?php } ?>
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

          <div class="dropdown-center">
            <?php
              $time     = strtotime($eventdet[0]['date']);
              $date     = date('j F Y', $time);
              $day      = date('l', $time);
              $start_t  = strtotime($eventdet[0]['start_time']);
              $start_t  = date('g:i a', $start_t);
              $end_t    = strtotime($eventdet[0]['end_time']);
              $end_t    = date('g:i a', $end_t);

              $merge_se = "$start_t - $end_t";
              $role     = $_SESSION['role'];
            ?>

              <a class="nav-link btn btn-outline-primary rounded shadow-sm" data-bs-toggle="dropdown">Event : <?= $date ?> | <?= $eventdet[0]['name'] ?>
                <i class="bi bi-caret-down-fill"></i>
              </a>

              <ul class="dropdown-menu">
                <?php foreach ($allevent as $key => $value) {
                  $time      = strtotime($value['date']);
                  $newformat = date('jS M',$time); ?>

                  <li>
                    <a href="index2.php?id=<?= $value['id'] ?>" class="dropdown-item">
                      <div class="">
                        <h6><?= $value['name'] ?></h6>
                        <p class="text-muted"><small><?= $newformat ?></small></p>
                      </div>
                    </a>
                  </li>
                  <?php $countkey = $key + 1;
                    if ($countevent != $countkey) { ?>
                    <?php } ?>
                <?php } ?>
              </ul>
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
              <div class="card card-body d-flex">
                <i class="bi bi-calendar3"></i>
                <div class="">
                  <?php
                    $time = strtotime($eventdet[0]['date']);
                    $date = date('j F Y',$time);
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
                <div class="">
                  <h6><?= $merge_se?></h6>
                  <p><small><?= $eventdet[0]['food']?></small></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3">
            <ul class="list-group">
              <li class="list-group-item active">
                <a href="index.php?id=<?= $id ?>&filter=<?= $filter ?>">
                  <p>All COB</p>
                </a>
              </li>
              <div class="" id="attendC"></div>

              <?php foreach ($distcob as $key => $value) {
                $cobnm    = $value['city_council'];
                $ciid     = selectall($conn, 'city_council', "city_council = '$cobnm'");
                $ciid     = $ciid[0]['id'];

                $countcob = countall($conn, 'pax', "event_id =$id AND city_council = $ciid");
                $attcob   = countall($conn, 'pax', "event_id =$id AND city_council = $ciid AND status = 1");
              ?>

              <?php if (!isset($cob)) { ?>
                  <li class="list-group-item">
              <?php } else {
                  if ($cob == $ciid) { ?>
                  <li class="list-group-item active">
                  <?php } else { ?>
                  <li class="list-group-item">
                      <?php }
                  }?>

                  <?php
                  if (isset($cob)) {
                      if ($cob == $ciid) {
                          if (isset($filter)) { ?>
                            <a href="index.php?id=<?= $id ?>&filter=<?= $filter ?>"> <?php
                          } else { ?>
                            <a href="index.php?id=<?= $id ?>"> <?php
                          }
                      } else {
                      if (isset($filter)) { ?>
                        <a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>&filter=<?= $filter ?>"> <?php
                      } else { ?>
                        <a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>"> <?php
                      }
                      }
                      } else { ?>
                        <a href="index.php?id=<?= $id ?>&cob=<?= $ciid ?>"> <?php
                      } ?>

                      <p class="mb-0 text-truncate"><?= $value['city_council'] ?></p>
                      <div class="custom-badge">
                        <p id="att2<?= $ciid ?>"></p>
                      </div>
                    </a>
              <?php } ?>
              </li>
            </ul>
          </div>

          <div class="col-lg-9">
            <div class="card card-table">
              <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <div class="">
                  <h6>List Pax</h6>
                </div>
                <div class="d-flex align-items-center">
                  <div class="form-check form-switch me-3" style="min-height: auto;">
                    <input class="form-check-input" type="checkbox" role="switch" id="toggleFilter">
                  </div>

                  <div class="dropdown col-sm-3 me-3">
                    <a class="dropdown-toggle btn btn-sm btn-primary" href="#" data-bs-toggle="dropdown" aria-expanded="false">Actions</a>
                    <ul class="dropdown-menu">
                      <li>
                        <a id="filter-table" class="dropdown-item" href="#">Filter Table</a>
                      </li>
                      <li>
                        <a id="export" class="dropdown-item" href="../export4.php?id=<?= $id ?>&where=<?= $filter ?>">Export table</a>
                      </li>
                      <?php if($role == "0"){?>
                        <li>
                          <a class="dropdown-item" href="../emailRegistration2.php?eventid=<?= $eventdet[0]['id'] ?>">Reminder</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="javascript: void(0)">Reminder 1 day before</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="javascript: void(0)">Reminder On day</a>
                        </li>
                      <?php } ?>
                    </ul>
                  </div>

                  <?php if (($role == "1" || $role == "3") && $publicevent == "0"):  ?>
                    <div class="col-sm-6">
                      <a href="../admin/editpax_new.php?id=<?= $id ?>">
                        <button type="button" class="btn btn-sm btn-primary" name="button">Edit Event Pax</button>
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="card-body p-0">
                  <div id="filter" style="display: none;" class="col-6 mb-3 p-3">

                    <form id="filterForm" method="GET">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <select name="filter" id="filterSelect" class="form-select">
                            <option value="1">Show All</option>
                            <option value="2">Attend</option>
                            <option value="3">Absent</option>
                        </select>
                        <input type="submit" class="btn btn-primary mt-3 mb-3 me-2" value="Apply Filter">
                        <input type="reset" class="btn btn-secondary mt-3 mb-3 resetBtn" value="Reset Filter">
                    </form>
                  </div>
                  <div class="table-responsive">
                    <table id="pax" class="table w-100 nowrap">
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
                      <tbody id="att">
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
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

    <div id="attmodal"></div>

    <script src="../js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#modalinfouser825').click(function(){
            alert("CLICK")
        });

        $("#toggleFilter").change(function() {
            $("#pax_length").toggle('slow');
            $("#pax_filter").toggle('slow');
        });

        //jquery to hide show filter section
        var filter = "hide";
        $("#filter-table").click(function() {
            $("#filter").slideToggle("slow");
        })
        var myLength = $("#sSearch").val();

        //ajax for datatable
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
            'url': 'ajaxcall.php',
            'data': function(d) {
              d.id = "<?= $id ?>";  // Pass the event ID correctly
              d.filter = $('#filterSelect').val();  // Pass filter value
            }
          },
          'columns': [
            {data: 'id'},
            {data: 'name'},
            {data: 'building_name'},
            {data: 'city_council'},
            {data: 'walkin'},
            {data: 'status'}
          ]
      });

      $('#filterSelect').on('change', function(){
        table.ajax.reload(null,false);
      });

      function fetch() {
        <?php
        $filter = isset($_GET['filter']) ? $_GET['filter'] : "1";

        foreach ($distcob as $key => $value) {
          $cobnm = $value['city_council'];
          $ciid  = selectall($conn, 'city_council', "city_council = '$cobnm'");
          $ciid  = $ciid[0]['id'];
        ?>
          $.get("fetch.php?countatt=true&id=<?= $id ?>&cob=<?= $ciid ?>",
          function(data, status) {
            var str = data;
            $("#att<?= $ciid ?>").html(str);
            $("#att2<?= $ciid ?>").html(str);
          });
        <?php
        }
        ?>
        $.get("ajaxmodal.php?id=<?= $id ?><?php if (isset($cob)) { echo "&cob=$ciid"; } ?>", function(data, status) {
          var str = data;
          $("#attmodal").html(str);
        });
      }

      fetch();

      setInterval(function() {
        table.ajax.reload(null, false);
        var smodal = 0;
        <?php foreach ($paxattend as $value) { ?>
          if ($('#modalinfouser<?= $value['id'] ?>').css('display') == 'block') {
            console.log("STILL OPEN");
            smodal = (smodal == 0) ? 1 : smodal;
          }
        <?php } ?>

        if (smodal == 0) {
          fetch();
        }
      }, 5000);

      // $('#filterForm').on('submit', function(event){
      //   event.preventDefault();
      //   var formData = $(this).serialize();
      //
      //   $.ajax({
      //     pageLength: 10,
      //     url:'fetch.php',
      //     type: 'GET',
      //     data: formData,
      //     success: function(data){
      //       $('#pax').html(data);
      //     },
      //     error: function(xhr,status,error){
      //       console.log('Error: '+ error);
      //     }
      //   });
      // });

      $('.resetBtn').on('click', function() {
        location.reload();
      });
      });
    </script>
  </body>
</html>
