<?php
require "../../../includes/database.php";
require "../../../includes/functions.php";
require '../../../includes/flashMessages.php';

if($_SESSION['csrf_token']==$_POST['token']) {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $email = filterInput($email);
    } else $msg->error("Please enter email!", '../login.php');
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        $password = filterInput($password);
    } else $msg->error("Please enter password!", '../login.php');
    $sql = "SELECT id, name, surname, password, email, profilePicture, role FROM users WHERE email='$email' LIMIT 1";
    $user = $databaseConnection->query($sql);
    if ($user->num_rows > 0) {
        while ($row = $user->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['surname'] = $row['surname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['profilePicture'] = $row['profilePicture'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['verified'] = $row['verified'];
                $location = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
                $databaseConnection->close();
                header("Location: /index.php");
            } else $msg->error("Incorect password!", "../login.php");
        }
    } else  $msg->warning("Not a valid email! <a href='/pages/users/register.php'>Plz register</a>", "../login.php");
}
else{
    $databaseConnection->close();
    $msg->error("Invalid token.", "/");
}