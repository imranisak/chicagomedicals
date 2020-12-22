<?php 
require 'includes/flashmessages.php';
require 'includes/database.php';
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
    <img src="<?php echo $_SESSION['profilePicture'] ?>" alt="Profile pic" width="10%">
    <?php } ?>

<form method="GET" action="pages/clinics/index.php">
    <label for="services">I need services of a </label>
    <input type="text" name="services" class="tagator" id="tags" placeholder="" required>
    <button class="btn btn-primary">Search</button>
</form>
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
</script>

<script type="text/javascript">
//Script for changing text in input
    /*function getRandomNumber(currentNumber, arrayLenght){
        var randomIndex=Math.floor(Math.random() * arrayLenght);
        console.log("Current num:"+currentNumber+" generated num: "+randomIndex);
        if(currentNumber==randomIndex) {
            getRandomNumber(currentNumber, arrayLenght);
        }
        else {
            console.log("Returned "+randomIndex);
            console.log("=======");
            return randomIndex;
        }
    }*/
    //This bit here should randomly change the placeholder for the search form
    //But it ain't working as it should...eh, I'll tend to it later
    $("#tags").attr("placeholder", "help");
    var tags=[<?php foreach ($tags as $tag) echo "'".$tag['tag']."',"; ?>];
    var whut=setInterval(changeServicesPlaceholder, 500);
    var arrayLenght=tags.length;
    function changeServicesPlaceholder(){
        var randomNumber=(Math.floor(Math.random() * arrayLenght));
        //$("#tags").attr("placeholder", "");
        var tag=tags[randomNumber]
        $("#tags").attr("placeholder", tag);
        console.log(tag);
    }

    
</script>
    <?php require 'includes/footer.php'; 
    $databaseConnection->close();
    ?>
</body>
</html>