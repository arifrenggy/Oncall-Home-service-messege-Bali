# Truly Home Massage Visual Style Refactoring Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refactor the landing page of `index.php` and the admin control panel `admin/index.php` to replicate the clean, high-end visual style of the reference website `trulyhomemassage.com/bali/`.

**Design Palette & Typography (Truly Style):**
*   **Primary Color:** Navy Blue (`#192a3d`) for headers, main text, and dark sections.
*   **Accent Color 1:** Royal/Clean Blue (`#2872fa`) for highlights and active navigation.
*   **Accent Color 2 (Terracotta/Warm Copper):** `#AE7D64` for action tags and primary CTA buttons.
*   **Backgrounds:** Clean white (`#ffffff`) and soft bluish-gray (`#f2f5f7`).
*   **Typography:** Google Fonts: **Poppins** (for headings) and **Inter** (for body text).

---

### Task 1: Head tags, Fonts, and Color Configurations

**Files:**
*   Modify: `index.php`, `admin/index.php`
*   Create: `tests/validate-truly-fonts-colors.js`

- [ ] **Step 1: Write Truly style verification test**
    Create `tests/validate-truly-fonts-colors.js` to ensure the new font family and hex colors are configured.
    ```javascript
    // tests/validate-truly-fonts-colors.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Truly style font and color settings...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for Poppins font and hex code variables
        assert.ok(content.includes('Poppins') && content.includes('Inter'), "Missing Poppins or Inter fonts");
        assert.ok(content.includes('#192a3d') || content.includes('#AE7D64') || content.includes('AE7D64'), "Missing Truly corporate colors");

        console.log("PASS: Truly style fonts and colors look correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-truly-fonts-colors.js`
    Expected: FAIL

- [ ] **Step 3: Update head configurations in index.php and admin/index.php**
    Integrate Google Fonts (Poppins & Inter) and set up custom Tailwind configuration inline or styles to map the Truly colors (`#192a3d`, `#2872fa`, `#AE7D64`, `#f2f5f7`).

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-truly-fonts-colors.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php admin/index.php tests/validate-truly-fonts-colors.js
    git commit -m "style: configure Poppins, Inter and Truly Home Massage corporate color scheme"
    ```

---

### Task 2: Header Navigation & Truly Style Hero Section

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-truly-hero.js`

- [ ] **Step 1: Write Hero layout verification test**
    Create `tests/validate-truly-hero.js` to assert the header links and layout match Truly style guidelines.
    ```javascript
    // tests/validate-truly-hero.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Truly Hero section...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for key Truly sections and bullet points
        assert.ok(content.includes('Gratis Biaya Transportasi') || content.includes('Free Transport'), "Missing transport cost highlight");
        assert.ok(content.includes('Terapis Ramah'), "Missing friendly therapist tagline");

        console.log("PASS: Truly Hero section is valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-truly-hero.js`
    Expected: FAIL

- [ ] **Step 3: Modify Header and Hero in index.php**
    *   **Header:** Clean navigation layout with "Tentang Kami", "Harga", "Kenapa Kami", "Hubungi" links. Transparent layout that merges with Hero.
    *   **Hero:** Left side features the bold title, checklist highlights with checkmarks, CTA buttons (terracotta primary and white secondary). Right side contains the professional therapist/massage image.

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-truly-hero.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-truly-hero.js
    git commit -m "style: implement Truly style header navigation and checklist-based Hero layout"
    ```

---

### Task 3: About Us & Pricing Treatments Layout

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-truly-treatments.js`

- [ ] **Step 1: Write About and Treatments verification test**
    Create `tests/validate-truly-treatments.js` to ensure the clean structure and pricing blocks exist.
    ```javascript
    // tests/validate-truly-treatments.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Truly Treatments layout...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        assert.ok(content.includes('Menit ='), "Missing price list time duration separators");
        assert.ok(content.includes('Tentang Kami') || content.includes('About Us'), "Missing About Us layout heading");

        console.log("PASS: About and Treatments layout looks correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-truly-treatments.js`
    Expected: FAIL

- [ ] **Step 3: Modify About and Treatments Section in index.php**
    *   **About Us:** Implement side-by-side layout with a terracotta highlighted "Gratis Biaya Transportasi area Bali" alert banner.
    *   **Treatments list:** Restructure the loop into clean alternating sections. Image on one side (rectangular with rounded edges), and title, benefits description, dynamic pricing options list (e.g. **90 Menit = 160K**), and "Pesan Sekarang" WA CTA buttons on the other.

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-truly-treatments.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-truly-treatments.js
    git commit -m "style: refactor About Us and Treatments menu to match Truly clean alternating lists"
    ```

---

### Task 4: Why Choose Us, Jam Kerja, Areas, FAQs & Footer

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-truly-bottom.js`

- [ ] **Step 1: Write bottom elements verification test**
    Create `tests/validate-truly-bottom.js` to assert the updated footer, working hours banner, and clean layout cards exist.
    ```javascript
    // tests/validate-truly-bottom.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Truly bottom page components...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        assert.ok(content.includes('JAM KERJA') || content.includes('Working Hours'), "Missing Working Hours banner");
        assert.ok(content.includes('bg-[#192a3d]') || content.includes('bg-slate-900') || content.includes('bg-emerald-950') === false, "Missing dark navy footer or incorrect emerald styling");

        console.log("PASS: Bottom components look correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-truly-bottom.js`
    Expected: FAIL

- [ ] **Step 3: Refactor bottom sections in index.php**
    *   **Why Choose Us:** Staggered boxes replaced with a clean grid of cards, using blue and terracotta accent circles for icons.
    *   **Working Hours (Jam Kerja):** Centered premium navy section with large terracotta highlights.
    *   **Areas & FAQ:** Clean layout, no overlapping masks, crisp map container, and interactive FAQ with navy blue highlights.
    *   **Footer:** Navy background (`#192a3d`) with logo, checkmarks, social icons, and copyright.

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-truly-bottom.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-truly-bottom.js
    git commit -m "style: refactor Why Choose Us, Jam Kerja banner, areas, FAQs, and navy footer to match Truly visual guidelines"
    ```
