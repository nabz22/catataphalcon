<?php
// Simulate POST request to create note

$url = 'http://localhost:8080/notes/create';

$postData = [
    'judul' => 'Catatan Test Create',
    'isi' => 'Ini adalah catatan test untuk memastikan create berfungsi dengan baik dan data masuk ke database',
    'tanggal' => '2026-01-08'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');

$result = curl_exec($ch);
$info = curl_getinfo($ch);

echo "Status Code: " . $info['http_code'] . "\n";
echo "Redirect URL: " . $info['redirect_url'] . "\n";
echo "Response content:\n";
echo substr($result, 0, 500) . "\n";

curl_close($ch);

// Check database
sleep(1);
$host = 'localhost';
$user = 'root';
$password = 'root';
$db = 'notes_db';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM notes ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();

echo "\nLatest note in DB:\n";
echo "ID: " . $row['id'] . "\n";
echo "Judul: " . $row['judul'] . "\n";
echo "Created: " . $row['created_at'] . "\n";

$conn->close();
?>
