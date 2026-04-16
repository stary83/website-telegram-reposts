<?php
session_start();

// ==================== CHANGE THESE ONLY WHEN UPLOADING TO BERTINA ====================
$host = 'localhost';
$db   = 'telegram_reposts';


// Local NixOS
$user = 'webuser';
$pass = 'nm123456';

// Bertina Hosting
//$user = 'webuser';                    // change if you used a different username
//$pass = 'Bs5KVURYcS?x8@-';

// ===================================================================================

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
