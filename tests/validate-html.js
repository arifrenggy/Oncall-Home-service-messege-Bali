// tests/validate-html.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.html structure...");
    const htmlPath = path.join(__dirname, '../index.html');
    assert.ok(fs.existsSync(htmlPath), "Missing index.html");

    const content = fs.readFileSync(htmlPath, 'utf8');

    // Check required SEO tags & Scripts
    assert.ok(content.includes('viewport'), "Missing viewport meta tag for mobile responsiveness");
    assert.ok(content.includes('description'), "Missing meta description tag");
    assert.ok(content.includes('tailwindcss'), "Missing Tailwind CSS CDN load");
    assert.ok(content.includes('netlify-identity-widget'), "Missing Netlify Identity script in index.html footer");
    assert.ok(content.includes('js/app.js'), "Missing js/app.js reference");

    // Check required section placeholders
    assert.ok(content.includes('id="hero"'), "Missing hero section container");
    assert.ok(content.includes('id="why-us"'), "Missing why-us section container");
    assert.ok(content.includes('id="services"'), "Missing services list container");
    assert.ok(content.includes('id="areas"'), "Missing areas section container");
    assert.ok(content.includes('id="faqs"'), "Missing FAQ section container");
    assert.ok(content.includes('id="map"'), "Missing map section container");

    console.log("PASS: HTML template scaffold looks complete!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
