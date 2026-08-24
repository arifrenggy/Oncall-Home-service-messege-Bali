<?php
$dsn = "mysql:host=" . getenv('MYSQLHOST') . ";port=" . getenv('MYSQLPORT') . ";dbname=" . getenv('MYSQLDATABASE') . ";charset=utf8mb4';";
$dsn = "mysql:host=" . getenv('MYSQLHOST') . ";port=" . getenv('MYSQLPORT') . ";dbname=" . getenv('MYSQLDATABASE') . ";charset=utf8mb4";
try {
    $db = new PDO($dsn, getenv('MYSQLUSER'), getenv('MYSQLPASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $stmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'brandLogo'");
    $val = $stmt->fetchColumn();
    echo "Database brandLogo: " . var_export($val, true) . "\n";
    if ($val) {
        $full_path = __DIR__ . '/' . $val;
        echo "Full path: $full_path\n";
        echo "File exists: " . (file_exists($full_path) ? 'YES' : 'NO') . "\n";
    }
    echo "Directory exists: " . (is_dir(__DIR__ . '/assets/images') ? 'YES' : 'NO') . "\n";
    echo "Directory writable: " . (is_writable(__DIR__ . '/assets/images') ? 'YES' : 'NO') . "\n";
    
    echo "Files in assets/images/:\n";
    print_r(scandir(__DIR__ . '/assets/images'));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
