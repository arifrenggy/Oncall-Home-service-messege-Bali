<?php
// about.php
require_once 'header.php';
?>

    <!-- Page Header -->
    <section class="bg-[#f2f5f7] py-16 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-spa mr-1.5"></i> Discover Our Story</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">About Us</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">Get to know the professional team behind Bali's premier in-villa and hotel on-call spa services.</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Side Image -->
            <div class="relative">
                <div class="aspect-[4/3] bg-slate-100 rounded-3xl overflow-hidden shadow-xl border border-stone-200">
                    <img src="assets/images/about-massage.webp" width="800" height="600" loading="lazy" alt="Oncall & Home Service Massage Bali" class="w-full h-full object-cover">
                </div>
            </div>
            <!-- Right Side Text -->
            <div class="space-y-6 text-left">
                <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-spa mr-1.5"></i> Who We Are</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 leading-tight">
                    Premium Oncall &amp; Home Service Massage
                </h2>
                <div class="text-slate-600 text-sm sm:text-base leading-relaxed space-y-4 font-light">
                    <p><?php echo htmlspecialchars($description); ?></p>
                    <p>Through our friendly, certified, and professionally trained female therapists, we are committed to delivering the ultimate relaxation experience directly to you without ever having to leave your room.</p>
                    <p>We supply all necessary equipment—including comfortable massage mats, fresh linens, premium essential oils, and soothing spa music—to transform your space into a peaceful oasis of relaxation.</p>
                    <p class="text-xs text-slate-500 font-normal">
                        Whether you are looking for a relaxing <strong>outcall massage Seminyak</strong>, a professional <strong>on call massage Canggu</strong>, or a premium <strong>spa villa call Bali</strong> service, we are ready to serve you. Enjoy the best <strong>home service massage Bali</strong> has to offer, tailored to your wellness needs.
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
    <section class="py-24 bg-[#f2f5f7] relative overflow-hidden">
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

<?php
require_once 'footer.php';
?>
