<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$basePath = 'c:\\Users\\ThinkPad T14 G1\\nazmi1';
define('BASE_PATH', $basePath);
define('APP_PATH', BASE_PATH . '\\app');

// Load config
$config = include APP_PATH . '\\config\\config.php';

// Load loader
require APP_PATH . '\\config\\loader.php';

// Simple DI Container
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
require APP_PATH . '\\config\\services_simple.php';

// Test save
use App\Models\Notes;

$note = new Notes();
$note->judul = 'Test Catatan Direct';
$note->isi = 'Ini adalah test catatan langsung dari script';
$note->tanggal = '2026-01-08';

echo "Before save - validation: " . ($note->validation() ? 'PASSED' : 'FAILED') . "\n";

$result = $note->save();
echo "Save result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

if (!$result) {
    echo "Messages:\n";
    foreach ($note->getMessages() as $msg) {
        echo "  - " . $msg->field . ": " . $msg->message . "\n";
    }
}

// Check database
$result = $di->get('db')->query("SELECT COUNT(*) as total FROM notes");
$row = $result->fetch_assoc();
echo "Total notes in DB: " . $row['total'] . "\n";

?>
