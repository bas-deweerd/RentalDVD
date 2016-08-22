<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\database\DatabaseUtil.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\entities\User.php');

class UserMapper {

    private static $table = "user";

    /**
     * Deletes a user from the database which matches the given pcn.
     * @param int $pcn
     * @return boolean
     */
    public static function delete($pcn) {
        $link = DatabaseUtils::getLink();
        $query = "DELETE FROM " . UserMapper::$table . " WHERE PCN = " . $pcn . ";";
        $result = $link->query($query);
        if ($result === false) {
            return false;
        }
        $userArray = UserMapper::select("SELECT * FROM " . UserMapper::$table . " WHERE PCN = " . $pcn . ";");
        return count($userArray) == 0;
    }

    /**
     * Adds a default user with the password 'password' and user type 'SuperVisor'
     * 
     * @return User|boolean the default user that has been added, false if not able to add a default user
     */
    public static function insertDefaultUser() {
        if (count(UserMapper::select("SELECT * FROM user LIMIT 1;")) == 0) {
            return UserMapper::insert("password", 2);
        } else {
            return false;
        }
    }

    /**
     * Adds a new user with the given password and usertype
     * @param string $password password
     * @param int $userType user type
     * @return User|boolean User if succesfull, false if not succesfull
     */
    public static function insert($password, $userType) {
        $link = DatabaseUtils::getLink();
        //create a password hash instead of storing the actual password ( we do not use md5 since it has been cracked)
        $hashedPassword = password_hash($link->real_escape_string($password), PASSWORD_BCRYPT);
        $query = "INSERT INTO " . UserMapper::$table . " (Password, User_type) VALUES ( '" . $hashedPassword . "', " . $userType . ");";

        /* @var $result \mysqli_result */
        $result = $link->query($query);
        if ($result != false) {
            $array = UserMapper::select("SELECT * FROM " . UserMapper::$table . " ORDER BY PCN ASC;");
            if (count($array) > 0) {
                return array_pop($array);
            }
        }
        return false;
    }

    /**
     * Verifies if the user exists within the database
     * @param int $pcn the username of the user
     * @param string $password the password of the user
     * @return boolean true if credentials match, false if not.
     */
    public static function checkCredentials($pcn, $password) {
        $link = DatabaseUtils::getLink();
        $query = "SELECT password FROM " . UserMapper::$table . " WHERE PCN = " . $pcn . ";";

        /* @var $result mysqli_result */
        $result = $link->query($query);
        if ($result === false) {
            return false;
        }
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_row()) {
                // we compare the given password with the password_verify function with the hashed password from the database
                return password_verify($password, $row[0]);
            }
        }
        return false;
    }

    /**
     * Gets all the users in the database
     * @return User[] All available users.
     */
    public static function selectAll() {
        $sql = "SELECT * FROM " . UserMapper::$table . " ;";
        return UserMapper::select($sql);
    }

    /**
     * Gets the user matching the given pcn.
     * @param integer $pcn the pcn the match
     * @return User the user matching the pcn, else null
     */
    public static function selectByPCN($pcn) {
        $sql = "SELECT * FROM " . UserMapper::$table . " WHERE PCN = " . $pcn;
        $userArray = UserMapper::select($sql);
        if (count($userArray) > 0) {
            return array_pop($userArray);
        }
        return null;
    }

    /**
     * Executes the given sql query and maps the resultset into a User array.
     * @param string $sql Query
     * @return User[] All the users matching the query.
     */
    public static function select($sql) {
        $users = array();
        $link = DatabaseUtils::getLink();

        /* @var $result mysqli_result */
        $result = $link->query($sql);
        if ($result != false) {
            while ($row = $result->fetch_assoc()) {
                $tempUser = new User($row['PCN'], '', $row['User_type']);
                $tempUser->setPasswordHash($row['Password']);
                //adds the $tempUser to the $users array
                array_push($users, $tempUser);
            }
        }
        return $users;
    }

    /**
     * Gets the usertype for the given pcn.
     * @param type $pcn
     * @return int corresponding user type, or -1 if not found.
     */
    public static function getUserType($pcn) {
        $sql = "SELECT * FROM " . UserMapper::$table . " WHERE PCN = " . $pcn . ";";
        $userArray = UserMapper::select($sql);
        if (count($userArray) > 0) {
            $user = array_pop($userArray);
            return $user->getUserType();
        }
        return -1;
    }

    /**
     * Updates a user in the user table
     * @param User $user the user that has to be updated
     * @return boolean false if not existing user, or not updated. true if updated
     */
    public static function update($user) {
        $link = DatabaseUtils::getLink();
        $sql = "UPDATE " . UserMapper::$table . " SET ";

//        $samePassword = UserMapper::checkCredentials($user->getPCN(), $user->getPassword());
//        if ($samePassword === false) {
//            echo 'password is different, also updating password';
//            $hashedPassword = password_hash($link->real_escape_string($user->getPassword()), PASSWORD_BCRYPT);
//            $sql .= "Password = '" . $hashedPassword . "', ";
//        }

        $sql .="User_type = " . $user->getUserType();
        $sql .=" WHERE PCN = " . $user->getPCN() . ";";
        return $link->query($sql) === true;
    }

}
