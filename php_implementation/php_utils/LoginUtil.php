<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/UserMapper.php');

session_start(); // Starting Session
$error = ''; // Variable To Store Error Message

if (isset($_POST)) {
    if (!LoginUtils::isLoggedIn()) {
        if (isset($_POST['login'])) {
            LoginUtils::login();
        }
    } else {
        if (isset($_POST['logout'])) {
            LoginUtils::logout();
        }
    }
} else {
    $error = 'post is not set';
}

class LoginUtils {

    /**
     * return string returns the current username if the user is logged in
     */
    public static function getUsername() {
        if (LoginUtils::isLoggedIn()) {
            return $_SESSION['username'];
        } else {
            return '';
        }
    }

    /**
     * Gets the user type from the sessions array.
     * @return int the user type
     */
    public static function getUserType() {
        if (LoginUtils::isLoggedIn()) {
            return $_SESSION['usertype'];
        } else {
            return -1;
        }
    }

    public static function login() {
        global $error;
        if (!LoginUtils::isLoggedIn()) {
            if (strlen($_POST['username']) === 0) {
                $error = "Please fill in your username";
                return;
            }
            if (strlen($_POST['password']) === 0) {
                $error = "Please fill in your password";
                return;
            }
            $PCN = $_POST['username'];
            $password = $_POST['password'];

            if (UserMapper::checkCredentials($PCN, $password)) {
                $userType = UserMapper::getUserType($PCN);
                $_SESSION['username'] = $PCN;
                $_SESSION['usertype'] = $userType;
            } else {
                echo 'Wrong user or wrong password supplied.';
            }
        }
    }

    /**
     * @return bool true if sessions is created and a username has been found.
     */
    public static function isLoggedIn() {
        return isset($_SESSION['username']) && isset($_SESSION['usertype']);
    }

    public static function logout() {
        session_destroy();
        //dit zorgt ervoor dat de pagina zichzelf refresht
        LoginUtils::refreshPage();
    }

    public static function refreshPage() {
        header('Location: ' . $_SERVER['PHP_SELF']);
    }

}
