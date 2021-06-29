<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
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
$user=$user->fetch_assoc();?>
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
                $.ajax({
                    method: POST,
                    data: {
                        userID:<?php echo $id; ?>,
                        subscriptionID:data.subscriptionID;
                    }
                    url:"/payments/addUserToSubscription"
                })
                alert('You have successfully created subscription ' + data.subscriptionID);
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
