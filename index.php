<?php
// index.php
require_once 'header.php';

// 1. Fetch featured services (limit to 3 for homepage)
$services_query = $db->query("SELECT * FROM services ORDER BY id ASC LIMIT 3");
$services = [];
while ($service = $services_query->fetch()) {
    $options_stmt = $db->prepare("SELECT duration, price FROM service_options WHERE service_ref = ? ORDER BY id ASC");
    $options_stmt->execute([$service['id']]);
    $service['options'] = $options_stmt->fetchAll();
    $services[] = $service;
}

// 2. Fetch areas
$areas_query = $db->query("SELECT area_name FROM areas ORDER BY id ASC LIMIT 4");
$areas = $areas_query->fetchAll(PDO::FETCH_COLUMN);

// 3. Fetch 3 latest reviews
$reviews_query = $db->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC LIMIT 3");
$reviews = $reviews_query->fetchAll();
?>

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
                </ul>
                
                <div class="pt-6 flex flex-wrap gap-4">
                    <a href="services.php" class="bg-[#9c654d] hover:bg-[#7d4d38] text-white px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                        <i aria-hidden="true" class="fab fa-whatsapp text-sm"></i>
                        <span>Book Now</span>
                    </a>
                    <a href="services.php" class="border border-slate-300 hover:border-slate-800 text-slate-700 hover:text-slate-900 px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                        View Prices
                    </a>
                </div>
            </div>
            
            <!-- Right Side Image Mockup -->
            <div class="lg:col-span-5 relative">
                <div class="aspect-[4/5] sm:aspect-square bg-gradient-to-tr from-stone-100 to-slate-200 rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200">
                    <img src="assets/images/hero-massage.webp" width="600" height="750" alt="On-call and home service massage Canggu Seminyak Bali - Honey Massage" class="w-full h-full object-cover">
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
                </div>
                
                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="about.php" class="bg-[#9c654d] hover:bg-[#7d4d38] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">Read More About Us</a>
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
                <div class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm transition-all duration-300 md:-translate-y-6 hover:-translate-y-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 text-lg mb-8">
                            <i aria-hidden="true" class="fas fa-spa"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-stone-900 mb-3">Certified Therapists</h3>
                        <p class="text-stone-700 text-sm leading-relaxed font-light">Our female therapists are fully trained, certified, and experienced in professional spa treatments and anatomy.</p>
                    </div>
                </div>
                
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

    <!-- Featured Services Section -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-tags mr-1"></i> Featured Treatments</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Our Popular Services</h2>
                <p class="text-slate-600">Explore some of our most requested treatments. Available for direct booking via WhatsApp.</p>
            </div>
            
            <div id="services-list" class="space-y-24 mt-20">
                <?php foreach ($services as $index => $service): 
                    $isEven = ($index % 2 == 0);
                    $directionClass = $isEven ? 'md:flex-row' : 'md:flex-row-reverse';
                    $alignTextClass = $isEven ? 'md:text-left md:items-start md:pl-16' : 'md:text-right md:items-end md:pr-16';

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

                        <div class="w-full md:w-1/2 flex flex-col justify-center items-center <?php echo $alignTextClass; ?> z-10 relative px-4 sm:px-8">
                            <div class="bg-white p-8 md:p-10 rounded-3xl border border-stone-100 shadow-lg max-w-md space-y-6 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#9c654d]">Treatments</span>
                                <h3 class="text-3xl font-serif font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-slate-500 text-sm leading-relaxed font-light"><?php echo htmlspecialchars($service['description']); ?></p>
                                
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
            
            <div class="mt-16 text-center">
                <a href="services.php" class="inline-block border border-slate-350 hover:border-slate-800 text-slate-700 hover:text-slate-900 px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                    View All Treatments &amp; Pricing
                </a>
            </div>
        </div>
    </section>

    <!-- Service Area Summary -->
    <section id="areas" class="py-24 bg-[#f2f5f7] relative overflow-hidden">
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
                
                <div class="pt-4">
                    <a href="areas.php" class="bg-[#9c654d] hover:bg-[#7d4d38] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">View All Service Areas</a>
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

    <!-- Testimonials Section -->
    <section id="reviews-summary" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-star mr-1"></i> Reviews</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">What Our Clients Say</h2>
                <p class="text-slate-600">Read the reviews from our satisfied clients or submit your own experience.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 pb-12">
                <?php foreach ($reviews as $rev): ?>
                    <div class="bg-[#f2f5f7] p-8 rounded-3xl border border-stone-100 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div class="flex text-amber-500 space-x-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?php echo ($i <= $rev['rating']) ? 'fas' : 'far'; ?> fa-star text-sm"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed font-light italic">"<?php echo htmlspecialchars($rev['comment']); ?>"</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm"><?php echo htmlspecialchars($rev['name']); ?></h4>
                            <span class="text-[10px] text-slate-400 font-light"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center space-x-4">
                <a href="reviews.php" class="inline-block bg-[#9c654d] hover:bg-[#7d4d38] text-white px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest shadow-md transition-all">
                    Write A Review
                </a>
                <a href="reviews.php" class="inline-block border border-slate-300 hover:border-slate-800 text-slate-700 hover:text-slate-900 px-8 py-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                    Read All Reviews
                </a>
            </div>
        </div>
    </section>

    <!-- Operating Hours Banner -->
    <section class="py-16 bg-[#192a3d] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="far fa-clock mr-1"></i> Available Daily</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold uppercase tracking-wider">OPERATING HOURS</h2>
            <p class="text-2xl sm:text-3xl font-bold text-[#9c654d]">Everyday (<?php echo htmlspecialchars($operatingHours); ?>)</p>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
