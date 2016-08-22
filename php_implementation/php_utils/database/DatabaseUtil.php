<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\database\UserMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\LoginUtil.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\entities\User.php');


if (!DatabaseUtils::exists()) {
    echo "Creating database <br/>";
    if (DatabaseUtils::create()) {
        echo "Created database <br/>";
    }
}


// this should create a new default user once there isn't any, it automatically is a supervisor( 2) and has the password 'password'
if (DatabaseUtils::exists()) {
    if (count(UserMapper::select("SELECT * FROM user LIMIT 1;")) == 0) {
        echo "Adding default user <br/>";
        $addedUser = UserMapper::insertDefaultUser();
        if ($addedUser != false) {
            echo 'Added default user: ' . $addedUser->toString() . ' <br/>';
        } else {
            echo 'Failed adding default user, please refresh page';
        }
    }
}

/**
 * This utility class contains the utilities needed to create and connect to the database
 */
class DatabaseUtils {

    private static $host = '127.0.0.1';
    private static $user = 'root';
    private static $password = '';
    private static $database = 'schoolscreen';

    /**
     * Gets a new mysqli link to the database, it basically returns a mysqli object that can be used.
     * @return mysqli|false a new mysqli object with a database connection, false if the link could not be created
     */
    public static function getLink() {
        $link = new mysqli(DatabaseUtils::$host, DatabaseUtils::$user);
        $link->select_db(DatabaseUtils::$database);
        if ($link->connect_error) {
            die("Could not connect to the database.");
            return false;
        }
        return $link;
    }

    /** Checks if the database has been created yet
     * @return boolean true if the database has been created yet, false if it hasn't.
     */
    static function exists() {
        $link = new mysqli(DatabaseUtils::$host, DatabaseUtils::$user);
        if ($link->connect_error) {
            die("Could not connect to the database.");
        }
        $exists = $link->select_db(DatabaseUtils::$database);
        $link->close();
        return $exists === true;
    }

    /**
     * Creates and fills the database with movies.
     * @return boolean true if successfull, false if not
     */
    static function create() {
        $createDatabaseFile = $_SERVER['DOCUMENT_ROOT'] . "/SQL/CreateDatabase.sql";
        $link = new mysqli(DatabaseUtils::$host, DatabaseUtils::$user);
        $created = DatabaseUtils::queryFromFile($link, $createDatabaseFile);
        return DatabaseUtils::exists();
    }

    /**
     * Executes the query in the given file with the given link.
     * @param type $link the mysqli object with a database connection
     * @param type $fileName the filename of the file containing the sql queries
     * @return boolean true if query succesfull, false if not
     */
    static function queryFromFile($link, $fileName) {
        $query = file_get_contents($fileName);
        $result = $link->multi_query($query);
        return $result === true;
    }

}
