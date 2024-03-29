<?php
require "../../../includes/database.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/fileUpload.php";
require "../../../includes/recaptcha.php";
require "../../../includes/functions.php";
require "../../../includes/sessionInfo.php";
require "../../../classes/clinic.php";

if(!$isVerified) $msg->warning("You must first verify your profile before adding a clinic.", "/");
if($_SESSION['csrf_token']!=$_POST['token']) $msg->error('Invalid token.', '/');
if(!$isLoggedIn) $msg->error("You must be logged in to submit a clinic!", "/");
if(!$hasPremium || $hasPremium=="0"){
    $SQLloadUserClinics="SELECT * FROM clinics WHERE ownerID='$id'";
    $numberOfClinics=$databaseConnection->query($SQLloadUserClinics);
    if(!$numberOfClinics){
        $databaseConnection->close();
        $msg->error("Error loading user's clinics.", "/");
    }
    $numberOfClinics=$numberOfClinics->num_rows;
    if($numberOfClinics>=1){
        $databaseConnection->close();
        $msg->warning("Free users can have only one clinic. <a href='/pages/users/editProfile.php?ID=$id'>Upgrade to premium?</a>", "/");
    }
}
$clinicOwner=$name." ".$surname;
$clinicOwnerID=$id;
if(isset($_POST['submit'])){
    //Clinic name
    if(isset($_POST['clinicName'])) $clinicName=filter_var($_POST['clinicName'], FILTER_SANITIZE_STRING);
    else $msg->error("Clinic must have a name!");
    //Clinic address
    if(isset($_POST['clinicAddress'])) $clinicAddress=filter_var($_POST['clinicAddress'], FILTER_SANITIZE_STRING);
    else $msg->error("Clinic must have an address!");
    //Clinic email
    if(isset($_POST['clinicEmail'])) $clinicEmail=filter_var($_POST['clinicEmail'], FILTER_SANITIZE_EMAIL);
    else $msg->error("Clinic must have a valid email!");
    //Clinic ZIP code
    if(isset($_POST['zip'])) $clinicZIPcode=filter_var($_POST['zip'], FILTER_SANITIZE_NUMBER_INT);
    else $msg->error("Clinic must have a ZIP code!");
    //Clinic services (tags)
    if(isset($_POST['services'])) $clinicServices=filter_var($_POST['services'], FILTER_SANITIZE_STRING);
    else $msg->error("You must enter at least one service (for example, dentist!");
    //Website
    if(isset($_POST['clinicWebsite'])) $clinicWebsite=filter_var($_POST['clinicWebsite'], FILTER_SANITIZE_URL);
    else $clinicWebsite="";
    //Facebook link
    if(isset($_POST['facebook'])) $clinicFacebook=filter_var($_POST['facebook'], FILTER_SANITIZE_URL);
    else $clinicFacebook="";
    //Instagram link
    if(isset($_POST['instagram'])) $clinicInstagram=filter_var($_POST['instagram'], FILTER_SANITIZE_URL);
    else $clinicInstagram="";
    //Twitter link
    if(isset($_POST['twitter'])) $clinicTwitter=filter_var($_POST['twitter'], FILTER_SANITIZE_URL);
    else $clinicTwitter="";
    //Images
    if(!$hasPremium){
        $numberOfImages=count(array_filter($_FILES['file']['name']));
        if($numberOfImages>10){
            $databaseConnection->close();
            $msg->warning("Free users can upload 10 images maximum. <a href='/pages/users/editProfile.php'>Upgrade to premium?</a>", "../addClinic.php");
        }
        else{
            if($numberOfImages>25){
                $databaseConnection->close();
                $msg->warning("You are allowed to add 25 images to your clinic maximum.", "../addClinic.php");
            }
        }
    }
    if(!multipleFileUpload($msg, "image")) $msg->error("Must select at least one image, or invalid image name!");
    else $images=multipleFileUpload($msg, "image");
    $images=serialize($images);//Converts the image array to a something so it can be saved to the database
    if($msg->hasErrors()) $msg->error("An error has occurred!", '../addClinic'); //If there are errors, go back
    //Loads the services, then saves the clinic
    $clinicServices=strtolower($clinicServices);
    $clinic=new clinic($clinicName, $clinicOwner, $clinicOwnerID, $clinicEmail, $clinicAddress, $clinicZIPcode, $clinicServices, $clinicWebsite, $images, $clinicFacebook, $clinicTwitter, $clinicInstagram);
    $clinicAdded=$clinic->addToDatabase($databaseConnection);
    if($clinicAdded===true){//Why'd I put this here like this?
        saveEmployees($databaseConnection, $hasPremium, $msg);
        //And this bit here send mails!
        if(!isset($mail)) $mail=new PHPMailer(true);
        $clinic->sendNotificationToOwner($email, $name, $mail);//$ownerEmail, $owner, $mail
        $clinic->sendNotificationToAdmin("info@imranisak.com", $mail);//TODO - figure out how to load ALL admin mails ;-;
        $databaseConnection->close();
        $msg->success("Your clinic has been submitted for review. We will let you know by mail if it has been approved - usually within 24h.", "../addClinic.php");
    }
    else{
        $databaseConnection->close();
        $msg->error("An error has occurred. Please try again. If the error persists, please report this error to admin - ".$databaseConnection->error, "../addClinic.php");
    }
}
else{
    $databaseConnection->close();
    $msg->error("What are you doing?", '/');
}


