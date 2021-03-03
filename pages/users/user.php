<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
require "../../includes/token.php";
if(isset($_GET['ID'])) $userID=filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);
else{
    $databaseConnection()->close();
    $msg->error("Invalid ID", "/");
}
$SQLloadUser="SELECT * FROM users WHERE ID='$userID'";
$user=$databaseConnection->query($SQLloadUser);
if(!$user){
    $databaseConnection->close();
    $msg->error("Error loading profile!");
}
if($user->num_rows==0){
    $databaseConnection->close();
    $msg->warning("User not found!", "/");
}
$user=$user->fetch_assoc();
$userName=$user['name'];
$userSurname=$user['surname'];
$userFullName=$userName." ".$userSurname;
$userProfilePicture=$user['profilePicture'];
$userEmail=$user['email'];

if($user['hasClinic']){
    $SQLloadUserClinics="SELECT * FROM clinics WHERE ownerID='$userID'";
    $clinics=$databaseConnection->query($SQLloadUserClinics);
    if(!$clinics) $msg->warning("Error loading user clinics!");
}
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> <?php echo $userFullName ?> </title>
    <?php require "../../includes/header.php"?>
</head>
<body>
<?php require "../../includes/navbar.php";
if ($msg->hasMessages()) $msg->display(); ?>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo $userProfilePicture ?>" alt="Profile Picture">
        </div>
        <div class="col-md-8">
            <p> <strong>Name:</strong> <?php echo $userName ?></p>
            <p> <strong>Surname:</strong> <?php echo $userSurname ?></p>
            <p> <strong>Email:</strong> <?php echo "<a href='mailto:".$userEmail."'>".$userEmail."</a>"?></p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-danger reportUser">Report user</button>
            <?php if(!$user['hasClinic']) echo "<h3>User has no approved clinics.</h3>";
            else{
                echo "<p>Clinics of ".$userName." on Chicago Medicals:</p>";
                foreach ($clinics as $clinic){
                    $clinicID=$clinic['ID'];
                    $clinicImages=unserialize($clinic['images']);
                    $clinicImage=$clinicImages[0];
                    $clinicName=$clinic['name'];
                    echo "<div class='row singleClinic'>
                            <div class='col-md-1'>
                                <img src='".$clinicImage."' class='previewPicOfClinicOnProfileView'>
                            </div>
                            <div class='col-md-10'>
                                <p>".$clinicName."</p>
                                <p class='ratingInBox'>User rating: ".$clinic['rating']." / 5 (based on ".$clinic['numberOfReviews']." reviews)</p>
                                <div class='stars' style='--rating: ".$clinic['rating'].";' aria-label='Rating of this product is ".$clinic['rating']." out of 5'></div><br>
                                <a href='/pages/clinics/clinic.php?ID=".$clinic['ID']."'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
                            </div>
                        </div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<!--User report system-->
<script>
    $('.reportUser').click(function (){
        var userToReport=<?php echo $userID ?>;
       report("user", userToReport);
    });
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
                    url: "/pages/clinics/post/reportClinic",
                    data: {
                        'reportReason':reportReason,
                        'ID': ID,
                        'type': type,
                        'token': '<?php echo $_SESSION['csrf_token']; ?>'
                    },
                    success: function(data){
                        console.log(data);
                        if(data=="true") {//<---Don't ask
                            Swal.fire(
                                'Reported!',
                                'The clinic has been reported. <br> Thank you for your feedback.',
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
require '../../includes/footer.php'?>
</body>
</html>
