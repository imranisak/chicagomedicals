<?php
require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php';
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
                if($databaseConnection->query($deleteVerificationSQL)){
                    //idk
                }
                else{
                    //$databaseConnection->error;
                    $error=true;
                }
            }
        }
    } else{
        $error=true;
    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify user</title>
</head>
<body>
    <?php if($error){?>
    <p>An error has occured ;-;</p>
    <p>Plz contact admin, he fix, no problem.</p>
    <?php }?>
    <?php if(!$valid){?>
    <p>Invalid or expired verification link</p>
    <?php }?>
    <?php if($updated){ ?>
    <h3>User email verified.</h3>
    <?php }?>
</body>
</html>