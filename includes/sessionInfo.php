<?php 
    $isLoggedIn=false;
    if (!session_id()) @session_start();
    if(isset($_SESSION["isLoggedIn"])){
        $isLoggedIn=true;
        $name=$_SESSION['name'];
        $surname=$_SESSION['surname'];
        $email=$_SESSION['email'];
    }
?>