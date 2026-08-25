# Design Spec: Dynamic Operating Hours & Footer Logo Fix

**Date:** 2026-08-25  
**Status:** Approved  
**Author:** Antigravity  

---

## 1. Goal
Make the operating hours text dynamic in the landing page banner, linking it directly to the admin settings value. Fix the footer logo rendering by removing color-inverting filters, allowing all logo formats (transparent PNGs, JPEGs, SVGs) to display correctly in their original colors.

---

## 2. Proposed Changes

### A. Main Homepage (`index.php`)

#### 1. Operating Hours Banner
- **Location:** Line 479 of `index.php` (inside the `Operating Hours Banner` section).
- **Change:** Replace the hardcoded hours string with PHP output echoing the `$operatingHours` variable.
- **Implementation:**
  ```php
  <p class="text-2xl sm:text-3xl font-bold text-[#9c654d]">Everyday (<?php echo htmlspecialchars($operatingHours); ?>)</p>
  ```

#### 2. Footer Logo Rendering
- **Location:** Line 514 of `index.php` (inside the `Footer` section).
- **Change:** Remove `brightness-0` and `invert` Tailwind utility classes.
- **Implementation:**
  ```php
  <img src="<?php echo htmlspecialchars($brandLogo); ?>" width="40" height="40" alt="Logo" class="h-10 w-auto object-contain">
  ```

---

## 3. Verification & Testing
1. **Visual Verification:** Check the homepage layout locally.
2. **PHP Syntax Verification:** Run `php -l index.php` to ensure no syntax errors were introduced.
3. **Admin Panel Compatibility:** Ensure no database schemas are altered; this is a presentation-layer change only.
