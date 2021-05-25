<?php
require "../../../includes/database.php";
require "../../../includes/sessionInfo.php";
require "../../../includes/flashMessages.php";
require "../../../includes/token.php";
if(!$isAdmin){
    $databaseConnection->close();
    $msg->error("Must be logged in as admin!", "/");
}
if(!isset($_POST['deleteUserID'])) $msg->error("Invalid input!", "/admin/users");
else $userID=filter_var($_POST['deleteUserID'], FILTER_SANITIZE_NUMBER_INT);
//Load clinics of user
$SQLloadUserClinic="SELECT * FROM clinics WHERE ownerID = '$userID'";
$userClinics=$databaseConnection->query($SQLloadUserClinic);
if(!$userClinics){
    $databaseConnection->close();
    $msg->error("Error loading user clinics!", "/admin/users");
}
if($userClinics->num_rows==0) $userHasClinics=false;
else{
    $userHasClinics=true;
    $userClinicIDs=[];
    foreach ($userClinics as $userClinic){
        array_push($userClinicIDs, $userClinic['ID']);
    }
}
if($userHasClinics) {
    //Delete reviews on that clinic
    foreach ($userClinicIDs as $userClinicID) {
        $SQLdeleteReviews = "DELETE FROM reviews WHERE clinicID='$userClinicID'";
        $deleteReviews = $databaseConnection->query($SQLdeleteReviews);
        if (!$deleteReviews) {
            $msg->error("Error deleting reviews of clinic!");
            break;
        }
    }

    //Delete clinic pictures
    foreach ($userClinicIDs as $userClinicID){
        $SQLloadPictureArray="SELECT images FROM clinics WHERE ID='$userClinicID'";
        $picturesArray=$databaseConnection->query($SQLloadPictureArray);
        if(!$picturesArray) {
            $msg->error("Error loading pictures");
            break;
        }
        $picturesArray=$picturesArray->fetch_assoc();
        $picturesArray=unserialize($picturesArray['images']);
        foreach ($picturesArray as $picture){
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$picture)) unlink($_SERVER['DOCUMENT_ROOT'].$picture);
        }
    }

    //Delete clinics
    foreach ($userClinicIDs as $userClinicID){
        $SQLdeleteClinics="DELETE FROM clinics WHERE ID='$userClinicID'";
        $deleteClinics=$databaseConnection->query($SQLdeleteClinics);
        if(!$deleteClinics){
            $msg->error("Error deleting user's clinics!");
            break;
        }
    }
}

//Load clinic IDs where user has left a review, then delete user reviews
$userHasReviews=false;
$IDsOfClinicsWhereUserHasLeftAreview=[];
$SQLloadClinicsWhereUserHasLeftAreview="SELECT * FROM reviews WHERE personID='$userID'";
$clinicsWhereUserHasLeftAreview=$databaseConnection->query($SQLloadClinicsWhereUserHasLeftAreview);
if(!$clinicsWhereUserHasLeftAreview) $msg->error("Error loading clinics where user has left a review!");
if($clinicsWhereUserHasLeftAreview->num_rows!=0) {
    $userHasReviews=true;
    foreach ($clinicsWhereUserHasLeftAreview as $clinic) array_push($IDsOfClinicsWhereUserHasLeftAreview, $clinic['clinicID']);
}
$SQLdeleteReviews="DELETE FROM reviews WHERE personID='$userID'";
$deleteReviews=$databaseConnection->query($SQLdeleteReviews);
if(!$deleteReviews){
    $databaseConnection->close();
    $msg->error("Error deleting reviews!", "/admin/users");
}

//Recount clinic score - where review is deleted
if($userHasReviews){
    foreach ($IDsOfClinicsWhereUserHasLeftAreview as $clinicID){
        $SQLloadClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
        $clinic=$databaseConnection->query($SQLloadClinic);
        if(!$clinic) {
            $databaseConnection->close();
            $msg->error("There has been an error updating the clinic score!");
        }
        $clinic=$clinic->fetch_assoc();
        //Counts reviews from DB, then stores them to the associated clinic
        $SQLcountReviews=" SELECT COUNT(review) FROM reviews WHERE clinicID='$clinicID'";
        $currentNumberOfReviewsForClinic=$databaseConnection->query($SQLcountReviews);
        if(!$currentNumberOfReviewsForClinic){
            $databaseConnection->close();
            $msg->error("Error counting reviews!");
        }
        $currentNumberOfReviewsForClinic=$currentNumberOfReviewsForClinic->fetch_assoc();
        $currentNumberOfReviewsForClinic=$currentNumberOfReviewsForClinic["COUNT(review)"];
        $SQLupdateNumberOfReviews="UPDATE clinics SET numberOfReviews='$currentNumberOfReviewsForClinic' WHERE ID='$clinicID'";
        if(!$databaseConnection->query($SQLupdateNumberOfReviews)) {
            $databaseConnection->close();
            $msg->error("There has been an error updating the number of reviews");
        }
        $SQLsumOfAllReview="SELECT SUM(score) FROM reviews WHERE clinicID='$clinicID'";
        $sumOfAllReviews=$databaseConnection->query($SQLsumOfAllReview);
        if(!$sumOfAllReviews) {
            $databaseConnection->close();
            $msg->error("Error updating sum of reviews!");
        }
        $sumOfAllReviews=$sumOfAllReviews->fetch_assoc();
        $sumOfAllReviews=$sumOfAllReviews["SUM(score)"];
        $newScore=$sumOfAllReviews / $currentNumberOfReviewsForClinic;
        $newScore=round($newScore, 1);
        $SQLsaveNewScoreOfClinic="UPDATE clinics SET rating = '$newScore' WHERE ID='$clinicID';";
        if(!$databaseConnection->query($SQLsaveNewScoreOfClinic))$msg->error("Error updating total clinic score!");
    }
}

//Delete reports
$SQLdeleteReports="DELETE FROM reports WHERE type='user' AND reporterID='$userID'";
$deleteReports=$databaseConnection->query($SQLdeleteReports);
if(!$deleteReports) $msg->error("Error deleting reports!");

//Cancel PayPal subscription - if any

//Delete user's profile picture
$SQLloadUserPicture="SELECT profilePicture FROM users WHERE id='$userID'";
$profilePicture=$databaseConnection->query($SQLloadUserPicture);
if(!$profilePicture) $msg->error("Error loading user's profile picture!");
$profilePicture=$profilePicture->fetch_assoc();
$profilePicture=$profilePicture['profilePicture'];
if(file_exists($_SERVER['DOCUMENT_ROOT'].$profilePicture)) unlink($_SERVER['DOCUMENT_ROOT'].$profilePicture);

//Delete user from DB
if(!$msg->hasErrors()) {
    $SQLdeleteUser = "DELETE FROM users WHERE ID='$userID'";
    $deleteUser = $databaseConnection->query($SQLdeleteUser);
    if (!$deleteUser) $msg->error("Error deleting user!");
}
if($msg->hasErrors()){
    $databaseConnection->close();
    $msg->error("An error has occurred!", "/admin/users");
}
else{
    $databaseConnection->close();
    $msg->success("User deleted!", "/admin/users");
}