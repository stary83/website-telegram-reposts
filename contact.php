<?php 
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
if ($_POST) {
    $message = trim($_POST['message']);
    if ($message) {
        $stmt = $pdo->prepare("INSERT INTO user_messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message]);
        $success = "Your message has been sent to the admin.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <link rel="stylesheet" href="resources/tailwind.css">
    <link rel="stylesheet" href="resources/fontawesome.css">
    <style>body { background: #0a0a0a; }</style>
</head>
<body class="text-white min-h-screen p-6">
    <div class="max-w-lg mx-auto">
        <a href="index.php" class="text-[#229ED9] mb-8 inline-block">← Back</a>

        <div class="bg-[#1f1f1f] rounded-3xl p-8">
            <h1 class="text-3xl font-bold mb-6">Send Message to Admin</h1>
            
            <?php if ($success): ?>
                <p class="text-green-400 text-center mb-6"><?= $success ?></p>
            <?php endif; ?>

            <form method="post">
                <textarea name="message" rows="6" placeholder="Write your message here..." required
                          class="w-full bg-[#2a2a2a] rounded-3xl px-6 py-5 text-lg outline-none"></textarea>
                
                <button type="submit" 
                        class="mt-6 w-full bg-[#229ED9] py-4 rounded-2xl text-xl font-medium">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</body>
</html>