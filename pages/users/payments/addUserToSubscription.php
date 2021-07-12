<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
//Checks
if($_SESSION['csrf_token']!=$_POST['token']) echo "Invalid token!";
if(!$isLoggedIn) $msg->error("Nope!", "/");
if(!isset($_POST['userID']) || $_POST['userID']=="" || !isset($_POST['subscriptionID']) || $_POST['subscriptionID']=="") $msg->error("Invalid input - saving user to subscription!",'/pages/users/editProfile.php?ID=$id');
$userID=filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
$subscriptionID=filter_var($_POST['subscriptionID'], FILTER_SANITIZE_STRING);
//Saves the data
require "../../../includes/database.php";
$SQLaddUserToSubscription="UPDATE subscriptions SET userID='$userID' WHERE subscriptionID='$subscriptionID'";
$addUserToSubscription=$databaseConnection->query($SQLaddUserToSubscription);
if(!$addUserToSubscription){
    $databaseConnection->close();
    echo "Error saving user to subscription!";
    http_response_code(500);
    die();
}
$SQLupdateUser="UPDATE users SET hasPremium = 1 WHERE ID='$userID'";
$updateUser=$databaseConnection->query($SQLupdateUser);
if(!$updateUser){
    $databaseConnection->close();
    echo "Error updating user!";
    http_response_code(500);
    die();
}
else $_SESSION['hasPremium']=1;
$databaseConnection->close();
//Send a mail to the user
$ownerEmail=$_SESSION['email'];
if(!isset($mail)) $mail=new PHPMailer(true);
$mail->addAddress($ownerEmail);
$mail->Subject='Premium activated on Chicago Medicals!';
$mail->Body=file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/premiumActivated.html");
$mail->send();