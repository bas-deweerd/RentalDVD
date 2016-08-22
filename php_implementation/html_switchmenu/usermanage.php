<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/UserMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');

if (LoginUtils::isLoggedIn() && LoginUtils::getUserType() == 2) {

    $editableUser = null;
    /* @var $addedUser User  */
    $addedUser = null;

    if (isset($_POST['add_user']) && isset($_POST['add_user_password'])) {
        $userType = $_POST['add_user_type'];
        $password = $_POST['add_user_password'];
        $addedUser = UserMapper::insert($password, $userType);
        if ($addedUser != false) {
            echo 'Added user: ' . $addedUser->toString();
        }
    }

    if (isset($_POST['delete_user'])) {
        $pcn = $_POST['delete_user'];
        if (LoginUtils::getUsername() === $pcn) {
            echo 'You silly, you can\'t delete your own user';
        } else {
            $user = UserMapper::selectByPCN($pcn);
            UserMapper::delete($pcn);
            echo 'Deleted user: ' . $user->toString();
        }
    }

    if (isset($_POST['edit_user'])) {
        $editableUser = $_POST['edit_user'];
    } else {
        $editableUser = null;
    }

    if (isset($_POST['save_user'])) {
        $savePCN = $_POST['save_user'];
        $saveUserType = $_POST['edit_user_type'];
        $user = UserMapper::selectByPCN($savePCN);
        if ($user->getUserType() != $saveUserType) {
            $user->setUserType($saveUserType);
            UserMapper::update($user);
            $editableUser = null;
            echo 'Updated user: PCN=' . $user->getPCN() . ', UserType=' . $user->getUserTypeAsString();
        }
    }

    // Add user form
    echo '<h2>Add new user</h2>';
    echo '<form action="" method="post">';
    echo '<label>User type: </label>';
    echo '<select name="add_user_type">';
    echo '<option value="0">Student</option>';
    echo '<option value="1">Docent</option>';
    echo '<option value="2">Supervisor</option>';
    echo '</select>';
    echo '<br />';
    echo '<label>Password: </label>';
    echo '<input id="password_input" type = "password" name = "add_user_password" placeholder = "password ">';
    echo '<br />';
    echo '<button name="add_user" type="submit" value="add_user">Add user</button>';
    echo '</form>';
    echo '<br />';

    // Display userlist table
    echo'<h2>Userlist</h2>';
    echo '<table border = "1" ><th>PCN</th><th>UserType</th><th>Edit</th><th>Delete</th>';
    foreach (UserMapper::selectAll() as $user) {
        echo '<form action = "" method = "post">';
        echo '<tr>';
        echo '<td>' . $user->getPCN() . '</td>';
        if ($editableUser == $user->getPCN()) {
            // Edit Usertype/button save user
            echo '<td>';
            echo '<select name = "edit_user_type">';
            echo '<option value = "0"' . ($user->getUserType() == 0 ? 'selected' : "") . '>Student</option>';
            echo '<option value = "1"' . ($user->getUserType() == 1 ? 'selected' : "") . '>Docent</option>';
            echo '<option value = "2"' . ($user->getUserType() == 2 ? 'selected' : "") . '>Supervisor</option>';
            echo '</select>';
            echo '</td>';
            echo '<td><button class = "save_user" name = "save_user" type = "submit" value = "' . $user->getPCN() . '">Save</button></td>';
        } else {
            // Show Usertype/button edit user
            echo '<td>' . $user->getUserTypeAsString() . '</td>';
            echo '<td><button class = "edit_user" name = "edit_user" type = "submit" value = "' . $user->getPCN() . '">Edit</button></td>';
        }
        echo '<td><button class = "delete_user" name = "delete_user" type = "submit" value = "' . $user->getPCN() . '">Delete</button></td>';
        echo '</tr>';
        echo '</form>';
    }
    echo '</table>';
} else {
    echo 'Not logged in or not supervised, if you should have the rights contact your local administrator.';
}


