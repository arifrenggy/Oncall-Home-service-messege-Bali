# Performance & Accessibility Optimization Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Improve Google PageSpeed Insights Performance score to 90%+ and Accessibility score to 95%+ by replacing the runtime Tailwind CSS CDN with compiled CSS, optimizing and caching images locally, adjusting color contrast, and configuring Apache gzip compression and caching via `.htaccess`.

**Architecture:** We compile Tailwind CSS locally using Tailwind CLI, saving a minified `tailwind.min.css` in `assets/css/`. Default Unsplash images are downloaded and stored locally as `.webp` files in `assets/images/`, with fallbacks implemented in PHP. Accessibility tags, contrast adjustments, and a `.htaccess` configuration are added to improve Lighthouse scores.

**Tech Stack:** PHP, Tailwind CSS v3 (JIT CLI compiler), Apache, Git.

## Global Constraints
- Do not modify or break the admin panel custom image and logo upload functions.
- Maintain existing database queries and schemas unchanged.
- Ensure the brand logo and service images fall back gracefully if they are still referencing remote URLs.
- Do not remove the Poppins or Inter font imports.

---

### Task 1: Setup Tailwind CSS Static Compilation Environment

**Files:**
- Create: `package.json`
- Create: `tailwind.config.js`
- Create: `assets/css/input.css`

**Interfaces:**
- Produces: `assets/css/tailwind.min.css` (static compiled stylesheet for Task 2)

- [ ] **Step 1: Create package.json**
  Write package configuration with tailwindcss dependency and build script.
  Create `/home/arifrenggy00/Oncall-Home-service-messege-Bali/package.json`:
  ```json
  {
    "name": "oncall-massage-bali",
    "version": "1.0.0",
    "description": "Oncall Home Service Massage Bali",
    "scripts": {
      "build:css": "tailwindcss -i ./assets/css/input.css -o ./assets/css/tailwind.min.css --minify"
    },
    "devDependencies": {
      "tailwindcss": "^3.4.1"
    }
  }
  ```

- [ ] **Step 2: Create tailwind.config.js**
  Write the Tailwind config file matching the theme and font configuration of the application, mapping the theme gold and primary colors to the new accessible color `#9c654d`.
  Create `/home/arifrenggy00/Oncall-Home-service-messege-Bali/tailwind.config.js`:
  ```javascript
  /** @type {import('tailwindcss').Config} */
  module.exports = {
    content: [
      "./index.php",
      "./admin/index.php",
      "./assets/**/*.js"
    ],
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
          serif: ['Poppins', 'sans-serif'],
        },
        colors: {
          theme: {
            50: '#f2f5f7',
            100: '#e5eaf0',
            200: '#cdd7e3',
            300: '#a3b7d1',
            400: '#7392bc',
            500: '#4e6fa0',
            600: '#3c5a87',
            700: '#324a6f',
            800: '#2c3e5a',
            900: '#192a3d',
            gold: '#9c654d', // Accessible color (originally #AE7D64)
            beige: '#ffffff',
          },
          emerald: {
            50: '#f2f5f7',
            100: '#e5eaf0',
            200: '#cdd7e3',
            300: '#a3b7d1',
            400: '#2872fa',
            500: '#2872fa',
            600: '#2872fa',
            700: '#1d4ed8',
            800: '#9c654d', // Accessible color (originally #AE7D64)
            900: '#192a3d',
            950: '#192a3d',
          },
          amber: {
            50: '#f7f4f2',
            100: '#f7f4f2',
            200: '#ebdcd3',
            300: '#d7b9a7',
            400: '#9c654d', // Accessible color (originally #AE7D64)
            500: '#9c654d', // Accessible color (originally #AE7D64)
            600: '#9c654d', // Accessible color (originally #AE7D64)
            700: '#91624a',
            800: '#734e3a',
            900: '#5a3d2e',
          }
        }
      },
    },
    plugins: [],
  }
  ```

- [ ] **Step 3: Create input.css**
  Create the entrypoint CSS file `/home/arifrenggy00/Oncall-Home-service-messege-Bali/assets/css/input.css`:
  ```css
  @tailwind base;
  @tailwind components;
  @tailwind utilities;
  ```

- [ ] **Step 4: Install dependencies and compile CSS**
  Run commands to install packages and compile the stylesheet.
  Run in terminal:
  ```bash
  npm install
  npm run build:css
  ```
  Expected output: Tailwind CSS files are successfully compiled, generating `/home/arifrenggy00/Oncall-Home-service-messege-Bali/assets/css/tailwind.min.css` with a size of ~10KB-100KB depending on utilities used.

- [ ] **Step 5: Verify CSS generation and commit**
  Verify file exists and commit.
  Run in terminal:
  ```bash
  ls -lh assets/css/tailwind.min.css
  git add package.json package-lock.json tailwind.config.js assets/css/input.css assets/css/tailwind.min.css
  git commit -m "build: setup tailwind static compilation"
  ```

---

### Task 2: Integrate Static CSS and Accessibility Adjustments in index.php and admin/index.php

**Files:**
- Modify: `index.php`
- Modify: `admin/index.php`

**Interfaces:**
- Consumes: `assets/css/tailwind.min.css` (from Task 1)

- [ ] **Step 1: Update index.php stylesheet reference and colors**
  Modify `/home/arifrenggy00/Oncall-Home-service-messege-Bali/index.php`:
  - Replace `<script src="https://cdn.tailwindcss.com"></script>` with `<link rel="stylesheet" href="assets/css/tailwind.min.css">`
  - Replace all occurrences of `#AE7D64` with `#9c654d`.
  - Replace all occurrences of `#91624a` with `#7d4d38`.
  - Also ensure that `<style>` block (around line 180) containing custom overrides for font-family is retained.

- [ ] **Step 2: Apply Accessibility adjustments to index.php**
  Modify `/home/arifrenggy00/Oncall-Home-service-messege-Bali/index.php`:
  - Associate select label with dropdown by adding `for` attribute:
    Find: `<label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Select Duration to Book</label>`
    Replace with: `<label for="select-<?php echo $service['id']; ?>" class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Select Duration to Book</label>` (Note: Changed color to text-slate-500 for better contrast on white background).
  - Add title to Google Maps iframe:
    Find: `<iframe src="https://www.google.com/maps/embed...`
    Add attribute: `title="Google Maps showing service coverage area in Bali"`
  - Add `aria-hidden="true"` to all Font Awesome icons. Loop through the file and add the attribute to all `<i class="...">` tags, e.g. `<i class="fas fa-spa" aria-hidden="true"></i>`.

- [ ] **Step 3: Update admin/index.php stylesheet reference and colors**
  Modify `/home/arifrenggy00/Oncall-Home-service-messege-Bali/admin/index.php`:
  - Replace `<script src="https://cdn.tailwindcss.com"></script>` (lines 214 and 335) with `<link rel="stylesheet" href="../assets/css/tailwind.min.css">`. Note the `../` because admin is in a subdirectory.
  - Replace all occurrences of `#AE7D64` with `#9c654d`.
  - Replace all occurrences of `#91624a` with `#7d4d38`.

- [ ] **Step 4: Verify syntax and compile CSS again**
  Recompile CSS to process newly added classes.
  Run:
  ```bash
  npm run build:css
  ```
  Check that the homepage PHP code runs without syntax errors.

- [ ] **Step 5: Commit changes**
  Run:
  ```bash
  git add index.php admin/index.php assets/css/tailwind.min.css
  git commit -m "style/accessibility: integrate compiled css and fix wcag contrast & labels"
  ```

---

### Task 3: Localize and Optimize Images

**Files:**
- Create: `assets/images/hero-massage.webp`
- Create: `assets/images/about-massage.webp`
- Create: `assets/images/service-reflexology.webp`
- Modify: `index.php`

- [ ] **Step 1: Download default assets as optimized WebP**
  Use `curl` to fetch the Unsplash images in WebP format directly.
  Run in terminal:
  ```bash
  mkdir -p assets/images
  curl -s "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=800&fm=webp" -o assets/images/hero-massage.webp
  curl -s "https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=800&fm=webp" -o assets/images/about-massage.webp
  curl -s "https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=800&fm=webp" -o assets/images/service-reflexology.webp
  ```

- [ ] **Step 2: Modify index.php to preload LCP image and use local paths with dimensions**
  Modify `/home/arifrenggy00/Oncall-Home-service-messege-Bali/index.php`:
  - Add preload link in `<head>` (around line 124):
    ```html
    <!-- Preload LCP Image -->
    <link rel="preload" as="image" href="assets/images/hero-massage.webp" type="image/webp" fetchpriority="high">
    ```
  - In the Hero section image (around line 264), replace the remote URL with the local path and specify explicit dimensions:
    ```html
    <img src="assets/images/hero-massage.webp" width="600" height="750" alt="Bali Spa Treatment" class="w-full h-full object-cover">
    ```
  - In the About section image (around line 275), replace the remote URL with the local path, specify explicit dimensions, and add `loading="lazy"`:
    ```html
    <img src="assets/images/about-massage.webp" width="800" height="600" loading="lazy" alt="Oncall & Home Service Massage Bali" class="w-full h-full object-cover">
    ```

- [ ] **Step 3: Add dynamic fallback logic for database services images in index.php**
  In `/home/arifrenggy00/Oncall-Home-service-messege-Bali/index.php`, inside the Treatments loop (around line 373):
  Add a helper logic to map remote fallback URLs to local assets:
  ```php
  <?php 
  $serviceImg = $service['image_path'];
  if (strpos($serviceImg, 'unsplash.com') !== false) {
      if ($service['service_id'] === 'balinese-massage') {
          $serviceImg = 'assets/images/hero-massage.webp';
      } elseif ($service['service_id'] === 'deep-tissue') {
          $serviceImg = 'assets/images/about-massage.webp';
      } elseif ($service['service_id'] === 'reflexology') {
          $serviceImg = 'assets/images/service-reflexology.webp';
      }
  }
  ?>
  ```
  Then modify the `<img>` tag:
  ```html
  <img src="<?php echo htmlspecialchars($serviceImg); ?>" width="448" height="336" loading="lazy" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700 ease-out">
  ```

- [ ] **Step 4: Commit assets and edits**
  Run in terminal:
  ```bash
  git add assets/images/ index.php
  git commit -m "perf: localize images, add width/height attributes, and setup lazy loading & preloads"
  ```

---

### Task 4: Configure Caching & Compression (.htaccess)

**Files:**
- Create: `.htaccess`

- [ ] **Step 1: Create .htaccess in root directory**
  Add standard Gzip compression rules and Expires caching header configuration for Apache.
  Create `/home/arifrenggy00/Oncall-Home-service-messege-Bali/.htaccess`:
  ```apache
  # Enable Gzip Compression
  <IfModule mod_deflate.c>
      AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/x-javascript application/json application/xml
  </IfModule>

  # Enable Browser Caching
  <IfModule mod_expires.c>
      ExpiresActive On
      ExpiresDefault "access plus 1 month"
      ExpiresByType image/jpg "access plus 1 year"
      ExpiresByType image/jpeg "access plus 1 year"
      ExpiresByType image/png "access plus 1 year"
      ExpiresByType image/webp "access plus 1 year"
      ExpiresByType image/svg+xml "access plus 1 year"
      ExpiresByType text/css "access plus 1 year"
      ExpiresByType text/javascript "access plus 1 year"
      ExpiresByType application/javascript "access plus 1 year"
      ExpiresByType font/woff2 "access plus 1 year"
  </IfModule>
  ```

- [ ] **Step 2: Commit .htaccess**
  Run in terminal:
  ```bash
  git add .htaccess
  git commit -m "perf: add apache compression and caching configuration"
  ```

---

### Task 5: End-to-End Verification

- [ ] **Step 1: Run validation scripts**
  Verify the edits didn't break baseline rules.
  Run in terminal:
  ```bash
  node tests/validate-seo-optimization.js
  node tests/validate-admin-crud-sql.js
  node tests/validate-admin-db-auth.js
  ```
  Expected: All tests pass.

- [ ] **Step 2: Manually check index.php and admin/index.php rendering**
  Ensure there are no PHP lint or formatting issues.
  Run in terminal:
  ```bash
  php -l index.php
  php -l admin/index.php
  ```
  Expected: `No syntax errors detected` for both files.
