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
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($clinics as $clinic){
            //var_dump($clinic);
            echo "<tr>"."<td>"."<a href='/admin/clinics/clinic?ID=".$clinic['ID']."'>".
            $clinic['name']."</a></td>".
            "<td>".$clinic['owner']."</td>";
            if($clinic['approved']) echo "<td><i class='fas fa-check'></i></td>";
            else echo "<td><i class='fas fa-times'></i></td>";
            "</tr>";
        }
    ?>
    </tbody>
</table>
</div>

    <?php require "../../includes/footer.php"; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
    <script>
$(document).ready( function () {
    $('#table').DataTable();
} );
</script>
</body>
</html>