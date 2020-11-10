<?php
//reCaptcha stuffs
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/users/register.php';//Used if reCaptcha fails.
include '../../../includes/recaptcha.php';
require '../../../classes/user.php';
require '../../../includes/database.php';
require '../../../includes/functions.php';
require '../../../includes/flashMessages.php';
//Checks if email is used
$checkMailInDatabaseSQL="SELECT email FROM users WHERE email='$email'";
$emailsInDatabase=$databaseConnection->query($checkMailInDatabaseSQL);
if($emailsInDatabase->num_rows!=0) $msg->error('Email in use.', '../register.php');

if(isset($_POST['name']) && $_POST['name']!="") $name=filterInput($_POST['name']);
else $msg->error('Name missing');

if(isset($_POST['surname']) && $_POST['surname']!="") $surname=filterInput($_POST['surname']);
else $msg->error('Surname missing');

if(isset($_POST['email']) && $_POST['email']!=""){
    $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $msg->error("Invalid mail!");
    $email=filterInput($email);
}
else {
    $msg->error('Email missling');
}
if(isset($_POST['password_1']) && $_POST['password_1']!="" && isset($_POST['password_2']) && $_POST['password_2']!=""){
    $password1=$_POST['password_1'];
    $password2=$_POST['password_2'];
    if($password1==$password2) $password=filterInput(password_hash($password1, PASSWORD_DEFAULT));
else $msg->error('Passwords do not match!');
}
else $msg->error('Password missing');;//Add an error when data is missing
//////////////////////////
$date=Date("Y-m-d");
$user=new user($name, $surname, $email, $password, $date);
$user->createVerification($databaseConnection); //Also send the verification email
$user->addToDatabase($databaseConnection);
if ($user->saved) $msg->success("Succesfully registered! Please check your mail for the verification link!", "../../../index.php");
else $msg->error("An error has occured. Please try again. If the problem keeps coming back, please contact the admin!", "../register.php");
$databaseConnection->close();