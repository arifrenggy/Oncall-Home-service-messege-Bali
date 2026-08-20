// tests/validate-creative-why.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking creative Why Choose Us layout in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Verify staggered layout margins
    assert.ok(content.includes('translate-y-') || content.includes('md:translate-y-'), "Missing staggered offset translations in Why-Us section");

    console.log("PASS: Why-Us staggered layout looks valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
