<?php
/**
 * SEKALI PAKAI — perbaiki konten DB yang memicu flag Google Ads.
 * Terlindungi token. Hapus file ini setelah dipakai.
 */
require_once __DIR__ . '/config.php';

$TOKEN = 'r3nggy-fix-db-20260905';
if (!isset($_GET['token']) || !hash_equals($TOKEN, $_GET['token'])) {
    http_response_code(404);
    exit('Not found');
}
header('Content-Type: text/plain; charset=utf-8');

// ---- 1. Tampilkan kondisi sebelum ----
echo "=== SETTINGS SEBELUM ===\n";
foreach ($db->query("SELECT setting_key, LEFT(setting_value,100) v FROM settings") as $r) {
    echo $r['setting_key'] . ' => ' . $r['v'] . "\n";
}
echo "\n=== SERVICES SEBELUM ===\n";
foreach ($db->query("SELECT id, service_id, title, LEFT(description,90) d FROM services") as $r) {
    echo $r['id'] . ' | ' . $r['name'] . ' | ' . $r['d'] . "\n";
}

// ---- 2. Update nilai yang bermasalah ----
$updates = [
    "UPDATE settings SET setting_value = REPLACE(setting_value, 'on-call massage', 'home service massage') WHERE setting_value LIKE '%on-call%'",
    "UPDATE settings SET setting_value = REPLACE(setting_value, 'Oncall & home service message', 'Honey Massage Bali') WHERE setting_key = 'brandName'",
    "UPDATE services  SET description  = REPLACE(description, 'on-call deep tissue massage', 'in-home deep tissue massage') WHERE description LIKE '%on-call%'",
    "UPDATE services  SET description  = REPLACE(description, 'on-call massage', 'home service massage') WHERE description LIKE '%on-call%'",
];
echo "\n=== EKSEKUSI UPDATE ===\n";
foreach ($updates as $sql) {
    $n = $db->exec($sql);
    echo ($n !== false ? "OK ($n row)" : "GAGAL") . " :: " . substr($sql, 0, 80) . "...\n";
}

// ---- 3. Tampilkan kondisi sesudah ----
echo "\n=== SETTINGS SESUDAH ===\n";
foreach ($db->query("SELECT setting_key, LEFT(setting_value,100) v FROM settings") as $r) {
    echo $r['setting_key'] . ' => ' . $r['v'] . "\n";
}
echo "\n=== SERVICES SESUDAH ===\n";
foreach ($db->query("SELECT id, service_id, title, LEFT(description,90) d FROM services") as $r) {
    echo $r['id'] . ' | ' . $r['name'] . ' | ' . $r['d'] . "\n";
}
echo "\nSELESAI. Hapus file ini sekarang.";
