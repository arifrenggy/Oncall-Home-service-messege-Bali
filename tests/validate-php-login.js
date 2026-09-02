// tests/validate-php-login.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking admin/index.php login mechanics...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    assert.ok(fs.existsSync(adminPath), "File admin/index.php does not exist");

    const content = fs.readFileSync(adminPath, 'utf8');

    // Assert session check is present
    assert.ok(content.includes('session_start('), "Missing session_start() for login management");
    assert.ok(content.includes('password_verify(') || content.includes('password_hash('), "Missing secure password hashing verification");
    assert.ok(content.includes('post') || content.includes('POST'), "Must handle POST requests for login submission");

    // Brute-force protection & session fixation defense
    assert.ok(content.includes('login_attempts'), "Missing DB-backed rate limiting (login_attempts table)");
    assert.ok(content.includes('session_regenerate_id'), "Missing session_regenerate_id after successful login");
    assert.ok(content.includes('password_verify('), "Missing password_verify for constant-time hash check");

    console.log("PASS: PHP admin login scaffold looks valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
