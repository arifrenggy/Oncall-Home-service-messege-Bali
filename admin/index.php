<?php
// admin/index.php
session_start();

// Default hashed password for "adminbali123"
// To generate a new hash: password_hash("your_password", PASSWORD_DEFAULT)
$password_hash = '$2y$10$WpP9U142Y2h569L4v05Hau8VfLzBszXfA.q1aW45B0uX4V8G1v4fO'; // adminbali123

$error = '';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $password = $_POST['password'] ?? '';
    if (password_verify($password, $password_hash)) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = 'Invalid password. Please try again.';
    }
}

// Check session
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Show login page if not logged in
if (!$is_logged_in):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Oncall & home service message</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl p-8 border border-stone-200 shadow-md space-y-6">
        <div class="text-center">
            <span class="text-3xl">💆‍♀️</span>
            <h1 class="text-2xl font-bold text-stone-900 mt-2">Admin Dashboard</h1>
            <p class="text-stone-500 text-sm">Oncall & home service message</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 text-sm p-3 rounded-xl border border-red-100 font-medium">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php" class="space-y-4">
            <input type="hidden" name="login" value="1">
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-500 mb-1">Enter Password</label>
                <input type="password" id="password" name="password" required class="w-full border border-stone-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl text-sm transition-colors shadow-sm">
                Access Dashboard
            </button>
        </form>
    </div>
</body>
</html>
<?php
exit;
endif;
?>
