<?php
// backup.php — token-gated full SQL dump of the app database.
// Called by the automated backup job. Requires the BACKUP_SECRET env var;
// without it this endpoint is completely inert (fail-closed).
require_once __DIR__ . '/config.php';

header('X-Robots-Tag: noindex, nofollow');

$secret = getenv('BACKUP_SECRET') ?: '';
$provided = $_SERVER['HTTP_X_BACKUP_SECRET'] ?? ($_GET['key'] ?? '');

if ($secret === '' || !is_string($provided) || !hash_equals($secret, $provided)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Forbidden');
}

// Collect table list (exclude the rate-limiting scratch table)
$tables = [];
$res = $db->query("SHOW TABLES");
foreach ($res as $row) {
    $name = array_values($row)[0];
    if ($name === 'login_attempts') continue;
    $tables[] = $name;
}

$lines = [];
$lines[] = "-- Honey Massage Bali (honeymassagebali.shop) database backup";
$lines[] = "-- Generated: " . gmdate('Y-m-d H:i:s') . " UTC";
$lines[] = "SET NAMES utf8mb4;";
$lines[] = "SET FOREIGN_KEY_CHECKS = 0;";

foreach ($tables as $table) {
    // Schema
    $create = $db->query("SHOW CREATE TABLE `" . str_replace('`', '', $table) . "`")->fetch();
    $lines[] = "";
    $lines[] = "-- --------------------------------------------------------";
    $lines[] = "-- Table structure for `$table`";
    $lines[] = "-- --------------------------------------------------------";
    $lines[] = "DROP TABLE IF EXISTS `$table`;";
    $lines[] = $create['Create Table'] . ";";

    // Rows
    $lines[] = "";
    $lines[] = "-- Dumping data for `$table`";
    $stmt = $db->query("SELECT * FROM `$table`");
    $first = true;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($first) {
            $cols = '`' . implode('`, `', array_keys($row)) . '`';
            $lines[] = "INSERT INTO `$table` ($cols) VALUES";
            $first = false;
        }
        $values = [];
        foreach ($row as $value) {
            $values[] = ($value === null) ? 'NULL' : $db->quote((string)$value);
        }
        $lines[] = "(" . implode(', ', $values) . "),";
    }
    if (!$first) {
        // Replace the trailing comma of the last VALUES row with a semicolon
        $lines[count($lines) - 1] = rtrim($lines[count($lines) - 1], ',') . ';';
    } else {
        $lines[] = "-- (no rows)";
    }
}

$lines[] = "";
$lines[] = "SET FOREIGN_KEY_CHECKS = 1;";
$lines[] = "-- End of backup";

header('Content-Type: application/sql; charset=utf-8');
header('Content-Disposition: attachment; filename="honeymassagebali-backup-' . gmdate('Y-m-d-His') . '.sql"');
echo implode("\n", $lines);
