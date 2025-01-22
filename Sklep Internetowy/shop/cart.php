<?php
session_start();
require_once '../utils.php';

/**
 * Gets cart items from session and product information
 * @return array An array of cart items with product data
 */
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

/**
 * Updates quantity of product in the cart
 * @param int $id The ID of the product.
 * @param int $quantity The new quantity of the product.
 */
function updateCartItem($id, $quantity)
{
    $id = (int)$id;
    $quantity = (int)$quantity;

    if (isset($_SESSION['cart'][$id])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        }
    }

    header("Location: cart.php");
    exit;
}

/**
 * Removes a product from the cart
 * @param int $id The ID of the product to remove.
 */
function removeCartItem($id)
{
    $id = (int)$id;

    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        updateCartItem($_POST['product_id'], $_POST['quantity']);
    } elseif (isset($_POST['remove_item'])) {
        removeCartItem($_POST['product_id']);
    }
}

$cartItems = getCartItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <style>
        .quantity-input {
            -moz-appearance: textfield;
            width: 50px;
            text-align: center;
        }
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Shopping Cart</h1>

        <?php if (empty($cartItems)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="las la-shopping-cart text-6xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg mb-4">Your cart is empty</p>
                <a href="index.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista produktów -->
                <div class="lg:col-span-2">
                    <?php foreach ($cartItems as $item): 
                        $price = ($item['net_price'] * (1 + $item['vat']/100)) / 100;
                        $total += $price * $item['quantity'];
                    ?>
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" class="w-20 h-20 object-cover rounded-lg">
                                <div>
                                    <h3 class="font-semibold text-gray-800"><?= $item['title'] ?></h3>
                                    <p class="text-blue-600 font-bold">$<?= number_format($price, 2) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <form method="post" action="cart.php" class="flex items-center">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="bg-gray-200 px-3 py-1 rounded-l">-</button>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                           class="quantity-input border-y bg-white text-center" min="1">
                                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="bg-gray-200 px-3 py-1 rounded-r">+</button>
                                </form>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="remove_item" class="text-red-600 hover:text-red-700">
                                        <i class="las la-trash-alt text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Podsumowanie -->
                <div class="bg-white rounded-lg shadow-md p-6 h-fit sticky top-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-green-600">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span>$0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="checkout.php" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Checkout
                        </a>
                        <a href="index.php" class="block w-full border border-gray-300 text-gray-600 text-center py-3 rounded-lg hover:bg-gray-50 transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stopka -->
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