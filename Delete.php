<?php
$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "DLSUD",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) die(print_r(sqlsrv_errors(), true));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sqlProducts = "SELECT PRODUCTNAME FROM PRODUCT ORDER BY PRODUCTNAME ASC";
    $resultProducts = sqlsrv_query($conn, $sqlProducts);
    if ($resultProducts === false) die(print_r(sqlsrv_errors(), true));

    $products = [];
    while ($row = sqlsrv_fetch_array($resultProducts, SQLSRV_FETCH_ASSOC)) {
        $products[] = $row['PRODUCTNAME'];
    }

    header('Content-Type: application/json');
    echo json_encode($products);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $PRODUCTNAME = trim($_POST['ProductName']);
    if (empty($PRODUCTNAME)) die("Error: Product Name must be provided.");

    $sqlSelect = "SELECT FILEPATH FROM PRODUCT WHERE PRODUCTNAME = ?";
    $paramsSelect = [$PRODUCTNAME];
    $resultSelect = sqlsrv_query($conn, $sqlSelect, $paramsSelect);
    if ($resultSelect === false) die(print_r(sqlsrv_errors(), true));

    $row = sqlsrv_fetch_array($resultSelect, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $filePath = $row['FILEPATH'];

        if (file_exists($filePath)) unlink($filePath);

        $sqlDelete = "DELETE FROM PRODUCT WHERE PRODUCTNAME = ?";
        $paramsDelete = [$PRODUCTNAME];
        $resultDelete = sqlsrv_query($conn, $sqlDelete, $paramsDelete);
        if ($resultDelete === false) die(print_r(sqlsrv_errors(), true));

        header("Location: Success.html");
        exit();
    } else {
        header("Location: Notfound.html");
        exit();
    }
}
?>
