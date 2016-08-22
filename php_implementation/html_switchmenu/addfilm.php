<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/entities/Product.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');
if (LoginUtils::isLoggedIn() && LoginUtils::getUserType() == 2) {

        ?>

                <h2>Film toevoegen/bewerken</h2>
                <form action="html_switchmenu/addfilm2.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" ><h6>Afbeelding mag niet groter zijn dan 0,5MB.</h6><br/>
                    Filmtitel: <input type="text" name="title"><br/>
                    Jaar: <input type="text" name="year"><br/>
                    Regisseur: <input type="text" name="director"><br/>
                    Genre: <select name="genre">
                        <option value="action">Actie</option>
                        <option value="comedy">Komedie</option>
                        <option value="sci-fi">Science Fiction</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="adventure">Adventure</option>
                        <option value="animation">Animatie</option>
                        <option value="horror">Horror</option>                
                    </select><br/>
                    Duur: <input type="text" name="duration"><br/>                   
                    Beschrijving: <textarea name="description"></textarea><br/>
                    Leengeld: <input type="text" name="rent_price"><br/>
                    <input type="submit">
                </form>
        <?php
        }
        else {
            echo 'Not logged in or not supervised, if you should have the rights contact your local administrators.';
        }
