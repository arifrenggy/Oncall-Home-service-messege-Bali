// tests/validate-fonts-icons.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking CDN assets in index.php...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check Font Awesome & Google Fonts CDNs
    assert.ok(content.includes('font-awesome') || content.includes('all.min.css') || content.includes('all.css'), "Missing Font Awesome CDN link");
    assert.ok(content.includes('Cormorant+Garamond'), "Missing Cormorant Garamond Google font import");
    assert.ok(content.includes('Inter'), "Missing Inter Google font import");

    console.log("PASS: Typography & Icon CDNs are configured!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
