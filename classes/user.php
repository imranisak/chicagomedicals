<?php
if (!session_id()) @session_start();//Why is this here?
class user{
    public $name, $surname, $email, $password, $dateAdded, $saved=false, $profilePicture;
    function __construct($nameInput, $surnameInput, $emailInput, $passwordInput, $dateAddedInput, $profilePictureInput){
        $this->name=$nameInput;
        $this->surname=$surnameInput;
        $this->email=$emailInput;
        $this->password=$passwordInput;
        $this->dateAdded=$dateAddedInput;
        $this->profilePicture=$profilePictureInput;
    }
    function getName(){
        return $this->name;
    }
    function addToDatabase($connection){
        $sql="INSERT INTO  users (name, surname, email, password, dateAdded, profilePicture) VALUES ('$this->name', '$this->surname', '$this->email', '$this->password' ,'$this->dateAdded', '$this->profilePicture')";
        if($connection->query($sql)===TRUE) $this->saved=true;
        else $this->saved=false;
    }
    function createVerification($connection){
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
        $hash=md5(generateRandomString());
        $sql="INSERT INTO verifications (hash, userEmail) VALUES ('$hash', '$this->email')";
        if($connection->query($sql)==TRUE){
            $nameAndSurname=$this->name." ".$this->surname;
            try{
                require $_SERVER['DOCUMENT_ROOT']."/includes/sendMail.php";
                if(!isset($mail)) $mail=new PHPMailer(true);
                $mail->addAddress($this->email, $nameAndSurname);     // Add a recipient
                $mail->Subject = 'User verification';
                $mail->Body    = "<a href='http://".$_SERVER['HTTP_HOST']."/pages/users/verify.php/?hash=$hash&email=$this->email' target='_blank'>Verify email here</a>";
                $mail->send();
            }
            //TODO Add messages in case verification message could not be sent.
            catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        else{
            echo $connection->error;
            echo "Could not add verification!";
        }
    }
}