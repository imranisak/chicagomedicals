<?php
require '../../../includes/database.php';
require '../../../includes/flashMessages.php';
require '../../../includes/sessionInfo.php';
require '../../../includes/recaptcha.php';
require '../../../includes/fileUpload.php';
require '../../../includes/functions.php';
//Bunch of checks - if the clinic exists, if the user is the owner, and if the CSFR token is OK, and loads the clinc
if(!$isLoggedIn) $msg->error("You must be logged in to edit a clinic!", "/pages/clinics");
if(isset($_POST['clinicID'])) $clinicID=$_POST['clinicID'];
else $msg->error("An error has occured!", "/pages/clinics");
$SQLloadClinic="SELECT * FROM clinics WHERE ID=$clinicID";
$clinic=$databaseConnection->query($SQLloadClinic);
if(mysqli_num_rows($clinic)==0) $msg->error("Clinic does not exist!", "/pages/clinics");
$clinic=mysqli_fetch_assoc($clinic);
if($clinic['ownerID']!=$id) $msg->error("You can only edit your own clinic!", "/pages/clinics");
if(!isset($_POST['token'])) $msg->error("Token missing", "/pages/clinics");
if($_POST['token']!=$_SESSION['csrf_token']) $msg->error("Invalid token!", "/pages/clinics");
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
    if(isset($_POST['imagesToRemove'])) $imagesToRemove=filter_var($_POST['imagesToRemove'], FILTER_SANITIZE_STRING);
    else $imagesToRemove=false;
    //Gets uploaded already uploaded, storem them in an array
    $imagesAlreadyUploaded=unserialize($clinic['images']);
    //$imagesAlreadyUploaded=substr($imagesAlreadyUploaded, 1, -1);
    //$imagesAlreadyUploaded=str_replace('"', "", $imagesAlreadyUploaded);
    //$imagesAlreadyUploaded=explode(", ", $imagesAlreadyUploaded);
    //Takes the images that need to be removed - if any - and removes them from the array
    //die(var_dump($imagesAlreadyUploaded));
    var_dump($imagesAlreadyUploaded);
    if($imagesToRemove){
    	$imagesToRemove=str_replace('"', "", $imagesToRemove);
    	$imagesToRemove=explode(",", $imagesToRemove);
    	//$counter=0;
    	//foreach($imagesAlreadyUploaded as $imageUploaded){
            foreach($imagesToRemove as $imageToRemove){
                echo $imageToRemove;
                $indexOfImageToRemove=array_search($imageToRemove, $imagesAlreadyUploaded);
                if($indexOfImageToRemove>=0){
                    //echo $indexOfImageToRemove."<br>";
                    unlink($_SERVER["DOCUMENT_ROOT"].$imagesAlreadyUploaded[$indexOfImageToRemove]);
                    unset($imagesAlreadyUploaded[$indexOfImageToRemove]);
                } 
            }
    	//}
    	//foreach($imagesToRemove as $imageToRemove) unlink($_SERVER["DOCUMENT_ROOT"].$imageToRemove);
    }
    //die();
    //Takes the uploaded images and stores them in the array - if any are uploade
    $uploadedImages=multipleFileUpload($msg, "image");
    if($uploadedImages) foreach($uploadedImages as $image) array_push($imagesAlreadyUploaded, $image);
    $imagesAlreadyUploaded=serialize($imagesAlreadyUploaded);
	$SQLupdateClinic="UPDATE clinics SET name='$clinicName', email='$clinicEmail', address='$clinicAddress', zip='$clinicZIPcode', services='$clinicServices', website='$clinicWebsite', images='$imagesAlreadyUploaded', facebook='$clinicFacebook', instagram='$clinicInstagram', twitter='$clinicTwitter' WHERE ID=$clinicID ";
	if($msg->hasErrors()) $msg->error("An error has occured!", "/pages/clinics/editclinic.php?ID='$clinicID'");
	if($databaseConnection->query($SQLupdateClinic)){
		$databaseConnection->close();
		$msg->success("Clinic updated.", "/pages/clinics/editclinic.php?ID='$clinicID'");
	}
	else{
		//$databaseConnection->close();
		$msg->error("An error has occured. Please, try again. If the issue keeps coming back, please contact admin! - ".$databaseConnection->error, "/pages/clinics/editclinic.php?ID='$clinicID'");
	}
}