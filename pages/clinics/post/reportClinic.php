<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/sendMail.php";
if ($_POST['token']!=$_SESSION['csrf_token']){
    echo "token";
    exit;
}
if (isset($_POST['reportReason'])) $reason=filter_var($_POST['reportReason'], FILTER_SANITIZE_STRING);
else{
    echo "input";
    exit;
}
if(isset($_POST['ID'])) $propertyID=filter_var($_POST['ID'], FILTER_SANITIZE_NUMBER_INT);
else{
    echo "propertyID";
    exit;
}
if(isset($_POST['type'])) $type=filter_var($_POST['type'], FILTER_SANITIZE_STRING);
else{
    echo "type";
    exit;
}
require "../../../includes/database.php";
if($isLoggedIn) $reporterID=$id;
else $reporterID="";
$SQLsaveReport="INSERT INTO reports (reporterID, type, reason, propertyID) 
VALUES('$reporterID', '$type', '$reason', '$propertyID')";
$saveReport=$databaseConnection->query($SQLsaveReport);
$SQLselectAdmins="SELECT * FROM users WHERE role = 'admin'";
$admins=$databaseConnection->query($SQLselectAdmins);
if($saveReport) echo "true";//<---Don't ask
else echo "sql";
if($admins) {
    foreach ($admins as $admin) {
        try{
            if (!isset($mail)) $mail = new PHPMailer(true);
            $mail->addAddress($admin['email']);     // Add a recipient
            $mail->Subject = 'User report!';
            $mail->Body = "New content has been reported. Please check the admin panel for more details";
            $mail->send();
        }
        catch (Exception $e){
            echo "send mail error";
        }

    }
}
else echo "send mail error";
$databaseConnection->close();
