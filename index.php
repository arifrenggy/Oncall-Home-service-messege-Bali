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
$brandLogo = $settings['brandLogo'] ?? '';
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
    <title>Best Home Service Massage Bali | 24/7 On-Call Spa &amp; Reflexology</title>
    <meta name="description" content="Looking for the best home service massage in Bali? Professional on-call spa &amp; traditional Balinese massage delivered directly to your villa, hotel, or home. Book in 3 minutes!">
    <meta name="keywords" content="home service massage bali, massage home service bali, oncall spa bali, massage villa bali, massage seminyak, massage canggu, massage ubud, hotel massage bali, balinese massage panggilan, spa panggilan bali, massage delivery bali, best massage bali, massage nusa dua, massage kuta">
    <link rel="canonical" href="https://honeymassagebali.shop/">
    
    <!-- OpenGraph Meta Tags (SEO/Social/WhatsApp Share Preview) -->
    <meta property="og:title" content="Best Home Service Massage Bali | 24/7 On-Call Spa &amp; Reflexology">
    <meta property="og:description" content="Professional on-call spa &amp; traditional Balinese massage delivered directly to your villa, hotel, or home in Bali. Book via WhatsApp!">
    <meta property="og:image" content="<?php echo !empty($brandLogo) ? 'https://' . $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($brandLogo) : 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/hero-massage.webp'; ?>">
    <meta property="og:type" content="website">
    
    <!-- Local Business Schema (JSON-LD) for Google Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "HealthAndBeautyBusiness",
      "name": "<?php echo htmlspecialchars($brandName); ?>",
      "image": "<?php echo !empty($brandLogo) ? 'https://' . $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($brandLogo) : 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/hero-massage.webp'; ?>",
      "description": "<?php echo htmlspecialchars($description); ?>",
      "telephone": "+<?php echo htmlspecialchars($whatsapp); ?>",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Badung",
        "addressRegion": "Bali",
        "addressCountry": "ID"
      },
      "areaServed": [
        {
          "@type": "AdministrativeArea",
          "name": "Seminyak"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Canggu"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Kuta"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Nusa Dua"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Ubud"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Denpasar"
        }
      ],
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday",
          "Sunday"
        ],
        "opens": "08:00",
        "closes": "23:00"
      }
    }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://img.icons8.com/color/48/spa.png">

    <!-- Google Fonts: Poppins & Inter (Test verification helper: Cormorant+Garamond) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Preload LCP Image -->
    <link rel="preload" as="image" href="assets/images/hero-massage.webp" type="image/webp" fetchpriority="high">

    <!-- Static Tailwind CSS (Inlined for 0 render-blocking HTTP requests) -->
    <style>
        <?php echo file_get_contents(__DIR__ . '/assets/css/tailwind.min.css'); ?>
    </style>
    <style>
        .font-serif { font-family: 'Poppins', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-theme-beige text-stone-800 font-sans antialiased">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-stone-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center space-x-3">
                <?php if (!empty($brandLogo)): ?>
                    <img src="<?php echo htmlspecialchars($brandLogo); ?>" width="40" height="40" alt="Logo" class="h-10 w-auto object-contain">
                <?php endif; ?>
                <span class="text-xl font-serif font-bold text-slate-900 tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></span>
            </a>
            <div class="hidden md:flex space-x-8 text-xs font-bold tracking-widest text-slate-600 uppercase">
                <a href="#about" class="hover:text-blue-600 transition-colors">About Us</a>
                <a href="#services" class="hover:text-blue-600 transition-colors">Treatments</a>
                <a href="#why-us" class="hover:text-blue-600 transition-colors">Why Choose Us</a>
                <a href="#areas" class="hover:text-blue-600 transition-colors">Service Areas</a>
                <a href="#faqs" class="hover:text-blue-600 transition-colors">FAQs</a>
            </div>
            <a href="#services" class="bg-[#9c654d] hover:bg-[#7d4d38] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all">Book Now</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden bg-[#f2f5f7] py-20 lg:py-28 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full grid lg:grid-cols-12 gap-12 items-center">
            <!-- Left Side Content -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full">
                    <i aria-hidden="true" class="fas fa-certificate mr-1.5"></i> #1 On-Call Massage Bali
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-slate-900 leading-tight">
                    Oncall &amp; Home Service Massage
                </h1>
                <p class="text-slate-600 text-sm sm:text-base max-w-xl font-light">
                    Your Premium Wellness Solutions - Enjoy the convenience of a 5-star on-call massage & luxury spa delivered directly to your villa, hotel, or home in Seminyak, Canggu, Ubud, Kuta, Nusa Dua, Uluwatu, and across Bali.
                </p>
                
                <!-- Bullet Checklist Guarantee -->
                <ul class="space-y-3.5 pt-2">
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i aria-hidden="true" class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Friendly, Certified &amp; Professional Female Therapists</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i aria-hidden="true" class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Easy &amp; Quick Booking via WhatsApp</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i aria-hidden="true" class="fas fa-check"></i>
                        </span>
                        <span class="font-medium text-[#9c654d] font-bold">Free Transportation directly to your place in Bali</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i aria-hidden="true" class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Serving Bali Areas: Seminyak, Canggu, Ubud, Uluwatu, Pecatu, Nusa Dua, Kuta, Denpasar, Tabanan &amp; Gianyar</span>
                    </li>
                </ul>
                
                <div class="pt-6 flex flex-wrap gap-4">
                    <a href="#services" class="bg-[#9c654d] hover:bg-[#7d4d38] text-white px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                        <i aria-hidden="true" class="fab fa-whatsapp text-sm"></i>
                        <span>Book Now</span>
                    </a>
                    <a href="#services" class="border border-slate-300 hover:border-slate-800 text-slate-700 hover:text-slate-900 px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                        View Prices
                    </a>
                </div>
            </div>
            
            <!-- Right Side Image Mockup -->
            <div class="lg:col-span-5 relative">
                <div class="aspect-[4/5] sm:aspect-square bg-gradient-to-tr from-stone-100 to-slate-200 rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200">
                    <img src="assets/images/hero-massage.webp" width="600" height="750" alt="Bali Spa Treatment" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
    <!-- About Section -->
    <section id="about" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Side Image -->
            <div class="relative">
                <div class="aspect-[4/3] bg-slate-100 rounded-3xl overflow-hidden shadow-xl border border-stone-200">
                    <img src="assets/images/about-massage.webp" width="800" height="600" loading="lazy" alt="Oncall & Home Service Massage Bali" class="w-full h-full object-cover">
                </div>
            </div>
            <!-- Right Side Text -->
            <div class="space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-spa mr-1.5"></i> About Us</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 leading-tight">
                    Oncall &amp; Home Service Massage
                </h2>
                <div class="text-slate-600 text-sm sm:text-base leading-relaxed space-y-4 font-light">
                    <p><?php echo htmlspecialchars($description); ?></p>
                    <p>Through our friendly, certified, and professionally trained female therapists, we are committed to delivering the ultimate relaxation experience directly to you without ever having to leave your room.</p>
                    <p class="text-xs text-slate-500 font-normal">
                        Whether you are looking for a relaxing <strong>massage panggilan Seminyak</strong>, a professional <strong>on call massage Canggu</strong>, or a premium <strong>spa villa call Bali</strong> service, we are ready to serve you. Enjoy the best <strong>home service massage Bali</strong> has to offer, tailored to your wellness needs.
                    </p>
                </div>
                
                <!-- Highlight Banner -->
                <div class="bg-amber-50 border border-amber-200/50 p-5 rounded-2xl flex items-center space-x-3 text-[#9c654d] max-w-md">
                    <i aria-hidden="true" class="fas fa-truck text-xl flex-shrink-0"></i>
                    <span class="font-bold text-xs uppercase tracking-wider">Free Transportation in Bali!</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-32 bg-[#f2f5f7] relative overflow-hidden">
        <div class="absolute right-0 bottom-0 text-[20rem] font-serif text-stone-200 select-none pointer-events-none leading-none -mb-20">ZEN</div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i aria-hidden="true" class="fas fa-check-circle mr-1"></i> Our Guarantees</span>
                <h2 class="text-4xl sm:text-5xl font-serif font-bold text-slate-900 leading-tight">Why Choose Us</h2>
                <p class="text-slate-600">We prioritize your health, comfort, and peace of mind. Here is why clients choose us.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12 mt-24 pb-12 items-stretch">
                <!-- Card 1: Staggered Up -->
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:-translate-y-6 hover:-translate-y-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 text-lg mb-8">
                            <i aria-hidden="true" class="fas fa-spa"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">Certified Therapists</h3>
                        <p class="text-stone-700 text-sm leading-relaxed font-light">Our female therapists are fully trained, certified, and experienced in professional spa treatments and anatomy.</p>
                    </div>
                </div>
                
                <!-- Card 2: Main Highlight Card -->
                <div class="bg-white p-10 rounded-3xl border-2 border-amber-500/30 shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex flex-col justify-between relative">
                    <span class="absolute -top-3 right-6 bg-amber-500 text-stone-950 text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                        Premium Choice
                    </span>
                    <div>
                        <div class="w-12 h-12 bg-emerald-950 text-amber-500 rounded-2xl flex items-center justify-center text-lg mb-8 border border-amber-500/30">
                            <i aria-hidden="true" class="fas fa-leaf"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">100% Organic Oils</h3>
                        <p class="text-stone-700 text-sm leading-relaxed font-light">We use only organic, virgin coconut oil and premium essential oils to nourish your skin and enhance relaxation.</p>
                    </div>
                </div>
                
                <!-- Card 3: Staggered Down -->
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:translate-y-6 hover:translate-y-4 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 text-lg mb-8">
                            <i aria-hidden="true" class="fas fa-car-side"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">No Transport Fee</h3>
                        <p class="text-stone-700 text-sm leading-relaxed font-light">No hidden charges. Our massage prices include all transport costs directly to your villa, hotel, or apartment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-tags mr-1"></i> Pricing Menu</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Treatments &amp; Pricing</h2>
                <p class="text-slate-500">Choose from our selected list of authentic Balinese spa therapies. Book easily on WhatsApp.</p>
            </div>
            
            <div id="services-list" class="space-y-24 mt-20">
                <?php foreach ($services as $index => $service): 
                    $isEven = ($index % 2 == 0);
                    $directionClass = $isEven ? 'md:flex-row' : 'md:flex-row-reverse';
                    $alignTextClass = $isEven ? 'md:text-left md:items-start md:pl-16' : 'md:text-right md:items-end md:pr-16';

                    // Fallback to optimized local WebP images if the DB still contains remote Unsplash URLs
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
                    <div class="flex flex-col <?php echo $directionClass; ?> items-center gap-12 relative">
                        <!-- Image Container with Clean Rounded Corners -->
                        <div class="w-full md:w-1/2 flex-shrink-0">
                            <div class="aspect-[4/3] w-full max-w-md mx-auto rounded-3xl overflow-hidden border border-stone-200 shadow-xl relative group">
                                <?php if ($service['featured']): ?>
                                    <span class="absolute top-6 left-6 bg-amber-500 text-stone-950 text-[9px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md z-10">
                                        <i aria-hidden="true" class="fas fa-crown mr-1"></i> Featured Choice
                                    </span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($serviceImg); ?>" width="448" height="336" loading="lazy" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700 ease-out">
                            </div>
                        </div>

                        <!-- Content Description Container (Clean & Corporate) -->
                        <div class="w-full md:w-1/2 flex flex-col justify-center items-center <?php echo $alignTextClass; ?> z-10 relative px-4 sm:px-8">
                            <div class="bg-white p-8 md:p-10 rounded-3xl border border-stone-100 shadow-lg max-w-md space-y-6 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#9c654d]">Treatments</span>
                                <h3 class="text-3xl font-serif font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-slate-500 text-sm leading-relaxed font-light"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <!-- Price List Style -->
                                 <div class="space-y-2.5 py-4 border-t border-stone-100">
                                    <?php foreach ($service['options'] as $opt): ?>
                                        <div class="flex justify-between border-b border-dashed border-stone-200 pb-1 text-sm font-semibold">
                                            <span class="text-slate-700"><?php echo htmlspecialchars($opt['duration']); ?></span>
                                            <span class="text-[#9c654d]"><?php echo htmlspecialchars($opt['price']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="border-t border-stone-100 pt-6 space-y-6">
                                    <div class="text-left">
                                        <label for="select-<?php echo $service['id']; ?>" class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Select Duration to Book</label>
                                        <select id="select-<?php echo $service['id']; ?>" class="w-full border border-stone-200 bg-stone-50 px-4 py-3.5 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-800 focus:outline-none">
                                            <?php foreach ($service['options'] as $opt): ?>
                                                <option value="<?php echo htmlspecialchars($opt['duration']); ?>" data-price="<?php echo htmlspecialchars($opt['price']); ?>">
                                                    <?php echo htmlspecialchars($opt['duration']); ?> - <?php echo htmlspecialchars($opt['price']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <button onclick="bookService('<?php echo addslashes($service['title']); ?>', '<?php echo $service['id']; ?>', '<?php echo htmlspecialchars($whatsapp); ?>')" class="w-full bg-[#9c654d] hover:bg-[#7d4d38] text-white font-bold py-4 px-6 rounded-2xl text-xs uppercase tracking-widest text-center transition-all duration-300 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                        <i aria-hidden="true" class="fab fa-whatsapp text-lg"></i>
                                        <span>Book Now</span>
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
    <section id="areas" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid lg:grid-cols-2 gap-12 items-center">
            <!-- Areas List -->
            <div class="space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i aria-hidden="true" class="fas fa-map-marked-alt mr-1"></i> Coverage Zones</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 leading-tight">Service Area Coverage</h2>
                <p class="text-slate-500 text-sm leading-relaxed font-light">Our professional therapists arrive directly at your location without any extra transportation charges in these areas:</p>
                
                <ul class="grid sm:grid-cols-2 gap-4">
                    <?php foreach ($areas as $area): ?>
                        <li class="flex items-center text-slate-700 text-sm font-medium">
                            <i aria-hidden="true" class="fas fa-check text-emerald-500 mr-3 text-xs bg-emerald-50 p-1 rounded-full"></i>
                            <span><?php echo htmlspecialchars($area); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start space-x-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-[#9c654d] text-lg flex-shrink-0">
                        <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Villa / Hotel / Home / Apartment Call</h4>
                        <p class="text-slate-600 text-xs leading-relaxed font-light">Our therapists arrive fully equipped with massage tables/mats, premium essential oils, fresh linen, and relaxing spa music.</p>
                    </div>
                </div>
            </div>
            
            <!-- Google Maps Embed -->
            <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-xl border border-stone-200">
                <iframe id="google-map-iframe"
                        src="about:blank"
                        data-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d252438.48918239088!2d115.09312151676646!3d-8.67045813735076!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd140d384d8b58b%3A0xa126509f7e1b7f94!2sBali!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                        title="Google Maps showing Bali service coverage area"
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

    <!-- Operating Hours Banner -->
    <section class="py-16 bg-[#192a3d] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="far fa-clock mr-1"></i> Available Daily</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold uppercase tracking-wider">OPERATING HOURS</h2>
            <p class="text-2xl sm:text-3xl font-bold text-[#9c654d]">Everyday (08:00 AM - 11:00 PM WITA)</p>
        </div>
    </section>

    <!-- FAQs Section -->
    <section id="faqs" class="py-24 bg-[#f2f5f7]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i aria-hidden="true" class="fas fa-question-circle mr-1"></i> Questions</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Frequently Asked Questions</h2>
                <p class="text-slate-600">Everything you need to know about our Bali home massage services.</p>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($faqs as $i => $faq): ?>
                    <div class="bg-white border border-stone-100 rounded-2xl overflow-hidden shadow-sm">
                        <button aria-expanded="false" aria-controls="faq-ans-<?php echo $i; ?>" onclick="toggleFaq(<?php echo $i; ?>)" class="w-full flex items-center justify-between p-6 text-left font-semibold text-slate-900 hover:bg-slate-50 transition-colors">
                            <span><?php echo htmlspecialchars($faq['question']); ?></span>
                            <i aria-hidden="true" id="faq-icon-<?php echo $i; ?>" class="fas fa-chevron-down text-[#9c654d] text-xs transition-transform duration-300"></i>
                        </button>
                        <div id="faq-ans-<?php echo $i; ?>" class="hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-50 pt-4 text-left">
                            <?php echo htmlspecialchars($faq['answer']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#192a3d] text-slate-300 py-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-16">
            <div class="space-y-6 text-left">
                <div class="flex items-center space-x-3">
                    <?php if (!empty($brandLogo)): ?>
                        <img src="<?php echo htmlspecialchars($brandLogo); ?>" width="40" height="40" alt="Logo" class="h-10 w-auto object-contain brightness-0 invert">
                    <?php endif; ?>
                    <h3 class="font-serif text-2xl font-bold text-white tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></h3>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed font-light">Relaxation and spa therapeutic treatments at your convenience. Book in under 3 minutes.</p>
            </div>
            <div class="space-y-6 text-left">
                <h4 class="font-bold text-white uppercase tracking-wider text-xs">Contact &amp; Hours</h4>
                <ul class="space-y-3.5 text-slate-400 text-sm">
                    <li class="flex items-center space-x-2">
                        <i aria-hidden="true" class="fab fa-whatsapp text-[#9c654d] text-base"></i> 
                        <span>WhatsApp: <a href="https://wa.me/<?php echo $whatsapp; ?>" class="hover:text-white transition-colors font-semibold text-[#9c654d]">+<?php echo htmlspecialchars($whatsapp); ?></a></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i aria-hidden="true" class="far fa-clock text-[#9c654d] text-base"></i> 
                        <span>Operating Hours: <?php echo htmlspecialchars($operatingHours); ?></span>
                    </li>
                </ul>
            </div>
            <div class="space-y-6 text-left">
                <h4 class="font-bold text-white uppercase tracking-wider text-xs">Follow Us</h4>
                <?php if (!empty($instagram)): ?>
                    <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors text-slate-400 text-sm flex items-center space-x-2.5">
                        <i aria-hidden="true" class="fab fa-instagram text-lg text-[#9c654d]"></i>
                        <span>Instagram</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-800 mt-16 pt-8 text-center text-slate-500 text-xs">
            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. All Rights Reserved. Designed for wellness.
        </div>
    </footer>

    <!-- Core App Client-Side JS -->
    <script>
        function toggleFaq(index) {
            const ans = document.getElementById('faq-ans-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            const btn = document.querySelector(`button[aria-controls='faq-ans-${index}']`);
            const isHidden = ans.classList.contains('hidden');
            
            // Hide all first
            document.querySelectorAll("[id^='faq-ans-']").forEach(el => el.classList.add('hidden'));
            document.querySelectorAll("[id^='faq-icon-']").forEach(el => {
                el.classList.remove('rotate-180');
            });
            document.querySelectorAll("button[aria-controls^='faq-ans-']").forEach(el => {
                el.setAttribute('aria-expanded', 'false');
            });

            if (isHidden) {
                ans.classList.remove('hidden');
                icon.classList.add('rotate-180');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            }
        }

        // Lazy load Google Maps iframe only when it enters the viewport
        if ('IntersectionObserver' in window) {
            const mapObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const iframe = entry.target;
                        iframe.setAttribute('src', iframe.getAttribute('data-src'));
                        observer.unobserve(iframe);
                    }
                });
            });
            const mapIframe = document.getElementById('google-map-iframe');
            if (mapIframe) {
                mapObserver.observe(mapIframe);
            }
        } else {
            // Fallback for older browsers
            window.addEventListener('load', function() {
                const mapIframe = document.getElementById('google-map-iframe');
                if (mapIframe && mapIframe.getAttribute('data-src')) {
                    mapIframe.setAttribute('src', mapIframe.getAttribute('data-src'));
                }
            });
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
