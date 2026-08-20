// tests/validate-php-dashboard.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking dashboard UI fields inside index.php...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const content = fs.readFileSync(adminPath, 'utf8');

    // Assert HTML structure elements exist
    assert.ok(content.includes('brandName'), "Missing brandName input field binding");
    assert.ok(content.includes('whatsapp'), "Missing whatsapp field binding");
    assert.ok(content.includes('services'), "Missing services listing UI");
    assert.ok(content.includes('faqs'), "Missing FAQ editing fields");

    console.log("PASS: PHP admin UI fields are correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
