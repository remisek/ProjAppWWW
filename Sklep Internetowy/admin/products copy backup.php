<?php
session_start();
require_once '../utils.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        addProduct(
            sanitizeInput($_POST['title']),
            sanitizeInput($_POST['description']),
            (int)($_POST['net_price'] * 100),  // Convert to cents
            (int)$_POST['vat'],
            (int)$_POST['stock_quantity'],
            (int)$_POST['status'],
            (int)$_POST['category'],
            sanitizeInput($_POST['image']),
            !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null
        );
        header("Location: products.php");
        exit;
    } elseif (isset($_POST['update_product'])) {
        updateProduct(
            (int)$_POST['product_id'],
            sanitizeInput($_POST['title']),
            sanitizeInput($_POST['description']),
            (int)($_POST['net_price'] * 100),  // Convert to cents
            (int)$_POST['vat'],
            (int)$_POST['stock_quantity'],
            (int)$_POST['status'],
            (int)$_POST['category'],
            sanitizeInput($_POST['image']),
            !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null
        );
        header("Location: products.php");
        exit;
    } elseif (isset($_POST['remove_product'])) {
        removeProduct((int)$_POST['product_id']);
        header("Location: products.php");
        exit;
    }
}

$products = getAllProducts();
$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../css/products.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="cms-wrapper">
    <main class="cms-main">
        <header class="cms-header">
            <h1 class="cms-title">Manage Products</h1>
            <div class="header-actions">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </header>

        <div class="product-management">
            <!-- Add Product Form -->
            <section class="management-section">
                <h2>Add New Product</h2>
                <form class="product-form" action="products.php" method="post">
                    <div class="form-group">
                        <label for="title">Product Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="net_price">Net Price (PLN)</label>
                        <input type="number" step="0.01" class="form-control" name="net_price" id="net_price" required>
                    </div>

                    <div class="form-group">
                        <label for="vat">VAT (%)</label>
                        <input type="number" class="form-control" name="vat" id="vat" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity</label>
                        <input type="number" class="form-control" name="stock_quantity" id="stock_quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="expiration_date">Expiration Date</label>
                        <input type="date" class="form-control" name="expiration_date" id="expiration_date">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" name="category" id="category" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Image URL</label>
                        <input type="text" class="form-control" name="image" id="image" required>
                    </div>
                    
                    <button type="submit" name="add_product" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Product
                    </button>
                </form>
            </section>

            <!-- Product List -->
            <section class="management-section">
                <h2>Product Inventory</h2>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Expiration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= $product['title'] ?></td>
                            <td><?= $product['category'] ?></td>
                            <td><?= number_format($product['net_price'] / 100, 2) ?> PLN</td>
                            <td><?= $product['stock_quantity'] ?></td>
                            <td><?= $product['expiration_date'] ?: 'N/A' ?></td>
                            <td>
                                <span class="status-badge <?= $product['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $product['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" 
                                       class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="products.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" 
                                                class="btn btn-remove"
                                                name="remove_product">
                                            <i class="fas fa-trash"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
</body>
</html>