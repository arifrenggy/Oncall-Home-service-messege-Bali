// tests/validate-app-js.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking js/app.js script...");
    const jsPath = path.join(__dirname, '../js/app.js');
    assert.ok(fs.existsSync(jsPath), "Missing js/app.js");

    const content = fs.readFileSync(jsPath, 'utf8');

    // Check required programmatic constructs
    assert.ok(content.includes('fetch'), "Must use fetch to load data");
    assert.ok(content.includes('data/content.json'), "Must fetch data/content.json");
    assert.ok(content.includes('encodeURIComponent'), "Must encode URL parameters for WhatsApp text");

    console.log("PASS: js/app.js exists and contains core fetching logic!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
