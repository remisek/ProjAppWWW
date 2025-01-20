<?php
session_start();
include('cfg.php');

// Funkcja do połączenia z bazą danych
function polaczZBaza() {
    // Tworzy połączenie z bazą danych, używane w innych funkcjach do wykonywania operacji SQL.
    $connection = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if (!$connection) {
        die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
    }
    return $connection;
}

// Funkcja do wyświetlania listy podstron
function ListaPodstron() {
    // Pobiera wszystkie podstrony z bazy danych i wyświetla je w tabeli HTML.
    $connection = polaczZBaza();
    $query = "SELECT id, page_title FROM page_list ORDER BY id ASC";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<table border="1"><tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr><td>' . $row['id'] . '</td><td>' . htmlspecialchars($row['page_title']) . '</td><td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                    <button type="submit" name="delete_submit">Usuń</button>
                </form>
                <form method="get" action="' . $_SERVER['PHP_SELF'] . '" style="display:inline;">
                    <input type="hidden" name="edit_id" value="' . $row['id'] . '">
                    <button type="submit">Edytuj</button>
                </form>
            </td></tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Brak podstron do wyświetlenia.</p>';
    }

    mysqli_close($connection);
}

// Obsługa dodawania/edycji podstrony
if (isset($_POST['add_new']) || isset($_POST['save_changes'])) {
    // Pobiera dane z formularza i zapisuje nową podstronę lub aktualizuje istniejącą.
    $connection = polaczZBaza();
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);
    $active = isset($_POST['active']) ? 1 : 0;

    if (isset($_POST['save_changes'])) {
        // Aktualizuje istniejącą podstronę w bazie danych.
        $id = intval($_POST['edit_id']);
        $query = "UPDATE page_list SET tytul = '$title', tresc = '$content', aktywna = $active WHERE id = $id LIMIT 1";
    } else {
        // Dodaje nową podstronę do bazy danych.
        $query = "INSERT INTO page_list (tytul, tresc, aktywna) VALUES ('$title', '$content', $active)";
    }

    if (mysqli_query($connection, $query)) {
        echo '<p>Operacja zakończona sukcesem.</p>';
    } else {
        echo '<p>Błąd: ' . mysqli_error($connection) . '</p>';
    }

    mysqli_close($connection);
}

?>
