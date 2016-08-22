<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');

if (LoginUtils::isLoggedIn()) {
    foreach (ProductMapper::selectAll() as $product) {
        echo '<div class="img">';
        echo '<a target="_blank" href="?filmID=' . $product->getID() . '">';
        echo '<img src="' . $product->getPhoto() . '" width="150" height="215" id="coverart">';
        echo '</a>';
        echo '<div class="desc"><p>Titel: ' . $product->getTitle() . '<br/>';
        echo 'Leengeld: ' . $product->getRent_Price() . '<br/>';
        echo 'Beschrijving: ' . $product->getDescription() . '<br/>';
        echo 'Beschikbaar: ';
        if(LeaseMapper::selectIsAvailable($product->getId())){
            echo 'nope';
            
        }else{
            echo'yep';
        }
        echo '<br/>';
        echo '</p></div>';
        echo '</div>';
    }
} else {
    echo 'Not logged in.';
}

