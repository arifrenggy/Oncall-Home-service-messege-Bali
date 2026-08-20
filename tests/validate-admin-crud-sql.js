// tests/validate-admin-crud-sql.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php SQL queries...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const content = fs.readFileSync(adminPath, 'utf8');

    // Check SQL methods
    assert.ok(content.includes('UPDATE settings'), "Missing SQL query to update settings table");
    assert.ok(content.includes('DELETE FROM service_options'), "Missing SQL command to flush service options before save");
    assert.ok(content.includes('INSERT INTO services'), "Missing SQL insert service query");
    assert.ok(content.includes('DELETE FROM areas'), "Missing SQL areas cleanup before save");

    console.log("PASS: index.php contains valid SQL statements!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
