<?php
/**
 * Single-entry-point router for Vercel.
 * Optimized for Vercel's directory structure.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// On Vercel, the project root is usually one level up from the 'api' folder
$rootDir = realpath(__DIR__ . '/..');

// Handle empty URI (root index)
if (empty($uri)) {
    $uri = 'index.php';
}

// Determine if it is an admin route
$isAdmin = (strpos($uri, 'admin/') === 0 || $uri === 'admin');

if ($isAdmin) {
    $page = ($uri === 'admin') ? 'index.php' : substr($uri, 6);
    if (!str_ends_with($page, '.php')) $page .= '.php';
    $targetDir = $rootDir . '/admin';
    $file = $targetDir . '/' . $page;
} else {
    $page = $uri;
    if (!str_ends_with($page, '.php') && !str_contains($page, '.')) $page .= '.php';
    $targetDir = $rootDir;
    $file = $targetDir . '/' . $page;
}

if (file_exists($file)) {
    chdir($targetDir);
    include $file;
} else {
    http_response_code(404);
    echo "<h1>404 - File Not Found</h1>";
    echo "<p><b>Looking for:</b> $file</p>";
    echo "<p><b>Root detected as:</b> $rootDir</p>";
    echo "<h3>Directory Content:</h3><pre>";
    print_r(scandir($rootDir));
    echo "</pre>";
}
