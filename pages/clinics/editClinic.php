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
$images=unserialize($clinic['images']);
//Loads the tags
$SQLloadTags="SELECT * FROM tags ORDER BY tag ASC";
$tags=$databaseConnection->query($SQLloadTags); 
//Loads the employees
$SQLloadEmployees="SELECT * FROM employees WHERE clinicID='$clinicID'";
$employees=$databaseConnection->query($SQLloadEmployees);
if(!$employees) $employees="<b>Error loading employees!</b>";
if ($employees->num_rows<=0) $employees=false;
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
<form action="post/editClinic.php" method="post" enctype='multipart/form-data' id="editClinicForm">
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
    <?php if(!$hasPremium){ ?><label for="pictureUpload">Upload pictures of your clinic (10 max) - premium users can upload up to 25 pictures</label><br>
    <?php } else {?><label for="pictureUpload">Upload pictures of your clinic (25 max)</label><br>
    <?php } ?>
    <input type="file" name="file[]" id="pictureUpload" multiple>
    <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>" required>
    <input type="hidden" name="imagesToRemove" value="" id="imagesToRemoveInput">
    <!--Employees-->
    <?php if($hasPremium) echo "<br><button class='btn btn-primary' id='addEmployeeButton' type='button'>Add an employee to your clinic!</button>";
    else echo "<b>Premium users can add employees to their clinics.</b><br><a href='/pages/users/editProfile.php?ID=$id'>Get premium?</a>";
    ?>
    <div id="employees" class="col-md-3"></div>
    <div id="addEmployeeBox" class="col-md-3"></div>
    <input type="hidden" name="numberOfEmployees" value="0" ID="numberOfEmployees">
    <input type="hidden" name="employeeIncrement" value="0" ID="employeeIncrement">
    <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
    <input type="submit" value="Save changes" name="submit" class="btn btn-success">
</form>
<form action="post/deleteClinic.php" id="deleteClinicForm" method="post">
    <input type="hidden" name="clinicID" value="<?php echo $_GET['ID'] ?>" required>
    <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>" required>
</form>

<!---Employees view (table)-->
<?php if($hasPremium && $employees){ ?>
    <div class="col-md-5">
        <p>These are the employees of your clinic:</p>
        <table id="employeeTable">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
            foreach ($employees as $employee) {
                $employeeID=$employee['ID'];
                echo "
                    <tr id='employee".$employeeID."Row'>
                        <td>
                            <p id='employee".$employeeID."' onclick=loadEmployee(".$employeeID.")>".$employee['name']." ".$employee['surname']."</p>
                        </td>
                        <td>
                            <i class='fas fa-trash-alt' style='display: inline; color: red;' onclick='deleteEmployeeFromDatabase(".$employeeID.",".$clinicID.")'></i>
                            |
                            <i class='fas fa-edit' onclick='editEmployee(".$employeeID.")'></i>
                        </td>
                    </tr>
                ";
            }
        ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<!--Edit employee form-->
<div class="col-md-3" id="editEmployeeBox">
</div>

<p>These are your current images. Click on them to remove them. Click again to undo.</p>
<?php
foreach($images as $image) echo "<img src=".$image." style='width:200px;' class='imagePreview'><br><br>";?>
<buton class="btn btn-danger" id="deleteClinicButton">Delete clinic</buton>

<!--SCRIPTS-->

<!--Edit employee-->
<script>
    function editEmployee(ID){
        let token = "<?php echo $_SESSION['csrf_token'];?>";
        let clinicID = "<?php echo $clinicID;?>";
        $("#editEmployeeBox").html("");
        $("#editEmployeeBox").append("<form action='/pages/employee/post/editEmployee.php' method='post'> " +
        "<div class='form-group'> " +
            "<label for='name'>Employee name</label>" +
            "<input type='text' class='form-control' id='editEmployeeName'  placeholder='Employee name' name='editEmployeeName' required> " +
        "</div> " +
        "<div class='form-group'>" +
            "<label for='surname'>Employee surname</label> " +
            "<input type='text' class='form-control' id='editEmployeeSurname' placeholder='Employee surname' name='editEmployeeSurname'> " +
        "</div> " +
        "<div class='form-group'> " +
            "<label for='title'>Employee title</label> " +
            "<input type='text' class='form-control' id='editEmployeeTitle' placeholder='Employee title' name='editEmployeeTitle'> " +
        "</div> " +
        "<div class='form-group'>" +
            "<label for='bio'>Employee bio</label>" +
            "<textarea class='form-control' id='editEmployeeBio' rows='3' placeholder='Bio' name='editEmployeeBio'></textarea> " +
        "</div>" +
        "<div class='form-group'> " +
            "<label for='picture'>Profile picture</label>" +
            "<input type='file' class='form-control-file' id='picture'> " +
        "</div>" +
            "<input type='hidden' id='editEmployeeID' name='editEmployeeID' required> " +
            "<input type='hidden' id='editEmployeeClinicID' name='editEmployeeClinicID' value="+clinicID+" required> " +
            "<input type='hidden' name='token' value='"+token+"' required> " +
            "<button type='submit' class='btn btn-success'>Update employee</button><br>" +
            "<button type='button' class='btn btn-danger' onclick='cancelEdit()'>Cancel edit</button>" +
            "</form>");
        $.ajax({
            url: "/pages/employee/loadEmployee.php",
            type: "post",
            data: {'ID' : ID},
            success :  function(data){
                employee=JSON.parse(data);
                $("#editEmployeeName").val(employee.name);
                $("#editEmployeeSurname").val(employee.surname);
                $("#editEmployeeTitle").val(employee.title);
                $("#editEmployeeBio").val(employee.bio);
                $("#editEmployeeID").val(employee.ID);
            }
        })
    }

    function cancelEdit(){
        $("#editEmployeeName").val("");
        $("#editEmployeeSurname").val("");
        $("#editEmployeeTitle").val("");
        $("#editEmployeeBio").val("");
        $("#editEmployeeBox").html("");
    }
</script>

<!--Load the employee / view employee-->
<script>
    function loadEmployee(ID){
        $.ajax({
            url: "/pages/employee/loadEmployee.php",
            type: "post",
            data: {'ID' : ID},
            success :  function(data){
                employee=JSON.parse(data);
                    Swal.fire({
                        title: employee.name + " " + employee.surname + " - " + employee.title,
                        imageUrl: employee.picture,
                        html: employee.bio,
                    })
            }
        })
    }
    function processEmployee(data, edit){

    }
</script>

<!--Deletes an employee from the database-->
<script>
    function deleteEmployeeFromDatabase(employeeID, clinicID){
        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove your employee from your clinic, including their potential files",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/pages/employee/post/deleteEmployee.php",
                    type: "POST",
                    data:{
                        'employeeID':employeeID,
                        'clinicID':clinicID
                    },
                    success : function(data){
                        if(data==="success") {
                            Swal.fire({title: 'Employee deleted', icon: 'success'});
                            $("#employee"+employeeID+"Row").remove();
                        }
                        else{
                            Swal.fire({title: 'An error has occurred', icon:'error'});
                            console.log(data);
                        }
                    }
                })
            }
        })

    }
</script>

<!--Add employee button-->
<script>
    var numberOfEmployees=0, employeePicutre="", employeeIncrement=0;
    //This function here is fired up when the user clicks the Add employee button.
    $("#addEmployeeButton").click(function (e) {
        $("#addEmployeeButton").attr("disabled", "disabled");
        e.preventDefault();
        $("#addEmployeeBox").append("<b>Don't forget to save your clinic when you finish adding your employees!</b>"+
            "<input type='text' id='employee" + employeeIncrement + "Name' name='employee" + employeeIncrement + "Name' placeholder='Employee name (required)' class='form-control'><br>" +
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
        $("#editClinicForm").append(inputs);
        $("#editClinicForm").append(textArea);
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
                $("#editClinicForm").append("<input type='hidden' id='employee"+employeeIncrement+"Picture' name='employee"+employeeIncrement+"Picture' value='"+data+"'>");
                $("#employees").append("<p id='"+employeeID+"'>"+nameOfEmployee+"<i class='fas fa-trash-alt' style='display: inline; color: red; margin-left:10px' onclick='deleteEmployee("+employeeID+")'></i></p>");
                $("#addEmployeeBox").text("");
                $("#employeeIncrement").val(employeeIncrement);
                numberOfEmployees++;
                employeeIncrement++
                $("#numberOfEmployees").val(numberOfEmployees);
            }
        });

    }
    //Empties the box where the user inputs employee data
    function cancelEmployee(){
        $("#addEmployeeButton").removeAttr("disabled");
        $("#addEmployeeBox").text("");
    }
    //Removes the employee the user has just saved - NOT from the database, but from the form
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
                $("#numberOfEmployees").val(numberOfEmployees);
            }
        })
    }
</script>

<!--Tagator script-->
<script>
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

<!--This bit here is responsible for adding/selecting images that will be removed-->
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

<!--This bit here yeets the clinic through the window-->
<script>
    $("#deleteClinicButton").click(function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#deleteClinicForm").submit();
            }
        })
    })


</script>
<?php 
$databaseConnection->close();
require "../../includes/footer.php";
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#employeeTable').DataTable();
    } );
</script>
</body>
</html>
