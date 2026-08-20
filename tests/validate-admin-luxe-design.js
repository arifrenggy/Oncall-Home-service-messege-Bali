// tests/validate-admin-luxe-design.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking admin dashboard theme...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const content = fs.readFileSync(adminPath, 'utf8');

    // Check for dashboard classes
    assert.ok(content.includes('bg-emerald-950') || content.includes('bg-emerald-900') || content.includes('bg-emerald-800') || content.includes('text-emerald-800'), "Missing admin emerald theme colors");
    assert.ok(content.includes('fas fa-') || content.includes('far fa-') || content.includes('fab fa-') || content.includes('font-awesome'), "Missing Font Awesome icons in admin sidebar");
    assert.ok(content.includes('saved=1'), "Missing query check for toast saved notifications");

    console.log("PASS: Admin dashboard visual update is correct!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
