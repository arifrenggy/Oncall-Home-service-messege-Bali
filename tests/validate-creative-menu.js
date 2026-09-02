// tests/validate-creative-menu.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking treatment menu layout in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Alternating card direction creates the editorial lookbook rhythm
    assert.ok(content.includes('flex-row') || content.includes('flex-row-reverse'), "Missing alternating flex direction for layout asymmetry");
    assert.ok(content.includes('flex-row-reverse'), "Menu cards must alternate direction (flex-row-reverse)");

    console.log("PASS: Alternating treatment menu is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
