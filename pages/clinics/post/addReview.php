<?php
require "../../../includes/database.php";
require "../../../includes/recaptcha.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/sessionInfo.php";
// TODO Forgot to add DB close here...on all places FFS
$clinicID=$_POST['clinicID'];
$goBackClinicLink="/pages/clinics/clinic.php?ID=".$clinicID;
if(!$isLoggedIn) {
    $databaseConnection->close();
    $msg->error("Must be logged in to post a review!", "/");
}
if(!$isVerified) {
    $databaseConnection->close();
    $msg->error("Your account must be approved to post a review!", "/");
}
if($_SESSION['csrf_token']!=$_POST['token']) {
    $databaseConnection->close();
    $msg->error("Invalid token", $goBackClinicLink);
}
if(!isset($_POST['score'])) {
    $databaseConnection->close();
    $msg->error("Score not submitted", $goBackClinicLink);
}
else $score=filter_var($_POST["score"], FILTER_SANITIZE_STRING);
if($score>5 || $score<1) {
    $databaseConnection->close();
    $msg->error("Invalid score!", $goBackClinicLink);
}
$SQLloadReviewsFromThisUserOnThisClinic="SELECT * FROM reviews WHERE clinicID = $clinicID AND personID=$id";
$reviews=$databaseConnection->query($SQLloadReviewsFromThisUserOnThisClinic);
if(!$reviews) {
    $databaseConnection->close();
    $msg->error("There has been an error processing your review. Please, try again.", $goBackClinicLink);
}
if(mysqli_num_rows($reviews)>0) {
    $databaseConnection->close();
    $msg->error("You have already posted a review for this clinic!", $goBackClinicLink);
}
if(isset($_POST['review'])) $review=filter_var($_POST['review'], FILTER_SANITIZE_STRING);
else $review="";
$SQLinsertReview="INSERT INTO reviews (review, clinicID, personID, score) VALUES ('$review', '$clinicID', '$id', $score)";
if(!$databaseConnection->query($SQLinsertReview)) {
    $databaseConnection->close();
    $msg->error("There has been an error submiting your review.", $goBackClinicLink);
}
//This bit here below updated the rating of the clinic
//I have no idea how this works, I was half asleep writing this
$SQLloadClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
$clinic=$databaseConnection->query($SQLloadClinic);
if(!$clinic) {
    $databaseConnection->close();
    $msg->error("There has been an error updating the clinic score!", $goBackClinicLink);
}
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
if(!$databaseConnection->query($SQLupdateNumberOfReviews)) {
    $databaseConnection->close();
    $msg->error("There has been an error updating the number of reviews", $goBackClinicLink);
}
$SQLsumOfAllReview="SELECT SUM(score) FROM reviews WHERE clinicID='$clinicID'";
$sumOfAllReviews=$databaseConnection->query($SQLsumOfAllReview);
if(!$sumOfAllReviews) {
    $databaseConnection->close();
    $msg->error("Error updating sum of reviews!", $goBackClinicLink);
}
$sumOfAllReviews=$sumOfAllReviews->fetch_assoc();
$sumOfAllReviews=$sumOfAllReviews["SUM(score)"];
$newScore=$sumOfAllReviews / $currentNumberOfReviewsForClinic;
$newScore=round($newScore, 1);
$SQLsaveNewScoreOfClinic="UPDATE clinics SET rating = '$newScore' WHERE ID='$clinicID';";
if($databaseConnection->query($SQLsaveNewScoreOfClinic)) {
    $databaseConnection->close();
    $msg->success("Review successfully submitted!", $goBackClinicLink);
}
else{
	$databaseConnection->close();
	$msg->error("Error updating total clinic score!", $goBackClinicLink);
}