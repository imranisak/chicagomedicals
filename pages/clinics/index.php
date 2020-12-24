<?php
require "../../includes/database.php";
//require "../../includes/database.php";
//require "../../includes/database.php";
if(isset($_GET['services'])) $services=$_GET['services'];
else {
	$SQLselectClinics="SELECT * FROM clinics";
	$services=false;
}
if($services){
	$services=filter_var($services, FILTER_SANITIZE_STRING);
	$services=strtolower($services);
	$services=ucfirst($services);
	echo $services;
	$SQLselectClinics="SELECT * FROM clinics WHERE services like '%$services%'";
}
$clinics=$databaseConnection->query($SQLselectClinics);
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
		if($clinic['approved']){
			$featuredImage=$clinic['images'];
			$featuredImage=explode(",", $featuredImage);
			$featuredImage=$featuredImage[0];
			$featuredImage=str_replace(['[', ']', '"'], "", $featuredImage);
			echo "
			<div class='container'>
			    <div class='row clinicBox'>
			        <div class='col-xs-3'><img src='".$featuredImage."' class='featuredImage'></div>
			        <div class='col-xs-9'>
			        	<h3 class='clinicNameInBox'>".$clinic['name']."</h3>
			        	<p class='ownerInBox'>Owner: ".$clinic['owner']."</p>
			        	<p class='adrressInBox'>Address: ".$clinic['address']."</p>
			        	<a href='/pages/clinics/clinic.php?ID=".$clinic['ID']."'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
			        </div>
			    </div>
			</div>
			";
			/*echo "<h3>".$clinic['name']."</h3><br><p>Owner:".$clinic['owner']."<br>".
			"Services: ".$clinic['services']."<hr style='width:100%'>";*/
		}
	}
	 ?>
	<?php require '../../includes/footer.php';
	$databaseConnection->close();?>
</body>
</html>