<?php
require "../../../includes/database.php";
if(isset($_POST['clinicID'])) $clinicID=$_POST['clinicID'];
if(isset($_POST['offset'])) $offset=$_POST['offset'];
else $offset=3;
$SQLloadReviews="SELECT * FROM reviews WHERE clinicID=$clinicID ORDER BY dateAdded DESC LIMIT 3 OFFSET $offset";
$reviews=$databaseConnection->query($SQLloadReviews);
if(!$reviews) echo $databaseConnection->error;
$boxOfReviews="";
foreach($reviews as $review) {
    $authorID=$review['personID'];
    $datePosted=new DateTime($review['dateAdded']);
    //$datePosted=$review['dateAdded'];
    $datePosted=$datePosted->format('Y-m-d');
    $SQLloadReviewAuthor="SELECT * FROM users WHERE ID='$authorID'";
    $reviewAuthor=$databaseConnection->query($SQLloadReviewAuthor);
    if(!$reviewAuthor) echo "Error loading review author! ".$databaseConnection->error;
    else{
        $reviewAuthor=$reviewAuthor->fetch_assoc();
        $name=$reviewAuthor['name'];
        $surname=$reviewAuthor['surname'];
        $profilePicture=$reviewAuthor['profilePicture'];
    }
    $boxOfReviews.="
            <div class='row singleReview'>
                <div class='col-md-1'>
                    <img src='".$profilePicture."' class='profilePictureOnReview'>
                </div>
                <div class='col-md-10'>
                    <p>".$name." ".$surname."</p>
                    <p>Posted on: ".$datePosted."</p>
                    <p>".$review['review']."</p>
                    <p>".$review['score']." / 5</p>
                </div>
            </div>
            ";
}
$databaseConnection->close();
echo $boxOfReviews;