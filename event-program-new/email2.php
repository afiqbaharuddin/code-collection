<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<?php

require_once('phpmailer_5.2.0/class.phpmailer.php');

require('path.php');
$last_id = $conn->insert_id;
$path = "$path$last_id";

$link = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=$path";
$img = file_get_contents($link);

$mail             = new PHPMailer(); // defaults to using php "mail()"



$mail->IsSMTP(); // telling the class to use SMTP
//mail trap setting

$mail->SMTPAuth      = true;                  // enable SMTP authentication
$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent


$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->Port          = 2525;                    // set the SMTP port for the GMAIL server
$mail->Username = '93d5eddfb4ee2a';
$mail->Password = '4b84fa909a5975';      // SMTP account password
$mail->SetFrom('list@mydomain.com', 'List manager');

//gmail setting


$mail->Subject       = "QRCODE Event";

$mail->addAddress("muirsyad2399@gmail.com","First Last");





$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$body = "<div style=\"margin-left: auto; margin-right: auto;\"> <img src=\"$link\" alt=\"qr_code\"> </div>";
$mail->MsgHTML($body);
//$mail->addAttachment($link,"QRcode.png");  
$mail->AddStringAttachment($img, "QRCode.png");


if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
header("Location: index.php?id=$id");

}

?>

</body>
</html>
