<?php
session_start();
include('cfg.php'); // Plik z konfiguracją bazy danych

// Funkcja do połączenia z bazą danych
function polaczZBaza() {
    $connection = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if (!$connection) {
        die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
    }
    return $connection;
}

// Funkcje zarządzania produktami
function DodajProdukt($name, $price, $category_id, $description) {
    $connection = polaczZBaza();
    $name = mysqli_real_escape_string($connection, $name);
    $price = floatval($price);
    $category_id = intval($category_id);
    $description = mysqli_real_escape_string($connection, $description);

    $query = "INSERT INTO products (name, price, category_id, description) VALUES ('$name', $price, $category_id, '$description')";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function UsunProdukt($id) {
    $connection = polaczZBaza();
    $id = intval($id);

    $query = "DELETE FROM products WHERE id = $id LIMIT 1";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function EdytujProdukt($id, $name, $price, $category_id, $description) {
    $connection = polaczZBaza();
    $id = intval($id);
    $name = mysqli_real_escape_string($connection, $name);
    $price = floatval($price);
    $category_id = intval($category_id);
    $description = mysqli_real_escape_string($connection, $description);

    $query = "UPDATE products SET name = '$name', price = $price, category_id = $category_id, description = '$description' WHERE id = $id LIMIT 1";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function PokazProdukty() {
    $connection = polaczZBaza();
    $query = "SELECT p.id, p.name, p.price, c.name AS category_name, p.description 
              FROM products p
              JOIN categories c ON p.category_id = c.id";
    $result = mysqli_query($connection, $query);

    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Cena</th>
                <th>Kategoria</th>
                <th>Opis</th>
                <th>Akcje</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['category_name']}</td>
                <td>{$row['description']}</td>
                <td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='delete_product'>Usuń</button>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";

    mysqli_close($connection);
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        DodajProdukt($_POST['name'], $_POST['price'], $_POST['category_id'], $_POST['description']);
    } elseif (isset($_POST['delete_product'])) {
        UsunProdukt($_POST['id']);
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie produktami</title>
</head>
<body>
    <h1>Zarządzanie produktami</h1>

    <!-- Formularz dodawania produktu -->
    <h2>Dodaj produkt</h2>
    <form method="post">
        <label for="name">Nazwa:</label>
        <input type="text" name="name" id="name" required>
        <label for="price">Cena:</label>
        <input type="number" step="0.01" name="price" id="price" required>
        <label for="category_id">Kategoria:</label>
        <input type="number" name="category_id" id="category_id" required>
        <label for="description">Opis:</label>
        <textarea name="description" id="description"></textarea>
        <button type="submit" name="add_product">Dodaj</button>
    </form>

    <!-- Wyświetlanie produktów -->
    <h2>Lista produktów</h2>
    <?php PokazProdukty(); ?>
</body>
</html>
