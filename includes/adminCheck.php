<?php
if($isLoggedIn){
if($role!='admin') $msg->error("lol no", "/index.php");
} else $msg->error("Must be logged in as admin.", "/index.php");