<?php
require '../../includes/database.php';
if (!session_id()) @session_start();
require '../../vendor/plasticbrain/php-flash-messages/src/FlashMessages.php';
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
    $valid=false;
    $updated=false;
    if(isset($_GET['hash']) && isset($_GET['email'])){
        $error=false;
        $hash=$_GET['hash'];
        $email=$_GET['email'];
        $sql="SELECT hash FROM verifications WHERE hash='$hash'";
        $verification=$databaseConnection->query($sql);
        if($verification){
            while($row=$verification->fetch_assoc()){
                $valid=true;
                $updatesql="UPDATE users SET verified = 1 WHERE email='$email'";
                if($databaseConnection->query($updatesql)) $updated=true;
                else $error=true;
                $deleteVerificationSQL="DELETE FROM verifications WHERE hash='$hash'";
                if($databaseConnection->query($deleteVerificationSQL)) $msg->success("Email successfully verified! You can close this tab.");
                else $msg->error("An error has occured. Please try again. If the problem keeps coming back, contact admin.");
            }
        }
    } else $msg->error("An error has occured. Please try again. If the problem keeps coming back, contact admin.");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require "../../includes/header.php"; ?>
    <title>Verify user</title>
</head>
<body>
    <?php 
        if ($valid==false && $error==false) $msg->error("Email already verified");
        $msg->display(); 
    ?>
    <?php require "../../includes/footer.php"; ?>
</body>
</html>