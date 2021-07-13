<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/fileUpload.php";
require "../../../includes/flashMessages.php";

if(!$isLoggedIn) $msg->error("You must be logged in!", "/");
if(!$hasPremium) $msg->error("You must have premium!", "/");

if(isset($_FILES["file"]["name"])) echo proccessFile($msg, "image");
else echo "/media/pictures/profilepicture.jpg";