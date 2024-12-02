	<?php
		
		require_once('phpmailer_5.2.0/class.phpmailer.php');
		require('path.php');
		require('db_conn.php');
		
		
			
			
			$paxlist = selectall($conn, 'pax', "event_id = $_GET[eventid]");
			
			/*  echo "<pre>";
			print_r($paxlist);
			echo "</pre>"; 
			 */
			
			foreach($paxlist as $pax){
			/* 	echo "<pre>";
			//print_r($pax['name']);
			print_r($pax['id']);
			print_r($pax['email']);
			echo "</pre>"; */
			
			
			$link = "https://qrcode.tec-it.com/API/QRCode?data=https://www.synergy-group.com.my/event-program/register.php?id=".$pax['id'];	//new qr code generate from api
			$img = file_get_contents($link);
			

			// $body ="<strong>Hello ".$pax['name']."</strong> <br><br> Thank you for your registration to our Green Initiative Program. We are very happy to have you as our guest. <br><br> Please find the attached QR code for your registration at the hotel. Below are the event details: <br><br>Thank you for your registration. We have sent you an email confirmation with QR code. Please present the QR code during on-site registration. <br>Pendaftaran anda telah diterima. Kami telah menghantar email pengesahan beserta kod QR. <br> Sila kemukakan kod QR semasa pendaftaran di majlis nanti. <br><br> <div style=\"margin-left: auto; margin-right: auto;\"> <img width='300' height='300' src=\"$link\" alt=\"qr_code\"> </div><br/><br/> <strong>This is an auto generated email. Please do not reply to this email. </strong> <br><br>";
			$body ="<strong>Hello ".$pax['name']."</strong> <br> Thank you for your registration to our Green Initiative Program. We are very happy to have you as our guest. <br> Please find the attached QR code for your registration at the hotel. Below are the event details: <br>Thank you for your registration. We have sent you an email confirmation with QR code. Please present the QR code during on-site registration. <br><br>Pendaftaran anda telah diterima. Kami telah menghantar email pengesahan beserta kod QR. <br> Sila kemukakan kod QR semasa pendaftaran di majlis nanti. <br><br> <div style=\"margin-left: auto; margin-right: auto;\"> <img width='300' height='300' src=\"$link\" alt=\"qr_code\"> </div><br/><br/> <strong>This is an auto generated email. Please do not reply to this email. </strong> <br><br>";
			
			$body .="Sincerely,<br/>Info@Synergy ESCO(M) Sdn Bhd<br/><br/>This is a computer generated email. Please do not reply.";

			//require("phpmailer_5.2.0/class.phpmailer.php");

				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Host = "smtp.gmail.com";  // specify main and backup server
				$mail->SMTPAuth = true;     // turn on SMTP authentication
				$mail->Username = "info@synergy-group.com.my";  // SMTP username
				//$mail->Username = "admin.intrack@synergy-group.com.my";  // SMTP username
				//$mail->Password = "bvkfedqnvrccpjey"; // SMTP password 
				$mail->Password = "drqhpyxmzocrlstx"; // SMTP password 
				$mail->SMTPSecure = 'tls';
				$mail->From = "info@synergy-group.com.my";
				$mail->FromName = "Info@Synergy ESCO(M) Sdn Bhd";
				$mail->Port = 25;
				// below we want to set the email address we will be sending our email to.
				/* $mail->AddAddress("info@synergy-group.com.my","Green Initiative Registration"); */
				$mail->AddAddress($pax['email'],"Green Initiative Registration");
				///$mail->AddAddress("ainhassan16@gmail.com","Green Initiative Registration");
				$mail->WordWrap = 200;
				$mail->IsHTML(true); 
				
				$mail->Subject = "Green Initiative Registration"; 
				$mail->AddStringAttachment($img, "QRCode.png");
				
				$mail->Body    = $body;
				$mail->AltBody = "Synergy Esco (Malaysia) Sdn Bhd";
				

				if(!$mail->Send())
				{
				   echo "Message could not be sent. <p>";
				   echo "Mailer Error: " . $mail->ErrorInfo;
				   exit;
				}
				 else{
					//die;
					/*echo "<script> window.location.href = 'green-initiative.html'; </script>"; */
					echo "<script> history.back(); </script>";
				} 
			}
				

?>