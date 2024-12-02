<?php
//ini_set('display_errors', 1);

if (isset($direct)) {
    require_once('phpmailer_5.2.0/class.phpmailer.php');
} else {
    require_once('../phpmailer_5.2.0/class.phpmailer.php');
    // require_once('../phpmailer_5.2.0/class.phpmailer.php')
    require('../path.php');
    require('../db_conn.php');
    $action = $_GET['action'];
    switch ($action) {
        case 'bulkmail':
            bulkmail($conn, $path);
            break;
        case 'feedback':

            feedback($conn, $path);
            break;
        case 'reminder':
            $for = $_GET['for'];
            reminder($conn, $path, $for);

        default:
            break;
    }
}


function reminder($conn, $path, $for)
{
  $id        = $_GET['id'];
  $view      = "reminder";
  $eventname = selectall($conn, "event", "id = $id");
  $event     = $eventname[0]['name'];
  $address   = $eventname[0]['address'];
  $banner    = $eventname[0]['banner_img'];
  $maps      = $eventname[0]['maps'];

  if (isset($_GET['testing'])) {
      $test = true;
  } else {
      $test = false;
  }

  switch ($for) {
    case '1d':
      $select = selectall($conn, 'pax', "event_id = $id AND email_sent=1");
      foreach ($select as $key => $value) {
        $status = sendmail($value['email'], $value['id'], $value['name'], $event, $address, $maps, $path, $view, null, $test, "true", $id);
        if ($status == true) {
          $paxid = $value['id'];
          update($conn, 'pax', 'email_sent = 2', $paxid);
        }
      }
      break;

    case '0d':
      $select = selectall($conn, 'pax', "event_id = $id AND email_sent=2");
      foreach ($select as $key => $value) {
        $status = sendmail($value['email'], $value['id'], $value['name'], $event, $address, $maps, $path, $view, null, $test, "true", $id);
        if ($status == true) {
          $paxid = $value['id'];
          update($conn, 'pax', 'email_sent = 3', $paxid);
        }
      }
      break;

    default:
      break;
  }
  header("Location: ../attend/index.php?id=$id");
}

function bulkmail($conn, $path)
{
  $id   = $_GET['id'];
  $view = $_GET['view'];

  if (isset($_GET['testing'])) {
      $test = true;
  } else {
      $test = false;
  }

  $select = selectall($conn, 'pax', "event_id = $id");

  foreach ($select as $key => $value) {
    $status = sendmail($value['email'], $value['id'], $value['name'], $path, $view, null, $test);
    if ($status == true) {
      $paxid = $value['id'];
      update($conn, 'pax', 'email_sent = 1', $paxid);
    }
  }
  header("Location: ../index.php?id=$id");
}

function feedback($conn, $path)
{
  $id   = $_GET['id'];
  $view = 'feedback';

  if (isset($_GET['testing'])) {
      $test = true;
  } else {
      $test = false;
  }

  $sel_event = selectall($conn, 'event', "id = $id");
  $survey    = $sel_event[0]['survey_form'];
  $select    = selectall($conn, 'pax', "event_id = $id AND status = 1");

  foreach ($select as $key => $value) {
    sendmail($value['email'], $value['id'], $value['name'], $path, $view, $survey, $test);
  }
  header("Location: ../index.php?id=$id");
}



//MAIN FUNCTION FOR SENDING EMAIL
function sendmail($email, $id, $name, $event = NULL, $address = NULL, $maps = NULL, $path, $view, $survey, $test, $direct = NULL, $event_id,$flyer,$banner)
{
  //bulk mail sending
  $last_id = $id;
  $path    = "$path$last_id";

  if ($direct != NULL) {
      $template = "template/$view.php";
  } else {
      $template = "../template/$view.php";
  }

  // $link = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=$path"; //not maintained any more
  $link = "https://qrcode.tec-it.com/API/QRCode?data=$path";	//new qr code generate from api
  $img  = file_get_contents($link);
  $mail = new PHPMailer(); // defaults to using php "mail()"

  $mail->IsSMTP(); // telling the class to use SMTP
  //mail trap setting

  $mail->SMTPAuth      = true;// enable SMTP authentication
  $mail->SMTPKeepAlive = true;
  $mail->SMTPSecure    = 'tls';// SMTP connection will not close after each email sent

  if ($test == true) {
    $mail->Host     = 'sandbox.smtp.mailtrap.io';
    $mail->Port     = 2525;                    // set the SMTP port for the GMAIL server
    $mail->Username = 'c04d2c045298c0';
    $mail->Password = 'd6646fb6a3a068';  // SMTP account password
    $mail->SetFrom('list@mydomain.com', 'List manager');
  } else {
       //$mail->Host = 'smtp.gmail.com';
      //$mail->Port          = 587;                    // set the SMTP port for the GMAIL server
      //$mail->Username = 'muirsyad2399@gmail.com';
      //$mail->Password = 'bpfs ffyh qigv nhgd';
      //$mail->SetFrom('muirsyad2399@gmail.com.com', 'Synergy Sdn Bhd (info)');

      //PRODUCTION USE SEND MAIL
  	$mail->Host     = "smtp.gmail.com";  // specify main and backup server
  	$mail->SMTPAuth = true;     // turn on SMTP authentication
  	$mail->Username = "info@synergy-group.com.my";  // SMTP username
  	$mail->Password = "drqhpyxmzocrlstx"; // SMTP password
  	$mail->SetFrom('info@synergy-group.com.my', '(info)Synergy ESCO (Malaysia) Sdn Bhd');
  	$mail->Port = 25;
  }


  if ($event != NULL) {
      $subject = "$event";
  } else {
      // $subject = 'Green Initiative Program @ Le Méridien, Petaling Jaya 4 Nov 2023';
      $subject = 'Green Initiative Program';
  }

  $sub = '=?UTF-8?B?' . base64_encode($subject) . '?=';
  $mail->Subject = $sub;
  // echo $subject;
  // die("Le Méridien, ");
  $mail->addAddress($email, $name);
  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
  $rep = "<div style=\"margin-left: auto; margin-right: auto;\"> <img width='300' height='300' src=\"$link\" alt=\"qr_code\"> </div>";
  // $repthumb = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/images/upload/thumbevent$event_id.png\"";
  $repthumb = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/$flyer\"";
  // $repbanner = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/images/upload/event$event_id.jpg\">";\"";
  $repbanner = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/$banner\">";
  //$repthumb = "<img width=\"60%\" src=\"../images/upload/thumbevent$event_id.png\"";


  $rep2 = $name;
  // $rep4 = "<a href=\"$maps\"> $repbanner</a>  <br>";
  $rep4 = "<a href=\"$maps\"> $repbanner</a>  <br>";

  if ($address != NULL) {
    $oriven = "<a href=\"$maps\"> $repthumb</a>  <br>";
    $rep3   = $oriven;
  }

  $repbreak = "Breakfast and lunch will be provided. We look forward to seeing you there. ";
  $gform    = $survey;
  $body     = file_get_contents("$template");

  //REPLACE CONTENT FROM TEMPLATE USING preg_replace
  if ($view == "reminder") {
    $body = preg_replace('/<NAME>/i', $rep2, $body);
    $body = preg_replace('/<ADDRESS>/i', $rep3, $body);
    // $body = preg_replace('/<THUMB>/i', $repthumb, $body);
    $body = preg_replace('/<BREAK>/i', $repbreak, $body);
    // $body = preg_replace('/#/i', $gform, $body);
    $mail->MsgHTML($body);
    if ($view != 'feedback') {
      if ($direct != NULL) {
          $mail->addAttachment("../images/upload/thumbevent$event_id.png", "Green Initiative Program.png");
      } else {
          $mail->addAttachment("../images/upload/thumbevent$event_id.png", "Green Initiative Program.png");
      }
    }
  } else {

    $body      = file_get_contents("$template");
    $repthumb  = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/images/upload/thumbevent$event_id.png\"";
    $repbanner = "<img width=\"60%\" src=\"https://www.synergy-group.com.my/event-program/$banner\">";

    $body = preg_replace('/Replace/i', $rep, $body);
    $body = preg_replace('/<NAME>/i', $rep2, $body);
    $body = preg_replace('/<ADDRESS>/i', $rep3, $body);
    $body = preg_replace('/<BANNER>/i', $repbanner, $body);
    // $body = preg_replace('/<THUMB>/i', $repthumb, $body);
    $body = preg_replace('/<BREAK>/i', $repbreak, $body);
    // $body = preg_replace('/#/i', $gform, $body);
    $mail->MsgHTML($body);

    if ($view != 'feedback') {
      $mail->AddStringAttachment($img, "QRCode.png");
      if ($direct != NULL) {
          $mail->addAttachment("$flyer", "Green Initiative Program.png");
      } else {
          $mail->addAttachment("../$flyer", "Green Initiative Program.png");
      }
    }
  }

  if (!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
      return $send = false;
  } else {
      echo "Message sent!";
      return $send = true;
  }
}
