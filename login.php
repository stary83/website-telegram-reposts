<?php include 'config.php'; 

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>
        body { background: #0a0a0a; }
        input { 
            color: #ffffff !important; 
            background-color: #2a2a2a !important;
        }
        input::placeholder { color: #888888 !important; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <img src="resources/rose.svg" width="80" height="80" class="mx-auto">
            <h1 class="text-4xl font-bold mt-4">Telegram Reposts</h1>
        </div>

        <div class="bg-[#1f1f1f] rounded-3xl p-8">
            <?php if ($error): ?>
                <p class="text-red-400 text-center mb-6">Incorrect username or password</p>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <input type="text" name="username" placeholder="Username" required
                       class="w-full rounded-2xl px-6 py-4 text-lg outline-none border border-gray-600">

                <input type="password" name="password" placeholder="Password" required
                       class="w-full rounded-2xl px-6 py-4 text-lg outline-none border border-gray-600">

                <button type="submit" 
                        class="w-full bg-[#229ED9] hover:bg-blue-600 py-4 rounded-2xl text-xl font-medium">
                    Login
                </button>
            </form>

            <div class="text-center mt-8 text-gray-400">
                No account? <a href="signup.php" class="text-[#229ED9]">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>



