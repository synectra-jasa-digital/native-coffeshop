<?php

/**
 * Autoloader for the application
 * Automatically loads classes from the app/ directory
 */
spl_autoload_register(function ($class) {
    // The base directory for the namespace prefix
    $base_dir = __DIR__ . '/../app/';

    // Convert namespace to full file path
    // Assuming namespace matches directory structure inside app/
    // Example: App\Core\Router -> app/Core/Router.php
    $class_path = str_replace('App\\', '', $class);
    $file = $base_dir . str_replace('\\', '/', $class_path) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
