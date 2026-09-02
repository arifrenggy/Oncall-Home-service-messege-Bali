// tests/validate-truly-fonts-colors.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly style font and color settings...");
    // Typography loads via the shared header.php; body palette lives in index.php
    const headerPath = path.join(__dirname, '../header.php');
    const indexPath = path.join(__dirname, '../index.php');
    const headerContent = fs.readFileSync(headerPath, 'utf8');
    const indexContent = fs.readFileSync(indexPath, 'utf8');

    // Check for Poppins font and hex code variables
    assert.ok(headerContent.includes('Poppins') && headerContent.includes('Inter'), "Missing Poppins or Inter fonts");
    assert.ok(indexContent.includes('#192a3d') || indexContent.includes('#AE7D64') || headerContent.includes('#192a3d'), "Missing Truly corporate colors");

    console.log("PASS: Truly style fonts and colors look correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
