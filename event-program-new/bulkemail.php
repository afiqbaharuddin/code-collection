<?php 

require_once('phpmailer_5.2.0/class.phpmailer.php');
require('path.php');
require('db_conn.php');
//process to send email
$id = $_GET['id'];
$action = $_GET['action'];
$select = selectall($conn,'pax',"event_id = $id");

foreach ($select as $key => $value) {
    
    sendmail($value['email'],$value['id'],$path,$action);
    
}
header("Location: index.php?id=$id");


    


    function sendmail($email,$id,$path,$action){
        $last_id = $id;
        $path = "$path$last_id";
        $template = "template/$action.php";
       
    
        $link = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=$path";
        $img = file_get_contents($link);
        $mail= new PHPMailer(); // defaults to using php "mail()"
    
    
    
        $mail->IsSMTP(); // telling the class to use SMTP
        //mail trap setting
    
        $mail->SMTPAuth      = true;                  // enable SMTP authentication
        $mail->SMTPKeepAlive = true;  
        $mail->SMTPSecure = 'tls';                // SMTP connection will not close after each email sent
    
    
        // $mail->Host = 'sandbox.smtp.mailtrap.io';
        // $mail->Port          = 2525;                    // set the SMTP port for the GMAIL server
        // $mail->Username = '93d5eddfb4ee2a';
        // $mail->Password = '4b84fa909a5975';      // SMTP account password
        // $mail->SetFrom('list@mydomain.com', 'List manager');
    
        //gmail setting
        $mail->Host = 'smtp.gmail.com';
        $mail->Port          = 587;                    // set the SMTP port for the GMAIL server
        $mail->Username = 'muirsyad2399@gmail.com';
        $mail->Password = 'bpfs ffyh qigv nhgd'; 
        $mail->SetFrom('muirsyad2399@gmail.com.com', 'List manager');
    
        $mail->Subject       = "QRCODE Event";
        $mail->addAddress($email,"First Last");
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $rep = "<div style=\"margin-left: auto; margin-right: auto;\"> <img src=\"$link\" alt=\"qr_code\"> </div>";
        
        $body= file_get_contents("$template");
        // $body= preg_replace('/\\\\/','', $body);
        $body = preg_replace('/Replace/i',$rep,$body);
        $mail->MsgHTML($body);
        $mail->AddStringAttachment($img, "QRCode.png");
    
    
        if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        echo "Message sent!";
       
    
        }
    }

    

?>
