<!DOCTYPE html>
<html>
    <head>
        <title>Add user</title>
        <?php require $_SERVER['DOCUMENT_ROOT']."/includes/header.php"; ?>
    </head>

    <body>
        <form action="post/add.php" method="POST">
            <input type="text" name="name" placeholder="name">
            <input type="text" name="surname" placeholder="surname">
            <input type="email" name="email" placeholder="email">
            <input type="password" name="password_1" placeholder="Enter password">
            <input type="password" name="password_2" placeholder="Repeat password">
            <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>