<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
require "../../includes/recaptcha.php";
require "../../includes/sessionInfo.php";
//require "../../includes/galleria.php";
if(isset($_GET['ID'])) $clinicID=$_GET['ID'];
else $msg->error("No ID provided!", "/pages/clinics");
$SQLseletClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
$clinic=$databaseConnection->query($SQLseletClinic);
if(!$clinic) $msg->error("There has been an internal error. Please, try again, or contact admin!", "/pages/clinics");
if(mysqli_num_rows($clinic)==0) $msg->error("No clinics found.", "/pages/clinics");
$clinic=$clinic->fetch_assoc();
if(!$clinic['approved']) $msg->error("Clinic has not yet been approved!", "/pages/clinics");
$clinicName=$clinic['name'];
$owner=$clinic['owner'];
$address=$clinic['address'];
$zip=$clinic['zip'];
$clinicMail=$clinic['email'];
$website=$clinic['website'];
$services=$clinic['services'];
$facebook=$clinic['facebook'];
$twitter=$clinic['twitter'];
$instagram=$clinic['instagram'];
$images=unserialize($clinic['images']);
$clinicIsApproved=$clinic['approved'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $clinicName ?></title>
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
    <?php if($msg->hasMessages()) $msg->display(); ?>
	<h1><?php echo $clinicName ?></h1>
	<p>Name: <?php echo $clinicName ?></p>
	<p>Owner: <?php echo $owner ?></p>
	<p>Address: <?php echo $address ?></p>
	<p>ZIP: <?php echo $zip ?></p>
	<p>Email: <?php echo $clinicMail ?></p>
	<p>Website: <?php echo "<a href='".$website."' target='_blank'>".$website."</a>" ?></p>
	<p>Services: <?php echo join(',', array_map('ucfirst', explode(',', $services))); ?></p>
	<p>Facebook: <?php echo "<a href='".$facebook."' target='_blank'>".$facebook."</a>" ?></p>
	<p>Instagram: <?php echo "<a href='".$instagram."' target='_blank'>".$instagram."</a>" ?></p>
	<p>Twitter: <?php echo "<a href='".$twitter."' target='_blank'>".$twitter."</a>" ?></p>
	<!--Images and gallery-->
<?php
    //$images=substr($images, 1, -1);
    //$images=explode(", ", $images);?>
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
<?php require "../../includes/reviewBox.php"; ?>
<?php
$databaseConnection->close();
require "../../includes/footer.php"
?>
</body>
</html>