<?php

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "DLSUD",
    "Uid" => "",
    "PWD" => ""
];
$conn = sqlsrv_connect($serverName, $connectionOptions);

$PRODUCTNAME = $_POST['ProductName'];
$PRICE = $_POST['Price'];
$CATEGORY = $_POST['Category'];
$DESCRIPTION = $_POST['Description'];
if (empty($PRODUCTNAME) || empty($PRICE) || empty($CATEGORY) || empty($DESCRIPTION)){
    die("Error: All product fields must be filled out.");
}
$destination = "Products/";
$filename = basename($_FILES['Photo']['name']);
$path = $destination . $filename;

$allowtypes = array('jpg', 'png', 'jpeg', 'pdf', 'JPG', 'PNG', 'JPEG', 'PDF');
$filetype = pathinfo($path, PATHINFO_EXTENSION);

if (in_array(($filetype), $allowtypes)) {
    $finalfolder = move_uploaded_file($_FILES['Photo']['tmp_name'], $path);

    if ($finalfolder) {

    $sql = "INSERT INTO PRODUCT (PRODUCTNAME, PRICE, CATEGORY ,DESCRIPTION, FILENAME, FILEPATH, DATE_UPLOADED)
            VALUES ('$PRODUCTNAME', '$PRICE', '$CATEGORY','$DESCRIPTION', '$filename', '$path', GETDATE())";

    $result = sqlsrv_query($conn, $sql);

    if ($result) {
        Header("Location: Success.html");
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
}

}
?>

