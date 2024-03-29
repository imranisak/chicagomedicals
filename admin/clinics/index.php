<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if(!$isAdmin){
    $databaseConnection->close();
    $msg->error("Must be logged in as admin!", "/");
}
$SQLselectClinics="SELECT * from clinics ORDER BY approved ASC";
if($databaseConnection->query($SQLselectClinics)) $clinics=$databaseConnection->query($SQLselectClinics);
else echo $databaseConnection->error;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review clinics</title>

    <?php require "../../includes/header.php"; ?>
</head>
<body>
    <?php require "../../includes/navbar.php" ?>
    
<h3>Clinics</h3>
<?php if($msg->hasMessages())$msg->display(); ?>
<div class="col-md-10">


<table id="table">
    <thead>
        <tr>
            <th>
                Clinic name
            </th>
            <th>
                Owner
            </th>            
            <th>
                Approved
            </th>
            <th>
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($clinics as $clinic){
            $clinicID=$clinic['ID'];
            echo "<tr>"."<td>"."<a href='/admin/clinics/clinic.php?ID=".$clinic['ID']."'>".
            $clinic['name']."</a></td>".
            "<td>".$clinic['owner']."</td>";
            if($clinic['approved']) echo "<td><i class='fas fa-check'></i></td>";
            else echo "<td><i class='fas fa-times'></i></td>";
            echo "<td><i class='fas fa-trash-alt deleteClinic' ID='$clinicID'></i></td>";
            "</tr>";
        }
    ?>
    </tbody>
</table>
</div>
    <form action="../../pages/clinics/post/deleteClinic.php" ID="removeClinicForm" method="post">
        <input type="hidden" value="" name="clinicID" ID="removeClinicFormInput">
        <input type="hidden" value="<?php echo $_SESSION['csrf_token'] ?>" name="token">
    </form>
    <?php require "../../includes/footer.php"; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
    <script>
$(document).ready( function () {
    $('#table').DataTable();
} );
$(".deleteClinic").click(function (){
    var ID=$(this).attr("ID");
    Swal.fire({
        title: 'Delete clinic?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if(result.isConfirmed) {
            $("#removeClinicFormInput").attr("value", ID);
            $("#removeClinicForm").submit();
        }
    })
})
<?php $databaseConnection->close(); ?>
</script>
</body>
</html>