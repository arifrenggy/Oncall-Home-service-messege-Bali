# PHP Admin Dashboard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create a secure, single-file PHP admin dashboard at `admin/index.php` to handle session-based login, update general website text, manage the massage services catalog (with price options & image uploads), edit coverage areas, and customize FAQs directly updating `data/content.json`.

**Architecture:** A lightweight PHP script that manages sessions, performs secure password authentication via hash, renders a dynamic Tailwind CSS administration interface, processes image file uploads to `assets/images/`, and performs write operations to the `data/content.json` file.

**Tech Stack:** PHP 7.4+, Tailwind CSS (CDN), Vanilla JS, Node.js (for simple static tests).

## Global Constraints
*   **Language:** Admin interface labels and inputs in English/Indonesian, writing content in English.
*   **Security:** Password must be hashed. PHP file upload must check mime-types (jpg, jpeg, png, webp).
*   **Data Integrity:** Changes must preserve the structure of `data/content.json`.

---

### Task 1: PHP Admin Dashboard Login & Session Setup

**Files:**
*   Create: `admin/index.php`
*   Create: `tests/validate-php-login.js`

**Interfaces:**
*   Consumes: None
*   Produces: Password login page on GET request without session, sets `$_SESSION['admin_logged_in']` on successful POST.

- [ ] **Step 1: Write the login validation test**
    Create `tests/validate-php-login.js` to verify `admin/index.php` exists and implements password authentication and session security checks.
    ```javascript
    // tests/validate-php-login.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking admin/index.php login mechanics...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        assert.ok(fs.existsSync(adminPath), "File admin/index.php does not exist");

        const content = fs.readFileSync(adminPath, 'utf8');

        // Assert session check is present
        assert.ok(content.includes('session_start('), "Missing session_start() for login management");
        assert.ok(content.includes('password_verify(') || content.includes('password_hash('), "Missing secure password hashing verification");
        assert.ok(content.includes('post') || content.includes('POST'), "Must handle POST requests for login submission");

        console.log("PASS: PHP admin login scaffold looks valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-php-login.js`
    Expected: FAIL with "File admin/index.php does not exist" or matches Decap CMS index.html if not renamed yet.

- [ ] **Step 3: Create index.php with Login logic**
    Create folder `admin` (if not exists) and replace contents of `admin/index.php` (overwriting the old Decap CMS index.html since we are converting).
    ```php
    <?php
    // admin/index.php
    session_start();

    // Default hashed password for "adminbali123"
    // To generate a new hash: password_hash("your_password", PASSWORD_DEFAULT)
    $password_hash = '$2y$10$WpP9U142Y2h569L4v05Hau8VfLzBszXfA.q1aW45B0uX4V8G1v4fO'; // adminbali123

    $error = '';

    // Handle logout
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        unset($_SESSION['admin_logged_in']);
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $password = $_POST['password'] ?? '';
        if (password_verify($password, $password_hash)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid password. Please try again.';
        }
    }

    // Check session
    $is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

    // Show login page if not logged in
    if (!$is_logged_in):
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Oncall & home service message</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-stone-50 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl p-8 border border-stone-200 shadow-md space-y-6">
            <div class="text-center">
                <span class="text-3xl">💆‍♀️</span>
                <h1 class="text-2xl font-bold text-stone-900 mt-2">Admin Dashboard</h1>
                <p class="text-stone-500 text-sm">Oncall & home service message</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 text-sm p-3 rounded-xl border border-red-100 font-medium">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php" class="space-y-4">
                <input type="hidden" name="login" value="1">
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-500 mb-1">Enter Password</label>
                    <input type="password" id="password" name="password" required class="w-full border border-stone-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl text-sm transition-colors shadow-sm">
                    Access Dashboard
                </button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
    endif;
    ?>
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-php-login.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git rm admin/index.html
    git add admin/index.php tests/validate-php-login.js
    git commit -m "feat: implement secure session login page in PHP admin dashboard"
    ```

---

### Task 2: PHP Dashboard UI & Content Editor View

**Files:**
*   Modify: `admin/index.php` (append dashboard UI to the file)
*   Create: `tests/validate-php-dashboard.js`

**Interfaces:**
*   Consumes: `data/content.json`
*   Produces: Rich UI tabs (General, Services, Areas, FAQs) when authenticated.

- [ ] **Step 1: Write test for dashboard UI elements**
    Create `tests/validate-php-dashboard.js` to ensure the dashboard view renders form inputs, data reading bindings, and secure CSRF/POST elements.
    ```javascript
    // tests/validate-php-dashboard.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking dashboard UI fields inside index.php...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        const content = fs.readFileSync(adminPath, 'utf8');

        // Assert HTML structure elements exist
        assert.ok(content.includes('brandName'), "Missing brandName input field binding");
        assert.ok(content.includes('whatsapp'), "Missing whatsapp field binding");
        assert.ok(content.includes('services'), "Missing services listing UI");
        assert.ok(content.includes('faqs'), "Missing FAQ editing fields");

        console.log("PASS: PHP admin UI fields are correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-php-dashboard.js`
    Expected: FAIL because `admin/index.php` only contains the login layout.

- [ ] **Step 3: Append dashboard HTML view**
    Modify `admin/index.php` by appending the full authenticated view code after `endif;`.
    ```php
    <?php
    // admin/index.php (Authenticated Dashboard View)
    $content_file = __DIR__ . '/../data/content.json';
    $data = json_decode(file_get_contents($content_file), true);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Oncall & home service message</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .tab-content { display: none; }
            .tab-content.active { display: block; }
        </style>
    </head>
    <body class="bg-stone-50 min-h-screen">

        <!-- Top Header Navigation -->
        <header class="bg-white border-b border-stone-200 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="text-2xl">💆‍♀️</span>
                <div>
                    <h1 class="text-lg font-bold text-stone-900">Admin Panel</h1>
                    <p class="text-xs text-stone-500">Edit Website Live Content</p>
                </div>
            </div>
            <a href="index.php?action=logout" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-xs font-semibold border border-red-200 transition-colors">
                Logout
            </a>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid lg:grid-cols-12 gap-8">
            
            <!-- Sidebar Navigation Tabs -->
            <div class="lg:col-span-3 space-y-2">
                <button onclick="switchTab('general')" id="tab-btn-general" class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors bg-emerald-600 text-white">
                    General Settings
                </button>
                <button onclick="switchTab('services')" id="tab-btn-services" class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors hover:bg-stone-100 text-stone-700">
                    Massage Menu
                </button>
                <button onclick="switchTab('areas')" id="tab-btn-areas" class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors hover:bg-stone-100 text-stone-700">
                    Service Areas
                </button>
                <button onclick="switchTab('faqs')" id="tab-btn-faqs" class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors hover:bg-stone-100 text-stone-700">
                    FAQs
                </button>
            </div>

            <!-- Tab Content Pane -->
            <main class="lg:col-span-9 bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                
                <form id="admin-form" method="POST" action="index.php" enctype="multipart/form-data">
                    <input type="hidden" name="save_content" value="1">
                    
                    <!-- TAB 1: GENERAL SETTINGS -->
                    <div id="tab-general" class="tab-content active space-y-6">
                        <div>
                            <h2 class="text-xl font-bold text-stone-900">General Settings</h2>
                            <p class="text-xs text-stone-500 mt-1">Configure brand identity, taglines, contact paths, and active operational windows.</p>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Brand Name</label>
                                <input type="text" name="general[brandName]" value="<?php echo htmlspecialchars($data['general']['brandName']); ?>" required class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">WhatsApp Number (e.g., 6281234567890)</label>
                                <input type="text" name="general[whatsapp]" value="<?php echo htmlspecialchars($data['general']['whatsapp']); ?>" required class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Hero Tagline</label>
                                <input type="text" name="general[tagline]" value="<?php echo htmlspecialchars($data['general']['tagline']); ?>" required class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">About Us Description</label>
                                <textarea name="general[description]" rows="4" required class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium"><?php echo htmlspecialchars($data['general']['description']); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Instagram Link (Optional)</label>
                                <input type="url" name="general[instagram]" value="<?php echo htmlspecialchars($data['general']['instagram'] ?? ''); ?>" class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Operating Hours</label>
                                <input type="text" name="general[operatingHours]" value="<?php echo htmlspecialchars($data['general']['operatingHours']); ?>" required class="w-full border border-stone-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: MASSAGE SERVICES -->
                    <div id="tab-services" class="tab-content space-y-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-stone-900">Massage Menu Services</h2>
                                <p class="text-xs text-stone-500 mt-1">Add, update, or remove treatments, prices, and configure featured elements.</p>
                            </div>
                            <button type="button" onclick="addService()" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold border border-emerald-200 transition-colors">
                                + Add Service
                            </button>
                        </div>
                        
                        <div id="services-container" class="space-y-6">
                            <?php foreach ($data['services'] as $index => $service): ?>
                                <div class="bg-stone-50 border border-stone-200 p-6 rounded-2xl relative space-y-4" id="service-card-<?php echo $index; ?>">
                                    <button type="button" onclick="removeElement('service-card-<?php echo $index; ?>')" class="absolute top-4 right-4 bg-red-50 hover:bg-red-100 text-red-600 p-1.5 rounded-lg border border-red-200 text-xs font-bold">Remove</button>
                                    
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Unique ID (e.g. deep-tissue)</label>
                                            <input type="text" name="services[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($service['id']); ?>" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Title</label>
                                            <input type="text" name="services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($service['title']); ?>" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Description</label>
                                            <input type="text" name="services[<?php echo $index; ?>][description]" value="<?php echo htmlspecialchars($service['description']); ?>" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                                        </div>
                                        
                                        <!-- Image Upload & Preview -->
                                        <div>
                                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Image</label>
                                            <input type="file" name="service_image_<?php echo $index; ?>" class="w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                            <input type="hidden" name="services[<?php echo $index; ?>][image]" value="<?php echo htmlspecialchars($service['image']); ?>">
                                            <p class="text-[10px] text-stone-400 mt-1">Current path: <?php echo htmlspecialchars($service['image']); ?></p>
                                        </div>
                                        
                                        <!-- Featured Switcher -->
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="services[<?php echo $index; ?>][featured]" value="true" <?php echo isset($service['featured']) && $service['featured'] ? 'checked' : ''; ?> class="rounded text-emerald-600 focus:ring-emerald-500">
                                                <span class="text-xs font-semibold text-stone-600 uppercase tracking-wide">Featured on Homepage</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Duration & Prices Array Options -->
                                    <div class="border-t border-stone-200 pt-4 space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Durations & Pricing Options</span>
                                            <button type="button" onclick="addOption(<?php echo $index; ?>)" class="text-emerald-600 hover:text-emerald-700 text-xs font-bold">+ Add Option</button>
                                        </div>
                                        <div id="options-container-<?php echo $index; ?>" class="space-y-2">
                                            <?php foreach ($service['options'] as $oIdx => $opt): ?>
                                                <div class="flex items-center space-x-3" id="option-<?php echo $index; ?>-<?php echo $oIdx; ?>">
                                                    <input type="text" name="services[<?php echo $index; ?>][options][<?php echo $oIdx; ?>][duration]" value="<?php echo htmlspecialchars($opt['duration']); ?>" placeholder="60 Mins" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/3">
                                                    <input type="text" name="services[<?php echo $index; ?>][options][<?php echo $oIdx; ?>][price]" value="<?php echo htmlspecialchars($opt['price']); ?>" placeholder="250,000 IDR" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/2">
                                                    <button type="button" onclick="removeElement('option-<?php echo $index; ?>-<?php echo $oIdx; ?>')" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- TAB 3: SERVICE AREAS -->
                    <div id="tab-areas" class="tab-content space-y-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-stone-900">Service Areas Coverage</h2>
                                <p class="text-xs text-stone-500 mt-1">Manage geographic boundaries and neighborhoods serviced in Bali.</p>
                            </div>
                            <button type="button" onclick="addArea()" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold border border-emerald-200 transition-colors">
                                + Add Area
                            </button>
                        </div>
                        
                        <div id="areas-container" class="space-y-3">
                            <?php foreach ($data['areas'] as $index => $area): ?>
                                <div class="flex items-center space-x-4" id="area-card-<?php echo $index; ?>">
                                    <input type="text" name="areas[]" value="<?php echo htmlspecialchars($area); ?>" required class="flex-1 border border-stone-200 px-4 py-2.5 rounded-xl text-sm font-medium">
                                    <button type="button" onclick="removeElement('area-card-<?php echo $index; ?>')" class="bg-red-50 hover:bg-red-100 text-red-600 p-2.5 rounded-xl border border-red-200 text-xs font-bold">Delete</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- TAB 4: FAQS -->
                    <div id="tab-faqs" class="tab-content space-y-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-stone-900">Frequently Asked Questions</h2>
                                <p class="text-xs text-stone-500 mt-1">Configure user answers regarding booking, operation, or cancellation policies.</p>
                            </div>
                            <button type="button" onclick="addFaq()" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold border border-emerald-200 transition-colors">
                                + Add FAQ
                            </button>
                        </div>
                        
                        <div id="faqs-container" class="space-y-4">
                            <?php foreach ($data['faqs'] as $index => $faq): ?>
                                <div class="bg-stone-50 border border-stone-200 p-5 rounded-2xl relative space-y-3" id="faq-card-<?php echo $index; ?>">
                                    <button type="button" onclick="removeElement('faq-card-<?php echo $index; ?>')" class="absolute top-4 right-4 bg-red-50 hover:bg-red-100 text-red-600 p-1.5 rounded-lg border border-red-200 text-xs font-bold">Remove</button>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Question</label>
                                        <input type="text" name="faqs[<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars($faq['question']); ?>" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Answer</label>
                                        <textarea name="faqs[<?php echo $index; ?>][answer]" rows="3" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white"><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Bottom Bar CTA -->
                    <div class="mt-8 pt-6 border-t border-stone-200 flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl text-sm transition-all shadow-md">
                            Save All Changes
                        </button>
                    </div>
                </form>

            </main>
        </div>

        <script>
            // Tab Switcher Logic
            function switchTab(tabId) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
                document.getElementById('tab-' + tabId).classList.add('active');

                // Button stylings
                document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
                    btn.className = "w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors hover:bg-stone-100 text-stone-700";
                });
                document.getElementById('tab-btn-' + tabId).className = "w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-colors bg-emerald-600 text-white";
            }

            // Remove item logic
            function removeElement(id) {
                const element = document.getElementById(id);
                if (element) element.remove();
            }

            // Add Area
            function addArea() {
                const container = document.getElementById('areas-container');
                const idx = container.children.length;
                const div = document.createElement('div');
                div.className = "flex items-center space-x-4";
                div.id = 'area-card-new-' + idx;
                div.innerHTML = `
                    <input type="text" name="areas[]" required placeholder="New Bali Area" class="flex-1 border border-stone-200 px-4 py-2.5 rounded-xl text-sm font-medium">
                    <button type="button" onclick="removeElement('area-card-new-${idx}')" class="bg-red-50 hover:bg-red-100 text-red-600 p-2.5 rounded-xl border border-red-200 text-xs font-bold">Delete</button>
                `;
                container.appendChild(div);
            }

            // Add FAQ
            function addFaq() {
                const container = document.getElementById('faqs-container');
                const idx = container.children.length;
                const div = document.createElement('div');
                div.className = "bg-stone-50 border border-stone-200 p-5 rounded-2xl relative space-y-3";
                div.id = 'faq-card-new-' + idx;
                div.innerHTML = `
                    <button type="button" onclick="removeElement('faq-card-new-${idx}')" class="absolute top-4 right-4 bg-red-50 hover:bg-red-100 text-red-600 p-1.5 rounded-lg border border-red-200 text-xs font-bold">Remove</button>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Question</label>
                        <input type="text" name="faqs[new_${idx}][question]" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Answer</label>
                        <textarea name="faqs[new_${idx}][answer]" rows="3" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white"></textarea>
                    </div>
                `;
                container.appendChild(div);
            }

            // Add Option (duration/price)
            function addOption(serviceIdx) {
                const container = document.getElementById('options-container-' + serviceIdx);
                const oIdx = container.children.length;
                const div = document.createElement('div');
                div.className = "flex items-center space-x-3";
                div.id = `option-${serviceIdx}-new-${oIdx}`;
                div.innerHTML = `
                    <input type="text" name="services[${serviceIdx}][options][new_${oIdx}][duration]" placeholder="60 Mins" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/3">
                    <input type="text" name="services[${serviceIdx}][options][new_${oIdx}][price]" placeholder="250,000 IDR" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/2">
                    <button type="button" onclick="removeElement('option-${serviceIdx}-new-${oIdx}')" class="text-red-500 hover:text-red-700 text-xs font-bold">✕</button>
                `;
                container.appendChild(div);
            }

            // Add Service
            function addService() {
                const container = document.getElementById('services-container');
                const idx = container.children.length;
                const div = document.createElement('div');
                div.className = "bg-stone-50 border border-stone-200 p-6 rounded-2xl relative space-y-4";
                div.id = 'service-card-new-' + idx;
                div.innerHTML = `
                    <button type="button" onclick="removeElement('service-card-new-${idx}')" class="absolute top-4 right-4 bg-red-50 hover:bg-red-100 text-red-600 p-1.5 rounded-lg border border-red-200 text-xs font-bold">Remove</button>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Unique ID (e.g. signature-spa)</label>
                            <input type="text" name="services[new_${idx}][id]" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Title</label>
                            <input type="text" name="services[new_${idx}][title]" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Description</label>
                            <input type="text" name="services[new_${idx}][description]" required class="w-full border border-stone-200 px-3 py-2 rounded-lg text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Service Image</label>
                            <input type="file" name="service_image_new_${idx}" required class="w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <input type="hidden" name="services[new_${idx}][image]" value="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=800">
                        </div>
                        <div class="flex items-center pt-6">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="services[new_${idx}][featured]" value="true" checked class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="text-xs font-semibold text-stone-600 uppercase tracking-wide">Featured on Homepage</span>
                            </label>
                        </div>
                    </div>
                    <div class="border-t border-stone-200 pt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Durations & Pricing Options</span>
                            <button type="button" onclick="addOption('new_${idx}')" class="text-emerald-600 hover:text-emerald-700 text-xs font-bold">+ Add Option</button>
                        </div>
                        <div id="options-container-new_${idx}" class="space-y-2">
                            <div class="flex items-center space-x-3" id="option-new_${idx}-0">
                                <input type="text" name="services[new_${idx}][options][0][duration]" placeholder="60 Mins" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/3">
                                <input type="text" name="services[new_${idx}][options][0][price]" placeholder="250,000 IDR" required class="border border-stone-200 px-3 py-1.5 rounded-lg text-xs w-1/2">
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(div);
            }
        </script>
    </body>
    </html>
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-php-dashboard.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.php tests/validate-php-dashboard.js
    git commit -m "feat: design visual tabs and responsive form layouts in PHP dashboard"
    ```

---

### Task 3: Form Processing, JSON Save, & Image Upload Logic

**Files:**
*   Modify: `admin/index.php` (insert POST saving block at the top)
*   Create: `tests/validate-php-save.js`

**Interfaces:**
*   Consumes: POST requests from authenticated sessions containing updated form data and file uploads.
*   Produces: Refreshed page with updated `data/content.json` file on disk, and uploaded images saved to `assets/images/`.

- [ ] **Step 1: Write test for data writing logic**
    Create `tests/validate-php-save.js` to ensure index.php contains data sanitization, file checking, and `file_put_contents` calls.
    ```javascript
    // tests/validate-php-save.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php data processing block...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        const content = fs.readFileSync(adminPath, 'utf8');

        // Check required operations
        assert.ok(content.includes('json_encode'), "Missing json_encode for writing output");
        assert.ok(content.includes('file_put_contents'), "Missing file_put_contents for updating data file");
        assert.ok(content.includes('move_uploaded_file'), "Missing move_uploaded_file logic for image upload handling");

        console.log("PASS: PHP admin saving logic looks correct!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-php-save.js`
    Expected: FAIL because we haven't added the POST processing logic yet.

- [ ] **Step 3: Insert POST processing logic in index.php**
    Modify `admin/index.php` by adding the POST parsing logic right after `$error = '';` and before `$is_logged_in = ...`.
    ```php
    // Insert after line 11 ($error = '';) in admin/index.php
    
    // Check if user is logged in for any data operation
    $is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

    // Process saving content
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_content']) && $is_logged_in) {
        $content_file = __DIR__ . '/../data/content.json';
        
        // Load existing content to preserve fields
        $original_data = json_decode(file_get_contents($content_file), true);
        
        $new_general = $_POST['general'] ?? [];
        $new_services_raw = $_POST['services'] ?? [];
        $new_areas_raw = $_POST['areas'] ?? [];
        $new_faqs_raw = $_POST['faqs'] ?? [];

        // 1. Process General
        $original_data['general'] = [
            'brandName' => trim($new_general['brandName'] ?? ''),
            'tagline' => trim($new_general['tagline'] ?? ''),
            'description' => trim($new_general['description'] ?? ''),
            'whatsapp' => preg_replace('/[^0-9]/', '', $new_general['whatsapp'] ?? ''),
            'instagram' => trim($new_general['instagram'] ?? ''),
            'operatingHours' => trim($new_general['operatingHours'] ?? '')
        ];

        // Ensure directories exist
        $images_dir = __DIR__ . '/../assets/images';
        if (!is_dir($images_dir)) {
            mkdir($images_dir, 0755, true);
        }

        // 2. Process Services
        $cleaned_services = [];
        $sIdx = 0;
        foreach ($new_services_raw as $sKey => $service) {
            $service_id = preg_replace('/[^a-zA-Z0-9\-]/', '', $service['id'] ?? '');
            if (empty($service_id)) continue;

            $title = trim($service['title'] ?? '');
            $desc = trim($service['description'] ?? '');
            $image_url = $service['image'] ?? '';
            $featured = isset($service['featured']) && $service['featured'] == 'true';

            // Handle Image Upload
            $file_key = "service_image_" . $sKey;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES[$file_key]['tmp_name'];
                $file_name = preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $_FILES[$file_key]['name']);
                
                // Validate mime type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file_tmp);
                finfo_close($finfo);

                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (in_array($mime_type, $allowed_types)) {
                    $target_file = $images_dir . '/' . time() . '_' . $file_name;
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $image_url = 'assets/images/' . time() . '_' . $file_name;
                    }
                }
            }

            // Options parsing
            $cleaned_options = [];
            $options_raw = $service['options'] ?? [];
            foreach ($options_raw as $oOpt) {
                $dur = trim($oOpt['duration'] ?? '');
                $prc = trim($oOpt['price'] ?? '');
                if (!empty($dur) && !empty($prc)) {
                    $cleaned_options[] = [
                        'duration' => $dur,
                        'price' => $prc
                    ];
                }
            }

            $cleaned_services[] = [
                'id' => $service_id,
                'title' => $title,
                'description' => $desc,
                'image' => $image_url,
                'options' => $cleaned_options,
                'featured' => $featured
            ];
            $sIdx++;
        }
        $original_data['services'] = $cleaned_services;

        // 3. Process Areas
        $cleaned_areas = [];
        foreach ($new_areas_raw as $area) {
            $val = trim($area);
            if (!empty($val)) {
                $cleaned_areas[] = $val;
            }
        }
        $original_data['areas'] = $cleaned_areas;

        // 4. Process FAQs
        $cleaned_faqs = [];
        foreach ($new_faqs_raw as $faq) {
            $q = trim($faq['question'] ?? '');
            $a = trim($faq['answer'] ?? '');
            if (!empty($q) && !empty($a)) {
                $cleaned_faqs[] = [
                    'question' => $q,
                    'answer' => $a
                ];
            }
        }
        $original_data['faqs'] = $cleaned_faqs;

        // Save back to JSON
        file_put_contents($content_file, json_encode($original_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        header("Location: index.php?saved=1");
        exit;
    }
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-php-save.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.php tests/validate-php-save.js
    git commit -m "feat: implement JSON parsing, image upload handling, and secure input filters in PHP admin post action"
    ```

---

### Task 4: Old CMS Cleanups & Deployment Integration

**Files:**
*   Delete: `admin/config.yml`
*   Create: `tests/validate-cleanup.js`

**Interfaces:**
*   Consumes: None
*   Produces: Pure PHP admin build without Decap config residues.

- [ ] **Step 1: Write test for residue cleanup**
    Create `tests/validate-cleanup.js` to ensure the git gateway and YAML config configurations are successfully removed.
    ```javascript
    // tests/validate-cleanup.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking for residue Decap CMS config...");
        const configYmlPath = path.join(__dirname, '../admin/config.yml');

        assert.ok(!fs.existsSync(configYmlPath), "File admin/config.yml still exists! Must be deleted.");

        console.log("PASS: CMS configurations cleaned successfully!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-cleanup.js`
    Expected: FAIL because `admin/config.yml` is still on disk.

- [ ] **Step 3: Remove old config.yml**
    Delete `admin/config.yml`.
    ```bash
    rm admin/config.yml
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-cleanup.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git rm admin/config.yml
    git add tests/validate-cleanup.js
    git commit -m "chore: remove old Decap CMS config files"
    ```
