// tests/validate-truly-fonts-colors.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly style font and color settings...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for Poppins font and hex code variables
    assert.ok(content.includes('Poppins') && content.includes('Inter'), "Missing Poppins or Inter fonts");
    assert.ok(content.includes('#192a3d') || content.includes('#AE7D64') || content.includes('AE7D64'), "Missing Truly corporate colors");

    console.log("PASS: Truly style fonts and colors look correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
