// tests/validate-backup-endpoint.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking backup.php endpoint...");
    const content = fs.readFileSync(path.join(__dirname, '../backup.php'), 'utf8');

    // Gated by env-var secret, timing-safe comparison, fail-closed
    assert.ok(content.includes("getenv('BACKUP_SECRET')"), "backup.php must read BACKUP_SECRET from env (fail-closed)");
    assert.ok(content.includes('hash_equals'), "backup.php must use hash_equals for token comparison");
    assert.ok(content.includes('http_response_code(403)'), "backup.php must reject requests without a valid token");
    assert.ok(content.includes("require_once __DIR__ . '/config.php'"), "backup.php must reuse the app DB connection");

    // Real dump logic
    assert.ok(content.includes('SHOW TABLES'), "backup.php must enumerate tables");
    assert.ok(content.includes('SHOW CREATE TABLE'), "backup.php must dump table schemas");
    assert.ok(content.includes('DROP TABLE IF EXISTS'), "backup.php must emit DROP TABLE guards for restore");
    assert.ok(content.includes("INSERT INTO"), "backup.php must emit INSERT statements");
    assert.ok(content.includes('FOREIGN_KEY_CHECKS'), "backup.php must toggle FOREIGN_KEY_CHECKS around the dump");

    // No hardcoded secrets in the file itself
    assert.ok(!/\$2y\$/.test(content), "backup.php must not contain a hardcoded hash");
    assert.ok(!/(password|secret)\s*=\s*['"][^'"]{16,}/i.test(content), "backup.php must not contain hardcoded secrets");

    console.log("PASS: backup endpoint looks valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
