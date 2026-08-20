// tests/validate-admin-logo-upload.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Admin Logo upload configuration...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const indexPath = path.join(__dirname, '../index.php');

    const adminContent = fs.readFileSync(adminPath, 'utf8');
    const indexContent = fs.readFileSync(indexPath, 'utf8');

    // 1. Check index.php logo layout structure
    assert.ok(indexContent.includes('$brandLogo ='), "Missing $brandLogo definition in index.php");
    assert.ok(indexContent.includes('alt="Logo"') || indexContent.includes('img src='), "Missing brandLogo presentation check in index.php");

    // 2. Check admin/index.php file upload configuration
    assert.ok(adminContent.includes('name="brand_logo"'), "Missing brand_logo input element in admin dashboard");
    assert.ok(adminContent.includes('enctype="multipart/form-data"'), "Admin form must support file uploads (enctype)");

    console.log("PASS: Admin Logo upload configuration is verified!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
