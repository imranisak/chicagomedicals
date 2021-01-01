<?php
require '../../includes/database.php';
require '../../includes/flashMessages.php';
require '../../includes/sessionInfo.php';
require '../../includes/token.php';
//Check if the user is logged in, and if the user is the owner
if(!$isLoggedIn) $msg->error("You must be logged in to edit your clinic!", "/pages/clinics");
if(isset($_GET['ID'])) $clinicID=$_GET['ID'];
else $msg->error("Clinic does not exist!", "/pages/clinics");
$SQLloadClinic="SELECT * FROM clinics WHERE ID=$clinicID";
$clinic=$databaseConnection->query($SQLloadClinic);
if(mysqli_num_rows($clinic)==0) $msg->error("Clinic does not exist!", "/pages/clinics");
if(!$clinic) $msg->error("There has been an internal error. Please, try again, or contact admin!", "/pages/clinics");
$clinic=mysqli_fetch_assoc($clinic);
if($clinic['ownerID']!=$_SESSION['id']) $msg->error("You can only edit your own clinics!", "/pages/clinics");
if(!$clinic['approved']) $msg->error("You can't edit a clinic that is not approved!", "/pages/clinics");
//Loads the data in easier variables
$clinicID=$clinic['ID'];
$clinicName=$clinic['name'];
$owner=$clinic['owner'];
$address=$clinic['address'];
$zip=$clinic['zip'];
$clinicEmail=$clinic['email'];
$website=$clinic['website'];
$services=$clinic['services'];
$facebook=$clinic['facebook'];
$twitter=$clinic['twitter'];
$instagram=$clinic['instagram'];
$images=$clinic['images'];
//Loads the tags
$SQLloadTags="SELECT * FROM tags ORDER BY tag ASC";
$tags=$databaseConnection->query($SQLloadTags); 
//Takes the loaded images and turns them into an array
$images=substr($images, 1, -1);
$images=explode(", ", $images);
//Go back, used if recaptcha fails
$_SESSION['goBack']="/pages/clinics/editclinic.php?ID=".$clinicID;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit <?php echo $clinicName ?></title>
	<?php require "../../includes/header.php"; ?>
    <link rel="stylesheet" href="/includes/tagator/fm.tagator.jquery.css">
    <script src="/includes/tagator/fm.tagator.jquery.js"></script>
</head>
<body>
<?php require "../../includes/navbar.php"; ?>
<h3>Editing <?php echo $clinicName; ?></h3>
<?php if($msg->hasMessages()) $msg->display(); ?>
<form action="post/editClinic.php" method="post" enctype='multipart/form-data'>
    <input type="hidden" name="clinicID" required value="<?php echo $clinicID; ?>"><br>
    <input type="text" name="clinicName" placeholder="Clinic name" required value="<?php echo $clinicName; ?>"><br>
    <input type="text" name="clinicAddress" placeholder="Clinic Address" required  value="<?php echo $address ?>"><br>
    <input type="email" name="clinicEmail" placeholder="Clinic Email" required  value="<?php echo $clinicEmail ?>"><br>
    <input type="number" name="zip" placeholder="Clinic ZIP code" required  value="<?php echo $zip ?>"><br>
    <input type="text" name="services" class="tagator" id="tags" placeholder="Services" required><br>
    <input type="url" name="clinicWebsite" placeholder="Website"  value="<?php echo $website ?>">
    <p>Social media</p>
    <input type="url" name="facebook" placeholder="Facebook"  value="<?php echo $facebook ?>"><br>
    <input type="url" name="instagram" placeholder="Instagram"  value="<?php echo $instagram ?>"><br>
    <input type="url" name="twitter" placeholder="Twitter"  value="<?php echo $twitter ?>"><br>
    <label for="pictureUpload">Upload pictures of your clinic (10 max)</label><br>
    <input type="file" name="file[]" id="pictureUpload" multiple>
    <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>" required>
    <input type="hidden" name="imagesToRemove" value="" id="imagesToRemoveInput">
    <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
    <input type="submit" value="Save changes" name="submit">
</form>
<p>These are your current images. Click on them to remove them. Click again to undo.</p>
<?php
foreach($images as $image) echo "<img src=".$image." style='width:200px;' class='imagePreview'><br><br>";?>
<script>
    //Tagator script
    $('#tags').tagator({
        prefix: 'tagator_',           // CSS class prefix
        height: 'auto',               // auto or element
        useDimmer: false,             // dims the screen when result list is visible
        showAllOptionsOnFocus: true, // shows all options even if input box is empty
        allowAutocompleteOnly: false, // only allow the autocomplete options
        autocomplete: [<?php foreach ($tags as $tag) echo "'".$tag['tag']."',"; ?>]              // this is an array of autocomplete options
    });
    $('#tags').val(<?php echo "'".$services."',";  ?>);
    $('#tags').tagator('refresh');
</script>
<script type="text/javascript">
    $(document).ready(function(){
        var imagesToRemove=[];
        $(".imagePreview").click(function(){
            //When you click on an image, adds them to an array. That array of images will be sent via POST
            //and removed from the clinic.
            var image=this.src;
            image = image.substring(image.lastIndexOf("/"));
            image="/media/pictures"+image;//Don't ask
            if(!imagesToRemove.includes(image)){
                imagesToRemove.push(image);
                $(this).addClass("imageToRemove");
            } 
            else{
                var indexOfImageToRemoveFromArray=imagesToRemove.indexOf(image);
                imagesToRemove.splice(indexOfImageToRemoveFromArray, 1);
                $(this).removeClass("imageToRemove");
            };
            $("#imagesToRemoveInput").attr("value", imagesToRemove);;
        });
    })
</script>
<?php 
$databaseConnection->close();
require "../../includes/footer.php";
?>
</body>
</html>
