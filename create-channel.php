<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_POST) {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    if ($name) {
        $dataFile = 'data/channels.json';
        $channels = json_decode(file_get_contents($dataFile), true) ?: [];
        
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        $channels[] = [
            'id' => time(),
            'slug' => $slug,
            'name' => $name,
            'description' => $desc ?: 'No description',
            'messages' => []
        ];
        
        file_put_contents($dataFile, json_encode($channels, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Channel</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>body { background: #0a0a0a; }</style>
</head>
<body class="bg-[#0a0a0a] text-white min-h-screen flex items-center">
    <div class="max-w-md mx-auto w-full px-6">
        <a href="index.php" class="flex items-center gap-2 text-[#229ED9] mb-8"><i class="fas fa-arrow-left"></i> Back</a>
        
        <h1 class="text-3xl font-bold mb-8">Create New Channel</h1>
        
        <form method="post" class="space-y-6">
            <input type="text" name="name" placeholder="Channel name (e.g. @MyChannel)" required
                   class="w-full bg-[#1f1f1f] rounded-3xl px-6 py-5 text-lg">
            <textarea name="description" placeholder="Description (optional)" rows="3"
                      class="w-full bg-[#1f1f1f] rounded-3xl px-6 py-5 text-lg"></textarea>
            <button type="submit" class="w-full bg-[#229ED9] py-5 rounded-3xl text-xl font-medium">Create Channel</button>
        </form>
    </div>
</body>
</html>