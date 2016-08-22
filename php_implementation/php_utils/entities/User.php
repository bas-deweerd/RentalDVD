<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ReservationMapper.php');  
/**
 * A user object representing a user entity.
 */
class User {

    private $PCN;
    private $password;
    private $passwordHash;
    private $userType;

    /**
     * @param integer $PCN
     * @param string $password
     * @param string $userType
     */
    public function __construct($PCN, $password, $userType) {
        $this->PCN = $PCN;
        $this->password = $password;
        $this->userType = $userType;
    }

    /**
     * Gets the PCN.
     * @return int pcn
     */
    public function getPCN() {
        return $this->PCN;
    }

    /**
     * Sets the PCN.
     * @param int $PCN
     */
    public function setPCN($PCN) {
        $this->PCN = $PCN;
    }

    /**
     * Gets the password.
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets the password
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Gets the user type.
     * @return int userType
     */
    public function getUserType() {
        return $this->userType;
    }

    /**
     * Sets the user type
     * @param int $userType
     */
    public function setUserType($userType) {
        $this->userType = $userType;
    }

    function getPasswordHash() {
        return $this->passwordHash;
    }

    function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    function toString() {
        return 'PCN=' . $this->getPCN() . ', UserType=' . $this->getUserTypeAsString();
    }
    
    /**
     * 
     * @return booleantrue if the accumolated amount of leases(which aren't returned yet and reservations) isn't higher than 5
     */
    public function canLeaseOrBorrow(){
        return ($this->leaseCount() + $this->reservationCOunt()) <= 5;
    }
    
    /**
     * Gets the amount of leases which are not returned for this user.
     * @return Integer the amount of leases which are not returned yet for this user
     */
    private function leaseCount(){
        $leases = LeaseMapper::select("SELECT * FROM lease WHERE PCN = " . $this->getPCN() . " AND Return_date IS NULL;");
        return count($leases);
    }
    /**
     * Gets the amount of reservations for this user
     * @return Integer the amount of reservations for this user
     */
    private function reservationCOunt(){
        $reservations = ReservationMapper::select("SELECT * FROM reservation WHERE PCN = " . $this->getPCN() . ";");
        return count($reservations);
    }
    
    /**
     * Gets the user type and returns a string representing the user type.
     * @return string representing the user type
     */
    public function getUserTypeAsString() {
        //TODO de type gebruikers invullen in de switch case, nu het is test data
        switch ($this->getUserType()) {
            case 0:
                return "Student";
            case 1:
                return "Docent";
            case 2:
                return "Supervisor";
            default:
                return "Unauthorized";
        }
    }

    /**
     * Makes this user lease the given product.
     * @param Product $product
     */
    public function leaseProduct($product) {
        
    }

}
