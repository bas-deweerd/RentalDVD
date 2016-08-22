<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Menu {

    public static function printMenu() {
        if (LoginUtils::isLoggedIn()) {
            echo '<ul>';
            echo '<li><a href = "index.php?c=0">Film List</a></li>';
            
            
            echo '<li>';
                echo '<div class="collapse-menu">';
                    echo '<div id="genre-header">Genres</div>';
                        echo '<ul>';
                            echo '<li><a href="?c=0&genre=action">Action</a></li>';
                            echo '<li><a href="?c=0&genre=sci-fi">Sci-fi</a></li>';
                            echo '<li><a href="?c=0&genre=fantasy">Fantasy</a></li>';
                            echo '<li><a href="?c=0&genre=adventure">Adventure</a></li>';
                            echo '<li><a href="?c=0&genre=animation">Animation</a></li>';
                            echo '<li><a href="?c=0&genre=comedy">Comedy</a></li>';
                            echo '<li><a href="?c=0&genre=horror">Horror</a></li>';
                        echo '</ul>';
                 echo '</div>';
            echo '</li>';
            
            
            echo '<li><a href = "index.php?c=1">History</a></li>';
            if  (LoginUtils::getUserType() == 2) {
                echo '<li><a href = "index.php?c=3">User List</li>';
            }
            echo '<li><a href = "index.php?c=2">Logout</a></li>';
            echo '</ul>';
        }
    }

}
