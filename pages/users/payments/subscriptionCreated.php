<?php
require "../../../includes/database.php";
require "../../../includes/sendMail.php";
require "../../../includes/flashMessages.php";
require "../../../includes/sessionInfo.php";
echo "DING DONG";
//Process data
header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'subscriptions.log', $req_dump );

//Gather data
$jsonFormated=json_decode($req_dump, true);
$resources=$jsonFormated['resource'];
$subscriptionID=$resources['id'];
$startTime=$resources['start_time'];
//Save subscription
$SQLinsertSubsscription="INSERT INTO subscriptions (subscriptionID, dateCreted) VALUES ('$subscriptionID', '$startTime')";
$insertSubscription=$databaseConnection->query($SQLinsertSubsscription);
if(!$insertSubscription){
    echo $databaseConnection->error;
    $databaseConnection->close();
    http_response_code(500);
    die();
}
else{
    $databaseConnection->close();
    http_response_code(200);
    die();
}
//Update user info (set hasPremium to 1)
//This bit here is done from the user edit page, an AJAX request is sent.
//Email is also sent there