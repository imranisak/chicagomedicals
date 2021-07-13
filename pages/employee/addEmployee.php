<?php
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/clinics/addClinic.php';
//require "../../includes/recaptcha.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
require "../../includes/database.php";
require "../../includes/token.php";
if(!$isLoggedIn) $msg->warning("You must be logged in to add an employee.", "/");
if(!$isVerified) $msg->warning("You must first verify your profile before adding an employee.", "/");
if(!$hasClinics) $msg->warning("You cannot add an employee if you do not have a verified clinic.", "/");
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add an employee</title>
    <?php require "../../includes/header.php"?>
</head>
<body>
<?php require "../../includes/navbar.php";
if($msg->hasMessages()) $msg->display();
?>

<h3>Add an employee to your clinic!</h3>



<?php require "../../includes/footer.php"?>
</body>
</html>
