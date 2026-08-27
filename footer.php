<?php
// footer.php
?>
    </main>
    <!-- Footer -->
    <footer class="bg-[#192a3d] text-slate-300 py-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-16">
            <div class="space-y-6 text-left">
                <div class="flex items-center space-x-3">
                    <?php if (!empty($brandLogo)): ?>
                        <img src="<?php echo htmlspecialchars($brandLogo); ?>" alt="Logo" class="h-10 w-auto object-contain" style="max-height: 40px; max-width: 120px; object-fit: contain;">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-800 mt-16 pt-8 text-center text-slate-500 text-xs space-y-4">
            <p class="max-w-3xl mx-auto leading-relaxed">
                Premium <strong>massage on call Seminyak</strong>, <strong>in-villa massage Canggu</strong>, and <strong>outcall spa Seminyak</strong> services. Our <strong>professional mobile spa Bali</strong> also serves Kuta, Denpasar, Nusa Dua, Ubud, Pecatu, Uluwatu, Tanah Lot, Tabanan, and Gianyar with <strong>massage panggilan Bali</strong> and <strong>deep tissue massage villa</strong> treatments.
            </p>
            <p>
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($brandName); ?>. All Rights Reserved. Designed for wellness.
            </p>
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
            if (!select) return;
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
