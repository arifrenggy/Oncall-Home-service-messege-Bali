// tests/validate-ssr-homepage.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php SSR code...");
    const homePath = path.join(__dirname, '../index.php');
    assert.ok(fs.existsSync(homePath), "Missing index.php");

    const content = fs.readFileSync(homePath, 'utf8');

    // Assert server-side code structure
    assert.ok(content.includes("require_once 'config.php'"), "Missing database configuration loading");
    assert.ok(content.includes('$db->query(') || content.includes('$db->prepare('), "Missing database querying calls");
    assert.ok(content.includes('bookService('), "Missing WhatsApp bookService inline js utility");

    console.log("PASS: index.php SSR structure is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
