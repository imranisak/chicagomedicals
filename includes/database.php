<?php
$server="localhost";
$dbuser="root";
$dbpass="";
$databaseConnection= new mysqli($server, $dbuser, $dbpass);

if($databaseConnection->connect_error){
    die("Database error: ". $databaseConnection->connect_error);
}

echo "yey database!";