<?php
$ch = curl_init();

$client= 'ARddlugswaQNxof1Gj1-Tgrafo_dqqsUu8Zjxepf-ESCG7lbt46UZmGoWcgJT5_6BtAuY08Q-WVnwAmZ';
$secret= 'EDXl_lLcOTH947duwu2dP9sj1RVkitigBIp8HuUOWAc0sRI21uSebWtfiMxh92TDmYEEjIpLVQyPsN9m';
$subscription='I-ASB67NCUH41D';
$token="A21AAIq0Ehqnv1iEQMtoFPCdVz0ZlLpEkrRqoDTsqCm9L_U8BUXRBed5ehdEXPm05FvWI_8YipKSJVuTPiQ0VnUkwZIsk5k8Q";
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
    if(!empty($json->error) && $json->error) echo "Whoops";
    else echo $json->status;
}