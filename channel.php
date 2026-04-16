<?php 
include 'config.php';

$channel_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM channels WHERE id = ?");
$stmt->execute([$channel_id]);
$channel = $stmt->fetch();

if (!$channel) {
    header("Location: index.php");
    exit;
}

// Fetch messages
$stmt = $pdo->prepare("SELECT m.*, u.username, u.is_admin 
                       FROM messages m 
                       LEFT JOIN users u ON m.user_id = u.id 
                       WHERE m.channel_id = ? 
                       ORDER BY m.timestamp ASC");
$stmt->execute([$channel_id]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($channel['name']) ?></title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>
        body { background: #0a0a0a; }
        .bubble {
            max-width: 78%;
            padding: 12px 16px;
            border-radius: 20px;
            margin-bottom: 10px;
            position: relative;
        }
        .admin-bubble { 
            background: #229ED9; 
            color: white; 
            border-bottom-right-radius: 4px;
            align-self: flex-end;
        }
        .user-bubble { 
            background: #2d2d2d; 
            color: #e5e5e5; 
            border-bottom-left-radius: 4px;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen text-white">
    <!-- Header -->
    <div class="bg-[#1f1f1f] px-4 py-4 flex items-center gap-4 sticky top-0 border-b border-gray-700">
        <a href="index.php" class="p-2">
            <img src="resources/telegram-back.svg" width="28" height="28" alt="Back">
        </a>
        <div class="flex items-center gap-3">
            <?php if ($channel['image_path']): ?>
                <img src="<?= htmlspecialchars($channel['image_path']) ?>" class="w-11 h-11 rounded-2xl object-cover">
            <?php else: ?>
                <div class="w-11 h-11 bg-[#229ED9] rounded-2xl flex items-center justify-center text-3xl">
                    <?= strtoupper(substr($channel['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <h1 class="font-semibold"><?= htmlspecialchars($channel['name']) ?></h1>
                <p class="text-xs text-gray-400">Public Channel</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 p-4 overflow-y-auto space-y-7 bg-[#0a0a0a]">
        <?php 
        $lastDate = '';
        foreach ($messages as $msg): 
            $date = date('l, F j, Y', strtotime($msg['timestamp']));
            if ($date !== $lastDate):
                $lastDate = $date;
        ?>
            <div class="text-center text-xs text-gray-500 my-10"><?= $date ?></div>
        <?php endif; ?>
            <div class="flex <?= $msg['is_admin'] ? 'justify-end' : 'justify-start' ?>">
                <div class="bubble <?= $msg['is_admin'] ? 'admin-bubble' : 'user-bubble' ?>">
                    <p class="text-[16px] leading-relaxed"><?= nl2br(htmlspecialchars($msg['text'])) ?></p>
                    <span class="text-[10px] opacity-70 block text-right mt-1">
                        <?= date('H:i', strtotime($msg['timestamp'])) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Send Bar - Only visible to Admin -->
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
    <form action="send-message.php" method="post" class="bg-[#1f1f1f] p-4 border-t border-gray-700">
        <input type="hidden" name="channel_id" value="<?= $channel_id ?>">
        <div class="flex gap-3">
            <input type="text" name="text" placeholder="Type a message..." required
                   class="flex-1 bg-[#2a2a2a] rounded-full px-6 py-4 outline-none">
            <button type="submit" class="bg-[#229ED9] px-8 rounded-full font-medium">
                Send
            </button>
        </div>
    </form>
    <?php else: ?>
        <div class="bg-[#1f1f1f] p-4 text-center text-sm text-gray-400 border-t border-gray-700">
            Login as admin to send messages in this channel
        </div>
    <?php endif; ?>
</body>
</html>