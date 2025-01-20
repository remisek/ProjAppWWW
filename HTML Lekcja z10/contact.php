<?php
// Funkcja wyświetlająca formularz kontaktowy
function PokazKontakt() {
    // Tworzy i zwraca formularz HTML umożliwiający użytkownikowi wprowadzenie
    // imienia, adresu e-mail oraz wiadomości. Formularz jest kompatybilny z metodą WyslijMailKontakt().
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
    // Sprawdza, czy użytkownik wypełnił wszystkie wymagane pola formularza kontaktowego.
    // Jeśli pola są poprawne, wiadomość e-mail jest wysyłana na adres podany w parametrze $odbiorca.

    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        // Wyświetla komunikat o błędzie i ponownie pokazuje formularz kontaktowy.
        echo '<p>Nie wypełniłeś wszystkich wymaganych pól.</p>';
        echo PokazKontakt(); // Powiązanie z metodą PokazKontakt().
    } else {
        // Przygotowuje dane e-mail do wysyłki
        $mail = [];
        $mail['subject'] = $_POST['temat']; // Temat wiadomości
        $mail['body'] = $_POST['tresc']; // Treść wiadomości
        $mail['sender'] = $_POST['email']; // Adres e-mail nadawcy
        $mail['recipient'] = $odbiorca; // Docelowy adres e-mail podany w parametrze

        // Tworzy nagłówki e-mail
        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\r\n";
        $header .= "Mime-Version: 1.0\r\n";
        $header .= "Content-Type: text/plain; charset=utf-8\r\n";
        $header .= "X-sender: <" . $mail['sender'] . ">\r\n";
        $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $header .= "X-Priority: 3\r\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\r\n";

        // Wysyła wiadomość za pomocą funkcji mail()
        if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
            echo '<p>Wiadomość została wysłana. Dziękujemy za kontakt!</p>';
        } else {
            echo '<p>Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
        }
    }
}

// Funkcja przypominająca hasło
function PrzypomnijHaslo() {
    // Tworzy uproszczoną metodę przypomnienia hasła admina.
    // Jeśli użytkownik poda poprawny adres e-mail, wysyła hasło na podany adres.
    if (isset($_POST['reset_password'])) {
        $email = htmlspecialchars($_POST['email']);
        
        // Weryfikacja adresu e-mail. W tej implementacji dane admina są hardkodowane.
        $admin_email = "admin@example.com"; // Adres e-mail admina
        $admin_password = "admin123"; // Obecne hasło admina

        if ($email === $admin_email) {
            // Przygotowanie e-mail z przypomnieniem hasła
            $to = $email;
            $subject = "Przypomnienie hasła do panelu admina";
            $headers = "From: no-reply@moja-strona.pl\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            $body = "Otrzymujesz to przypomnienie, ponieważ poprosiłeś o przypomnienie hasła do panelu admina.\n\n";
            $body .= "Twoje obecne hasło to: $admin_password\n\n";
            $body .= "Jeśli nie poprosiłeś o przypomnienie, zignoruj tę wiadomość.";

            // Wysyła e-mail z hasłem
            if (mail($to, $subject, $body, $headers)) {
                echo '<p>Hasło zostało wysłane na Twój adres e-mail.</p>';
            } else {
                echo '<p>Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
            }
        } else {
            echo '<p>Podany adres e-mail nie pasuje do konta admina.</p>';
        }
    }

    // Tworzy formularz resetowania hasła
    return '
    <h1>Przypomnienie hasła</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
        <label for="email">Adres e-mail:</label><br>
        <input type="email" name="email" id="email" required><br><br>
        <button type="submit" name="reset_password">Przypomnij hasło</button>
    </form>';
}

?>