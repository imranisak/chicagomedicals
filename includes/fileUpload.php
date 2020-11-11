<?php
//Code used for handling file uploads. Idea is to use this file universally, for all file uploads (pics, docs etc..)
//Dir can be changed later if needed (maybe docs folder for .pdf)
$hasFile=false;
if(empty($_FILES["file"])) {
    $targetDir = "/media/pictures";
    $fileName = $targetDir . basename($_FILES["file"]["name"]) . date("Y-m-d-H-i-s");//Adds the current date and time with seconds to the file name, so you can't make a duplicate
    $uploadOK = true;
    $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    echo $fileType;
// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check == false) $msg->error("File is not a supported format - .jpg, .jpeg, .png are supported.");
    } else $msg->error("File upload error. Pleas try again.");
    if (file_exists($fileName)) $msg->error("File with the same name already exists. Please try again.");
    if ($_FILES["picture"]["size"] > 1000000) $msg->error("File must be under 2MB");
//Can be changed later - for ex., if documents are uploaded, only .pdf, .docx or such are supported.
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") $msg->error("File is not a supported format. .jpg, .jpeg, .png are supported.");
    if (!$msg->hasMessages($msg::ERROR)) $hasFile=true;
        else $uploadOK = false;//If any errors are gives, turns it false. This can be useful later on.
}