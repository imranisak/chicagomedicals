<?php
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;
if (!session_id()) @session_start();

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
class user{
    public $name, $surname, $email, $password, $dateAdded, $saved=false;
    function __construct($nameInput, $surnameInput, $emailInput, $passwordInput, $dateAddedInput){
        $this->name=$nameInput;
        $this->surname=$surnameInput;
        $this->email=$emailInput;
        $this->password=$passwordInput;
        $this->dateAdded=$dateAddedInput;
    }
    function getName(){
        return $this->name;
    }
    function addToDatabase($connection){
        $sql="INSERT INTO  users (name, surname, email, password, dateAdded) VALUES ('$this->name', '$this->surname', '$this->email', '$this->password' ,'$this->dateAdded')";
        if($connection->query($sql)===TRUE) $this->saved=true;
        else $this->saved=false;
    }

    function createVerification($connection){
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
        $hash=md5(generateRandomString());
        $sql="INSERT INTO verifications (hash, userEmail) VALUES ('$hash', '$this->email')";
        if($connection->query($sql)==TRUE){
            echo $hash;
            $nameAndSurname=$this->name." ".$this->surname;
            $mail=new PHPMailer(true);
            try{
                $mail->SMTPDebug = 0;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.mailtrap.io';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = '35640481810ae6';                     // SMTP username
                $mail->Password   = '5105188f7636b5';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
                //Recipients
                $mail->setFrom('info@chicagomedicals.com', 'Chicago Medicals');
                $mail->addAddress($this->email, $nameAndSurname);     // Add a recipient
                $mail->addReplyTo('info@chicagomedicals.com', 'Chicago Medicals');
    
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'User verification';
                $mail->Body    = "<a href='http://chichagomedicals/pages/users/verify.php/?hash=$hash&email=$this->email' target='_blank'>Verify email here</a>";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
                $mail->send();
                echo "Mail sent!";
            }
            catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        else{
            echo $connection->error;
            echo "Could not add verification!";
        }
    }
}