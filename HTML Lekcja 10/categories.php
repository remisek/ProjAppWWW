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

// Funkcje zarządzania kategoriami
function DodajKategorie($name, $parent_id = 0) {
    $connection = polaczZBaza();
    $name = mysqli_real_escape_string($connection, $name);
    $parent_id = intval($parent_id);

    $query = "INSERT INTO categories (name, parent_id) VALUES ('$name', $parent_id)";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function UsunKategorie($id) {
    $connection = polaczZBaza();
    $id = intval($id);

    // Usuń kategorię i jej podkategorie
    $query = "DELETE FROM categories WHERE id = $id OR parent_id = $id";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function EdytujKategorie($id, $new_name) {
    $connection = polaczZBaza();
    $id = intval($id);
    $new_name = mysqli_real_escape_string($connection, $new_name);

    $query = "UPDATE categories SET name = '$new_name' WHERE id = $id";
    mysqli_query($connection, $query);
    mysqli_close($connection);
}

function PokazKategorie() {
    $connection = polaczZBaza();
    $query = "SELECT * FROM categories WHERE parent_id = 0";
    $result = mysqli_query($connection, $query);

    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . htmlspecialchars($row['name']);

        // Pobierz podkategorie
        $sub_query = "SELECT * FROM categories WHERE parent_id = " . $row['id'];
        $sub_result = mysqli_query($connection, $sub_query);

        if (mysqli_num_rows($sub_result) > 0) {
            echo "<ul>";
            while ($sub_row = mysqli_fetch_assoc($sub_result)) {
                echo "<li>" . htmlspecialchars($sub_row['name']) . "</li>";
            }
            echo "</ul>";
        }
        echo "</li>";
    }
    echo "</ul>";

    mysqli_close($connection);
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        DodajKategorie($_POST['name'], $_POST['parent_id']);
    } elseif (isset($_POST['delete_category'])) {
        UsunKategorie($_POST['id']);
    } elseif (isset($_POST['edit_category'])) {
        EdytujKategorie($_POST['id'], $_POST['new_name']);
    }
}

// Formularze do zarządzania kategoriami
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie kategoriami</title>
</head>
<body>
    <h1>Zarządzanie kategoriami</h1>
    
    <!-- Formularz dodawania kategorii -->
    <h2>Dodaj kategorię</h2>
    <form method="post">
        <label for="name">Nazwa kategorii:</label>
        <input type="text" name="name" id="name" required>
        <label for="parent_id">Kategoria nadrzędna (0 dla głównej):</label>
        <input type="number" name="parent_id" id="parent_id" value="0">
        <button type="submit" name="add_category">Dodaj</button>
    </form>

    <!-- Formularz edycji kategorii -->
    <h2>Edytuj kategorię</h2>
    <form method="post">
        <label for="id">ID kategorii:</label>
        <input type="number" name="id" id="id" required>
        <label for="new_name">Nowa nazwa:</label>
        <input type="text" name="new_name" id="new_name" required>
        <button type="submit" name="edit_category">Zapisz zmiany</button>
    </form>

    <!-- Formularz usuwania kategorii -->
    <h2>Usuń kategorię</h2>
    <form method="post">
        <label for="id">ID kategorii do usunięcia:</label>
        <input type="number" name="id" id="id" required>
        <button type="submit" name="delete_category">Usuń</button>
    </form>

    <!-- Wyświetlanie kategorii -->
    <h2>Lista kategorii</h2>
    <?php PokazKategorie(); ?>
</body>
</html>
