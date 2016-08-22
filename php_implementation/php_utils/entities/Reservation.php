<?php

class Reservation {

    private $PCN;
    private $pruductId;
    private $note;

    /**
     * 
     * @param type $PCN
     * @param type $productId
     * @param type $userType
     */
    public function __construct($PCN, $productId, $note) {
        $this->PCN = $PCN;
        $this->productId = $productId;
        $this->note = $note;
    }

    /**
     * Sets the PCN.
     * @param integer $PCN
     */
    public function setPCN($PCN) {
        $this->PCN = $PCN;
    }

    /**
     * Sets the product id.
     * @param int product id
     */
    public function setProductId($productId) {
        $this->$productId = $productId;
    }

    /**
     * Returns the PCN.
     * @return int the pcn
     */
    public function getPCN() {
        return $this->PCN;
    }

    /**
     * Returns the product id.
     * @return int the product id
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Gets the note of the reservation.
     * @return string the note of the reservation
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * Sets the node of the reservation.
     * @param string $note
     */
    function setNote($note) {
        $this->note = $note;
    }

    /**
     * Returns the attributes of the reservation object as usable string
     */
    function toString() {
        return 'PCN=' . $this->getPCN() . ', ProductId=' . $this->getProductId() . ', Note=' . $this->getNote();
    }

}
