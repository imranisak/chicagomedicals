<?php
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/clinics/addClinic.php';
require "../../includes/recaptcha.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
require "../../includes/database.php";
$SQLgetTags="SELECT * from tags ORDER BY tag ASC";
$tags=$databaseConnection->query($SQLgetTags);
//If it is a free users, and already has a clinic, redirect to payment page
if($isLoggedIn){
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php require "../../includes/header.php"?>
    <link rel="stylesheet" href="/includes/tagator/fm.tagator.jquery.css">
    <script src="/includes/tagator/fm.tagator.jquery.js"></script>
    <title>Add clinic</title>
</head>
<body>
<?php require "../../includes/navbar.php" ?>
<h1>Add a clinic</h1>
<form action="post/addClinic.php" method="post" enctype='multipart/form-data'>
    <input type="text" name="clinicName" placeholder="Clinic name" required><br>
    <input type="text" name="clinicAddress" placeholder="Clinic Address" required><br>
    <input type="text" class="tagator" id="tags" placeholder="Services"><br>
    <label for="pictureUpload">Upload pictures of your clinic (10 max)</label><br>
    <input type="file" name="file[]" id="pictureUpload" multiple>
    <input type="submit" value="Add clinic" name="submit">

</form>
<script>
    $('#tags').tagator({
        prefix: 'tagator_',           // CSS class prefix
        height: 'auto',               // auto or element
        useDimmer: false,             // dims the screen when result list is visible
        showAllOptionsOnFocus: true, // shows all options even if input box is empty
        allowAutocompleteOnly: false, // only allow the autocomplete options
        autocomplete: [<?php foreach ($tags as $tag) echo "'".$tag['tag']."',"; ?>]              // this is an array of autocomplete options
    });
</script>

<?php require "../../includes/footer.php" ?>
</body>
</html>


<?php
}else $msg->error("You must be logged in to access this page.", "/");
?>

