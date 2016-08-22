<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');

//for testing purposes, DEL when done
//$Product = ProductMapper::insert('test', 1);
//echo $product->getId() . ' - ' . $product->getPhoto() . ' - ' . $product->getRent_Price() . ' - ' . $product->getYear() . ' - ' . $product->getTitle() . ' - ' . $product->getDirector() . ' - ' . $product->getGenre() . ' - ' . $product->getDuration() . ' - ' . $product->getDescription() . '<br />';
//echo '<br />';
//
//foreach (ProductMapper::selectAll() as $product) {
//    echo $product->getId() . ' - ' . $product->getPhoto() . ' - ' . $product->getRent_Price() . ' - ' . $product->getYear() . ' - ' . $product->getTitle() . ' - ' . $product->getDirector() . ' - ' . $product->getGenre() . ' - ' . $product->getDuration() . ' - ' . $product->getDescription() . '<br />';
//}

class ProductMapper {

    private static $table = 'product';

    /**
     * Deletes a product from the database that matches the given id.
     * @param int id
     * @return boolean
     */
    public static function delete($id) {
        $link = DatabaseUtils::getLink();
        $query = "DELETE FROM " . ProductMapper::$table . " WHERE Id = " . $id . ";";
        $result = $link->query($query);
        if ($result === false) {
            return false;
        }
        $productArray = ProductMapper::select("SELECT * FROM " . ProductMapper::$table . "WHERE Id = " . $id . ";");
        return count($productArray) == 0;
    }

    /**
     * Adds a new product with the given information (see variables).
     * @param int id
     * @param String photo
     * @param int rent_price
     * @param int year
     * @param String title
     * @param String director
     * @param String genre
     * @param String duration
     * @param String description
     * @return Product|boolean Product if succesful, false if not successful
     */
    public static function insert($photo, $rent_price, $year, $title, $director, $genre, $duration, $description) {
        $link = DatabaseUtils::getLink();
        $query = "INSERT INTO " . ProductMapper::$table . " (Photo, Rent_price, Year, Title, Director, Genre, Duration, Description) VALUES ('" . $photo . "', '" . $rent_price . "', '" . $year . "', '" . $title . "', '" . $director . "', '" . $genre . "', '" . $duration . "', '" . $description . "');";

        /* @var $result \mysqli_result */
        $result = $link->query($query);
        if ($result === true) {
            $array = ProductMapper::select("SELECT * FROM " . ProductMapper::$table . " ORDER BY Id ASC");
            if (count($array) > 0) {
                return array_pop($array);
            }
        }
        return false;
    }

    /**
     * Gets all the products in the database.
     * @return Product[] All available products.
     */
    public static function selectAll() {
        $sql = "SELECT * FROM " . ProductMapper::$table . " ORDER BY Title ASC;";
        return ProductMapper::select($sql);
    }

    /**
     * Execute the given sql query and maps the resultset into a Product array.
     * @param String @sql
     * @return Product[] All the products matching the query.
     */
    public static function select($sql) {
        $products = array();
        $link = DatabaseUtils::getLink();

        /* @var $result mysqli_result */
        $result = $link->query($sql);
        while ($row = $result->fetch_assoc()) {
            $tempProduct = new Product($row['Id'], $row['Photo'], $row['Rent_price'], $row['Year'], $row['Title'], $row['Director'], $row['Genre'], $row['Duration'], $row['Description']);
            array_push($products, $tempProduct);
        }
        return $products;
    }

    /**
     * Updates a product in the table.
     * @param Product $product The product that has to be updated.
     * @return boolean false if not existing product, or not updated. True if updated.
     */
    public static function update($product) {
        $link = DatabaseUtils::getLink();
        $sql = "UPDATE " . ProductMapper::$table . " SET ";
//        $sql .="Photo = '" . $product->getPhoto() . "',";
        $sql .="Rent_price = " . $product->getRent_price() . ", ";
        $sql .="Year = " . $product->getYear() . ", ";
        $sql .="Title = '" . $link->real_escape_string($product->getTitle()) . "', ";
        $sql .="Director = '" . $link->real_escape_string($product->getDirector()) . "', ";
        $sql .="Genre = '" . $link->real_escape_string($product->getGenre()) . "', ";
        $sql .="Duration = '" . $link->real_escape_string($product->getDuration()) . "', ";
        $sql .="Description = '" . $link->real_escape_string($product->getDescription()) . "' ";
        $sql .=" WHERE ID = " . $product->getId() . ";";
        return $link->query($sql) === true;
    }

    public static function search($genre, $title) {
        $sql = 'SELECT * FROM product ';
        $sql.= isset($genre) || isset($title) ? 'WHERE' : '';

        if (isset($genre)) {
            $sql.= ' Genre = "' . $genre . '"ORDER BY Title;';
        }
        if (isset($title)) {
            $sql.= ' Title LIKE "%' . $title . '%" ORDER BY Title;';
        }
        return $sql;
    }

}
