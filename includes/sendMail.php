<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'].'/vendor/phpmailer/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'].'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/vendor/phpmailer/phpmailer/src/SMTP.php';

//require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$mail=new PHPMailer(true);
$mail->SMTPDebug = 0;                      // Enable verbose debug output
$mail->isSMTP();                                            // Send using SMTP
$mail->Host       = 'smtp.mailtrap.io';                    // Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = '35640481810ae6';                     // SMTP username
$mail->Password   = '5105188f7636b5';                               // SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
$mail->setFrom('info@chicagomedicals.com', 'Chicago Medicals');
$mail->addReplyTo('info@chicagomedicals.com', 'Chicago Medicals');
$mail->isHTML(true);
/*
if(!isset($mail)) $mail=new PHPMailer(true);
$mail->addAddress($ownerEmail, $owner);     // Add a recipient
$mail->Subject = 'Clinic submitted!';
$mail->Body    = file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/newClinicOwnerNotification.html");
$mail->send();
*/