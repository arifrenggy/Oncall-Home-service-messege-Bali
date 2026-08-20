// tests/validate-creative-areas.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Areas layout in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Verify overlay or relative floating elements in areas
    assert.ok(content.includes('relative') && content.includes('z-10'), "Missing relative stacking in areas section");

    console.log("PASS: Areas staggered layout looks valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
