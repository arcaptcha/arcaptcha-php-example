<?php

    require './ArCaptcha.php';

    $ArCaptcha = new ArCaptcha('SITE_KEY', 'SECRET_KEY', ['size' => 'invisible', 'callback' => 'callback']); // dont forget to replace your site_key and secret_key

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
            <?php echo $ArCaptcha->getScript() // try to inject script into body                                        ?>
            <form method="POST" id="form" action="" name="form">
                <?php echo $ArCaptcha->getWidget() // try to inject div with arcaptcha class                                        ?>
                <input id="submit_value" type="button" name="submit_value" value="Submit"/>
            </form>



            <script>
                document.getElementById("submit_value").addEventListener('click',onClick);

                function onClick(e){
                    e.preventDefault();

                    arcaptcha.execute();
                }

                function callback(token){
                    console.log(token);
                    // submit the form
                    document.getElementById("form").submit();
                }
            </script>
        </body>
    </html>
    <?php
        }
    ?>
