<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\database\DatabaseUtil.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\entities\Reservation.php');

class ReservationMapper {

    private static $table = 'Reservation';

    /**
     * Inserts a new reservation into the database.
     * @param Reservation $reservation
     * @return Reservation|false the reservation that has been added, false if the insert didn't succeed
     */
    public static function insert($reservation) {
        $sql = "INSERT INTO " . ReservationMapper::$table . " (PCN, Product_id, Note) VALUES (" . $reservation->getPCN() . ", " . $reservation->getProductId() . ", '" . $reservation->getNote() . "');";
        $link = DatabaseUtils::getLink();
        $result = $link->query($sql);
        if ($result === true) {
            return ReservationMapper::selectByProduct($reservation->getProductId());
        }
        return false;
    }

    /**
     * Deletes a reservation from the database.
     * @param Reservation $reservation
     * @return true if succeeded
     */
    public static function delete($reservation) {
        $link = DatabaseUtils::getLink();
        $query = "DELETE FROM " . ReservationMapper::$table . " WHERE PCN = " . $reservation->getPCN();
        $result = $link->query($query);
        if ($result === false) {
            return false;
        }
        $userArray = UserMapper::select("SELECT * FROM " . ReservationMapper::$table . " WHERE PCN = " . $reservation->getPCN() . ";");
        return count($userArray) == 0;
    }

    /**
     * Gets all the reservations.
     * @return Reservation[] all the reservations
     */
    public static function selectAll() {
        return select("SELECT * FROM " . ReservationMapper::$table);
    }

    /**
     * Selects the reservation belonging to the given product
     * @param int $productID
     * @return Reservation the reservation belonging to the given product, null of no reservation
     */
    public static function selectByProduct($productID) {
        $sql = "SELECT * FROM " . ReservationMapper::$table . " WHERE Product_id = " . $productID . ';';
        $reservationArray = ReservationMapper::select($sql);
        if (count($reservationArray) > 0) {
            return array_pop($reservationArray);
        }
        return null;
    }

    /**
     * Gets the reservations belonging to the given user(pcn).
     * @param int $PCN the pcn to check
     * @return Reservation[] all the reservations belonging to the given user
     */
    public static function selectByUser($PCN) {
        $sql = "SELECT * FROM " . ReservationMapper::$table . " WHERE PCN = " . $PCN . ";";
        return ReservationMapper::select($sql);
    }

    /**
     * Gets all the reservations matching the given query.
     * @param string $sql Query
     * @return Reservation[] All the reservations matching the given query
     */
    public static function select($sql) {
        $reservations = array();
        $link = DatabaseUtils::getLink();

        /* @var $result mysqli_result */
        $result = $link->query($sql);
        if ($result === false) {
            return $reservations;
        }
        while ($row = $result->fetch_assoc()) {
            $tempReservation = new Reservation($row['PCN'], $row['Product_Id'], $row['Note']);
            //adds the $tempReservation to the $reservations array
            array_push($reservations, $tempReservation);
        }

        return $reservations;
    }

    /**
     * Updates a reservation in the database.
     * @param Reservation $reservation
     * @return true if succeeded
     */
    public static function update($reservation) {
        $link = DatabaseUtils::getLink();
        $sql = "UPDATE " . ReservationMapper::$table . "SET Note = " . $link->real_escape_string($reservation->getNote()) . " WHERE PCN = " . $reservation->getPCN();
        $result = $link->query($sql);
        return $result === true;
    }

}
