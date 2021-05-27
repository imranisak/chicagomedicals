<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if(!$isAdmin){
    $databaseConnection->close();
    $msg->error("Must be logged in as admin!", "/");
}
$SQLloadUsers="SELECT * FROM users";
$users=$databaseConnection->query($SQLloadUsers);
if(!$users) $msg->error("Error loading users.");
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All users</title>
    <?php require "../../includes/header.php"; ?>
</head>
<body>
<?php require "../../includes/navbar.php";
if($msg->hasMessages()) $msg->display();
?>

<h3>Users</h3>
<div class="col-md-10">
    <table id="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Email</th>
                <th>Verified</th>
                <th>Has clinic</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($users as $user){
                $userID=$user['ID'];
                $row="<tr>";
                $userName=$user['name'];
                $userSurname=$user['surname'];
                $userHasClinic=$user['hasClinic'];
                $userIsVerified=$user['verified'];
                $userEmail=$user['email'];
                
                $row.="<td>$userName</td>";
                $row.="<td>$userSurname</td>";
                $row.="<td>$userEmail</td>";

                if($userIsVerified) $row.="<td><i class='fas fa-check'></i></td>";
                else $row.="<td><i class='fas fa-times'></i></td>";

                if($userHasClinic) $row.="<td><i class='fas fa-check'></i></td>";
                else $row.="<td><i class='fas fa-times'></i></td>";

                $actions="<td>";
                $actions.="<a href='/pages/users/user.php?ID=$userID' target='_blank'><i class='fas fa-user'></i></a> | ";
                $actions.="<i class='fas fa-user-times' onclick='deleteUser($userID)'></i></a>";
                $actions.="</td>";
                $row.=$actions;
                $row.="</td>";
                echo $row;
            }
            ?>
        </tbody>
    </table>
</div>
<form action="post/deleteUser.php" method="post" id="deleteUserForm" name="deleteUserForm">
    <input type="hidden" value="" name="deleteUserID" id="deleteUserID">
</form>
<?php require "../../includes/footer.php"; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#table').DataTable();
    } );

    function deleteUser(ID){
        Swal.fire({
            title: 'Delete user?',
            text: "Are you sure? User's pictures, clinics, comments etc. will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete the user!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#deleteUserID").val(ID);
                $("#deleteUserForm").submit();
            }
        })
    }
</script>
<?php $databaseConnection->close(); ?>
</body>
</html>
