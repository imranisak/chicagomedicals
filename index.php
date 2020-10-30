<?php 
require 'includes/flashmessages.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chicago Medicals</title>
    <?php require 'includes/header.php'; ?>
</head>
<body>
<?php include "includes/navbar.php";?>
    <?php if (isset($msg)) $msg->display(); ?>
    <h1>Chicago Medicals</h1>
    <?php require 'includes/footer.php'; ?>
</body>
</html>