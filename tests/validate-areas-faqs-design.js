// tests/validate-areas-faqs-design.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Areas and FAQs design...");
    // Areas list renders on the homepage (index.php); the FAQ accordion lives on services.php
    const homePath = path.join(__dirname, '../index.php');
    const servicesPath = path.join(__dirname, '../services.php');
    const footerPath = path.join(__dirname, '../footer.php');
    const content = fs.readFileSync(homePath, 'utf8');
    const servicesContent = fs.readFileSync(servicesPath, 'utf8');
    const footerContent = fs.readFileSync(footerPath, 'utf8');

    // Check for area check icons on the homepage
    assert.ok(content.includes('fa-check-circle') || content.includes('fa-check'), "Missing check icon in areas list");

    // Check for FAQ toggle indicator (accordion) on services.php
    assert.ok(servicesContent.includes('fa-chevron-down') || servicesContent.includes('faq-icon'), "Missing toggle indicator for FAQs");
    // ...and the shared toggle behaviour in footer.php
    assert.ok(footerContent.includes('toggleFaq'), "Missing toggleFaq handler in footer.php");

    console.log("PASS: Areas & FAQs sections design looks correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
