<?php 
include 'config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description'] ?? '');

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $target = 'uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_path = $target;
            }
        } else {
            $error = "Only JPG, PNG, GIF and WebP images are allowed.";
        }
    }

    if (!$error && $name) {
        $stmt = $pdo->prepare("INSERT INTO channels (name, description, image_path, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $image_path, $_SESSION['user_id']]);
        $success = "Channel created successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Channel - Telegram Reposts</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>body { background: #0a0a0a; }</style>
</head>
<body class="text-white min-h-screen p-6">
    <div class="max-w-lg mx-auto">
        <a href="index.php" class="inline-flex items-center gap-2 text-[#229ED9] mb-8">
            ← Back to Channels
        </a>

        <div class="bg-[#1f1f1f] rounded-3xl p-8">
            <h1 class="text-3xl font-bold mb-6">Create New Channel</h1>

            <?php if ($success): ?>
                <p class="text-green-400 text-center mb-6"><?= $success ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="text-red-400 text-center mb-6"><?= $error ?></p>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <input type="text" name="name" placeholder="Channel Name (e.g. @NewsDaily)" required
                       class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg outline-none">

                <textarea name="description" placeholder="Description (optional)" rows="3"
                          class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg outline-none"></textarea>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Channel Image (optional)</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full bg-[#2a2a2a] rounded-2xl px-6 py-4 text-lg">
                </div>

                <button type="submit" 
                        class="w-full bg-[#229ED9] hover:bg-blue-600 py-4 rounded-2xl text-xl font-medium">
                    Create Channel
                </button>
            </form>
        </div>
    </div>
</body>
</html>