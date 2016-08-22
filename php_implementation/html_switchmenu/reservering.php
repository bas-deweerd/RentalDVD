<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ReservationMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Reservation.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
$film = $_GET['filmID'];

$browser = 'not_firefox';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = $_SERVER['HTTP_USER_AGENT'];
}
if (strlen(strstr($agent, 'Firefox')) > 0) {
    $browser = 'firefox';
}

if (LoginUtils::isLoggedIn() && LoginUtils::getUserType() >= 1) {
    if (isset($_POST['add_reservation'])) {
        $productId = $_POST['add_reservation'];
        $note = null;
        if (isset($_POST['add_note'])) {
            $note = $_POST['add_note'];
        }

        $tempReservation = ReservationMapper::selectByProduct($productId);
        if ($tempReservation === NULL) {
            $newReservation = new Reservation(LoginUtils::getUsername(), $productId, $note);
            $reservation = ReservationMapper::insert($newReservation);
            if ($reservation) {
                echo 'Added new reservation: ' . $newReservation->toString();
            } else {
                echo "Could not add a new reservation, something went wrong";
            }
        } else {
            echo 'Reservation already exists.';
        }
    }

    foreach (ProductMapper::select("SELECT * FROM product WHERE Id = '" . $film . "'") as $product) {
        echo '<h1>' . $product->getTitle() . '</h1>';
        echo '<img src="' . $product->getPhoto() . '" width="150" height="215" id="coverart"><br/>';
        echo '<p>Movie description: ' . $product->getDescription() . '<br/>';
        echo 'Title: ' . $product->getTitle() . '<br/>';
        echo 'Genre: ' . $product->getGenre() . '<br/>';
        echo 'Duration: ' . $product->getDuration() . '<br/>';
        echo 'Director: ' . $product->getDirector() . '<br/>';
        //Reservation form
        echo '<form action="" method="post">';
        echo '<label>Note:</label>';
        echo '<textarea maxlength=255 placeholder="Note for the reservation(optional)" name="add_note">';
        echo '</textarea>';
        echo '<br />';
        if ($browser == 'firefox') {
            echo '<button name="add_reservation" type ="submit" value="' . $product->getId() . '" >Place reservation</button>';
        }
        else {
            echo '<input name="add_reservation" type ="image" src="../images/Reservation_button.png" value="' . $product->getId() . '" ></input>';
        }
        echo '</form>';
        echo '</p>';
    }
}