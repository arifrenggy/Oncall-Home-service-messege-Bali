// tests/validate-english-ui.js
const fs = require('fs');
const path = require('path');
const assert = require('assert');

try {
    console.log("Checking that UI language is default English...");
    const homePath = path.join(__dirname, '../index.php');
    const content = fs.readFileSync(homePath, 'utf8');

    // Check that Indonesian UI terms are translated to English
    assert.ok(!content.includes('Tentang Kami'), "Found Indonesian 'Tentang Kami' in UI");
    assert.ok(!content.includes('Pesan Sekarang'), "Found Indonesian 'Pesan Sekarang' in UI");
    assert.ok(!content.includes('Jam Operasional') && !content.includes('JAM OPERASIONAL'), "Found Indonesian 'Jam Operasional' in UI");
    assert.ok(!content.includes('Wilayah Layanan Kami'), "Found Indonesian 'Wilayah Layanan Kami' in UI");

    // Check for correct English translations
    assert.ok(content.includes('About Us'), "Missing English 'About Us' in UI");
    assert.ok(content.includes('Book Now'), "Missing English 'Book Now' in UI");
    assert.ok(content.includes('OPERATING HOURS'), "Missing English 'OPERATING HOURS' in UI");
    assert.ok(content.includes('Service Area Coverage'), "Missing English 'Service Area Coverage' in UI");

    console.log("PASS: UI default language is English!");
    process.exit(0);
} catch (error) {
    console.error("FAIL:", error.message);
    process.exit(1);
}
