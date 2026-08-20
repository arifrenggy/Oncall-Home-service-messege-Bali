// tests/validate-seo-optimization.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking SEO Optimization configuration...");
    const indexPath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(indexPath, 'utf8');

    // 1. Check title and description
    assert.ok(content.includes('Best Home Service Massage Bali | 24/7 On-Call Spa'), "Missing optimized SEO title tag");
    assert.ok(content.includes('Looking for the best home service massage in Bali?'), "Missing optimized SEO description tag");

    // 2. Check JSON-LD local schema structure
    assert.ok(content.includes('"@type": "HealthAndBeautyBusiness"'), "Missing HealthAndBeautyBusiness Structured Data Schema");
    assert.ok(content.includes('"name": "<?php echo htmlspecialchars($brandName); ?>"'), "Missing dynamic brand name inside schema binding");
    assert.ok(content.includes('"areaServed"'), "Missing administrative areaServed coverage within JSON-LD");

    console.log("PASS: SEO Optimization and Structured Data are verified!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
