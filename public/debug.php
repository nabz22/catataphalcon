<?php
/**
 * 404 Troubleshooting Guide
 * ========================
 * 
 * Jika melihat 404 Controller Not Found, ikuti langkah berikut:
 */

echo "<h2>🔧 404 Troubleshooting Checklist</h2>";

// 1. Check Apache mod_rewrite
echo "<h3>1. Check Apache Configuration</h3>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p>mod_rewrite: " . (in_array('mod_rewrite', $modules) ? "✅ ENABLED" : "❌ DISABLED") . "</p>";
    echo "<p>mod_headers: " . (in_array('mod_headers', $modules) ? "✅ ENABLED" : "❌ DISABLED") . "</p>";
} else {
    echo "<p>⚠️ Cannot check modules (PHP running as CGI/FastCGI)</p>";
}

// 2. Check routing parameters
echo "<h3>2. Check Request Information</h3>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'N/A') . "\n";
echo "_GET[_url]: " . ($_GET['_url'] ?? 'N/A') . "\n";
echo "METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n";
echo "</pre>";

// 3. Check controller files
echo "<h3>3. Check Controller Files</h3>";
$controllersDir = dirname(__DIR__) . '/app/controllers';
if (is_dir($controllersDir)) {
    $files = scandir($controllersDir);
    echo "<p>Controllers found:</p>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && substr($file, -4) === '.php') {
            echo "<li>✅ " . $file . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>❌ Controllers directory not found!</p>";
}

// 4. Check namespace loading
echo "<h3>4. Check Class Loading</h3>";
echo "<pre>";
$testClass = 'App\\Controllers\\NotesController';
if (class_exists($testClass)) {
    echo "✅ $testClass - CLASS LOADED\n";
    $methods = get_class_methods($testClass);
    echo "Available methods:\n";
    foreach ($methods as $method) {
        if (strpos($method, 'Action') !== false) {
            echo "  - $method\n";
        }
    }
} else {
    echo "❌ $testClass - CLASS NOT FOUND\n";
}
echo "</pre>";

// 5. Solutions
echo "<h3>5. 💡 Solutions</h3>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><strong>Clear Cache</strong>: Delete all files in /cache/</li>";
echo "<li><strong>Check .htaccess</strong>: Ensure AllowOverride All is set in Apache config</li>";
echo "<li><strong>Verify Router</strong>: Test direct URL like <code>/public/index.php?_url=/notes/index</code></li>";
echo "<li><strong>Check Loader</strong>: Ensure composer autoload is working properly</li>";
echo "<li><strong>Enable Debug</strong>: Set debugMode=true in public/index.php to see logs</li>";
echo "<li><strong>Check Logs</strong>: Look at error log for any PHP errors</li>";
echo "</ol>";
echo "</div>";

// 6. Test direct access
echo "<h3>6. Test Direct URL Access</h3>";
echo "<p>Try these URLs:</p>";
echo "<ul>";
echo "<li><a href='/public/index.php?_url=/notes' target='_blank'>Direct: /public/index.php?_url=/notes</a></li>";
echo "<li><a href='/notes' target='_blank'>Routed: /notes</a></li>";
echo "<li><a href='/notes/create' target='_blank'>Routed: /notes/create</a></li>";
echo "<li><a href='/index' target='_blank'>Routed: /index</a></li>";
echo "</ul>";

?>
