<?php

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Only handle App\ namespace classes
    if (strpos($class, 'App\\') !== 0) {
        return false;
    }
    
    // Get the base directory correctly
    // When called from /var/www/html/app/config/loader.php:
    // __DIR__ = /var/www/html/app/config
    // dirname(__DIR__) = /var/www/html/app
    // dirname(dirname(__DIR__)) = /var/www/html
    $appDir = dirname(dirname(__DIR__)) . '/app';
    
    // Remove "App\" prefix and convert namespace to path
    // App\Controllers\NotesController -> Controllers/NotesController
    $relative = substr($class, 4); // Remove "App\"
    $relative = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
    
    // Build full file path
    $filePath = $appDir . DIRECTORY_SEPARATOR . $relative . '.php';
    
    // Load the file if it exists
    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }
    
    return false;
});
