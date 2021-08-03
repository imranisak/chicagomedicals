<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
//Initial checks
if(!$isLoggedIn) $msg->error("Must be logged in. What are you doing?", "/");
if(!$hasPremium) $msg->error("Must have premium to add, edit and delete employees!", "/");
if(!isset($_POST['token'])) $msg->error("Token missing!", "/");
if($_POST['token']!=$_SESSION['csrf_token']) $msg->error("Invalid token!", "/");
require "../../../includes/database.php";
//Ownership check
$clinicID=filter_var($_POST['editEmployeeClinicID'], FILTER_SANITIZE_NUMBER_INT);
$employeeID=filter_var($_POST['editEmployeeID'], FILTER_SANITIZE_NUMBER_INT);
$SQLloadClinic="SELECT ID from clinics WHERE ownerID='$id' AND ID='$clinicID'";
$clinic=$databaseConnection->query($SQLloadClinic);
if(!$clinic){
    $databaseConnection->close();
    $msg->error("Error checking employees ownership", "/");
}
if(!$clinic->num_rows || $clinic->num_rows<=0){
    $databaseConnection->close();
    echo "aaa";
    $msg->error("STOP! You can only edit your own employees!", "/");
}
echo $clinicID." ".$employeeID;