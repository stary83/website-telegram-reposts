<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Reposts</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>
        body { background: #0a0a0a; }
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-[#1f1f1f] px-5 py-4 rounded-3xl">
            <div class="flex items-center gap-3">
                <img src="resources/rose.svg" width="48" height="48" alt="Rose">
                <h1 class="text-3xl font-bold">Telegram Reposts</h1>
            </div>
            <div class="flex gap-5 items-center">
                <a href="about.php" class="hover:text-blue-400">About</a>
                <a href="contact.php" class="hover:text-blue-400">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="text-red-400 hover:text-red-300">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="bg-[#229ED9] px-5 py-2 rounded-2xl text-sm font-medium">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="text-2xl font-medium mb-6 px-2">Public Channels</h2>

        <?php
        $stmt = $pdo->query("SELECT * FROM channels ORDER BY created_at DESC");
        $channels = $stmt->fetchAll();
        ?>

        <?php if (empty($channels)): ?>
            <p class="text-gray-400 text-center py-20">No channels created yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($channels as $ch): ?>
                    <a href="channel.php?id=<?= $ch['id'] ?>" 
                       class="bg-[#1f1f1f] hover:bg-[#2a2a2a] rounded-3xl p-6 transition">
                        <div class="flex items-center gap-4">
                            <?php if ($ch['image_path']): ?>
                                <img src="<?= htmlspecialchars($ch['image_path']) ?>" 
                                     class="w-16 h-16 rounded-2xl object-cover">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center text-4xl">
                                    <?= strtoupper(substr($ch['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="text-2xl font-semibold"><?= htmlspecialchars($ch['name']) ?></h3>
                                <p class="text-gray-400 mt-1"><?= htmlspecialchars($ch['description'] ?? '') ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <div class="mt-12 text-center">
                <a href="create-channel.php" class="inline-flex items-center gap-3 bg-[#229ED9] hover:bg-blue-600 px-8 py-4 rounded-3xl text-lg font-medium">
                    <i class="fas fa-plus"></i> Create New Channel (Admin)
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>