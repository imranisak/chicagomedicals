<?php
require "../../../includes/flashMessages.php";
require "../../../includes/sessionInfo.php";
if ($_SESSION["csrf_token"]!=$_POST['token']) $msg->error("Invalid token.", "/");
if($isAdmin) {
    if (isset($_POST['tag']) && $_POST['id']) {
        require "../../../includes/database.php";
        $tag = strtolower($_POST['tag']);
        $id = $_POST['id'];
        $insertTagToDatabase = "UPDATE tags SET tag = '$tag' WHERE ID = '$id'";
        if ($databaseConnection->query($insertTagToDatabase)) {
            $databaseConnection->close();
            $msg->success("Tag edited.", "../tags.php");
        } else {
            $databaseConnection->close();
            $msg->error("Error, $databaseConnection->error", "../tags.php");
        }
    } else {
        $databaseConnection->close();
        $msg->error("You did an whoopise", '../tags.php');
    }
}else{
    $databaseConnection->close();
    $msg->error("lol no", "/");
}