<?php
session_start();
require_once '../utils.php';

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 1;
        header('Location: index.php');
        exit;
    } else {
        $loginError = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation Menu -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="../index.php" class="text-gray-800 font-bold text-xl">Homepage</a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="index.php" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <?php else: ?>
                            <a href="login.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-8">
            <h1 class="text-2xl font-semibold mb-4">Login</h1>
            <?php if ($loginError): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                   <span class="block sm:inline"><?php echo $loginError; ?></span>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" name="username" id="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full transition-colors">Login</button>
            </form>
        </div>
    </div>
</body>
</html>