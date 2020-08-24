<?php
if(isset($_POST['email'])) $email=$_POST['email'];
else echo "no email!";
if(isset($_POST['password'])) $password=$_POST['password'];
else echo "No password!";
require $_SERVER['DOCUMENT_ROOT']."/includes/database.php";
$sql="SELECT id, name, surname, password, email FROM users WHERE email='$email'";
$password=md5($password);
$user=$databaseConnection->query($sql);
if($user->num_rows>0){
    while($row=$user->fetch_assoc()){
        //echo $row['surname'];
        if($row['password']==$password) {
            session_start();
            $_SESSION['id']=$row['id'];
            $_SESSION['name']=$row['name'];
            $_SESSION['surname']=$row['surname'];
            $_SESSION['email']=$row['email'];
            $_SESSION['isLoggedIn']=true;
            $location=$_SERVER['DOCUMENT_ROOT'].'/index.php';
            echo $location;
            //die();
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