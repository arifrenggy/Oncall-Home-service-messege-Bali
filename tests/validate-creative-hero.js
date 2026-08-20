// tests/validate-creative-hero.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking creative Hero layout in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for centered hero text and gallery containers
    assert.ok(content.includes('text-center') && content.includes('mx-auto'), "Hero header text is not centered");
    assert.ok(content.includes('rounded-t-full') || content.includes('rounded-full'), "Hero gallery is missing organic or archway masks");

    console.log("PASS: Creative Hero layout looks valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
