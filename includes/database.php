<?php
$server="localhost";
$dbuser="root";
$dbpass="";
$db="chmeds";
$databaseConnection=new mysqli($server, $dbuser, $dbpass, $db);

if($databaseConnection->connect_error){
    die("Database error: ". $databaseConnection->connect_error);
}

echo "yey database!".'<br>';