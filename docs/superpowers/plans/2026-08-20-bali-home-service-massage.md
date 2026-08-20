# Bali Home Service Massage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a high-performance, responsive single-page landing page website for a home service massage business in Bali with Decap CMS integration, custom WhatsApp booking links, Google Maps integration, and SEO readiness.

**Architecture:** A static Jamstack architecture consisting of a single HTML5 page styled with Tailwind CSS, using Vanilla JS to fetch and dynamically render services and business information from a local `data/content.json` file. Decap CMS is integrated via a static admin folder to manage the JSON data file and commits updates directly back to GitHub, triggering auto-builds on Netlify.

**Tech Stack:** HTML5, Tailwind CSS (via CDN), Vanilla JavaScript, Decap CMS, Node.js (for simple automated test scripts).

## Global Constraints
*   **Language:** Client-facing site must be completely in English. Admin dashboard (Decap CMS labels) can be in Indonesian/English.
*   **Color Theme:** Green theme (Primary: Green, Accent: White, Beige, Soft Gold).
*   **Device Support:** Mobile-first layout (fully responsive).
*   **Hosting Compatibility:** Hosted on Netlify with Netlify Identity for Decap CMS authentication.
*   **Clean Separation:** Dynamic content (pricing, text) must be fetched from `data/content.json`, not hardcoded in HTML.

---

### Task 1: Project Setup, Content Schema, and Testing Scaffolding

**Files:**
*   Create: `data/content.json`
*   Create: `tests/validate-content.js`

**Interfaces:**
*   Consumes: None
*   Produces: `data/content.json` containing structured content for services, pricing, business contact info, and areas.

- [ ] **Step 1: Write the failing validation test**
    Create `tests/validate-content.js` to assert the existence and schema of `data/content.json`.
    ```javascript
    // tests/validate-content.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking if data/content.json exists...");
        const dataPath = path.join(__dirname, '../data/content.json');
        
        assert.ok(fs.existsSync(dataPath), "File data/content.json does not exist");
        
        const content = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
        
        // Assert required structure
        assert.ok(content.general, "Missing 'general' settings");
        assert.ok(content.general.brandName, "Missing brandName");
        assert.ok(content.general.whatsapp, "Missing whatsapp number");
        assert.ok(content.services && Array.isArray(content.services), "Missing or invalid 'services' array");
        assert.ok(content.faqs && Array.isArray(content.faqs), "Missing or invalid 'faqs' array");
        assert.ok(content.areas && Array.isArray(content.areas), "Missing or invalid 'areas' array");
        
        console.log("PASS: data/content.json has correct structure!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-content.js`
    Expected: FAIL with "File data/content.json does not exist" or directory not found.

- [ ] **Step 3: Create the minimal JSON content file**
    Create `data/content.json` with the required schema and initial data.
    ```json
    {
      "general": {
        "brandName": "Bali Green Oasis Massage",
        "tagline": "Rejuvenate Your Body & Mind at Your Villa",
        "description": "Premium on-call massage and spa treatments delivered directly to your villa, hotel, or home in Bali. Certified therapists, organic oils, and pure relaxation.",
        "whatsapp": "6281234567890",
        "instagram": "https://instagram.com/baligreenoasis",
        "operatingHours": "09:00 AM - 10:00 PM"
      },
      "services": [
        {
          "id": "balinese-massage",
          "title": "Balinese Traditional Massage",
          "description": "A full-body, deep-tissue, holistic treatment that uses a combination of gentle stretches, acupressure, reflexology, and aromatherapy.",
          "image": "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600",
          "options": [
            { "duration": "60 Mins", "price": "250,000 IDR" },
            { "duration": "90 Mins", "price": "350,000 IDR" },
            { "duration": "120 Mins", "price": "450,000 IDR" }
          ],
          "featured": true
        },
        {
          "id": "deep-tissue",
          "title": "Deep Tissue Massage",
          "description": "Focuses on realigning deeper layers of muscles. Beneficial for chronic aches and pains and contracted areas.",
          "image": "https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=600",
          "options": [
            { "duration": "60 Mins", "price": "300,000 IDR" },
            { "duration": "90 Mins", "price": "400,000 IDR" },
            { "duration": "120 Mins", "price": "500,000 IDR" }
          ],
          "featured": true
        },
        {
          "id": "reflexology",
          "title": "Foot Reflexology",
          "description": "Applies pressure to specific points on the feet to restore natural energy flow and improve circulation.",
          "image": "https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=600",
          "options": [
            { "duration": "60 Mins", "price": "200,000 IDR" },
            { "duration": "90 Mins", "price": "280,000 IDR" }
          ],
          "featured": false
        }
      ],
      "areas": [
        "Pecatu, Uluwatu, Nusa Dua",
        "Kuta, Seminyak, Canggu (including Pererenan)",
        "Tanah Lot, Tabanan",
        "Gianyar, Ubud"
      ],
      "faqs": [
        {
          "question": "How do I book a massage?",
          "answer": "Simply select your service on our website, click 'Book on WhatsApp', fill in your details (date, time, villa/hotel address), and our admin will confirm your booking instantly."
        },
        {
          "question": "Are there any transport fees?",
          "answer": "No, all transport costs are included in the menu price for our service coverage areas."
        },
        {
          "question": "What payment methods do you accept?",
          "answer": "We accept Cash (IDR) directly to the therapist, Bank Transfers, or Wise payments."
        }
      ]
    }
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-content.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add data/content.json tests/validate-content.js
    git commit -m "feat: set up content schema and validation test"
    ```

---

### Task 2: Decap CMS Configuration Scaffold

**Files:**
*   Create: `admin/index.html`
*   Create: `admin/config.yml`
*   Create: `tests/validate-cms.js`

**Interfaces:**
*   Consumes: `data/content.json`
*   Produces: Admin route `/admin/` rendering Decap CMS visual editor connected to `data/content.json`.

- [ ] **Step 1: Write validation test for CMS setup**
    Create `tests/validate-cms.js` to ensure the config file exists and contains the correct file mappings and collection structures.
    ```javascript
    // tests/validate-cms.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking Decap CMS files...");
        const adminHtmlPath = path.join(__dirname, '../admin/index.html');
        const configYmlPath = path.join(__dirname, '../admin/config.yml');

        assert.ok(fs.existsSync(adminHtmlPath), "Missing admin/index.html");
        assert.ok(fs.existsSync(configYmlPath), "Missing admin/config.yml");

        const configContent = fs.readFileSync(configYmlPath, 'utf8');
        assert.ok(configContent.includes('data/content.json'), "config.yml must link to data/content.json");
        assert.ok(configContent.includes('collections:'), "config.yml must define collections");

        console.log("PASS: CMS config is valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-cms.js`
    Expected: FAIL with "Missing admin/index.html" or "Missing admin/config.yml"

- [ ] **Step 3: Implement Decap CMS files**
    Create `admin/index.html`:
    ```html
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Content Manager - Bali Green Oasis Massage</title>
        <!-- Netlify Identity Widget -->
        <script src="https://identity.netlify.com/v1/netlify-identity-widget.js"></script>
      </head>
      <body>
        <!-- Decap CMS Script -->
        <script src="https://unpkg.com/decap-cms@^3.0.0/dist/decap-cms.js"></script>
      </body>
    </html>
    ```
    
    Create `admin/config.yml`:
    ```yaml
    backend:
      name: git-gateway
      branch: main

    media_folder: "assets/images"
    public_folder: "/assets/images"

    collections:
      - name: "website_content"
        label: "Website Content"
        delete: false
        editor:
          preview: false
        files:
          - file: "data/content.json"
            label: "Landing Page Data"
            name: "landing_data"
            file_key: "data"
            fields:
              - label: "General Settings"
                name: "general"
                widget: "object"
                fields:
                  - { label: "Brand Name", name: "brandName", widget: "string" }
                  - { label: "Tagline", name: "tagline", widget: "string" }
                  - { label: "About Description", name: "description", widget: "text" }
                  - { label: "WhatsApp Number (format: 628xxx)", name: "whatsapp", widget: "string" }
                  - { label: "Instagram Link", name: "instagram", widget: "string", required: false }
                  - { label: "Operating Hours", name: "operatingHours", widget: "string" }
              
              - label: "Massage Services Menu"
                name: "services"
                widget: "list"
                fields:
                  - { label: "ID (Unique, lowercase, e.g., deep-tissue)", name: "id", widget: "string" }
                  - { label: "Service Name", name: "title", widget: "string" }
                  - { label: "Description", name: "description", widget: "text" }
                  - { label: "Image URL", name: "image", widget: "image" }
                  - label: "Duration & Price Options"
                    name: "options"
                    widget: "list"
                    fields:
                      - { label: "Duration (e.g. 60 Mins)", name: "duration", widget: "string" }
                      - { label: "Price (e.g. 250,000 IDR)", name: "price", widget: "string" }
                  - { label: "Show on Homepage Feature?", name: "featured", widget: "boolean", default: true }

              - label: "Service Areas List"
                name: "areas"
                widget: "list"
                field: { label: "Area Region Name", name: "area", widget: "string" }

              - label: "Frequently Asked Questions (FAQ)"
                name: "faqs"
                widget: "list"
                fields:
                  - { label: "Question", name: "question", widget: "string" }
                  - { label: "Answer", name: "answer", widget: "text" }
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-cms.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.html admin/config.yml tests/validate-cms.js
    git commit -m "feat: configure Decap CMS settings and content mappings"
    ```

---

### Task 3: Homepage HTML & Tailwind Scaffold (Mobile-First Layout)

**Files:**
*   Create: `index.html`
*   Create: `tests/validate-html.js`

**Interfaces:**
*   Consumes: None (Statically structured first)
*   Produces: Fully responsive HTML layout ready for injection of dynamic JS.

- [ ] **Step 1: Write HTML template validation test**
    Create `tests/validate-html.js` to ensure key DOM nodes, semantic elements, Meta tags, Tailwind CDN link, and scripts exist.
    ```javascript
    // tests/validate-html.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.html structure...");
        const htmlPath = path.join(__dirname, '../index.html');
        assert.ok(fs.existsSync(htmlPath), "Missing index.html");

        const content = fs.readFileSync(htmlPath, 'utf8');

        // Check required SEO tags & Scripts
        assert.ok(content.includes('viewport'), "Missing viewport meta tag for mobile responsiveness");
        assert.ok(content.includes('description'), "Missing meta description tag");
        assert.ok(content.includes('tailwindcss'), "Missing Tailwind CSS CDN load");
        assert.ok(content.includes('netlify-identity-widget'), "Missing Netlify Identity script in index.html footer");
        assert.ok(content.includes('js/app.js'), "Missing js/app.js reference");

        // Check required section placeholders
        assert.ok(content.includes('id="hero"'), "Missing hero section container");
        assert.ok(content.includes('id="why-us"'), "Missing why-us section container");
        assert.ok(content.includes('id="services"'), "Missing services list container");
        assert.ok(content.includes('id="areas"'), "Missing areas section container");
        assert.ok(content.includes('id="faqs"'), "Missing FAQ section container");
        assert.ok(content.includes('id="map"'), "Missing map section container");

        console.log("PASS: HTML template scaffold looks complete!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-html.js`
    Expected: FAIL with "Missing index.html"

- [ ] **Step 3: Create the HTML5 template**
    Create `index.html` with clean layouts, custom Google font (`Playfair Display` for elegant headings and `Inter` for clean body), Tailwind configs, and elements.
    ```html
    <!DOCTYPE html>
    <html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- SEO Meta Tags -->
        <title>Premium Home Service Massage & Spa in Bali</title>
        <meta name="description" content="Professional on-call spa & traditional massage therapy delivered directly to your villa, hotel, or residence in Bali. Book easily via WhatsApp. Clean, certified & premium experience.">
        <meta name="keywords" content="home service massage bali, massage home service bali, oncall spa bali, massage villa bali, massage seminyak, massage canggu, massage ubud">
        
        <!-- OpenGraph Meta Tags (SEO/Social) -->
        <meta property="og:title" content="Premium Home Service Massage & Spa in Bali">
        <meta property="og:description" content="Professional massage therapy directly to your villa or hotel in Bali. Certified therapists, organic oils, and top relaxation.">
        <meta property="og:type" content="website">
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="https://img.icons8.com/color/48/spa.png">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            serif: ['Playfair Display', 'serif'],
                        },
                        colors: {
                            theme: {
                                50: '#f2f8f4',
                                100: '#e1efe6',
                                200: '#c5e0cf',
                                300: '#99c7aa',
                                400: '#68a57e',
                                500: '#46885f',
                                600: '#346d4a',
                                700: '#2a573c',
                                800: '#234631',
                                900: '#1d3b2a',
                                gold: '#d4af37',
                                beige: '#faf8f5',
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
            .font-sans { font-family: 'Inter', sans-serif; }
        </style>
        
        <!-- Netlify Identity Widget -->
        <script src="https://identity.netlify.com/v1/netlify-identity-widget.js"></script>
    </head>
    <body class="bg-theme-beige text-stone-800 font-sans antialiased">

        <!-- Navigation -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-theme-100 shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-2">
                    <span class="text-xl font-serif font-bold text-theme-700 tracking-wide" id="nav-brand">Bali Massage</span>
                </a>
                <div class="hidden md:flex space-x-8 text-sm font-medium text-stone-600">
                    <a href="#services" class="hover:text-theme-600 transition-colors">Services</a>
                    <a href="#why-us" class="hover:text-theme-600 transition-colors">Why Choose Us</a>
                    <a href="#areas" class="hover:text-theme-600 transition-colors">Service Areas</a>
                    <a href="#faqs" class="hover:text-theme-600 transition-colors">FAQs</a>
                </div>
                <a href="#services" class="bg-theme-600 hover:bg-theme-700 text-white px-5 py-2 rounded-full text-sm font-semibold tracking-wide shadow-sm hover:shadow transition-all">Book Now</a>
            </nav>
        </header>

        <!-- Hero Section -->
        <section id="hero" class="relative overflow-hidden bg-theme-100 py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-block bg-theme-600/10 text-theme-700 font-semibold px-4 py-1.5 rounded-full text-xs uppercase tracking-wider">Luxe Wellness Coming to You</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-theme-900 leading-tight" id="hero-title">
                        Rejuvenate Your Body & Mind at Your Villa
                    </h1>
                    <p class="text-lg text-stone-600 max-w-xl leading-relaxed" id="hero-desc">
                        Premium on-call massage and spa treatments delivered directly to your villa, hotel, or home in Bali. Certified therapists, organic oils, and pure relaxation.
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="#services" class="bg-theme-600 hover:bg-theme-700 text-white px-8 py-3 rounded-full text-base font-semibold tracking-wide shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Explore Treatments</a>
                        <a href="#why-us" class="border-2 border-theme-600 text-theme-700 hover:bg-theme-600 hover:text-white px-8 py-3 rounded-full text-base font-semibold tracking-wide transition-all transform hover:-translate-y-0.5">Learn More</a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="aspect-square bg-gradient-to-tr from-theme-200 to-theme-50 rounded-2xl overflow-hidden shadow-2xl relative border-4 border-white">
                        <img id="hero-img" src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=800" alt="Balinese Massage Treatment" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section id="why-us" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto space-y-4">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-theme-950">Indulge in Premium Wellness</h2>
                    <p class="text-stone-500">We prioritize your health, comfort, and peace of mind. Here is why clients choose us.</p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                    <div class="bg-theme-beige p-8 rounded-2xl border border-theme-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-theme-100 rounded-xl flex items-center justify-center text-theme-600 text-2xl mb-6">💆‍♀️</div>
                        <h3 class="text-xl font-bold text-theme-900 mb-2">Certified Therapists</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">Our female therapists are fully trained, certified, and experienced in professional spa treatments and anatomy.</p>
                    </div>
                    <div class="bg-theme-beige p-8 rounded-2xl border border-theme-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-theme-100 rounded-xl flex items-center justify-center text-theme-600 text-2xl mb-6">🌿</div>
                        <h3 class="text-xl font-bold text-theme-900 mb-2">100% Organic Oils</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">We use only organic, virgin coconut oil and premium essential oils to nourish your skin and enhance relaxation.</p>
                    </div>
                    <div class="bg-theme-beige p-8 rounded-2xl border border-theme-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-theme-100 rounded-xl flex items-center justify-center text-theme-600 text-2xl mb-6">🚗</div>
                        <h3 class="text-xl font-bold text-theme-900 mb-2">No Transport Fee</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">No hidden charges. Our massage prices include all transport costs directly to your villa, hotel, or apartment.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-20 bg-theme-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto space-y-4">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-theme-950">Our Massage Menu</h2>
                    <p class="text-stone-500">Pick from our carefully selected list of authentic Balinese spa therapies. Book easily on WhatsApp.</p>
                </div>
                
                <div id="services-list" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                    <!-- Dynamic services will load here -->
                    <div class="animate-pulse bg-stone-200 h-96 rounded-2xl"></div>
                    <div class="animate-pulse bg-stone-200 h-96 rounded-2xl"></div>
                    <div class="animate-pulse bg-stone-200 h-96 rounded-2xl"></div>
                </div>
            </div>
        </section>

        <!-- Service Area & Google Maps -->
        <section id="areas" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-theme-950">Service Area Coverage</h2>
                    <p class="text-stone-600">Our on-call massage service is available across key tourist and residential areas in Bali. No transport fee is charged within these boundaries:</p>
                    
                    <ul id="areas-list" class="space-y-3">
                        <!-- Dynamic list of areas will load here -->
                    </ul>
                    
                    <div class="bg-theme-50 p-6 rounded-2xl border border-theme-100 flex items-start space-x-4">
                        <span class="text-2xl mt-1">📍</span>
                        <div>
                            <h4 class="font-bold text-theme-900">Villa/Hotel/Home Panggilan</h4>
                            <p class="text-stone-500 text-sm leading-relaxed">Our therapists arrive with massage tables/mats, professional massage oils, linen, and relaxing music setup.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Google Maps Embed -->
                <div id="map" class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg border border-stone-200">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d252438.48918239088!2d115.09312151676646!3d-8.67045813735076!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd140d384d8b58b%3A0xa126509f7e1b7f94!2sBali!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

        <!-- FAQs Section -->
        <section id="faqs" class="py-20 bg-theme-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center space-y-4 mb-12">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-theme-950">Frequently Asked Questions</h2>
                    <p class="text-stone-500">Everything you need to know about our Bali home massage services.</p>
                </div>
                
                <div id="faqs-list" class="space-y-4">
                    <!-- Dynamic FAQs will load here -->
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-theme-900 text-theme-100 py-12 border-t border-theme-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-serif text-xl font-bold text-white mb-4" id="footer-brand">Bali Massage</h3>
                    <p class="text-stone-400 text-sm leading-relaxed">Relaxation and spa therapeutic treatments at your convenience. Book in under 3 minutes.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-stone-400 text-sm">
                        <li>WhatsApp: <a href="#" id="footer-wa" class="hover:text-white transition-colors"></a></li>
                        <li>Operating Hours: <span id="footer-hours"></span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Follow Us</h4>
                    <a href="#" id="footer-insta" target="_blank" class="hover:text-white transition-colors text-stone-400 text-sm flex items-center space-x-2">
                        <span>Instagram</span>
                    </a>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-theme-800 mt-8 pt-8 text-center text-stone-500 text-xs">
                &copy; <span id="copyright-year">2026</span> <span id="copyright-brand">Bali Massage</span>. All Rights Reserved. Designed for wellness.
            </div>
        </footer>

        <!-- Core App JS -->
        <script src="js/app.js"></script>
        
        <!-- Netlify Identity Widget Handoff -->
        <script>
          if (window.netlifyIdentity) {
            window.netlifyIdentity.on("init", user => {
              if (!user) {
                window.netlifyIdentity.on("login", () => {
                  document.location.href = "/admin/";
                });
              }
            });
          }
        </script>
    </body>
    </html>
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-html.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add index.html tests/validate-html.js
    git commit -m "feat: design mobile-first responsive HTML layout scaffold"
    ```

---

### Task 4: Content Loading Script & WhatsApp Booking Generator

**Files:**
*   Create: `js/app.js`
*   Create: `tests/validate-app-js.js`

**Interfaces:**
*   Consumes: `data/content.json`
*   Produces: Dynamically populated pages, reactive select dropdown price changes, custom WhatsApp booking link creation with populated templates.

- [ ] **Step 1: Write application script validation test**
    Create `tests/validate-app-js.js` to ensure the Javascript app exists and exposes the core render logic.
    ```javascript
    // tests/validate-app-js.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking js/app.js script...");
        const jsPath = path.join(__dirname, '../js/app.js');
        assert.ok(fs.existsSync(jsPath), "Missing js/app.js");

        const content = fs.readFileSync(jsPath, 'utf8');

        // Check required programmatic constructs
        assert.ok(content.includes('fetch'), "Must use fetch to load data");
        assert.ok(content.includes('data/content.json'), "Must fetch data/content.json");
        assert.ok(content.includes('encodeURIComponent'), "Must encode URL parameters for WhatsApp text");

        console.log("PASS: js/app.js exists and contains core fetching logic!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-app-js.js`
    Expected: FAIL with "Missing js/app.js"

- [ ] **Step 3: Implement Vanilla JS file**
    Create `js/app.js` to read raw data and bind logic to page nodes:
    ```javascript
    // js/app.js
    document.addEventListener("DOMContentLoaded", () => {
        fetch('data/content.json')
            .then(res => res.json())
            .then(data => {
                renderGeneral(data.general);
                renderServices(data.services, data.general.whatsapp);
                renderAreas(data.areas);
                renderFAQs(data.faqs);
            })
            .catch(err => {
                console.error("Error loading content settings:", err);
            });
    });

    function renderGeneral(general) {
        // Titles & brand names
        document.title = `${general.brandName} - Premium Home Service Massage in Bali`;
        document.getElementById('nav-brand').textContent = general.brandName;
        document.getElementById('footer-brand').textContent = general.brandName;
        document.getElementById('copyright-brand').textContent = general.brandName;
        
        document.getElementById('hero-title').textContent = general.tagline;
        document.getElementById('hero-desc').textContent = general.description;
        
        // Footer info
        const waLink = document.getElementById('footer-wa');
        waLink.href = `https://wa.me/${general.whatsapp}`;
        waLink.textContent = `+${general.whatsapp}`;
        
        document.getElementById('footer-hours').textContent = general.operatingHours;
        
        const instaLink = document.getElementById('footer-insta');
        if (general.instagram) {
            instaLink.href = general.instagram;
            instaLink.classList.remove('hidden');
        } else {
            instaLink.classList.add('hidden');
        }

        // Set Copyright Year
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    }

    function renderServices(services, whatsapp) {
        const listContainer = document.getElementById('services-list');
        listContainer.innerHTML = ''; // Clear loading skeleton placeholders

        services.forEach(service => {
            // Generate select dropdown element for duration options
            let optionsHTML = '';
            service.options.forEach((opt, index) => {
                optionsHTML += `<option value="${opt.duration}" data-price="${opt.price}">${opt.duration} - ${opt.price}</option>`;
            });

            const card = document.createElement('div');
            card.className = "bg-white rounded-2xl overflow-hidden border border-theme-100 shadow-sm hover:shadow-md transition-shadow flex flex-col";
            card.innerHTML = `
                <div class="h-56 bg-stone-100 overflow-hidden relative">
                    <img src="${service.image}" alt="${service.title}" class="w-full h-full object-cover">
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div class="space-y-3">
                        <h3 class="text-xl font-bold text-theme-900">${service.title}</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">${service.description}</p>
                    </div>
                    
                    <div class="mt-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Select Duration</label>
                            <select id="select-${service.id}" class="w-full border border-stone-200 bg-stone-50 px-3 py-2 rounded-xl text-sm font-medium focus:ring-2 focus:ring-theme-500 focus:outline-none">
                                ${optionsHTML}
                            </select>
                        </div>
                        
                        <button onclick="bookService('${service.title}', '${service.id}', '${whatsapp}')" class="w-full bg-theme-600 hover:bg-theme-700 text-white font-semibold py-3 px-4 rounded-xl text-sm tracking-wide text-center transition-all flex items-center justify-center space-x-2">
                            <span>Book via WhatsApp</span>
                        </button>
                    </div>
                </div>
            `;
            listContainer.appendChild(card);
        });
    }

    function renderAreas(areas) {
        const container = document.getElementById('areas-list');
        container.innerHTML = '';
        
        areas.forEach(area => {
            const li = document.createElement('li');
            li.className = "flex items-center space-x-3 text-stone-600 text-sm";
            li.innerHTML = `
                <span class="text-theme-600 text-lg">✓</span>
                <span class="font-medium">${area}</span>
            `;
            container.appendChild(li);
        });
    }

    function renderFAQs(faqs) {
        const container = document.getElementById('faqs-list');
        container.innerHTML = '';

        faqs.forEach((faq, index) => {
            const faqEl = document.createElement('div');
            faqEl.className = "bg-white border border-theme-100 rounded-2xl overflow-hidden";
            faqEl.innerHTML = `
                <button onclick="toggleFaq(${index})" class="w-full flex items-center justify-between p-6 text-left font-semibold text-theme-900 hover:bg-theme-50/50 transition-colors">
                    <span>${faq.question}</span>
                    <span id="faq-icon-${index}" class="text-theme-600 transition-transform duration-200">+</span>
                </button>
                <div id="faq-ans-${index}" class="hidden px-6 pb-6 text-sm text-stone-600 leading-relaxed border-t border-stone-50 pt-4">
                    ${faq.answer}
                </div>
            `;
            container.appendChild(faqEl);
        });
    }

    function toggleFaq(index) {
        const ans = document.getElementById(`faq-ans-${index}`);
        const icon = document.getElementById(`faq-icon-${index}`);
        const isHidden = ans.classList.contains('hidden');
        
        // Hide all first
        document.querySelectorAll("[id^='faq-ans-']").forEach(el => el.classList.add('hidden'));
        document.querySelectorAll("[id^='faq-icon-']").forEach(el => el.textContent = '+');

        if (isHidden) {
            ans.classList.remove('hidden');
            icon.textContent = '−';
        }
    }

    function bookService(serviceName, selectId, whatsapp) {
        const select = document.getElementById(`select-${selectId}`);
        const duration = select.value;
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        
        const message = `Hi, I would like to book a ${serviceName} (${duration} - ${price}). Here are my details:
    - Date & Time: 
    - Address (Hotel/Villa/Home): 
    - Number of People: 

    Please confirm my booking. Thank you!`;
        
        const waUrl = `https://wa.me/${whatsapp}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-app-js.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add js/app.js tests/validate-app-js.js
    git commit -m "feat: add client-side dynamic parsing script and WhatsApp link builder"
    ```
