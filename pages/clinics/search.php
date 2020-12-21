<?php
require "../../includes/database.php";
//require "../../includes/database.php";
//require "../../includes/database.php";
if(isset($_GET['services'])) $services=$_GET['services'];
else $services=false;
if($services){
	$services=filter_var($services, FILTER_SANITIZE_STRING);
	$services=strtolower($services);
	$services=ucfirst($services);
	echo $services;
	$SQLselectClinics="SELECT * FROM clinics WHERE services like '%$services%'";
	$clinics=$databaseConnection->query($SQLselectClinics);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<?php require '../../includes/header.php'; ?>
</head>
<body>
	<?php require '../../includes/navbar.php'; ?>
	<?php 
	foreach($clinics as $clinic){
		echo "<h3>".$clinic['name']."</h3><br><p>Owner:".$clinic['owner']."<br>".
		"Services: ".$clinic['services']."<hr style='width:100%'>";
	}
	 ?>
	<?php require '../../includes/footer.php';
	$databaseConnection->close();?>
</body>
</html>