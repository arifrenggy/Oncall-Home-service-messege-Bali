// tests/validate-admin-logo-upload.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Admin Logo upload configuration...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const headerPath = path.join(__dirname, '../header.php');

    const adminContent = fs.readFileSync(adminPath, 'utf8');
    const headerContent = fs.readFileSync(headerPath, 'utf8');

    // 1. Check header.php logo layout structure (logo renders site-wide in the shared header)
    assert.ok(headerContent.includes('$brandLogo ='), "Missing $brandLogo definition in header.php");
    assert.ok(headerContent.includes('alt="Logo"') || headerContent.includes('<img src='), "Missing brandLogo presentation check in header.php");

    // 2. Check admin/index.php file upload configuration
    assert.ok(adminContent.includes('name="brand_logo"'), "Missing brand_logo input element in admin dashboard");
    assert.ok(adminContent.includes('enctype="multipart/form-data"'), "Admin form must support file uploads (enctype)");

    console.log("PASS: Admin Logo upload configuration is verified!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
