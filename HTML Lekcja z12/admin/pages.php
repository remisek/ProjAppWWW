<?php
session_start();
require_once '../utils.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

// Database connection
global $db;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_page'])) {
            $title = sanitizeInput($_POST['title']);
            $content = sanitizeInput($_POST['content']);
            $alias = sanitizeInput($_POST['alias']);
            $status = isset($_POST['status']) ? 1 : 0;
            addPage($title, $content, $alias, $status);
            header("Location: pages.php");
            exit;
        } elseif (isset($_POST['update_page'])) {
            $id = (int)$_POST['id'];
            $title = sanitizeInput($_POST['title']);
            $content = sanitizeInput($_POST['content']);
            $alias = sanitizeInput($_POST['alias']);
            $status = isset($_POST['status']) ? 1 : 0;
            updatePage($id, $title, $content, $alias, $status);
            header("Location: pages.php");
            exit;
        } elseif (isset($_POST['delete_page'])) {
            $id = (int)$_POST['id'];
            deletePage($id);
            header("Location: pages.php");
            exit;
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch all pages
$pages = getAllPages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pages</title>
    <link rel="stylesheet" href="../css/pages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="cms-wrapper">
    <div class="cms-main">
        <div class="page-management">
            <header class="cms-header">
                <h1 class="cms-title">Manage Pages</h1>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </header>

            <!-- Add Page Form -->
            <section class="management-section">
                <h2 class="section-header">Create New Page</h2>
                <form class="page-form" action="pages.php" method="post">
                    <div class="form-group">
                        <label class="form-label" for="title">Page Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="alias">URL Slug</label>
                        <input type="text" class="form-control" name="alias" id="alias" required maxlength="40">
                        <span class="form-hint">Maximum 40 characters</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Content</label>
                        <textarea class="form-control" name="content" id="content" rows="10" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="status" id="status" checked>
                            <span class="checkmark"></span>
                            Active Status
                        </label>
                    </div>

                    <button type="submit" name="add_page" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create Page
                    </button>
                </form>
            </section>

            <!-- Page List -->
            <section class="management-section">
                <h2 class="section-header">Existing Pages</h2>
                <table class="page-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>URL Slug</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                        <tr>
                            <td><?= $page['id'] ?></td>
                            <td><?= $page['page_title'] ?></td>
                            <td><code>/<?= $page['alias'] ?></code></td>
                            <td>
                                <span class="status-badge <?= $page['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $page['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_page.php?id=<?= $page['id'] ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="pages.php" method="post" class="inline">
                                        <input type="hidden" name="id" value="<?= $page['id'] ?>">
                                        <button type="submit" class="btn btn-danger" name="delete_page">
                                            <i class="fas fa-trash"></i>
                                            Delete
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
    </div>
</body>
</html>