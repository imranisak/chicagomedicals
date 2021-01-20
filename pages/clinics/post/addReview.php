<?php
require "../../../includes/database.php";
require "../../../includes/recaptcha.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/sessionInfo.php";
// TODO Forgot to add DB close here...on all places FFS
$clinicID=$_POST['clinicID'];
$goBackClinicLink="/pages/clinics/clinic.php?ID=".$clinicID;
if(!$isLoggedIn) $msg->error("Must be logged in to post a review!", "/");
if(!$isVerified) $msg->error("Your account must be approved to post a review!", "/");
if($_SESSION['csrf_token']!=$_POST['token']) $msg->error("Invalid token", $goBackClinicLink);
if(!isset($_POST['score'])) $msg->error("Score not submited", $goBackClinicLink);
else $score=filter_var($_POST["score"], FILTER_SANITIZE_STRING);
if($score>5 || $score<1) $msg->error("Invalid score!", $goBackClinicLink);
$SQLloadReviewsFromThisUserOnThisClinic="SELECT * FROM reviews WHERE clinicID = $clinicID AND personID=$id";
$reviews=$databaseConnection->query($SQLloadReviewsFromThisUserOnThisClinic);
if(!$reviews) $msg->error("There has been an error processing your review. Please, try again.", $goBackClinicLink);
if(mysqli_num_rows($reviews)>0) $msg->error("You have already posted a review for this clinic!", $goBackClinicLink);
if(isset($_POST['review'])) $review=filter_var($_POST['review'], FILTER_SANITIZE_STRING);
else $review="";
$SQLinsertReview="INSERT INTO reviews (review, clinicID, personID, score) VALUES ('$review', '$clinicID', '$id', $score)";
if(!$databaseConnection->query($SQLinsertReview)) $msg->error("There has been an error submiting your review.", $goBackClinicLink);
//This bit here below updated the rating of the clinic
//I have no idea how this works, I was half asleep writing this
$SQLloadClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
$clinic=$databaseConnection->query($SQLloadClinic);
if(!$clinic) $msg->error("There has been an error updating the clinic score!".$clinic->error, $goBackClinicLink);
$clinic=$clinic->fetch_assoc();
//Counts reviws from DB, then stores them to the asociated clinic
$SQLcountReviews=" SELECT COUNT(review) FROM reviews WHERE clinicID='$clinicID'";
$currentNumberOfReviewsForClinic=$databaseConnection->query($SQLcountReviews);
if(!$currentNumberOfReviewsForClinic){
	$databaseConnection->close();
	$msg->error("Error counting reviews!", $goBackClinicLink);
}
$currentNumberOfReviewsForClinic=$currentNumberOfReviewsForClinic->fetch_assoc();
$currentNumberOfReviewsForClinic=$currentNumberOfReviewsForClinic["COUNT(review)"];
$SQLupdateNumberOfReviews="UPDATE clinics SET numberOfReviews='$currentNumberOfReviewsForClinic' WHERE ID=$clinicID";
if(!$databaseConnection->query($SQLupdateNumberOfReviews)) $msg->error("There has been an error updating the number of reviews", $goBackClinicLink);
$SQLsumOfAllReview="SELECT SUM(score) FROM reviews WHERE clinicID='$clinicID'";
$sumOfAllReviews=$databaseConnection->query($SQLsumOfAllReview);
if(!$sumOfAllReviews) $msg->error("Error updating sum of reviews!", $goBackClinicLink);
$sumOfAllReviews=$sumOfAllReviews->fetch_assoc();
$sumOfAllReviews=$sumOfAllReviews["SUM(score)"];
$newScore=$sumOfAllReviews / $currentNumberOfReviewsForClinic;
$newScore=round($newScore, 1);
//die(var_dump($newScore));
$SQLsaveNewScoreOfClinic="UPDATE clinics SET rating = '$newScore' WHERE ID='$clinicID';";
if($databaseConnection->query($SQLsaveNewScoreOfClinic)) $msg->success("Review succesfully submited!", $goBackClinicLink);
else{
	$databaseConnection->close();
	$msg->error("Error updating total clinic score!", $goBackClinicLink);
}


$databaseConnection->close();