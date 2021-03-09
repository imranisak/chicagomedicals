<?php
require "../../../includes/database.php";
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
function errorRedirect($message, $databaseConnection, $msg){
    $databaseConnection->close();
    if($isAdmin) $msg->error($message, "/admin/clinics");
    else $msg->error($message, "/pages/clinics");
}
//Various checks
$clinicID=filter_var($_POST['clinicID'], FILTER_SANITIZE_NUMBER_INT);
if(!$clinicID) errorRedirect("Invalid ID", $databaseConnection, $msg);
if(!$isLoggedIn) errorRedirect("Must be logged in", $databaseConnection, $msg);
$SQLloadClinicForCheck="SELECT ownerID FROM clinics WHERE ID='$clinicID'";
$clinicOwnerID=$databaseConnection->query($SQLloadClinicForCheck);
if(!$clinicOwnerID) errorRedirect("Error checking ownership", $databaseConnection, $msg);
$clinicOwnerID=$clinicOwnerID->fetch_assoc();
$clinicOwnerID=$clinicOwnerID['ownerID'];
if(!$isAdmin) if($clinicOwnerID!=$id) errorRedirect("You can only delete your own clinic...duh!", $databaseConnection, $msg);
if(!isset($_POST['token']) || !isset($_POST['clinicID'])) errorRedirect("Token or ID missing", $databaseConnection, $msg);
if($_POST['token']!=$_SESSION['csrf_token']) errorRedirect("Invalid token", $databaseConnection, $msg);
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
foreach ($clinicImages as $image) if(file_exists($_SERVER['DOCUMENT_ROOT'].$image)) unlink($_SERVER['DOCUMENT_ROOT'].$image);
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
    $SQLcheckIfUserHasMoreClinic="SELECT * FROM clinics WHERE ownerID='$clinicOwnerID'";
    $clinics=$databaseConnection->query($SQLcheckIfUserHasMoreClinic);
    if(!$clinics) $msg->error("Clinic deleted, error checking if user has more clinics.");
    if($clinics->num_rows==0){
        $SQLsetUserHasNoClinics="UPDATE users SET hasClinic = '0' WHERE ID='$clinicOwnerID'";
        $ownerStatusUpdate=$databaseConnection->query($SQLsetUserHasNoClinics);
        if(!$ownerStatusUpdate) $msg->error("Clinic deleted, but error updating user info!");
    }
    $databaseConnection->close();
    if($isAdmin) $msg->success("Clinic removed!", "/admin/clinics");
    else $msg->success("Clinic removed!", '/pages/clinics');
}