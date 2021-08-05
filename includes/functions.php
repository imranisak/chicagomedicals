<?php
function filterInput($input){
    //Removes invalid/dangerous stuff from input - like slashes, and HTML chars
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function whitespace($input){
    //Removes whitespaces
    if (!preg_match("/^[a-zA-Z-' ]*$/",$input)) return true;
    else return false;
}
/**
 * @param object $databaseConnection Database Connection
 * @param bool $hasPremium Has Premium
 * @param string $msg Flash Messages
 */
function saveEmployees($databaseConnection, $hasPremium, $msg, $clinicID=0){
    if($hasPremium) {
        //Gets the ID of the clinic that was just saved
        if($clinicID==0) $clinicID = $databaseConnection->insert_id;
        if (isset($_POST['numberOfEmployees'])) $numberOfEmployees = filter_var($_POST['numberOfEmployees'], FILTER_SANITIZE_NUMBER_INT);
        if ($numberOfEmployees > 100 || $numberOfEmployees < 0) {
            $databaseConnection->close();
            $msg->error("What are you doing?", "../addClinic.php");
        }
        if (isset($_POST['employeeIncrement'])) $employeeIncrement = filter_var($_POST['employeeIncrement'], FILTER_SANITIZE_NUMBER_INT);
        if ($employeeIncrement > 0 || $employeeIncrement < 100) {
            for ($i = 0; $i <= $employeeIncrement; $i++) {
                if (isset($_POST['employee' . $i . 'Name'])) {
                    //Name
                    $employeeName = filter_var($_POST['employee' . $i . 'Name'], FILTER_SANITIZE_STRING);
                    //Surname
                    if (isset($_POST['employee' . $i . 'Surname'])) $employeeSurname = filter_var($_POST['employee' . $i . 'Surname']);
                    else $employeeSurname = "";
                    //Title
                    if (isset($_POST['employee' . $i . 'Title'])) $employeeTitle = filter_var($_POST['employee' . $i . 'Title']);
                    else $employeeTitle = "";
                    //Bio
                    if (isset($_POST['employee' . $i . 'Bio'])) $employeeBio = filter_var($_POST['employee' . $i . 'Bio']);
                    else $employeeBio = "";
                    //Pic
                    if (isset($_POST['employee' . $i . 'Picture'])) $employeePicture = filter_var($_POST['employee' . $i . 'Picture']);
                    else $employeePicture = "/media/pictures/profilepicture.jpg";
                    //Save the employee
                    $SQLsaveEmployee = "INSERT INTO employees (clinicID, name, surname, picture, title, bio) VALUES ('$clinicID', '$employeeName', '$employeeSurname', '$employeePicture', '$employeeTitle', '$employeeBio')";
                    $employee = $databaseConnection->query($SQLsaveEmployee);
                    if (!$employee) $msg->error("Error saving employee " . $employeeName);
                }
            }
        }
    }
}

/**
 * @param object $databaseConnection Database connection
 * @param object $msg Flash Message
 * @param integer $clinicID Employee UD
 * @return bool
 */
function deleteEmployees($databaseConnection, $msg, $clinicID){
    $SQLloadEmployeeImages="SELECT picture FROM employees WHERE clinicID='$clinicID'";
    $employeeImages=$databaseConnection->query($SQLloadEmployeeImages);
    if(!$employeeImages) $msg->error("Error loading employee images!");
    if($employeeImages->num_rows!=0 && $employeeImages->num_rows>=0){
        foreach ($employeeImages as $employeeImage) {
            $employeeImage=$employeeImage['picture'];
            if($employeeImage!="/media/pictures/profilepicture.jpg"){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$employeeImage)) unlink($_SERVER['DOCUMENT_ROOT'].$employeeImage);
            }
        }
    }
    $SQLdeleteEmployees="DELETE FROM employees WHERE clinicID='$clinicID'";
    $deleteEmployees=$databaseConnection->query($SQLdeleteEmployees);
    if(!$deleteEmployees) return false;
    else return true;
}