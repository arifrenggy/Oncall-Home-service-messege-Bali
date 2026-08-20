// tests/validate-truly-treatments.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly Treatments layout...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for PHP loop indicators for prices and about container id
    assert.ok(content.includes("$opt['duration']") || content.includes('$opt["duration"]'), "Missing pricing loop duration variable");
    assert.ok(content.includes('id="about"'), "Missing About Us layout heading id");

    console.log("PASS: About and Treatments layout looks correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
