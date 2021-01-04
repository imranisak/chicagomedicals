<?php
if (!session_id()) @session_start();
if($_POST['token']==$_SESSION['csrf_token']){
	require "../../../includes/database.php";
	require "../../../includes/flashMessages.php";
	require "../../../includes/sessionInfo.php";
	if(!$isAdmin) die("Must be admin!");
	$tag=strtolower($_POST['tag']);
	$SQLinsertTag="INSERT INTO tags (tag) VALUES ('$tag')";
	if($databaseConnection->query($SQLinsertTag)){
		$databaseConnection->close();
		echo true;
	} 
	else{
		$databaseConnection->close();
		echo $databaseConnection->error;
	} 
} else echo "na-a";