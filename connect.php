<?php
session_start();
$servername = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connection_options = ["Database" => "DLSUD", "Uid" => "", "PWD" => ""];
$conn = sqlsrv_connect($servername, $connection_options);

$USERNAME = $_POST['User'];
$PASSWORD = $_POST['Passkey'];

$sql = "SELECT USERNAME, PASSKEY, CATEGORY
        FROM ACCOUNT
        WHERE USERNAME = ? AND PASSKEY = ?";
$params = [$USERNAME, $PASSWORD];
$result = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($result);

if ($row === null) {
    header("Location: Invalid.html"); 
    exit;
}

$_SESSION['usertype'] = $row['CATEGORY']; // 'ADMIN' or 'EMPLOYEE'

if ($row['CATEGORY'] == 'ADMIN') {
    header("Location: Admin.html");
} else {
    header("Location: Employee.html");
}
exit;
?>
