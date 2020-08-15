<?php
require '../../../classes/users.php';
$name=$_POST['name'];
$surname=$_POST['surname'];
$email=$_POST['email'];
echo $name.'<br>'.$surname.'<br>'.$email;