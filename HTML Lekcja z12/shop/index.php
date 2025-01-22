<?php
session_start();
require_once '../utils.php';

/**
 * Gets cart items from session and product information
 * @return array An array of cart items with product data
 */
function getCartItems() {
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


   foreach($products as $product){
     $cartItems[] = array_merge($product, ['quantity'=>$_SESSION['cart'][$product['id']]['quantity']]);
    }
    return $cartItems;
}

$products = getActiveProducts();
$cartItems = getCartItems();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop - Premium Electronics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="shop-nav">
        <div class="nav-container">
            <a href="index.php" class="brand">TechShop</a>
            <div class="nav-links">
                <a href="index.php" class="nav-link">Products</a>
                <!-- <a href="product.php" class="nav-link">Products</a> -->
                <a href="cart.php">
                  <div class="cart-icon">
                      <i class="las la-shopping-cart text-2xl"></i>
                      <div class="cart-badge"><?= array_sum(array_column($cartItems, 'quantity')) ?></div>
                      <div class="cart-popup">
                          <!-- Cart popup content -->
                      </div>
                  </div>
                </a>  
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- <main class="product-grid">
        <?php foreach ($products as $product): ?>
            <article class="product-card">
                <img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="product-image">
                <div class="product-content">
                    <h3 class="product-title"><?= $product['title'] ?></h3>
                    <p class="product-description"><?= substr($product['description'], 0, 100) ?>...</p>
                    <div class="product-price">
                        $<?= number_format(($product['net_price'] * (1 + $product['vat']/100)) / 100, 2) ?>
                    </div>
                    <div class="flex gap-2">
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn-primary">Details</a>
                        <button class="btn-secondary">Add to Cart</button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </main> -->

    <div class="shop-container">
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" 
                     class="product-image mb-3">
                
                <h3 class="text-lg font-semibold mb-2"><?php echo $product['title']; ?></h3>
                
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    <?php echo $product['description']; ?>
                </p>
                
                <div class="flex items-center justify-between">
                    <span class="text-base font-bold text-blue-600">
                        $<?php echo number_format(($product['net_price'] * (1 + $product['vat']/100)) / 100, 2); ?>
                    </span>
                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                       class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700 transition-colors">
                        View
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
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