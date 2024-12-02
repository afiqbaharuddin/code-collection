<?php
require('db_conn.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    //find event with current date
    $selid = selectall($conn, 'event', "date = CURRENT_DATE()");
    if(count($selid) < 1){
        //find nearest event/pass event
        $selid = selectall($conn, 'event', "date < CURRENT_DATE() ORDER BY date DESC");
        $selid = $selid[0]['id'];
    }else{
        //found the event
        $selid = $selid[0]['id'];
    }

    $id = $selid;
}

//Redirect event-program link to login page
$request = $_SERVER['REQUEST_URI'];

// Remove query string from the URI (if any)
$request = strtok($request, '?');

// Check if the user accessed the specific URL
if ($request === '/event-program/') {
    // Redirect to the login page
    header('Location: /event-program/admin/login.php');
    exit();
}

$count_reg = countall($conn, 'pax', "event_id = $id");  //count registered for the event
$count_att = countall($conn, 'pax', "status=1 AND event_id = $id"); //count participants attend
$count_buil = distict($conn, 'pax', 'building_name', 1, "event_id = $id"); //count building name

$count_pbt = countspecial($conn, "event_id = $id"); //count pbt
$sel_pbt = selectall($conn, 'city_council');
$attend = selectall($conn, 'pax', "status=1 AND event_id = $id"); //count participants that has attend status
$eventdet = selectall($conn, 'event', "id = $id"); //get event details by id
$allevent = selectall($conn, 'event'); //get all event
$countevent = countall($conn, 'event'); //count all event


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
    <script>
        $(document).ready(function() {

            fetch();

            function fetch() {
                //refesh realtime data
                console.log('render again2')

                $.get("fetch.php?countatt=true&id=<?= $id ?>", function(data, status) {
                    var str = data;

                    $("#attendC").html(str);
                });
                $.get("fetch.php?paxlist=true&id=<?= $id ?>", function(data, status) {
                    var str = data;

                    $("#paxlist").html(str);
                });
            }
            var refreshId = setInterval(fetch, 5000);
        });
    </script>
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
                        <!-- <li class="nav-item">
                            <a class="nav-link active" href="/event-program/">Dashboard</a>
                        </li> -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link" data-bs-toggle="dropdown" href="/event-program/form-registration.php?id=2">Event</a>

                            <ul class="dropdown-menu">
                                <?php
                                //foreach ($allevent as $key => $value) {
                                    # code...
                                    $time = strtotime($value['date']);

                                    $newformat = date('jS M.', $time);
                                    // $date = date_format($date);

                                    // die();
                                ?>
                                    <li>
                                        <a href="index.php?id=<?= $value['id']?>" class="dropdown-item">
                                            <div>
                                                <p class="text-muted"><small><?= $newformat ?></small></p>
                                                <h6><?= $value['name'] ?></h6>
                                            </div>
                                        </a>
                                    </li>
                                    <?php
                                    //$countkey = $key + 1;

                                    //if ($countevent != $countkey) {
                                    ?><li>
                                            <hr class="dropdown-divider">
                                        </li> <?php
                                            //}
                                                ?>

                                <?php
                                //}
                                ?>

                            </ul>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="form-registration.php?id=<?= $id ?>">Registration</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/event-program/admin/login.php">Login</a>
                        </li> -->

			<li class="nav-item">
	    		    <a class="nav-link active" href="/event-program/index.php">Dashboard</a>
	                </li>
			<li class="nav-item">
            		    <a class="nav-link" href="/event-program/admin/index.php">List Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/event-program/admin/addevent.php">Add New Event</a>
                        </li>
			<li class="nav-item">
            		    <a class="nav-link" href="logout.php">Logout</a>
        		</li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="text-gip">Green Initiative Program</h1>
                    <h6 class="fw-800 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</h6>
                </div>
                <div class="dropdown-center">
                    <?php
                        $time = strtotime($eventdet[0]['date']);
                        $date = date('j F Y', $time);
                        $day = date('l', $time);
                    ?>
                    <a class="nav-link btn btn-outline-primary rounded shadow-sm" data-bs-toggle="dropdown" href="/event-program/form-registration.php?id=2">Event : <?= $date ?> | <?= $eventdet[0]['name'] ?> <i class="bi bi-caret-down-fill"></i></a>

                    <ul class="dropdown-menu">
                        <?php
                        foreach ($allevent as $key => $value) {
                            $time = strtotime($value['date']);
                            $newformat = date('jS M.', $time);
                        ?>
                            <li>
                                <a href="index.php?id=<?= $value['id'] ?>" class="dropdown-item">
                                    <div>
                                        <h6 mb-0><?= $value['name'] ?></h6>
                                        <p class="text-muted"><small><?= $newformat ?></small></p>
                                    </div>
                                </a>
                            </li>
                            <?php
                            $countkey = $key + 1;

                            if ($countevent != $countkey) {
                            ?> <?php
                                    }
                                        ?>

                        <?php
                        }
                        ?>

                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 pe-md-5">
                    <p class="text-justify">Program for the residential buildings to ease the burden of high electricity bills by achieving 80% of savings on lighting and reduce carbon footprint. REGISTER TO SAVE YOUR SEAT NOW!! Registration ends Thursday, 19 October 2023.</p>
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card card-event border-bottom">
                                <div class="card-body d-flex">
                                    <i class="bi bi-calendar3"></i>
                                    <div><?php


                                            $time = strtotime($eventdet[0]['date']);

                                            $date = date('j F Y', $time);
                                            $day = date('l', $time);

 					                                  $start_t = strtotime($eventdet[0]['start_time']);
                                            $start_t = date('g:i a', $start_t);
                                            $end_t = strtotime($eventdet[0]['end_time']);
                                            $end_t = date('g:i a', $end_t);

                                            $merge_se = "$start_t - $end_t";

                                            ?>

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
                                        <h6 class="mb-0"><?= $merge_se?></h6>
                                        <p><small>Food will be provided</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-event">
                        <div class="card-body d-flex">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <h6 class="mb-0"><?= $eventdet[0]['name'] ?></h6>
                                <p>
                                    <small><?= $eventdet[0]['address'] ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-participant registered">
                                <div class="card-body">
                                    <div>
                                        <h2><?= $count_reg ?></h2>
                                        <p>User Registered</p>
                                    </div>
                                    <i class="bi bi-ui-checks"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-participant">
                                <div class="card-body">
                                    <div>
                                        <h2 id="attendC"><?= $count_att ?></h2>
                                        <p>User Attend</p>
                                    </div>
                                    <i class="bi bi-person-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card card-participant">
                                <div class="card-body">
                                    <div>
                                        <h2><?= $count_buil ?></h2>
                                        <p>Management / Building Involved</p>
                                    </div>
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-participant">
                                <div class="card-body">
                                    <div>
                                        <h2><?= $count_pbt ?></h2>
                                        <p>PBT Involved</p>
                                    </div>
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-5 pe-md-5">
                    <div class="card card-table">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>No. of Participant</h6>
                            <button type="button" class="btn btn-primary btn-sm visibility-hidden">Button</button>
                        </div>
                        <div class="table-resposive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>PBT</th>
                                        <th>JMB/MC</th>
                                        <th>Entites</th>
                                        <th>No. of Participant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($sel_pbt as $key => $value) {
                                        echo "<tr>";
                                        echo "<td>" . $value['city_council'] . "</td>";
                                        $citycon = $value['id'];
                                        $count_pax = countall($conn, 'pax', "city_council=$citycon AND event_id =$id");
                                        if ($citycon != 7) {
                                            $count_jmb = countall($conn, 'pax', "city_council=$citycon AND event_id =$id AND (`starta_status` = 'JMB' OR `starta_status` = 'MC')");
                                            echo "<td> $count_jmb </td>";
                                            echo "<td> 0 </td>";
                                        } else {
                                            $count_jmb = countall($conn, 'pax', "city_council=$citycon AND event_id =$id");
                                            echo "<td> 0 </td>";
                                            echo "<td> $count_jmb </td>";
                                        }

                                        echo "<td> $count_pax </td>";
                                        echo "</tr>";
                                    }

                                    ?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-table">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Attendance List</h6>
                            <button type="button" class="btn btn-sm btn-primary visibility-hidden" data-bs-toggle="modal" data-bs-target="#export-password">Export</button>
                            <button type="button" class="btn btn-sm btn-primary " data-bs-toggle="modal" data-bs-target="#export-password"> <a class="text-white" href="attend/index.php?id=<?= $id ?>">Details view</a></button>
                            <!-- <button type="button" class="btn btn-sm btn-primary " data-bs-toggle="modal" data-bs-target="#export-password"> <a class="text-white" href="Controller/emailController.php?action=feedback&id=<?= $id ?>">Send Feedback form</a></button> -->

                        </div>
                        <div class="table-resposive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Building Name/Facility Management</th>
                                        <th>PBT</th>
                                        <th>Walk-in/online</th>
                                    </tr>
                                </thead>
                                <tbody id="paxlist">
                                    <?php
                                    foreach ($attend as $key => $value) {
                                        $key++;
                                        $name = $value['name'];
                                        $build = $value['building_name'];
                                        echo "<tr>";
                                        echo "<td> $key</td>
                                        <td>$name</td>
                                        <td>$build</td>
                                        <td>MP Kajang</td>";
                                        echo "</tr>";
                                    }
                                    ?>

                                </tbody>
                            </table>
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> -->
    <script src="js/bootstrap/bootstrap.min.js"></script>

</body>

</html>
