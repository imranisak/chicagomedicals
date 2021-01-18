<?php
require 'includes/flashMessages.php';
require 'includes/database.php';
require 'includes/sessionInfo.php';
$SQLloadTags="SELECT * FROM tags ORDER BY tag ASC";
$tags=$databaseConnection->query($SQLloadTags); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chicago Medicals</title>
    <?php require 'includes/header.php'; ?>
    <link rel="stylesheet" href="/includes/tagator/fm.tagator.jquery.css">
    <script src="/includes/tagator/fm.tagator.jquery.js"></script>
</head>
<body>
<?php include "includes/navbar.php";?>
    <?php if (isset($msg)) $msg->display(); ?>
    <h1>Chicago Medicals</h1>
    <?php if(isset($_SESSION['isLoggedIn'])){?>
    <p>Welcome, <?php echo $_SESSION['name']." ".$_SESSION['surname']." ".$_SESSION['role'];; ?></p>
    <img src="<?php echo $_SESSION['profilePicture'] ?>" alt="Profile pic" id="profilePicture">
    <?php } ?>
<form method="GET" action="pages/clinics/index.php">
    <label for="services">I need services of a, or I need services for </label>
    <input type="text" name="services" class="tagator" id="tags" placeholder="" required>
    <button class="btn btn-primary">Search</button>
</form>
    <?php var_dump($_SESSION['verified']); ?>
<script>
//Tagator script
$('#tags').tagator({
    prefix: 'tagator_',           // CSS class prefix
    height: 'auto',               // auto or element
    useDimmer: false,             // dims the screen when result list is visible
    showAllOptionsOnFocus: true, // shows all options even if input box is empty
    allowAutocompleteOnly: true, // only allow the autocomplete options
    autocomplete: [<?php foreach ($tags as $tag) echo "'".ucfirst($tag['tag'])."',"; ?>]              // this is an array of autocomplete options
});
</script>

<script type="text/javascript">
//Script for changing text in input
    function getRandomNumber(currentNumber, arrayLenght){
        var randomIndex=Math.floor(Math.random() * arrayLenght);
        console.log("Current num:"+currentNumber+" generated num: "+randomIndex);
        if(currentNumber==randomIndex) {
            getRandomNumber(currentNumber, arrayLenght);
        }
        else {
            //console.log("Returned "+randomIndex);
            //console.log("=======");
            return randomIndex;
        }
    }
    //This bit here should randomly change the placeholder for the search form
    //But it ain't working as it should...eh, I'll tend to it later
   $("#tags").attr("placeholder", "Dentist");
    var tags=[<?php foreach ($tags as $tag) echo "'".$tag['tag']."',"; ?>];
    var whut=setInterval(changeServicesPlaceholder, 1000);
    var arrayLenght=tags.length;
    function changeServicesPlaceholder(){
        var randomNumber=(Math.floor(Math.random() * arrayLenght));
        $("#tags").attr("placeholder", "");
        var tag=tags[randomNumber]
        $("#tags").attr("placeholder", tag);
        $('#tags').tagator('refresh');
    }

    
</script>
    <?php require 'includes/footer.php'; 
    $databaseConnection->close();
    ?>
</body>
</html>