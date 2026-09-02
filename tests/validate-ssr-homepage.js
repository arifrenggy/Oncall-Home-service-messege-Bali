// tests/validate-ssr-homepage.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php SSR code...");
    const homePath = path.join(__dirname, '../index.php');
    const headerPath = path.join(__dirname, '../header.php');
    assert.ok(fs.existsSync(homePath), "Missing index.php");
    const content = fs.readFileSync(homePath, 'utf8');

    // index.php pulls in the shared header, which loads config.php (DB connection)
    assert.ok(content.includes("require_once 'header.php'"), "index.php must include header.php");
    const headerContent = fs.readFileSync(headerPath, 'utf8');
    assert.ok(headerContent.includes("config.php"), "header.php must load database configuration");
    assert.ok(headerContent.includes('session_start'), "Missing session start for CSRF/flash handling");

    // Assert server-side code structure
    assert.ok(content.includes('$db->query(') || content.includes('$db->prepare('), "Missing database querying calls");
    assert.ok(content.includes('bookService('), "Missing WhatsApp bookService inline js utility");

    console.log("PASS: index.php SSR structure is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
