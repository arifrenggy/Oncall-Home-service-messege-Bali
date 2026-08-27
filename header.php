<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

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
$ratingValue = $settings['ratingValue'] ?? '4.9';
$reviewCount = $settings['reviewCount'] ?? '24';

// Auto-create reviews table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Seed reviews table if empty
$check_reviews = $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
if ($check_reviews == 0) {
    $db->exec("INSERT INTO `reviews` (`name`, `rating`, `comment`, `status`) VALUES
        ('Sarah Jenkins', 5, 'Absolutely amazing massage! The therapist arrived at our Seminyak villa on time, brought fresh towels, and the massage was incredibly relaxing after a long flight.', 'approved'),
        ('Michael Go', 5, 'Highly professional deep tissue massage. Helped so much with my muscle stiffness. Will definitely book again during my stay in Canggu.', 'approved'),
        ('Emily Watson', 5, 'Best home service spa in Bali! The aromatherapy oils smelled wonderful and the therapists were very polite and skilled. Very easy to book via WhatsApp.', 'approved')
    ;");
}

// Get current page file name to active states in menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Best Home Service Massage Bali | On-Call Spa &amp; Reflexology'; ?></title>
    <meta name="description" content="<?php echo isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Looking for the best home service massage in Bali? Professional on-call spa &amp; traditional Balinese massage delivered directly to your villa, hotel, or home. Book in 3 minutes!'; ?>">
    <meta name="keywords" content="home service massage bali, massage home service bali, oncall spa bali, massage villa bali, massage seminyak, massage canggu, massage ubud, hotel massage bali, balinese massage panggilan, spa panggilan bali, massage delivery bali, best massage bali, massage nusa dua, massage kuta">
    <link rel="canonical" href="<?php echo isset($canonicalUrl) ? htmlspecialchars($canonicalUrl) : 'https://honeymassagebali.shop/'; ?>">
    
    <!-- OpenGraph Meta Tags (SEO/Social/WhatsApp Share Preview) -->
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Best Home Service Massage Bali | On-Call Spa &amp; Reflexology'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Professional on-call spa &amp; traditional Balinese massage delivered directly to your villa, hotel, or home in Bali. Book via WhatsApp!'; ?>">
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
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?php echo htmlspecialchars($ratingValue); ?>",
        "reviewCount": "<?php echo htmlspecialchars($reviewCount); ?>",
        "bestRating": "5",
        "worstRating": "1"
      },
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Badung",
        "addressRegion": "Bali",
        "addressCountry": "ID"
      },
      "areaServed": [
        {"@type": "AdministrativeArea", "name": "Seminyak"},
        {"@type": "AdministrativeArea", "name": "Canggu"},
        {"@type": "AdministrativeArea", "name": "Kuta"},
        {"@type": "AdministrativeArea", "name": "Nusa Dua"},
        {"@type": "AdministrativeArea", "name": "Ubud"},
        {"@type": "AdministrativeArea", "name": "Denpasar"}
      ],
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"
        ],
        "opens": "08:00",
        "closes": "23:00"
      }
    }
    </script>
    
    <!-- Favicon (Stable URL for Google Indexing compliance) -->
    <?php 
    $faviconSrc = 'favicon.png';
    if (!file_exists(__DIR__ . '/favicon.png') && !empty($brandLogo) && file_exists(__DIR__ . '/' . $brandLogo)) {
        @copy(__DIR__ . '/' . $brandLogo, __DIR__ . '/favicon.png');
    }
    if (!file_exists(__DIR__ . '/favicon.png')) {
        $faviconSrc = 'assets/images/favicon.png';
    }
    ?>
    <link rel="icon" type="image/png" href="<?php echo $faviconSrc; ?>" sizes="96x96">
    <link rel="apple-touch-icon" href="<?php echo $faviconSrc; ?>">

    <!-- Google Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap">
    </noscript>

    <!-- Preload Font Awesome Webfonts to prevent LCP delay/layout shifts -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Font Awesome CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- Preload LCP Image -->
    <link rel="preload" as="image" href="assets/images/hero-massage.webp" type="image/webp" fetchpriority="high">

    <!-- Static Tailwind CSS -->
    <style>
        <?php echo file_get_contents(__DIR__ . '/assets/css/tailwind.min.css'); ?>
    </style>
    <style>
        .font-serif { font-family: 'Poppins', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        @media (min-width: 768px) {
            .md-hidden-forced { display: none !important; }
        }
    </style>
</head>
<body class="bg-theme-beige text-stone-800 font-sans antialiased">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-stone-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="./" class="flex items-center space-x-2 sm:space-x-3">
                <?php if (!empty($brandLogo)): ?>
                    <img src="<?php echo htmlspecialchars($brandLogo); ?>" alt="Logo" class="h-8 w-auto object-contain sm:h-10" style="max-height: 40px; max-width: 120px; object-fit: contain;">
                <?php endif; ?>
                <span class="text-base sm:text-xl font-serif font-bold text-slate-900 tracking-wider uppercase truncate max-w-[150px] sm:max-w-none"><?php echo htmlspecialchars($brandName); ?></span>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 text-xs font-bold tracking-widest text-slate-600 uppercase">
                <a href="./" class="<?php echo ($current_page == 'index.php') ? 'text-blue-600' : 'hover:text-blue-600'; ?> transition-colors">Home</a>
                <a href="services" class="<?php echo ($current_page == 'services.php') ? 'text-blue-600' : 'hover:text-blue-600'; ?> transition-colors">Treatments &amp; Info</a>
                <a href="reviews" class="<?php echo ($current_page == 'reviews.php') ? 'text-blue-600' : 'hover:text-blue-600'; ?> transition-colors">Reviews</a>
            </div>
            
            <!-- Desktop Book Now Button & Mobile Hamburger -->
            <div class="flex items-center space-x-4">
                <a href="services" class="hidden md:inline-flex bg-[#9c654d] hover:bg-[#7d4d38] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all">Book Now</a>
                
                <!-- Mobile Hamburger Button -->
                <button onclick="toggleMobileMenu()" aria-label="Toggle menu" class="md:hidden md-hidden-forced text-slate-700 focus:outline-none p-2 rounded-lg hover:bg-stone-50 transition-colors">
                    <i id="hamburger-icon" class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </nav>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-stone-100 bg-white px-4 pt-2 pb-6 space-y-1 shadow-md">
            <a href="./" class="block px-3 py-2.5 rounded-xl text-sm font-semibold tracking-wide text-slate-700 <?php echo ($current_page == 'index.php') ? 'bg-blue-50 text-blue-600' : 'hover:bg-stone-50'; ?> transition-colors">Home</a>
            <a href="services" class="block px-3 py-2.5 rounded-xl text-sm font-semibold tracking-wide text-slate-700 <?php echo ($current_page == 'services.php') ? 'bg-blue-50 text-blue-600' : 'hover:bg-stone-50'; ?> transition-colors">Treatments &amp; Info</a>
            <a href="reviews" class="block px-3 py-2.5 rounded-xl text-sm font-semibold tracking-wide text-slate-700 <?php echo ($current_page == 'reviews.php') ? 'bg-blue-50 text-blue-600' : 'hover:bg-stone-50'; ?> transition-colors">Reviews</a>
        </div>
    </header>
    <main>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('hamburger-icon');
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark'); // Change to X icon
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars'); // Change back to Hamburger
            }
        }
    </script>
