<?php
session_start();

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = ["Database" => "DLSUD", "Uid" => "", "PWD" => ""];
$conn = sqlsrv_connect($serverName, $connectionOptions);

if(!$conn) die(print_r(sqlsrv_errors(), true));

$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if($orderId == 0) die("No order to display.");

$sqlOrder = "SELECT * FROM ORDERS WHERE Order_ID = ?";
$resultOrder = sqlsrv_query($conn, $sqlOrder, [$orderId]);
$order = sqlsrv_fetch_array($resultOrder, SQLSRV_FETCH_ASSOC);
if(!$order) die("Order not found.");

$sqlDetails = "SELECT * FROM ORDER_DETAILS WHERE ORDER_ID = ?";
$resultDetails = sqlsrv_query($conn, $sqlDetails, [$orderId]);
?>
<!DOCTYPE html>
<html>
<head>
<title>Receipt - Order #<?= $orderId ?></title>

<style>
body {
    background: #f2f2f2;
    font-family: "Courier New", monospace;
}

.receipt {
    background: #fff;
    width: 320px;
    margin: 20px auto;
    padding: 15px;
    border: 1px dashed #000;
}

.center {
    text-align: center;
}

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 10px 0;
}

table {
    width: 100%;
    font-size: 14px;
}

td {
    padding: 3px 0;
}

.right {
    text-align: right;
}

.totals td {
    padding-top: 5px;
}

.buttons {
    width: 320px;
    margin: 15px auto;
}

button {
    width: 100%;
    padding: 10px;
    margin-bottom: 8px;
    font-size: 14px;
    cursor: pointer;
}

/* Button hider for pdf */
@media print {
    body {
        background: none;
    }
    .buttons {
        display: none;
    }
}
</style>
</head>

<body>

<div class="receipt">

    <div class="center">
        <strong>CHERRY ON TOP</strong><br>
        Café & Diner<br>
        ==================
        
    </div>

    <p>
        Order #: <?= $orderId ?><br>
        Customer: <?= htmlspecialchars($order['CUSTOMER']) ?><br>
        Date: <?= $order['DATE_ORDERED']->format('Y-m-d H:i') ?>
    </p>

    <hr>

    <table>
        <?php while($item = sqlsrv_fetch_array($resultDetails, SQLSRV_FETCH_ASSOC)): ?>
        <tr>
            <td colspan="2"><?= htmlspecialchars($item['PRODUCTNAME']) ?></td>
        </tr>
        <tr>
            <td><?= $item['QUANTITY'] ?> x ₱<?= number_format($item['PRICE'],2) ?></td>
            <td class="right">
                ₱<?= number_format($item['PRICE'] * $item['QUANTITY'],2) ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">₱<?= number_format($order['TOTAL'] + $order['DISCOUNT'], 2) ?></td>
        </tr>
        <tr>
            <td>Discount</td>
            <td class="right">-₱<?= number_format($order['DISCOUNT'], 2) ?></td>
        </tr>
        <tr>
            <td>Tax</td>
            <td class="right">₱<?= number_format($order['TAX'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="right"><strong>₱<?= number_format($order['TOTAL'], 2) ?></strong></td>
        </tr>
    </table>

    <hr>

    <div class="center">
        Have a Cherry on Top day!🥰<br>
    </div>

</div>

<div class="buttons">
    <button onclick="window.print()">Print Receipt</button>
    <button onclick="window.location.href='menu.php'">Place Another Order</button>
</div>

</body>
</html>
