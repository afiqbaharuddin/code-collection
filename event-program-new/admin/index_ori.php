<?php
require('../db_conn.php');
require('protect.php');
//get all event
$allevent = selectall($conn, 'event');
if ($_SESSION['role'] == 2) {
    header("location: ../attend/index.php");
}else{
    header("location: ../admin/listevent.php");
}

// var_dump($_SERVER['REQUEST_URI']);


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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>


    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .w-50 {
            width: 50%;
        }

        .w-75 {
            width: 80% !important;
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

        #eventlist {
            width: 100%;
        }

        thead {
            background-color: #ededed;
        }

        #eventlist tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table {
            text-align: center;
        }
    </style>
    <script>
        //

        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                aoColumns: [{
                        "sWidth": "15%"
                    },
                    {
                        "sWidth": "15%"
                    },
                    {
                        "sWidth": "15%"
                    },
                    {
                        "sWidth": "15%"
                    },


                ],
                columnDefs: [
                    // Center align the header content of column 1
                    {
                        className: "dt-head-center",
                        targets: [0, 1, 2, 3]
                    }
                ]
            });
        });
    </script>
</head>

<body class="h-100">
    <header>
        <nav class="navbar navbar-expand-lg border-bottom">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <!-- <img src="/images/logo/synery group logo -.png" alt="" class="img-fluid logo"> -->
                    <img src="../images/logo/new-logo.png" alt="dlogo" class="img-fluid logo">

                </a>
                <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <div id="line1"></div>
                    <div id="line2"></div>
                </button>
                <?php
                require("adminnav.php");
                ?>
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
                <div></div>
            </div>

            <div class="card card-table">
                <div class="card-header">
                    <h6>List New Event</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="example" class="table display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <!-- <th>Maps</th>
                                    <th>feedback</th> -->
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($allevent as $key => $value) {
                                # code...
                                $key++;
                                $id = $value['id'];
                                $name = $value['name'];
                                $address = $value['address'];
                                $survey_form = $value['survey_form'];
                                $date = $value['date'];
                                $maps = $value['maps'];
                            ?>
                                <tr>

                                    <td><?= $name ?></td>
                                    <td><?= $address ?></td>

                                    <td><?= $date ?></td>
                                    <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                        <a href="../attend/index.php?id=<?= $id ?>" class="btn btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $id ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $id ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                        
                                    
                                    <!-- <button id=<?= $id ?> type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $id ?>">
                                            Edit
                                        </button>
                                        <a class="btn btn-primary btn-sm" href="../attend/index.php?id=<?= $id ?>">Show</a>
                                        <button id=<?= $id ?> type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $id ?>">
                                            Delete
                                        </button> -->

                                    </td>
                                </tr>


                            <?php

                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <?php
        foreach ($allevent as $key => $value) {
            # code...
            $id = $value['id'];
            $name = $value['name'];
            $address = $value['address'];
            $survey_form = $value['survey_form'];
            $date = $value['date'];
            $maps = $value['maps'];
            $bannerpath = $value['banner_img'];
            $close = $value['close_date'];
            // $thumbmail = $value['thumbnail_img'];


        ?>
            <div class="modal fade" id="exampleModal<?= $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit New Event</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="update.php" method="post" enctype="multipart/form-data">
                            <div class="modal-body">


                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1">Event name:</label>
                                    <input name="name" value="<?= $name ?>" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Event name">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Address:</label>
                                    <input name="address" value="<?= $address ?>" type="text" class="form-control" id="exampleInputPassword1" placeholder="Address">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Maps:</label>
                                    <input name="maps" value="<?= $maps ?>" type="text" class="form-control" id="exampleInputPassword1" placeholder="Maps link">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Banner Image:</label>
                                    <input name="banner" id="banner" value="<?= $bannerpath?>" type="file" class="form-control" id="exampleInputPassword1" placeholder="Maps link">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Thumbnail Image:</label>
                                    <input name="thumbnail" id="thumbnail" value="<?= $thumbmail?>" type="file" class="form-control" id="exampleInputPassword1" placeholder="Maps link">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Survey form:</label>
                                    <input name="survey_form" value="<?= $survey_form ?>" class="form-control" id="exampleInputPassword1" placeholder="Survey Form">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Date form:</label>
                                    <input name="date" value="<?= $date ?>" type="date" class="form-control" id="exampleInputPassword1">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Close form:</label>
                                    <input name="close" value="<?= $close ?>" type="datetime-local" class="form-control" id="exampleInputPassword1">
                                </div>
                                <input name=id type="hidden" value="<?= $id ?>">



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal<?= $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Event</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="deleteevent.php" method="post">
                            <div class="modal-body">



                                <span>Do you confirm to delete this?</span>
                                <input name="id" type="hidden" value="<?= $id?>">


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php
            //     echo <<< EOT
            //         <div class="modal fade" id="exampleModal$id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            //         <div class="modal-dialog">
            //             <div class="modal-content">
            //                 <div class="modal-header">
            //                     <h1 class="modal-title fs-5" id="exampleModalLabel">Edit New Event</h1>
            //                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            //                 </div>
            //                 <form action="update.php" method="post">
            //                 <div class="modal-body">


            //                         <div class="form-group mb-3">
            //                             <label for="exampleInputEmail1">Event name:</label>
            //                             <input name="name" value="$name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Event name">
            //                         </div>
            //                         <div class="form-group mb-3">
            //                             <label for="exampleInputPassword1">Address:</label>
            //                             <input name="address" value="$address" type="text" class="form-control" id="exampleInputPassword1" placeholder="Address">
            //                         </div>
            //                         <div class="form-group mb-3">
            //                             <label for="exampleInputPassword1">Survey form:</label>
            //                             <input name="survey_form" value="$survey_form"   class="form-control" id="exampleInputPassword1" placeholder="Survey Form">
            //                         </div>
            //                         <div class="form-group mb-3">
            //                             <label for="exampleInputPassword1">Date form:</label>
            //                             <input name="date" value="$date" type="date" class="form-control" id="exampleInputPassword1" >
            //                         </div>
            //                         <input name=id type="hidden" value="$id">



            //                 </div>
            //                 <div class="modal-footer">
            //                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            //                     <button type="submit" class="btn btn-primary">Save changes</button>
            //                 </div>
            //                 </form>
            //             </div>
            //         </div>
            //     </div>
            // EOT;
        }

        ?>




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

    <script src="../js/bootstrap/bootstrap.min.js"></script>

</body>

</html>