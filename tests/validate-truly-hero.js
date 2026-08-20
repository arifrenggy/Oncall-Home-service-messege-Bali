// tests/validate-truly-hero.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly Hero section...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for key Truly sections and bullet points in English
    assert.ok(content.includes('Free Transportation') || content.includes('Free transport'), "Missing transport cost highlight");
    assert.ok(content.includes('Friendly, Certified'), "Missing friendly therapist tagline");

    console.log("PASS: Truly Hero section is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
