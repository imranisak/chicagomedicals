<?php
require "../../../includes/database.php";
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
if(!$isAdmin) $msg->error("lol no", "/");
$tag=ucfirst($_POST['tag']);
$SQLinsertTag="INSERT INTO tags(tag) VALUES ('$tag')";
if($databaseConnection->query($SQLinsertTag)) return true;
else return $databaseConnection->error;

$databaseConnection->close();