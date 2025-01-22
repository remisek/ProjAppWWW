<?php
session_start();
require_once '../utils.php';


if (isset($_GET['id'])) {
    $product = getProductById($_GET['id']);
} else {
    header('Location: index.php');
    exit;
}

if (!$product) {
    echo "Product not found.";
    exit;
}

/**
 * Adds an item to the cart using $_SESSION
 * @param int $id The ID of the product.
 * @param int $quantity The quantity of the product.
 */
function addToCart($id, $quantity)
{
    global $db;

    // Get product stock
    $product = getProductById($id);
    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header("Location: product.php?id=" . $id);
        exit;
    }

    // Calculate total requested (existing cart quantity + new quantity)
    $currentCartQuantity = isset($_SESSION['cart'][$id]['quantity']) ? $_SESSION['cart'][$id]['quantity'] : 0;
    $totalRequested = $currentCartQuantity + $quantity;

    // Validate against stock
    if ($totalRequested > $product['stock_quantity']) {
        $_SESSION['error'] = "Requested quantity exceeds available stock (Max: {$product['stock_quantity']}).";
        header("Location: product.php?id=" . $id);
        exit;
    }

    // Add to cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$id]['quantity'] = $totalRequested;

    header("Location: product.php?id=" . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    addToCart($_GET['id'], $_POST['quantity']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="container mx-auto p-6 max-w-7xl">
    <a href="index.php" class="inline-flex items-center mb-8 text-indigo-600 hover:text-indigo-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to Products
    </a>

    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Product Image -->
            <div class="lg:w-1/2">
                <div class="aspect-w-1 aspect-h-1 bg-gray-50 rounded-xl overflow-hidden">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" 
                         class="w-full h-full object-contain p-6">
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:w-1/2 space-y-6">
                <h1 class="text-4xl font-bold text-gray-900"><?php echo $product['title']; ?></h1>
                
                <div class="space-y-4">
                    <p class="text-gray-600 text-lg leading-relaxed">
                        <?php echo $product['description']; ?>
                    </p>
                    
                    <div class="text-3xl font-semibold text-indigo-600">
                        €<?php echo number_format(($product['net_price'] * (1 + ($product['vat'] / 100))) / 100, 2); ?>
                    </div>
                </div>

                <form action="product.php?id=<?php echo $product['id']; ?>" method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">
                            Quantity
                            <span class="text-gray-400 ml-1">(Available: <?php echo $product['stock_quantity']; ?>)</span>
                        </label>
                        <input type="number"
                            name="quantity"
                            id="quantity"
                            value="1"
                            min="1"
                            max="<?php echo $product['stock_quantity']; ?>"
                            class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            required>
                    </div>

                    <button type="submit" name="add_to_cart" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg 
                                   transition-all transform hover:scale-[1.02] active:scale-95">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
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
                            <span class="text-sm">123 Tech Street, Silicon Valley, CA 94043</span>
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