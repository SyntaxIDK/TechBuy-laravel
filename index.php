<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file serves as a fallback for Azure App Service
 * when the document root is not properly set to /public
 */

// Check if we're in the right directory
if (file_exists(__DIR__ . '/public/index.php')) {
    // Redirect to public folder
    require_once __DIR__ . '/public/index.php';
} else {
    // Fallback error message
    echo '<h1>TechBuy Laravel Application</h1>';
    echo '<p>Application files are being loaded...</p>';
    echo '<p>If you see this message, the application is deployed but the document root needs configuration.</p>';

    // Try to show some debug info
    if (file_exists(__DIR__ . '/artisan')) {
        echo '<p>✅ Laravel files detected</p>';
    } else {
        echo '<p>❌ Laravel files not found</p>';
    }

    echo '<p>Current directory: ' . __DIR__ . '</p>';
    echo '<p>Files in directory: ' . implode(', ', array_slice(scandir(__DIR__), 2, 10)) . '</p>';
}
