<?php
require "../../../includes/sessionInfo.php";

if(!isset($_POST['clinicID']) || !isset($_POST['employeeID'])) die();
if(!$isLoggedIn) die();
if(!$hasPremium) die();
//Check ownership of clinic
require "../../../includes/database.php";
$clinicID=filter_var($_POST['clinicID'], FILTER_SANITIZE_NUMBER_INT);
$employeeID=filter_var($_POST['employeeID'], FILTER_SANITIZE_NUMBER_INT);
$SQLloadClinic="SELECT ID from clinics WHERE ownerID='$id' AND ID='$clinicID'";
$clinic=$databaseConnection->query($SQLloadClinic);
if(!$clinic){
    $databaseConnection->close();
    echo "Error checking owner info!";
    die();
}
if(!$clinic->num_rows || $clinic->num_rows<=0){
    $databaseConnection->close();
    echo "err_ownership";
    die();
}
//Delete picture - and maybe related files in the future
$SQLlooadEmployeePicture="SELECT picture FROM employees WHERE ID='$employeeID'";
$employeePicture=$databaseConnection->query($SQLlooadEmployeePicture);
if(!$employeePicture){
    $databaseConnection->close();
    echo "err_loadingPicture";
    die();
}
$employeePicture=$employeePicture->fetch_assoc();
$employeePicture['picture'];
//Don't delete the pic if it's the default one, otherwise, everyone will have a bad time
if(!$employeePicture=="/media/pictures/profilepicture.jpg")  if(file_exists($employeePicture)) unlink($_SERVER['DOCUMENT_ROOT'].$employeePicture);

//Delete from DB
$SQLremoveEmployee="DELETE FROM employees WHERE ID='$employeeID'";
$deletedEmployee=$databaseConnection->query($SQLremoveEmployee);
if(!$deletedEmployee) {
    $databaseConnection->close();
    echo "err_deleteUser";
    die();
}
//Return success
echo "success";
die();