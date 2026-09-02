// tests/validate-seo-optimization.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking SEO Optimization configuration...");
    // SEO head markup (title, meta description, JSON-LD) lives in the shared header.php
    const headerPath = path.join(__dirname, '../header.php');
    const content = fs.readFileSync(headerPath, 'utf8');

    // 1. Check title and description (dynamic, with SEO-optimized fallbacks)
    assert.ok(content.includes('Best Home Service Massage Bali | On-Call Spa'), "Missing optimized SEO title tag fallback");
    assert.ok(content.includes('Looking for the best home service massage in Bali?'), "Missing optimized SEO description tag");
    assert.ok(content.includes('$pageTitle'), "Missing dynamic per-page title binding");

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
