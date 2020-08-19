<?php
class user{
    public $name, $surname, $email, $password, $dateAdded;
    function __construct($nameInput, $surnameInput, $emailInput, $passwordInput, $dateAddedInput){
        $this->name=$nameInput;
        $this->surname=$surnameInput;
        $this->email=$emailInput;
        $this->password=$passwordInput;
        $this->dateAdded=$dateAddedInput;
    }
    function getName(){
        return $this->name;
    }
    function addToDatabase($connection){
        $sql="INSERT INTO  users (name, surname, email, password, dateAdded) VALUES ('$this->name', '$this->surname', '$this->email', '$this->password' ,'$this->dateAdded')";
        if($connection->query($sql)===TRUE)
        {
            $rediredtLocation=$_SERVER['DOCUMENT_ROOT'].'/pages/users/welcome.php';
            header("Location:/pages/welcome.php");
        }
        else
        {
            echo $connection->error;
        }
    }
}