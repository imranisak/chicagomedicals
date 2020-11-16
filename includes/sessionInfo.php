<?php 
    $isLoggedIn=false;
    if (!session_id()) @session_start();
    if(isset($_SESSION["isLoggedIn"])){
        $isLoggedIn=true;
        $name=$_SESSION['name'];
        $surname=$_SESSION['surname'];
        $id=$_SESSION['id'];
        $email=$_SESSION['email'];
        $profilePicture=$_SESSION['profilePicture'];
        $role=$_SESSION['role'];
    } else{
        $isLoggedIn=false;
        $role='visitor';
    }
?>