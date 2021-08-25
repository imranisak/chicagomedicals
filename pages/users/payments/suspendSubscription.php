<?php
require "../../../includes/functions.php";
require "../../../includes/database.php";
$subscriptionID='I-ASB67NCUH41D';
$token=loadBearerToken($databaseConnection);
$url = "https://api.sandbox.paypal.com/v1/billing/subscriptions/".$subscriptionID."/suspend";

$ch=curl_init($url);
$postRequest=array(
    'reason'=>'testing'
);
$postRequest=json_encode($postRequest);
$postHeader=array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$token
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeader);

$response=curl_exec($ch);
curl_close($ch);
var_dump($response);