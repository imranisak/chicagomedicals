<?php
include "../../../includes/recaptcha.php";
require "../../../includes/database.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/functions.php";
if(isset($_POST['email'])){
    $email=$_POST['email'];
    $checkMailInDatabaseSQL="SELECT email FROM users WHERE email='$email'";
    $emailsInDatabase=$databaseConnection->query($checkMailInDatabaseSQL);
    if($emailsInDatabase->num_rows==0) $msg->error('No email in database or invalid email!', '../resetPassword.php');
    if($emailsInDatabase->num_rows!=0){
        //If the user has already made a request, but not validated it, remove the old validation and create a new one.
        $sql="DELETE FROM passwordreset WHERE email='$email'";
        if(!$databaseConnection->query($sql)) $msg->error("An error has occurred, please try again", "../resetPassword.php");
    }
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    $hash=md5(generateRandomString());
    $sql="INSERT INTO passwordreset (hash, email) VALUES ('$hash', '$email')";
    if (isset($databaseConnection)) {
        if($databaseConnection->query($sql)) {
            if(!isset($mail)) $mail=new PHPMailer(true);
            $mail->addAddress($email);
            $mail->Subject="Password reset";
            $mail->Body="<a href='http://chichagomedicals/pages/users/enterNewPassword.php/?hash=$hash&email=$email'>Click here to reset your password on Chicago Medicals!</a>";
            $mail->send();
            $msg->success("Please check your mail for the password reset link.","../resetPassword");
        } else if (isset($msg)) $msg->error("An error has happened. Please try again.", "../resetPassword");
    }
}
else if (isset($msg)) $msg->error("An error has happened. Please try again.", "../resetPassword");