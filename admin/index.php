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

// ==========================================
// Authenticated Dashboard View Starts Here
// ==========================================
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
        // Checks if element exists and removes it from UI
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
