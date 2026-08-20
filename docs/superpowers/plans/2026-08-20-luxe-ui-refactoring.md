# Luxe UI Refactoring Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refactor the visual styling of both the landing page (`index.php`) and the admin dashboard (`admin/index.php`) to match the "Emerald & Gold Luxe" theme, incorporating premium Google Fonts (Cormorant Garamond & Inter), Font Awesome icon vectors, smooth CSS transitions, zoom effects, and micro-interactions.

**Architecture:** Visual refactoring of server-side PHP templates. Google Fonts and Font Awesome CDN are imported into templates. Tailwind configurations are expanded locally inside HTML tags to define brand palette colors. SVG-based icons replace emojis. CSS transition animations are configured on cards and buttons.

**Tech Stack:** PHP, Tailwind CSS, Font Awesome CDN, Google Fonts.

## Global Constraints
*   **Fonts:** Titles must use `font-serif` (linked to `Cormorant Garamond`). Body copy must use `font-sans` (linked to `Inter`).
*   **Icons:** No emojis allowed in the client-facing UI. Emojis must be replaced with Font Awesome `<i class="fas ...">` icons.
*   **Colors:** Emerald green (`bg-emerald-950` / `text-emerald-800`), Warm gold (`text-amber-500` / `#d4af37`), Warm beige (`bg-theme-beige`).

---

### Task 1: Typography, Icon CDN, and Global Theme Configuration

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-fonts-icons.js`

**Interfaces:**
*   Consumes: None
*   Produces: Head tags in `index.php` loading Cormorant Garamond, Inter, and Font Awesome, and updated Tailwind theme settings.

- [ ] **Step 1: Write integration validation test**
    Create `tests/validate-fonts-icons.js` to ensure the CDN libraries and font imports are declared inside `index.php`.
    ```javascript
    // tests/validate-fonts-icons.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking CDN assets in index.php...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check Font Awesome & Google Fonts CDNs
        assert.ok(content.includes('font-awesome') || content.includes('all.min.css') || content.includes('all.css'), "Missing Font Awesome CDN link");
        assert.ok(content.includes('Cormorant+Garamond'), "Missing Cormorant Garamond Google font import");
        assert.ok(content.includes('Inter'), "Missing Inter Google font import");

        console.log("PASS: Typography & Icon CDNs are configured!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-fonts-icons.js`
    Expected: FAIL because `index.php` currently has `Playfair Display` and no Font Awesome.

- [ ] **Step 3: Modify index.php Head tags**
    Update head tags in `index.php` (lines 30-70 approximately) to load Font Awesome CDN, new Google fonts, and update the Tailwind theme configuration.
    ```php
    // In index.php head tags, replace the Google Fonts link and Tailwind config with:
    
    <!-- Google Fonts: Cormorant Garamond (Title) & Inter (Body) -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    ```
    And update tailwind extended colors inside script:
    ```javascript
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    serif: ['Cormorant Garamond', 'serif'],
                },
                colors: {
                    theme: {
                        50: '#f0fdf4',
                        100: '#dcfce7',
                        200: '#bbf7d0',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                        gold: '#d4af37',
                        beige: '#faf8f5',
                    }
                }
            }
        }
    }
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-fonts-icons.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-fonts-icons.js
    git commit -m "style: configure Cormorant Garamond, Inter, and Font Awesome CDN inside index.php"
    ```

---

### Task 2: Header & Hero Section Refactoring

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-hero-design.js`

**Interfaces:**
*   Consumes: None
*   Produces: Premium high-end layout for Header navigation and Hero banner in `index.php`.

- [ ] **Step 1: Write Hero banner validation test**
    Create `tests/validate-hero-design.js` to ensure the Hero container implements absolute positioning, dark emerald gradient overlay, and gold accent styling.
    ```javascript
    // tests/validate-hero-design.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php Hero layout styles...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for premium Tailwind classes
        assert.ok(content.includes('bg-emerald-950') || content.includes('bg-emerald-900') || content.includes('from-emerald-950'), "Hero missing emerald container color background");
        assert.ok(content.includes('from-amber-500') || content.includes('from-amber-400') || content.includes('text-amber-500') || content.includes('text-amber-400'), "Hero missing gold buttons/highlights");

        console.log("PASS: Hero layout classes are configured!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-hero-design.js`
    Expected: FAIL because `index.php` still uses `bg-theme-100` for the Hero banner.

- [ ] **Step 3: Modify index.php Header & Hero Sections**
    Update the Header and Hero section tags in `index.php` to match the luxe emerald theme.
    ```php
    // In index.php:
    // Update Header to have a premium emerald logo styling and a golden book button.
    // Update Hero Section to utilize a high-quality spa image background with a deep emerald gradient overlay.
    // Example:
    // <section id="hero" class="relative bg-emerald-950 text-white min-h-[80vh] flex items-center overflow-hidden">
    // ... complete updated layout code is specified in task implementation ...
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-hero-design.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-hero-design.js
    git commit -m "style: design premium emerald-gold Hero section with background overlay in index.php"
    ```

---

### Task 3: Benefits & Services Cards Refactoring

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-cards-design.js`

**Interfaces:**
*   Consumes: `services` array from MySQL
*   Produces: Beautiful grid layouts with Font Awesome vector icons and dynamic hover card interactions.

- [ ] **Step 1: Write cards validation test**
    Create `tests/validate-cards-design.js` to assert the removal of emojis and implementation of Font Awesome icons and zoom transitions.
    ```javascript
    // tests/validate-cards-design.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php card styles...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for Font Awesome icon references in why-us
        assert.ok(content.includes('fa-user-md') || content.includes('fa-user-nurse') || content.includes('fa-award'), "Missing premium certified therapist icon");
        assert.ok(content.includes('fa-seedling') || content.includes('fa-leaf'), "Missing premium organic oil icon");
        assert.ok(content.includes('fa-car-side') || content.includes('fa-car') || content.includes('fa-road'), "Missing premium transport icon");
        
        // Check transition classes in services
        assert.ok(content.includes('group-hover:scale-') || content.includes('hover:scale-'), "Missing hover scale transition on service images");
        assert.ok(content.includes('fa-whatsapp'), "Missing WhatsApp icon in booking button");

        console.log("PASS: Cards and icons configuration is valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-cards-design.js`
    Expected: FAIL because emojis are still used and scale transitions are missing.

- [ ] **Step 3: Refactor Why Choose Us & Massage Menu sections**
    Replace emojis with Font Awesome elements and update CSS layouts to include smooth shadows, scale transitions (`transition-all duration-500`), and a luxurious gold featured badge on featured services.
    ```php
    // In index.php:
    // Update the Why Choose Us grid elements to use Font Awesome.
    // Update the Services rendering loop to output a premium card layout.
    // ... complete updated layout code is specified in task implementation ...
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-cards-design.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-cards-design.js
    git commit -m "style: apply Font Awesome vectors and hover scale card micro-interactions in index.php"
    ```

---

### Task 4: Areas & FAQs Sections Refactoring

**Files:**
*   Modify: `index.php`
*   Create: `tests/validate-areas-faqs-design.js`

**Interfaces:**
*   Consumes: `areas` and `faqs` arrays from MySQL
*   Produces: Clean, styled region coverage checkmarks and smooth-transition FAQ toggles.

- [ ] **Step 1: Write sections validation test**
    Create `tests/validate-areas-faqs-design.js` to verify the usage of checkmark check circle icons and accordion rotating animations.
    ```javascript
    // tests/validate-areas-faqs-design.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php Areas and FAQs design...");
        const homePath = path.join(__dirname, '../index.php');
        const content = fs.readFileSync(homePath, 'utf8');

        // Check for area checks and FAQ chevrons
        assert.ok(content.includes('fa-check-circle') || content.includes('fa-check'), "Missing check icon in areas list");
        assert.ok(content.includes('fa-chevron-down') || content.includes('fa-plus') || content.includes('faq-icon'), "Missing toggle indicator for FAQs");

        console.log("PASS: Areas & FAQs sections design looks correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-areas-faqs-design.js`
    Expected: FAIL because check icons are currently standard tick characters (`✓`).

- [ ] **Step 3: Modify Areas & FAQs Sections**
    Update index.php to replace tick text with `<i class="fas fa-check-circle text-amber-500 mr-3"></i>`, wrap the Google Maps iframe inside a card container, and style the FAQ buttons to use Font Awesome chevron indicators that rotate smoothly.
    ```php
    // In index.php:
    // Update areas rendering checkmarks.
    // Update FAQ buttons to use Font Awesome chevrons.
    // ... complete updated layout code is specified in task implementation ...
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-areas-faqs-design.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.php tests/validate-areas-faqs-design.js
    git commit -m "style: polish Service Areas checklists and interactive FAQ accordion chevrons"
    ```

---

### Task 5: Admin Panel (admin/index.php) Visual Refactoring

**Files:**
*   Modify: `admin/index.php`
*   Create: `tests/validate-admin-luxe-design.js`

**Interfaces:**
*   Consumes: `config.php` and SQL data
*   Produces: Beautiful, professional admin dashboard layout using a dark emerald theme matching the landing page.

- [ ] **Step 1: Write admin panel validation test**
    Create `tests/validate-admin-luxe-design.js` to verify the new CSS classes, sidebar layout, and saving indicator.
    ```javascript
    // tests/validate-admin-luxe-design.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking admin dashboard theme...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        const content = fs.readFileSync(adminPath, 'utf8');

        // Check for dashboard classes
        assert.ok(content.includes('bg-emerald-950') || content.includes('bg-emerald-900') || content.includes('text-emerald-800'), "Missing admin emerald theme colors");
        assert.ok(content.includes('fas fa-') || content.includes('far fa-'), "Missing Font Awesome icons in admin sidebar");
        assert.ok(content.includes('saved=1'), "Missing query check for toast saved notifications");

        console.log("PASS: Admin dashboard visual update is correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-admin-luxe-design.js`
    Expected: FAIL because `admin/index.php` currently has standard stone/neutral gray buttons and does not include Font Awesome link.

- [ ] **Step 3: Modify admin/index.php UI elements**
    Import Font Awesome CSS in `admin/index.php` head tag, style the top header and tabs to use the emerald-gold theme, update input fields to focus on emerald green, and implement Font Awesome icons on sidebar navigation buttons.
    ```php
    // In admin/index.php:
    // Load Font Awesome in the head tag.
    // Update navigation sidebar to display icons alongside tab names.
    // Update styling for cards, inputs, and button structures.
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-admin-luxe-design.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.php tests/validate-admin-luxe-design.js
    git commit -m "style: refactor PHP admin dashboard with emerald theme and Font Awesome navigation"
    ```
