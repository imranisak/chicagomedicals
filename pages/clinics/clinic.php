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
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="/includes/photoswipe/photoswipe.css">

    <!-- Skin CSS file (styling of UI - buttons, caption, etc.)
         In the folder of skin CSS file there are also:
         - .png and .svg icons sprite,
         - preloader.gif (for browsers that do not support CSS animations) -->
    <link rel="stylesheet" href="/includes/photoswipe/default-skin/default-skin.css">

    <!-- Core JS file -->
    <script src="/includes/photoswipe/photoswipe.min.js"></script>

    <!-- UI JS file -->
    <script src="/includes/photoswipe/photoswipe-ui-default.min.js"></script>
</head>
<body>
	<?php include "../../includes/navbar.php" ?>
    <?php if($msg->hasMessages()) $msg->display(); ?>
    <!--Images and gallery-->

<div class="container">
    <div class="row">
        <div class="col-md-5">
                <div class="swiper-container col-md-12">
                    <!-- Additional required wrapper -->
                    <ul class="swiper-wrapper my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
                        <!-- Slides -->
                        <?php foreach ($images as $image) echo " 
                        <li class='swiper-slide' itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>
                            <a title='click to zoom-in' href='".$image."' itemprop='contentUrl' data-size='1200x800'>
                                <img src='".$image."' itemprop='thumbnail' alt='".$clinicName."' style='height: 300px' >
                            </a>
                        </li>"
                        ?>
                    </ul>

                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>


                <!-- Root element of PhotoSwipe. Must have class pswp. -->
                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

                    <!-- Background of PhotoSwipe.
                        It's a separate element, as animating opacity is faster than rgba(). -->
                    <div class="pswp__bg"></div>

                    <!-- Slides wrapper with overflow:hidden. -->
                    <div class="pswp__scroll-wrap">

                        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
                        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
                        <div class="pswp__container">
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                        </div>

                        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                        <div class="pswp__ui pswp__ui--hidden">

                            <div class="pswp__top-bar">

                                <!--  Controls are self-explanatory. Order can be changed. -->

                                <div class="pswp__counter"></div>

                                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                                <button class="pswp__button pswp__button--share" title="Share"></button>

                                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                                <!-- element will get class pswp__preloader--active when preloader is running -->
                                <div class="pswp__preloader">
                                    <div class="pswp__preloader__icn">
                                        <div class="pswp__preloader__cut">
                                            <div class="pswp__preloader__donut"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div class="pswp__share-tooltip"></div>
                            </div>

                            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                            </button>

                            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                            </button>

                            <div class="pswp__caption">
                                <div class="pswp__caption__center"></div>
                            </div>

                        </div>

                    </div>
                </div>
        </div>
        <div class="col-md-7">
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
        </div>
    </div>
</div>
<?php
    //$images=substr($images, 1, -1);
    //$images=explode(", ", $images);?>
<!--End of images-->
<?php
//Loads reviews
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
else echo "<h3>You must be logged in to submit a review!</h3>";

?>
    <div class="allReviews">
        <div class="col-md-12">
            <p><?php echo $clinicName." has ".$clinic['numberOfReviews']." reviews!" ?></p>
        </div>
    </div>

    <script>

        //This bit here loads the reviews
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
    <script src="/includes/gallery.js"></script>

</body>
</html>