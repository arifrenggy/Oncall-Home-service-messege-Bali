// tests/validate-cards-design.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking index.php card styles...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check for Font Awesome icon references in why-us
    assert.ok(content.includes('fa-user-md') || content.includes('fa-user-nurse') || content.includes('fa-award') || content.includes('fa-spa'), "Missing premium certified therapist icon");
    assert.ok(content.includes('fa-seedling') || content.includes('fa-leaf') || content.includes('fa-mortar-pestle'), "Missing premium organic oil icon");
    assert.ok(content.includes('fa-car-side') || content.includes('fa-car') || content.includes('fa-road') || content.includes('fa-map-marker-alt'), "Missing premium transport icon");
    
    // Check transition classes in services
    assert.ok(content.includes('group-hover:scale-') || content.includes('hover:scale-') || content.includes('duration-'), "Missing hover scale transition on service images");
    assert.ok(content.includes('fa-whatsapp'), "Missing WhatsApp icon in booking button");

    console.log("PASS: Cards and icons configuration is valid!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
