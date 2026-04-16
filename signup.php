<?php include 'config.php'; 

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_POST) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($username) < 3) $error = "Username must be at least 3 characters";
    elseif (strlen($password) < 6) $error = "Password must be at least 6 characters";

    if (!$error) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php");
            exit;
        } catch (Exception $e) {
            $error = "Username already exists";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Telegram Reposts</title>
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
            <h1 class="text-4xl font-bold mt-4">Create Account</h1>
        </div>

        <div class="bg-[#1f1f1f] rounded-3xl p-8">
            <?php if ($error): ?>
                <p class="text-red-400 text-center mb-6"><?= $error ?></p>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <input type="text" name="username" placeholder="Choose username" required
                       class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg outline-none border border-gray-700 focus:border-blue-500">

                <input type="password" name="password" placeholder="Password (min 6 chars)" required
                       class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg outline-none border border-gray-700 focus:border-blue-500">

                <button type="submit" 
                        class="w-full bg-[#229ED9] hover:bg-blue-600 py-4 rounded-2xl text-xl font-medium">
                    Create Account
                </button>
            </form>
        </div>
    </div>
</body>
</html>