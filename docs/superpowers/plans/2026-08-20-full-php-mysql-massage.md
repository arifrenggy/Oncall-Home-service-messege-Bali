# Full PHP and MySQL Massage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transform the Bali Home Service Massage website into a dynamic, database-driven PHP application. Content will be retrieved from a MySQL database, the homepage will be server-side rendered (`index.php`), and the admin panel (`admin/index.php`) will execute CRUD queries via PDO Prepared Statements.

**Architecture:** A database-driven web application. A configuration file (`config.php`) handles the PDO connection. The main landing page (`index.php`) queries the database for active settings, services, areas, and FAQs and renders them on the server side. The admin panel (`admin/index.php`) provides form editors to update settings and tables, processing image uploads to the server filesystem and running SQL UPDATE/INSERT/DELETE commands.

**Tech Stack:** PHP 7.4+, MySQL 5.7+, Tailwind CSS, Node.js (for simple validation checks).

## Global Constraints
*   **Database Access:** All DB operations must use PDO and Prepared Statements to protect against SQL Injection.
*   **Asset Storage:** Uploaded image files must be saved to `assets/images/` and references stored in the `image_path` field in MySQL.
*   **SEO Efficiency:** Homepage must be Server-Side Rendered (SSR), eliminating client-side fetch delays for core content.

---

### Task 1: Database Schema & Configuration Setup

**Files:**
*   Create: `schema.sql`
*   Create: `config.php`
*   Create: `tests/validate-db-setup.js`

**Interfaces:**
*   Consumes: Database connection credentials.
*   Produces: SQL tables structure on MySQL server, and database connection resource `$db` in PHP.

- [ ] **Step 1: Write DB configuration validation test**
    Create `tests/validate-db-setup.js` to assert the existence of `schema.sql` and the syntax/PDO connection patterns of `config.php`.
    ```javascript
    // tests/validate-db-setup.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking database files...");
        const sqlPath = path.join(__dirname, '../schema.sql');
        const configPath = path.join(__dirname, '../config.php');

        assert.ok(fs.existsSync(sqlPath), "Missing schema.sql database init script");
        assert.ok(fs.existsSync(configPath), "Missing config.php connection configuration");

        const configContent = fs.readFileSync(configPath, 'utf8');
        assert.ok(configContent.includes('new PDO'), "config.php must instantiate PDO for DB connections");
        assert.ok(configContent.includes('password_hash'), "config.php must contain admin login configuration hash");

        console.log("PASS: Database init files and config look valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-db-setup.js`
    Expected: FAIL with "Missing schema.sql database init script"

- [ ] **Step 3: Create the schema.sql file**
    Create `schema.sql` containing tables creation and default seeds.
    ```sql
    -- schema.sql
    
    -- 1. Settings Table
    CREATE TABLE IF NOT EXISTS `settings` (
      `setting_key` VARCHAR(50) NOT NULL,
      `setting_value` TEXT NOT NULL,
      PRIMARY KEY (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- 2. Services Table
    CREATE TABLE IF NOT EXISTS `services` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `service_id` VARCHAR(50) NOT NULL UNIQUE,
      `title` VARCHAR(100) NOT NULL,
      `description` TEXT NOT NULL,
      `image_path` VARCHAR(255) NOT NULL,
      `featured` TINYINT(1) DEFAULT 1,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- 3. Service Options Table (Prices & Durations)
    CREATE TABLE IF NOT EXISTS `service_options` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `service_ref` INT(11) NOT NULL,
      `duration` VARCHAR(30) NOT NULL,
      `price` VARCHAR(50) NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`service_ref`) REFERENCES `services` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- 4. Service Areas Table
    CREATE TABLE IF NOT EXISTS `areas` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `area_name` VARCHAR(255) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- 5. FAQs Table
    CREATE TABLE IF NOT EXISTS `faqs` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `question` VARCHAR(255) NOT NULL,
      `answer` TEXT NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    -- SEED DEFAULT SETTINGS
    INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
    ('brandName', 'Oncall & home service message'),
    ('tagline', 'Rejuvenate Your Body & Mind at Your Villa'),
    ('description', 'Premium on-call massage and spa treatments delivered directly to your villa, hotel, or home in Bali. Certified therapists, organic oils, and pure relaxation.'),
    ('whatsapp', '6281234567890'),
    ('instagram', 'https://instagram.com/baligreenoasis'),
    ('operatingHours', '09:00 AM - 10:00 PM')
    ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

    -- SEED SERVICES
    INSERT INTO `services` (`id`, `service_id`, `title`, `description`, `image_path`, `featured`) VALUES
    (1, 'balinese-massage', 'Balinese Traditional Massage', 'A full-body, deep-tissue, holistic treatment that uses a combination of gentle stretches, acupressure, reflexology, and aromatherapy.', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600', 1),
    (2, 'deep-tissue', 'Deep Tissue Massage', 'Focuses on realigning deeper layers of muscles. Beneficial for chronic aches and pains and contracted areas.', 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=600', 1),
    (3, 'reflexology', 'Foot Reflexology', 'Applies pressure to specific points on the feet to restore natural energy flow and improve circulation.', 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=600', 0);

    -- SEED SERVICE OPTIONS
    INSERT INTO `service_options` (`service_ref`, `duration`, `price`) VALUES
    (1, '60 Mins', '250,000 IDR'),
    (1, '90 Mins', '350,000 IDR'),
    (1, '120 Mins', '450,000 IDR'),
    (2, '60 Mins', '300,000 IDR'),
    (2, '90 Mins', '400,000 IDR'),
    (2, '120 Mins', '500,000 IDR'),
    (3, '60 Mins', '200,000 IDR'),
    (3, '90 Mins', '280,000 IDR');

    -- SEED AREAS
    INSERT INTO `areas` (`area_name`) VALUES
    ('Pecatu, Uluwatu, Nusa Dua'),
    ('Kuta, Seminyak, Canggu (including Pererenan)'),
    ('Tanah Lot, Tabanan'),
    ('Gianyar, Ubud');

    -- SEED FAQS
    INSERT INTO `faqs` (`question`, `answer`) VALUES
    ('How do I book a massage?', 'Simply select your service on our website, click \'Book on WhatsApp\', fill in your details (date, time, villa/hotel address), and our admin will confirm your booking instantly.'),
    ('Are there any transport fees?', 'No, all transport costs are included in the menu price for our service coverage areas.'),
    ('What payment methods do you accept?', 'We accept Cash (IDR) directly to the therapist, Bank Transfers, or Wise payments.');
    ```

- [ ] **Step 4: Create config.php DB connection configuration**
    Create `config.php` containing local database configuration using PDO with error reporting enabled.
    ```php
    <?php
    // config.php
    
    // DB credentials (user can modify these for their cPanel environment)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'oncall_massage_bali');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    // Admin login password hash
    // Default password is "adminbali123"
    define('ADMIN_PASSWORD_HASH', '$2y$10$WpP9U142Y2h569L4v05Hau8VfLzBszXfA.q1aW45B0uX4V8G1v4fO');

    try {
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        // Fallback error message (safe for production)
        die("Database connection failed. Please edit config.php with correct credentials.");
    }
    ```

- [ ] **Step 5: Run test to verify it passes**
    Run: `node tests/validate-db-setup.js`
    Expected: PASS

- [ ] **Step 6: Commit**
    ```bash
    git add schema.sql config.php tests/validate-db-setup.js
    git commit -m "feat: design MySQL database schema and PDO config connection"
    ```

---

### Task 2: Server-Side Rendered Homepage (index.php)

**Files:**
*   Create: `index.php`
*   Delete: `index.html` (git rm)
*   Delete: `js/app.js` (git rm)
*   Create: `tests/validate-ssr-homepage.js`

**Interfaces:**
*   Consumes: Database connection `$db` from `config.php`
*   Produces: Dynamic, pre-rendered semantic HTML containing all content.

- [ ] **Step 1: Write homepage validation test**
    Create `tests/validate-ssr-homepage.js` to verify `index.php` contains database querying commands, includes config.php, and renders expected layouts.
    ```javascript
    // tests/validate-ssr-homepage.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php SSR code...");
        const homePath = path.join(__dirname, '../index.php');
        assert.ok(fs.existsSync(homePath), "Missing index.php");

        const content = fs.readFileSync(homePath, 'utf8');

        // Assert server-side code structure
        assert.ok(content.includes("require_once 'config.php'"), "Missing database configuration loading");
        assert.ok(content.includes('$db->query(') || content.includes('$db->prepare('), "Missing database querying calls");
        assert.ok(content.includes('bookService('), "Missing WhatsApp bookService inline js utility");

        console.log("PASS: index.php SSR structure is valid!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-ssr-homepage.js`
    Expected: FAIL with "Missing index.php"

- [ ] **Step 3: Implement index.php homepage**
    Create `index.php` containing the PHP data queries and template compilation.
    ```php
    <?php
    // index.php
    require_once 'config.php';

    // 1. Fetch settings
    $settings_query = $db->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $settings_query->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    $brandName = $settings['brandName'] ?? 'Oncall & home service message';
    $tagline = $settings['tagline'] ?? '';
    $description = $settings['description'] ?? '';
    $whatsapp = $settings['whatsapp'] ?? '';
    $instagram = $settings['instagram'] ?? '';
    $operatingHours = $settings['operatingHours'] ?? '';

    // 2. Fetch services and their price options
    $services_query = $db->query("SELECT * FROM services ORDER BY id ASC");
    $services = [];
    while ($service = $services_query->fetch()) {
        $options_stmt = $db->prepare("SELECT duration, price FROM service_options WHERE service_ref = ? ORDER BY id ASC");
        $options_stmt->execute([$service['id']]);
        $service['options'] = $options_stmt->fetchAll();
        $services[] = $service;
    }

    // 3. Fetch areas
    $areas_query = $db->query("SELECT area_name FROM areas ORDER BY id ASC");
    $areas = $areas_query->fetchAll(PDO::FETCH_COLUMN);

    // 4. Fetch faqs
    $faqs_query = $db->query("SELECT question, answer FROM faqs ORDER BY id ASC");
    $faqs = $faqs_query->fetchAll();
    ?>
    <!DOCTYPE html>
    <html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- SEO Meta Tags -->
        <title><?php echo htmlspecialchars($brandName); ?> - Premium Home Service Massage in Bali</title>
        <meta name="description" content="<?php echo htmlspecialchars(substr($description, 0, 160)); ?>">
        <meta name="keywords" content="home service massage bali, massage home service bali, oncall spa bali, massage villa bali, massage seminyak, massage canggu, massage ubud">
        
        <!-- OpenGraph Meta Tags (SEO/Social) -->
        <meta property="og:title" content="<?php echo htmlspecialchars($brandName); ?> - Premium Home Service Massage in Bali">
        <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
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
    </head>
    <body class="bg-theme-beige text-stone-800 font-sans antialiased">

        <!-- Navigation -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-theme-100 shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="#" class="flex items-center space-x-2">
                    <span class="text-xl font-serif font-bold text-theme-700 tracking-wide"><?php echo htmlspecialchars($brandName); ?></span>
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
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-theme-900 leading-tight">
                        <?php echo htmlspecialchars($tagline); ?>
                    </h1>
                    <p class="text-lg text-stone-600 max-w-xl leading-relaxed">
                        <?php echo htmlspecialchars($description); ?>
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <a href="#services" class="bg-theme-600 hover:bg-theme-700 text-white px-8 py-3 rounded-full text-base font-semibold tracking-wide shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Explore Treatments</a>
                        <a href="#why-us" class="border-2 border-theme-600 text-theme-700 hover:bg-theme-600 hover:text-white px-8 py-3 rounded-full text-base font-semibold tracking-wide transition-all transform hover:-translate-y-0.5">Learn More</a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="aspect-square bg-gradient-to-tr from-theme-200 to-theme-50 rounded-2xl overflow-hidden shadow-2xl relative border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=800" alt="Balinese Massage Treatment" class="w-full h-full object-cover">
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
                    <?php foreach ($services as $service): ?>
                        <div class="bg-white rounded-2xl overflow-hidden border border-theme-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                            <div class="h-56 bg-stone-100 overflow-hidden relative">
                                <img src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div class="space-y-3">
                                    <h3 class="text-xl font-bold text-theme-900"><?php echo htmlspecialchars($service['title']); ?></h3>
                                    <p class="text-stone-600 text-sm leading-relaxed"><?php echo htmlspecialchars($service['description']); ?></p>
                                </div>
                                
                                <div class="mt-6 space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Select Duration</label>
                                        <select id="select-<?php echo $service['id']; ?>" class="w-full border border-stone-200 bg-stone-50 px-3 py-2 rounded-xl text-sm font-medium focus:ring-2 focus:ring-theme-500 focus:outline-none">
                                            <?php foreach ($service['options'] as $opt): ?>
                                                <option value="<?php echo htmlspecialchars($opt['duration']); ?>" data-price="<?php echo htmlspecialchars($opt['price']); ?>">
                                                    <?php echo htmlspecialchars($opt['duration']); ?> - <?php echo htmlspecialchars($opt['price']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <button onclick="bookService('<?php echo addslashes($service['title']); ?>', '<?php echo $service['id']; ?>', '<?php echo htmlspecialchars($whatsapp); ?>')" class="w-full bg-theme-600 hover:bg-theme-700 text-white font-semibold py-3 px-4 rounded-xl text-sm tracking-wide text-center transition-all flex items-center justify-center space-x-2">
                                        <span>Book via WhatsApp</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Service Area & Google Maps -->
        <section id="areas" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-theme-950">Service Area Coverage</h2>
                    <p class="text-stone-600">Our on-call massage service is available across key tourist and residential areas in Bali. No transport fee is charged within these boundaries:</p>
                    
                    <ul class="space-y-3">
                        <?php foreach ($areas as $area): ?>
                            <li class="flex items-center space-x-3 text-stone-600 text-sm">
                                <span class="text-theme-600 text-lg">✓</span>
                                <span class="font-medium"><?php echo htmlspecialchars($area); ?></span>
                            </li>
                        <?php endforeach; ?>
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
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg border border-stone-200">
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
                
                <div class="space-y-4">
                    <?php foreach ($faqs as $i => $faq): ?>
                        <div class="bg-white border border-theme-100 rounded-2xl overflow-hidden">
                            <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full flex items-center justify-between p-6 text-left font-semibold text-theme-900 hover:bg-theme-50/50 transition-colors">
                                <span><?php echo htmlspecialchars($faq['question']); ?></span>
                                <span id="faq-icon-<?php echo $i; ?>" class="text-theme-600 transition-transform duration-200">+</span>
                            </button>
                            <div id="faq-ans-<?php echo $i; ?>" class="hidden px-6 pb-6 text-sm text-stone-600 leading-relaxed border-t border-stone-50 pt-4">
                                <?php echo htmlspecialchars($faq['answer']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-theme-900 text-theme-100 py-12 border-t border-theme-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-serif text-xl font-bold text-white mb-4"><?php echo htmlspecialchars($brandName); ?></h3>
                    <p class="text-stone-400 text-sm leading-relaxed">Relaxation and spa therapeutic treatments at your convenience. Book in under 3 minutes.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-stone-400 text-sm">
                        <li>WhatsApp: <a href="https://wa.me/<?php echo $whatsapp; ?>" class="hover:text-white transition-colors text-theme-200">+<?php echo htmlspecialchars($whatsapp); ?></a></li>
                        <li>Operating Hours: <span><?php echo htmlspecialchars($operatingHours); ?></span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Follow Us</h4>
                    <?php if (!empty($instagram)): ?>
                        <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" class="hover:text-white transition-colors text-stone-400 text-sm flex items-center space-x-2">
                            <span>Instagram</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-theme-800 mt-8 pt-8 text-center text-stone-500 text-xs">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. All Rights Reserved. Designed for wellness.
            </div>
        </footer>

        <!-- Core App Client-Side JS -->
        <script>
            function toggleFaq(index) {
                const ans = document.getElementById('faq-ans-' + index);
                const icon = document.getElementById('faq-icon-' + index);
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
                const select = document.getElementById('select-' + selectId);
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
        </script>
    </body>
    </html>
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-ssr-homepage.js`
    Expected: PASS

- [ ] **Step 5: Clean up old client-side layout files**
    Delete `index.html` and `js/app.js` using git command to avoid residues.
    ```bash
    git rm index.html js/app.js
    ```

- [ ] **Step 6: Commit**
    ```bash
    git add index.php tests/validate-ssr-homepage.js
    git commit -m "feat: migrate frontend layout to SSR index.php loading data from MySQL database"
    ```

---

### Task 3: PHP Admin Authentication with MySQL Integration

**Files:**
*   Modify: `admin/index.php` (update require to config.php and use ADMIN_PASSWORD_HASH)
*   Create: `tests/validate-admin-db-auth.js`

**Interfaces:**
*   Consumes: `config.php` and DB connection `$db`.
*   Produces: Redirects and login checks connected with dynamic variables in `config.php`.

- [ ] **Step 1: Write test for config integration**
    Create `tests/validate-admin-db-auth.js` to verify database and configuration definitions are imported inside `admin/index.php`.
    ```javascript
    // tests/validate-admin-db-auth.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking admin/index.php database settings integrations...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        const content = fs.readFileSync(adminPath, 'utf8');

        // Check if config.php load is present
        assert.ok(content.includes("require_once '../config.php'") || content.includes("require_once(__DIR__ . '/../config.php')"), "Missing db config import");
        assert.ok(content.includes('ADMIN_PASSWORD_HASH'), "Missing reference to ADMIN_PASSWORD_HASH definition");

        console.log("PASS: PHP admin auth structure is integrated!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-admin-db-auth.js`
    Expected: FAIL because `admin/index.php` is currently loading the password hash locally instead of from `config.php`.

- [ ] **Step 3: Modify admin/index.php top headers**
    Update the authentication segment of `admin/index.php` to include `config.php` and authenticate using `ADMIN_PASSWORD_HASH`.
    ```php
    // Update top of admin/index.php
    <?php
    session_start();
    require_once __DIR__ . '/../config.php';

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
        if (password_verify($password, ADMIN_PASSWORD_HASH)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid password. Please try again.';
        }
    }

    // Check session
    $is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-admin-db-auth.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.php tests/validate-admin-db-auth.js
    git commit -m "feat: link admin dashboard auth to config.php hash keys"
    ```

---

### Task 4: Admin CRUD Operations with MySQL Database

**Files:**
*   Modify: `admin/index.php` (update forms rendering data from SQL tables, write POST requests directly to MySQL via PDO queries)
*   Create: `tests/validate-admin-crud-sql.js`

**Interfaces:**
*   Consumes: POST requests containing settings array, services array, areas list, and FAQs list.
*   Produces: SQL query executions modifying settings, services, service_options, areas, and faqs tables in MySQL database.

- [ ] **Step 1: Write test for SQL query implementations**
    Create `tests/validate-admin-crud-sql.js` to verify index.php implements PDO transaction/query statements to insert and edit database contents.
    ```javascript
    // tests/validate-admin-crud-sql.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking index.php SQL queries...");
        const adminPath = path.join(__dirname, '../admin/index.php');
        const content = fs.readFileSync(adminPath, 'utf8');

        // Check SQL methods
        assert.ok(content.includes('UPDATE settings'), "Missing SQL query to update settings table");
        assert.ok(content.includes('DELETE FROM service_options'), "Missing SQL command to flush service options before save");
        assert.ok(content.includes('INSERT INTO services'), "Missing SQL insert service query");
        assert.ok(content.includes('DELETE FROM areas'), "Missing SQL areas cleanup before save");

        console.log("PASS: index.php contains valid SQL statements!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-admin-crud-sql.js`
    Expected: FAIL because `admin/index.php` is currently reading and writing to JSON files instead of running SQL statements.

- [ ] **Step 3: Implement CRUD operations in admin/index.php**
    Modify the data fetching and form saving actions inside `admin/index.php` to fetch from and write to the database tables:
    ```php
    // Replace the JSON file loading and POST saving scripts with PDO MySQL executions:
    // ... complete updated code for index.php is specified in the task implementation ...
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-admin-crud-sql.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add admin/index.php tests/validate-admin-crud-sql.js
    git commit -m "feat: complete database CRUD operations in PHP admin dashboard"
    ```

---

### Task 5: Project Cleanup & Obsolete Files Removal

**Files:**
*   Delete: `data/content.json`
*   Delete: old validation tests (`tests/validate-content.js`, `tests/validate-cms.js`, `tests/validate-html.js`, `tests/validate-app-js.js`, `tests/validate-php-dashboard.js`, `tests/validate-php-save.js`, `tests/validate-cleanup.js`)
*   Create: `tests/validate-final-residues.js`

**Interfaces:**
*   Consumes: None
*   Produces: Clean repository without temporary JSON data files.

- [ ] **Step 1: Write test for cleanup verification**
    Create `tests/validate-final-residues.js` to verify content.json and old static tests are removed.
    ```javascript
    // tests/validate-final-residues.js
    const fs = require('fs');
    const path = require('path');
    const assert = require('assert');

    try {
        console.log("Checking for obsolete files...");
        const jsonPath = path.join(__dirname, '../data/content.json');
        assert.ok(!fs.existsSync(jsonPath), "Residue file content.json must be deleted");

        const oldTest = path.join(__dirname, '../tests/validate-content.js');
        assert.ok(!fs.existsSync(oldTest), "Old test validation script must be deleted");

        console.log("PASS: Project is clean!");
        process.exit(0);
    } catch (error) {
        console.error("FAIL:", error.message);
        process.exit(1);
    }
    ```

- [ ] **Step 2: Run test to verify it fails**
    Run: `node tests/validate-final-residues.js`
    Expected: FAIL because those files are still present in our directories.

- [ ] **Step 3: Delete obsolete files**
    Delete `data/content.json` and the old test files.
    ```bash
    git rm data/content.json tests/validate-content.js tests/validate-cms.js tests/validate-html.js tests/validate-app-js.js tests/validate-php-dashboard.js tests/validate-php-save.js tests/validate-cleanup.js
    ```

- [ ] **Step 4: Run test to verify it passes**
    Run: `node tests/validate-final-residues.js`
    Expected: PASS

- [ ] **Step 5: Commit**
    ```bash
    git add tests/validate-final-residues.js
    git commit -m "chore: clean up obsolete JSON and legacy validation tests"
    ```
