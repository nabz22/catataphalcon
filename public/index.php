<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Load config
$config = include APP_PATH . '/config/config.php';

// Load loader
require APP_PATH . '/config/loader.php';

// CRITICAL FIX: Pre-load all essential classes before DI setup
// This ensures they are available when controllers try to use them
$_essential_classes = [
    // BaseModel sudah di-load lewat services_simple.php (require_once)
    'App\\Models\\Notes' => APP_PATH . '/models/Notes.php',
    'App\\Controllers\\NotesController' => APP_PATH . '/controllers/NotesController.php',
    'App\\Controllers\\IndexController' => APP_PATH . '/controllers/IndexController.php',
];

foreach ($_essential_classes as $_class => $_file) {
    if (file_exists($_file) && !class_exists($_class)) {
        require_once $_file;
    }
}
unset($_essential_classes, $_class, $_file);

// Simple DI Container class
class DI {
    private $services = [];
    
    public function set($key, $val) {
        $this->services[$key] = $val;
        return $this;
    }
    
    public function get($key) {
        return $this->services[$key] ?? null;
    }
    
    public function getConfig() {
        return $this->get('config');
    }
}

$di = new DI();
$di->set('config', $config);

// Load services
require APP_PATH . '/config/services_simple.php';

// Simple routing and dispatch
try {
    // Get URI - multiple fallback methods with better handling
    $uri = '';
    
    // Method 1: Check _url parameter (from .htaccess rewrite)
    if (!empty($_GET['_url'])) {
        $uri = $_GET['_url'];
    }
    // Method 2: Parse REQUEST_URI
    elseif (!empty($_SERVER['REQUEST_URI'])) {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove /public/ prefix if exists (for direct /public/index.php access)
        $uri = preg_replace('#^/public/#', '', $uri);
        
        // Remove index.php if exists
        $uri = str_replace('/index.php', '', $uri);
        
        // Remove /public/index.php pattern
        $uri = str_replace('/public/index.php', '', $uri);
        
        // Get script directory (for subdirectory installations)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptDir && $scriptDir !== '/') {
            // Remove script directory from URI
            $scriptDir = str_replace('/public', '', $scriptDir);
            $uri = preg_replace('#^' . preg_quote($scriptDir, '#') . '#', '', $uri);
        }
    }
    // Method 3: Check PATH_INFO
    elseif (!empty($_SERVER['PATH_INFO'])) {
        $uri = $_SERVER['PATH_INFO'];
    }
    
    // Clean up URI - remove leading/trailing slashes and normalize
    $uri = trim($uri, '/');
    $uri = preg_replace('/\/+/', '/', $uri); // Remove double slashes
    
    // Debug logging (enable untuk troubleshooting)
    $debugMode = false; // Set ke true untuk debug
    
    // Default route: notes/index - Initialize first
    if (empty($uri) || $uri === 'index.php' || $uri === 'index') {
        $controller = 'notes';
        $action = 'index';
        $params = [];
    } else {
        // Parse route - better handling for empty parts
        $parts = array_filter(explode('/', $uri)); // Filter out empty parts from multiple slashes
        $parts = array_values($parts); // Re-index array
        
        $controller = !empty($parts[0]) ? strtolower($parts[0]) : 'notes';
        $action = !empty($parts[1]) ? strtolower($parts[1]) : 'index';
        $params = array_slice($parts, 2);
    }
    
    if ($debugMode) {
        error_log("=== Routing Debug ===");
        error_log("REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
        error_log("SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A'));
        error_log("_GET[_url]: " . ($_GET['_url'] ?? 'N/A'));
        error_log("Parsed URI: " . $uri);
        error_log("Controller: " . $controller);
        error_log("Action: " . $action);
        error_log("==================");
    }
    
    // Build controller class name
    $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';
    
    // Debug: Log class loading attempt
    if ($debugMode) {
        error_log("Attempting to load class: " . $controllerClass);
        error_log("Class exists: " . (class_exists($controllerClass) ? "YES" : "NO"));
    }
    
    // Check if controller exists
    if (class_exists($controllerClass)) {
        $ctrl = new $controllerClass();
        $ctrl->_setDi($di);
        
        $method = $action . 'Action';
        
        // Check if method exists
        if (method_exists($ctrl, $method)) {
            // Call controller method with params
            call_user_func_array([$ctrl, $method], $params);
        } else {
            // Action not found
            http_response_code(404);
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Action Not Found</title>
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }
                    .error-container {
                        background: white;
                        padding: 40px;
                        border-radius: 10px;
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                        max-width: 600px;
                        text-align: center;
                    }
                    h1 {
                        color: #dc3545;
                        margin-bottom: 20px;
                    }
                    p {
                        color: #666;
                        margin-bottom: 15px;
                        line-height: 1.6;
                    }
                    .btn {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #667eea;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        margin-top: 20px;
                        transition: all 0.3s ease;
                    }
                    .btn:hover {
                        background-color: #5568d3;
                        transform: translateY(-2px);
                    }
                    .debug {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                        text-align: left;
                        font-size: 14px;
                        font-family: monospace;
                    }
                </style>
            </head>
            <body>
                <div class="error-container">
                    <h1>404 - Action Not Found</h1>
                    <p>Action <strong><?php echo htmlspecialchars($action); ?></strong> not found in controller <strong><?php echo htmlspecialchars($controller); ?></strong></p>
                    <div class="debug">
                        <strong>Debug Info:</strong><br>
                        Controller: <?php echo htmlspecialchars($controllerClass); ?><br>
                        Expected Method: <?php echo htmlspecialchars($method); ?><br>
                        Available Methods: <?php echo implode(', ', array_filter(get_class_methods($ctrl), function($m) { return strpos($m, 'Action') !== false; })); ?>
                    </div>
                    <a href="/notes" class="btn">Go to Notes</a>
                </div>
            </body>
            </html>
            <?php
        }
    } else {
        // Controller not found
        http_response_code(404);
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Controller Not Found</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .error-container {
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                    max-width: 600px;
                    text-align: center;
                }
                h1 {
                    color: #dc3545;
                    margin-bottom: 20px;
                }
                p {
                    color: #666;
                    margin-bottom: 15px;
                    line-height: 1.6;
                }
                .btn {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #667eea;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 20px;
                    transition: all 0.3s ease;
                }
                .btn:hover {
                    background-color: #5568d3;
                    transform: translateY(-2px);
                }
                .debug {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    margin-top: 20px;
                    text-align: left;
                    font-size: 14px;
                    font-family: monospace;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>404 - Controller Not Found</h1>
                <p>Controller <strong><?php echo htmlspecialchars($controller); ?></strong> not found</p>
                <div class="debug">
                    <strong>Debug Info:</strong><br>
                    URI: <?php echo htmlspecialchars($uri); ?><br>
                    Controller: <?php echo htmlspecialchars($controller); ?><br>
                    Action: <?php echo htmlspecialchars($action); ?><br>
                    Expected Class: <?php echo htmlspecialchars($controllerClass); ?><br>
                    <br>
                    <strong>Request Details:</strong><br>
                    REQUEST_URI: <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?><br>
                    SCRIPT_NAME: <?php echo htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A'); ?>
                </div>
                <a href="/notes" class="btn">Go to Notes</a>
            </div>
        </body>
        </html>
        <?php
    }
} catch (Exception $e) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>500 - Internal Server Error</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .error-container {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                max-width: 800px;
                width: 100%;
            }
            h1 {
                color: #dc3545;
                margin-bottom: 20px;
            }
            .error-message {
                background: #f8d7da;
                color: #721c24;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                border: 1px solid #f5c6cb;
            }
            .stack-trace {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
                font-family: monospace;
                font-size: 12px;
                line-height: 1.5;
            }
            .btn {
                display: inline-block;
                padding: 12px 24px;
                background-color: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
                transition: all 0.3s ease;
            }
            .btn:hover {
                background-color: #5568d3;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>500 - Internal Server Error</h1>
            <div class="error-message">
                <strong>Error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <div class="stack-trace">
                <strong>Stack Trace:</strong><br>
                <?php echo nl2br(htmlspecialchars($e->getTraceAsString())); ?>
            </div>
            <a href="/notes" class="btn">Go to Notes</a>
        </div>
    </body>
    </html>
    <?php
}