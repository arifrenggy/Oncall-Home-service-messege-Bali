// tests/validate-db-setup.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking database files...");
    const sqlPath = path.join(__dirname, '../schema.sql');
    const configPath = path.join(__dirname, '../config.php');

    assert.ok(fs.existsSync(sqlPath), "Missing schema.sql database init script");
    assert.ok(fs.existsSync(configPath), "Missing config.php connection configuration");

    const configContent = fs.readFileSync(configPath, 'utf8');
    assert.ok(configContent.includes('new PDO'), "config.php must instantiate PDO for DB connections");
    assert.ok(configContent.includes('password_hash'), "config.php must contain admin login configuration hash");

    console.log("PASS: Database init files and config look valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
