// tests/validate-areas-faqs-design.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php Areas and FAQs design...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for area checks and FAQ chevrons
    assert.ok(content.includes('fa-check-circle') || content.includes('fa-check'), "Missing check icon in areas list");
    assert.ok(content.includes('fa-chevron-down') || content.includes('fa-plus') || content.includes('faq-icon') || content.includes('chevron'), "Missing toggle indicator for FAQs");

    console.log("PASS: Areas & FAQs sections design looks correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
