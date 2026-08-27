<?php
// services.php
$pageTitle = "Spa Treatments Menu & Booking Info | Honey Massage Bali";
$pageDesc = "Browse our professional Balinese massage, deep tissue treatments, and foot reflexology pricing. Check our free transport coverage areas and FAQs in Bali.";
$canonicalUrl = "https://honeymassagebali.shop/services";
require_once 'header.php';

// 1. Fetch all services
$services_query = $db->query("SELECT * FROM services ORDER BY id ASC");
$services = [];
while ($service = $services_query->fetch()) {
    $options_stmt = $db->prepare("SELECT duration, price FROM service_options WHERE service_ref = ? ORDER BY id ASC");
    $options_stmt->execute([$service['id']]);
    $service['options'] = $options_stmt->fetchAll();
    $services[] = $service;
}

// 2. Fetch all areas
$areas_query = $db->query("SELECT area_name FROM areas ORDER BY id ASC");
$areas = $areas_query->fetchAll(PDO::FETCH_COLUMN);

// 3. Fetch all FAQs
$faqs_query = $db->query("SELECT question, answer FROM faqs ORDER BY id ASC");
$faqs = $faqs_query->fetchAll();
?>

    <!-- FAQ Page Schema (JSON-LD) for dynamic Google Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        <?php foreach ($faqs as $i => $faq): ?>
        {
          "@type": "Question",
          "name": "<?php echo htmlspecialchars($faq['question']); ?>",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?php echo htmlspecialchars($faq['answer']); ?>"
          }
        }<?php echo ($i < count($faqs) - 1) ? ',' : ''; ?>
        <?php endforeach; ?>
      ]
    }
    </script>

    <!-- Page Header -->
    <section class="bg-[#f2f5f7] py-16 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-spa mr-1.5"></i> Treatments &amp; Info</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">Our Wellness Menu &amp; Booking Info</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">Browse our spa treatment catalog, view coverage zones, and find answers to frequently asked questions.</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="services-list" class="space-y-24">
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
                        <!-- Image Container -->
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

                        <!-- Content Description Container -->
                        <div class="w-full md:w-1/2 flex flex-col justify-center items-center <?php echo $alignTextClass; ?> z-10 relative px-4 sm:px-8">
                            <div class="bg-white p-8 md:p-10 rounded-3xl border border-stone-100 shadow-lg max-w-md space-y-6 text-left">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#9c654d]">Treatments</span>
                                <h3 class="text-3xl font-serif font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-slate-500 text-sm leading-relaxed font-light"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <!-- Price List -->
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

    <!-- Service Area Section (Merged) -->
    <section id="areas" class="py-24 bg-[#f2f5f7] border-t border-b border-stone-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid lg:grid-cols-2 gap-12 items-center">
            <!-- Areas List -->
            <div class="space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full"><i aria-hidden="true" class="fas fa-map-marked-alt mr-1"></i> Coverage Zones</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900 leading-tight">Service Area Coverage</h2>
                <p class="text-slate-500 text-sm leading-relaxed font-light">Our professional, certified female therapists arrive directly at your location without any extra transportation charges in these areas:</p>
                
                <ul class="grid sm:grid-cols-2 gap-4">
                    <?php foreach ($areas as $area): ?>
                        <li class="flex items-center text-slate-700 text-sm font-medium">
                            <i aria-hidden="true" class="fas fa-check text-emerald-500 mr-3 text-xs bg-emerald-50 p-1 rounded-full"></i>
                            <span><?php echo htmlspecialchars($area); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <p class="text-xs text-slate-500 leading-relaxed font-light mt-4">
                    Kami melayani berbagai kebutuhan Anda mulai dari <strong>massage panggilan Kuta</strong>, <strong>massage panggilan Ubud</strong>, <strong>massage panggilan Nusa Dua</strong>, hingga layanan premium seperti <strong>spa panggilan Seminyak</strong> dan <strong>spa panggilan Canggu</strong>. Bagi Anda wisatawan asing maupun domestik yang membutuhkan <strong>on call massage Seminyak</strong>, <strong>on call spa Uluwatu</strong>, atau <strong>massage hotel Bali</strong> dan <strong>massage delivery Bali</strong>, terapis profesional kami siap datang langsung ke tempat Anda. Kami juga menyediakan opsi <strong>massage panggilan Bali profesional</strong> serta <strong>balinese massage panggilan</strong> yang autentik untuk relaksasi maksimal.
                </p>
                
                <div class="bg-white p-6 rounded-2xl border border-stone-150 flex items-start space-x-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-[#9c654d] text-lg flex-shrink-0">
                        <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Villa / Hotel / Home / Apartment Call</h3>
                        <p class="text-slate-650 text-xs leading-relaxed font-light">Our therapists arrive fully equipped with massage tables/mats, premium essential oils, fresh linen, and relaxing spa music.</p>
                    </div>
                </div>
            </div>
            
            <!-- Google Maps Embed -->
            <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-xl border border-stone-200 bg-white">
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

    <!-- FAQs Section (Merged) -->
    <section id="faqs" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i aria-hidden="true" class="fas fa-question-circle mr-1"></i> Questions &amp; Answers</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Frequently Asked Questions</h2>
                <p class="text-slate-650 text-sm font-light">Everything you need to know about booking, transportation, payment methods, and therapist protocols.</p>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($faqs as $i => $faq): ?>
                    <div class="bg-[#f2f5f7] border border-stone-100 rounded-2xl overflow-hidden shadow-sm">
                        <button aria-expanded="false" aria-controls="faq-ans-<?php echo $i; ?>" onclick="toggleFaq(<?php echo $i; ?>)" class="w-full flex items-center justify-between p-6 text-left font-semibold text-slate-900 hover:bg-slate-50 transition-colors">
                            <span><?php echo htmlspecialchars($faq['question']); ?></span>
                            <i aria-hidden="true" id="faq-icon-<?php echo $i; ?>" class="fas fa-chevron-down text-[#9c654d] text-xs transition-transform duration-300"></i>
                        </button>
                        <div id="faq-ans-<?php echo $i; ?>" class="hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4 text-left bg-white">
                            <?php echo htmlspecialchars($faq['answer']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
