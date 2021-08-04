<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
require "../../../includes/fileUpload.php";
//Initial checks
if(!$isLoggedIn) $msg->error("Must be logged in. What are you doing?", "/");
if(!$hasPremium) $msg->error("Must have premium to add, edit and delete employees!", "/");
if(!isset($_POST['token'])) $msg->error("Token missing!", "/");
if($_POST['token']!=$_SESSION['csrf_token']) $msg->error("Invalid token!", "/");
require "../../../includes/database.php";
//Ownership check of clinic
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
    $msg->error("STOP! You can only edit your own employees!", "/");
}
//Checks if employee belongs to clinic
$SQLloadEmployeeClinic="SELECT ID, picture from employees WHERE clinicID='$clinicID'";//Also loads picture, so I don't have to send another query down the line.
$employeeClinic=$databaseConnection->query($SQLloadEmployeeClinic);
if(!$employeeClinic){
    echo $databaseConnection->error;
    $databaseConnection->close();
    $msg->error("Error checking if the employee belongs to clinic!", "/");
}
$employeeClinic=$employeeClinic->fetch_assoc();
if(!$employeeClinic){
    $databaseConnection->close();
    $msg->error("An error happened.", "/pages/clinics/editClinic.php?ID=".$clinicID);
}
if(!$employeeClinic|| !in_array($employeeID, $employeeClinic)){
    $databaseConnection->close();
    $msg->error("STOP! You can only edit your own employees!");
}
//Loads submitted employee data
if(isset($_POST['editEmployeeName']) && $_POST['editEmployeeName']!="") $employeeName=filter_var($_POST['editEmployeeName'], FILTER_SANITIZE_STRING);
else{
    $databaseConnection->close();
    $msg->error("Your employee must at least have a name, right?", "/pages/clinics/editClinic.php?ID=".$clinicID);
}
if(isset($_POST['editEmployeeSurname'])) $employeeSurname=filter_var($_POST['editEmployeeSurname'], FILTER_SANITIZE_STRING);
else $employeeSurname="";
if(isset($_POST['editEmployeeTitle'])) $employeeTitle=filter_var($_POST['editEmployeeTitle'], FILTER_SANITIZE_STRING);
else $employeeTitle="";
if(isset($_POST['editEmployeeBio'])) $employeeBio=filter_var($_POST['editEmployeeBio'], FILTER_SANITIZE_STRING);
else $employeeBio="";
if(is_uploaded_file($_FILES['file']['tmp_name'])) $employeeImage=proccessFile($msg, "image");
else $employeeImage=$employeeClinic['picture'];
die($employeeImage);

//Saves employee