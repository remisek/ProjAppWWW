<?php
session_start();
require_once '../utils.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="cms-wrapper">
    <div class="cms-wrapper">
        <!-- Panel boczny -->
        <aside class="cms-sidebar">
            <div class="cms-brand">CMS Panel</div>
            <nav>
                <ul class="cms-nav">
                    <li class="cms-nav-item">
                        <a href="index.php" class="cms-nav-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="cms-nav-item">
                        <a href="products.php" class="cms-nav-link">
                            <i class="fas fa-box"></i>
                            Produkty
                        </a>
                    </li>
                    <li class="cms-nav-item">
                        <a href="categories.php" class="cms-nav-link">
                            <i class="fas fa-tags"></i>
                            Kategorie
                        </a>
                    </li>
                    <li class="cms-nav-item">
                        <a href="pages.php" class="cms-nav-link">
                            <i class="fas fa-file"></i>
                            Strony
                        </a>
                    </li>
                    <li class="cms-nav-item">
                        <a href="?logout=1" class="cms-nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Wyloguj
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Główna zawartość -->
        <main class="cms-main">
            <!-- Nagłówek -->
            <header class="cms-header">
                <h1 class="cms-title">Panel Administracyjny</h1>
                <div class="header-actions">
                    <a href="../index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Powrót do strony
                    </a>
                </div>
            </header>

            <!-- Sekcje zarządzania -->
            <div class="cms-stats">
                <div class="management-card">
                    <div class="management-icon">
                        <i class="fas fa-tags fa-3x"></i>
                    </div>
                    <div class="management-content">
                        <h3>Kategorie</h3>
                        <p>Zarządzaj kategoriami produktów</p>
                        <a href="categories.php" class="btn btn-primary">Przejdź do kategorii</a>
                    </div>
                </div>

                <div class="management-card">
                    <div class="management-icon">
                        <i class="fas fa-box-open fa-3x"></i>
                    </div>
                    <div class="management-content">
                        <h3>Produkty</h3>
                        <p>Zarządzaj asortymentem sklepu</p>
                        <a href="products.php" class="btn btn-primary">Przejdź do produktów</a>
                    </div>
                </div>

                <div class="management-card">
                    <div class="management-icon">
                        <i class="fas fa-file-alt fa-3x"></i>
                    </div>
                    <div class="management-content">
                        <h3>Strony</h3>
                        <p>Edytuj zawartość strony internetowej</p>
                        <a href="pages.php" class="btn btn-primary">Przejdź do stron</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa aktywnych linków
        const currentPage = window.location.href;
        document.querySelectorAll('.cms-nav-link').forEach(link => {
            if (link.href === currentPage) {
                link.classList.add('active');
            }
        });

        // Animacje hover dla kart
        document.querySelectorAll('.management-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    });
    </script>
</body>
</html>

