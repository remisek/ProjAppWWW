<?php
session_start();
include('cfg.php'); // Plik z konfiguracją bazy danych

// Połączenie z bazą danych
function polaczZBaza() {
    $connection = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if (!$connection) {
        die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
    }
    return $connection;
}

// Funkcje zarządzania produktami

// Dodaj produkt
function DodajProdukt($name, $description, $expiration_date, $price, $vat_rate, $stock, $availability_status, $category_id, $size, $image_link) {
    $connection = polaczZBaza();
    $name = mysqli_real_escape_string($connection, $name);
    $description = mysqli_real_escape_string($connection, $description);
    $expiration_date = $expiration_date ? "'$expiration_date'" : "NULL";
    $price = floatval($price);
    $vat_rate = floatval($vat_rate);
    $stock = intval($stock);
    $availability_status = mysqli_real_escape_string($connection, $availability_status);
    $category_id = intval($category_id);
    $size = mysqli_real_escape_string($connection, $size);
    $image_link = mysqli_real_escape_string($connection, $image_link);

    $query = "INSERT INTO products (name, description, expiration_date, price, vat_rate, stock, availability_status, category_id, size, image_link)
              VALUES ('$name', '$description', $expiration_date, $price, $vat_rate, $stock, '$availability_status', $category_id, '$size', '$image_link')";

    mysqli_query($connection, $query);
    mysqli_close($connection);
}

// Usuń produkt
function UsunProdukt($id) {
    $connection = polaczZBaza();
    $id = intval($id);
    $query = "DELETE FROM products WHERE id = $id LIMIT 1";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

// Edytuj produkt
function EdytujProdukt($id, $name, $description, $expiration_date, $price, $vat_rate, $stock, $availability_status, $category_id, $size, $image_link) {
    $connection = polaczZBaza();
    $id = intval($id);
    $name = mysqli_real_escape_string($connection, $name);
    $description = mysqli_real_escape_string($connection, $description);
    $expiration_date = $expiration_date ? "'$expiration_date'" : "NULL";
    $price = floatval($price);
    $vat_rate = floatval($vat_rate);
    $stock = intval($stock);
    $availability_status = mysqli_real_escape_string($connection, $availability_status);
    $category_id = intval($category_id);
    $size = mysqli_real_escape_string($connection, $size);
    $image_link = mysqli_real_escape_string($connection, $image_link);

    $query = "UPDATE products
              SET name = '$name', description = '$description', expiration_date = $expiration_date, price = $price, vat_rate = $vat_rate,
                  stock = $stock, availability_status = '$availability_status', category_id = $category_id, size = '$size', image_link = '$image_link'
              WHERE id = $id LIMIT 1";

    mysqli_query($connection, $query);
    mysqli_close($connection);
}

// Wyświetl produkty z warunkami dostępności
function PokazProdukty() {
    $connection = polaczZBaza();
    $query = "SELECT p.*, c.name AS category_name
              FROM products p
              JOIN categories c ON p.category_id = c.id";
    $result = mysqli_query($connection, $query);

    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena netto</th>
                <th>VAT</th>
                <th>Magazyn</th>
                <th>Status</th>
                <th>Kategoria</th>
                <th>Gabaryt</th>
                <th>Data wygaśnięcia</th>
                <th>Zdjęcie</th>
                <th>Akcje</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $dostepnosc = ($row['availability_status'] === 'available' && $row['stock'] > 0 && (!$row['expiration_date'] || $row['expiration_date'] > date('Y-m-d')))
            ? 'Dostępny' : 'Niedostępny';

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['description']}</td>
                <td>{$row['price']}</td>
                <td>{$row['vat_rate']}%</td>
                <td>{$row['stock']}</td>
                <td>{$dostepnosc}</td>
                <td>{$row['category_name']}</td>
                <td>{$row['size']}</td>
                <td>{$row['expiration_date']}</td>
                <td><img src='{$row['image_link']}' alt='{$row['name']}' style='width:100px;'></td>
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
        DodajProdukt($_POST['name'], $_POST['description'], $_POST['expiration_date'], $_POST['price'], $_POST['vat_rate'], $_POST['stock'], $_POST['availability_status'], $_POST['category_id'], $_POST['size'], $_POST['image_link']);
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
        <label>Nazwa:</label><input type="text" name="name" required><br>
        <label>Opis:</label><textarea name="description" required></textarea><br>
        <label>Cena netto:</label><input type="number" step="0.01" name="price" required><br>
        <label>VAT:</label><input type="number" step="0.01" name="vat_rate" required><br>
        <label>Magazyn:</label><input type="number" name="stock" required><br>
        <label>Status:</label><select name="availability_status">
            <option value="available">Dostępny</option>
            <option value="unavailable">Niedostępny</option>
        </select><br>
        <label>Kategoria:</label><input type="number" name="category_id" required><br>
        <label>Gabaryt:</label><input type="text" name="size"><br>
        <label>Data wygaśnięcia:</label><input type="date" name="expiration_date"><br>
        <label>Zdjęcie (URL):</label><input type="text" name="image_link"><br>
        <button type="submit" name="add_product">Dodaj</button>
    </form>

    <!-- Wyświetlanie produktów -->
    <h2>Lista produktów</h2>
    <?php PokazProdukty(); ?>
</body>
</html>
