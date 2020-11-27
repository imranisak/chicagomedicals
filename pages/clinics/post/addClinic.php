<?php
require "../../../includes/database.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sendMail.php";
require "../../../includes/fileUpload.php";
require "../../../includes/recaptcha.php";
require "../../../includes/functions.php";


multipleFileUpload($msg, "image");