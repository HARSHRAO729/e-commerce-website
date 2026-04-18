<?php
/**
 * Single-entry-point router for Vercel.
 * Routes all requests to the correct PHP file using ONE Lambda.
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Determine route target
if (strpos($uri, 'admin/') === 0 || $uri === 'admin') {
    // Admin route
    $page = (strlen($uri) > 6) ? substr($uri, 6) : 'index';
    if (empty($page)) $page = 'index';
    if (!str_ends_with($page, '.php')) $page .= '.php';
    $baseDir = __DIR__ . '/admin';
    $file    = $baseDir . '/' . $page;
} else {
    // Front-end route
    if (empty($uri)) {
        $page = 'index.php';
    } else {
        $page = str_ends_with($uri, '.php') ? $uri : $uri . '.php';
    }
    $baseDir = __DIR__;
    $file    = $baseDir . '/' . $page;
}

// Serve the file if it exists, else 404
if (file_exists($file)) {
    chdir($baseDir);  // Fix relative includes inside each page
    include $file;
} else {
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>Could not find: ' . htmlspecialchars($uri) . '</p>';
}
