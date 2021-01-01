<?php
// recaptcha div - <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
/*if(!isset($msg)) include 'flashMessages.php';
$_location=$_SESSION['goBack'];
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}
if(!$captcha) $msg->error("Please solve the recaptcha!", $_location);
$secretKey = "6LfzjcAZAAAAAOmiOiVvOmB_70I7wBpOi94xt7Xb";
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response,true);
if(intval($responseKeys["success"]) !== 1) $msg->error("Invalid recaptcha, plese try again.", $_location);*/