// tests/validate-final-residues.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking for obsolete files...");
    const jsonPath = path.join(__dirname, '../data/content.json');
    assert.ok(!fs.existsSync(jsonPath), "Residue file content.json must be deleted");

    const oldTest = path.join(__dirname, '../tests/validate-content.js');
    assert.ok(!fs.existsSync(oldTest), "Old test validation script must be deleted");

    console.log("PASS: Project is clean!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
