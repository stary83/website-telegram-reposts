<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']);

$dataFile = 'data/channels.json';
if (!file_exists('data')) mkdir('data', 0777, true);
if (!file_exists($dataFile)) file_put_contents($dataFile, '[]');

$channels = json_decode(file_get_contents($dataFile), true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Reposts</title>
    
    <!-- Fully Local CSS -->
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    
    <style>
        body { background: #0a0a0a; font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="text-white min-h-screen pb-20">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold flex items-center gap-3">
                <!-- Local Rose Icon -->
                <img src="resources/rose.png" alt="Rose" width="42" height="42" class="drop-shadow-md">
                Telegram Reposts
            </h1>
            
            <?php if ($isLoggedIn): ?>
                <a href="logout.php" class="text-gray-400 hover:text-white">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-[#229ED9] hover:bg-blue-600 px-6 py-2.5 rounded-2xl font-medium flex items-center gap-2">
                    <i class="fas fa-lock"></i> Admin Login
                </a>
            <?php endif; ?>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-medium">Channels</h2>
            <?php if ($isLoggedIn): ?>
                <a href="create-channel.php" 
                   class="w-14 h-14 bg-[#229ED9] hover:bg-blue-600 rounded-3xl flex items-center justify-center text-3xl shadow-lg">
                    <i class="fas fa-plus"></i>
                </a>
            <?php endif; ?>
        </div>

        <div class="space-y-4">
            <?php if (empty($channels)): ?>
                <p class="text-gray-400 text-center py-16">No channels yet.<br>
                <?php if (!$isLoggedIn): ?>Login as admin to create the first channel.<?php endif; ?></p>
            <?php else: ?>
                <?php foreach ($channels as $ch): 
                    $last = !empty($ch['messages']) ? max(array_column($ch['messages'], 'timestamp')) : null;
                    $lastText = $last ? date('M j, Y', strtotime($last)) : 'never';
                ?>
                    <a href="channel.php?slug=<?= htmlspecialchars($ch['slug']) ?>" 
                       class="block bg-[#1f1f1f] hover:bg-[#2a2a2a] rounded-3xl p-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-semibold"><?= htmlspecialchars($ch['name']) ?></h3>
                            <p class="text-gray-400 text-sm"><?= htmlspecialchars($ch['description']) ?></p>
                        </div>
                        <div class="text-right text-xs text-gray-400">
                            Last updated<br>
                            <span class="font-medium"><?= $lastText ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>