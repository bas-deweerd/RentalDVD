<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ReservationMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');

if(isset($_POST['search'])) {
    $search = $_POST['search'];
    $getProducts = ProductMapper::select(ProductMapper::search(NULL, $search));
    if ($getProducts == NULL) {
        echo 'No search results for "' . $search . '".';
    }
}
elseif (isset($_GET['genre'])) {
    $genre = $_GET['genre'];
    $getProducts = ProductMapper::select(ProductMapper::search($genre, NULL));
}
else {
    $getProducts = ProductMapper::selectAll();
}
if (LoginUtils::isLoggedIn()) {  
    if(LoginUtils::getUserType() == 2) {
        echo '<a href="?c=6">Film toevoegen</a>';
    }
    foreach ($getProducts as $product) {
        echo '<div class="img">';
        echo '<a href="?c=4&filmID=' . $product->getID() . '" target="_self">';
        echo '<img src="' . $product->getPhoto() . '" width="150" height="215"/>';
        echo '</a>';
        echo '<div class="desc"><p><a href="?c=4&filmID=' . $product->getID() . '" target="_self">
                ' . $product->getTitle() . ' (' .$product->getYear() . ')' . '<br/>
                    </a>';
        if (LoginUtils::getUserType() != 1) {
            echo 'Rent price: ' . '€' . $product->getRent_Price() .'<br/>';
        }
        echo 'Description: ' . $product->getDescription() . '<br/>';
        echo 'Availability: ';
        if(!LeaseMapper::selectIsAvailable($product->getId()) || (ReservationMapper::selectByProduct($product->getId()))){
            echo '<img class="availability" src="../images/Red_X.png" width="42" height="36" />';
            
        }else{
            echo '<img class="availability" src="../images/Checkmark.png" width="42" height="36" />';
        }
        echo '<br/>';
        echo '</div>';
        
        echo '</p></div>';
    
    
    }

} else {
    echo 'Not logged in.';
}

