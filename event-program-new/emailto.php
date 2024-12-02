<?php


// mail('muirsyad2399@gmail.com', 'Hi leokhoa', 'I like Mail Sender feature.');
require('path.php');
$last_id = $conn->insert_id;
$path = "$path.$last_id";

$link = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=$path";
$destdir = '/images/uploads';
$img = file_get_contents($link);
$namefile = "images/QrCodeEvent.png";
file_put_contents($namefile, $img);
$tempimg = $namefile;
?>

    <?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions

    $phpmailer = new PHPMailer();
    try {
        //Server settings






        // $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '93d5eddfb4ee2a';
        $phpmailer->Password = '4b84fa909a5975';

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        // $mail->isSMTP();                                            //Send using SMTP
        // $mail->Host = 'sandbox.smtp.mailtrap.io';                 //Set the SMTP server to send through
        // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        // $mail->Username = '93d5eddfb4ee2a';
        // $mail->Password = '4b84fa909a5975';                       //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        // $mail->Port = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $phpmailer->setFrom('muirsyad2399@gmail.com', 'Mailer');
        $phpmailer->addAddress($email, $name);     //Add a recipient
        // $phpmailer->addAddress('ellen@example.com');               //Name is optional
        // $phpmailer->addReplyTo('info@example.com', 'Information');
        // $phpmailer->addCC('cc@example.com');
        // $phpmailer->addBCC('bcc@example.com');

        //Attachments
        $phpmailer->addAttachment($tempimg);         //Add attachments
        // $phpmailer->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $phpmailer->isHTML(true);                                  //Set email format to HTML
        $phpmailer->Subject = 'Your Qr code geenrated';
        $phpmailer->Body    = '<div style="margin-left: auto; margin-right: auto;"> <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=http://localhost/purePHP/Event/index.php?id=$last_id" alt="qr_code"> </div>';
        $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $phpmailer->send();
        unlink($tempimg);
        header("Location: index.php");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

?>
