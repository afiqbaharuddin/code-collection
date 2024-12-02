

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
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#">Event</a>
                            <!-- <ul class="dropdown-menu">
                                <?php /*
                                foreach ($event as $key => $value) { 
                                    
                                    ?>
                                    
                                    <li><a class="dropdown-item" href="index.php?id=<?= $value['id']?>"><?= $value['name'] ?></a></li>

                                <?php
                                 }
                                */ ?> 

                            </ul> -->
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/event-program/index.php?id=1" class="dropdown-item">
                                        <div>
                                            <p class="text-muted"><small>21st Oct.</small></p>
                                            <h6>Bangi Golf Resort</h6>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="/event-program/index.php?id=2" class="dropdown-item">
                                        <div>
                                            <p class="text-muted"><small>4th Nov.</small></p>
                                            <h6>Le Meridien PJ</h6>
                                        </div>
                                   </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item ">
                            <a class="nav-link" href="/event-program/form-registration.php?id=2">Registration</a>
                        </li>
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
            <h2 class="text-primary">Event Registration</h2>
            <p class="mb-5 text-muted">Exploring Energy Efficiency Solution With Zero Upfront Cost</p>
            <div class="row">
                <div class="col-lg-5 pe-md-5">
                    <p class="text-justify">Program for the residential buildings to ease the burden of high electricity bills by achieving 80% of savings on lighting and reduce carbon footprint. REGISTER TO SAVE YOUR SEAT NOW!! Registration ends Thursday, 2 November 2023.</p>
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card card-event border-bottom">
                                <div class="card-body d-flex">
                                    <i class="bi bi-calendar3"></i>
                                    <div>
                                        <h6 class="mb-0">4 November 2023</h6>
                                        <p><small>Saturday</small></p>
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
                                <h6 class="mb-0">Mercure Hotel, Glenmarie</h6>
                                <p>
                                    <small>Jalan Kontraktor U1/14, Seksyen U1, 40150 Shah Alam, Selangor</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 ps-md-5">
                    <!-- <h6>Sign up to save your seat.</h6>
                    <p class="mb-5 text-muted">Registration ends Thursday, 2 November 2023.</p> -->
                    <form action="" method="" class="row g-3">
                        <div class="col-md-12">
                            <label for="inputPassword4" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName">
                        </div>
                        <div class="col-md-12">
                            <label for="inputAddress" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail">
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress2" class="form-label">Telephone No.</label>
                            <input type="tel" class="form-control" id="phoneNo" placeholder="">
                        </div>
                        <div class="col-md-12">
                            <label for="inputCity" class="form-label">Name of Strata Building </label>
                            <input type="text" class="form-control" id="stratabuilding" placeholder="(Eg. Mira Condo)/Facility Management/Property Management">
                        </div>
                        <div class="col-md-6">
                            <label for="cityCouncil" class="form-label">City Council/Company:</label>
                            <select id="cityCouncil" class="form-select">
                                <option selected>Choose...</option>
                                <option>...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="noLights" class="form-label">Number of lights:</label>
                            <input type="number" class="form-control" id="noLights">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Sign in</button>
                        </div>
                    </form>
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

    <script src="js/bootstrap/bootstrap.min.js"></script>

</body>
</html>