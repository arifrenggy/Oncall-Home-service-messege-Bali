// tests/validate-truly-hero.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly Hero section...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for key Truly sections and bullet points
    assert.ok(content.includes('Gratis Biaya Transportasi') || content.includes('Free Transport'), "Missing transport cost highlight");
    assert.ok(content.includes('Terapis Ramah'), "Missing friendly therapist tagline");

    console.log("PASS: Truly Hero section is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
