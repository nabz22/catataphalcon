<?php
/**
 * Koneksi Database menggunakan PDO
 * Database: notes_db
 * Host: localhost
 * Username: root
 * Password: root
 */

try {
    // Konfigurasi database
    $host = 'localhost';
    $db = 'notes_db';
    $user = 'root';
    $pass = 'root';
    
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    
    // Membuat koneksi PDO
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>
