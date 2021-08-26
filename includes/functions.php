<?php
declare(strict_types=1);
function filterInput($input){
    //Removes invalid/dangerous stuff from input - like slashes, and HTML chars
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function whitespace($input){
    //Removes whitespaces
    if (!preg_match("/^[a-zA-Z-' ]*$/",$input)) return true;
    else return false;
}
/**
 * @param object $databaseConnection Database Connection
 * @param bool $hasPremium Has Premium
 * @param string $msg Flash Messages
 */
function saveEmployees($databaseConnection, $hasPremium, $msg, $clinicID=0){
    if($hasPremium) {
        //Gets the ID of the clinic that was just saved
        if($clinicID==0) $clinicID = $databaseConnection->insert_id;
        if (isset($_POST['numberOfEmployees'])) $numberOfEmployees = filter_var($_POST['numberOfEmployees'], FILTER_SANITIZE_NUMBER_INT);
        if ($numberOfEmployees > 100 || $numberOfEmployees < 0) {
            $databaseConnection->close();
            $msg->error("What are you doing?", "../addClinic.php");
        }
        if (isset($_POST['employeeIncrement'])) $employeeIncrement = filter_var($_POST['employeeIncrement'], FILTER_SANITIZE_NUMBER_INT);
        if ($employeeIncrement > 0 || $employeeIncrement < 100) {
            for ($i = 0; $i <= $employeeIncrement; $i++) {
                if (isset($_POST['employee' . $i . 'Name'])) {
                    //Name
                    $employeeName = filter_var($_POST['employee' . $i . 'Name'], FILTER_SANITIZE_STRING);
                    //Surname
                    if (isset($_POST['employee' . $i . 'Surname'])) $employeeSurname = filter_var($_POST['employee' . $i . 'Surname']);
                    else $employeeSurname = "";
                    //Title
                    if (isset($_POST['employee' . $i . 'Title'])) $employeeTitle = filter_var($_POST['employee' . $i . 'Title']);
                    else $employeeTitle = "";
                    //Bio
                    if (isset($_POST['employee' . $i . 'Bio'])) $employeeBio = filter_var($_POST['employee' . $i . 'Bio']);
                    else $employeeBio = "";
                    //Pic
                    if (isset($_POST['employee' . $i . 'Picture'])) $employeePicture = filter_var($_POST['employee' . $i . 'Picture']);
                    else $employeePicture = "/media/pictures/profilepicture.jpg";
                    //Save the employee
                    $SQLsaveEmployee = "INSERT INTO employees (clinicID, name, surname, picture, title, bio) VALUES ('$clinicID', '$employeeName', '$employeeSurname', '$employeePicture', '$employeeTitle', '$employeeBio')";
                    $employee = $databaseConnection->query($SQLsaveEmployee);
                    if (!$employee) $msg->error("Error saving employee " . $employeeName);
                }
            }
        }
    }
}

/**
 * @param object $databaseConnection Database connection
 * @param object $msg Flash Message
 * @param integer $clinicID Employee UD
 * @return bool
 */
function deleteEmployees($databaseConnection, $msg, $clinicID){
    $SQLloadEmployeeImages="SELECT picture FROM employees WHERE clinicID='$clinicID'";
    $employeeImages=$databaseConnection->query($SQLloadEmployeeImages);
    if(!$employeeImages) $msg->error("Error loading employee images!");
    if($employeeImages->num_rows!=0 && $employeeImages->num_rows>=0){
        foreach ($employeeImages as $employeeImage) {
            $employeeImage=$employeeImage['picture'];
            if($employeeImage!="/media/pictures/profilepicture.jpg"){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$employeeImage)) unlink($_SERVER['DOCUMENT_ROOT'].$employeeImage);
            }
        }
    }
    $SQLdeleteEmployees="DELETE FROM employees WHERE clinicID='$clinicID'";
    $deleteEmployees=$databaseConnection->query($SQLdeleteEmployees);
    if(!$deleteEmployees) return false;
    else return true;
}

function safeEncrypt(string $message, string $key): string
{
    if (mb_strlen($key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        throw new RangeException('Key is not the correct size (must be 32 bytes).');
    }
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    $cipher = base64_encode(
        $nonce.
        sodium_crypto_secretbox(
            $message,
            $nonce,
            $key
        )
    );
    sodium_memzero($message);
    sodium_memzero($key);
    return $cipher;
}

function generateBearerToken($db){
    $key = ",>47P2'v<7X5=YqHU=PUhz4xGN,KhLVv";//random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    $ch = curl_init();
    $client= 'ARddlugswaQNxof1Gj1-Tgrafo_dqqsUu8Zjxepf-ESCG7lbt46UZmGoWcgJT5_6BtAuY08Q-WVnwAmZ';
    $secret= 'EDXl_lLcOTH947duwu2dP9sj1RVkitigBIp8HuUOWAc0sRI21uSebWtfiMxh92TDmYEEjIpLVQyPsN9m';
    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $client.":".$secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Accept-Language: en_US"
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    if(empty($result))die("Error: No response.");
    else
    {
        $json = json_decode($result);
        if(!empty($json->error) && $json->error) echo "Whoops";
        $token=$json->access_token;
        $expiresIn=$json->expires_in;
        $tokenExpireTimeInSeconds=time()+$expiresIn;//At what Unix epoch / timestamp the token will expire
    }
    $ciphertext = safeEncrypt($token, $key);//Encrypted token
    $tokenInfo=[
        'token'=>$ciphertext,
        'expiresIn'=>$tokenExpireTimeInSeconds
    ];
    $tokenInfo=serialize($tokenInfo);
    $SQLsaveToken="UPDATE options SET value='$tokenInfo' WHERE `option`='bearerToken'";
    $saveToken=$db->query($SQLsaveToken);
    if($saveToken) return true;
    else return false;
}

function loadBearerToken($db){
    $SQLloadToken="SELECT * FROM options WHERE `option`='bearerToken'";
    $token=$db->query($SQLloadToken);
    if($token){
        $tokenArray=$token->fetch_assoc();
        $tokenArray=unserialize($tokenArray['value']);
        $token=$tokenArray['token'];
        $secondsWhenTheTokenExpires=$tokenArray['expiresIn'];
        if($secondsWhenTheTokenExpires<=time()){
            generateBearerToken($db);
            loadBearerToken($db);
            echo "new token generated!";
            $SQLloadToken="SELECT * FROM options WHERE `option`='bearerToken'";
            $token=$db->query($SQLloadToken);
            $tokenArray=$token->fetch_assoc();
            $tokenArray=unserialize($tokenArray['value']);
            $token=$tokenArray['token'];
        }
        $key=",>47P2'v<7X5=YqHU=PUhz4xGN,KhLVv";
        $decoded = base64_decode($token);
        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $key
        );
        if (!is_string($plain)) {
            throw new Exception('Invalid MAC');
        }
        sodium_memzero($ciphertext);
        sodium_memzero($key);
        return $plain;
    }
    else return false;
}


/**
 * @param object $db The database - of course
 * @param string $subscription ID of the subscription I-XXXXXXXXXXXX
 * @param string $token The bearer token
 * @param string $action What to do (suspend, activate, delete etc...)
 */
function subscriptionAction($db, $subscription, $token, $action){
    if($action=='') $url="https://api-m.sandbox.paypal.com/v1/billing/subscriptions/".$subscription;
    else $url="https://api-m.sandbox.paypal.com/v1/billing/subscriptions/".$subscription."/".$action;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer ".$token
    ));
    $result = curl_exec($ch);

    curl_close($ch);
    if(empty($result))die("Error: No response.");
    else
    {
        $json = json_decode($result);
        if(!empty($json->error) && $json->error) echo $json->error;
        return $json;
    }
}