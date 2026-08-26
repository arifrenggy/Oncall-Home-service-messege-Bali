<?php
// update_descriptions.php
// Script untuk membersihkan kata-kata melanggar kebijakan Google Ads di database live.
// Script ini akan menghapus dirinya sendiri setelah berhasil dijalankan.

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>Mengupdate Deskripsi Layanan honeymassagebali.shop...</h2>";

try {
    // Daftar kata-kata sensitif dan penggantinya
    $replacements = [
        'chronic pain' => 'deep muscle tension',
        'joint pain' => 'joint flexibility',
        'prevent injuries' => 'muscle recovery',
        'reduce headaches' => 'facial tension relief',
        'remove toxins' => 'support natural circulation',
        'reduce swelling' => 'refresh the body',
        'healing process' => 'revitalization process',
        'common cold' => 'fatigue',
        'muscle aches' => 'muscle stiffness',
        'pregnancy-related discomfort' => 'pregnancy-related needs'
    ];

    $totalUpdated = 0;

    foreach ($replacements as $target => $replacement) {
        // Melakukan update case-insensitive/sensitive replace
        $stmt = $db->prepare("UPDATE services SET description = REPLACE(description, ?, ?) WHERE description LIKE ?");
        $stmt->execute([$target, $replacement, "%" . $target . "%"]);
        $totalUpdated += $stmt->rowCount();
        
        // Cek juga untuk huruf besar di awal kata (Capitalized)
        $targetCap = ucfirst($target);
        $replacementCap = ucfirst($replacement);
        $stmtCap = $db->prepare("UPDATE services SET description = REPLACE(description, ?, ?) WHERE description LIKE ?");
        $stmtCap->execute([$targetCap, $replacementCap, "%" . $targetCap . "%"]);
        $totalUpdated += $stmtCap->rowCount();
    }

    echo "<p style='color: green; font-weight: bold;'>Sukses! Berhasil memperbarui data deskripsi di database.</p>";
    echo "<p>Menghapus script ini untuk keamanan...</p>";

    // Menghapus file ini sendiri agar tidak disalahgunakan
    if (unlink(__FILE__)) {
        echo "<p style='color: blue; font-weight: bold;'>Script aman: File 'update_descriptions.php' telah berhasil dihapus secara otomatis dari server!</p>";
    } else {
        echo "<p style='color: red;'>Peringatan: Gagal menghapus file secara otomatis. Silakan hapus file 'update_descriptions.php' secara manual dari cPanel Anda demi keamanan.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>Error Database: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
}
