<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ReservationMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Lease.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
$film = $_GET['filmID'];

if (isset($_POST['delete_movie'])) {
    header("Location: http://localhost:8000/index.php");
    $product = $_POST['delete_movie'];
    ProductMapper::delete($product);
}
if (isset($_POST['edit_movie'])) {
    $editableUser = true;
} else {
    $editableUser = false;
}
$browser = 'not_firefox';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
}
if (strlen(strstr($agent, 'Firefox')) > 0) {
    $browser = 'firefox';
}
if (isset($_POST['borrow_movie'])) {
    //header("Location: http://localhost:8000/index.php");
    $product = $_POST['borrow_movie'];
    $id = null;
    $wololo = UserMapper::getUserType($id);
    if (isset($_POST['add_id'])) {
        $id = $_POST['add_id'];
    }
    if(UserMapper::selectByPCN($id)->canLeaseOrBorrow()){
    $reservation = ReservationMapper::selectByProduct($product);
    if (isset($reservation) && $id == $reservation->getPCN()) {
        ReservationMapper::delete(ReservationMapper::selectByProduct($product));
        LeaseMapper::insert($id, $product);
    } else if (isset($reservation) && $id != $reservation->getPCN()) {
        echo 'User has not placed the reservation therefor the movie cant be borrowed to the user with PCN: '.$reservation->getPCN().'.';
    } else 
        LeaseMapper::insert($id, $product);
    } else{
        $error1 = "can not place order too many orders";
    }
}
if (isset($_POST['return_movie'])) {
    //header("Location: http://localhost:8000/index.php");
    $product = $_POST['return_movie'];
    $ddate = date('y-m-d');

    LeaseMapper::update($product, $ddate);
}


if (isset($_POST['save_movie'])) {
    foreach (ProductMapper::select("SELECT * FROM product WHERE Id = '" . $film . "'") as $product) {
        $product->setTitle($_POST['title']);
        //$product->setYear($_POST['year']);
        $product->setDirector($_POST['director']);
        $product->setGenre($_POST['genre']);
        $product->setDuration($_POST['duration']);
        $product->setDescription($_POST['description']);
        $product->setRent_price($_POST['rent_price']);
        ProductMapper::update($product);
    }
}




foreach (ProductMapper::select("SELECT * FROM product WHERE Id = '" . $film . "'") as $product) {
    if ($editableUser == true) {
        echo '<form method="post">';
        echo '<label class="label_title">Title: </label> <input type = "text" name = "title" value = "' . $product->getTitle() . '"><br/>';
        echo '<label class="label_description"> Beschrijving: <textarea  name="description" >' . $product->getDescription() . ' </textarea><br />';
        echo '<label class="label_genre">Genre: </label> <input type = "text" name = "genre" value = "' . $product->getGenre() . '"><br/>';
        echo '<label class="label_duration">Duration: </label> <input type = "text" name = "duration" value = "' . $product->getDuration() . '"><br/>';
        echo '<label class="label_director">Director: </label> <input type = "text" name = "director" value = "' . $product->getDirector() . '"><br/>';
        echo '<label class="label_rent_price">Rent price: </label> <input type = "text" name = "rent_price" value = "' . $product->getRent_price() . '"><br/>';
        echo '<button  name="save_movie" type="submit" value="' . $product->getId() . '">Save ' . $product->getTitle() . '</button>';
        echo '</form>';
//        echo '<td><button class = "save_user" name = "save_user" type = "submit" value = "' . $user->getPCN() . '">Save</button></td>';
    } else {
        echo '<h1>' . $product->getTitle() . ' (' . $product->getYear() . ')' . '</h1>';
        echo '<img src="' . $product->getPhoto() . '" width="150" height="215" id="coverart"><br/>';
        echo '<p>Description: ' . $product->getDescription() . '<br/>';
        echo 'Title: ' . $product->getTitle() . '<br/>';
        echo 'Genre: ' . $product->getGenre() . '<br/>';
        echo 'Duration: ' . $product->getDuration() . '<br/>';
        echo 'Director: ' . $product->getDirector() . '<br/>';
        echo '</p><br/>';
        
        echo '<p>';
        if (LoginUtils::getUserType() != 1) {
            echo 'Rent price: ' . '€' . $product->getRent_price() . '<br/>';
        }
        echo 'Availability: ';

        if (!LeaseMapper::selectIsAvailable($product->getId()) || (ReservationMapper::selectByProduct($product->getId()))) {
            echo '<img src="../images/Red_X.png" width="42" height="36" /><br/>';
        } else {
            echo '<img src="../images/Checkmark.png" width="42" height="36" /><br/>';
            if (LoginUtils::getUserType() != 0) {
                echo '<a href = "index.php?c=5&filmID=' . $product->getId() . '"><img src="../images/Reservation_button.png" width="200" height="30"></a><br />';
            }
        }
        


        echo '</p>';
        if (LoginUtils::getUserType() == 2) {
            echo '<form method ="post">';
            if ($browser == 'firefox') {
                echo '<button class="edit" name="edit_movie" type = "submit" value="' . $product->getId() . '">Edit film</button>';
            }
            else {
                echo '<input class="edit" name="edit_movie" type = "image" src="../images/edit_button.png" value="' . $product->getId() . '"></input>';
            }
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            if ($browser == 'firefox') {
                echo '<button class="delete" name="delete_movie" type = "submit" value="' . $product->getId() . '">Delete film</button><br />';
            }
            else {
                echo '<input class="delete" name="delete_movie" type = "image" src="../images/delete_button.png" value="' . $product->getId() . '"></input></br>';
            }
            
            if (LeaseMapper::selectIsAvailable($product->getId())) {
                echo '<textarea maxlength=255 placeholder="Give the ID of the lessee" name="add_id">';
                echo '</textarea>';
                if ($browser == 'firefox') {
                    echo '<button class="borrow" name="borrow_movie" type = "submit" value="' . $product->getId() . '">Borrow</button>';
                }
                else {
                    echo '<input class="borrow" name="borrow_movie" type = "image" src="../images/Borrow_button.png" value="' . $product->getId() . '"></input>';
                }
            } else{
                if ($browser == 'firefox') {
                    echo '<button class="return" name="return_movie" type = "submit" value="' . $product->getId() . '">Return</button>';
                }
                else {
                    echo '<input class="return" name="return_movie" type = "image" src="../images/Return_button.png" value="' . $product->getId() . '"></input>';
                }
            }
            echo '</form>';
        }
    }
}
