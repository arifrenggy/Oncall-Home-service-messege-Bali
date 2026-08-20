// tests/validate-hero-design.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php Hero layout styles...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for premium Tailwind classes
    assert.ok(content.includes('bg-emerald-950') || content.includes('bg-emerald-900') || content.includes('from-emerald-950'), "Hero missing emerald container color background");
    assert.ok(content.includes('from-amber-500') || content.includes('from-amber-400') || content.includes('text-amber-500') || content.includes('text-amber-400'), "Hero missing gold buttons/highlights");

    console.log("PASS: Hero layout classes are configured!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
