<?php
//Load employee, from its ID
if(isset($_GET['ID'])) $employeeID=filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);
else die();

require "../../includes/database.php";
$SQLloadEmployee="SELECT * FROM employees WHERE ID='$employeeID'";
$employee=$databaseConnection->query($SQLloadEmployee);
if(!$employee){
    echo $databaseConnection->error;
    $databaseConnection->close();
    die();
}
//Send the data to the frontend!
$employee=$employee->fetch_assoc();
echo json_encode($employee);
