// tests/validate-homepage-images-customization.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking Homepage Images customization configuration...");
    const adminPath = path.join(__dirname, '../admin/index.php');
    const headerPath = path.join(__dirname, '../header.php');
    const indexPath = path.join(__dirname, '../index.php');
    const schemaPath = path.join(__dirname, '../schema.sql');

    const adminContent = fs.readFileSync(adminPath, 'utf8');
    const headerContent = fs.readFileSync(headerPath, 'utf8');
    const indexContent = fs.readFileSync(indexPath, 'utf8');
    const schemaContent = fs.readFileSync(schemaPath, 'utf8');

    // 1. Check header.php variables
    assert.ok(headerContent.includes('$heroImage ='), "Missing $heroImage definition in header.php");
    assert.ok(headerContent.includes('$aboutImage ='), "Missing $aboutImage definition in header.php");
    assert.ok(headerContent.includes('hero-massage.webp'), "Missing fallback to default hero image");
    assert.ok(headerContent.includes('about-massage.webp'), "Missing fallback to default about image");

    // 2. Check index.php rendering dynamic variables
    assert.ok(indexContent.includes('$heroImage'), "index.php should use dynamic $heroImage");
    assert.ok(indexContent.includes('$aboutImage'), "index.php should use dynamic $aboutImage");

    // 3. Check admin/index.php inputs and handlers
    assert.ok(adminContent.includes('name="hero_image"'), "Missing hero_image file input in admin");
    assert.ok(adminContent.includes('name="about_image"'), "Missing about_image file input in admin");
    assert.ok(adminContent.includes('remove_hero_image'), "Missing remove_hero_image reset control in admin");
    assert.ok(adminContent.includes('remove_about_image'), "Missing remove_about_image reset control in admin");
    assert.ok(adminContent.includes("['heroImage'"), "Missing heroImage setting update handler in admin");
    assert.ok(adminContent.includes("['aboutImage'"), "Missing aboutImage setting update handler in admin");

    // 4. Check schema.sql
    assert.ok(schemaContent.includes("'heroImage'"), "Missing heroImage in schema.sql");
    assert.ok(schemaContent.includes("'aboutImage'"), "Missing aboutImage in schema.sql");

    console.log("PASS: Homepage Images customization is verified!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
