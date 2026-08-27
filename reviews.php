<?php
// reviews.php
$pageTitle = "Customer Reviews & Write a Review | Honey Massage Bali";
$pageDesc = "Read testimonials from customers who ordered our spa services in Bali, or submit your own review of your massage experience.";
$canonicalUrl = "https://honeymassagebali.shop/reviews";
require_once 'header.php';

$error = '';
$success = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Security check failed: Invalid CSRF token. Please refresh the page and try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $rating = intval($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if (empty($name) || empty($comment)) {
            $error = 'Please fill in both your name and comment.';
        } elseif ($rating < 1 || $rating > 5) {
            $error = 'Invalid rating value.';
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO reviews (name, rating, comment, status) VALUES (?, ?, ?, 'approved')");
                $stmt->execute([$name, $rating, $comment]);
                $success = 'Thank you! Your review has been submitted successfully.';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all approved reviews
$reviews_query = $db->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC");
$reviews = $reviews_query->fetchAll();
?>

    <!-- Page Header -->
    <section class="bg-[#f2f5f7] py-16 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9c654d]"><i aria-hidden="true" class="fas fa-star mr-1"></i> Customer Experiences</span>
            <h1 class="text-4xl font-serif font-bold text-slate-900 leading-tight">Reviews &amp; Testimonials</h1>
            <p class="text-slate-650 font-light text-sm max-w-xl mx-auto">Read honest feedback from our valued clients or share your own personal experience with our service.</p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-16 items-start">
            
            <!-- Left Side: Submit Review Form -->
            <div class="lg:col-span-5 bg-[#f2f5f7] p-8 md:p-10 rounded-3xl border border-stone-150 shadow-md space-y-6">
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#9c654d]">Your Feedback</span>
                    <h2 class="text-2xl font-serif font-bold text-slate-900 leading-tight">Write a Review</h2>
                    <p class="text-slate-500 text-xs font-light">Your feedback helps us maintain our high standard of service across Bali.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-medium animate-pulse">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="reviews.php" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div>
                        <label for="name" class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. John Doe" class="w-full border border-stone-200 bg-white px-4 py-3 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-800 focus:outline-none">
                    </div>

                    <div>
                        <label for="rating" class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">Rating</label>
                        <select name="rating" id="rating" required class="w-full border border-stone-200 bg-white px-4 py-3 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-800 focus:outline-none">
                            <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars - Excellent)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Stars - Good)</option>
                            <option value="3">⭐⭐⭐ (3 Stars - Average)</option>
                            <option value="2">⭐⭐ (2 Stars - Poor)</option>
                            <option value="1">⭐ (1 Star - Very Poor)</option>
                        </select>
                    </div>

                    <div>
                        <label for="comment" class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">Review Comment</label>
                        <textarea name="comment" id="comment" rows="5" required placeholder="Tell us about your therapist, massage duration, and experience..." class="w-full border border-stone-200 bg-white px-4 py-3 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-800 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" name="submit_review" class="w-full bg-[#9c654d] hover:bg-[#7d4d38] text-white font-bold py-4 px-6 rounded-xl text-xs uppercase tracking-widest text-center transition-all duration-300 shadow-md hover:shadow-lg">
                        Submit Review
                    </button>
                </form>
            </div>

            <!-- Right Side: Display Reviews List -->
            <div class="lg:col-span-7 space-y-6">
                <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                    <h2 class="text-2xl font-serif font-bold text-slate-900 leading-tight">Reviews (<?php echo count($reviews); ?>)</h2>
                    <div class="flex items-center space-x-2 text-sm text-slate-500">
                        <span class="font-bold text-slate-900"><?php echo htmlspecialchars($ratingValue); ?></span>
                        <div class="flex text-amber-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 max-h-[800px] overflow-y-auto pr-2">
                    <?php if (count($reviews) === 0): ?>
                        <p class="text-slate-500 text-sm italic">No reviews yet. Be the first to write a review!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="bg-[#f2f5f7] p-6 sm:p-8 rounded-3xl border border-stone-100 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex text-amber-500 space-x-1">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="<?php echo ($i <= $rev['rating']) ? 'fas' : 'far'; ?> fa-star text-xs"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="text-slate-650 text-sm leading-relaxed font-light italic">"<?php echo htmlspecialchars($rev['comment']); ?>"</p>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <h3 class="font-bold text-slate-900"><?php echo htmlspecialchars($rev['name']); ?></h3>
                                    <span class="text-slate-400 font-light"><?php echo date('d M Y, H:i', strtotime($rev['created_at'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>

<?php
require_once 'footer.php';
?>
