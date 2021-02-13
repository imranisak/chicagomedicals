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
        <p>Welcome, <?php echo $_SESSION['name']." ".$_SESSION['surname']." "; ?></p>
        <img src="<?php echo $_SESSION['profilePicture'] ?>" alt="Profile pic" id="profilePicture">
        <a href="/pages/users/editProfile.php?ID=<?php echo $id ?>">Edit profile</a>
    <?php } ?>
<form method="GET" action="pages/clinics/index.php">
    <label for="services">I need services of a, or I need services for </label>
    <input type="text" name="services" class="tagator" id="tags" placeholder="" required>
    <button class="btn btn-primary">Search</button>
</form>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3 style="text-align: center;">Latest added</h3>
            <?php $SQLloadLatestClinics="SELECT * FROM clinics WHERE approved = '1' ORDER BY dateAdded DESC LIMIT 3";
                $latestClinics=$databaseConnection->query($SQLloadLatestClinics);
                if(!$latestClinics) echo "Error loading latest clinics";
                else{
                    foreach ($latestClinics as $bestClinic) {
                        $featuredImage=unserialize($bestClinic['images']);
                        $featuredImage=$featuredImage[0];
                        echo "
                <div class='container'>
			        <div class='row clinicBox'>
			        <div class='col-md-3'><img src='" .$featuredImage. "' class='featuredImage'></div>
                        <div class='col-md-9'>
                            <p class='clinicNameInBox'>" . $bestClinic['name'] . "</p>
                            <p class='ownerInBox'>Owner: " . $bestClinic['owner'] . "</p>
                            <a href='/pages/clinics/clinic.php?ID=" . $bestClinic['ID'] . "'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
			            </div>
                    </div>
			    </div>";
                    }
                }
            ?>
        </div>
        <div class="col-md-6">
            <h3 style="text-align: center">Best rated</h3>
            <?php
                $SQLloadBestClinics="SELECT * FROM clinics ORDER BY rating DESC LIMIT 3";
                $bestClinics=$databaseConnection->query($SQLloadBestClinics);
                if(!$bestClinics) echo "<p>Error loading best clinics. Guess they are too good, huh...</p>";
                else{
                    foreach ($bestClinics as $clinic){
                        $featuredImage=unserialize($clinic['images']);
                        $featuredImage=$featuredImage[0];
                        echo "
                        <div class='container'>
                            <div class='row clinicBox'>
                            <div class='col-md-3'><img src='" .$featuredImage. "' class='featuredImage'></div>
                                <div class='col-md-9'>
                                    <p class='clinicNameInBox'>" . $clinic['name'] . "</p>
                                    <p class='ownerInBox'>Owner: " . $clinic['owner'] . "</p>
                                    <a href='/pages/clinics/clinic.php?ID=" . $clinic['ID'] . "'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
                                </div>
                            </div>
                        </div>";
                    }
                }
            ?>
        </div>
    </div>
</div>

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
        var tag=tags[randomNumber];
        tag = tag.charAt(0).toUpperCase() + tag.slice(1)
        $("#tags").attr("placeholder", tag);
        $('#tags').tagator('refresh');
    }

    
</script>
    <?php require 'includes/footer.php'; 
    $databaseConnection->close();
    ?>
</body>
</html>