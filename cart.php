<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$subtotal_total = 0;
foreach ($cart as $item) {
    $subtotal_total += $item['qty'] * $item['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cherry on Top - Cart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { 
    background-image: url('Design/Chess.svg'); 
    background-size: cover; 
    background-position: center; 
    font-family: 'Poppins', sans-serif; 
    padding: 20px; 
    overflow-x: hidden; 
}

.cart-container { 
    display: flex; 
    flex-direction: column; 
    min-height: 95vh; 
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
    gap: 3px; 
    margin-left: 120px; 
}

.nav-custom button { 
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

.nav-custom button:hover { 
    background-color: #E30B5D; 
    transform: scale(1.05); 
}

h2 { 
    text-align: center; 
    font-family: 'DM Serif Text', serif; 
    font-size: 32px; 
    margin-bottom: 20px; 
}

.no-order-label { 
    text-align:center; 
    font-size: 18px; 
}

.no-order-label a { 
    color: #EE4F70; 
    text-decoration: none; 
    font-weight: bold; 
}

.no-order-label a:hover { 
    color: #E30B5D; 
    text-decoration: underline; 
}

.cart-table { 
    width: 100%; 
    border-collapse: collapse;
    margin-top: 20px; 
    background: #FFFDD8; 
    border: 3px solid #EE4F70; 
    border-radius: 10px; 
    overflow: hidden; 
    font-size: 16px; 
}

.cart-table th, 
.cart-table td { 
    padding: 12px; 
    border-bottom: 1px solid #EE4F70; 
    text-align: center; 
}

.cart-table th { 
    background: #EE4F70; 
    color: #EEE980; 
    font-family: 'DM Serif Text', serif; 
    font-weight: bold; 
}

.cart-table td button { 
    background: #EEE980; 
    color: #EE4F70; 
    border: 1px solid #EE4F70; 
    border-radius: 15px; 
    padding: 4px 10px; 
    cursor: pointer; 
    font-family: 'DM Serif Text', serif; 
    font-weight: bold; 
    transition: transform 0.15s ease; 
}

.cart-table td button:hover { 
    background: #D6D173; 
    transform: scale(1.05); 
}

.total-box { 
    font-weight: bold; 
    font-size: 18px; 
    text-align: right; 
    margin-top: 15px; 
    color: #444; 
}

.checkout-form { 
    display: flex; 
    flex-direction: column; 
    align-items: flex-end; 
    gap: 12px; 
    margin-top: 15px; 
    font-size: 16px;
}

.checkout-form label { 
    margin-right: 10px; 
    font-family: 'DM Serif Text', serif; 
    font-weight: 500; 
}

.checkout-form input, 
.checkout-form select { 
    padding: 6px 8px; 
    border-radius: 8px; 
    border: 1px solid #7D8086; 
    width: 200px; 
}

.checkout-form button { 
    background: #EEE980; 
    color: #EE4F70; border: 2px solid #EE4F70; 
    border-radius: 15px; 
    padding: 10px 20px; 
    cursor: pointer; 
    font-family: 'DM Serif Text', serif; 
    font-weight: bold; 
    font-size: 16px; 
    transition: transform 0.15s ease; 
}

.checkout-form button:hover { 
    background: #D6D173; 
    transform: scale(1.03); 
}

.footer { 
    background: #EE4F70; 
    padding: 12px 0; 
    text-align: center; 
    font-weight: bold; 
    color: #EEE980; 
    border-radius: 10px; 
    margin-top: 25px; 
}

.footer a { 
    color: #EEE980; 
    text-decoration: none; 
    font-weight: bold; 
}

.footer a:hover { 
    color: #D6D173; 
    text-decoration: underline; 
}

.modal-footer button { 
    background-color: #EE4F70; 
    color: #EEE980; 
    border: 1px solid #7D8086; 
    border-radius: 15px; 
    padding: 8px 15px; 
    font-family: 'DM Serif Text', serif;  
    font-size: 16px; 
    cursor: pointer; 
    transition: transform 0.15s ease; 
}

.modal-footer button:hover { 
    background-color: #E30B5D !important; 
    transform: scale(1.05); 
}

</style>
</head>
<body>

<div class="cart-container">
    <div class="nav-custom mb-3">
        <div class="d-flex align-items-center gap-3 navigation-buttons">
            <div class="logo"><img src="Design/Logo.png" alt="Logo"></div>
            <button onclick="location.href='menu.php'">Back</button>
        </div>
    </div>

    <h2>Your Cart</h2>

    <?php if(empty($cart)) : ?>
        <div class="no-order-label">
            <p>MAKE YOUR FIRST ORDER NOW!</p><br>
            <a href="menu.php">Go back to menu</a>
        </div>
    <?php else: ?>
    <table class="cart-table">
        <tr>
            <th>Name</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cart as $index => $item): 
            $subtotal = $item['qty'] * $item['price'];
        ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>
                <form action="update_cart.php" method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="hidden" name="action" value="decrease">
                    <button type="submit">−</button>
                </form>
                <?= $item['qty'] ?>
                <form action="update_cart.php" method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="hidden" name="action" value="increase">
                    <button type="submit">+</button>
                </form>
            </td>
            <td>₱<?= number_format($item['price'],2) ?></td>
            <td>₱<?= number_format($subtotal,2) ?></td>
            <td>
                <form action="update_cart.php" method="POST">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit">Remove</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="checkout-form">
        <form id="checkoutForm" action="checkout.php" method="POST">
            <label>Customer Name:</label>
            <input type="text" name="customer" required>

            <label>Customer Type:</label>
            <select name="customer_type" id="customerType" required>
                <option value="Regular">Regular</option>
                <option value="PWD">PWD</option>
                <option value="Student">Student</option>
                <option value="Senior">Senior Citizen</option>
            </select>

            <p class="total-box">Item Total: ₱<span id="baseTotal"><?= number_format($subtotal_total/1.12,2) ?></span></p>
            <p class="total-box">Tax (12% included): ₱<span id="taxAmount"><?= number_format($subtotal_total - ($subtotal_total/1.12),2) ?></span></p>
            <p class="total-box" id="discountRow" style="display:none;">Discount: -₱<span id="discountAmount">0.00</span></p>
            <p class="total-box">Total: ₱<span id="totalAmount"><?= number_format($subtotal_total,2) ?></span></p>

            <label>Payment:</label>
            <input type="number" name="payment" id="payment" step="0.01" required>

            <input type="hidden" name="total_before_discount" id="totalBeforeDiscount" value="<?= $subtotal_total ?>">
            <input type="hidden" name="tax_amount" id="taxAmountInput" value="<?= $subtotal_total - ($subtotal_total/1.12) ?>">
            <input type="hidden" name="discount_rate" id="discountRate" value="0">

            <button type="button" data-bs-toggle="modal" data-bs-target="#confirmModal">Proceed to Checkout</button>
        </form>
    </div>
    <?php endif; ?>

    <footer class="footer mt-auto">
        <a href="https://www.instagram.com/pot.2x/" target="_blank">Meet the owner</a>
    </footer>
</div>


<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to proceed to checkout? Please confirm your order details.
      </div>
      <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmCheckoutBtn">Yes, Checkout</button>
      </div>
    </div>
  </div>
</div>

<script>
const subtotalTotal = <?= $subtotal_total ?>;
const taxRate = 0.12;
const discountableTypes = ['PWD','Student','Senior'];
const customerType = document.getElementById('customerType');
const baseTotalElem = document.getElementById('baseTotal');
const taxAmountElem = document.getElementById('taxAmount');
const discountRow = document.getElementById('discountRow');
const discountAmountElem = document.getElementById('discountAmount');
const totalAmountElem = document.getElementById('totalAmount');
const discountRateInput = document.getElementById('discountRate');
const paymentInput = document.getElementById('payment');

function updateTotal() {
    const type = customerType.value;
    const baseTotal = subtotalTotal / (1 + taxRate);
    const taxAmount = subtotalTotal - baseTotal;
    let discount = 0;

    if(discountableTypes.includes(type)) {
        discount = subtotalTotal * 0.20;
        discountRow.style.display = 'block';
    } else {
        discountRow.style.display = 'none';
    }

    discountAmountElem.textContent = discount.toFixed(2);
    baseTotalElem.textContent = baseTotal.toFixed(2);
    taxAmountElem.textContent = taxAmount.toFixed(2);

    const grandTotal = subtotalTotal - discount;
    totalAmountElem.textContent = grandTotal.toFixed(2);

    discountRateInput.value = discount / subtotalTotal;
    paymentInput.min = Math.ceil(grandTotal);
}

updateTotal();
customerType.addEventListener('change', updateTotal);


const confirmCheckoutBtn = document.getElementById('confirmCheckoutBtn');
const checkoutForm = document.getElementById('checkoutForm');

confirmCheckoutBtn.addEventListener('click', function() {
    checkoutForm.submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
