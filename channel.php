<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']);

$dataFile = 'data/channels.json';
$channels = json_decode(file_get_contents($dataFile), true) ?: [];

$slug = $_GET['slug'] ?? '';
$channel = null;
foreach ($channels as $c) {
    if ($c['slug'] === $slug) {
        $channel = $c;
        break;
    }
}
if (!$channel) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($channel['name']) ?> - Telegram Reposts</title>
    
    <!-- LOCAL CSS -->
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    
    <style>
        body { background: #0a0a0a; }
        .msg { max-width: 85%; }
        .sent { background: #229ED9; border-radius: 20px 20px 4px 20px; }
        .received { background: #1f1f1f; border-radius: 20px 20px 20px 4px; }
    </style>
</head>
<body class="text-white flex flex-col min-h-screen">
    <!-- Header -->
    <div class="bg-[#1f1f1f] px-4 py-4 flex items-center gap-4 sticky top-0 z-10 border-b border-gray-800">
        <a href="index.php" class="text-3xl p-2 hover:bg-gray-800 rounded-xl">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold"><?= htmlspecialchars($channel['name']) ?></h1>
            <p class="text-xs text-gray-400"><?= htmlspecialchars($channel['description']) ?></p>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 p-4 overflow-y-auto space-y-8 pb-24" id="chat">
        <?php
        $messages = $channel['messages'] ?? [];
        usort($messages, fn($a,$b) => strtotime($a['timestamp']) - strtotime($b['timestamp']));

        $currentDate = '';
        foreach ($messages as $msg):
            $date = date('l, F j, Y', strtotime($msg['timestamp']));
            if ($date !== $currentDate):
                $currentDate = $date;
        ?>
            <div class="text-center text-xs text-gray-400 my-8"><?= $date ?></div>
        <?php endif; ?>
            <div class="flex <?= $msg['from'] === 'You' ? 'justify-end' : 'justify-start' ?>">
                <div class="msg p-4 <?= $msg['from'] === 'You' ? 'sent' : 'received' ?>">
                    <?php if (!empty($msg['file'])): ?>
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg['file'])): ?>
                            <img src="<?= htmlspecialchars($msg['file']) ?>" class="max-w-[280px] rounded-2xl mb-3">
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($msg['file']) ?>" download class="flex items-center gap-2 text-blue-400 hover:text-blue-300">
                                <i class="fas fa-paperclip"></i> <?= basename($msg['file']) ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($msg['text']): ?>
                        <p class="text-[17px] leading-relaxed"><?= nl2br(htmlspecialchars($msg['text'])) ?></p>
                    <?php endif; ?>
                    <div class="text-[10px] text-right mt-2 opacity-70">
                        <?= date('H:i', strtotime($msg['timestamp'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($messages)): ?>
            <div class="text-center text-gray-400 py-20">No messages yet.</div>
        <?php endif; ?>
    </div>

    <!-- Send bar - only for logged in users -->
    <?php if ($isLoggedIn): ?>
    <form action="send-message.php" method="post" enctype="multipart/form-data" 
          class="bg-[#1f1f1f] p-4 flex items-center gap-3 sticky bottom-0 border-t border-gray-800">
        <input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>">
        <div class="flex-1 bg-[#2a2a2a] rounded-3xl px-5 py-3 flex items-center">
            <input type="text" name="text" placeholder="Type a message..." 
                   class="flex-1 bg-transparent outline-none text-lg placeholder-gray-400">
            <label class="cursor-pointer ml-4 text-2xl text-gray-400 hover:text-white px-2">
                <i class="fas fa-paperclip"></i>
                <input type="file" name="file" class="hidden">
            </label>
        </div>
        <button type="submit" class="w-12 h-12 bg-[#229ED9] rounded-3xl flex items-center justify-center text-2xl">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
    <?php else: ?>
        <div class="bg-[#1f1f1f] p-4 text-center text-sm text-gray-400 border-t border-gray-800">
            <a href="login.php" class="text-[#229ED9] hover:underline">Login as admin</a> to send messages
        </div>
    <?php endif; ?>
</body>
</html>