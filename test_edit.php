<?php
// Test edit note
$url = 'http://localhost:8080/notes/edit/6';

// First, get the edit form
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$result = curl_exec($ch);
curl_close($ch);

echo "Form loaded. Testing edit with POST...\n\n";

// Now post the edit
$postData = [
    'judul' => 'Catatan Pertama - DIUBAH',
    'isi' => 'Ini adalah catatan pertama saya yang sudah diubah dengan konten baru',
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
echo "Response preview:\n";
echo substr($result, 0, 400) . "\n\n";

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

$result = $conn->query("SELECT id, judul, created_at, updated_at FROM notes WHERE id = 6");
$row = $result->fetch_assoc();

echo "Updated note in DB:\n";
echo "ID: " . $row['id'] . "\n";
echo "Judul: " . $row['judul'] . "\n";
echo "Created: " . $row['created_at'] . "\n";
echo "Updated: " . $row['updated_at'] . "\n";

$conn->close();
?>
