<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/fileUpload.php";
require "../../../includes/flashMessages.php";

if(!$isLoggedIn) $msg->error("You must be logged in!", "/");
if(!$hasPremium) $msg->error("You must have premium!", "/");
if(isset($_POST['picture'])){
    $picture=filter_var($_POST['picture'], FILTER_SANITIZE_STRING);
    $picture=$_SERVER['DOCUMENT_ROOT'].$picture;
    if(file_exists($picture)) {
        if(unlink($picture)) echo "Success";
    }
}