<?php
// Enable error reporting for debugging (remove for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once 'utils.php'; // Include utils.php for database connection and functions
include_once 'mail.php';

// Function to fetch a page based on alias or ID
function fetchPage($alias)
{
    global $db; // Use the PDO connection from utils.php
    $stmt = $db->prepare("SELECT * FROM page_list WHERE alias = :alias OR (alias IS NULL AND id = :id) LIMIT 1");
    $stmt->bindParam(':alias', $alias);
    $stmt->bindParam(':id', $alias);
    $stmt->execute();
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    return $page ? $page : null;
}

// Function to fetch all pages
function fetchAllPages()
{
    global $db; // Use the PDO connection from utils.php
    $stmt = $db->query("SELECT id, page_title, alias FROM page_list WHERE status = 1 ORDER BY page_title ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch page content
$page_alias = isset($_GET['alias']) ? sanitizeInput($_GET['alias']) : 'glowna';
$page = fetchPage($page_alias);

// Fetch all pages for the sidebar
$pages = fetchAllPages();
// Strona główna
// Panel Admina w podstronie
// Kompletnie zmienić CSS na darkmode
// Zawartość stron do sql
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Computer Vision Notes</title>
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div class="navigation-box">
        <div class="nav-container">
            <a href="index.php" class="nav-home">
                <i class="fas fa-home"></i>
            </a>
            
            <nav class="main-nav">
                <div class="nav-item">
                    <a href="#" class="nav-toggle">
                        Laboratoria 
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="index.php?idp=what-is-computer-vision">
                            <i class="fas fa-video"></i>
                            Wprowadzenie
                        </a>
                        <a href="index.php?idp=basics-of-computer-vision-convolutional-neural-networks">
                            <i class="fas fa-network-wired"></i>
                            Podstawy CNN
                        </a>
                        <a href="index.php?idp=history-of-computer-vision-architectures-reviwing-the-architectures">
                            <i class="fas fa-history"></i>
                            Historia architektur
                        </a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-toggle">
                        Ćwiczenia 
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="index.php?idp=javascript-exercise">
                            <i class="fab fa-js"></i>
                            JavaScript
                        </a>
                        <a href="index.php?idp=timedatejs">
                            <i class="fas fa-clock"></i>
                            Praca z czasem
                        </a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="contact.php" class="nav-toggle">
                        <i class="fas fa-envelope"></i>
                        Kontakt
                    </a>
                </div>
				<div class="nav-item">
                    <a href="admin/index.php" class="nav-toggle">
                        <i class="fas fa-envelope"></i>
                        Admin
                    </a>
                </div>
				<div class="nav-item">
                    <a href="shop/index.php" class="nav-toggle">
                        <i class="fas fa-envelope"></i>
                        Sklep
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <header class="content-header">
        <h1>Computer Vision Notes</h1>
    </header>

    <main class="content-box">
        <h2>Spis treści</h2>
        <div class="toc-container">
            <ul class="toc-list">
                <li>1. Wprowadzenie do Computer Vision</li>
                <li>2. Podstawy sieci konwolucyjnych (CNN)</li>
                <li>3. Przegląd historycznych architektur</li>
                <li>4. Generatywne sieci przeciwstawne (GAN)</li>
                <li>5. Praktyczna implementacja</li>
                <li>6. Case studies i analizy</li>
            </ul>
        </div>
    </main>

    <script>
    $(document).ready(function(){
        // Animowane rozwijanie sekcji
        $(".nav-toggle").hover(function(){
            $(this).next(".nav-dropdown").stop(true, true).slideDown(200);
        }, function(){
            $(this).next(".nav-dropdown").stop(true, true).slideUp(200);
        });
    });
    </script>
</body>
</html>

