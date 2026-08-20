// tests/validate-creative-menu.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking lookbook layout in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for archway masks and negative margin overlaps
    assert.ok(content.includes('rounded-t-full') && (content.includes('-mt-') || content.includes('-translate-y-')), "Missing archway mask or negative margin overlaps in lookbook menu");
    assert.ok(content.includes('flex-row') || content.includes('flex-row-reverse'), "Missing alternating flex direction for layout asymmetry");

    console.log("PASS: Alternating lookbook menu is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
