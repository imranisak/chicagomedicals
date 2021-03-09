<?php
class clinic
{
    public $name, $owner, $ownerID, $email, $address, $zip, $services, $website, $images, $facebook, $twitter, $instagram;
    function __construct($nameInput, $ownerInput, $ownerIDInput, $ownerEmailInput, $addressInput, $zipInput, $servicesInput, $websiteInput, $imagesInput, $facebookInput, $twitterInput, $instagramInput){
        $this->name=$nameInput;
        $this->owner=$ownerInput;
        $this->ownerID=$ownerIDInput;
        $this->email=$ownerEmailInput;
        $this->address=$addressInput;
        $this->zip=$zipInput;
        $this->services=$servicesInput;
        $this->website=$websiteInput;
        $this->images=$imagesInput;
        $this->facebook=$facebookInput;
        $this->twitter=$twitterInput;
        $this->instagram=$instagramInput;
    }
    function addToDatabase($connection){
        $SQLinsertClinic="INSERT INTO clinics (name, owner, ownerID, email, address, zip, services, website, images, facebook, twitter, instagram) VALUES('$this->name', '$this->owner', '$this->ownerID', '$this->email', '$this->address', '$this->zip', '$this->services', '$this->website', '$this->images', '$this->facebook', '$this->twitter', '$this->instagram')";
        if($connection->query($SQLinsertClinic)) return true;
        else return $connection->error;
    }
    function sendNotificationToOwner($ownerEmail, $owner, $mail){
        if(!isset($mail)) $mail=new PHPMailer(true);
        $mail->addAddress($ownerEmail, $owner);     // Add a recipient
        $mail->Subject = 'Clinic submitted!';
        $mail->Body    = file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/newClinicOwnerNotification.html");
        $mail->send();

    }
    function sendNotificationToAdmin($adminMail, $mail){
        $mail->addAddress($adminMail, 'Admin');     // Add a recipient
        $mail->Subject = 'New clinic added!';
        $mail->Body    = file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/emails/newClinicAdminNotification.html");
        $mail->send();
    }

}