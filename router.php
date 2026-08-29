<?php
// router.php for PHP built-in web server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve existing static file directly
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

// Route clean URLs (e.g. /services -> services.php)
if (file_exists(__DIR__ . $uri . '.php')) {
    require __DIR__ . $uri . '.php';
    return true;
}

// Route directory index (e.g. /admin -> admin/index.php)
if (is_dir(__DIR__ . $uri) && file_exists(__DIR__ . $uri . '/index.php')) {
    require __DIR__ . $uri . '/index.php';
    return true;
}

// Fallback to index.php
require __DIR__ . '/index.php';
return true;
