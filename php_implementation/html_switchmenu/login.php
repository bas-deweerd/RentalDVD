<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$browser = 'not_firefox';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
}
if (strlen(strstr($agent, 'Firefox')) > 0) {
    $browser = 'firefox';
}

echo '<form id="login_form" action="" method="post">';
if (LoginUtils::isLoggedIn()) {
    //header('Location: ../html/filmlist.php');
    echo '<br />';
    echo '<input class="logout_button" name ="logout" type = "image" src="../images/Logout_button.png">';
    echo '<br />';
} else {
    echo '<div id="login_form_div">';
    echo '<label class="label_login_form">Username: </label> <input type = "text" name = "username" placeholder = "username"><br /><br />';
    echo '<label class="label_login_form">Password: </label> <input id="password_input" type = "password" name = "password" placeholder = "password "> <br />';
    if ($browser == 'firefox') {
        echo '<button class="login_button" name="login" type="submit" value="Login">Login</button><br />';
    }
    else {
        echo '<input class="login_button" name = "login" type = "image" src="../images/Login_button.png" value="Login"><br />';
    }
    
    echo '</div>';
}

if (strlen($error) > 0) {
    echo '<br />';
    echo $error;
}

echo '</form>';
?>