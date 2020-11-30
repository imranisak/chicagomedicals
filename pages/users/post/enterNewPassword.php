<?php
require "../../../includes/database.php";
require "../../../includes/flashMessages.php";
require "../../../includes/functions.php";
require "../../../includes/sessionInfo.php";
if(isset($_SESSION['isLoggedIn'])) {
    $databaseConnection->close();
    $msg->error("Cannot change passoword while logged in! Please <a href='/pages/users/post/logout.php'>log out.</a>", "/index.php");
}
if($_POST['token']==$_SESSION['csrf_token']) {
    if (isset($_POST['password1'], $_POST['password2'], $_POST['email'], $_POST['hash']) && $_POST['password1'] != "" && $_POST['password2'] != "" && $_POST['email'] != "" && $_POST['hash'] != "") {
        $p1 = filterInput($_POST['password1']);
        $p2 = filterInput($_POST['password2']);
        $email = filterInput($_POST['email']);
        $hash = filterInput($_POST['hash']);
        if ($p1 == $p2) $password = password_hash($p1, PASSWORD_DEFAULT);
        else $msg->error("Passwords do not match. Please reuse the link in your mail.", '../resetPassword.php');
        $SQLselectFromPasswordsReset = "SELECT email, hash FROM passwordReset WHERE email='$email' AND hash='$hash' LIMIT 1";
        $resetPasswordRow = $databaseConnection->query($SQLselectFromPasswordsReset);
        if ($resetPasswordRow && $resetPasswordRow->num_rows != 0) {//Skips everything is user edits the hash or email.
            while ($row = $resetPasswordRow->fetch_assoc()) {
                if ($row['hash'] == $hash && $row['email'] == $email) {
                    $SQLsaveNewPassword = "UPDATE users SET password = '$password' WHERE email = '$email'";
                    $databaseConnection->query($SQLsaveNewPassword);
                    $SQLremovePasswordReset = "DELETE from passwordreset WHERE email='$email' AND hash='$hash'";
                    $databaseConnection->query($SQLremovePasswordReset);
                    $databaseConnection->close();
                    $msg->success("New password successfully set.", "/index.php");
                } else {
                    $databaseConnection->close();
                    $msg->error("Invalid hash or email. Please try again.", "/pages/users/resetPassword.php");
                }
            }
        } else {
            $databaseConnection->close();
            $msg->error("Invalid hash or email. Please try again. You cannot use the same password reset link twice.", "/pages/users/resetPassword.php");
        }
    } else if (isset($msg)) {
        $databaseConnection->close();
        $msg->error("An error has occurred, please try again!", "/pages/users/resetPassword.php");
    }
} else {
    $databaseConnection->close();
    $msg->error("Invalid token!", "/");
}