<?php 
include 'config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

// Fetch all private messages from users
$stmt = $pdo->query("SELECT um.*, u.username 
                     FROM user_messages um 
                     JOIN users u ON um.user_id = u.id 
                     ORDER BY um.timestamp DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private User Messages - Admin</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>body { background: #0a0a0a; }</style>
</head>
<body class="text-white min-h-screen p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Private User Messages</h1>
            <a href="index.php" class="text-[#229ED9]">← Back to Home</a>
        </div>

        <?php if (empty($messages)): ?>
            <p class="text-gray-400 text-center py-20">No private messages from users yet.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($messages as $msg): ?>
                    <div class="bg-[#1f1f1f] rounded-3xl p-6">
                        <div class="flex justify-between text-sm text-gray-400 mb-2">
                            <span>From: <strong><?= htmlspecialchars($msg['username']) ?></strong></span>
                            <span><?= date('M j, Y • H:i', strtotime($msg['timestamp'])) ?></span>
                        </div>
                        <p class="text-lg leading-relaxed"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>