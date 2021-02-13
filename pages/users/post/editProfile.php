<?php
$_SESSION['goBack']='/pages/users/editProfile.php';//Used if reCaptcha fails.
//include '../../../includes/recaptcha.php';
require '../../../includes/database.php';
require '../../../includes/flashMessages.php';
require '../../../includes/fileUpload.php';
require '../../../includes/sessionInfo.php';
if($id!=$_POST["profileToEditID"]){
    $databaseConnection->close();
    $msg->error("You can only edit your own profile.", "/");
}
if($_POST['token']!=$_SESSION['csrf_token']){
    $databaseConnection->close();
    $msg->error("Invalid token.", "/");
}
$SQLloadUser="SELECT * FROM userasds WHERE ID=$id";
$user=$databaseConnection->query($SQLloadUser);
if(!$user){
    $databaseConnection->close();
    $msg->error("Error loading your profile.", "/pages/users/editProfile.php?ID".$id);
}