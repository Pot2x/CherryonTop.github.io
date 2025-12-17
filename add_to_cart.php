<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$name = $_POST['ProductName'];
$price = $_POST['Price'];
$qty = $_POST['Quantity'];

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['name'] === $name) {
        $item['qty'] += $qty;
        $found = true;
        break;
    }
}
if (!$found) {
    $_SESSION['cart'][] = [
        "name" => $name,
        "price" => $price,
        "qty" => $qty
    ];
}

header("Location: menu.php");
exit();
?>
