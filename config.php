<?php
// config.php

// DB credentials (user can modify these for their cPanel environment)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'oncall_massage_bali');
define('DB_USER', 'root');
define('DB_PASS', '');

// Admin login password hash (using php password_hash function)
define('ADMIN_PASSWORD_HASH', '$2y$10$aJ5Cgohf/optRaAKUf8tvuEQUscjGE09UYaqjuOAKpzt2EGGPsa0K');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Fallback error message (safe for production)
    die("Database connection failed. Please edit config.php with correct credentials.");
}
