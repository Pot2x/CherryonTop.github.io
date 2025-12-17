<?php
session_start();

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = ["Database" => "DLSUD", "Uid" => "", "PWD" => ""];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) die(print_r(sqlsrv_errors(), true));

$view  = $_GET['view'] ?? 'daily';
$year  = intval($_GET['year'] ?? date('Y'));
$month = intval($_GET['month'] ?? date('m'));

$totalSalesSum = 0;
$totalOrdersSum = 0;

if ($view === 'daily') {
    $title = "Daily Sales & Orders";
    $sql = "
        SELECT 
            CAST(DATE_ORDERED AS DATE) AS Label,
            SUM(TOTAL) AS TotalSales,
            COUNT(Order_ID) AS OrderCount
        FROM ORDERS
        WHERE YEAR(DATE_ORDERED) = ? AND MONTH(DATE_ORDERED) = ?
        GROUP BY CAST(DATE_ORDERED AS DATE)
        ORDER BY CAST(DATE_ORDERED AS DATE)
    ";
    $params = [$year, $month];
    $chartType = 'line';
} elseif ($view === 'weekly') {
    $title = "Weekly Sales & Orders";
    $sql = "
        SELECT 
            CONCAT('Week ', DATEPART(WEEK, DATE_ORDERED)) AS Label,
            SUM(TOTAL) AS TotalSales,
            COUNT(Order_ID) AS OrderCount
        FROM ORDERS
        WHERE YEAR(DATE_ORDERED) = ?
        GROUP BY DATEPART(WEEK, DATE_ORDERED)
        ORDER BY DATEPART(WEEK, DATE_ORDERED)
    ";
    $params = [$year];
    $chartType = 'line';
} elseif ($view === 'monthly') {
    $title = "Monthly Sales & Orders";
    $sql = "
        SELECT 
            MONTH(DATE_ORDERED) AS MonthNum,
            DATENAME(MONTH, DATE_ORDERED) AS Label,
            SUM(TOTAL) AS TotalSales,
            COUNT(Order_ID) AS OrderCount
        FROM ORDERS
        WHERE YEAR(DATE_ORDERED) = ?
        GROUP BY MONTH(DATE_ORDERED), DATENAME(MONTH, DATE_ORDERED)
        ORDER BY MONTH(DATE_ORDERED)
    ";
    $params = [$year];
    $chartType = 'bar';
} else {
    $title = "Yearly Sales & Orders";
    $sql = "
        SELECT 
            CAST(YEAR(DATE_ORDERED) AS VARCHAR) AS Label,
            SUM(TOTAL) AS TotalSales,
            COUNT(Order_ID) AS OrderCount
        FROM ORDERS
        GROUP BY YEAR(DATE_ORDERED)
        ORDER BY YEAR(DATE_ORDERED)
    ";
    $params = [];
    $chartType = 'bar';
}

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) die(print_r(sqlsrv_errors(), true));

$labels = $sales = $orders = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $label = $row['Label'];
    if ($label instanceof DateTime) $label = $label->format('Y-m-d');
    $labels[] = $label;
    $sales[]  = floatval($row['TotalSales'] ?? 0);
    $orders[] = intval($row['OrderCount'] ?? 0);
    $totalSalesSum  += floatval($row['TotalSales'] ?? 0);
    $totalOrdersSum += intval($row['OrderCount'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:wght@400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { font-family: 'Poppins', sans-serif; background: url('Design/Chess.svg') no-repeat center center fixed; background-size: cover; }
.card-wrapper { background: rgba(248,247,227,.95); border: 5px solid #7D8086; border-radius: 15px; padding: 25px; max-width: 1100px; margin: 50px auto; }
h1 { font-family: 'DM Serif Text', serif; color: #EE4F70; text-align: center; }
.summary { display: flex; gap: 20px; margin-bottom: 25px; }
.summary-card { flex: 1; background: #FFFDD8; border: 3px solid #EE4F70; border-radius: 12px; padding: 15px; text-align: center; }
.summary-card h4 { font-family: 'DM Serif Text', serif; color: #EE4F70; }
canvas { background: #FFFDD8; border: 3px solid #EE4F70; border-radius: 12px; padding: 15px; }
.filter-buttons button, .navigation-buttons button { background-color: #EE4F70; color: #EEE980; border: 1px solid #7D8086; border-radius: 15px; cursor: pointer; height: 42px; width: auto; font-family: 'DM Serif Text', serif; font-style: italic; font-size: 20px; transition: transform 0.15s ease; }
.filter-buttons button:hover, .navigation-buttons button:hover { background-color: #E30B5D; transform: scale(1.05); }
select { border-radius: 10px; padding: 6px 12px; }
</style>
</head>
<body>

<div class="card-wrapper">
<div class="navigation-buttons mb-3">
    <button onclick="location.href='Admin.html'">⬅ Back</button>
</div>

<h1><?= $title ?></h1>

<form method="GET" class="filter-buttons text-center mb-4">
    <select name="view" id="view" onchange="this.form.submit()">
        <option value="daily" <?= $view=='daily'?'selected':'' ?>>Daily</option>
        <option value="weekly" <?= $view=='weekly'?'selected':'' ?>>Weekly</option>
        <option value="monthly" <?= $view=='monthly'?'selected':'' ?>>Monthly</option>
    </select>
    <?php if($view=='daily'): ?>
    <select name="month" id="monthSelect">
        <?php for($m=1;$m<=12;$m++): ?>
            <option value="<?= $m ?>" <?= $m==$month?'selected':'' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
        <?php endfor; ?>
    </select>
    <?php endif; ?>
    <select name="year">
        <?php for($y=date('Y')-5;$y<=date('Y');$y++): ?>
            <option value="<?= $y ?>" <?= $y==$year?'selected':'' ?>><?= $y ?></option>
        <?php endfor; ?>
    </select>
</form>

<div class="summary">
    <div class="summary-card">
        <h4>Total Sales</h4>
        <p>₱<?= number_format($totalSalesSum, 2) ?></p>
    </div>
    <div class="summary-card">
        <h4>Total Orders</h4>
        <p><?= $totalOrdersSum ?></p>
    </div>
</div>

<canvas id="chart"></canvas>
</div>

<script>
new Chart(document.getElementById('chart'), {
    type: '<?= $chartType ?>',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            { label: 'Sales (₱)', data: <?= json_encode($sales) ?>, backgroundColor: 'rgba(238,79,112,0.4)', borderColor: '#7D8086', borderWidth: 2 },
            { label: 'Orders', data: <?= json_encode($orders) ?>, backgroundColor: 'rgba(125,128,134,0.4)', borderColor: '#7D8086', borderWidth: 2 }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>

</body>
</html>
