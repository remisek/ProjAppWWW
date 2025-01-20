<?php
// Funkcja wyświetlająca formularz kontaktowy
function PokazKontakt() {
    return '
    <h1>Formularz Kontaktowy</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
        <label for="name">Imię i nazwisko:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="email">Adres e-mail:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label for="message">Treść wiadomości:</label><br>
        <textarea name="message" id="message" rows="5" required></textarea><br><br>

        <button type="submit" name="send_contact">Wyślij wiadomość</button>
    </form>';
}

// Funkcja wysyłająca mail z formularza kontaktowego
function WyslijMailKontakt($odbiorca) {
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '<p>Nie wypełniłeś wszystkich wymaganych pól.</p>';
        echo PokazKontakt();
    } else {
        $mail = [];
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca;

        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\r\n";
        $header .= "Mime-Version: 1.0\r\n";
        $header .= "Content-Type: text/plain; charset=utf-8\r\n";
        $header .= "X-sender: <" . $mail['sender'] . ">\r\n";
        $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $header .= "X-Priority: 3\r\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\r\n";

        if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
            echo '<p>Wiadomość została wysłana. Dziękujemy za kontakt!</p>';
        } else {
            echo '<p>Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
        }
    }
}

// Function to remind the admin of their password
function PrzypomnijHaslo() {
    if (isset($_POST['reset_password'])) {
        $email = htmlspecialchars($_POST['email']);
        
        $admin_email = "admin@example.com";
        $admin_password = "admin123";

        if ($email === $admin_email) {
            $to = $email;
            $subject = "Przypomnienie hasła do panelu admina";
            $headers = "From: no-reply@moja-strona.pl\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            $body = "Otrzymujesz to przypomnienie, ponieważ poprosiłeś o przypomnienie hasła do panelu admina.\n\n";
            $body .= "Twoje obecne hasło to: $admin_password\n\n";
            $body .= "Jeśli nie poprosiłeś o przypomnienie, zignoruj tę wiadomość.";

            if (mail($to, $subject, $body, $headers)) {
                echo '<p>Hasło zostało wysłane na Twój adres e-mail.</p>';
            } else {
                echo '<p>Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
            }
        } else {
            echo '<p>Podany adres e-mail nie pasuje do konta admina.</p>';
        }
    }

    return '
    <h1>Przypomnienie hasła</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
        <label for="email">Adres e-mail:</label><br>
        <input type="email" name="email" id="email" required><br><br>
        <button type="submit" name="reset_password">Przypomnij hasło</button>
    </form>';
}


// Wywołanie odpowiednich funkcji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_contact'])) {
        WyslijMailKontakt();
    } elseif (isset($_POST['reset_password'])) {
        PrzypomnijHaslo();
    }
} else {
    echo PokazKontakt();
    echo PrzypomnijHaslo();
}
?>
