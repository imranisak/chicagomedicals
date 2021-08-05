<?php
require "../includes/sessionInfo.php";
require "../includes/flashMessages.php";
if(!$isAdmin) $msg->error("You must be admin to access this!");
require "../includes/database.php";

// Loads all files to an array
$filesInFolder=[];
$filesInDB=[];
$dir = $_SERVER['DOCUMENT_ROOT']."/media/pictures";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            //echo "filename: $file"."<br>";
            array_push($filesInFolder, $file);
        }
        closedir($dh);
    }
}

//Load profile pictures
$SQLloadProfilePictures="SELECT profilePicture FROM users";
$profilePictures=$databaseConnection->query($SQLloadProfilePictures);
if(!$profilePictures) $msg->error("Error loading profile pics!");
else foreach ($profilePictures as $profilePicture) array_push($filesInDB, $profilePicture['profilePicture']);
//Remove employee pictures
$SQLloadEmployeePictures="SELECT picture FROM employees";
$employeePictures=$databaseConnection->query($SQLloadEmployeePictures);
if(!$employeePictures) $msg->error("Error loading employee pictures!");
else foreach ($employeePictures as $employeePicture) array_push($filesInDB, $employeePicture['picture']);
//Load clinic images
$SQLloadClinicImages="SELECT images FROM clinics";
$clinicImages=$databaseConnection->query($SQLloadClinicImages);
if(!$clinicImages) $msg->error("Error loading clinic images!");
else {
    foreach ($clinicImages as $clinicImage){
        $arrayOfClinicImages=unserialize($clinicImage['images']);
        foreach ($arrayOfClinicImages as $clinicImageTemp) array_push($filesInDB, $clinicImageTemp);
    }
}
//var_dump($filesInDB);
foreach ($filesInFolder as $fileInFolder) {
    $fileInFolder="/media/pictures/".$fileInFolder;
    if(!in_array($fileInFolder, $filesInDB)) {
        if($fileInFolder!="/media/pictures/." && $fileInFolder!="/media/pictures/..") {
            if(unlink($_SERVER['DOCUMENT_ROOT'].$fileInFolder))  echo $fileInFolder." deleted!<br>";
            //echo $fileInFolder."<br>";
        }
    }
}