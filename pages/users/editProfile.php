<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
require "../../includes/token.php";
if(!$isLoggedIn) {
    $databaseConnection->close();
    $msg->error("You must be logged in to edit your profile.", "/");
}
if(!isset($id) || !isset($_GET["ID"])) {
    $databaseConnection->close();
    $msg->error("An error has occurred. Please, try again later.", "/");
}
if($id!=$_GET["ID"]){
    $databaseConnection->close();
    $msg->error("You can only edit your own profile.", "/");
}
$SQLloadUser="SELECT * FROM users WHERE ID=$id LIMIT 1";
$user=$databaseConnection->query($SQLloadUser);
if(!$user){
    $databaseConnection->close();
    $msg->error("Error loading your profile. Please, try again.", "/");
}
$user=$user->fetch_assoc();
$SQLloadUserClinics="SELECT name, ID FROM clinics WHERE ownerID='$id'";
$userClinics=$databaseConnection->query($SQLloadUserClinics);
if(!$userClinics) $msg->warning("Error loading user clinics!");
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit profile</title>
    <?php require "../../includes/header.php";?>
</head>
<body>
    <?php require "../../includes/navbar.php"; ?>
    <?php if($msg->hasMessages()) $msg->display(); ?>
    <script
            src="https://www.paypal.com/sdk/js?client-id=ARddlugswaQNxof1Gj1-Tgrafo_dqqsUu8Zjxepf-ESCG7lbt46UZmGoWcgJT5_6BtAuY08Q-WVnwAmZ&vault=true&intent=subscription">
    </script>
    <form action="post/editProfile.php" method="POST" enctype="multipart/form-data">
        <label> Name:
            <input type="text" name="name" placeholder="Name" required value="<?php echo $user["name"]; ?>">
        </label><br><br>
        <label> Surname:
            <input type="text" name="surname" placeholder="Surname" required value="<?php echo $user["surname"]; ?>">
        </label><br><br>
        <label> E-mail:
            <input type="email" name="email" placeholder="E-mail" required value="<?php echo $user["email"]; ?>"><br>
        </label><br><br>
        <label>Profile picture:<br>
            <img src="<?php echo $user['profilePicture']; ?>" alt="" style="width: 200px;"><br>
            <input type="file" name="file" id="picture">
        </label><br><sub>Max file size: 1MB<br>.jpg .jpeg .png only allowed</sub><br><br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token']; ?>" required>
        <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
        <input type="hidden" value="<?php echo $id ?>" name="profileToEditID">
        <input type="submit" value="submit" name="submit">
    </form>
    <p>Need to change your password? <a href="/pages/users/resetPassword.php">Click here!</a></p>
    <?php if($hasClinics){?>
    <p>Here are your clinics:</p><br>
    <?php foreach ($userClinics as $userClinic){
        $clinicIDtemp=$userClinic['ID'];
        $clinicNameTemp=$userClinic['name'];
        echo "<a href='/pages/clinics/clinic.php?ID=$clinicIDtemp' target='_blank'>$clinicNameTemp</a><br>";
    }
    ?>
    <?php }
    else{?>
    <b>You have no approved clinics!</b><br>
    <?php }?>
    <?php if(!$_SESSION['hasPremium']) { ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="paypal-button-container"></div>
                <script>
                    //paypal.Buttons().render('#paypal-button-container');
                </script>
            </div>
        </div>
    </div>
<?php }
    else echo "<b>You already have premium!</b>";
    ?>
    <p>
        Test values:<br>
        =============================================<br>
        Card Type: Visa<br>
        Card Number: 4032031358226115<br>
        Expiration Date: 07/2024<br>
        CVV: 004<br>
        =============================================<br>
        sb-nj43of263812@personal.example.com<br>
        "?)%r3S:<br>
    </p>
    <?php
    require "../../includes/footer.php";
    $databaseConnection->close();
    ?>
    <script>
        paypal.Buttons({

            createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': 'P-03196537NY788235NMDDUH2A'
                });
            },
            onApprove: function(data, actions) {
                alert('You have successfully created subscription ' + data.subscriptionID);
                processSubscription(data.subscriptionID);
            }
        }).render('#paypal-button-container');
        function processSubscription(subID){
            $.ajax({
                method: "POST",
                data: {
                    userID:<?php echo $id; ?>,
                    subscriptionID:subID,
                    token: '<?php echo $_SESSION['csrf_token'] ?>'
                },
                url:"/pages/users/payments/addUserToSubscription.php",
                success: function (data){
                    Swal.fire({
                        icon: 'success',
                        title: 'Premium activated!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: `Ok`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })
                }
            })
        }
    </script>
</body>
</html>
