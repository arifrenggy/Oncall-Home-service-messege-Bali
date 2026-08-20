// tests/validate-admin-db-auth.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking admin/index.php database settings integrations...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const content = fs.readFileSync(adminPath, 'utf8');

    // Check if config.php load is present
    assert.ok(content.includes("require_once '../config.php'") || content.includes("require_once __DIR__ . '/../config.php'"), "Missing db config import");
    assert.ok(content.includes('ADMIN_PASSWORD_HASH'), "Missing reference to ADMIN_PASSWORD_HASH definition");

    console.log("PASS: PHP admin auth structure is integrated!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
