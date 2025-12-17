<?php
session_start();

if (!isset($_SESSION['usertype'])) {
    header("Location: index.php"); // 
    exit;
}

$usertype = $_SESSION['usertype']; 

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = ["Database" => "DLSUD", "Uid" => "", "PWD" => ""];
$conn = sqlsrv_connect($serverName, $connectionOptions);

$category = isset($_GET['category']) ? $_GET['category'] : "All";
$sql = ($category === "All")
    ? "SELECT * FROM PRODUCT"
    : "SELECT * FROM PRODUCT WHERE CATEGORY = ?";
$params = ($category === "All") ? [] : [$category];

$result = sqlsrv_query($conn, $sql, $params);
$categoryLabel = ($category === "All") ? "All" : $category;

$serverName = "LAPTOP-N1K7OLOU\SQLEXPRESS01";
$connectionOptions = ["Database" => "DLSUD", "Uid" => "", "PWD" => ""];
$conn = sqlsrv_connect($serverName, $connectionOptions);

$category = isset($_GET['category']) ? $_GET['category'] : "All";
$sql = ($category === "All")
    ? "SELECT * FROM PRODUCT"
    : "SELECT * FROM PRODUCT WHERE CATEGORY = ?";
$params = ($category === "All") ? [] : [$category];

$result = sqlsrv_query($conn, $sql, $params);
$categoryLabel = ($category === "All") ? "All" : $category;
$category = isset($_GET['category']) ? $_GET['category'] : "All";

$sql = ($category === "All")
    ? "SELECT * FROM PRODUCT"
    : "SELECT * FROM PRODUCT WHERE CATEGORY = ?";

$params = ($category === "All") ? [] : [$category];

$result = sqlsrv_query($conn, $sql, $params);
$categoryLabel = ($category === "All") ? "All" : $category;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cherry on Top - Menu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:wght@400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">

<style>
body {
    background-image: url('Design/Chess.svg');
    background-size: cover;
    background-position: center;
    font-family: Arial, sans-serif;
    padding: 20px;
    overflow: hidden;
}

.menu-container {
    display: flex;
    flex-direction: column;
    height: 95vh;
    background: rgba(248, 247, 227, 0.95);
    border: 10px solid #7D8086;
    padding: 20px;
    border-radius: 15px;
}

.nav-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 4px solid #7D8086;
    border-radius: 10px;
    padding: 10px 20px;
    margin-bottom: 15px;
    background-image: url('Design/Lines.svg');
    background-size: cover;
    position: relative;
}

.nav-custom .logo {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
}

.nav-custom .logo img {
    width: 90px;
    height: auto;
    border: 3px solid #EE4F70;
    border-radius: 50%;
    object-fit: contain;
}

.nav-custom .navigation-buttons {
    display: flex;
    align-items: center;
    margin-left: 120px;
}

.filter-buttons button,
.navigation-buttons button {
    background-color: #EE4F70;
    color: #EEE980;
    border: 1px solid #7D8086;
    border-radius: 15px;
    cursor: pointer;
    height: 42px;
    width: auto;
    font-family: 'DM Serif Text', serif;
    font-style: italic;
    font-size: 20px;
    transition: transform 0.15s ease;
}

.filter-buttons button:hover,
.navigation-buttons button:hover {
    background-color: #E30B5D;
    transform: scale(1.05);
}

.filter-label {
    font-family: 'DM Serif Text', serif;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
    color: #444;
    line-height:1;
}

.products-wrapper {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-auto-rows: auto;
    gap: 20px; 
    overflow-y: auto;
    flex: 1;
    padding-bottom: 10px;
}

.product-card {
    background: #FFFDD8;
    border: 3px solid #EE4F70;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    padding: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

.product-card img {
    width: 100%;
    height: 180px;
    border-radius: 15px;
    object-fit: cover;
    border: 2px solid #7D8086;
    margin-bottom: 12px;
}

.product-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 6px;
}

.product-info h3 {
    font-family: "Delicious Handrawn", cursive;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 2px;
}

.product-info p {
    font-size: 14px;
    line-height: 1.2em;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 4.8em;
}

.category-box {
    background: #BEDDB2;
    color: #EE4F70;
    border: 1px solid #94BF94;
    border-radius: 5px;
    padding: 4px 6px;
    font-family: 'DM Serif Text', serif;
    font-style: italic;
    font-weight: bold;
    font-size: 20px;
    text-align: center;
    width: fit-content;
}

.price-box {
    font-family: 'DM Serif Text', serif;
    font-size: 18px;
    font-weight: bold;
}

.quantity-box {
    font-family: 'DM Serif Text', serif;
    font-size: 18px;
    display: flex;
    gap: 5px;
    align-items: center;
}

.quantity-box button {
    width: 28px;
    height: 28px;
    font-size: 16px;
    background: #EEE980;
    border: 1px solid #EE4F70;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

.quantity-box button:hover {
    background: #D6D173;
}

.quantity-box input {
    width: 35px;
    text-align: center;
    border-radius: 5px;
    border: 1px solid #7D8086;
}

.add-to-cart-btn {
    background: #EEE980;
    color: #EE4F70;
    border: 2px solid #EE4F70;
    padding: 6px 10px;
    border-radius: 10px;
    cursor: pointer;
    font-family: 'DM Serif Text', serif;
    font-size: 18px;
    width: 100%;
    margin-top: auto;
    transition: transform 0.15s ease;
}

.add-to-cart-btn:hover {
    background: #D6D173;
    transform: scale(1.03);
}

.popup {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #EE4F70;
    color: #EEE980;
    padding: 12px 20px;
    border: 3px solid #7D8086;
    border-radius: 10px;
    font-family: 'DM Serif Text', serif;
    font-weight: bold;
    font-size: 16px;
    opacity: 0;
    transition: opacity 0.5s ease;
    z-index: 9999;
}

.footer {
    background: #EE4F70;
    padding: 5px 0;
    font-family: 'DM Serif Text', serif;
    font-weight: bold;
    text-align: center;
    color: #EEE980;
    border-radius: 10px;
    margin-top: 20px;
}

.footer a {
    color: #EEE980;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
    color: #D6D173;
}
</style>
</head>
<body>
<div class="menu-container">

    <div class="nav-custom mb-3">
        <div class="d-flex align-items-center gap-3 navigation-buttons">
            <div class="logo"><img src="Design/Logo.png" alt="Logo"></div>
            <button onclick="location.href='<?= $usertype === 'ADMIN' ? 'Admin.html' : 'Employee.html' ?>'">Back</button>
            <button onclick="location.href='cart.php'">View Cart</button>
        </div>

        <div class="filter-buttons d-flex gap-3 flex-wrap">
            <button onclick="location.href='menu.php?category=All'">All</button>
            <button onclick="location.href='menu.php?category=Light Meals'">Light Meals</button>
            <button onclick="location.href='menu.php?category=Snacks'">Snacks</button>
            <button onclick="location.href='menu.php?category=Pastry'">Pastry</button>
            <button onclick="location.href='menu.php?category=Drinks'">Drinks</button>
            <button onclick="location.href='menu.php?category=Dessert'">Dessert</button>
            <button onclick="location.href='menu.php?category=Alcohol'">Alcohol</button>
            <button onclick="location.href='menu.php?category=Coffee'">Coffee</button>
            <button onclick="location.href='menu.php?category=Tea'">Tea</button>
        </div>
    </div>

    <div class="filter-label"><?= htmlspecialchars($categoryLabel) ?></div>

    <div class="products-wrapper">
        <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) : ?>
            <div class="product-card d-flex flex-column">
                <img src="<?= htmlspecialchars($row['FILEPATH']) ?>" alt="Product">
                <div class="product-info">
                    <h3><?= htmlspecialchars($row['PRODUCTNAME']) ?></h3>
                    <p><?= htmlspecialchars($row['DESCRIPTION']) ?></p>
                    <div class="category-box"><?= htmlspecialchars($row['CATEGORY']) ?></div>
                    <div class="price-box">₱<?= number_format($row['PRICE'], 2) ?></div>
                </div>

                <form action="add_to_cart.php" method="POST" class="d-flex flex-column mt-2 mb-2">
                    <div class="quantity-box">
                        <button type="button" onclick="decreaseQty(this)">−</button>
                        <input type="number" name="Quantity" min="1" value="1">
                        <button type="button" onclick="increaseQty(this)">+</button>
                    </div>
                    <input type="hidden" name="ProductName" value="<?= htmlspecialchars($row['PRODUCTNAME']) ?>">
                    <input type="hidden" name="Price" value="<?= $row['PRICE'] ?>">
                    <button type="submit" class="add-to-cart-btn mt-2">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <footer class="footer mt-auto">
        <a href="https://www.instagram.com/pot.2x/" target="_blank">Meet the owner</a>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>

function decreaseQty(btn) {
    const input = btn.nextElementSibling;
    if (parseInt(input.value) > parseInt(input.min)) {
        input.value = parseInt(input.value) - 1;
    }
    animateInput(input);
}

function increaseQty(btn) {
    const input = btn.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    animateInput(input);
}

function animateInput(input) {
    input.style.transition = 'all 0.1s ease';
    input.style.transform = 'scale(1.2)';
    setTimeout(() => input.style.transform = 'scale(1)', 100);
}

function animateInput(input) {
    input.style.transition = 'all 0.1s ease';
    input.style.transform = 'scale(1.2)';
    setTimeout(() => input.style.transform = 'scale(1)', 100);
}



document.querySelectorAll('.quantity-box input').forEach(input => {
    input.addEventListener('input', () => {
        if (parseInt(input.value) < 1 || isNaN(parseInt(input.value))) input.value = 1;
    });
});


</script>
<div class="popup" id="popup"></div>

<script>

function decreaseQty(btn) {
    const input = btn.nextElementSibling;
    if (parseInt(input.value) > parseInt(input.min)) {
        input.value = parseInt(input.value) - 1;
    }
    animateInput(input);
}

function increaseQty(btn) {
    const input = btn.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    animateInput(input);
}

function animateInput(input) {
    input.style.transition = 'all 0.1s ease';
    input.style.transform = 'scale(1.2)';
    setTimeout(() => input.style.transform = 'scale(1)', 100);
}


document.querySelectorAll('.quantity-box input').forEach(input => {
    input.addEventListener('input', () => {
        if (parseInt(input.value) < 1 || isNaN(parseInt(input.value))) input.value = 1;
    });
});

function showPopup(message, duration = 3000) {
    const popup = document.getElementById('popup');
    popup.textContent = message;
    popup.style.display = 'block';
    
    setTimeout(() => popup.style.opacity = '1', 50);

    setTimeout(() => {
        popup.style.opacity = '0';
        setTimeout(() => popup.style.display = 'none', 500);
    }, duration);
}


document.querySelectorAll('form[action="add_to_cart.php"]').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault(); 

        const qty = form.querySelector('input[name="Quantity"]').value;
        const product = form.querySelector('input[name="ProductName"]').value;

        showPopup(`Added ${qty} x ${product} to cart!`, 2000);

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            console.log('Cart updated:', data);
        })
        .catch(err => console.error('Error adding to cart:', err));
    });
});
</script>


</body>
</html>
