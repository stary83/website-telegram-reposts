<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_POST) {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if ($user === 'admin' && $pass === 'password123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = 'Admin';
        header("Location: index.php");
        exit;
    } else {
        $error = "Incorrect username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Telegram Reposts</title>
    
    <!-- LOCAL CSS -->
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    
    <style>body { background: #0a0a0a; }</style>
</head>
<body class="text-white min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <a href="index.php" class="inline-flex items-center gap-2 text-[#229ED9] hover:text-blue-400 mb-8">
            <i class="fas fa-arrow-left text-xl"></i>
            <span>Back to Channels</span>
        </a>

        <div class="bg-[#1f1f1f] rounded-3xl p-10">
            <div class="text-center mb-8">
                <i class="fa-brands fa-telegram text-6xl text-[#229ED9]"></i>
                <h1 class="text-3xl font-bold mt-4">Admin Login</h1>
            </div>

            <?php if ($error): ?>
                <p class="text-red-400 text-center mb-6"><?= $error ?></p>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <input type="text" name="username" placeholder="Username" required
                       class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg focus:outline-none">
                
                <input type="password" name="password" placeholder="Password" required
                       class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg focus:outline-none">

                <button type="submit" 
                        class="w-full bg-[#229ED9] hover:bg-blue-600 py-4 rounded-2xl text-xl font-medium">
                    Login
                </button>
            </form>

            <p class="text-center text-gray-500 text-sm mt-6">
                Demo: <strong>admin</strong> / <strong>password123</strong>
            </p>
        </div>
    </div>
</body>
</html>