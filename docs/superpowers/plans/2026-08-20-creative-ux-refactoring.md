# Creative Editorial Layout Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refactor the homepage layout of `index.php` from a generic grid template into an imaginative, high-end editorial "Lookbook" layout. This incorporates centered hero banners with overlapping offset gallery walls, alternating full-width service catalog layouts using archway masking, overlapping text containers, and diagonal flow diagrams.

**Architecture:** Visual refactoring of PHP landing page. Custom CSS classes for masking (`rounded-t-full` for archways), negative margins (`-mt-12`, `-mr-8` etc.) for overlapping elements, and asymmetrical spacing are used.

**Tech Stack:** PHP, Tailwind CSS, Font Awesome.

---

### Task 1: Centered Hero & Offset Floating Gallery Wall

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-creative-hero.js`

**Interfaces:**
*   Consumes: None
*   Produces: Centered header, large serif glyphs, and a staggered three-image gallery layout in the Hero section.

- [ ] **Step 1: Write Hero layout verification test**
    Create `tests/validate-creative-hero.js` to ensure the new layout classes (centered typography and multiple offset images) exist.
    ```javascript
    // tests/validate-creative-hero.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking creative Hero layout in index.php...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for centered hero text and gallery containers
        assert.ok(content.includes('text-center') && content.includes('mx-auto'), "Hero header text is not centered");
        assert.ok(content.includes('rounded-t-full') || content.includes('rounded-full'), "Hero gallery is missing organic or archway masks");

        console.log("PASS: Creative Hero layout looks valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-creative-hero.js`
    Expected: FAIL

- [ ] **Step 3: Modify Hero Section in index.php**
    Replace the old Hero layout (grid with image on right) with a centered text wrapper and a staggered, overlapping 3-image gallery wall.
    ```php
    // Refactor the #hero section in index.php to use:
    // - Centered titles with a gold leaf separator.
    // - A grid of overlapping images featuring rounded-t-full (archway) styles and offset translations.
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-creative-hero.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-creative-hero.js
    git commit -m "style: implement centered typography and overlapping archway gallery wall in Hero section"
    ```

---

### Task 2: Diagonal Flow "Why Choose Us" Section

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-creative-why.js`

**Interfaces:**
*   Consumes: None
*   Produces: Staggered, diagonally-flowing columns for the why-us section instead of standard grid boxes.

- [ ] **Step 1: Write Why Choose Us layout verification test**
    Create `tests/validate-creative-why.js` to ensure the grid boxes are refactored into offset, staggered items.
    ```javascript
    // tests/validate-creative-why.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking creative Why Choose Us layout in index.php...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Verify staggered layout margins
        assert.ok(content.includes('translate-y-') || content.includes('md:translate-y-'), "Missing staggered offset translations in Why-Us section");

        console.log("PASS: Why-Us staggered layout looks valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-creative-why.js`
    Expected: FAIL

- [ ] **Step 3: Modify Why Choose Us Section in index.php**
    Refactor the `#why-us` container to use offset staggered positions (`md:translate-y-8`, `md:-translate-y-8`, etc.) and a beautiful diagonal flow.
    ```php
    // Replace standard grid inside #why-us with asymmetrical cards
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-creative-why.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-creative-why.js
    git commit -m "style: refactor why-us layout to use staggered asymmetrical columns"
    ```

---

### Task 3: Alternating Editorial "Lookbook" Massage Menu

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-creative-menu.js`

**Interfaces:**
*   Consumes: `services` from MySQL
*   Produces: Dynamic alternating lookbook items with archway masks and overlapping negative margins.

- [ ] **Step 1: Write lookbook layout verification test**
    Create `tests/validate-creative-menu.js` to assert alternating flex layouts and negative margins are present in the services list loop.
    ```javascript
    // tests/validate-creative-menu.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking lookbook layout in index.php...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for archway masks and negative margin overlaps
        assert.ok(content.includes('rounded-t-full') && content.includes('-mt-'), "Missing archway mask or negative margin overlaps in lookbook menu");
        assert.ok(content.includes('flex-row-reverse'), "Missing alternating flex direction for layout asymmetry");

        console.log("PASS: Alternating lookbook menu is valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-creative-menu.js`
    Expected: FAIL

- [ ] **Step 3: Modify Massage Menu Section in index.php**
    Completely refactor the `#services-list` loop to render alternating rows (`flex-col md:flex-row` and `md:flex-row-reverse`) for each service.
    ```php
    // In index.php services loop:
    // Use an index counter to alternate flex direction.
    // Set service image as an elegant archway (rounded-t-full h-[400px] w-full object-cover).
    // Set description container to overlap the image with negative margins.
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-creative-menu.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-creative-menu.js
    git commit -m "style: implement alternating lookbook layout with archway masks and overlapping cards in services list"
    ```

---

### Task 4: Staggered Service Areas & Decorative Overlays

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-creative-areas.js`

**Interfaces:**
*   Consumes: None
*   Produces: Staggered, asymmetrical grids for areas and maps with decorative background serif glyphs.

- [ ] **Step 1: Write Areas layout verification test**
    Create `tests/validate-creative-areas.js` to ensure the layout contains floating/overlapping grids and background decorations.
    ```javascript
    // tests/validate-creative-areas.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Areas layout in index.php...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Verify overlay or relative floating elements in areas
        assert.ok(content.includes('relative') && content.includes('z-10'), "Missing relative stacking in areas section");

        console.log("PASS: Areas staggered layout looks valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-creative-areas.js`
    Expected: FAIL

- [ ] **Step 3: Refactor Areas Section in index.php**
    Modify the areas layout to incorporate overlapping borders, background text elements (like "BALI" or "ZEN" in low-opacity headings), and floating panels.
    ```php
    // Update #areas section in index.php
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-creative-areas.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-creative-areas.js
    git commit -m "style: enhance Areas section with relative overlapping frames and maps"
    ```
