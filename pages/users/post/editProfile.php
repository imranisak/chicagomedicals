<?php
$_SESSION['goBack']='/pages/users/editProfile.php';//Used if reCaptcha fails.
//include '../../../includes/recaptcha.php';
require '../../../includes/database.php';
require '../../../includes/flashMessages.php';
require '../../../includes/sessionInfo.php';
require '../../../includes/functions.php';
require '../../../includes/fileUpload.php';
//exit(var_dump($id));
$redirectLink="/pages/users/editProfile.php?ID".$id;
if(!isset($_POST['profileToEditID'])) $msg->error("No user ID");
else $profileToEdit=$_POST['profileToEditID'];
if($id!=$profileToEdit){
    $databaseConnection->close();
    $msg->error("You can only edit your own profile.", "/");
}
if($_POST['token']!=$_SESSION['csrf_token']){
    $databaseConnection->close();
    $msg->error("Invalid token.", "/");
}
$SQLloadUser="SELECT * FROM users WHERE ID=$id";
$user=$databaseConnection->query($SQLloadUser);
if(!$user){
    $databaseConnection->close();
    $msg->error("Error loading your profile.", $redirectLink);
}
$user=$user->fetch_assoc();
$currentProfilePicture=$user['profilePicture'];
if(isset($_POST['name'])) $newname=filter_var($_POST['name'], FILTER_SANITIZE_STRING);
else $msg->error("Name cannot be empty");
if(isset($_POST['surname'])) $newsurname=filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
else $msg->error("Surname cannot be empty.");
if(isset($_POST['email'])) $newemail=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
else $msg->error("Email cannot be empty");
if (!is_uploaded_file($_FILES['file']['tmp_name'])) $newProfilePicture = $currentProfilePicture;
else $newProfilePicture = proccessFile($msg, "image");
if($msg->hasErrors()) $msg->error("An error has occurred.", "/pages/users/editProfile.php?ID=".$id);
$SQLupdateUser="UPDATE users SET name='$newname', surname='$newsurname', email='$newemail', profilePicture='$newProfilePicture' WHERE ID='$id'";
$updateUser=$databaseConnection->query($SQLupdateUser);
if(!$updateUser) $msg->error("An error has occurred while updating your profile. Please, try again.", $redirectLink);
$SQLloadUserClinics="SELECT * FROM clinics WHERE ownerID = '$id'";
$userClinics=$databaseConnection->query($SQLloadUserClinics);
if(!$userClinics){
    $databaseConnection->close();
    $msg->error("Profile updated, but an error happened while updating info of your clinics.", $redirectLink);
}
$newClinicOwner=$newname." ".$newsurname;
foreach ($userClinics as $clinic){
    $SQLupdateClinicOwner="UPDATE clinics SET owner='$newClinicOwner' WHERE ownerID='$id'";
    $update=$databaseConnection->query($SQLupdateClinicOwner);
    if(!$update) $msg->error("Error updating clinics info.", $redirectLink);
}
$msg->success("Profile successfully updated!", $redirectLink);