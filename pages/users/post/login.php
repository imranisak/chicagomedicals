<?php
require $_SERVER['DOCUMENT_ROOT']."/includes/database.php";
require $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';

if(isset($_POST['email'])){
$email=$_POST['email'];
$email=filterInput($email);
}
else echo "No email!";
if(isset($_POST['password'])){
    $password=$_POST['password'];
    $password=filterInput($password);
} 
else echo "No password!";
$sql="SELECT id, name, surname, password, email FROM users WHERE email='$email'";
$user=$databaseConnection->query($sql);
if($user->num_rows>0){
    while($row=$user->fetch_assoc()){
        echo $password;
        if(password_verify($password, $row['password'])){
            session_start();
            $_SESSION['id']=$row['id'];
            $_SESSION['name']=$row['name'];
            $_SESSION['surname']=$row['surname'];
            $_SESSION['email']=$row['email'];
            $_SESSION['isLoggedIn']=true;
            $location=$_SERVER['DOCUMENT_ROOT'].'/index.php';
            header("Location: /index.php");
        }
        else{
            echo "Incorect password";
            die();
        }
    }
}
else {
    echo 'No email in database.'; ?><a href=<?php $_SERVER['DOCUMENT_ROOT'];?>"/pages/users/register.php">Plz register</a><?php
}