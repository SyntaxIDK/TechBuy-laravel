<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * 
 * This file serves as a bridge for Azure App Service
 * while maintaining compatibility with local development
 */

// Check if we're running on Azure App Service
$isAzure = isset($_SERVER['WEBSITE_SITE_NAME']) || 
           strpos($_SERVER['HTTP_HOST'] ?? '', 'azurewebsites.net') !== false ||
           strpos($_SERVER['SERVER_SOFTWARE'] ?? '', 'Microsoft-IIS') !== false;

if ($isAzure) {
    // Azure App Service: Load Laravel directly from root
    define('LARAVEL_START', microtime(true));

    // Check for maintenance mode
    if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Load Composer autoloader
    require __DIR__.'/vendor/autoload.php';

    // Bootstrap Laravel application
    $app = require_once __DIR__.'/bootstrap/app.php';

    // Handle the request
    $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
    
} else {
    // Local development: Use standard Laravel approach
    // This should work with 'php artisan serve' or properly configured web server
    if (php_sapi_name() === 'cli-server') {
        // Built-in server - check if file exists in public
        $publicFile = __DIR__ . '/public' . $_SERVER['REQUEST_URI'];
        if (is_file($publicFile)) {
            return false; // Let the built-in server serve the file
        }
        // Otherwise, serve through public/index.php
        require_once __DIR__ . '/public/index.php';
    } else {
        // Web server - should be configured to use public folder as document root
        echo '<h1>TechBuy Laravel Application</h1>';
        echo '<p><strong>Local Development:</strong></p>';
        echo '<p>Please use: <code>php artisan serve</code></p>';
        echo '<p>Or configure your web server document root to point to the <code>/public</code> directory</p>';
        echo '<hr>';
        echo '<p>Current working directory: ' . __DIR__ . '</p>';
        echo '<p>For production deployment, this will automatically work on Azure App Service.</p>';
    }
}