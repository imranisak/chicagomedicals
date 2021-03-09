<?php
require "../../../includes/database.php";
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
function errorRedirect($message, $databaseConnection, $msg){
    $databaseConnection->close();
    $msg->error($message, "/admin/clinics");
}
//Various checks
if(!$isAdmin) errorRedirect("Must be admin", $databaseConnection, $msg);
if(!isset($_POST['token']) || !isset($_POST['clinicID'])) errorRedirect("Token or ID missing", $databaseConnection, $msg);
if($_POST['token']!=$_SESSION['csrf_token']) errorRedirect("Invalid token", $databaseConnection, $msg);
$clinicID=filter_var($_POST['clinicID'], FILTER_SANITIZE_NUMBER_INT);
if(!$clinicID) errorRedirect("Invalid ID", $databaseConnection, $msg);
//Deletes reviews
$SQLdeleteReviews="DELETE FROM reviews WHERE clinicID='$clinicID'";
$deletedReviews=$databaseConnection->query($SQLdeleteReviews);
if(!$deletedReviews) errorRedirect("Error deleting reviews!", $databaseConnection, $msg);
//Loads clinic
$SQlloadClinic="SELECT * FROM clinics WHERE ID='$clinicID'";
$clinic=$databaseConnection->query($SQlloadClinic);
if(!$clinic) errorRedirect("Error loading clinic", $databaseConnection, $msg);
//Loads images, then deletes them
$clinic=$clinic->fetch_assoc();
$clinicImages=unserialize($clinic['images']);
foreach ($clinicImages as $image) if(file_exists($image)) unlink($image);
//Removes clinic from reports
$SQLdeleteFromReports="DELETE FROM reports WHERE propertyID='$clinicID' AND type='clinic'";
$deletedReports=$databaseConnection->query($SQLdeleteFromReports);
if(!$deletedReports) errorRedirect("Error deleting reports", $databaseConnection, $msg);
//Removed PayPal subscription! (to be added)


//Deletes the clinic itself
$SQLdeleteClinic="DELETE FROM clinics WHERE id='$clinicID'";
$deletedClinic=$databaseConnection->query($SQLdeleteClinic);
if(!$deletedClinic) errorRedirect("Error deleting clinic", $databaseConnection, $msg);
else{
    $databaseConnection->close();
    $msg->success("Clinic removed!", "/admin/clinics");
}