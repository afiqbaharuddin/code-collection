<?php
require('../db_conn.php');
require('protect.php');


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
    <style>
        .w-50 {
            width: 50%;
        }

        .h-100 {
            height: 100%;
        }

        body {
            min-height: 100vh !important;
        }

        .stick-bot {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

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
                <?php
                require("adminnav.php");
                ?>
                <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">List Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addevent.php">Add Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="green-initiative.html">Back To Green Initiative</a>
                        </li>
                    </ul>
                </div> -->
            </div>
        </nav>
    </header>

    <section class="py-5">
        <div class="card w-50 mx-auto">
            <div class="card-body">
                <form action="insert.php" method="post" enctype="multipart/form-data">
                    <h1 class="mb-3">Add New Event</h1>
                    <div class="form-group mb-3">
                        <label for="eventname">Event name:</label>
                        <input name="name"  type="text" class="form-control" id="name" placeholder="Enter event name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="locationname">Location Name:</label>
                        <input name="locationname" type="text" class="form-control" id="locationname" placeholder="Enter location name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="eventaddress">Address:</label>
                        <input name="address" type="text" class="form-control" id="eventaddress" placeholder="Enter event address">
                    </div>
                    <div class="form-group mb-3">
                        <label for="maps">Maps:</label>
                        <input name="maps" type="text" class="form-control" id="maps" placeholder="Enter event maps url">
                    </div>
                    <div class="form-group mb-3">
                        <label for="event_date">Date of Event:</label>
                        <input name="date"  type="date" class="form-control" id="event_date">
                    </div>
                    <div class="form-group mb-3">
                        <label for="event_dateform">Event Time Start at:</label>
                        <input name="start_time"  type="time" class="form-control" id="event_startTime">
                    </div>
                    <div class="form-group mb-3">
                        <label for="event_dateform">Event Time Ends at:</label>
                        <input name="end_time"  type="time" class="form-control" id="event_endTime">
                    </div>
                    <div class="form-group mb-3">
                        <label for="event_regclosedate">Event Registration Form Closing Date:</label>
                        <input name="close_date"  type="datetime-local" class="form-control" id="event_regclosedate">
                    </div>
                    <div class="form-group mb-3">
                        <label for="food">Food Status:</label>
                        <input name="food"  type="text" class="form-control" id="food" placeholder="E.g: Food will be provided">
                    </div>
                    <div class="form-group mb-3">
                        <label for="banner">Banner Image:</label>
                        <input name="banner" id="banner" type="file" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="thumbnail">Thumbnail Image:</label>
                        <input name="thumbnail" id="thumbnail" type="file" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="surveyform">Survey form URL:</label>
                        <input name="survey_form"  class="form-control" id="surveyform" placeholder="Enter survey form type">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
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

    <script src="/js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
