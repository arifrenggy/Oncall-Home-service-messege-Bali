// tests/validate-sitemap-robots.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Sitemap and Robots configuration...");
    const sitemapPath = path.join(__dirname, '../sitemap.xml');
    const robotsPath = path.join(__dirname, '../robots.txt');

    assert.ok(fs.existsSync(sitemapPath), "Missing sitemap.xml in root");
    assert.ok(fs.existsSync(robotsPath), "Missing robots.txt in root");

    const sitemapContent = fs.readFileSync(sitemapPath, 'utf8');
    const robotsContent = fs.readFileSync(robotsPath, 'utf8');

    assert.ok(sitemapContent.includes('<loc>'), "sitemap.xml is missing <loc> tag");
    assert.ok(robotsContent.includes('Disallow: /admin/'), "robots.txt is missing admin restriction");

    console.log("PASS: Sitemap and Robots files are verified!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
