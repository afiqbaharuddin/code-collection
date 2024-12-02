<?php
require('../db_conn.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $selid = selectall($conn, 'event', "date >= NOW()");
    $selid = $selid[0]['id'];
    $id = $selid;
    
}
$sql = "SELECT * FROM pax Where event_id = $id";
$result = $conn->query($sql);
$arr = [];
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        array_push($arr, $row);
        // die($temp);
    }
} else {
    
}
// echo "<h1>List Participants:</h1>";




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
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            padding: 1.5rem;
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
                            <a class="nav-link" href="index.php?id=<?= $id ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/event-program/form-registration.php">Registration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="green-initiative.html">Back To Green Initiative</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="py-5">
        <div class="container">
            <div class='d-flex justify-content-between mb-3'>
                <div>
                    <h3 class="text-primary">List of Pax</h3>
                    <p class="mb-4 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</p>
                </div>
                <div class="btn-group align-items-center" role="group" aria-label="Basic example">
                    <!-- <button type="button" class="btn btn-primary">Invite QR <a href="#">ss</a></button> -->
                    <a class="btn btn-primary" href="import.php">Import</a>
                    <a class="btn btn-primary" href="controller/emailController.php?action=bulkmail&view=invite&id=<?= $id ?>">Invite</a>
                    <a class="btn btn-primary" href="controller/emailController.php?action=bulkmail&view=invite&id=<?= $id ?>">Reminder</a>
                    <!-- <a class="btn btn-primary" href="bulkemail.php?action=invite&id=<?= $id ?>">InviteQR</a>
            <a class="btn btn-primary" href="bulkemail.php?action=reminder&id=<?= $id ?>">Reminder</a> -->


                </div>

            </div>


            <div class="card card-table">
                <table id="pax" class="table">
                    <thead>
                        <tr class="">
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>COB</th>
                            <th>QR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($arr as $key => $value) {
                            $key++;
                            $ciid = $value['city_council'];

                            $cityc = selectall($conn, "city_council", "id = $ciid");

                        ?>
                            <tr class="text-center">
                                <td><?= $key ?></td>
                                <td><?= $value['name'] ?></td>
                                <td><?= $value['email'] ?></td>
                                <td><?= $cityc[0]['city_council'] ?></td>
                                <td>
                                    <?php
                                    if ($value['status'] == 1) {


                                        echo "<button disabled class=\" btn btn-primary me-3\"><a class=\"text-white\" href=\"#\">Check-in</a></button>";
                                    } else {
                                        $paxid = $value['id'];
                                        // echo "<button  class=\" btn btn-primary me-3\"><a class=\"text-white\" href=\"checkin.php?id=$paxid&event=$id\">Check-in</a></button>";
                                        echo "<button  class=\" btn btn-primary me-3\"><a class=\"text-white\" href=\"../register.php?id=$paxid\">Check-in</a></button>";
                                    }

                                    ?>
                                    <!-- <button class="btn btn-primary me-3"><a href="checkin.php?id=<?= $value['id'] ?>&event=<?= $id ?>">Check-in</a></button>     -->
                                    <a href="qrCode.php?id=<?= $value['id'] ?>">show QR</a>
                                    <a class="btn btn-danger" href="deletepax.php?id=<?=  $value['id']  ?>&eventid=<?= $id?>">Delete</a>
                                </td>
                            </tr>


                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script>
        new DataTable('#pax');
    </script>
</body>

</html>