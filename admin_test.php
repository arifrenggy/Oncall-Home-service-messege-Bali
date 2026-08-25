<?php
// admin_test.php - Diagnostic script to test Admin Panel CRUD live on the server
require_once __DIR__ . '/config.php';

header("Content-Type: text/plain");
echo "=== Admin Panel Live Diagnostic Tests ===\n";

try {
    $db->beginTransaction();

    // Test 1: Save General Settings (dynamic ratings)
    echo "Test 1: Saving General Settings... ";
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->execute(['testKey', 'testValue', 'testValue']);
    
    // Verify it was saved
    $chk = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'testKey'")->fetchColumn();
    if ($chk === 'testValue') {
        echo "PASSED\n";
    } else {
        throw new Exception("Failed to save setting");
    }

    // Test 2: Add a new Massage Service (CRUD Create)
    echo "Test 2: Creating a new service... ";
    $ins = $db->prepare("INSERT INTO services (service_id, title, description, image_path, featured) VALUES (?, ?, ?, ?, ?)");
    $ins->execute(['test-service', 'Test Service', 'Test description', 'assets/images/about-massage.webp', 0]);
    $service_id = $db->lastInsertId();
    
    if ($service_id > 0) {
        echo "PASSED (ID: $service_id)\n";
    } else {
        throw new Exception("Failed to create service");
    }

    // Test 3: Add Service Options (Duration & Price)
    echo "Test 3: Creating service duration options... ";
    $ins_opt = $db->prepare("INSERT INTO service_options (service_ref, duration, price) VALUES (?, ?, ?)");
    $ins_opt->execute([$service_id, '60 Mins', '150,000 IDR']);
    
    // Verify option exists
    $opt_count = $db->query("SELECT COUNT(*) FROM service_options WHERE service_ref = $service_id")->fetchColumn();
    if ($opt_count == 1) {
        echo "PASSED\n";
    } else {
        throw new Exception("Failed to create service option");
    }

    // Test 4: Update Massage Service (CRUD Update)
    echo "Test 4: Updating the service... ";
    $upd = $db->prepare("UPDATE services SET title = ? WHERE id = ?");
    $upd->execute(['Test Service Updated', $service_id]);
    
    $chk_title = $db->query("SELECT title FROM services WHERE id = $service_id")->fetchColumn();
    if ($chk_title === 'Test Service Updated') {
        echo "PASSED\n";
    } else {
        throw new Exception("Failed to update service title");
    }

    // Test 5: Delete Massage Service (CRUD Delete / Cleanup)
    echo "Test 5: Deleting service and options... ";
    $del_opts = $db->prepare("DELETE FROM service_options WHERE service_ref = ?");
    $del_opts->execute([$service_id]);
    
    $del_srv = $db->prepare("DELETE FROM services WHERE id = ?");
    $del_srv->execute([$service_id]);
    
    $chk_del = $db->query("SELECT COUNT(*) FROM services WHERE id = $service_id")->fetchColumn();
    if ($chk_del == 0) {
        echo "PASSED\n";
    } else {
        throw new Exception("Failed to delete service");
    }

    // Cleanup Test 1 key
    $db->exec("DELETE FROM settings WHERE setting_key = 'testKey'");

    $db->rollBack();
    echo "All admin CRUD database operations PASSED and rolled back safely!\n";

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "FAILED: " . $e->getMessage() . "\n";
}
