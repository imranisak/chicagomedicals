<?php
require "../../../includes/database.php";
require "../../../includes/sendMail.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sessionInfo.php";
echo "DING DONG";
header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'subscriptions.log', $req_dump );
$json='{"id":"WH-6JT50488G18568604-1T8860702T2274417","event_version":"1.0","create_time":"2021-06-22T11:18:01.983Z","resource_type":"subscription","resource_version":"2.0","event_type":"BILLING.SUBSCRIPTION.CREATED","summary":"Subscription created","resource":{"start_time":"2021-06-22T11:18:01Z","quantity":"1","create_time":"2021-06-22T11:18:01Z","links":[{"href":"https://www.sandbox.paypal.com/webapps/billing/subscriptions?ba_token=BA-2HJ76048AB027345R","rel":"approve","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/billing/subscriptions/I-BWWDFP6BC7EA","rel":"edit","method":"PATCH"},{"href":"https://api.sandbox.paypal.com/v1/billing/subscriptions/I-BWWDFP6BC7EA","rel":"self","method":"GET"}],"id":"I-BWWDFP6BC7EA","plan_overridden":false,"plan_id":"P-03196537NY788235NMDDUH2A","status":"APPROVAL_PENDING"},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-6JT50488G18568604-1T8860702T2274417","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-6JT50488G18568604-1T8860702T2274417/resend","rel":"resend","method":"POST"}]}';
//echo $json;
//Gather data
$jsonFormated=json_decode($json, true);
$resources=$jsonFormated['resource'];
$subscriptionID=$resources['id'];
$userID="test";
echo $id;

//Save subscription

//Update user info (set hasPremium to 1)

//Send email to user