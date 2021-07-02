<?php
require '../../../includes/database.php';
require '../../../includes/sessionInfo.php';
require '../../../includes/sendMail.php';
require '../../../includes/flashMessages.php';
if(!$isAdmin){
	$databaseConnection->close();	
	$msg->error("lol no", "/");
} 

if(isset($_GET['ID'])) $clinicID=$_GET['ID'];
else if(isset($_POST['ID'])) $clinicID=$_POST['ID'];

//Gets the ID of the owner, since it is needed to get his mail
$SQLgetOwnerID="SELECT ownerID FROM clinics WHERE ID = $clinicID";
$ownerID=$databaseConnection->query($SQLgetOwnerID);
$row=$ownerID->fetch_assoc();
$ownerID=$row['ownerID'];
//Gets the Email of the owner
$SQLgetOwnerEmail="SELECT email FROM users WHERE ID=$ownerID";
$ownerEmail=$databaseConnection->query($SQLgetOwnerEmail);
$row=$ownerEmail->fetch_assoc();
$ownerEmail=$row['email'];

if($_GET['action']=='approve'){
	if($_GET['token']!=$_SESSION['csrf_token']){
	$databaseConnection->close();
	$msg->error("no", '/');
	}
	$SQLapproveClinic="UPDATE clinics SET approved = '1' WHERE ID = $clinicID";
	if($databaseConnection->query($SQLapproveClinic)) {
		//Sends the mail
		if(!isset($mail)) $mail=new PHPMailer(true);
		$mail->addAddress($ownerEmail);
		$mail->Subject='Clinic approved!';
		$mail->Body=file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/clinicApprovedNotification.html");
		//$mail->send();
		//Updates user info
        $SQLupdateUserInfo="UPDATE users SET hasClinic = 1 WHERE ID='$ownerID'";
        if(!$databaseConnection->query($SQLupdateUserInfo)){
            $databaseConnection->close();
            $msg->error("Clinic approved, but error updating user info.", "/admin/clinics/clinic?ID=$clinicID");
        }
		$databaseConnection->close();
		$msg->success("Clinics successfully approved!", "/admin/clinics/");
	}
	else $msg->error("An error has occurred. Please try again. GET", "/admin/clinics/clinic?ID=$clinicID");
} elseif ($_POST['action']=="deny") {
	if($_SESSION['csrf_token']!=$_POST['token']){
		$databaseConnection->close();
		$msg->error("no", "/");
	}
	else{
		$reason=$_POST['reasonForDenial'];
		$clinicID=$_POST['ID'];
		$SQLgetClinicImages="SELECT images FROM clinics WHERE ID=$clinicID";
		$clinicImages=$databaseConnection->query($SQLgetClinicImages);
		if(!$clinicImages) $msg->error("Error loading clinic images!");
		$clinicImages=mysqli_fetch_array($clinicImages);
		$clinicImages=$clinicImages[0];
		$clinicImages=unserialize($clinicImages);
		//Deletes the uploaded images
		foreach($clinicImages as $clinicImage){
			if(!unlink($_SERVER['DOCUMENT_ROOT'].$clinicImage)) {
				$msg->error("Error deleting clinic images");
			}
		}
		$SQLremoveClinic="DELETE FROM clinics WHERE ID = $clinicID";
		if($msg->hasErrors()) $msg->error("An error has occurred!", "/admin/clinics/index.php");
		if($databaseConnection->query($SQLremoveClinic)){
			$mailContent=file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/clinicDenyNotification.php")." ".$reason;
			if(!isset($mail)) $mail=new PHPMailer(true);
			$mail->addAddress($ownerEmail);
			$mail->Subject='Your clinic was not approved on Chicago Medicals!';
			$mail->Body=$mailContent;
			//$mail->send();
			$databaseConnection->close();
			$msg->success("Clinic has been declined, and removed from database.", "/admin/clinics/");
		}
		else{
			$databaseConnection->close();
			$msg->error("An error has occurred, please try again!", "/admin/clinics/clinic?ID=$clinicID");
		}
	}
}