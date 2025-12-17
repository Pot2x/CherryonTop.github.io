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


$PRODUCTNAME = trim($_POST['ProductName']);
$NEWNAME = trim($_POST['NewName']);
$PRICE = trim($_POST['Price']);
$CATEGORY = trim($_POST['Category']);
$DESCRIPTION = trim($_POST['Description']);

if (empty($PRODUCTNAME)) {
    die("Error: Product Name to edit must be provided.");
}

$sqlSelect = "SELECT FILEPATH, FILENAME FROM PRODUCT WHERE PRODUCTNAME = ?";
$paramsSelect = [$PRODUCTNAME];
$resultSelect = sqlsrv_query($conn, $sqlSelect, $paramsSelect);
if ($resultSelect === false) die(print_r(sqlsrv_errors(), true));

$row = sqlsrv_fetch_array($resultSelect, SQLSRV_FETCH_ASSOC);
if (!$row) Header("Location: Notfound.html");

$oldFilePath = $row['FILEPATH'];
$filename = $row['FILENAME']; 
$path = $oldFilePath;


if (!empty($_FILES['Photo']['name'])) {
    $destination = "Products/";
    $filename = basename($_FILES['Photo']['name']);
    $path = $destination . $filename;

    if (file_exists($oldFilePath)) unlink($oldFilePath);

    $allowtypes = ['jpg', 'jpeg', 'png', 'pdf'];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($filetype, $allowtypes)) die("Error: Invalid file type.");

    if (!move_uploaded_file($_FILES['Photo']['tmp_name'], $path)) {
        Header("Location: Wrongfile.html");
    }
}

$updateFields = [];
$params = [];

if (!empty($NEWNAME)) {
    $updateFields[] = "PRODUCTNAME = ?";
    $params[] = $NEWNAME;
}
if (!empty($PRICE)) {
    $updateFields[] = "PRICE = ?";
    $params[] = $PRICE;
}
if (!empty($CATEGORY)) {
    $updateFields[] = "CATEGORY = ?";
    $params[] = $CATEGORY;
}
if (!empty($DESCRIPTION)) {
    $updateFields[] = "DESCRIPTION = ?";
    $params[] = $DESCRIPTION;
}
if (!empty($_FILES['Photo']['name'])) {
    $updateFields[] = "FILENAME = ?";
    $params[] = $filename;
    $updateFields[] = "FILEPATH = ?";
    $params[] = $path;
}


if (count($updateFields) == 0) {
    Header("Location: Nofields.html");
    exit();
}

$sqlUpdate = "UPDATE PRODUCT SET " . implode(", ", $updateFields) . " WHERE PRODUCTNAME = ?";
$params[] = $PRODUCTNAME;

$resultUpdate = sqlsrv_query($conn, $sqlUpdate, $params);
if ($resultUpdate === false) die(print_r(sqlsrv_errors(), true));

header("Location: Success.html");
exit();
?>
