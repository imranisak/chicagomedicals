<?php require '../../includes/flashMessages.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add user</title>
        <?php require "../../includes/header.php";?>
    </head>

    <body>
    <?php include "../../includes/navbar.php";?>
        <form action="post/register.php" method="POST">
            <input type="text" name="name" placeholder="name" required>
            <input type="text" name="surname" placeholder="surname" required>
            <input type="email" name="email" placeholder="email" required>
            <input type="password" name="password_1" placeholder="Enter password" required>
            <input type="password" name="password_2" placeholder="Repeat password" required>
            <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
            <input type="submit" value="Submit">
        </form>
        <?php if (isset($msg)) $msg->display(); ?>
        <?php require "../../includes/footer.php";?>
    </body>
</html>