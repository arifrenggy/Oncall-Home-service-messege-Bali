<?php
// areas.php
require_once 'header.php';

// 1. Fetch all areas
$areas_query = $db->query("SELECT area_name FROM areas ORDER BY id ASC");
$areas = $areas_query->fetchAll(PDO::FETCH_COLUMN);
?>

    <!-- Page Header -->
    <section class="bg-[#f2f5f7] py-16 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full"><i aria-hidden="true" class="fas fa-map-marked-alt mr-1"></i> Coverage Zones</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">Service Area Coverage</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">We provide free transportation directly to your villa, hotel, home, or apartment across major tourist locations in Bali.</p>
        </div>
    </section>

    <!-- Areas & Google Maps Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
            <!-- Areas List -->
            <div class="space-y-6 text-left">
                <h2 class="text-3xl font-serif font-bold text-slate-900 leading-tight">Where We Deliver Wellness</h2>
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

<?php
require_once 'footer.php';
?>
