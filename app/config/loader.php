<?php

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $baseDir = dirname(dirname(__DIR__));
    
    if (strpos($class, 'App\\') === 0) {
        // Remove "App\" and convert backslashes to slashes
        $relative = str_replace('\\', '/', substr($class, 4));
        // Map to app/ directory
        $path = $baseDir . '/app/' . $relative . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});
