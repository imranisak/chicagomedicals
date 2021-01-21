<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
require "../../includes/recaptcha.php";
require "../../includes/sessionInfo.php";
//require "../../includes/galleria.php";
if(isset($_GET['ID'])) $clinicID=$_GET['ID'];
else $msg->error("No ID provided!", "/pages/clinics");
$SQLseletClinic="SELECT * FROM clinics WHERE ID = '$clinicID'";
$clinic=$databaseConnection->query($SQLseletClinic);
if(!$clinic) $msg->error("There has been an internal error. Please, try again, or contact admin!", "/pages/clinics");
if(mysqli_num_rows($clinic)==0) $msg->error("No clinics found.", "/pages/clinics");
$clinic=$clinic->fetch_assoc();
if(!$clinic['approved']) $msg->error("Clinic has not yet been approved!", "/pages/clinics");
$clinicName=$clinic['name'];
$owner=$clinic['owner'];
$address=$clinic['address'];
$zip=$clinic['zip'];
$clinicMail=$clinic['email'];
$website=$clinic['website'];
$services=$clinic['services'];
$facebook=$clinic['facebook'];
$twitter=$clinic['twitter'];
$instagram=$clinic['instagram'];
$images=unserialize($clinic['images']);
$clinicIsApproved=$clinic['approved'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $clinicName ?></title>
	<?php include "../../includes/header.php" ?>
	<link type="text/css" rel="stylesheet" href="/includes/galleria/themes/classic/galleria.classic.css">
<script type="text/javascript" src="/includes/galleria/galleria.js"></script>
<script type="text/javascript" src="/includes/galleria/themes/classic/galleria.classic.js"></script>
<style>
.galleria{
    height:500px;
    width:auto;
}
</style>
</head>
<body>
	<?php include "../../includes/navbar.php" ?>
    <?php if($msg->hasMessages()) $msg->display(); ?>
	<h1><?php echo $clinicName ?></h1>
	<p>Name: <?php echo $clinicName ?></p>
	<p>Owner: <?php echo $owner ?></p>
	<p>Address: <?php echo $address ?></p>
	<p>ZIP: <?php echo $zip ?></p>
	<p>Email: <?php echo $clinicMail ?></p>
	<p>Website: <?php echo "<a href='".$website."' target='_blank'>".$website."</a>" ?></p>
	<p>Services: <?php echo join(',', array_map('ucfirst', explode(',', $services))); ?></p>
	<p>Facebook: <?php echo "<a href='".$facebook."' target='_blank'>".$facebook."</a>" ?></p>
	<p>Instagram: <?php echo "<a href='".$instagram."' target='_blank'>".$instagram."</a>" ?></p>
	<p>Twitter: <?php echo "<a href='".$twitter."' target='_blank'>".$twitter."</a>" ?></p>
	<!--Images and gallery-->
<?php
    //$images=substr($images, 1, -1);
    //$images=explode(", ", $images);?>
    <div class='galleria col-md-5'>
    <?php foreach($images as $image) echo "<img src=".$image.">";?>
    </div>
    <script>
                (function() {
                    Galleria.loadTheme('/includes/galleria/themes/classic/galleria.classic.js');
                    Galleria.run('.galleria');
                }());
    </script>
    <script type="text/javascript">
    $('.galleria').galleria({
    width: auto,
    height: 500
    });
    </script>
<!--End of images-->
<?php
if($isLoggedIn){
$SQLcountReviews="SELECT COUNT(review) FROM reviews WHERE clinicID=$clinicID AND personID=$id";
$numberOfReviewsFromThisPersonForThisClinic=$databaseConnection->query($SQLcountReviews);
$numberOfReviewsFromThisPersonForThisClinic=$numberOfReviewsFromThisPersonForThisClinic->fetch_assoc();
$numberOfReviewsFromThisPersonForThisClinic=$numberOfReviewsFromThisPersonForThisClinic["COUNT(review)"];
if($numberOfReviewsFromThisPersonForThisClinic==0) require "../../includes/reviewBox.php";
else{
    $SQLloadTheReview="SELECT * FROM reviews WHERE personID=$id AND clinicID=$clinicID";
    $review=$databaseConnection->query($SQLloadTheReview);
    if(!$review) echo "<h3>$databaseConnection->error</h3>";
    else{
        $review=$review->fetch_assoc();
            echo "
            <p>You have already posted a review for ".$clinicName."</p>
            <div class='row'>
                <div class='col-md-1'>
                    <img src='".$profilePicture."' class='profilePictureOnReview'>
                </div>
                <div class='col-md-10'>
                    <p>".$name." ".$surname."</p>
                    <p>".$review['review']."</p>
                    <p>".$review['score']." / 5</p>
                </div>
            </div>
            ";
        }
    }
}
?>
    <div class="allReviews">
        <div class="col-md-12">
            <p><?php echo $clinicName." has ".$clinic['numberOfReviews']." reviews!" ?></p>
        </div>
    </div>

    <script>
        var clinicID=<?php echo $clinicID;?>;
        $(document).ready(function(){
            loadReviews();
        })
        function loadReviews(){
            var numberOfLoadedReviews=$('.singleReview').length;
            $.ajax({
                url:'post/loadReviews.php',
                data:{
                    'clinicID':clinicID,
                    'offset':numberOfLoadedReviews
                },
                success: function (data){
                    $(".allReviews").append(data);
                },
                method: "POST"
            });

        }
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                loadReviews();
            }
        });
    </script>
<?php
$databaseConnection->close();
require "../../includes/footer.php"
?>
</body>
</html>