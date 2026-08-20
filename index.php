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
    <header class="sticky top-0 z-50 bg-white border-b border-stone-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center space-x-2">
                <span class="text-xl font-serif font-bold text-slate-900 tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></span>
            </a>
            <div class="hidden md:flex space-x-8 text-xs font-bold tracking-widest text-slate-600 uppercase">
                <a href="#about" class="hover:text-blue-600 transition-colors">Tentang Kami</a>
                <a href="#services" class="hover:text-blue-600 transition-colors">Harga & Layanan</a>
                <a href="#why-us" class="hover:text-blue-600 transition-colors">Kenapa Kami</a>
                <a href="#areas" class="hover:text-blue-600 transition-colors">Wilayah Layanan</a>
                <a href="#faqs" class="hover:text-blue-600 transition-colors">FAQs</a>
            </div>
            <a href="#services" class="bg-[#AE7D64] hover:bg-[#91624a] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all">Pesan Sekarang</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden bg-[#f2f5f7] py-20 lg:py-28 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full grid lg:grid-cols-12 gap-12 items-center">
            <!-- Left Side Content -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full">
                    <i class="fas fa-certificate mr-1.5"></i> #1 Pijat Panggilan Bali
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-slate-900 leading-tight">
                    Truly Home Massage, Spa & Reflexology
                </h1>
                <p class="text-slate-500 text-sm sm:text-base max-w-xl font-light">
                    Your TRULY Solutions - Nikmati kenyamanan spa mewah bintang 5 langsung di villa, hotel, rumah, atau apartemen Anda di seluruh wilayah Bali.
                </p>
                
                <!-- Bullet Checklist Guarantee -->
                <ul class="space-y-3.5 pt-2">
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Terapis Ramah, Bersertifikat &amp; Profesional</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Pemesanan Mudah &amp; Cepat via WhatsApp</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="font-medium text-[#AE7D64] font-bold">Gratis Biaya Transportasi area Bali</span>
                    </li>
                    <li class="flex items-center space-x-3 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="font-medium">Melayani Area Bali: Kuta, Denpasar, Seminyak, Canggu, Ubud &amp; Nusa Dua</span>
                    </li>
                </ul>
                
                <div class="pt-6 flex flex-wrap gap-4">
                    <a href="#services" class="bg-[#AE7D64] hover:bg-[#91624a] text-white px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span>Pesan Sekarang</span>
                    </a>
                    <a href="#services" class="border border-slate-300 hover:border-slate-800 text-slate-700 hover:text-slate-900 px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                        Lihat Harga
                    </a>
                </div>
            </div>
            
            <!-- Right Side Image Mockup -->
            <div class="lg:col-span-5 relative">
                <div class="aspect-[4/5] sm:aspect-square bg-gradient-to-tr from-stone-100 to-slate-200 rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200">
                    <img src="https://trulyhomemassage.com/wp-content/uploads/2024/04/Apa-Itu-Spa.webp" alt="Bali Spa Treatment" class="w-full h-full object-cover">
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
                    <img src="https://trulyhomemassage.com/wp-content/uploads/2024/08/truly-home-massage-bali.jpg" alt="Truly Home Massage Bali" class="w-full h-full object-cover">
                </div>
            </div>
            <!-- Right Side Text -->
            <div class="space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-[#AE7D64]"><i class="fas fa-spa mr-1.5"></i> Tentang Kami</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 leading-tight">
                    Truly Home Massage, Spa &amp; Reflexology
                </h2>
                <div class="text-slate-600 text-sm sm:text-base leading-relaxed space-y-4 font-light">
                    <p><?php echo htmlspecialchars($description); ?></p>
                    <p>Melalui terapis wanita kami yang ramah, bersertifikat, dan terlatih secara profesional, kami berkomitmen memberikan kepuasan relaksasi terbaik langsung ke hadapan Anda tanpa perlu keluar kamar.</p>
                </div>
                
                <!-- Highlight Banner (Truly Style) -->
                <div class="bg-amber-50 border border-amber-200/50 p-5 rounded-2xl flex items-center space-x-3 text-[#AE7D64] max-w-md">
                    <i class="fas fa-truck text-xl flex-shrink-0"></i>
                    <span class="font-bold text-xs uppercase tracking-wider">Gratis Biaya Transportasi Area Bali!</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-32 bg-[#f2f5f7] relative overflow-hidden">
        <div class="absolute right-0 bottom-0 text-[20rem] font-serif text-stone-200 select-none pointer-events-none leading-none -mb-20">ZEN</div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i class="fas fa-check-circle mr-1"></i> Keunggulan Kami</span>
                <h2 class="text-4xl sm:text-5xl font-serif font-bold text-slate-900 leading-tight">Kenapa Harus Memilih Kami</h2>
                <p class="text-slate-500">We prioritize your health, comfort, and peace of mind. Here is why clients choose us.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12 mt-24 pb-12 items-stretch">
                <!-- Card 1: Staggered Up -->
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:-translate-y-6 hover:-translate-y-8 flex flex-col justify-between">
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
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:translate-y-6 hover:translate-y-4 flex flex-col justify-between">
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
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#AE7D64]"><i class="fas fa-tags mr-1"></i> Daftar Harga</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Layanan &amp; Harga</h2>
                <p class="text-slate-500">Pilih dari daftar menu terapi tradisional Bali terbaik kami. Lakukan reservasi secara instan.</p>
            </div>
            
            <div id="services-list" class="space-y-24 mt-20">
                <?php foreach ($services as $index => $service): 
                    $isEven = ($index % 2 == 0);
                    $directionClass = $isEven ? 'md:flex-row' : 'md:flex-row-reverse';
                    $alignTextClass = $isEven ? 'md:text-left md:items-start md:pl-16' : 'md:text-right md:items-end md:pr-16';
                ?>
                    <div class="flex flex-col <?php echo $directionClass; ?> items-center gap-12 relative">
                        <!-- Image Container with Clean Rounded Corners -->
                        <div class="w-full md:w-1/2 flex-shrink-0">
                            <div class="aspect-[4/3] w-full max-w-md mx-auto rounded-3xl overflow-hidden border border-stone-200 shadow-xl relative group">
                                <?php if ($service['featured']): ?>
                                    <span class="absolute top-6 left-6 bg-amber-500 text-stone-950 text-[9px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md z-10">
                                        <i class="fas fa-crown mr-1"></i> Featured Choice
                                    </span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700 ease-out">
                            </div>
                        </div>

                        <!-- Content Description Container (Clean & Corporate) -->
                        <div class="w-full md:w-1/2 flex flex-col justify-center items-center <?php echo $alignTextClass; ?> z-10 relative px-4 sm:px-8">
                            <div class="bg-white p-8 md:p-10 rounded-3xl border border-stone-100 shadow-lg max-w-md space-y-6 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#AE7D64]">Treatments</span>
                                <h3 class="text-3xl font-serif font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-slate-500 text-sm leading-relaxed font-light"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <!-- Truly Price List Style -->
                                <div class="space-y-2.5 py-4 border-t border-stone-100">
                                    <?php foreach ($service['options'] as $opt): ?>
                                        <div class="flex justify-between border-b border-dashed border-stone-200 pb-1 text-sm font-semibold">
                                            <span class="text-slate-700"><?php echo htmlspecialchars($opt['duration']); ?></span>
                                            <span class="text-[#AE7D64]"><?php echo htmlspecialchars($opt['price']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="border-t border-stone-100 pt-6 space-y-6">
                                    <div class="text-left">
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Select Duration to Book</label>
                                        <select id="select-<?php echo $service['id']; ?>" class="w-full border border-stone-200 bg-stone-50 px-4 py-3.5 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-800 focus:outline-none">
                                            <?php foreach ($service['options'] as $opt): ?>
                                                <option value="<?php echo htmlspecialchars($opt['duration']); ?>" data-price="<?php echo htmlspecialchars($opt['price']); ?>">
                                                    <?php echo htmlspecialchars($opt['duration']); ?> - <?php echo htmlspecialchars($opt['price']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <button onclick="bookService('<?php echo addslashes($service['title']); ?>', '<?php echo $service['id']; ?>', '<?php echo htmlspecialchars($whatsapp); ?>')" class="w-full bg-[#AE7D64] hover:bg-[#91624a] text-white font-bold py-4 px-6 rounded-2xl text-xs uppercase tracking-widest text-center transition-all duration-300 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                        <span>Pesan Sekarang</span>
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
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i class="fas fa-map-marked-alt mr-1"></i> Area Jangkauan</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 leading-tight">Wilayah Layanan Kami</h2>
                <p class="text-slate-500 text-sm leading-relaxed font-light">Terapis profesional kami siap datang langsung ke tempat Anda tanpa biaya transportasi tambahan untuk wilayah-wilayah berikut:</p>
                
                <ul class="grid sm:grid-cols-2 gap-4">
                    <?php foreach ($areas as $area): ?>
                        <li class="flex items-center text-slate-700 text-sm font-medium">
                            <i class="fas fa-check text-emerald-500 mr-3 text-xs bg-emerald-50 p-1 rounded-full"></i>
                            <span><?php echo htmlspecialchars($area); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start space-x-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-[#AE7D64] text-lg flex-shrink-0">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Villa/Hotel/Rumah/Apartemen</h4>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">Terapis kami tiba dengan membawa matras pijat, minyak aromaterapi profesional, sprei bersih, serta musik relaksasi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Google Maps Embed -->
            <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-xl border border-stone-200">
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

    <!-- Operating Hours Banner -->
    <section class="py-16 bg-[#192a3d] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#AE7D64]"><i class="far fa-clock mr-1"></i> Available Daily</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold uppercase tracking-wider">JAM OPERASIONAL</h2>
            <p class="text-2xl sm:text-3xl font-bold text-[#AE7D64]">Setiap Hari (08:00 - 23:00 WITA)</p>
        </div>
    </section>

    <!-- FAQs Section -->
    <section id="faqs" class="py-24 bg-[#f2f5f7]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i class="fas fa-question-circle mr-1"></i> Pertanyaan</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Frequently Asked Questions</h2>
                <p class="text-slate-500">Semua informasi penting yang wajib Anda ketahui mengenai layanan pijat kami.</p>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($faqs as $i => $faq): ?>
                    <div class="bg-white border border-stone-100 rounded-2xl overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full flex items-center justify-between p-6 text-left font-semibold text-slate-900 hover:bg-slate-50 transition-colors">
                            <span><?php echo htmlspecialchars($faq['question']); ?></span>
                            <i id="faq-icon-<?php echo $i; ?>" class="fas fa-chevron-down text-[#AE7D64] text-xs transition-transform duration-300"></i>
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
                <h3 class="font-serif text-2xl font-bold text-white tracking-wider uppercase"><?php echo htmlspecialchars($brandName); ?></h3>
                <p class="text-slate-400 text-sm leading-relaxed font-light">Relaxation and spa therapeutic treatments at your convenience. Book in under 3 minutes.</p>
            </div>
            <div class="space-y-6 text-left">
                <h4 class="font-bold text-white uppercase tracking-wider text-xs">Kontak &amp; Alamat</h4>
                <ul class="space-y-3.5 text-slate-400 text-sm">
                    <li class="flex items-center space-x-2">
                        <i class="fab fa-whatsapp text-[#AE7D64] text-base"></i> 
                        <span>WhatsApp: <a href="https://wa.me/<?php echo $whatsapp; ?>" class="hover:text-white transition-colors font-semibold text-[#AE7D64]">+<?php echo htmlspecialchars($whatsapp); ?></a></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="far fa-clock text-[#AE7D64] text-base"></i> 
                        <span>Jam Operasional: <?php echo htmlspecialchars($operatingHours); ?></span>
                    </li>
                </ul>
            </div>
            <div class="space-y-6 text-left">
                <h4 class="font-bold text-white uppercase tracking-wider text-xs">Ikuti Media Sosial Kami</h4>
                <?php if (!empty($instagram)): ?>
                    <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" class="hover:text-white transition-colors text-slate-400 text-sm flex items-center space-x-2.5">
                        <i class="fab fa-instagram text-lg text-[#AE7D64]"></i>
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
