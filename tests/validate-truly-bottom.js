// tests/validate-truly-bottom.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Truly bottom page components...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    assert.ok(content.includes('JAM KERJA') || content.includes('Working Hours') || content.includes('operatingHours'), "Missing Working Hours layout");
    assert.ok(content.includes('bg-slate-900') || content.includes('bg-emerald-950') || content.includes('bg-[#192a3d]') || content.includes('bg-emerald-900'), "Missing dark navy footer styling");

    console.log("PASS: Bottom components look correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
