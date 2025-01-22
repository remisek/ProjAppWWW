<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Rest of your code...
session_start();
require_once 'utils.php';

// Konfiguracja
$adminEmail = 'admin@example.com'; // Zmień na prawdziwy adres
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;

$errors = [];
$success = false;
$formData = [
    'name' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sprawdź CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token!";
    }

    // Pobierz i waliduj dane
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'message' => trim($_POST['message'] ?? '')
    ];

    // Walidacja
    if (empty($formData['name'])) {
        $errors[] = "Name is required.";
    }

    if (empty($formData['email'])) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($formData['subject'])) {
        $errors[] = "Subject is required.";
    }

    if (empty($formData['message'])) {
        $errors[] = "Message is required.";
    }

    // Jeśli brak błędów - wyślij email
    if (empty($errors)) {
        $to = $adminEmail;
        $subject = "[Contact Form] " . $formData['subject'];
        $message = "You have received a new message from your website contact form.\n\n".
                   "Name: {$formData['name']}\n".
                   "Email: {$formData['email']}\n".
                   "Message:\n{$formData['message']}";
        
        $headers = "From: {$formData['email']}" . "\r\n" .
                   "Reply-To: {$formData['email']}" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $success = true;
            $formData = []; // Wyczyść formularz po udanym wysłaniu
        } else {
            $errors[] = "There was a problem sending your message. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Contact Us</h1>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    Thank you! Your message has been sent successfully.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="contact.php" method="post" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="subject" class="block text-gray-700 font-medium mb-2">Subject *</label>
                    <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($formData['subject'] ?? '') ?>" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="message" class="block text-gray-700 font-medium mb-2">Message *</label>
                    <textarea id="message" name="message" rows="5" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</body>
</html>