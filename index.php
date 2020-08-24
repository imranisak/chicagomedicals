<?php 
session_start();
if($_SESSION['isLoggedIn']) $isLoggedIn=true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chicago Medicals</title>
</head>
<body>
    <h1>Chicago Medicals</h1>
    <?php if(!$isLoggedIn) {?><a href="/pages/users/register.php"><h3>Register</h3></a><?php } ?>
    <?php if($isLoggedIn){?>
        <h3>Hello, <?php echo  $_SESSION['name'];?></h3>
        <h3><a href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/logout.php">Log out</a></h3>
    <?php } else { ?><h3><a href="<?php $_SERVER['DOCUMENT_ROOT']?>/pages/users/login.php">Log in</a></h3><?php } ?>
</body>
</html>