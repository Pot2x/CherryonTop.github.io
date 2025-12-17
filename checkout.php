<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)) die("Your cart is empty. <a href='menu.php'>Go back to menu</a>");

// make everything float
$customer = trim($_POST['customer'] ?? "Guest");
$customer_type = $_POST['customer_type'] ?? "Regular";
$total_before_discount = floatval($_POST['total_before_discount'] ?? 0);
$tax_amount = floatval($_POST['tax_amount'] ?? 0);
$discount_rate = floatval($_POST['discount_rate'] ?? 0);
$payment = floatval($_POST['payment'] ?? 0);

$discount_amount = $total_before_discount * $discount_rate;
$total_after_discount = $total_before_discount - $discount_amount;
$change = $payment - $total_after_discount;

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$conn = sqlsrv_connect($serverName, ["Database"=>"DLSUD","Uid"=>"","PWD"=>""]);
if(!$conn) die(print_r(sqlsrv_errors(), true));

$sqlOrder = "
INSERT INTO ORDERS (CUSTOMER, TOTAL, TAX, DISCOUNT, DATE_ORDERED)
OUTPUT INSERTED.Order_ID
VALUES (?, ?, ?, ?, GETDATE())
";
$paramsOrder = [$customer, $total_after_discount, $tax_amount, $discount_amount];
$orderIdResult = sqlsrv_query($conn, $sqlOrder, $paramsOrder);
if($orderIdResult === false) die(print_r(sqlsrv_errors(), true));

$orderRow = sqlsrv_fetch_array($orderIdResult, SQLSRV_FETCH_ASSOC);
$orderId = $orderRow['Order_ID'] ?? die("Failed to get Order ID");

foreach($cart as $item){
    $sqlDetails = "INSERT INTO ORDER_DETAILS (ORDER_ID, PRODUCTNAME, QUANTITY, PRICE) VALUES (?, ?, ?, ?)";
    $paramsDetails = [$orderId, $item['name'], $item['qty'], $item['price']];
    $resultDetails = sqlsrv_query($conn, $sqlDetails, $paramsDetails);
    if($resultDetails === false) die(print_r(sqlsrv_errors(), true));
}

unset($_SESSION['cart']);

header("Location: receipt.php?order_id=$orderId");
exit;
?>
