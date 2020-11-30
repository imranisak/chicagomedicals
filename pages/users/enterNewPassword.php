<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enter new password</title>
    <?php require "../../includes/flashMessages.php"; ?>
    <?php require "../../includes/header.php"; ?>
    <?php require "../../includes/database.php"; ?>
    <?php require "../../includes/token.php"; ?>
    <?php
    $email=$_GET['email'];
    $hash=$_GET['hash'];
    $SQLselectFromPasswordsReset="SELECT email, hash FROM passwordReset WHERE email='$email' AND hash='$hash' LIMIT 1";
    $resetPasswordRow=$databaseConnection->query($SQLselectFromPasswordsReset);
    if($resetPasswordRow->num_rows==0){
        $databaseConnection->close();
        $msg->error("Password reset link already used.", "/pages/users/resetPassword.php");
    }
    ?>
</head>
<body>
<?php require "../../includes/navbar.php"; ?>
<h1>Reset password</h1>
<?php
    if(!isset($_GET['hash'])) $msg->error("Invalid request", '/pages/users/resetPassword.php');
?>
<form action="/pages/users/post/enterNewPassword.php" method="post">
    <label>Enter new password<br>
        <input type="password" name="password1" placeholder="Enter password" required>
    </label><br><br>
    <label>Confirm your new password<br>
        <input type="password" name="password2" placeholder="Confirm password" required>
    </label><br><br>
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
    <input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" required>
    <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'] ?>" required>
    <button type="submit">Submit</button>
</form>

<?php require "../../includes/footer.php"; ?>
</body>
</html>