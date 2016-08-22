<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/LeaseMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/database/ProductMapper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php_utils/LoginUtil.php');

$user = LoginUtils::getUserType();

if (!LoginUtils::isLoggedIn()) {
    die();
}
if (LoginUtils::getUserType() === '2') {
    $query = LeaseMapper::selectAll();
} else {
    $query = LeaseMapper::select("SELECT * FROM lease WHERE PCN = " . LoginUtils::getUsername() . "");
}
if (isset($_POST['LeaseDays'])) {
    if ($_POST['LeaseDays'] == 1) {
        $leasedays = $_POST['LeaseDays'] . ' day';
    } else {
        $leasedays = $_POST['LeaseDays'] . ' days';
    }
} else {
    $leasedays = 'all';
}
?>

<form method="post">
    See those items leased for: <input type="number" name="LeaseDays" value="10"> days. <input type="submit">
</form>
<table border="1">
    <tr>

        <td>Film name</td>
        <td>Lessee</td>
        <td>Leased out</td>
        <td>Returned</td>
        <td>Days leased out</td>
        <?php
        if (!($user == 1)) {
            echo '<td>Rent Price</td>';
            echo '<td>Fine</td>';
        }
        ?>
    </tr>
    <?php
    foreach ($query as $history) {
        if ($leasedays === 'all') {
            echo '<tr>';
            foreach (ProductMapper::select("SELECT * FROM product WHERE id = " . $history->getProductId() . "") as $leasedProduct) {
                echo '<td>' . $leasedProduct->getTitle() . '</td>';
            }

            echo '<td>' . $history->getPCN() . '</td>';
            echo '<td>' . $history->getLeaseDate() . '</td>';
            echo '<td>' . $history->getEndDate() . '</td>';
            echo '<td>' . $history->getDaysLeasedOut() . '</td>';

            if (!($history->getUserType($history->getPCN()) == 1)) {
                echo '<td>&#8364;' . $leasedProduct->getRent_price() . '</td>';
                echo '<td>&#8364;' . $history->getPenalty() . '</td>';
            } else {
                 if (!($user == 1)) {
                    echo '<td>&#8364;0</td>';
                    echo '<td>&#8364;0</td>';
                    }
            }
            echo '</tr>';
        } else {
            if ($history->getDaysLeasedOut() === $leasedays) {
                echo '<tr>';
                foreach (ProductMapper::select("SELECT * FROM product WHERE id = " . $history->getProductId() . "") as $leasedProduct) {
                    echo '<td>' . $leasedProduct->getTitle() . '</td>';
                }

                echo '<td>' . $history->getPCN() . '</td>';
                echo '<td>' . $history->getLeaseDate() . '</td>';
                echo '<td>' . $history->getEndDate() . '</td>';
                echo '<td>' . $history->getDaysLeasedOut() . '</td>';
                if (!($history->getUserType($history->getPCN()) == 1)) {

                    echo '<td>&#8364;' . $leasedProduct->getRent_price() . '</td>';
                    echo '<td>&#8364;' . $history->getPenalty() . '</td>';
                } else {
                    if (!($user == 1)) {
                    echo '<td>&#8364;0</td>';
                    echo '<td>&#8364;0</td>';
                    }
                }
                echo '</tr>';
            }
        }
    }
    ?>

</table>
