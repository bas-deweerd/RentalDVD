<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ReservationMapper.php');

class Lease {

    private $id;
    private $PCN;
    private $productId;
    private $leaseDate;
    private $endDate;
    private $getUserType;

    /**
     * @param integer $id
     * @param integer $PCN
     * @param string $productId
     * @param date $leaseDate
     * @param date $endDate
     */
    public function __construct($id, $PCN, $productId, $leaseDate, $endDate) {
        $this->id = $id;
        $this->PCN = $PCN;
        $this->productId = $productId;
        $this->leaseDate = $leaseDate;
        $this->endDate = $endDate;
    }

    /**
     * Returns the lease date.
     * @return date $leaseDate
     */
    public function getLeaseDate() {
        return $this->leaseDate;
    }

    /**
     * Returns the id.
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the PCN.
     * @return integer $PCN 
     */
    public function getPCN() {
        return $this->PCN;
    }

    /**
     * Returns the Product ID.
     * @return string $productId
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Retuns the end date.
     * @return type $endDate
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Returns the penalty for not returning in time
     * @return  integer $penalty
     */
    public function getPenalty() {
        //difference between lease and end date
        $leaseDate = date_create($this->leaseDate);
        $endDate = date_create($this->endDate);
        if ($endDate === NULL) {
            $endDate = date("Y/m/d");
        }
        $boete = 0.50;
        $returnInDays = 10;

        //lease date + 10 days
        $penaltyDate = $leaseDate->add(new DateInterval('P' . $returnInDays . 'D'));

        //difference between penaltyDate and endDate
        $penaltyDifference = date_diff($penaltyDate, $endDate);
        $penaltyDifferenceInt = $penaltyDifference->format('%d');
        if ($endDate > $penaltyDate) {
            return $penaltyDifferenceInt * $boete;
        } else {
            return 0;
        }
    }

    /**
     * Returns the amount of days between leased out and returned
     * @return  string $DaysLeasedOut
     */
    public function getDaysLeasedOut() {
        $leaseDate = date_create($this->leaseDate);
        $endDate = date_create($this->endDate);
        $diff1Day = new DateInterval('P1D');
        $endDate = date_add($endDate, $diff1Day);
        $daysDiff = date_diff($endDate, $leaseDate);
        $daysDiffString = $daysDiff->format('%a');
        if ($daysDiffString === '1') {
            return $daysDiffString . ' day';
        } else {
            return $daysDiffString . ' days';
        }
    }

    /**
     * Sets the ID.
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Sets the PCN.
     * @param type $PCN
     */
    public function setPCN($PCN) {
        $this->PCN = $PCN;
    }

    /**
     * Sets the product ID.
     * @param type $productId
     */
    public function setProductId($productId) {
        $this->productId = $productId;
    }

    /**
     * Sets the end date.
     * @param type $endDate
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    /**
     * Sets the lease date.
     * @param string $leaseDate
     */
    public function setLeaseDate($leaseDate) {
        $this->leaseDate = $leaseDate;
    }

    /**
     * This method gets the usertype of the pcn that has borrowed a movie.
     * @param int $PCN 
     * @return int UserType
     */
    public function getUserType($PCN) {
        return UserMapper::getUserType($PCN);
    }

}
