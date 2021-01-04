<?php
require "../../includes/database.php";
require "../../includes/sessionInfo.php";
require "../../includes/flashMessages.php";
require "../../includes/token.php";
if($isAdmin){
    $loadTagsFromDB="SELECT * from tags ORDER BY tag ASC";
    $tags=$databaseConnection->query($loadTagsFromDB);
?>

    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php require "../../includes/header.php" ?>
        <title>Add & edit tags</title>
    </head>
    <body>
    <?php require "../../includes/navbar.php";
    if($msg->hasMessages()) $msg->display();
    ?>
    <h3>Add and edit tags</h3>
    <form action="post/tags.php" method="post">
        <input type="text" placeholder="Tag" name="tag">
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'] ?>">
        <input type="submit">
    </form>
    <table>
        <thead>
            <tr>
                <th>Tag</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($tags as $tag){
                ?><tr><?php
                $displayTag=$tag['tag'];
                $id=$tag['ID'];
                echo "<td>".ucfirst($displayTag)."</td>";
                echo "<td><a href='editTags.php/?tag=$displayTag&id=$id'><i class='fas fa-edit'></i></a></td>";
                ?></tr><?php
            }
            ?>
        </tbody>
    </table>
    <?php require "../../includes/footer.php"?>
    </body>
    </html>

<?php
$databaseConnection->close();
}
else $msg->error("lol no", "/");