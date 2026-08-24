# Design Spec: Performance & Accessibility Optimization

## 1. Introduction & Objectives
The goal of this optimization is to improve the Google PageSpeed Insights metrics for the public-facing landing page of **Oncall Home Service Massage Bali**. 
- **Performance:** Increase from ~60% to **90%+**
- **Accessibility:** Increase from ~75% to **95%+**
- **SEO & Best Practices:** Maintain the existing **100%** scores.

We will preserve all existing functionality, especially the admin dashboard's custom image upload features.

---

## 2. Technical Design

### Section A: Tailwind CSS Static Compilation
Currently, the runtime compiler `<script src="https://cdn.tailwindcss.com"></script>` downloads a large engine and compiles utility classes on the fly in the browser. This blocks rendering and increases CPU time.

**Solution:**
1. Initialize npm in the root directory if not present.
2. Install `tailwindcss` (v3 compatibility) as a devDependency.
3. Create `assets/css/input.css` containing Tailwind directives:
   ```css
   @tailwind base;
   @tailwind components;
   @tailwind utilities;
   ```
4. Create `tailwind.config.js` replicating the custom configurations from `index.php` and `admin/index.php` (such as fonts and the custom terracotta/beige/emerald color palettes).
5. Compile and minify the CSS into `assets/css/tailwind.min.css`.
6. Replace the CDN script tag with a `<link rel="stylesheet" href="assets/css/tailwind.min.css">` tag in both `index.php` and `admin/index.php`.

---

### Section B: Image Optimization & CLS Elimination
External Unsplash images are slow to load and lack dimension properties, causing Cumulative Layout Shift (CLS).

**Solution:**
1. Download the default Unsplash images, convert them to highly optimized `.webp` format, and save them in the local `assets/images/` directory:
   - `assets/images/hero-massage.webp` (Default Hero & Balinese Traditional Massage)
   - `assets/images/about-massage.webp` (Default About & Deep Tissue Massage)
   - `assets/images/service-reflexology.webp` (Default Foot Reflexology)
2. Define explicit `width` and `height` attributes on all `<img>` tags in `index.php` to prevent layout shift.
3. Speed up Largest Contentful Paint (LCP) by preloading the hero image in the `<head>` block:
   ```html
   <link rel="preload" as="image" href="assets/images/hero-massage.webp" fetchpriority="high">
   ```
4. Apply `loading="lazy"` on all below-the-fold images (About Section and Treatments Menu).
5. Update default SQL seed paths in `schema.sql` (if required for new installations) to point to the local paths instead of Unsplash.

---

### Section C: Accessibility Improvements (WCAG AA)
1. **Color Contrast:** The primary terracotta color `#AE7D64` fails the WCAG AA minimum contrast ratio of 4.5:1 for normal text (it has ~3.6:1). We will adjust the color slightly to a darker shade `#9c654d` (R:156, G:101, B:77) which yields a contrast ratio of **4.83:1** on white. This color will be updated in the Tailwind config and styling rules.
2. **Form Label Association:** Connect form labels with their respective select dropdown inputs by adding `for` attributes:
   ```html
   <label for="select-<?php echo $service['id']; ?>" class="...">Select Duration to Book</label>
   ```
3. **Iframe Title:** Add `title="Google Maps showing Bali service coverage area"` to the Google Maps `<iframe>`.
4. **Decorative Icons:** Add `aria-hidden="true"` to all Font Awesome `<i>` tags so screen readers ignore them.

---

### Section D: Caching & Compression (.htaccess)
Create a `.htaccess` file in the root directory to enable Apache compression and caching rules:
- **Gzip Compression (`mod_deflate`):** Compress HTML, CSS, JS, SVG, XML, and JSON files during transmission.
- **Expires Headers (`mod_expires`):** Cache CSS, JS, images, icons, and fonts for up to 1 year.

---

## 3. Admin Dashboard Compatibility Safeguards
To ensure custom image uploads are unaffected:
- The custom upload logic in `admin/index.php` moves files into `__DIR__ . '/../assets/images'`. This directory structure will remain identical.
- Dynamic DB-driven image paths (such as `assets/images/logo_...` or custom paths) will render correctly since the CSS class styles (`w-full h-full object-cover`) remain unchanged.
- We will NOT touch the file upload parsing code, form structures, or database query parameters in `admin/index.php`.

---

## 4. Verification Plan
1. **Build Verification:** Verify that Tailwind compiles without errors and generated `tailwind.min.css` contains all styles.
2. **Visual Verification:** Check the local site layout, colors, and font rendering.
3. **Admin Verification:** Test uploading a custom logo and custom service images via the admin dashboard, verifying they save and display on the homepage correctly.
4. **Performance & Accessibility Audits:** Run Lighthouse checks to verify target scores.
