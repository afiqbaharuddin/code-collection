<?php

require('../db_conn.php');
$eid = $_POST['id'];
$target_dir = "/images/upload/";
$namedir =  "images/upload/";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);
// if ($_FILES["banner"]["name"] != "") {



//   $ori_file = $target_dir . basename($_FILES["banner"]["name"]);
//   $uploadOk = 1;
//   // $con = file_get_contents($_FILES["banner"]["tmp_name"]);
//   // var_dump($con);
//   // die();
//   $imageFileType = strtolower(pathinfo($ori_file, PATHINFO_EXTENSION));
//   $target_file = $target_dir . "event$eid.$imageFileType";
//   //$savepath = $target_file;
//   $savepath = $namedir . "event$eid.$imageFileType";
//   var_dump($target_file);
//   // Check if image file is a actual image or fake image
//   if (isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["banner"]["tmp_name"]);
//     if ($check !== false) {
//       echo "File is an image - " . $check["mime"] . ".";
//       $uploadOk = 1;
//     } else {
//       echo "File is not an image.";
//       $uploadOk = 0;
//     }
//   }

//   // Check if file already exists
//   if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
//   }

//   // Check file size
//   if ($_FILES["banner"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
//   }
//   $dirpath = dirname(getcwd());
//   $target_file = $dirpath . $target_file;
//   // var_dump($target_file);
//   // die();
//   // Allow certain file formats
//   if (
//     $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//     && $imageFileType != "gif"
//   ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
//   }

//   // Check if $uploadOk is set to 0 by an error
//   if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
//     //die();
//     // if everything is ok, try to upload file
//   } else {
//     if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
//       echo "The file " . htmlspecialchars(basename($_FILES["banner"]["name"])) . " has been uploaded.";
//     } else {
//       echo "Sorry, there was an error uploading your file.";
//     }
//   }

//   $bannerpath = $savepath;
//   $bannercom = ", banner_img = '$bannerpath'";
// } else {

//   $bannercom = "";
// }


// if(isset($_POST['banner'])){
//   $bannerpath = $savepath;
//   $bannercom = ", banner_img = '$bannerpath'";
// }else{
//   $bannerpath = "";
//   $bannercom = "";
// }
// DIE("OUTT");

if ($_FILES["banner"]["name"] != "") {
  $bannercom = save_img("banner", $eid);
} else {
  $bannercom = "";
}

if ($_FILES["thumbnail"]["name"] != "") {
  $thumbcom = save_img("thumbnail", $eid);
} else {
  $thumbcom = "";
}

$id      = $_POST['id'];
$name    = $_POST['name'];
$address = $_POST['address'];
$survey  = $_POST['survey_form'];
$date    = $_POST['date'];

if(!empty($_POST['close'])){
  $close = $_POST['close'];
}else{
  $close = NULL;
}

$maps   = $_POST['maps'];
$banner = $_POST['banner_img'];

if($close == NULL){
$column = " name = '$name' , address = '$address', survey_form = '$survey', date = '$date',close_date = NULL, maps = '$maps' $bannercom $thumbcom";
}else{
$column = " name = '$name' , address = '$address', survey_form = '$survey', date = '$date',close_date = '$close' , maps = '$maps' $bannercom $thumbcom";
}
update($conn, 'event', $column, $id);
header("location:listevent.php");


function save_img($source, $eid)
{
  $target_dir = "/images/upload/";
  $namedir    =  "images/upload/";
  $ori_file   = $target_dir . basename($_FILES[$source]["name"]);
  $uploadOk   = 1;

  $imageFileType = strtolower(pathinfo($ori_file, PATHINFO_EXTENSION));
  if ($source == "thumbnail") {
    $target_file = $target_dir . "thumbevent$eid.$imageFileType";
    $savepath = $namedir . "thumbevent$eid.$imageFileType";
  } else {
    $target_file = $target_dir . "event$eid.$imageFileType";
    $savepath = $namedir . "event$eid.$imageFileType";
  }

  var_dump($target_file);
  // Check if image file is a actual image or fake image
  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES[$source]["tmp_name"]);
    if ($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
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

  // Check file size
  // if ($_FILES[$source]["size"] > 500000) {
  //   echo "Sorry, your file is too large.";
  //   $uploadOk = 0;
  // }

  $dirpath     = dirname(getcwd());
  $target_file = $dirpath . $target_file;

  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
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

  $bannerpath = $savepath;
  $bannercom  = ", banner_img = '$bannerpath'";

  switch ($source) {
    case 'banner':
      $bannercom = ", banner_img = '$bannerpath'";
      break;
    case 'thumbnail':
      $bannercom = ", thumbnail_img = '$bannerpath'";
      break;

    default:
      break;
  }

  return $bannercom;
}
