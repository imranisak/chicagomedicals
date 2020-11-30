<?php
if (!session_id()) @session_start();
$_SESSION['csrf_token']=bin2hex(openssl_random_pseudo_bytes(24));
