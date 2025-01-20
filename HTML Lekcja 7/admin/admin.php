<?php
session_start();
include('cfg.php');

// Funkcja do połączenia z bazą danych
function polaczZBaza() {
    $connection = mysqli_connect('localhost', 'root', '', 'cms'); // Zmień dane logowania zgodnie z konfiguracją
    if (!$connection) {
        die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
    }
    return $connection;
}

// Funkcja do formularza logowania
function FormularzLogowania() {
    return '
    <div class="logowanie">
        <h1>Panel CMS:</h1>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <table>
                <tr><td>Login:</td><td><input type="text" name="login_email" required></td></tr>
                <tr><td>Hasło:</td><td><input type="password" name="login_pass" required></td></tr>
                <tr><td></td><td><input type="submit" name="login_submit" value="Zaloguj"></td></tr>
            </table>
        </form>
    </div>';
}

// Funkcja do edycji/dodawania podstrony
function FormularzPodstrony($row = null) {
    $title = $row['tytul'] ?? '';
    $content = $row['tresc'] ?? '';
    $checked = isset($row['aktywna']) && $row['aktywna'] ? 'checked' : '';
    $action = $row ? 'save_changes' : 'add_new';
    $button = $row ? 'Zapisz zmiany' : 'Dodaj podstronę';
    return '
    <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
        <label>Tytuł podstrony:</label><br>
        <input type="text" name="title" value="' . htmlspecialchars($title) . '" required><br>
        <label>Treść podstrony:</label><br>
        <textarea name="content" required>' . htmlspecialchars($content) . '</textarea><br>
        <label><input type="checkbox" name="active" ' . $checked . '> Strona aktywna</label><br>
        ' . ($row ? '<input type="hidden" name="edit_id" value="' . $row['id'] . '">' : '') . '
        <button type="submit" name="' . $action . '">' . $button . '</button>
    </form>';
}

// Funkcja wyświetlania listy podstron z akcjami
function ListaPodstron() {
    $connection = polaczZBaza();
    $query = "SELECT id, tytul FROM page_list ORDER BY id ASC";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<table border="1"><tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr><td>' . $row['id'] . '</td><td>' . htmlspecialchars($row['tytul']) . '</td><td>
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

// Obsługa logowania
if (isset($_POST['login_submit'])) {
    if ($_POST['login_email'] === $login && $_POST['login_pass'] === $pass) {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        echo '<p>Nieprawidłowy login lub hasło.</p>' . FormularzLogowania();
    }
}

// Obsługa dodawania/edycji podstrony
if (isset($_POST['add_new']) || isset($_POST['save_changes'])) {
    $connection = polaczZBaza();
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);
    $active = isset($_POST['active']) ? 1 : 0;

    if (isset($_POST['save_changes'])) {
        $id = intval($_POST['edit_id']);
        $query = "UPDATE page_list SET tytul = '$title', tresc = '$content', aktywna = $active WHERE id = $id LIMIT 1";
    } else {
        $query = "INSERT INTO page_list (tytul, tresc, aktywna) VALUES ('$title', '$content', $active)";
    }

    if (mysqli_query($connection, $query)) {
        echo '<p>Operacja zakończona sukcesem.</p>';
    } else {
        echo '<p>Błąd: ' . mysqli_error($connection) . '</p>';
    }

    mysqli_close($connection);
}

// Obsługa usuwania podstrony
if (isset($_POST['delete_submit'])) {
    $id = intval($_POST['delete_id']);
    $connection = polaczZBaza();
    $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";

    if (mysqli_query($connection, $query)) {
        echo '<p>Podstrona usunięta.</p>';
    } else {
        echo '<p>Błąd: ' . mysqli_error($connection) . '</p>';
    }

    mysqli_close($connection);
}

// Wyświetlenie odpowiedniego interfejsu
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo FormularzLogowania();
} elseif (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $connection = polaczZBaza();
    $query = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($connection, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        echo FormularzPodstrony($row);
    } else {
        echo '<p>Nie znaleziono podstrony.</p>';
    }
    mysqli_close($connection);
} else {
    echo '<h1>Lista podstron:</h1>';
    ListaPodstron();
    echo '<h1>Dodaj nową podstronę:</h1>';
    echo FormularzPodstrony();
}
?>
