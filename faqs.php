<?php
// faqs.php
require_once 'header.php';

// 1. Fetch all FAQs
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
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-100 px-4 py-1 rounded-full"><i aria-hidden="true" class="fas fa-question-circle mr-1"></i> Questions &amp; Answers</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">Frequently Asked Questions</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">Everything you need to know about booking, transportation, payment methods, and therapist protocols.</p>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-24 bg-white animate-fadeIn">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
