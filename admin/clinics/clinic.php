<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if(!$isAdmin){
    $databaseConnection->close();
    $msg->error("Must be logged in as admin!", "/");
}
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
        }
    ?>
<h1>Clinic -<?php echo $name ?></h1>
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
?>
<!--Images and gallery-->
<?php
    $images=substr($images, 1, -1);
    $images=explode(", ", $images);?>
    <div class='galleria col-md-5'">
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
<script type="text/javascript">
    $(".addTagToDatabse").click(function(){
        var $this = $(this);
        var tagToAdd=$this.text();
       /* $.post( "post/addTag.php", { tag:"tagToAdd" })
          .done(function( data ) {
            alert( "Data Loaded: " + data );
          });*/
          $.ajax({
            url:"post/addTag.php",
            data: tagToAdd,
            method: "POST",
            success: function
          })
    })
</script>
<?php require "../../includes/footer.php"; ?>


</body>
</html>