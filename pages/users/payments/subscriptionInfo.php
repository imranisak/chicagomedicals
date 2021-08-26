<?php
require "../../../includes/functions.php";
require "../../../includes/database.php";


$subscription='I-ASB67NCUH41D';
$token=loadBearerToken($databaseConnection);
$res=subscriptionAction($databaseConnection, $subscription, $token, '');
var_dump($res);
$databaseConnection->close();