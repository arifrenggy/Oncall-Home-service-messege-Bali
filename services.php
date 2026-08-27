<?php
// services.php
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
?>

    <!-- Page Header -->
    <section class="bg-[#f2f5f7] py-16 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-tags mr-1"></i> Our Wellness Menu</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">Treatments &amp; Pricing</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">Explore our range of premium therapies. Select your duration and book directly to your villa, hotel, or home.</p>
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

<?php
require_once 'footer.php';
?>
