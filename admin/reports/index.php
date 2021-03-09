<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../classes/clinic.php";
if(!$isAdmin || !$isLoggedIn) $msg->error("You must be logged in as admin!", '/');
$SQLloadReports="SELECT * FROM reports ORDER BY date ASC";
$reports=$databaseConnection->query($SQLloadReports);
if(!$reports) $msg->error("Error loading reports.");
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User reports</title>
    <?php require "../../includes/header.php"?>
</head>
<body>
<?php require "../../includes/navbar.php";
if ($msg->hasMessages()) $msg->display(); ?>
<h3>User reports</h3>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <table id="reportsTable">
                <thead>
                <tr>
                    <th>Report type</th>
                    <th>Reported by</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    if($reports){
                        foreach ($reports as $report){
                            ?>
                            <tr>
                            <?php
                            //Report type
                            if($report['type']=="user") echo "<td><a href='/pages/users/user.php?ID=".$report['propertyID']."'>User</a></td>";
                            else if ($report['type']=="clinic") echo "<td><a href='/pages/clinics/clinic.php?ID=".$report['propertyID']."'>Clinic</a></td>";
                            else echo "<td>Review</td>";
                            //echo "<td>".ucfirst($report['type'])."</td>";
                            //Reporter
                            if($report['reporterID']){
                                $reporterID=$report['reporterID'];
                                $SQLloadUserName="SELECT name, surname, ID FROM users WHERE ID='$reporterID'";
                                $userInfo=$databaseConnection->query($SQLloadUserName);
                                if($userInfo){
                                    $userInfo=$userInfo->fetch_assoc();
                                    echo "<td><a href='/pages/users/user?ID=".$userInfo['ID']."' target='blank'>".$userInfo['name']." ".$userInfo['surname']."</a></td>";
                                }
                            }
                            else echo "<td>Anonymous </td>";
                            //Date
                            echo "<td>".$report['date']."</td>";
                            if($report['resolved']) echo "<td style='text-align: center'><i class='fas fa-check'></i></td>";
                            else echo "<td style='text-align: center'><i class='fas fa-times'></i></td>";


                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?php
require "../../includes/footer.php";
$databaseConnection->close();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
<script>
$(document).ready( function () {
    $('#reportsTable').DataTable();
} );
</script>

</body>
</html>
