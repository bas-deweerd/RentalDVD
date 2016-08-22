<!DOCTYPE html>
<html>
    <head>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php
        include('php_utils/LoginUtil.php');
        ?>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style/style.css">
        <title>Log in</title>
    </head>
    <body>
        <div id="container">
            <header>
            </header>
            <section>
                <form id='login_form' action="" method="post">
                    <?php
                    if (LoginUtils::isLoggedIn()) {
                        //header('Location: ../html/filmlist.php');
                        br();
                        echo '<input name ="logout" type="submit" value="Logout">';
                        br();
                        
                    } else {
                        echo '<label class="label_login_form">Username: </label> <input type = "text" name = "username" placeholder = "username"><br /><br />';
                        echo '<label class="label_login_form">Password: </label> <input id="password_input" type = "password" name = "password" placeholder = "password "> <br />';
                        echo '<input class="login_button" name = "login" type = "submit" value = "Login"><br />';
                        echo DatabaseUtils::addUser('herm', 'password');
                    }

                    if (strlen($error) > 0) {
                        echo '<br />';
                        echo $error;
                    }
                    ?>
                </form>

            </section>
            <nav>
                <ul>
                    <li>
                        <ul><a href='' "localhost:8000"></a></ul>
                    </li>
                </ul>
            </nav>
            <footer>
                <text> Copyright</text>
            </footer>
        </div>
    </body>
</html>