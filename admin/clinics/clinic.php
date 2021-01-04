<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if(!$isAdmin){
    $databaseConnection->close();
    $msg->error("Must be logged in as admin!", "/");
}
$token=$_SESSION['csrf_token'];
//Loads the clinic
$clinicID=$_GET['ID'];
$SQLselectClinic="SELECT * FROM clinics WHERE ID = '$clinicID' LIMIT 1";
if($databaseConnection->query($SQLselectClinic)) $clinics=$databaseConnection->query($SQLselectClinic);
else echo $databaseConnection->error;
//Loads the services/tags (dentist and such), converts them all to lower case
$SQLloadServices="SELECT * from tags ORDER BY tag ASC";
$allServices=$databaseConnection->query($SQLloadServices);
$servicesTemp=[];
foreach($allServices as $service) array_push($servicesTemp, strtolower($service['tag']));
$allServices=$servicesTemp;
//Loads the services that the user has sent, and converts them all to lower case - probably a terrible way to do this, but worksssss
$userInput=[];
array_push($userInput, strtolower($clinics->fetch_assoc()['services'])); 
$userInputTemp=[];
$userInputTemp=explode(",", $userInput[0]);
$userInput=$userInputTemp;
$notInDB=[];
foreach($userInput as $input) if(!in_array($input, $allServices)) array_push($notInDB, $input);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve clinic</title>
<?php require "../../includes/header.php" ?>
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
<?php require "../../includes/navbar.php"; ?>
<?php 
        foreach($clinics as $clinic){
            $clinicID=$clinic['ID'];
            $name=$clinic['name'];
            $owner=$clinic['owner'];
            $address=$clinic['address'];
            $zip=$clinic['zip'];
            $email=$clinic['email'];
            $website=$clinic['website'];
            $services=$clinic['services'];
            $facebook=$clinic['facebook'];
            $twitter=$clinic['twitter'];
            $instagram=$clinic['instagram'];
            $images=$clinic['images'];
            $clinicIsApproved=$clinic['approved'];
        }
    ?>
<?php if($msg->hasMessages()) $msg->display(); ?>
<h1>Clinic - <?php echo $name ?></h1>
<p>Name: <?php echo $name ?></p>
<p>Owner: <?php echo $owner ?></p>
<p>Address: <?php echo $address ?></p>
<p>ZIP: <?php echo $zip ?></p>
<p>Email: <?php echo $email ?></p>
<p>Website: <?php echo "<a href='".$website."' target='_blank'>".$website."</a>" ?></p>
<p>Services: <?php echo $services ?></p>
<p>Facebook: <?php echo "<a href='".$facebook."' target='_blank'>".$facebook."</a>" ?></p>
<p>Instagram: <?php echo "<a href='".$instagram."' target='_blank'>".$instagram."</a>" ?></p>
<p>Twitter: <?php echo "<a href='".$twitter."' target='_blank'>".$twitter."</a>" ?></p>
<?php
        if(!empty($notInDB)){
            echo "<p>These tags are not in the database, but the user has submitted them. <br>Click on each of them to add them to the database, so they can be used later.</p>";
            foreach ($notInDB as $input) echo "<a class='addTagToDatabse' href='#'>".$input."</a><br>";
        }
         if(!$clinicIsApproved) {
        echo "<p>This clinic has not yet been approved, and is not publiclly visible!</p>" ?>
        <?php //yes, the csrf token in visible in the link - but since it is randomlly generated with every load, it should not be a problem...right? ?>
        <a href="post/approveOrDenyClinic.php?token=<?php echo $token ?>&ID=<?php echo $clinicID ?>&action=approve"><button class="btn btn-success">Approve</button></a><span><button class="btn btn-danger" style="margin-left: 10px;" id="denyButton">Deny</button></span>
        <form style="display: none" id="reasonForDenial" method="POST" action="post/approveOrDenyClinic.php">
            <textarea name="reasonForDenial" rows='10' cols='30'></textarea><br>
            <p>Please explain why the clinic is being denied. The notification will be sent to the owner.</p>
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="action" value="deny">
            <input type="hidden" name="ID" value="<?php echo $clinicID ?>">
            <button value="submit" type="submit">Submit</button>
        </form>
        <?php 
}
?>
<!--Images and gallery-->
<?php
    //$images=substr($images, 1, -1);
    //$images=explode(", ", $images);
    $images=unserialize($images);
?>
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
<!--Add tags to DB-->
<script type="text/javascript">
    var token="<?php echo $_SESSION['csrf_token'] ?>";
    $(".addTagToDatabse").click(function(e){
        e.preventDefault()
        var $this = $(this);
        var tagToAdd=$this.text();
        if(confirm("Are you sure you want to add "+tagToAdd+" to database?")){
          $.ajax({
            url:"post/addTag.php",
            data: {'tag': tagToAdd, 
                    'token' : token},
            method: "POST",
            success: function(data){
                if(data==true){
                    $this.remove();
                    alert(tagToAdd + " has been added to the database!");
                }
                else alert("Something went wrong. Plese try again, or contact admin");
            }
          })
        }
    })
</script>
<!--Displays the form for denying-->
<script type="text/javascript">
    $("#denyButton").click(function(){
        console.log("boop");
        $("#reasonForDenial").css("display", "block");
    })
</script>
<?php require "../../includes/footer.php"; ?>


</body>
</html>