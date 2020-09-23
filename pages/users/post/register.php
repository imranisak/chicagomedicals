<?php
//reCaptcha stuffs
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
  }
  if(!$captcha){
    echo '<h2>Please check the the captcha form.</h2>';
    exit;
  }
  $secretKey = "6LfzjcAZAAAAAOmiOiVvOmB_70I7wBpOi94xt7Xb";
  $ip = $_SERVER['REMOTE_ADDR'];
  $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
  $responseKeys = json_decode($response,true);
  if(intval($responseKeys["success"]) !== 1) {
    return '<h1>lol, no</h1>';
  } else {
//////////////////
require $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php';
require $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';

if(isset($_POST['name']) && $_POST['name']!="") $name=$_POST['name'];
else exit("Name missing");//Add an error when data is missing

if(isset($_POST['surname']) && $_POST['surname']!="") $surname=$_POST['surname'];
else exit("Surname missing");//Add an error when data is missing

if(isset($_POST['email']) && $_POST['email']!=""){
  $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) exit("Invalid mail");
  $email=filterInput($email);
} 
else {
  exit("Email missing");//Add an error when data is missing
}
if(isset($_POST['password_1']) && $_POST['password_1']!="" && isset($_POST['password_2']) && $_POST['password_2']!=""){
    $password1=$_POST['password_1'];
    $password2=$_POST['password_2'];
    if($password1==$password2) $password=password_hash($password1, PASSWORD_DEFAULT);
    else exit("Passwords do not match");
}
else "lol wut";//Add an error when data is missing
//Checks if email is used
$checkMailInDatabaseSQL="SELECT email FROM users WHERE email='$email'";
$emailsInDatabase=$databaseConnection->query($checkMailInDatabaseSQL);
if($emailsInDatabase->num_rows!=0)
{
  echo "Email already in use!";
  exit();
}
//////////////////////////
$date=Date("Y-m-d");
$user=new user($name, $surname, $email, $password, $date);
//$user->addToDatabase($databaseConnection);
//$user->createVerification($databaseConnection);
$databaseConnection->close();
}