<?php
session_start();

$index = $_POST['index'];
$action = $_POST['action'];

if (!isset($_SESSION['cart'][$index])) {
    header("Location: cart.php");
    exit();
}

switch($action) {
    case "increase":
        $_SESSION['cart'][$index]['qty']++;
        break;
    case "decrease":
        if ($_SESSION['cart'][$index]['qty'] > 1) {
            $_SESSION['cart'][$index]['qty']--;
        } else {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
        break;
    case "remove":
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
        break;
}

header("Location: cart.php");
exit();
?>
