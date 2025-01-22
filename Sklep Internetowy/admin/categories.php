<?php
session_start();
require_once '../utils.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

/**
 * Adds a new category to the database.
 * @param string $name The name of the category.
 * @param int $parent The id of the parent category (0 for main category).
 */
function addCategory($name, $parent = 0) {
    global $db;
    $name = sanitizeInput($name);
    $parent = (int)$parent;

    $stmt = $db->prepare("INSERT INTO categories (name, parent) VALUES (:name, :parent)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':parent', $parent);
    $stmt->execute();
}

/**
 * Removes a category from the database by its name.
 * @param string $name The name of the category.
 */
function removeCategoryByName($name) {
    global $db;
    $name = sanitizeInput($name);

    // Get category ID
    $stmt = $db->prepare("SELECT id FROM categories WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $categoryId = $category['id'];

        // Delete all products in this category first
        $db->beginTransaction();
        try {
            // Delete products
            $stmtProducts = $db->prepare("DELETE FROM products WHERE category = :categoryId");
            $stmtProducts->bindParam(':categoryId', $categoryId);
            $stmtProducts->execute();

            // Delete the category
            $stmtCategory = $db->prepare("DELETE FROM categories WHERE id = :categoryId");
            $stmtCategory->bindParam(':categoryId', $categoryId);
            $stmtCategory->execute();

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}

/**
 * Updates category information in the database.
 * @param string $oldName The current name of the category.
 * @param string $newName The new name of the category.
 * @param string $parentName The name of the new parent category.
 */
function updateCategoryByName($oldName, $newName, $parentName) {
    global $db;
    $oldName = sanitizeInput($oldName);
    $newName = sanitizeInput($newName);
    $parentName = sanitizeInput($parentName);

    // Get the parent category ID
    $parentId = 0;
    if (!empty($parentName)) {
        $stmt = $db->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->bindParam(':name', $parentName);
        $stmt->execute();
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);
        $parentId = $parent ? $parent['id'] : 0;
    }

    $stmt = $db->prepare("UPDATE categories SET name = :newName, parent = :parent WHERE name = :oldName");
    $stmt->bindParam(':newName', $newName);
    $stmt->bindParam(':parent', $parentId);
    $stmt->bindParam(':oldName', $oldName);
    $stmt->execute();
}

/**
 * Recursive function to display nested categories.
 * @param array $categories Array of all categories.
 * @param int $parent The parent category ID.
 * @param int $level Indentation level.
 */
function displayCategories($categories, $parent = 0, $level = 0) {
    foreach ($categories as $category) {
        if ($category['parent'] == $parent) {
            echo str_repeat("&nbsp;&nbsp;", $level) . "- " . $category['name'] . "<br>";
            displayCategories($categories, $category['id'], $level + 1);
        }
    }
}

// Example usage (can be moved to a separate controller)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $parentName = sanitizeInput($_POST['parent']);
        $parentId = 0;
        if (!empty($parentName)) {
            $stmt = $db->prepare("SELECT id FROM categories WHERE name = :name");
            $stmt->bindParam(':name', $parentName);
            $stmt->execute();
            $parent = $stmt->fetch(PDO::FETCH_ASSOC);
            $parentId = $parent ? $parent['id'] : 0;
        }
        addCategory($_POST['name'], $parentId);
        header("Location: categories.php");
        exit;
    } elseif (isset($_POST['remove_category'])) {
        removeCategoryByName($_POST['category_name']);
        header("Location: categories.php");
        exit;
    } elseif (isset($_POST['update_category'])) {
        updateCategoryByName($_POST['old_name'], $_POST['new_name'], $_POST['parent']);
        header("Location: categories.php");
        exit;
    }
}

$categories = getAllCategories();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="cms-wrapper">
    <div class="cms-main">
        <div class="category-management">
            <header class="cms-header">
                <h1 class="cms-title">Manage Categories</h1>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </header>

            <!-- Add Category Form -->
            <section class="management-section">
                <h2 class="section-header">Add Category</h2>
                <form class="category-form" action="categories.php" method="post">
                    <div class="form-group">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <!-- Other form elements -->
                    <button type="submit" name="add_category" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Category
                    </button>
                </form>
            </section>

            <!-- Remove Category Form -->
            <section class="management-section">
                <h2 class="section-header">Remove Category</h2>
                <form class="category-form" action="categories.php" method="post">
                    <!-- Form elements -->
                    <button type="submit" name="remove_category" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Remove Category
                    </button>
                </form>
            </section>

            <!-- Update Category Form -->
            <section class="management-section">
                <h2 class="section-header">Update Category</h2>
                <form class="category-form" action="categories.php" method="post">
                    <!-- Form elements -->
                    <button type="submit" name="update_category" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        Update Category
                    </button>
                </form>
            </section>

            <!-- Category Tree -->
            <section class="management-section">
                <h2 class="section-header">Category Hierarchy</h2>
                <div class="category-tree">
                    <?php displayCategories($categories); ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>