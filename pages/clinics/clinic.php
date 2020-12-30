<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
//require "../../includes/galleria.php";
if(isset($_GET['ID'])) $clinicID=$_GET['ID'];
else $msg->error("No ID provided!", "/pages/clinics");

$SQLseletClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
$clinic=$databaseConnection->query($SQLseletClinic);
if(!$clinic) $msg->error("There has been an internal error. Please, try again, or contact admin!", "/pages/clinics");
$clinic=$clinic->fetch_assoc();
$name=$clinic['name'];
$owner=$clinic['owner'];
$address=$clinic['address'];
$zip=$clinic['zip'];
$email=$clinic['email'];
$website=$clinic['website'];
$services=$clinic['services'];
$facebook=$clinic['facebook'];
$twitter=$clinic['twitter'];
$instagram=$clinic['instagram'];
$images=$clinic['images'];
$clinicIsApproved=$clinic['approved'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $name ?></title>
	<?php include "../../includes/header.php" ?>
	<link type="text/css" rel="stylesheet" href="/includes/galleria/themes/classic/galleria.classic.css">
<script type="text/javascript" src="/includes/galleria/galleria.js"></script>
<script type="text/javascript" src="/includes/galleria/themes/classic/galleria.classic.js"></script>
<style>
.galleria{
    height:500px;
    width:auto;
}
</style>
</head>
<body>
	<?php include "../../includes/navbar.php" ?>
	<h1>Clinic - <?php echo $name ?></h1>
	<p>Name: <?php echo $name ?></p>
	<p>Owner: <?php echo $owner ?></p>
	<p>Address: <?php echo $address ?></p>
	<p>ZIP: <?php echo $zip ?></p>
	<p>Email: <?php echo $email ?></p>
	<p>Website: <?php echo "<a href='".$website."' target='_blank'>".$website."</a>" ?></p>
	<p>Services: <?php echo $services ?></p>
	<p>Facebook: <?php echo "<a href='".$facebook."' target='_blank'>".$facebook."</a>" ?></p>
	<p>Instagram: <?php echo "<a href='".$instagram."' target='_blank'>".$instagram."</a>" ?></p>
	<p>Twitter: <?php echo "<a href='".$twitter."' target='_blank'>".$twitter."</a>" ?></p>
	<!--Images and gallery-->
<?php
    $images=substr($images, 1, -1);
    $images=explode(", ", $images);?>
    <div class='galleria col-md-5'>
    <?php foreach($images as $image) echo "<img src=".$image.">";?>
    </div>

    <script>
                (function() {
                    Galleria.loadTheme('/includes/galleria/themes/classic/galleria.classic.js');
                    Galleria.run('.galleria');
                }());
    </script>
    <script type="text/javascript">
    $('.galleria').galleria({
    width: auto,
    height: 500
    });
    </script>
<!--End of images-->
<?php
$databaseConnection->close();
require "../../includes/footer.php"
?>
</body>
</html>