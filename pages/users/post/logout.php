<?php
require $_SERVER["DOCUMENT_ROOT"]."/includes/flashMessages.php";
session_unset();
session_destroy();
//echo "Logged out";
//die();
header("Location: /index.php");
//$msg->success("You have been logged out.", "/");