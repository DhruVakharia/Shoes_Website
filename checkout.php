<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment-method'];
    $card_details = $_POST['card-details'];

    // Insert customer details into the database
    $stmt = $conn->prepare("INSERT INTO customers (name, email, address, payment_method, card_details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $address, $payment_method, $card_details);
    $stmt->execute();

    // Get the customer ID
    $customer_id = $stmt->insert_id;

    // Calculate total price (you should replace this with actual logic to get the total from the cart)
    $total_price = 150.00; // Example total price

    // Insert order into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_price) VALUES (?, ?)");
    $stmt->bind_param("id", $customer_id, $total_price);
    $stmt->execute();

    // Redirect to confirmation page
    header("Location: order-confirmed.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Commerce Shoes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>E-Commerce Shoes</h1>
        </div>
        <div class="cart-icon">
            <a href="cart.html">
                <img src="cart-icon.webp" alt="Cart" width="30px">
            </a>
        </div>
    </header>

    <section class="checkout">
        <h2>Checkout</h2>

        <div class="checkout-details">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="address">Shipping Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="credit-card">Credit Card</option>
                        <option value="debit-card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="card-details">Card Details:</label>
                    <input type="text" id="card-details" name="card-details" placeholder="Card Number" required>
                </div>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <p>Total items: <span id="total-items">0</span></p>
                    <p>Total price: <span id="total-price">$0.00</span></p>
                </div>

                <button type="submit" class="checkout-btn" >Place Order</button>
            </form>
        </div>
    </section>

    <footer>
        <p>© 2024 E-Commerce Shoes. All rights reserved.</p>
    </footer>

    <script>
        // Function to load cart items and calculate totals
        function loadCartSummary() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = 0;
            let totalPrice = 0;

            cart.forEach(item => {
                totalItems += item.quantity;
                totalPrice += item.price * item.quantity;
            });

            document.getElementById('total-items').innerText = totalItems;
            document.getElementById('total-price').innerText = `$${totalPrice.toFixed(2)}`;
        }

        // Function to handle order placement
        function placeOrder() {
            // You can add more order processing logic here
            // Redirect to order confirmation page
            window.location.href = 'order-confirmed.html';
            return false; // Prevent form submission
        }

        // Load the cart summary when the page is loaded
        window.onload = loadCartSummary;
    </script>
</body>
</html>