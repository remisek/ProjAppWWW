<?php
// Enable error reporting for debugging (remove for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../utils.php';

function getCartItems()
{
    global $db;
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    $cart = $_SESSION['cart'];
    $cartItems = [];

    $placeholders = implode(',', array_fill(0, count($cart), '?'));

    $stmt = $db->prepare("SELECT id, title, image, net_price, vat FROM products WHERE id IN ($placeholders)");
    $i = 1;
    foreach (array_keys($cart) as $key) {
        $stmt->bindValue($i, $key);
        $i++;
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $cartItems[] = array_merge($product, ['quantity' => $_SESSION['cart'][$product['id']]['quantity']]);
    }
    return $cartItems;
}

$cartItems = getCartItems();
$total = 0;

foreach ($cartItems as $item) {
    $price = ($item['net_price'] * (1 + $item['vat']/100)) / 100;
    $total += $price * $item['quantity'];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Walidacja danych
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $zip = trim($_POST['zip'] ?? '');
    $cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $cardExp = trim($_POST['card_exp'] ?? '');
    $cardCvc = trim($_POST['card_cvc'] ?? '');

    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($city)) $errors[] = "City is required";
    if (!preg_match('/^\d{2}-\d{3}$/', $zip)) $errors[] = "ZIP Code must be in format 00-000";
    if (!preg_match('/^\d{16}$/', $cardNumber)) $errors[] = "Invalid card number";
    if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $cardExp)) $errors[] = "Invalid expiration date (MM/YY)";
    if (!preg_match('/^\d{3,4}$/', $cardCvc)) $errors[] = "Invalid CVC";

    if (empty($errors)) {
        // W rzeczywistej implementacji tutaj byłaby integracja z płatnościami
        // Tymczasowo tylko czyszczenie koszyka
        unset($_SESSION['cart']);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
</head>
<body class="bg-gray-50">
    <!-- Nagłówek -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="text-2xl font-bold text-gray-800">TechShop</a>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="products.php" class="text-gray-600 hover:text-blue-600">Products</a>
                    <a href="about.php" class="text-gray-600 hover:text-blue-600">About</a>
                    <a href="cart.php" class="relative text-gray-600 hover:text-blue-600">
                        <i class="las la-shopping-cart text-2xl"></i>
                        <span class="absolute -top-2 -right-2 bg-blue-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                            <?= array_sum(array_column($cartItems, 'quantity')) ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Główna zawartość -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <?php if ($success): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="las la-check-circle text-6xl text-green-500 mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Order Confirmed!</h1>
                <p class="text-gray-600 mb-4">Thank you for your purchase. A confirmation email has been sent to <?= htmlspecialchars($email) ?></p>
                <a href="index.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formularz -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout Details</h2>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php foreach ($errors as $error): ?>
                                <p><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="space-y-4">
                        <!-- Shipping Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Shipping Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <input type="text" name="address" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                        <input type="text" name="city" required
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                                        <input type="text" name="zip" pattern="\d{2}-\d{3}" placeholder="00-000" required
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Payment Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                    <input type="text" name="card_number" pattern="\d{16}" placeholder="0000 0000 0000 0000" required
                                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           value="<?= htmlspecialchars($_POST['card_number'] ?? '') ?>">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                                        <input type="text" name="card_exp" pattern="\d{2}/\d{2}" placeholder="MM/YY" required
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               value="<?= htmlspecialchars($_POST['card_exp'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                                        <input type="text" name="card_cvc" pattern="\d{3,4}" placeholder="123" required
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               value="<?= htmlspecialchars($_POST['card_cvc'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                            Complete Order ($<?= number_format($total, 2) ?>)
                        </button>
                    </form>
                </div>

                <!-- Podsumowanie zamówienia -->
                <div class="bg-white rounded-lg shadow-md p-6 h-fit sticky top-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" class="w-12 h-12 object-cover rounded">
                                    <span><?= $item['title'] ?> × <?= $item['quantity'] ?></span>
                                </div>
                                <span>$<?= number_format(($item['net_price'] * (1 + $item['vat']/100) * $item['quantity']) / 100, 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="my-4">
                    <div class="space-y-3">
                        <div class="flex justify-between font-bold">
                            <span>Total:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="shop-footer bg-gray-900 text-gray-300 py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- About Section -->
                <div class="footer-section mb-8 md:mb-0">
                    <h3 class="text-xl font-semibold text-white mb-4 border-b-2 border-blue-600 pb-2">About TechShop</h3>
                    <p class="text-sm leading-relaxed">Premium electronics store offering the latest tech gadgets and accessories since 2015. Committed to quality and customer satisfaction.</p>
                    <div class="social-icons mt-4 flex space-x-4">
                        <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="lab la-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="lab la-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="lab la-instagram text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section mb-8 md:mb-0">
                    <h3 class="text-xl font-semibold text-white mb-4 border-b-2 border-blue-600 pb-2">Quick Links</h3>
                    <div class="footer-links space-y-2">
                        <a href="#" class="block hover:text-blue-400 transition-colors text-sm">
                            <i class="las la-shipping-fast mr-2"></i>Shipping Policy
                        </a>
                        <a href="#" class="block hover:text-blue-400 transition-colors text-sm">
                            <i class="las la-exchange-alt mr-2"></i>Returns & Exchanges
                        </a>
                        <a href="#" class="block hover:text-blue-400 transition-colors text-sm">
                            <i class="las la-question-circle mr-2"></i>FAQs
                        </a>
                        <a href="#" class="block hover:text-blue-400 transition-colors text-sm">
                            <i class="las la-lock mr-2"></i>Privacy Policy
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="footer-section">
                    <h3 class="text-xl font-semibold text-white mb-4 border-b-2 border-blue-600 pb-2">Contact Us</h3>
                    <div class="footer-links space-y-3">
                        <div class="flex items-center">
                            <i class="las la-map-marker-alt mr-3 text-blue-400"></i>
                            <span class="text-sm">WMiI Olsztyn</span>
                        </div>
                        <div class="flex items-center">
                            <i class="las la-phone mr-3 text-blue-400"></i>
                            <span class="text-sm">(555) 123-4567</span>
                        </div>
                        <div class="flex items-center">
                            <i class="las la-envelope mr-3 text-blue-400"></i>
                            <a href="mailto:support@techshop.com" class="text-sm hover:text-blue-400 transition-colors">support@techshop.com</a>
                        </div>
                        <div class="flex items-center">
                            <i class="las la-clock mr-3 text-blue-400"></i>
                            <span class="text-sm">Mon-Fri: 9AM - 6PM PST</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-6 text-center">
                <p class="text-xs text-gray-500">
                    &copy; 2025 TechShop. Remigiusz Sęk. All rights reserved. 
                    <a href="#" class="hover:text-blue-400 transition-colors">Terms of Service</a> | 
                    <a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>