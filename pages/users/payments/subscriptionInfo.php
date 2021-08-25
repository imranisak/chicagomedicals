<?php
require "../../../includes/functions.php";
require "../../../includes/database.php";
$ch = curl_init();


$subscription='I-ASB67NCUH41D';
//echo loadBearerToken($databaseConnection);
$token=loadBearerToken($databaseConnection);
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-ASB67NCUH41D");
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
    else var_dump($json);
}
$databaseConnection->close();