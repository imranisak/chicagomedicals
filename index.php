<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
if(isset($_SESSION['isLoggedIn'])) $isLoggedIn=true;
else $isLoggedIn=false;
require 'vendor/plasticbrain/php-flash-messages/src/FlashMessages.php';
$msg = new \Plasticbrain\FlashMessages\FlashMessages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chicago Medicals</title>
    <?php require 'includes/header.php'; ?>
</head>
<body>
    <?php if (isset($msg)) $msg->display(); ?>
    <h1>Chicago Medicals</h1>
    <?php if(!$isLoggedIn) {?><a href="/pages/users/register.php"><h3>Register</h3></a><?php } ?>
    <?php if($isLoggedIn){?>
        <h3>Hello, <?php echo  $_SESSION['name'];?></h3>
        <h3><a href="/pages/users/post/logout.php">Log out</a></h3>
    <?php } else { ?><h3><a href="/pages/users/login.php">Log in</a></h3><?php } ?>
    <?php require 'includes/footer.php'; ?>
</body>
</html>