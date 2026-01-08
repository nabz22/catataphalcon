<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$db = 'notes_db';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "SELECT * FROM notes ORDER BY id DESC";
$result = $conn->query($sql);

echo "Total notes in database: " . $result->num_rows . "\n";

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " - Judul: " . $row['judul'] . "\n";
}

$conn->close();
?>
