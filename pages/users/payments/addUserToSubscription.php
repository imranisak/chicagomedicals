<?php
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
if(!$isLoggedIn) $msg->error("Nope!", "/");
if(!isset($_POST['userID']) || $_POST['userID']=="" || !isset($_POST['subscriptionID']) || $_POST['subscriptionID']=="") $msg->error("Invalid input - saving user to subscription!",'/pages/users/editProfile.php?ID=$id');
$userID=filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
$subscriptionID=filter_var($_POST['subscriptionID'], FILTER_SANITIZE_STRING);


require "../../../includes/database.php";
