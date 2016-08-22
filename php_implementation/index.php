<?php
if (!isset($_GET['c'])) { $_GET['c']=0; }
if (isset($_GET['c'])) 
	{ 
		$choice = $_GET['c'];	
		switch ($choice) { 
  			case 0 : $titel="Filmlijst"; break;
  			case 1 : $titel="History"; break;
  			case 2 : $titel="Organisation"; break;
  			case 3 : $titel="User manage"; break;
  			case 4 : $titel="Film details"; break;
                        case 5 : $titel="Reservation"; break;
  			default : $titel="Welcome"; break;
		}
	}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
        include_once($_SERVER['DOCUMENT_ROOT'] . '/php_includes/menu.php');


        
        if (ISSET($_GET['c'])) {
            $menu = $_GET['c'];
        } else {
            $menu = 0;
        }
        ?>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style/style.css">
        <title><?php echo $titel; ?></title>
    </head>
    <body>
        <div id="container">
            <header>
                <div id="search">
                    <?php
                    if(LoginUtils::isLoggedIn()) {
                        ?>
                        <form action="c?=0" method="post">
                        <input type="text" name="search">
                        <input id="search_button" type="image" src="../images/search_button.png">
                    </form>
                    <?php
                    }
                    ?>
                    
                </div>
            </header>
            <div id="main">
                <nav>
                    <?php
                    Menu::printMenu();
                    ?>
                </nav>
                <section>
                    
                    <?php

                    if (!LoginUtils::isLoggedIn()) {
                        include 'html_switchmenu/login.php';
                    } else {
                        switch ($menu) {
                            case 0:
                                $title = 'Filmlist';
                                include 'html_switchmenu/filmlist.php';

                                break;
                            case 1;
                                $title = 'History';
                                include 'html_switchmenu/history.php';

                                break;
                            case 2:
                                LoginUtils::logout();
                                break;
                            case 3:
                                include 'html_switchmenu/usermanage.php';
                                $title = 'Manage users';
                                break;
                            case 4:
                                include 'html_switchmenu/filmdetails.php';
                                $title = 'Film details';
                                break;
                            case 5:
                                include 'html_switchmenu/reservering.php';
                                $title = 'Reservation';
                                break;
                            case 6:
                                include 'html_switchmenu/addfilm.php';
                                break;
                            default:
                                0;
                                break;
                        }
                    }
                    ?>
                </section>
            </div>


        </div>
        <footer>
        </footer>
    </body>
</html>