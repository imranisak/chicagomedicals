<?php
require "../../includes/recaptcha.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
if($isLoggedIn){
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add clinic</title>
    <?php require "../../includes/header.php"?>
</head>
<body>
<?php require "../../includes/navbar.php" ?>
<h1>Add a clinic</h1>
<form action="post/addClinic.php" method="post">
    <input type="text" name="clinicName" placeholder="Clinic name" required>
    <input type="text" name="clinicAddress" placeholder="Clinic Address" required>
</form>
<?php require "../../includes/footer.php" ?>
</body>
</html>


<?php
}else $msg->error("You must be logged in to access this page.", "/");
?>

