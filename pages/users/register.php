<?php require '../../includes/flashMessages.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add user</title>
        <?php require "../../includes/header.php";?>
    </head>

    <body>
    <?php include "../../includes/navbar.php";?>
        <form action="post/register.php" method="POST" enctype="multipart/form-data">
            <label> Name:
                <input type="text" name="name" placeholder="Name" required>
            </label><br><br>
            <label> Surname:
                <input type="text" name="surname" placeholder="Surname" required>
            </label><br><br>
            <label> E-mail:
                <input type="email" name="email" placeholder="E-mail" required>
            </label><br><br>
            <label>Profile picture:<br>
                <input type="file" name="file" id="picture">
            </label><br><sub>Max file size: 1MB<br>.jpg .jpeg .png only allowed</sub><br><br>
            <label> Enter password:
                <input type="password" name="password_1" placeholder="Enter password" required>
            </label><br><br>
            <label> Repeat password:
                <input type="password" name="password_2" placeholder="Repeat password" required>
            </label>
            <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
            <input type="submit" value="submit" name="submit">
        </form>
        <?php if (isset($msg)) $msg->display(); ?>
        <?php require "../../includes/footer.php";?>
    </body>
</html>