<?php

require('../db_conn.php');

$name         = $_POST['name'];
$address      = $_POST['address'];
$surveyform   = $_POST['survey_form'];
$date         = $_POST['date'];
$maps         = $_POST['maps'];
$location     = $_POST['locationname'];
$start_time   = $_POST['start_time'];
$end_time     = $_POST['end_time'];
$close_date   = $_POST['close_date'];
$food         = $_POST['food'];

$bannercom    = "";
$thumbcom     = "";

if (!empty($food)) {
  $food = $_POST['food'];
}else {
  $food = 'No Food Provided';
}

// Handling banner upload
if (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
    $bannercom = save_img("banner");
}

// Handling thumbnail upload
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
    $thumbcom = save_img("thumbnail");
}

function save_img($source) {
    $target_dir = "/images/upload/";
    $namedir    = "images/upload/";
    $ori_file   = $target_dir . basename($_FILES[$source]["name"]);
    $uploadOk   = 1;

    $imageFileType = strtolower(pathinfo($ori_file, PATHINFO_EXTENSION));

    if ($source == "thumbnail") {
        $target_file = $target_dir . "thumbevent.$imageFileType";
        $savepath    = $namedir . "thumbevent.$imageFileType";
    } else {
        $target_file = $target_dir . "event.$imageFileType";
        $savepath    = $namedir . "event.$imageFileType";
    }

    // Check if image file is an actual image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$source]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    $dirpath     = dirname(getcwd());
    $target_file = $dirpath . $target_file;

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES[$source]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES[$source]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    return $savepath;
}

// Adjust the SQL columns and values to include the images
$column = "name, address, survey_form, date, maps, location_name, banner_img, thumbnail_img, start_time, end_time, close_date, food";
$value  = "'$name', '$address', '$surveyform', '$date', '$maps', '$location', '$bannercom', '$thumbcom', '$start_time', '$end_time', '$close_date', '$food'";

// Insert value to db and redirect to list event
insert($conn, 'event', $column, $value);
header("location:listevent.php");
