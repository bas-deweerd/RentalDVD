<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '\php_utils\database\DatabaseUtil.php');
$title = $_POST['title'];
$year = $_POST['year'];
$director = $_POST['director'];
$genre = $_POST['genre'];
$duration = $_POST['duration'];
$description = $_POST['description'];
$rent_price = $_POST['rent_price'];


$target_dir = $_SERVER['DOCUMENT_ROOT'] . '\images\Coverart\coverart_';
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$photo = 'images/Coverart/coverart_' . $_FILES["fileToUpload"]["name"];
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 512000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    header("Location: http://localhost:8000/index.php");
    echo "Sorry, your file was not uploaded.";
    exit;
// if everything is ok, try to upload file
} else {
    header("Location: http://localhost:8000/index.php");
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    $message = 'File has been uploaded<br/>';
    echo $target_file;
}


include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');

ProductMapper::insert($photo, $rent_price, $year, $title, $director, $genre, $duration, $description);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

