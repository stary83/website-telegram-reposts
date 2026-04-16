<?php 
include 'config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

$channel_id = $_POST['channel_id'] ?? 0;
$text = trim($_POST['text'] ?? '');

if ($channel_id && $text) {
    $stmt = $pdo->prepare("INSERT INTO messages (channel_id, user_id, text) VALUES (?, ?, ?)");
    $stmt->execute([$channel_id, $_SESSION['user_id'], $text]);
}

header("Location: channel.php?id=" . $channel_id);
exit;
?>