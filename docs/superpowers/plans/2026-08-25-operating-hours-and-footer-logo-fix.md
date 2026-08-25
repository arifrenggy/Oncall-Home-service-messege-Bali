# Dynamic Operating Hours & Footer Logo Fix Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the homepage operating hours banner show the hours configured in the admin dashboard, and fix the footer logo so it displays in its original colors.

**Architecture:** Update `index.php` presentation layer. Operating hours banner displays dynamic PHP output. Footer logo image tags display original colored/unfiltered image assets.

**Tech Stack:** PHP, Tailwind CSS.

## Global Constraints
- **Language:** All client-facing elements remain in English.
- **TDD / Verification:** Ensure modifications do not break PHP syntax and adhere to standard HTML/CSS patterns.

---

### Task 1: Make Operating Hours Banner Dynamic

**Files:**
- Modify: `index.php`

**Interfaces:**
- Consumes: Database-fetched `$operatingHours` variable.
- Produces: Dynamic operating hours displayed in the banner.

- [ ] **Step 1: Verify the file's current state**
    Run: `grep -n "OPERATING HOURS" index.php`
    Expected: Shows lines around line 478-479 containing `<h2 ...>OPERATING HOURS</h2>` and `<p ...>Everyday (08:00 AM - 11:00 PM WITA)</p>`.

- [ ] **Step 2: Replace hardcoded operating hours with PHP dynamic content**
    Edit `index.php` around line 479 to display the variable `$operatingHours`:
    ```php
    <p class="text-2xl sm:text-3xl font-bold text-[#9c654d]">Everyday (<?php echo htmlspecialchars($operatingHours); ?>)</p>
    ```

- [ ] **Step 3: Run PHP syntax validation check**
    Run: `php -l index.php`
    Expected: `No syntax errors detected in index.php`

- [ ] **Step 4: Verify update in index.php**
    Run: `grep -n "Everyday" index.php`
    Expected: Displays `<p class="text-2xl sm:text-3xl font-bold text-[#9c654d]">Everyday (<?php echo htmlspecialchars($operatingHours); ?>)</p>` on line 479.

- [ ] **Step 5: Commit**
    ```bash
    git add index.php
    git commit -m "feat: make operating hours banner dynamic"
    ```

---

### Task 2: Fix Footer Logo Rendering

**Files:**
- Modify: `index.php`

**Interfaces:**
- Consumes: `$brandLogo` variable from database.
- Produces: Correct footer logo presentation in original colors.

- [ ] **Step 1: Check existing footer logo styling**
    Run: `grep -n -C 2 "brightness-0 invert" index.php`
    Expected: Output shows line 514: `<img src="<?php echo htmlspecialchars($brandLogo); ?>" width="40" height="40" alt="Logo" class="h-10 w-auto object-contain brightness-0 invert">`

- [ ] **Step 2: Modify image element classes to remove filters**
    Edit `index.php` around line 514:
    ```php
    <img src="<?php echo htmlspecialchars($brandLogo); ?>" width="40" height="40" alt="Logo" class="h-10 w-auto object-contain">
    ```

- [ ] **Step 3: Run PHP syntax validation check**
    Run: `php -l index.php`
    Expected: `No syntax errors detected in index.php`

- [ ] **Step 4: Verify update in index.php**
    Run: `grep -n "brandLogo" index.php`
    Expected: The line in footer no longer contains `brightness-0` or `invert`.

- [ ] **Step 5: Commit**
    ```bash
    git add index.php
    git commit -m "fix: remove brightness and invert filters from footer logo to display in original colors"
    ```
