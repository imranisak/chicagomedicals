<?php
require '../../includes/flashMessages.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <?php include '../../includes/header.php';?>
</head>
<body>
    <?php include '../../includes/navbar.php';?>
    <form action="post/resetPassword.php" method="post">
        <p>Forgot your password? No problem. Enter your account email, and you will get an password reset link.</p>
        <input type="email" placeholder="E-mail" name="email">
        <button type="submit">Reset password</button>
    </form>
    <?php if (isset($msg)) {
        $msg->display();
    } ?>
    <!-- TODO INCLUDE RECAPTCHA -->
    <?php include '../../includes/footer.php';?>
</body>
</html>