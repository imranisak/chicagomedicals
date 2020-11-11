<?php
//This file is loaded wherever flashmessages are needed - which is pretty much everywhere.
//Since I am lazy to type these three lines of code everywhere, I'll just include this file
//wherever flash messages are needed *taps forehead* *big brain meme*
if(!isset($msg)) {
    if (!session_id()) @session_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/plasticbrain/php-flash-messages/src/FlashMessages.php';
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
}
?>