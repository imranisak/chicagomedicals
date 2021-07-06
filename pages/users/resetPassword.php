<?php
require '../../includes/flashMessages.php';
require '../../includes/token.php';
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/users/resetPassword.php';
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
        <p>Forgot your password? Need to update your password?<br>
            No problem. Enter your account email, and you will get a password reset link.</p>
        <input type="email" placeholder="E-mail" name="email">
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token']; ?>" required>
        <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
        <button type="submit" class="btn btn-primary">Reset password</button>
    </form>
    <?php if (isset($msg)) {
        $msg->display();
    } ?>
    <?php include '../../includes/footer.php';?>
</body>
</html>