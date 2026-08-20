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

    <!-- Google Fonts: Cormorant Garamond & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            gold: '#d4af37',
                            beige: '#faf8f5',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .font-serif { font-family: 'Cormorant Garamond', serif; }
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
