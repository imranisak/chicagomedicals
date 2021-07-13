<?php
//reCaptcha stuffs
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/users/register.php';//Used if reCaptcha fails.
include '../../../includes/recaptcha.php';
require '../../../classes/user.php';
require '../../../includes/database.php';
require '../../../includes/functions.php';
require '../../../includes/flashMessages.php';
require '../../../includes/fileUpload.php';
if($_SESSION['csrf_token']==$_POST['token']) {
//Checks if email is used
    if (isset($_POST['email']) && $_POST['email'] != "") {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $msg->error("Invalid mail!", "/pages/users/register.php");
        $email = filter_var($email);
        $email = strtolower($email);
    } else {
        $msg->error('Email missing', "/pages/users/register.php");
    }
    $checkMailInDatabaseSQL = "SELECT email FROM users WHERE email='$email'";
    $emailsInDatabase = $databaseConnection->query($checkMailInDatabaseSQL);
    if ($emailsInDatabase->num_rows != 0) $msg->error('Email in use.', '../register.php');
    /////////////////////////
    if (isset($_POST['name']) && $_POST['name'] != "") $name = filterInput($_POST['name']);
    else $msg->error('Name missing', "/pages/users/register.php");

    if (isset($_POST['surname']) && $_POST['surname'] != "") $surname = filterInput($_POST['surname']);
    else $msg->error('Surname missing', "/pages/users/register.php");

    if (isset($_POST['password_1']) && $_POST['password_1'] != "" && isset($_POST['password_2']) && $_POST['password_2'] != "") {
        $password1 = $_POST['password_1'];
        $password2 = $_POST['password_2'];
        if ($password1 == $password2) $password = filterInput(password_hash($password1, PASSWORD_DEFAULT));
        else $msg->error('Passwords do not match!', "/pages/users/register.php");
    } else $msg->error('Password missing');
    if (!is_uploaded_file($_FILES['file']['tmp_name'])) $profilePicture = "/media/pictures/profilepicture.jpg";
    else $profilePicture = proccessFile($msg, "image");
    /*echo $_SERVER['DOCUMENT_ROOT'].$profilePicture;
    die();*/
    if ($msg->hasMessages($msg::ERROR)) $msg->error("An error has happened. Please try again", "/pages/users/register.php");
    //////////////////////////
    $date = Date("Y-m-d");
    $user = new user($name, $surname, $email, $password, $date, $profilePicture);
    $user->addToDatabase($databaseConnection);
    if (!$user->saved) $msg->error("An error has occurred. Please try again. If the problem keeps coming back, please contact the admin!", "../register.php");
    $user->createVerification($databaseConnection); //Also send the verification email
    if ($user->saved) $msg->success("Successfully registered! Please check your mail for the verification link!", "/");
    $databaseConnection->close();
}
else {
    $databaseConnection->close();
    $msg->error("Invalid token.", "/pages/users/register.php");
}