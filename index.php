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

    <!-- Google Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                            gold: '#AE7D64',
                            beige: '#ffffff',
                        },
                        emerald: {
                            50: '#f2f5f7',
                            100: '#e5eaf0',
                            200: '#cdd7e3',
                            300: '#a3b7d1',
                            400: '#2872fa', // Clean Royal Blue
                            500: '#2872fa', // Clean Royal Blue
                            600: '#2872fa', // Clean Royal Blue
                            700: '#1d4ed8',
                            800: '#AE7D64', // Terracotta/Copper Gold for primary CTAs
                            900: '#192a3d', // Navy Blue
                            950: '#192a3d', // Navy Blue
                        },
                        amber: {
                            50: '#f7f4f2', // Light cream terracotta tint
                            100: '#f7f4f2',
                            200: '#ebdcd3',
                            300: '#d7b9a7',
                            400: '#AE7D64', // Terracotta/Copper Gold
                            500: '#AE7D64', // Terracotta/Copper Gold
                            600: '#AE7D64', // Terracotta/Copper Gold
                            700: '#91624a',
                            800: '#734e3a',
                            900: '#5a3d2e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .font-serif { font-family: 'Poppins', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-theme-beige text-stone-800 font-sans antialiased">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center space-x-2">
                <span class="text-2xl font-serif font-bold text-emerald-900 tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></span>
            </a>
            <div class="hidden md:flex space-x-8 text-sm font-semibold tracking-wide text-stone-600 uppercase">
                <a href="#services" class="hover:text-emerald-800 transition-colors">Services</a>
                <a href="#why-us" class="hover:text-emerald-800 transition-colors">Why Choose Us</a>
                <a href="#areas" class="hover:text-emerald-800 transition-colors">Service Areas</a>
                <a href="#faqs" class="hover:text-emerald-800 transition-colors">FAQs</a>
            </div>
            <a href="#services" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Book Now</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden bg-emerald-950 text-white pt-32 pb-24">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-950 via-emerald-950 to-emerald-900/90"></div>
            <!-- Large floating glyph for art-gallery styling -->
            <div class="absolute -right-16 top-12 text-[26rem] font-serif text-emerald-900/10 select-none pointer-events-none leading-none">S</div>
            <div class="absolute -left-16 bottom-12 text-[26rem] font-serif text-emerald-900/10 select-none pointer-events-none leading-none">O</div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center flex flex-col items-center">
            <div class="max-w-4xl space-y-8">
                <span class="inline-flex items-center space-x-2 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-semibold px-5 py-2 rounded-full text-xs uppercase tracking-widest">
                    <i class="fas fa-crown text-[10px] mr-1"></i> Luxe Wellness Coming to You
                </span>
                <h1 class="text-5xl sm:text-6xl lg:text-8xl font-serif font-bold text-white leading-tight tracking-tight">
                    <?php echo htmlspecialchars($tagline); ?>
                </h1>
                <p class="text-base sm:text-lg text-emerald-100/70 max-w-xl mx-auto leading-relaxed font-light">
                    <?php echo htmlspecialchars($description); ?>
                </p>
                <div class="pt-4 flex flex-wrap justify-center gap-6">
                    <a href="#services" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 px-10 py-4 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-spa mr-2"></i> Explore Treatments
                    </a>
                    <a href="#why-us" class="border-2 border-emerald-500 hover:bg-emerald-500 hover:text-white px-10 py-4 rounded-full text-xs font-bold uppercase tracking-widest transition-all transform hover:-translate-y-0.5 text-emerald-400">
                        Our Philosophy
                    </a>
                </div>
            </div>

            <!-- Staggered Floating Gallery Wall (Lookbook Style) -->
            <div class="flex items-center justify-center -space-x-12 sm:-space-x-16 md:-space-x-24 pt-20 w-full max-w-5xl z-10 relative">
                <!-- Left Image (Small, tilted left) -->
                <div class="w-36 h-56 sm:w-48 sm:h-72 rounded-t-full overflow-hidden shadow-2xl -rotate-6 transform -translate-y-8 border border-amber-500/20 flex-shrink-0 transition-transform duration-500 hover:rotate-0">
                    <img src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=400" alt="Spa Room" class="w-full h-full object-cover">
                </div>
                <!-- Center Image (Largest, main arch) -->
                <div class="w-48 h-72 sm:w-72 sm:h-[420px] rounded-t-full overflow-hidden shadow-2xl z-20 border-4 border-amber-500/30 flex-shrink-0 transition-transform duration-500 hover:scale-[1.03]">
                    <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=800" alt="Massage Treatment" class="w-full h-full object-cover">
                </div>
                <!-- Right Image (Medium, tilted right) -->
                <div class="w-40 h-64 sm:w-56 sm:h-80 rounded-t-full overflow-hidden shadow-2xl rotate-6 transform translate-y-8 border border-amber-500/20 flex-shrink-0 transition-transform duration-500 hover:rotate-0">
                    <img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=400" alt="Relaxation Spa" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-32 bg-white relative overflow-hidden">
        <div class="absolute right-0 bottom-0 text-[20rem] font-serif text-stone-50 select-none pointer-events-none leading-none -mb-20">ZEN</div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600"><i class="fas fa-seedling mr-1"></i> Our Core Philosophy</span>
                <h2 class="text-4xl sm:text-5xl font-serif font-bold text-stone-900 leading-tight">Indulge in Premium Wellness</h2>
                <p class="text-stone-500">We prioritize your health, comfort, and peace of mind. Here is why clients choose us.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12 mt-24 pb-12 items-stretch">
                <!-- Card 1: Staggered Up -->
                <div class="bg-theme-beige p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:-translate-y-6 hover:-translate-y-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 text-lg mb-8">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">Certified Therapists</h3>
                        <p class="text-stone-600 text-sm leading-relaxed font-light">Our female therapists are fully trained, certified, and experienced in professional spa treatments and anatomy.</p>
                    </div>
                </div>
                
                <!-- Card 2: Main Highlight Card -->
                <div class="bg-white p-10 rounded-3xl border-2 border-amber-500/30 shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex flex-col justify-between relative">
                    <span class="absolute -top-3 right-6 bg-amber-500 text-stone-950 text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                        Premium Choice
                    </span>
                    <div>
                        <div class="w-12 h-12 bg-emerald-950 text-amber-500 rounded-2xl flex items-center justify-center text-lg mb-8 border border-amber-500/30">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">100% Organic Oils</h3>
                        <p class="text-stone-600 text-sm leading-relaxed font-light">We use only organic, virgin coconut oil and premium essential oils to nourish your skin and enhance relaxation.</p>
                    </div>
                </div>
                
                <!-- Card 3: Staggered Down -->
                <div class="bg-theme-beige p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:translate-y-6 hover:translate-y-4 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 text-lg mb-8">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">No Transport Fee</h3>
                        <p class="text-stone-600 text-sm leading-relaxed font-light">No hidden charges. Our massage prices include all transport costs directly to your villa, hotel, or apartment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-theme-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-stone-900">Our Massage Menu</h2>
                <p class="text-stone-500">Pick from our carefully selected list of authentic Balinese spa therapies. Book easily on WhatsApp.</p>
            </div>
            
            <div id="services-list" class="space-y-32 mt-24">
                <?php foreach ($services as $index => $service): 
                    $isEven = ($index % 2 == 0);
                    $directionClass = $isEven ? 'md:flex-row' : 'md:flex-row-reverse';
                    $alignTextClass = $isEven ? 'md:text-left md:items-start md:pl-16' : 'md:text-right md:items-end md:pr-16';
                ?>
                    <div class="flex flex-col <?php echo $directionClass; ?> items-center gap-12 md:gap-0 relative">
                        <!-- Image Container with Archway Masking -->
                        <div class="w-full md:w-1/2 relative z-0 flex-shrink-0">
                            <!-- Background shadow glow for premium spa feeling -->
                            <div class="absolute -inset-4 bg-amber-500/5 rounded-t-full blur-2xl pointer-events-none"></div>
                            
                            <div class="aspect-[4/5] sm:aspect-[3/4] md:h-[500px] w-full max-w-md mx-auto rounded-t-full overflow-hidden border-2 border-amber-500/20 shadow-2xl relative group">
                                <?php if ($service['featured']): ?>
                                    <span class="absolute top-6 left-6 bg-amber-500 text-stone-950 text-[9px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md z-10">
                                        <i class="fas fa-crown mr-1"></i> Featured Choice
                                    </span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out">
                            </div>
                        </div>

                        <!-- Content Description Container Overlapping with Image -->
                        <div class="w-full md:w-1/2 flex flex-col justify-center items-center <?php echo $alignTextClass; ?> z-10 relative -mt-16 md:mt-0 px-4 sm:px-8">
                            <div class="bg-white/95 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-stone-100 shadow-xl max-w-md space-y-6">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-600">Premium treatment</span>
                                <h3 class="text-3xl font-serif font-bold text-stone-900 leading-tight"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-stone-500 text-sm leading-relaxed font-light"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <div class="border-t border-stone-100 pt-6 space-y-6">
                                    <div class="text-left">
                                        <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Select Session Duration</label>
                                        <select id="select-<?php echo $service['id']; ?>" class="w-full border border-stone-200 bg-stone-50 px-4 py-3.5 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-800 focus:outline-none">
                                            <?php foreach ($service['options'] as $opt): ?>
                                                <option value="<?php echo htmlspecialchars($opt['duration']); ?>" data-price="<?php echo htmlspecialchars($opt['price']); ?>">
                                                    <?php echo htmlspecialchars($opt['duration']); ?> - <?php echo htmlspecialchars($opt['price']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <button onclick="bookService('<?php echo addslashes($service['title']); ?>', '<?php echo $service['id']; ?>', '<?php echo htmlspecialchars($whatsapp); ?>')" class="w-full bg-emerald-800 hover:bg-emerald-950 text-white font-bold py-4 px-6 rounded-2xl text-xs uppercase tracking-widest text-center transition-all duration-300 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                        <span>Reserve via WhatsApp</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Service Area & Google Maps -->
    <section id="areas" class="py-32 bg-stone-50 relative overflow-hidden">
        <!-- Giant background text -->
        <div class="absolute left-10 top-1/2 -translate-y-1/2 text-[22rem] font-serif text-stone-100/70 select-none pointer-events-none leading-none z-0">BALI</div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <!-- Areas List Card (Overlapping float) -->
            <div class="lg:col-span-6 z-10 relative md:-mr-12">
                <div class="bg-white/95 backdrop-blur-md p-10 rounded-3xl border border-stone-100 shadow-2xl space-y-8">
                    <div class="space-y-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-amber-600"><i class="fas fa-map-marked-alt mr-1"></i> Coverage Zones</span>
                        <h2 class="text-4xl font-serif font-bold text-stone-900 leading-tight">Service Area Coverage</h2>
                        <p class="text-stone-500 text-sm leading-relaxed font-light">Our on-call massage service is available across key tourist and residential areas in Bali. No transport fee is charged within these boundaries:</p>
                    </div>
                    
                    <ul class="grid sm:grid-cols-2 gap-4">
                        <?php foreach ($areas as $area): ?>
                            <li class="flex items-center text-stone-700 text-sm">
                                <i class="fas fa-check-circle text-amber-500 mr-3 text-base flex-shrink-0"></i>
                                <span class="font-medium text-left"><?php echo htmlspecialchars($area); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="bg-theme-50 p-6 rounded-2xl border border-theme-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-600 text-lg flex-shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-bold text-stone-900 text-sm">Villa/Hotel/Home Panggilan</h4>
                            <p class="text-stone-500 text-xs leading-relaxed font-light">Our therapists arrive with massage tables/mats, professional massage oils, linen, and relaxing music setup.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Google Maps Embed with Archway Masking -->
            <div class="lg:col-span-6 relative z-0">
                <div class="aspect-[4/3] rounded-t-full md:rounded-t-[200px] rounded-b-3xl overflow-hidden shadow-2xl border-4 border-amber-500/20 relative">
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
        </div>
    </section>

    <!-- FAQs Section -->
    <section id="faqs" class="py-20 bg-theme-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-stone-900">Frequently Asked Questions</h2>
                <p class="text-stone-500">Everything you need to know about our Bali home massage services.</p>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($faqs as $i => $faq): ?>
                    <div class="bg-white border border-stone-100 rounded-2xl overflow-hidden">
                        <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full flex items-center justify-between p-6 text-left font-semibold text-stone-900 hover:bg-theme-50/50 transition-colors">
                            <span><?php echo htmlspecialchars($faq['question']); ?></span>
                            <i id="faq-icon-<?php echo $i; ?>" class="fas fa-chevron-down text-amber-500 text-xs transition-transform duration-300"></i>
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
    <footer class="bg-emerald-950 text-emerald-100 py-16 border-t border-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-12">
            <div class="space-y-4">
                <h3 class="font-serif text-2xl font-bold text-white tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></h3>
                <p class="text-emerald-100/60 text-sm leading-relaxed font-light">Relaxation and spa therapeutic treatments at your convenience. Book in under 3 minutes.</p>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-white uppercase tracking-wider text-xs">Contact Info</h4>
                <ul class="space-y-3 text-emerald-100/60 text-sm">
                    <li><i class="fab fa-whatsapp text-amber-500 mr-2 text-base"></i> WhatsApp: <a href="https://wa.me/<?php echo $whatsapp; ?>" class="hover:text-white transition-colors font-medium text-amber-400">+<?php echo htmlspecialchars($whatsapp); ?></a></li>
                    <li><i class="far fa-clock text-amber-500 mr-2 text-base"></i> Operating Hours: <span><?php echo htmlspecialchars($operatingHours); ?></span></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-white uppercase tracking-wider text-xs">Follow Us</h4>
                <?php if (!empty($instagram)): ?>
                    <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" class="hover:text-amber-400 transition-colors text-emerald-100/60 text-sm flex items-center space-x-2">
                        <i class="fab fa-instagram text-lg text-amber-500"></i>
                        <span>Instagram</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-emerald-900 mt-12 pt-8 text-center text-emerald-100/40 text-xs">
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
            document.querySelectorAll("[id^='faq-icon-']").forEach(el => {
                el.classList.remove('rotate-180');
            });

            if (isHidden) {
                ans.classList.remove('hidden');
                icon.classList.add('rotate-180');
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
