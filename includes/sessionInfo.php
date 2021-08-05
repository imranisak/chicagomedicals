<?php 
$isLoggedIn=false;
$isAdmin=false;
if (!session_id()) @session_start();
if(isset($_SESSION["isLoggedIn"])){
    $isLoggedIn=true;
    $name=$_SESSION['name'];
    $surname=$_SESSION['surname'];
    $id=$_SESSION['id'];
    $email=$_SESSION['email'];
    $profilePicture=$_SESSION['profilePicture'];
    $role=$_SESSION['role'];
    $isVerified=$_SESSION['verified'];
    $hasPremium=$_SESSION['hasPremium'];
    $hasClinics=$_SESSION['hasClinic'];
    if($role==="admin") $isAdmin=true;
    else $isAdmin=false;
} else{
    $isLoggedIn=false;
    $role='visitor';
}