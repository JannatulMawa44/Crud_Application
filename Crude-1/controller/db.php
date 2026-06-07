<?php
/**
 * Database Connection using PDO
 */

$host = '127.0.0.1';
$db   = 'user_management';
$user = 'root';
$pass = ''; // Default XAMPP/local setting
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log error and show user-friendly message
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
