<?php
require "../../../includes/database.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/fileUpload.php";
require "../../../includes/recaptcha.php";
require "../../../includes/functions.php";
if($_SESSION['csrf_token']!=$_POST['token']) $msg->error('Invalid token.', '/');

multipleFileUpload($msg, "image");