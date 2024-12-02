<?php
require('db_conn.php');
session_start();

$cityc    = selectall($conn, 'city_council ORDER BY `city_council` DESC');
$event_id = $_GET['id'];
$event    = selectall($conn, 'event');
$eventdet = selectall($conn, 'event', "id = $event_id");

$countevent = countall($conn, 'event');
$time       = strtotime($eventdet[0]['date']);

// $newformat = date('Y-m-d',$time);
$nowdate    = date("Y-m-d");
$closedate  = selectall($conn, 'event', "id = $event_id AND close_date < \"$nowdate\"");
$openedate  = selectall($conn, 'event', "id = $event_id AND close_date >= \"$nowdate\"");
$closedate  = count($closedate);
$openedate  = count($openedate);
$onevent    = selectall($conn, 'event', "id = $event_id AND date = \"$nowdate\"");
$onevent    = count($onevent);

date_default_timezone_set("Asia/Kuala_Lumpur");

$closestrtime = strtotime($eventdet[0]['close_date']);
$now          = strtotime(date("Y-m-d H:i:s", time()));

$currdate  = date('Y-m-d');
$dateevent = $eventdet[0]['date'];

$simpdate = strtotime($eventdet[0]['date']);
$simpdate = date('d F Y',$simpdate);

// var_dump("$currdate $dateevent");
// if()

$postpone = $eventdet[0]['postpone'];
if($postpone == 1){
    $pagestts = "POSTPONE";
}else{
    if ($currdate != $dateevent) {
        if ($closestrtime > $now) {
            //not pass close time yet
            $pagestts = "OPEN";
        } else {
            //have pass close date form close
            $pagestts = "CLOSE";
        }
    } else {
        //currrent date = to event date
        $pagestts = "OPEN";
        $walkin = "true";
    }
}


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

		.icon-rounded {
			background: #07a479;
			border-radius: 50%;
			height:35px;
			width: 35px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-right: 16px;
		}

		.icon-rounded i {
			color: white;
		}

		.icon-rect {
			background: #07a479;
			border-radius: 8px;
			height:35px;
			width: 35px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-right: 5px;
		}

		.icon-rect i {
			color: white;
		}
		.highlight {
        	   border: 1px solid red; /* Highlight with a red border */
    }
    input::placeholder {
      color: #B0B7C0 !important;
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
                    <img src="/images/logo/synery group logo -.png" alt="" class="img-fluid logo">
                </a>
                <button class="navbar-toggler btn btn-menu dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <div id="line1"></div>
                    <div id="line2"></div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/event-program/">Dashboard</a>
                        </li>



                        <li class="nav-item ">
                            <a class="nav-link active" href="/event-program/form-registration.php?id=2">Registration</a>
                        </li>
						-->
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
            <h2 class="text-primary text-center">Event Registration <?php if(isset($walkin)){echo "Walkin";}?></h2>
            <p class="mb-5 text-muted text-center">Exploring Energy Efficiency Solution With Zero Upfront Cost</p>
            <?php
				$imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
				// $imgExtsJpg = array("jpg", "jpeg");
				$url ='https://synergy-group.com.my/event-program/'.$eventdet[0]['banner_img'];
				$urlExt = pathinfo($url, PATHINFO_EXTENSION);
				if (in_array($urlExt, $imgExts)) {
			?>
			<!-- <div class="d-flex justify-content-center mb-5 over-x"> -->
                <img class="img-fluid mb-5" src="<?= $eventdet[0]['banner_img'] ?>" alt="banner">

            <!-- </div> -->
			<?php
				}else{
					// echo 'No Banner attached for this event';
				}
			?>


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
                    <?php

                    switch ($pagestts) {
                        case 'OPEN':
                            # code...
                    ?>
                            <form id="form-res" action="regprocess.php" method="POST" class="row g-3">
                                <span class="fw-bold">Personal information</span>
                                <hr>
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">First Name <span style="color: red;">*</span></label>
                                    <input type="text" name="firstname" class="form-control" id="firstName" required>
				                            <p id="fieldError" class="fieldError" style="color: red;"></p>
                                </div>
								                <div class="col-md-6">
                                    <label for="lastname" class="form-label">Last Name <span style="color: red;">*</span></label>
                                    <input type="text" name="lastname" class="form-control" id="lastName" required>
				                            <p id="fieldError" class="fieldError" style="color: red;"></p>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputPassword4" class="form-label">Preferred name <span style="color: red;">*</span></label>
                                    <input type="text" name="nickname" class="form-control" id="nickname" required>
                                </div>
                                <div class="col-md-12">
                                    <!-- <label for="inputAddress" class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" id="designation" placeholder=""> -->
                                    <label for="cityCouncil" class="form-label">Designation: <span style="color: red;">*</span></label>
                                    <select name="designation" id="designation" class="form-select" required>
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
                                        <option>Symposium Speaker</option>
                                        <option>Others</option>
                                    </select>
				                                <p id="fieldError" class="fieldError" style="color: red;"></p>
                                    <input type="text" disabled name="designation2" class="form-control mt-3" id="desig2" placeholder="Please input other designation" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Email <span style="color: red;">*</span></label>
                                    <input type="email" name="email" class="form-control" id="inputEmail" required>
 				                               <p id="fieldError" class="fieldError" style="color: red;"></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="phoneNo" class="form-label">Phone No. <span style="color: red;">*</span></label>
                                    <input type="tel" name="tel" class="form-control" id="phoneNo" placeholder="" value="+60" required>
				                            <p id="fieldError" class="fieldError" style="color: red;"></p>
                                </div>

                                <span class="fw-bold">Building information</span>
                                <hr>
                                <div class="col-md-12">
                                    <label for="inputCity" class="form-label">Name of Strata Building <span style="color: red;">*</span></label>
                                    <input type="text" name="building" class="form-control" id="stratabuilding" placeholder="(Eg. Mira Condo)/Facility Management/Property Management" required>
				                            <p id="fieldError" class="fieldError" style="color: red;"></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="cityCouncil" class="form-label">City Council/Company: <span style="color: red;">*</span></label>
                                    <select name="city_council" id="cityCouncil" class="form-select" required>
                                        <option selected value="">Choose...</option>
                                        <?php

                                        foreach ($cityc as $key => $value) {

                                            if ($value['id'] == "7") {
                                                echo "<option value = $value[id]> Facilities Management / Property Management Company (FM / PMC)</option>";
                                            } else {
                                                echo "<option value = $value[id]> $value[city_council]</option>";
                                            }
                                        }
                                        ?>

                                    </select>
				                            <p id="fieldError" class="fieldError" style="color: red;"></p>
                                    <input type="text" disabled name="othercom" class="form-control mt-3" id="othercom" placeholder="FM / PMC name">
                                </div>
                                <div class="col-md-6">
                                    <label for="starta" class="form-label">Strata Status: <span style="color: red;">*</span></label>
                                    <select name="starta_status" id="starta_status" class="form-select" required>
                                        <option selected value="">Choose...</option>
                                        <option>JMB</option>
                                        <option>MC</option>
                                        <option>FMC</option>
                                        <option>Commercial</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="starta" class="form-label">Number of lights: <span style="color: red;">*</span></label>
                                    <select name="light" id="starta_status" class="form-select" required>
                                        <option selected value="">Choose...</option>
                                        <option>1,000 below</option>
                                        <option>1,001 - 3,000</option>
                                        <option>3,001 - 5,000</option>
                                        <option>5,001 above</option>
                                    </select>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="starta" class="form-label">Type of Lights:</label>
                                    <select name="type_light" id="starta_status" class="form-select" required>
                                        <option selected value="">Choose...</option>
                                        <option>LED / LED </option>
                                        <option>Fluorescent / Lampu kalimantang</option>
                                        <option>Both above / Kedua-dua di atas</option>
                                    </select>
                                </div> -->

                                <div class="col-md-6">
                                    <label for="" class="form-label">Number of residential units <span style="color: red;">*</span></label>
                                    <input type="text" name="no_residents_unit" class="form-control" required>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="" class="form-label">Number of blocks</label>
                                    <input type="text" name="no_blocks" class="form-control" required>
                                </div> -->

                                <span class="fw-bold">Additional Participant</span>
                                <hr>
                                <div class="col-md-10">
                                <label for="addParticipant" class="form-label">Choose No. of addtional participant attending:</label>
                                <select class="form-select" name="addParticipant" id="addParticipant">
                                  <option value="" selected>Choose..</option>
                                  <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                      echo "<option value=\"$i\">$i</option>";
                                    }
                                  ?>
                                </select>
                              </div>

                                <!-- Container add participant field -->
                                <div id="participantFields" class="mt-3"></div>
								<!--
                                <div class="col-md-6">
                                    <label for="starta" class="form-label">Parking</label>
                                    <select name="parking" id="starta_status" class="form-select">
                                        <option selected value="NULL">Choose...</option>
                                        <option>Indoor parking / Tempat letak kereta dalam bangunan </option>
                                        <option>Outdoor parking / Tempat letak kereta di luar bangunan</option>
                                        <option>Both above / Kedua-dua di atas</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="starta" class="form-label">TNB Effective Tariff Rate</label>
                                    <select name="tariff" id="starta_status" class="form-select">
                                        <option selected value="NULL">Choose...</option>
                                        <option>Residential / Residensi </option>
                                        <option>Commercial / Komersial</option>
                                        <option>Mix Development</option>

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
                                        <label for="cont2">MOBILE NUMBER [CALL]</label><br>
                                    </div>
                                    <div class="mb-3">
                                        <input type="radio" id="cont2" name="contact_by" value="3">
                                        <label for="cont2">WHATSAPP</label><br>
                                    </div>

                                </div>
								-->

                                <input type="hidden" name="event_id" value=<?= $event_id ?>>
                                <div class="col-12">
                                    <button id="signup" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>


                        <?php
                            break;
                        case 'CLOSE':
                        ?>
                            <span> Online registration is now closed.
                                For any inquiries, kindly contact +6013-201 7743
                                Thank you!</span>

                            <hr>
                            <span>
                                Pendaftaran atas talian telah ditutup.
                                Untuk sebarang pertanyaan, sila hubungi +6013-201 7743
                                Terima kasih!
                            </span>

                    <?php
                            # code...
                            break;
			case "POSTPONE":
                        ?>
                            <span>
                                This is to inform you that the Green Initiative Program scheduled on <?= $simpdate?>, 2.00 pm at Dorsett Grand Subang has been postponed to a later date due to unavoidable circumstances. We apologize for the inconvenience caused.
                                For any inquiries, kindly contact our Customer Service at +6013-201 7743.

                            </span>
                            <hr>
                            <span>Dimaklumkan bahawa majlis Program Inisiatif Hijau yang dijadualkan pada <?= $simpdate?> bertempat di Dorsett Grand Subang telah ditunda kepada suatu tarikh baharu yang akan dimaklumkan kemudian atas sebab-sebab yang tidak dapat dielakkan.
                                Sebarang kesulitan amatlah dikesali dan pertanyaan boleh diajukan kepada Khidmat Pelanggan kami di talian +6013-201 7743.</span>
                    <?php
                            break;

                        default:
                            # code...
                            break;
                    }
                    ?>

                </div>
				<div class="col-lg-7 ps-md-5 mx-auto">
				<br/>
				<hr>
				<br/>
				</div>

				<div class="col-lg-7 mx-auto">

					<?php
						$imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
						// $imgExtsJpg = array("jpg", "jpeg");
						$url ='https://synergy-group.com.my/event-program/'.$eventdet[0]['thumbnail_img'];
						$urlExt = pathinfo($url, PATHINFO_EXTENSION);
						if (in_array($urlExt, $imgExts)) {
					?>
					<!-- <a target="_blank" href="../event-program/images/Flyers.jpg" download> -->
					<a target="_blank" href="<?= $eventdet[0]['thumbnail_img'] ?>" download>
						<!-- <img class="img-fluid" src="../event-program/images/Flyers.jpg" alt="" /> -->
						<img class="img-fluid" src="<?= $eventdet[0]['thumbnail_img'] ?>" alt="" />
					</a>
					<?php
						}else{
							// echo 'No Flyer attached for this event';
						}
					?>
				</div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row py-5">
				<div class="col-lg-4">
					<div class="d-flex">
						<div class="icon-rounded">
							<i class="bi bi-geo-alt"></i>
						</div>
						<p class="mb-3 mb-lg-0"><span class="fw-bold text-primary">Synergy ESCO (Malaysia) Sdn. Bhd.</span><br>
						Lot No.B-2-2, Level 2, The Ascent, Paradigm<br>
						No. 1, Jalan SS7/26A, Kelana Jaya,<br>
						47301 Petaling Jaya, Selangor, Malaysia</p>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="d-flex align-items-center mb-2">
						<div class="icon-rounded">
						<i class="bi bi-telephone"></i>
						</div>
						<p>+603 7890 1188</p>
					</div>
					<div class="d-flex align-items-center mb-2">
						<div class="icon-rounded">
						<i class="bi bi-envelope"></i>
						</div>
						<p><a href="mailto:enquiry@synergy-group.com.my">enquiry@synergy-group.com.my</a></p>
					</div>
					<div class="d-flex align-items-center mb-3 mb-lg-0">
						<div class="icon-rounded">
						<i class="bi bi-globe"></i>
						</div>
						<p><a href="https://synergy-group.com.my" target="_blank">https://synergy-group.com.my</a></p>
					</div>
				</div>
				<div class="col-lg-4 d-flex justify-content-between flex-column">
					<div class="d-flex align-items-center">
						<a href="https://www.facebook.com/synergygroup.malaysia" target="_blank" class="icon-rect">
							<i class="bi bi-facebook"></i>
						</a>
						<a href="https://www.instagram.com/synergy_malaysia?igsh=NGVmOTZkbGs5eXk3" target="_blank" class="icon-rect">
							<i class="bi bi-instagram"></i>
						</a>
						<a href="https://www.linkedin.com/company/synergymalaysia/" target="_blank" class="icon-rect">
							<i class="bi bi-linkedin"></i>
						</a>
						<a href="https://www.youtube.com/channel/UCqA_kGxF_5vsrg6V6k4ZwZw" target="_blank" class="icon-rect">
							<i class="bi bi-youtube"></i>
						</a>
						<h6 class="text-primary">Synergy Malaysia</h6>
					</div>
					<p>&copy; 2024 Synergy ESCO (M). All Rights Reserved.</p>
				</div>
                <!-- <p class="col-lg-4 text-lg-end text-start">
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms & Conditions</a>
                </p> -->
            </div>
        </div>
    </footer>

    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <script>
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

        $('#addParticipant').change(function(){
          $('#participantFields').empty();

          let numberOfParticipants = $(this).val();

          if (numberOfParticipants > 0) {
            for (let i = 0; i < numberOfParticipants; i++) {
            $('#participantFields').append(`
              <div class="row">
              <label class="form-label mt-2">Participant Details:</label>
                <div class="col-md-5">
                  <label for="participantName${i}" class="form-label">Name:</label>
                  <input type="text" class="form-control" id="participantName${i}" name="participantName${i}" required>
                </div>
                <div class="col-md-3">
                  <label for="participantPhone${i}" class="form-label">Phone Number:</label>
                  <input type="text" class="form-control" id="participantPhone${i}" name="participantPhone${i}" value= "+60" required>
                </div>
                <div class="col-md-4">
                  <label for="designation${i}" class="form-label">Designation:</label>
                  <input type="text" class="form-control" id="designation${i}" name="designation${i}" placeholder= "Eg: Building Manager" required>
                </div>
              </div>
            `);
           }
          }
        });

        //phone no format main pax
        const phoneInput = document.getElementById('phoneNo');
        // Set initial value if not present
        if (!phoneInput.value.startsWith('+60')) {
          phoneInput.value = '+60';
        }

        phoneInput.addEventListener('input', function() {
          // Prevent user from deleting +60
          if (!phoneInput.value.startsWith('+60')) {
              phoneInput.value = '+60';
          }
        });

        phoneInput.addEventListener('keydown', function(e) {
          const caretPosition = phoneInput.selectionStart;

          // Prevent the backspace and delete keys from removing +60
          if ((e.key === 'Backspace' || e.key === 'Delete') && caretPosition <= 3) {
              e.preventDefault();
          }
        });
    </script>
</body>

</html>
