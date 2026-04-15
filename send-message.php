<?php
session_start();

// Only logged-in users can send messages
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$slug = $_POST['slug'] ?? '';
$text = trim($_POST['text'] ?? '');
$filePath = null;

if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp','pdf','zip','mp4','txt'];
    if (in_array($ext, $allowed)) {
        $newName = 'uploads/' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $newName)) {
            $filePath = $newName;
        }
    }
}

$dataFile = 'data/channels.json';
$channels = json_decode(file_get_contents($dataFile), true) ?: [];

foreach ($channels as &$ch) {
    if ($ch['slug'] === $slug) {
        $ch['messages'][] = [
            'id'        => time(),
            'text'      => $text,
            'file'      => $filePath,
            'timestamp' => date('Y-m-d H:i:s'),
            'from'      => 'You'
        ];
        break;
    }
}

file_put_contents($dataFile, json_encode($channels, JSON_PRETTY_PRINT));

header("Location: channel.php?slug=" . urlencode($slug));
exit;
?>