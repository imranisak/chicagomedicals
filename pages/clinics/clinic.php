<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
// require "../../includes/recaptcha.php";
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
$ownerID=$clinic['ownerID'];
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
$_SESSION['goBack']="/pages/clinics/clinic?ID=".$clinicID;

$SQLloadEmployees="SELECT * FROM employees WHERE clinicID='$clinicID'";
$employees=$databaseConnection->query($SQLloadEmployees);
if(!$employees) $msg->error("Error loading employees!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <link rel="stylesheet" type="text/css" href="/includes/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="/includes/slick/slick-theme.css">
</head>
<body>
	<?php include "../../includes/navbar.php" ?>
    <?php if($msg->hasMessages()) $msg->display(); ?>
<div class="container">
    <div class="row">
        <!--SLIDER-->
        <!--Images and gallery-->
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
            <br>
            <buton class="btn btn-danger reportClinicButton <?php echo $clinicID; ?>">Report clinic</buton>
        </div>
        <!--Info on right side-->
        <div class="col-md-7">
            <h1><?php echo $clinicName ?></h1>
            <?php if(isset($id) && $isLoggedIn) if($id==$clinic['ownerID']) echo "<a href='/pages/clinics/editClinic.php?ID=".$clinic['ID']." ' style='color: white'><button class='btn btn-primary'>Edit clinic</button></a>" ?>
            <p>Name: <?php echo $clinicName ?></p>
            <p>Owner: <a href="/pages/users/user.php?ID=<?php echo $ownerID ?>"><?php echo $owner ?></a></p>
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
<!--Employees-->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3>Employees of the clinic:</h3>
            <div class='employees'>
            <?php
            foreach ($employees as $employee){
                $employeeID=$employee['ID'];
                //echo "<p onclick='loadEmployee($employeeID)'>".$employee['name']." ".$employee['surname']."</p>";
                echo "
                    <div class='employeeSliderBoxContainer'>
                        <div class='employeeSliderBox'>
                            <img src='".$employee['picture']."' alt='".$employee['name']."'>
                            <h3>".$employee['name']."</h3>
                        </div>
                    </div>
                ";
            }
            ?>
            </div>
        </div>
    </div>
</div>
<?php
//Loads reviewes
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

    <!--Load the employee / view employee-->
    <script>
        function loadEmployee(ID){
            $.ajax({
                url: "/pages/employee/loadEmployee.php",
                type: "post",
                data: {'ID' : ID},
                success :  function(data){
                    employee=JSON.parse(data);
                    Swal.fire({
                        title: employee.name + " " + employee.surname + " - " + employee.title,
                        imageUrl: employee.picture,
                        html: employee.bio,
                    })
                }
            })
        }
    </script>
    <!--This bit here loads the reviews-->
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
                error: function (data){
                    console.log(data);
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
    <!--This bit here is responsible for clinic / review reporting-->
    <script>
        $(".reportClinicButton").click(function (){
            var selectedElementClass=$(this).attr("class");
            var clinicID=selectedElementClass.split(" ").pop();
            report("clinic", clinicID);
        });

        function reportReview(data){
            var input=data.substring(1);
        }

        function report(reportType, ID){
            var reportReason="", type=reportType;
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to report this?",
                icon: 'warning',
                input: 'text',
                inputLabel: 'Please explain why are you reporting this.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, report it!',
                inputValidator: (value) => {
                    if (!value) return 'You need to write a reason for reporting!'
                    else reportReason=value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: "post/reportClinic.php",
                        data: {
                            'reportReason':reportReason,
                            'ID': ID,
                            'type': type,
                            'token': '<?php echo $_SESSION['csrf_token']; ?>'
                        },
                        success: function(data){
                            if(data=="true") {//<---Don't ask
                                Swal.fire(
                                    'Reported!',
                                    'The content has been reported. <br> Thank you for your feedback.',
                                    'success'
                                )
                            }
                            else if(data=="token"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Invalid token!',
                                })
                            }
                            else if(data=="input"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Invalid input!',
                                })
                            }
                            else if(data=="sql"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error saving your report!',
                                })
                            }
                            else if(data=="send mail error"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error saving your report!',
                                })
                            }
                        },
                        error: function(data){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    })
                }
            })

        }
    </script>
<?php
$databaseConnection->close();
require "../../includes/footer.php"
?>
    <script type="text/javascript" src="/includes/slick/slick.min.js"></script>
    <script src="/includes/gallery.js"></script>
    <script>
        $(document).ready(function (){
            $('.employees').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });
            $('.slick-next').css('background-color', 'red');
            $('.slick-prev').css('background-color', 'red');
        });

    </script>
</body>
</html>