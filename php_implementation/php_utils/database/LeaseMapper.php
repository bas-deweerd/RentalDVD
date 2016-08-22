<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Lease.php');

class LeaseMapper {

    private static $table = "Lease";

    public static function delete($id) {
        $link = DatabaseUtils::getLink();

        $query = "DELETE FROM " . LeaseMapper::$table . " WHERE id = " . $id . ";";
        /* @var $result \mysqli_result */
        $result = $link->query($query);
        return $result === true;
    }

    public static function insert($PCN, $productId) {
        $date = date('y-m-d', time());

        $link = DatabaseUtils::getLink();
        $query = "INSERT INTO " . LeaseMapper::$table . " (PCN, Product_id, Lease_date) VALUES ( '" . $PCN . "', '" . $productId . "', '" . $date . "' );";

        /* @var $result \mysqli_result */
        $result = $link->query($query);
        //check result to check if the new user has been added correctly.

        if ($result === true) {
            $array = LeaseMapper::select("SELECT * FROM " . LeaseMapper::$table . " ORDER BY ID ASC");
            if (count($array) > 0) {
                return array_pop($array);
            }
            return false;
        }
    }

    public static function update($productId, $returndate ) {
        $link = DatabaseUtils::getLink();
        
        $query = "UPDATE " . LeaseMapper::$table . " SET Return_date ='". $returndate ."' WHERE Product_id ='".$productId."' ORDER BY ID DESC LIMIT 1";
        /* @var $result \mysqli_result */
        $result = $link->query($query);
        //check result to check if the new user has been added correctly.

        if ($result === true) {
            $array = LeaseMapper::select("SELECT * FROM " . LeaseMapper::$table . " ORDER BY ID ASC");
            if (count($array) > 0) {
                return array_pop($array);
            }
            return false;
        }
    }

    public static function selectAll() {
        $sql = "SELECT * FROM " . LeaseMapper::$table . ";";
        return LeaseMapper::select($sql);
    }

    /**
     * Executes the given sql query and maps the resultset into a Lease array.
     * @param string $sql
     * @return Lease[] All the users matching the query.
     */
    public static function select($sql) {
        $lease = array();
        $link = DatabaseUtils::getLink();
        /* @var $result mysqli_result */
        $result = $link->query($sql);
        while ($row = $result->fetch_assoc()) {
            $tempLease = new Lease($row['Id'], $row['PCN'], $row['Product_id'], $row['Lease_date'], $row['Return_date']);
            array_push($lease, $tempLease);
        }
        return $lease;
    }

    /**
     * Selects leases which are overtime
     * @return Lease[]
     * 
     */
    public static function selectOverTimeLease() {
        $date = date('y-m-d', time());
        $sql = "SELECT * FROM " . LeaseMapper::$table . " WHERE '".$date."' > return_date ;";
        return LeaseMapper::select($sql);
    }

    /**
     * checks if the product is available
     * @param int $productId
     * @return boolean
     */
    public static function selectIsAvailable($productId) {

        $sql = "SELECT * FROM " . LeaseMapper::$table . " WHERE return_date IS NULL AND product_id =" . $productId . ";";
        $result = LeaseMapper::select($sql);
        if (count($result) > 0) {
            return false;
        }
        return true;
    }

//    public static function getMoviesBorrowedByPCN($pcn){
//        
//        $sql = "SELECT COUNT(PCN) FROM " . LeaseMapper::$table . " WHERE pcn = " .$pcn. " AND return_date is null;";
//        
//        }
}
