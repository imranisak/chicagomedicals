<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
if(!$isLoggedIn) die();
if(!isset($_POST['employeeID']) || !isset($_POST['clinicID'])) die();
$clinicID=filter_var($_POST['clinicID'], FILTER_SANITIZE_NUMBER_INT);
$employeeID=filter_var($_POST['employeeID'], FILTER_SANITIZE_NUMBER_INT);
require "../../../includes/database.php";
$SQLcheckClinicOwner="SELECT ownerID from clinics WHERE ownerID='$id' AND ID='$clinicID'";
$checkClinicOwner=$databaseConnection->query($SQLcheckClinicOwner);
if(!$checkClinicOwner){
    $databaseConnection->close();
    echo "Error checking clinic owner!";
}
$checkClinicOwner=$checkClinicOwner->fetch_row();
$clinicOwner=$checkClinicOwner['ownerID'];
if($clinicOwner!=$id){
    $databaseConnection->close();
    echo "You can only edit your own employees!";
    die();
}
else echo "You are the owner!";