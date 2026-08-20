// tests/validate-cms.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Decap CMS files...");
    const adminHtmlPath = path.join(__dirname, '../admin/index.html');
    const configYmlPath = path.join(__dirname, '../admin/config.yml');

    assert.ok(fs.existsSync(adminHtmlPath), "Missing admin/index.html");
    assert.ok(fs.existsSync(configYmlPath), "Missing admin/config.yml");

    const configContent = fs.readFileSync(configYmlPath, 'utf8');
    assert.ok(configContent.includes('data/content.json'), "config.yml must link to data/content.json");
    assert.ok(configContent.includes('collections:'), "config.yml must define collections");

    console.log("PASS: CMS config is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
