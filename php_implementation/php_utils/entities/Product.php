<?php

/**
 * A product object representing a product entity.
 */
class Product {

    private $id;
    private $photo;
    private $rent_price;
    private $year;
    private $title;
    private $director;
    private $genre;
    private $duration;
    private $description;

    /**
     * @param integer $id
     * @param String $photo
     * @param integer $rent_price
     * @param integer $year
     * @param String $title
     * @param String $director
     * @param String $genre
     * @param String $duration
     * @param String $description
     */
    public function __construct($id, $photo, $rent_price, $year, $title, $director, $genre, $duration, $description) {
        $this->id = $id;
        $this->photo = $photo;
        $this->rent_price = $rent_price;
        $this->year = $year;
        $this->title = $title;
        $this->director = $director;
        $this->genre = $genre;
        $this->duration = $duration;
        $this->description = $description;
    }

    /**
     * Gets the ID.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the ID.
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Gets the photo.
     * @return String
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Sets the photo.
     * @param String $photo
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    /**
     * Gets the rent price.
     * @return int
     */
    public function getRent_price() {
        return $this->rent_price;
    }

    /**
     * Sets the rent price.
     * @param int $rent_price
     */
    public function setRent_price($rent_price) {
        $this->rent_price = $rent_price;
    }

    /**
     * Gets the year.
     * @return int
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Sets the year.
     * @param int $year
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * Gets the title.
     * @return String
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets the title.
     * @param String $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Gets the director.
     * @return String
     */
    public function getDirector() {
        return $this->director;
    }

    /**
     * Sets the director.
     * @param String $director
     */
    public function setDirector($director) {
        $this->director = $director;
    }

    /**
     * Gets the genre.
     * @return String
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Sets the genre.
     * @param String $genre
     */
    public function setGenre($genre) {
        $this->genre = $genre;
    }

    /**
     * Gets the duration.
     * @return String
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Sets the duration.
     * @param String $duration
     */
    public function setDuration($duration) {
        $this->duration = $duration;
    }

    /**
     * Gets the description.
     * @return String
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the description.
     * @param String $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    public function isLeased() {
        return LeaseMapper::selectIsAvailable($this->getId());
    }

    public function hasReservation() {
        $reservationArray = ReservationMapper::select("SELECT * FROM Reservation WHERE Product_id = " . $this->getId());
        return count($reservationArray) > 0;
    }

}
