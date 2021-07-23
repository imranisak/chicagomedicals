<?php
if (!session_id()) @session_start();
$_SESSION['goBack']='/pages/clinics/addClinic.php';
//require "../../includes/recaptcha.php";
require "../../includes/flashMessages.php";
require "../../includes/sessionInfo.php";
require "../../includes/database.php";
require "../../includes/token.php";
if(!$isLoggedIn) $msg->warning("You must be logged in to add a clinic.", "/");
if(!$isVerified) $msg->warning("You must first verify your profile before adding a clinic.", "/");
$SQLgetTags="SELECT * from tags ORDER BY tag ASC";
$tags=$databaseConnection->query($SQLgetTags);
//If it is a free users, and already has a clinic, redirect to payment page
//var_dump($_SESSION);
if($isLoggedIn){
    if(!$hasPremium || $hasPremium=="0"){
        $SQLloadUserClinics="SELECT * FROM clinics WHERE ownerID='$id'";
        $numberOfClinics=$databaseConnection->query($SQLloadUserClinics);
        if(!$numberOfClinics){
            $databaseConnection->close();
            $msg->error("Error loading user's clinics.", "/");
        }
        $numberOfClinics=$numberOfClinics->num_rows;
        if($numberOfClinics>=1){
            $databaseConnection->close();
            $msg->warning("Free users can have only one clinic. <a href='/pages/users/editProfile.php?ID=$id'>Upgrade to premium?</a>", "/");
        }
    }
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
<?php if($msg->hasMessages()) $msg->display(); ?>
<h1>Add a clinic</h1>
<h3>The clinic you add will be linked to your account, and you will be added as the owner!</h3>
    <form action="post/addClinic.php" method="post" enctype='multipart/form-data' ID="addClinicForm">
        <div class="form-group">
            <input type="text" name="clinicName" placeholder="Clinic name" required><br>
            <input type="text" name="clinicAddress" placeholder="Clinic Address" required><br>
            <input type="email" name="clinicEmail" placeholder="Clinic Email" required><br>
            <input type="number" name="zip" placeholder="Clinic ZIP code" required><br>
            <input type="text" name="services" class="tagator" id="tags" placeholder="Services" required><br>
            <input type="url" name="clinicWebsite" placeholder="Website">
            <p>Social media</p>
            <input type="url" name="facebook" placeholder="Facebook"><br>
            <input type="url" name="instagram" placeholder="Instagram"><br>
            <input type="url" name="twitter" placeholder="Twitter"><br>
            <input type="file" name="file[]" id="pictureUpload" multiple required onchange="checkFiles(this.files)"><br>
            <label for="pictureUpload"><?php if(!$hasPremium) echo "Upload pictures of your clinic (10 max). Premium users can upload up to 25 pictures!"; ?>
            <?php if($hasPremium) echo "Upload pictures of your clinic (25 max)."; ?>
            </label><br>
            <?php if($hasPremium) echo "<button class='btn btn-primary' id='addEmployeeButton' type='button'>Add an employee to your clinic!</button>";
            else echo "<b>Premium users can add employees to their clinics.</b><br><a href='/pages/users/editProfile.php?ID=$id'>Get premium?</a>";
            ?>
            <div id="employees" class="col-md-3"></div>
            <div id="addEmployeeBox" class="col-md-2" style="margin: 10px 0px 10px -10px"></div>
            <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>" required>
            <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
            <button type="submit" value="Add clinic" name="submit" class="btn btn-success">Add clinic</button>
        </div>
    </form>
<!--Add employee button-->
<script>
    var numberOfEmployees=0, employeePicutre="", employeeIncrement=0;
    //This function here is fired up when the user clicks the Add employee button.
    $("#addEmployeeButton").click(function (e) {
        $("#addEmployeeButton").attr("disabled", "disabled");
        e.preventDefault();
        $("#addEmployeeBox").append("<input type='text' id='employee" + employeeIncrement + "Name' name='employee" + employeeIncrement + "Name' placeholder='Employee name (required)' class='form-control'><br>" +
            "<input type='text' id='employee" + employeeIncrement + "Surname' name='employee" + employeeIncrement + "Surname' placeholder='Employee surname' class='form-control'><br>" +
            "<input type='text' id='employee" + employeeIncrement + "Title' name='employee" + numberOfEmployees + "Title' placeholder='Employee title' class='form-control'><br>" +
            "<textarea id='employee" + employeeIncrement + "Bio' name='employee" + employeeIncrement + "Bio' placeholder='Short bio' class='form-control'></textarea><br>" +
            "<label>Profile picture:<br> <input id='employeePicture' type='file' name='file' accept='image/jpg, image/png, image/jfif, image/gif, image/jpeg' > </label><br><sub>Max file size: 1MB</sub><br><br>" +
            "<button class='btn btn-success' type='button' onclick='saveEmployee("+employeeIncrement+");'>Save employee!</button>" +
            "<button class='btn btn-danger' type='button' onclick='cancelEmployee();'>Cancel</button>");
    });
    //This function here fires up when the user clicks "Save employee"
    function saveEmployee(employeeNumber){
        var nameOfEmployee=$("#employee"+employeeNumber+"Name").val();
        if (nameOfEmployee=="") {
            $("#employee"+employeeNumber+"Name").attr("style", "border: 3px solid red;");
            return 0;
        }
        $("#addEmployeeButton").removeAttr("disabled");
        var inputs=$("#addEmployeeBox > input").attr("hidden", "true");
        var textArea=$("#addEmployeeBox > textarea").attr("hidden", "true");
        $("#addClinicForm").append(inputs);
        $("#addClinicForm").append(textArea);
        //Image upload
        var file_data = $('#employeePicture').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('submit', true);
        $.ajax({
            url: '/pages/employee/post/uploadPicture.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success: function(data){
                var employeeID=numberOfEmployees;
                employeePicutre=data;
                $("#addClinicForm").append("<input type='hidden' id='employee"+employeeIncrement+"Picture' name='employee"+employeeIncrement+"Picture' value='"+data+"'>");
                $("#employees").append("<p id='"+employeeID+"'>"+nameOfEmployee+"<i class='fas fa-trash-alt' style='display: inline; color: red; margin-left:10px' onclick='deleteEmployee("+employeeID+")'></i></p>");
                $("#addEmployeeBox").text("");
                numberOfEmployees++;
                employeeIncrement++
            }
        });

    }
    //Empties the box where the user inputs employee data
    function cancelEmployee(){
        $("#addEmployeeButton").removeAttr("disabled");
        $("#addEmployeeBox").text("");
    }
    //Removes the employee the user has just saved.
    function deleteEmployee(employeeID){
        Swal.fire({
            title: 'Are you sure?',
            text: "Remove employee you just added?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#"+employeeID).remove();
                //Removes the hidden values from the form
                $("#employee"+employeeID+"Name").remove();
                $("#employee"+employeeID+"Surname").remove();
                $("#employee"+employeeID+"Title").remove();
                $("#employee"+employeeID+"Bio").remove();
                $("#employee"+employeeID+"Picture").remove();
                $.ajax({
                   url:"/pages/employee/post/removePicture",
                   type: "POST",
                   data: {picture:employeePicutre},
                   success:function (data){
                       //alert(data);
                   }
                });
                numberOfEmployees--;
            }
        })
    }
</script>
<!--Tagator-->
<script>
    $('#tags').tagator({
        prefix: 'tagator_',           // CSS class prefix
        height: 'auto',               // auto or element
        useDimmer: false,             // dims the screen when result list is visible
        showAllOptionsOnFocus: true, // shows all options even if input box is empty
        allowAutocompleteOnly: false, // only allow the autocomplete options
        autocomplete: [<?php foreach ($tags as $tag) echo "'".ucfirst($tag['tag'])."',"; ?>]              // this is an array of autocomplete options
    });
</script>
<!--Image check-->
<script>
    var maxFiles=<?php if(!$hasPremium) echo 10; else echo 25; ?>;
    var hasPremium=<?php echo $hasPremium; ?>;
    var userID=<?php echo $id ?>;
    function checkFiles(files) {
        if(files.length>maxFiles) {
            if(!hasPremium){
                var linkPremium="/pages/users/editProfile.php?ID="+userID;
                Swal.fire({
                    icon: 'warning',
                    title: 'Too many images!',
                    text: 'Free users can only upload 10 images!',
                    footer: '<a href='+linkPremium+'>Upgrade to premium?</a>'
                })
            }
            else
            {
                Swal.fire({
                    icon: 'warning',
                    title: 'Too many images!',
                    text: "You're allowed a maximum of 25 images per clinic!"
                })
            }

            let list = new DataTransfer;
            for(let i=0; i<maxFiles; i++)
                list.items.add(files[i])

            document.getElementById('pictureUpload').files = list.files
        }
    }
</script>
<?php require "../../includes/footer.php" ?>
</body>
</html>


<?php
}else $msg->warning("You must be logged in to add clinics.", "/pages/users/login.php");
?>

