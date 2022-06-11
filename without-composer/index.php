<?php

    require './ArCaptcha.php';

    $ArCaptcha = new ArCaptcha('SITE_KEY', 'SECRET_KEY'); // dont forget to replace your site_key and secret_key

?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // check form submission
        if ($ArCaptcha->verify($_POST["arcaptcha-response"])) { // verify that captcha is solved
            echo "OK!";
        } else {
            echo "FAILED!";
        }
    } else { // show form
    ?>
    <!DOCTYPE html>
        <head></head>
        <body>
            <?php echo $ArCaptcha->getScript() // try to inject script into body          ?>
            <form method="POST">
                <?php echo $ArCaptcha->getWidget() // try to inject div with arcaptcha class          ?>
                <input type="submit" value="Submit" />
            </form>
        </body>
    </html>
    <?php
        }
    ?>
