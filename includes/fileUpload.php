<?php
//Code used for handling file uploads. Idea is to use this file universally, for all file uploads (pics, docs etc..)
//Dir can be changed later if needed (maybe docs folder for .pdf)
/**
 * @param string $msg Must include the flash message variable, $msg
 * @param string $type Defines what kind of file (image, or document) it is, so it knows where to save - yes, I know there's a function for this
 * @return string Returns the file name
 * @return bool Returns false if something goes wrong.
 */
function proccessFile($msg, $type)
{
    if ($type=="image") $targetDir = "/media/pictures";
    elseif ($type=="document") $targetDir="/media/documents";
    else $targetDir="/media";
    $fileName = $targetDir ."/".date("Y-m-d-H-i-s")."_". basename($_FILES["file"]["name"]);//Adds the current date and time with seconds to the file name, so you can't make a duplicate
    $uploadOK = true;
    $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check == false) $msg->error("File is not a supported format - .jpg, .jpeg, .png are supported.");
    } else $msg->error("File upload error. Pleas try again.");
    if (file_exists($fileName)) $msg->error("File with the same name already exists. Please try again.");
    if ($_FILES["file"]["size"] > 1000000) $msg->error("File must be under 1MB");
//Can be changed later - for ex., if documents are uploaded, only .pdf, .docx or such are supported.
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") $msg->error("File is not a supported format. .jpg, .jpeg, .png are supported.");
    if (!$msg->hasMessages($msg::ERROR)){
        move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$fileName);
        return $fileName;
    }
else return false;//If any errors are gives, turns it false. This can be useful later on.
}