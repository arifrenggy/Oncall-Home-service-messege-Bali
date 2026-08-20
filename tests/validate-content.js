// tests/validate-content.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking if data/content.json exists...");
    const dataPath = path.join(__dirname, '../data/content.json');
    
    assert.ok(fs.existsSync(dataPath), "File data/content.json does not exist");
    
    const content = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
    
    // Assert required structure
    assert.ok(content.general, "Missing 'general' settings");
    assert.ok(content.general.brandName, "Missing brandName");
    assert.ok(content.general.whatsapp, "Missing whatsapp number");
    assert.ok(content.services && Array.isArray(content.services), "Missing or invalid 'services' array");
    assert.ok(content.faqs && Array.isArray(content.faqs), "Missing or invalid 'faqs' array");
    assert.ok(content.areas && Array.isArray(content.areas), "Missing or invalid 'areas' array");
    
    console.log("PASS: data/content.json has correct structure!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
