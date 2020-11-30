<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if($isAdmin){
    $tag=$_GET['tag'];
    $id=$_GET['id'];
    ?>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php require "../../includes/header.php" ?>
        <title>Edit <?php echo $tag; ?></title>
    </head>
    <body>
    <?php require "../../includes/navbar.php";
    if($msg->hasMessages()) $msg->display();
    ?>
    <h3>Edit tags</h3>
    <form action="/admin/tags/post/editTags.php" method="post">
        <input type="text" placeholder="Tag" name="tag" value="<?php echo $tag;?>">
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'];?>">
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
        <input type="submit">
    </form>
    <?php require "../../includes/footer.php"?>
    </body>
    </html>

    <?php
    $databaseConnection->close();
}
else $msg->error("lol no", "/");