<?php
//Load employee, from its ID
if(isset($_POST['ID'])) $employeeID=filter_var($_POST['ID'], FILTER_SANITIZE_NUMBER_INT);
else die("no ID");

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
