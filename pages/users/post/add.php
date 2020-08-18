<?php

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
    return '<h2>lol, no</h2>';
  } else {

require $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php';

if(isset($_POST['name'])) $name=$_POST['name'];
else "lol wut";//Add an error when data is missing

if(isset($_POST['surname'])) $surname=$_POST['surname'];
else "lol wut";//Add an error when data is missing

if(isset($_POST['email'])) $email=$_POST['email'];
else "lol wut";//Add an error when data is missing

if(isset($_POST['password_1']) && isset($_POST['password_2'])){
    $password1=$_POST['password_1'];
    $password2=$_POST['password_2'];
    if($password1==$password2) $password=password_hash($password1, PASSWORD_DEFAULT);
    else echo "plz check passwords";
    die();
}
else "lol wut";//Add an error when data is missing
$date=Date("Y-m-d");
$user=new user($name, $surname, $email, $password, $date);
$user->addToDatabase($databaseConnection);
$databaseConnection->close();
}