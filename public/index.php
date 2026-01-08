<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Load config
$config = include APP_PATH . '/config/config.php';

// Load loader
require APP_PATH . '/config/loader.php';

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
    $uri = $_GET['_url'] ?? $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $uri = trim($uri, '/');
    
    if (empty($uri)) {
        $uri = 'notes';
    }
    
    $parts = explode('/', $uri);
    $controller = $parts[0] ?? 'notes';
    $action = $parts[1] ?? 'index';
    $params = array_slice($parts, 2);
    
    $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';
    
    if (class_exists($controllerClass)) {
        $ctrl = new $controllerClass();
        $ctrl->_setDi($di);
        
        $method = $action . 'Action';
        if (method_exists($ctrl, $method)) {
            call_user_func_array([$ctrl, $method], $params);
        } else {
            echo '404 Not Found (method)';
        }
    } else {
        echo '404 Not Found (controller)';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}


