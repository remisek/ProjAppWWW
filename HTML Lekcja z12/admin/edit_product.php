<?php
session_start();
require_once '../utils.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product = getProductById($_GET['id']);
if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    updateProduct(
        (int)$_POST['product_id'],
        sanitizeInput($_POST['title']),
        sanitizeInput($_POST['description']),
        (float)$_POST['net_price'],
        (float)$_POST['vat'],
        (int)$_POST['stock_quantity'],
        (int)$_POST['status'],
        (int)$_POST['category'],
        sanitizeInput($_POST['image'])
    );
    header("Location: products.php");
    exit;
}

$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-indigo-50 min-h-screen">
    <div class="container mx-auto p-6 max-w-4xl font-[Inter]">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent mb-8">
                Edit Product: <?php echo $product['title']; ?>
                <span class="block w-20 h-1.5 bg-purple-500 mt-2 rounded-full"></span>
            </h1>
            
            <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post" class="space-y-6">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-purple-900 mb-2">Product Title</label>
                            <input type="text" name="title" id="title" 
                                   value="<?php echo htmlspecialchars($product['title']); ?>" 
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-purple-900 mb-2">Description</label>
                            <textarea name="description" id="description" 
                                      class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 h-32 resize-none transition-all outline-none"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-semibold text-purple-900 mb-2">Image URL</label>
                            <input type="text" name="image" id="image" 
                                   value="<?php echo htmlspecialchars($product['image']); ?>" 
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="net_price" class="block text-sm font-semibold text-purple-900 mb-2">Net Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-500">$</span>
                                <input type="number" step="0.01" name="net_price" id="net_price" 
                                       value="<?php echo number_format($product['net_price'] / 100, 2); ?>" 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="vat" class="block text-sm font-semibold text-purple-900 mb-2">VAT (%)</label>
                            <input type="number" step="1" name="vat" id="vat" 
                                   value="<?php echo htmlspecialchars($product['vat']); ?>" 
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>

                        <div>
                            <label for="stock_quantity" class="block text-sm font-semibold text-purple-900 mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" 
                                   value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" 
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-purple-900 mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiA3NzJlZmYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1jaGV2cm9uLWRvd24iPjxwYXRoIGQ9Im03IDE1IDUgNSA1LTUiLz48L3N2Zz4=')] bg-no-repeat bg-[right_1rem_center]">
                                <option value="1" <?php echo $product['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo $product['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-semibold text-purple-900 mb-2">Category</label>
                            <select name="category" id="category" 
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-purple-100 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiA3NzJlZmYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1jaGV2cm9uLWRvd24iPjxwYXRoIGQ9Im03IDE1IDUgNSA1LTUiLz48L3N2Zz4=')] bg-no-repeat bg-[right_1rem_center]">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $product['category'] == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-6 border-t border-purple-100">
                    <button type="submit" name="update_product" 
                            class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold px-6 py-3 rounded-lg transition-all transform hover:scale-[1.02] shadow-lg shadow-purple-200">
                        Save Changes
                    </button>
                    <a href="products.php" 
                       class="px-6 py-3 font-semibold text-purple-600 hover:text-purple-800 transition-colors underline underline-offset-4 decoration-2 decoration-purple-200">
                        Cancel and Return
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>