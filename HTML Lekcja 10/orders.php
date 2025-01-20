<?php
session_start();
include('cfg.php');

// Funkcja do połączenia z bazą danych
function polaczZBaza() {
    $connection = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if (!$connection) {
        die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
    }
    return $connection;
}

// Funkcja wyświetlająca zamówienia
function PokazZamowienia() {
    $connection = polaczZBaza();
    $query = "SELECT * FROM orders ORDER BY created_at DESC";
    $result = mysqli_query($connection, $query);

    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Klient</th>
                <th>E-mail</th>
                <th>Kwota</th>
                <th>Status</th>
                <th>Data</th>
                <th>Akcje</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['customer_email']}</td>
                <td>{$row['total']}</td>
                <td>{$row['status']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='mark_completed'>Oznacz jako zakończone</button>
                        <button type='submit' name='cancel_order'>Anuluj</button>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";

    mysqli_close($connection);
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_completed'])) {
        $id = intval($_POST['id']);
        $connection = polaczZBaza();
        $query = "UPDATE orders SET status = 'completed' WHERE id = $id LIMIT 1";
        mysqli_query($connection, $query);
        mysqli_close($connection);
    } elseif (isset($_POST['cancel_order'])) {
        $id = intval($_POST['id']);
        $connection = polaczZBaza();
        $query = "UPDATE orders SET status = 'cancelled' WHERE id = $id LIMIT 1";
        mysqli_query($connection, $query);
        mysqli_close($connection);
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie zamówieniami</title>
</head>
<body>
    <h1>Zarządzanie zamówieniami</h1>

    <!-- Wyświetlanie zamówień -->
    <h2>Lista zamówień</h2>
    <?php PokazZamowienia(); ?>
</body>
</html>
