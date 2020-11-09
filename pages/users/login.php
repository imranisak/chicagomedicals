<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 }
if(isset($_SESSION["isLoggedIn"])){
    if($_SESSION["isLoggedIn"]==true) header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <?php include "../../includes/header.php";?>
</head>
<body>
<?php include "../../includes/navbar.php";?>
    <form action="post/login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit">
    </form>
    <a href="resetPassword.php">Forgot password?</a>
    <?php include "../../includes/footer.php";?>
</body>
</html>