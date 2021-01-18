<?php
require "../../../includes/database.php";
require "../../../includes/recaptcha.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/sessionInfo.php";

$clinicID=$_POST['clinicID'];
if(!$isLoggedIn) $msg->error("Must be logged in to post a review!", "/");
if(!$isVerified) $msg->error("Your account must be approved to post a review!", "/");
if($_SESSION['csrf_token']!=$_POST['token']) $msg->error("Invalid token", "/pages/clinics/clinic.php?ID=".$clinicID);
if(!isset($_POST['score'])) $msg->error("Score not submited", "/pages/clinics/clinic.php?ID=".$clinicID);
else $score=filter_var($_POST["score"], FILTER_SANITIZE_STRING);
if($score>5 || $score<1) $msg->error("Invalid score!", "/pages/clinics/clinic.php?ID=".$clinicID);
$SQLloadReviewsFromThisUserOnThisClinic="SELECT * FROM reviews WHERE clinicID = $clinicID AND personID=$id";
$reviews=$databaseConnection->query($SQLloadReviewsFromThisUserOnThisClinic);
if(!$reviews) $msg->error("There has been an error processing your review. Please, try again.", "/pages/clinics/clinic.php?ID=".$clinicID);
if(mysqli_num_rows($reviews)>0) $msg->error("You have already posted a review for this clinic!", "/pages/clinics/clinic.php?ID=".$clinicID);
if(isset($_POST['review'])) $review=filter_var($_POST['review'], FILTER_SANITIZE_STRING);
else $review="";
$SQLinsertReview="INSERT INTO reviews (review, clinicID, personID, score) VALUES ('$review', '$clinicID', '$id', $score)";
if(!$databaseConnection->query($SQLinsertReview)) $msg->error("There has been an error submiting your review.", "/pages/clinics/clinic.php?ID=".$clinicID);
else echo "Elham";