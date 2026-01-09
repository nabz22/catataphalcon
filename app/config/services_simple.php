<?php

namespace {

// Load Models - BaseModel and all app classes (pastikan hanya sekali)
require_once APP_PATH . '/models/BaseModel.php';

// Ensure autoloader is registered
if (!function_exists('\spl_autoload_functions') || empty(spl_autoload_functions())) {
    // If no autoloaders registered, try to load loader again
    if (file_exists(APP_PATH . '/config/loader.php')) {
        require APP_PATH . '/config/loader.php';
    }
}

// Simple View class
class SimpleView {
    private $viewsDir;
    private $data = [];
    public $assets;
    public $flashSession;
    
    public function __construct() {
        $this->assets = new SimpleAssets();
    }
    
    public function setViewsDir($dir) {
        $this->viewsDir = $dir;
    }
    
    public function setFlashSession($flashSession) {
        $this->flashSession = $flashSession;
    }
    
    public function render($view, $data = []) {
        $this->data = $data;
        $viewFile = $this->viewsDir . $view . '.phtml';
        
        if (file_exists($viewFile)) {
            extract($this->data);
            include $viewFile;
        } else {
            echo "View file not found: $viewFile";
        }
    }
}

// Simple Assets class
class SimpleAssets {
    private $css = [];
    private $js = [];
    
    public function addCss($path) {
        if (!in_array($path, $this->css)) {
            $this->css[] = $path;
        }
        return $this;
    }
    
    public function addJs($path) {
        if (!in_array($path, $this->js)) {
            $this->js[] = $path;
        }
        return $this;
    }
    
    public function outputCss() {
        $output = '';
        foreach ($this->css as $path) {
            $output .= '<link rel="stylesheet" href="/' . $path . '">' . "\n";
        }
        return $output;
    }
    
    public function outputJs() {
        $output = '';
        foreach ($this->js as $path) {
            $output .= '<script src="/' . $path . '"></script>' . "\n";
        }
        return $output;
    }
}

// Simple FlashSession class
class SimpleFlashSession {
    public function _flash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
    
    public function has($type) {
        return isset($_SESSION['flash_' . $type]);
    }
    
    public function output() {
        foreach (['success', 'error'] as $type) {
            if (isset($_SESSION['flash_' . $type])) {
                $msg = $_SESSION['flash_' . $type];
                unset($_SESSION['flash_' . $type]);
                return $msg;
            }
        }
        return '';
    }
}

// Simple Request class
class SimpleRequest {
    public function getPost($key, $filter = null) {
        return $_POST[$key] ?? null;
    }
    
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

// Simple Response class
class SimpleResponse {
    public function redirect($url) {
        header("Location: /$url");
        exit;
    }
}

// Simple Database class
class SimpleDb {
    private $connection;
    
    public function __construct($host, $username, $password, $dbname) {
        // Tambahkan retry supaya tidak error "Connection refused" saat MySQL baru startup
        $maxAttempts = 5;
        $attempt = 0;
        $lastError = null;

        while ($attempt < $maxAttempts) {
            $this->connection = @new mysqli($host, $username, $password, $dbname);
            
            if (!$this->connection->connect_error) {
                // Sukses
                $this->connection->set_charset('utf8mb4');
                return;
            }

            $lastError = $this->connection->connect_error;
            $attempt++;
            // Tunggu sebentar sebelum coba lagi
            sleep(1);
        }

        // Jika tetap gagal setelah beberapa kali percobaan
        die('Database connection failed after retries: ' . $lastError);
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get config
$config = $di->getConfig();

// Create database instance
$db = new SimpleDb(
    $config['database']['host'],
    $config['database']['username'],
    $config['database']['password'],
    $config['database']['dbname']
);

// Set DB for models
\App\Models\BaseModel::setDb($db);

// Register services
$di->set('request', new SimpleRequest());
$di->set('response', new SimpleResponse());
$di->set('view', new SimpleView());
$di->set('flashSession', new SimpleFlashSession());
$di->set('db', $db);

}
