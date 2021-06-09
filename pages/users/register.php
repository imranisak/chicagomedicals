<?php require '../../includes/flashMessages.php'; ?>
<?php require '../../includes/token.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register to Chicago Medicals</title>
        <?php require "../../includes/header.php";?>
    </head>

    <body>
    <?php include "../../includes/navbar.php";?>
    <?php if (isset($msg)) $msg->display(); ?>
    <script
            src="https://www.paypal.com/sdk/js?client-id=ARddlugswaQNxof1Gj1-Tgrafo_dqqsUu8Zjxepf-ESCG7lbt46UZmGoWcgJT5_6BtAuY08Q-WVnwAmZ&vault=true">
    </script>
        <form action="post/register.php" method="POST" enctype="multipart/form-data">
            <label> Name:
                <input type="text" name="name" placeholder="Name" required autocomplete="given-name">
            </label><br><br>
            <label> Surname:
                <input type="text" name="surname" placeholder="Surname" required autocomplete="family-name">
            </label><br><br>
            <label> E-mail:
                <input type="email" name="email" placeholder="E-mail" required>
            </label><br><br>
            <label>Profile picture:<br>
                <input type="file" name="file" id="picture">
            </label><br><sub>Max file size: 1MB<br>.jpg .jpeg .png .jfif only allowed</sub><br><br>
            <label> Enter password:
                <input type="password" name="password_1" placeholder="Enter password" required>
            </label><br><br>
            <label> Repeat password:
                <input type="password" name="password_2" placeholder="Repeat password" required>
            </label>
            <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token']; ?>" required>
            <div class="g-recaptcha" data-sitekey="6LfzjcAZAAAAABoWk_NvnAVnGzhHdJ8xOKIuVYYr"></div>
            <input type="submit" value="Register!" name="submit">
        </form>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({

            createSubscription: function(data, actions) {

                return actions.subscription.create({

                    'plan_id': 'P-2G481811JL2768614MANKLFI'

                });

            },


            onApprove: function(data, actions) {
                alert('You have successfully created subscription ' + data.subscriptionID);
            },
            onCancel: function (data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Canceled',
                    text: 'You have canceled the creation of your subscription.'
                })
            },
            onError: function (err) {
                console.error('error from the onError callback', err);
            }


        }).render('#paypal-button-container');
    </script>
        <?php require "../../includes/footer.php";?>
    </body>
</html>