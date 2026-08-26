<?php
// update_descriptions_v2.php
// Script penyempurnaan tata bahasa (grammar) dan perbaikan kata sensitif yang tersisa.
// Script ini akan menghapus dirinya sendiri setelah berhasil dijalankan.

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>Menyempurnakan Deskripsi Layanan di honeymassagebali.shop...</h2>";

try {
    // Daftar penyempurnaan kalimat agar tata bahasa Inggris terdengar natural
    $replacements = [
        'relieve joint flexibility' => 'improve joint mobility',
        'to muscle recovery' => 'to promote muscle recovery',
        'facial tension relief' => 'restore facial freshness',
        'reduce joint swelling' => 'relieve joint pressure',
        'the fatigue, muscle stiffness, and fatigue' => 'body fatigue, muscle stiffness, and tiredness'
    ];

    $totalUpdated = 0;

    foreach ($replacements as $target => $replacement) {
        $stmt = $db->prepare("UPDATE services SET description = REPLACE(description, ?, ?) WHERE description LIKE ?");
        $stmt->execute([$target, $replacement, "%" . $target . "%"]);
        $totalUpdated += $stmt->rowCount();
    }

    echo "<p style='color: green; font-weight: bold;'>Sukses! Berhasil menyempurnakan $totalUpdated deskripsi layanan agar lebih natural.</p>";
    echo "<p>Menghapus script v2 ini untuk keamanan...</p>";

    // Menghapus file ini sendiri agar tidak disalahgunakan
    if (unlink(__FILE__)) {
        echo "<p style='color: blue; font-weight: bold;'>Script aman: File 'update_descriptions_v2.php' telah berhasil dihapus secara otomatis dari server!</p>";
    } else {
        echo "<p style='color: red;'>Peringatan: Silakan hapus file 'update_descriptions_v2.php' secara manual dari cPanel Anda jika tidak terhapus otomatis.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>Error Database: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
}
