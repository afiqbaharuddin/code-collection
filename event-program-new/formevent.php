<?php

//THIS FORM FOR SHORT VERSION FORM REQUESTED BY LOH
//*for original form can use form-registration.php
require('db_conn.php');
session_start();
$cityc = selectall($conn, 'city_council WHERE `is_pmc` = 0 ORDER BY `city_council` DESC');
$fpmc = selectall($conn, 'city_council WHERE `is_pmc` = 1 ORDER BY `city_council` DESC');
$id = $_GET['id'];
$event = selectall($conn, 'event');
$eventdet = selectall($conn, 'event', "id = $id");

$countevent = countall($conn, 'event');

$time = strtotime($eventdet[0]['date']);

// $newformat = date('Y-m-d',$time);
$nowdate = date("Y-m-d");
$closedate = selectall($conn, 'event', "id = $id AND close_date < \"$nowdate\"");
$openedate = selectall($conn, 'event', "id = $id AND close_date >= \"$nowdate\"");
$closedate = count($closedate);
$openedate = count($openedate);
$onevent = selectall($conn, 'event', "id = $id AND date = \"$nowdate\"");
$onevent = count($onevent);

date_default_timezone_set("Asia/Kuala_Lumpur");

$closestrtime = strtotime($eventdet[0]['close_date']);
$now = strtotime(date("Y-m-d H:i:s", time()));

$currdate = date('Y-m-d');
$dateevent = $eventdet[0]['date'];

$simpdate = strtotime($eventdet[0]['date']);
$simpdate = date('d F Y',$simpdate);
// var_dump($eventdet);
// var_dump($eventdet[0]['spec_event']);
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

    <!-- <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @media (max-width: 991px) {
            .event-details {
                order: 2;
                margin-top: 2rem;
            }
        }

        @media (min-width:320px) {
            .banner-img {

                width: 300px !important;
                height: 100% !important;
            }

        }

        @media (min-width:375px) {
            .banner-img {

                width: 350px !important;
                height: 100% !important;
            }

        }

        @media (min-width:425px) {
            .banner-img {

                width: 400px !important;
                height: 100% !important;
            }
        }

        @media (min-width:768px) {
            .banner-img {

                width: 700px !important;
                height: 100% !important;
            }
        }

        .banner-img {
            width: 50%;
            height: 50%;
        }
        .gap-x-3{
            column-gap: 1rem;
        }
        .hidden{
            display: none;
        }
    </style>

</head>

<body>
    <script>
        $(document).ready(function() {
            $("#error").delay(5000).fadeOut();
        });
    </script>
    <header>
        <nav class="navbar navbar-expand-lg border-bottom">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <img src="images/logo/new-logo.png" alt="dlogo" class="img-fluid logo">
                </a>
                <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <div id="line1"></div>
                    <div id="line2"></div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/event-program/">Dashboard</a>
                        </li> -->



                        <!-- <li class="nav-item ">
                            <a class="nav-link active" href="/event-program/form-registration.php?id=2">Registration</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="attend/index.php?id=<?= $eventdet[0]['id'] ?>"><strong>Back to <?= $eventdet[0]['name'] ?> Details Page</strong></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <?php


    if (isset($_SESSION['error'])) {
        $err = $_SESSION['error'];
        echo " <div id=\"error\" class=\"alert alert-danger m-5\" role=\"alert\">
        <strong> $err </strong>
    </div>";

        unset($_SESSION['error']);
    }
    ?>
    <section class="py-5">
        <div class="container">
            <?php if($eventdet[0]['spec_event'] == 1)
            {

            echo  '<h1 class="text-primary text-center mb-3">'.$eventdet[0]['name'].'</h1>';

            }
            ?>
            <h1 class="text-primary text-center"></h1>
            <h2 class="text-primary text-center">
                <?php
                if($eventdet[0]['spec_event'] == 2){
                    echo "EVENT REGISTRATION";
                }else{

                if($eventdet[0]['spec_event'] == 1) {echo "FREE CONSULTATION & PIONEER INCENTIVE";}?> <?php if(isset($walkin)){echo "Walkin";}?>
            <?php  }?>
            </h2>
            <p class="mb-5 text-muted text-center">Exploring Energy Efficiency Solution With Zero Upfront Cost</p>
            <div class="d-flex justify-content-center mb-5 over-x">
                <?php
                if($eventdet[0]['spec_event'] == 1){
                    ?>
                    <img class="banner-img "  src= "images/upload/spec_event.png" alt="banner">

                    <?php

                }else{
                    ?>
                    <img class="banner-img "  src= "<?= $eventdet[0]['banner_img'] ?>" alt="banner">

                    <?php
                }
                ?>

            </div>

            <div class="row">

                <!-- hide -->
                <!-- <div class="col-lg-5 pe-md-5 event-details">
                    <p class="text-justify">Program for the residential buildings to ease the burden of high electricity bills by achieving 80% of savings on lighting and reduce carbon footprint. REGISTER TO SAVE YOUR SEAT NOW!! Registration ends Thursday, 2 November 2023.</p>
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card card-event border-bottom">
                                <div class="card-body d-flex">
                                    <i class="bi bi-calendar3"></i>
                                    <div>
                                        <?php


                                        $time = strtotime($eventdet[0]['date']);

                                        $date = date('j F Y', $time);
                                        $day = date('l', $time);

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
                                        <h6 class="mb-0">09:00am - 12:30pm</h6>
                                        <p><small>Lunch will be provided</small></p>
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
                </div> -->

                <!-- end hide -->


                <div class="col-lg-7 ps-md-5 mx-auto">
                    <!-- <h6>Sign up to save your seat.</h6>
                    <p class="mb-5 text-muted">Registration ends Thursday, 2 November 2023.</p> -->
                    <form id="form-res" action="evregprocess.php" method="POST" class="row g-3">
                        <?php
                        if($eventdet[0]['spec_event'] == 2){
                            ?>
                            <span>We collaborate with our clients around the world to find ways to
                                improve their energy efficiency. Synergy ESCO (Malaysia) Sdn Bhd can help to save up to 88% in the lighting energy cost.</span>
							<hr>
							<div class="col-md-12">
								<label for="inputPassword4" class="form-label">What are you looking for by joining this event?</label>

								<textarea class="form-control" id="user_interest" name="user_interest" rows="4" cols="50"></textarea>
							</div>
                            <hr>
                            <div class="form-group mb-3">
                                <label class="form-label" for="date_ev">Date:</label>
                                <br>
                                <input required class="form-control" min="" value="" type="date" id="date_ev" name="date_ev">
                            </div>
                            <span class="fw-bold">Personal information</span>
                            <hr>
                            <label for="cityCouncil" class="form-label">Designation: <strong class="text-danger">*</strong> </label>
                                    <select name="designation" id="designation" class="form-select mb-3" required>
                                        <option selected value="">Choose...</option>
                                        <option>Chairman / Pengerusi</option>
                                        <option>Building Manager / Pengurus Bagunan</option>
                                        <option>Secretary / Setiausaha</option>
                                        <option>Treasurer / Bendahari</option>
                                        <option>Admin / Staff</option>
                                        <option>Technician / Juruteknik</option>
                                        <option>Building Executive / Eksekutif Bagunan</option>
                                        <option>Manager / Pengurus</option>
                                        <option>Comittee Member / Ahli Jawatankuasa</option>
                                        <option>Others</option>
                                    </select>
                                    <input type="text" disabled name="designation2" class="form-control" id="desig2" placeholder="Please input other designation">

                                <div class="col-md-12">
                                    <label for="inputPassword4" class="form-label">First Name <strong class="text-danger">*</strong> </label>
                                    <input required type="text" name="fname" class="form-control" id="fullName">
                                </div>
                                <div class="col-md-12">
                                    <label for="inputPassword4" class="form-label">Last Name</label>
                                    <input  type="text" name="lname" class="form-control" id="fullName">
                                </div>
                                <div class="col-md-12">
                                    <label for="inputPassword4" class="form-label">Preferred name</label>
                                    <input type="text" name="nickname" class="form-control" id="nickname">
                                </div>

                                <div class="col-md-12">
                                    <div id="inemail"></div>
                                    <label for="inputEmail4" class="form-label">Email <strong class="text-danger">*</strong> </label>
                                    <input required type="email" name="email" class="form-control" id="mail">
                                </div>

                                <div class="mb-2">
                                <label for="basic-url" class="form-label">Mobile Number <strong class="text-danger">*</strong> </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">+60</span>
                                    <input required name="tel" type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                                </div>

                                <span class="fw-bold">Building information</span>
                                <hr>
                                <div class="col-md-12">
                                    <label for="inputCity" class="form-label">Name of Strata Building</label>
                                    <input  type="text" name="building" class="form-control" id="stratabuilding" placeholder="(Eg. Mira Condo)/Facility Management/Property Management">
                                </div>
                                <div class="col-md-6">
                                    <label for="cityCouncil" class="form-label">City Council/Company: <strong class="text-danger">*</strong> </label>

                                    <select  name="city_council" id="cityCouncil" class="form-select mb-3" required >
                                        <option selected value="">Choose city council..</option>
                                        <?php

                                        foreach ($cityc as $key => $value) {

                                            if ($value['id'] == "7") {
                                                continue;
                                            } else {
                                                echo "<option value = $value[id]> $value[city_council]</option>";
                                            }
                                        }
                                        ?>
                                        <option value="7">Other FM/PMC</option>

                                    </select>

                                    <input type="text" disabled name="othercom" class="form-control mt-3" id="othercom" placeholder="FM / PMC name">
                                </div>
                                <div class="col-md-6 hidden">
                                  <label for="starta" class="form-label">Strata Status:</label>
                                  <select name="starta_status" id="starta_status" class="form-select">
                                    <option selected value="NULL">Choose...</option>
                                    <option>JMB</option>
                                    <option>MC</option>
                                    <option>Commercial</option>
                                  </select>
                                </div>

                                <span class="fw-bold mt-5">PREFFERED TO CONTACT USING</span>
                                <hr>
                                <div class="mb-3">
                                  <div class="mb-3">
                                    <input type="radio" id="cont1" name="contact_by" value="1" checked>
                                    <label for="cont1">EMAIL</label><br>
                                  </div>
                                  <div class="mb-3">
                                    <input type="radio" id="cont2" name="contact_by" value="2">
                                    <label for="cont2">MOBILE NUMBER</label><br>
                                  </div>
                                  <div class="mb-3">
                                    <input type="radio" id="cont2" name="contact_by" value="3">
                                    <label for="cont2">WHATSAPP</label><br>
                                  </div>
                                </div>
                            <?php }else{ ?>
                             <span>We collaborate with our clients around the world to find ways to improve their energy efficiency. <?php if($eventdet[0]['spec_event'] == 1) {echo "Synergy can help to save up to 88% in the lighting energy cost.";}else{ echo "On average, we helped them save 80% in their energy costs.";} ?></span>
                             <span class="fw-bold">Personal information</span>
                                <hr>
                                <div class="col-md-12">
                                    <label for="inputPassword4" class="form-label">Personal Name</label>
                                    <input type="text" name="name" class="form-control" id="fullName">
                                </div>


                                <div class="col-md-12">
                                    <label for="inputEmail4" class="form-label">Personal Email</label>
                                    <input type="email" name="email" class="form-control" id="inputEmail">
                                </div>

                                <div class="mb-2">
                                <label for="basic-url" class="form-label">Phone No.</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">+60</span>
                                    <input name="tel" type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="stname" class="form-label">Strata Name</label>
                                    <input type="text" name="str_name" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label for="stname" class="form-label">How many units are there in your Strata Residence?</label>
                                    <input type="number" name="no_units" class="form-control">
                                </div>
                                <div class="col-md-6">
                                  <label for="parking" class="form-label">Does your Strata Residence have indoor carpark?</label>
                                  <select name="parking" id="parking" class="form-select">
                                    <option selected value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                  </select>
                                </div>
                                <div class="col-md-6">
                                  <label for="represent" class="form-label">Represent from:</label>
                                  <select name="rep" id="represent" class="form-select">
                                    <option selected value="">Select</option>
                                    <option>PMC / BM</option>
                                    <option>Committee</option>
                                    <option>Owner</option>
                                    <option>Other Profession</option>
                                  </select>
                                </div>
                                <div class="col-md-12">
                                  <label for="shinesalad" class="form-label">Are you interested in subscribing to our Shine+ Salad?</label>
                                  <select name="shinesalad" id="shinesalad" class="form-select">
                                    <option selected value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                  </select>
                                </div>
                            <?php } ?>
                        <input type="hidden" name="id" value=<?= $id ?>>
                        <div class="col-12">
                            <button id="signup" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="d-flex gap-x-3 pt-3">
                <div>
                    <img src="images/logo/icon.png" alt="fotter-icon">
                </div>

            </div>
            <div class="row py-3">
                <h6 class="mb-0 col-lg-6">&copy; 2024 Synergy (ESCO) Malaysia. All Rights Reserved.</h6>
                <p class="col-lg-6 text-lg-end text-start">
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms & Conditions</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script>
        $("#ccrad").click(function(){
            $("#cityCouncil").attr("disabled",false)
            $("#fpmcdrop").attr("disabled",true)
        })
        $("#fmrad").click(function(){
            $("#cityCouncil").attr("disabled",true)
            $("#fpmcdrop").attr("disabled",false)
        })
        $("#mail").change(function(){
            let mail =  $("#mail").val()
            console.log(mail)
            $.get(`attend/ajaxemail.php?email=${mail}`, function(data, status){
                if(data == 1){
                    let str = "<strong class=\"text-danger\">Already exist please use different email</strong>"
                    $("#inemail").html(str);
                    $("#signup").attr("disabled",true)

                }else if(data == 0){
                    let str = ""
                    $("#inemail").html(str);
                    $("#signup").attr("disabled",false)
                }
            });
        })
        $("#designation").change(function() {
            var select = $("#designation").val();
            if (select == "Others") {

                $("#desig2").attr("disabled", false);
            } else {
                $("#desig2").attr("disabled", true);
            }
        })
        $("#cityCouncil").change(function() {
            var select = $("#cityCouncil").val();
            if (select == "7") {

                $("#othercom").attr("disabled", false);
            } else {
                $("#othercom").attr("disabled", true);
            }
        })
        $("#signup").click(function() {
            $("#signup").attr("disabled",true);
            $("#form-res").submit();
        })
        $(document).ready(function(){
            console.log("ready")

            let  today 		= new Date();
            let  dd 		= String(today.getDate()).padStart(2, '0');
            let  mm 		= String(today.getMonth() + 1).padStart(2, '0'); //janvier = 0
            let  yyyy 		= today.getFullYear();

            //set min date for date picker min date = today
            let min_date = `${yyyy}-${mm}-${dd}`;
            $("#date_ev").attr('min',min_date)
            $("#date_ev").attr('value',min_date)

        })
    </script>
</body>

</html>
