// tests/validate-php-save.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php data processing block...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const content = fs.readFileSync(adminPath, 'utf8');

    // Check required operations
    assert.ok(content.includes('json_encode'), "Missing json_encode for writing output");
    assert.ok(content.includes('file_put_contents'), "Missing file_put_contents for updating data file");
    assert.ok(content.includes('move_uploaded_file'), "Missing move_uploaded_file logic for image upload handling");

    console.log("PASS: PHP admin saving logic looks correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
