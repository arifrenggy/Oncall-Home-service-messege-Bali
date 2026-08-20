// tests/validate-cleanup.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking for residue Decap CMS config...");
    const configYmlPath = path.join(__dirname, '../admin/config.yml');

    assert.ok(!fs.existsSync(configYmlPath), "File admin/config.yml still exists! Must be deleted.");

    console.log("PASS: CMS configurations cleaned successfully!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
