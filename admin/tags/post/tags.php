<?php
require "../../../includes/flashMessages.php";
require "../../../includes/sessionInfo.php";
if($_SESSION['csrf_token']!=$_POST['token']) $msg->error("Invalid token.", "/");
if($isAdmin) {
    require "../../../includes/database.php";
    if (isset($_POST['tag'])) {
        $tag = $_POST['tag'];
        $insertTagToDatabase = "INSERT INTO tags (tag) VALUES ('$tag')";
        if ($databaseConnection->query($insertTagToDatabase)) {
            $databaseConnection->close();
            $msg->success("Tag added.", "../tags.php");
        } else {
            $databaseConnection->close();
            $msg->error("Error, $databaseConnection->error", "../tags.php");
        }
    }
} else{
    $databaseConnection->close();
    $msg->error("lol no", "/");
}