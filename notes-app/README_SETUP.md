# 📝 CRUD Aplikasi Catatan (Notes) - Setup & Running

Aplikasi web CRUD sederhana untuk mengelola catatan menggunakan PHP dan MySQL.

---

## 📋 Daftar Isi
1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Struktur Project](#struktur-project)
3. [Cara Instalasi](#cara-instalasi)
4. [Import Database](#import-database)
5. [Cara Running Aplikasi](#cara-running-aplikasi)
6. [Testing CRUD](#testing-crud)
7. [Troubleshooting](#troubleshooting)

---

## 🛠️ Persyaratan Sistem

Sebelum memulai, pastikan komputer Anda memiliki:

- **PHP** >= 7.4 (dengan extension PDO MySQL)
- **MySQL** >= 5.7 atau MariaDB
- **Web Server** (Apache, Nginx, atau gunakan PHP Built-in Server)
- **Browser Modern** (Chrome, Firefox, Edge, Safari)

### Tools yang Bisa Digunakan:
- **XAMPP** (Apache + MySQL + PHP) - Recommended untuk pemula
- **WAMP** (Windows Apache MySQL PHP)
- **Docker** (containerized)
- **PHP Built-in Server** (untuk development lokal)

---

## 📁 Struktur Project

```
notes-app/
├── config/
│   └── database.php              # Koneksi PDO ke MySQL
│
├── public/
│   ├── index.php                 # Tampilkan daftar catatan (READ)
│   ├── create.php                # Form tambah catatan (CREATE)
│   ├── edit.php                  # Form edit catatan (UPDATE)
│   └── delete.php                # Hapus catatan (DELETE)
│
├── assets/
│   └── style.css                 # CSS styling (responsive)
│
├── schema.sql                    # SQL untuk membuat database & tabel
├── README.md                     # Dokumentasi lengkap
├── INSTALL.md                    # Panduan instalasi detail
├── CODE_EXPLANATION.md           # Penjelasan teknis kode
├── QUICK_START.md                # Panduan cepat 5 menit
└── README_SETUP.md               # File ini
```

---

## 💾 Cara Instalasi

### Opsi 1: Menggunakan XAMPP (Recommended)

#### Langkah 1: Install XAMPP
```
1. Download XAMPP dari: https://www.apachefriends.org/
2. Install dengan next-next-finish
3. Pilih komponen: Apache, MySQL, PHP (harus ada)
```

#### Langkah 2: Copy Project
```bash
# Windows
xcopy /E notes-app "C:\xampp\htdocs\notes-app\"

# Atau gunakan File Explorer
# Copy folder notes-app ke: C:\xampp\htdocs\
```

#### Langkah 3: Start Apache & MySQL
```
1. Buka XAMPP Control Panel
2. Klik "Start" untuk:
   - Apache
   - MySQL
3. Status harus berubah menjadi "Running" (warna hijau)
```

---

### Opsi 2: Menggunakan PHP Built-in Server

Cocok untuk development di komputer lokal, tanpa perlu install Apache.

```bash
# Terminal/Command Prompt
cd C:\Users\ThinkPad T14 G1\nazmi1\notes-app

# Jalankan PHP server
php -S localhost:8000 -t public

# Buka browser: http://localhost:8000
```

---

### Opsi 3: Menggunakan WAMP

Sama seperti XAMPP, hanya pastikan folder notes-app di:
```
C:\wamp64\www\notes-app\
```

---

### Opsi 4: Menggunakan Docker

```bash
# Buat docker-compose.yml
version: '3.8'
services:
  web:
    image: php:7.4-apache
    ports:
      - "8080:80"
    volumes:
      - ./notes-app:/var/www/html
      
  db:
    image: mysql:5.7
    environment:
      MYSQL_DATABASE: notes_db
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"

# Jalankan
docker-compose up -d

# Akses: http://localhost:8080
```

---

## 🗄️ Import Database

### Metode 1: phpMyAdmin (Paling Mudah)

```
1. Buka browser: http://localhost/phpmyadmin
   (atau http://localhost:8080/phpmyadmin jika Docker)

2. Login:
   - Username: root
   - Password: (kosong untuk XAMPP, atau "root" untuk Docker)

3. Klik menu "SQL" di atas (atau "+ Create Database")

4. Buat database baru:
   - Nama: notes_db
   - Charset: utf8mb4_unicode_ci
   - Klik "Create"

5. Pilih database notes_db yang baru dibuat

6. Buka tab "SQL"

7. Copy isi file schema.sql:
   - Buka file: notes-app/schema.sql
   - Copy semua isinya

8. Paste ke text area phpMyAdmin

9. Klik "Go" atau tombol "Execute"

10. Muncul pesan hijau ✓ "Your SQL query has been executed successfully"
```

---

### Metode 2: MySQL Command Line

```bash
# Windows - buka Command Prompt atau PowerShell
# Pastikan MySQL sudah running

# Login ke MySQL
mysql -u root -p

# Jika diminta password, tekan Enter (kosong untuk XAMPP default)

# Jalankan script
source C:/Users/ThinkPad%20T14%20G1/nazmi1/notes-app/schema.sql;

# atau gunakan < untuk input redirection
mysql -u root < C:\Users\ThinkPad T14 G1\nazmi1\notes-app\schema.sql

# Verifikasi database dibuat
SHOW DATABASES;
# Output harus menampilkan: notes_db

# Verifikasi tabel dibuat
USE notes_db;
SHOW TABLES;
# Output harus menampilkan: notes

# Lihat struktur tabel
DESC notes;
```

---

### Metode 3: Menggunakan MySQL Workbench

```
1. Buka MySQL Workbench
2. Buat connection ke server MySQL lokal
3. Buka connection
4. File > Open SQL Script
5. Pilih: notes-app/schema.sql
6. Klik ⚡ (Execute Selected Statement atau Ctrl+Enter)
7. Script akan execute
```

---

### Metode 4: Menggunakan DBeaver

```
1. Install DBeaver Community (gratis)
2. Buat connection ke MySQL:
   - Host: localhost
   - Port: 3306 (default)
   - Username: root
   - Password: (kosong atau sesuai konfigurasi)
3. Klik "Test Connection" untuk verifikasi
4. File > Open SQL Script > pilih schema.sql
5. Execute script
```

---

## 🚀 Cara Running Aplikasi

### Akses Aplikasi di Browser

Tergantung metode instalasi yang Anda gunakan:

#### XAMPP/WAMP
```
http://localhost/notes-app/public
```

#### PHP Built-in Server
```
http://localhost:8000
```

#### Docker
```
http://localhost:8080
```

---

### Konfigurasi Database Connection

Sebelum aplikasi berjalan, pastikan konfigurasi database sudah sesuai.

**Edit file:** `notes-app/config/database.php`

```php
<?php
try {
    // Konfigurasi database
    $host = 'localhost';        // Host database
    $db = 'notes_db';           // Nama database
    $user = 'root';             // Username MySQL
    $pass = 'root';             // Password MySQL
    
    // Untuk XAMPP/WAMP (password kosong):
    // $pass = '';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>
```

**Contoh Konfigurasi Berbagai Platform:**

| Platform | Host | Username | Password | Port |
|----------|------|----------|----------|------|
| XAMPP Default | localhost | root | (kosong) | 3306 |
| WAMP Default | localhost | root | (kosong) | 3306 |
| Docker | db | root | root | 3306 |
| Remote Server | db.example.com | username | password | 3306 |

---

## ✅ Testing CRUD

Setelah aplikasi berjalan, test setiap operasi CRUD:

### 1. Test CREATE (Tambah Catatan)

```
URL: http://localhost/notes-app/public/create.php

1. Isi form:
   - Judul: "Catatan Test"
   - Isi: "Ini adalah catatan test untuk verifikasi sistem"
   - Tanggal: (pilih hari ini)

2. Klik "Simpan Catatan"

3. Verifikasi: 
   - Muncul pesan "Catatan berhasil ditambahkan!"
   - Redirect ke halaman index
   - Catatan muncul di tabel daftar
```

### 2. Test READ (Lihat Daftar)

```
URL: http://localhost/notes-app/public/index.php

1. Verifikasi:
   - Halaman menampilkan tabel daftar catatan
   - Catatan yang dibuat tadi ada di tabel
   - Format tanggal dd-mm-yyyy
   - Ada tombol Edit dan Hapus untuk setiap catatan
```

### 3. Test UPDATE (Edit Catatan)

```
URL: http://localhost/notes-app/public/edit.php?id=1

1. Verifikasi:
   - Form terisi dengan data catatan
   - Ubah data:
     * Judul: "Catatan Test - SUDAH DIUPDATE"
     * Isi: "Data sudah diperbarui"

2. Klik "Update Catatan"

3. Verifikasi:
   - Muncul pesan "Catatan berhasil diupdate!"
   - Redirect ke halaman index
   - Data di tabel sudah berubah
```

### 4. Test DELETE (Hapus Catatan)

```
URL: http://localhost/notes-app/public/index.php

1. Klik tombol "Hapus" pada catatan test

2. Klik "OK" di dialog konfirmasi

3. Verifikasi:
   - Catatan hilang dari tabel
   - Redirect ke halaman index
```

---

## 🔍 Verifikasi Database via SQL

Setelah melakukan CRUD operations, verifikasi data di database:

```bash
# Login ke MySQL
mysql -u root -p notes_db

# Lihat semua catatan
SELECT * FROM notes;

# Lihat jumlah catatan
SELECT COUNT(*) FROM notes;

# Lihat catatan dengan filter
SELECT * FROM notes WHERE tanggal = '2024-01-08';

# Lihat struktur tabel
DESC notes;
```

---

## 📊 Contoh SQL Schema

File: `notes-app/schema.sql`

```sql
-- Buat database
CREATE DATABASE IF NOT EXISTS notes_db;
USE notes_db;

-- Buat tabel
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data sampel (opsional)
INSERT INTO notes (judul, isi, tanggal) VALUES
('Belanja Kebutuhan', 'Susu, roti, telur, sayuran, buah', '2024-01-07'),
('Project Deadline', 'Selesaikan project CRUD Notes sebelum 15 Januari', '2024-01-08'),
('Jadwal Meeting', 'Meeting dengan klien hari Jumat jam 10:00 pagi', '2024-01-06');
```

---

## 🐛 Troubleshooting

### Problem 1: "Koneksi Database Gagal"

**Penyebab:** MySQL tidak berjalan atau konfigurasi salah

**Solusi:**
```
1. Cek apakah MySQL sudah running:
   - XAMPP: Status di Control Panel harus "Running"
   - Command: mysql -u root -p (enter tanpa password)

2. Cek konfigurasi di config/database.php:
   - $host = 'localhost' ✓
   - $user = 'root' ✓
   - $pass = '' (untuk XAMPP default)

3. Restart MySQL:
   - XAMPP: Stop > Start
   - Command: net stop mysql80 && net start mysql80
```

---

### Problem 2: "Table 'notes_db.notes' doesn't exist"

**Penyebab:** Database atau tabel belum dibuat

**Solusi:**
```
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Verifikasi database notes_db ada
3. Jika tidak ada, buat dan import schema.sql
4. Verifikasi tabel notes ada
5. Jika tidak ada, run schema.sql lagi
```

---

### Problem 3: "Access denied for user 'root'@'localhost'"

**Penyebab:** Username/password salah

**Solusi:**
```
Cek password MySQL di config/database.php:
- XAMPP default: $pass = ''; (kosong)
- WAMP default: $pass = ''; (kosong)
- Docker: $pass = 'root';

Atau reset MySQL password:
mysql -u root
> SET PASSWORD FOR 'root'@'localhost' = PASSWORD('');
> FLUSH PRIVILEGES;
```

---

### Problem 4: "Fatal error: Allowed memory size exhausted"

**Penyebab:** PHP memory limit terlalu kecil

**Solusi:**
```php
// Tambah di awal file index.php atau create.php:
ini_set('memory_limit', '256M');
```

---

### Problem 5: CSS tidak muncul / halaman tidak ter-styling

**Penyebab:** CSS path salah atau file tidak ada

**Solusi:**
```
1. Buka DevTools di browser (F12)
2. Tab "Network"
3. Refresh halaman
4. Cek apakah style.css terload (status 200 OK)
5. Jika 404, periksa path di HTML:
   <link rel="stylesheet" href="../assets/style.css">

6. Pastikan folder assets/ dan file style.css ada
```

---

### Problem 6: Form tidak bisa di-submit

**Penyebab:** PHP tidak support PDO MySQL

**Solusi:**
```bash
# Cek PHP extensions:
php -m | findstr pdo

# Output harus ada:
# pdo
# pdo_mysql

# Jika tidak ada, aktifkan di php.ini:
# extension=pdo_mysql

# Restart Apache setelah edit php.ini
```

---

## 📝 Data Sample SQL

Untuk menambah data sample ke tabel:

```sql
INSERT INTO notes (judul, isi, tanggal) VALUES
('Catatan Pertama', 'Ini adalah catatan pertama saya tentang pembelajaran PHP dan MySQL.', '2024-01-07'),
('Belanja Mingguan', 'Susu, roti, telur, sayuran segar, buah-buahan, daging sapi.', '2024-01-08'),
('Project Deadline', 'Selesaikan project CRUD Notes sebelum 15 Januari 2024.', '2024-01-08'),
('Jadwal Meeting', 'Meeting dengan klien hari Jumat jam 10:00 pagi di kantor pusat.', '2024-01-06');
```

---

## 🔒 Security Notes

Aplikasi ini sudah menggunakan:
- ✅ Prepared Statements (prevent SQL Injection)
- ✅ htmlspecialchars() (prevent XSS)
- ✅ Input Validation
- ✅ Error Handling

**Untuk Production, tambahkan:**
- [ ] CSRF Token
- [ ] Session Management
- [ ] Rate Limiting
- [ ] Input Sanitization lebih ketat
- [ ] HTTPS/SSL
- [ ] Environment Variables untuk konfigurasi
- [ ] Database Backup Regular

---

## 📚 File Dokumentasi

| File | Isi |
|------|-----|
| **README_SETUP.md** | **File ini - Setup & Running** |
| README.md | Dokumentasi lengkap project |
| INSTALL.md | Panduan instalasi detail dengan troubleshooting |
| CODE_EXPLANATION.md | Penjelasan teknis kode PHP line by line |
| QUICK_START.md | Panduan cepat 5 menit |

---

## ✨ Fitur Aplikasi

- ✅ **Create** - Tambah catatan baru
- ✅ **Read** - Tampilkan daftar catatan
- ✅ **Update** - Edit catatan yang ada
- ✅ **Delete** - Hapus catatan
- ✅ **Responsive Design** - Mobile-friendly
- ✅ **Form Validation** - Validasi input
- ✅ **Modern UI** - Gradient background, modern buttons
- ✅ **Error Handling** - Tangani error dengan graceful

---

## 🎯 Next Steps

Setelah aplikasi berjalan:

1. **Test semua CRUD operations** (CREATE, READ, UPDATE, DELETE)
2. **Tambah beberapa catatan** untuk testing
3. **Baca CODE_EXPLANATION.md** untuk memahami kodenya
4. **Modify & customize** sesuai kebutuhan
5. **Deploy** ke server production (jika ingin live)

---

## 📞 Quick Reference

```bash
# Terminal shortcuts

# Start XAMPP MySQL
# Windows: XAMPP Control Panel > Start MySQL

# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE notes_db;

# Show databases
SHOW DATABASES;

# Use database
USE notes_db;

# Show tables
SHOW TABLES;

# Show table structure
DESC notes;

# Run SQL file
source notes-app/schema.sql;

# Exit MySQL
EXIT;

# Start PHP server
php -S localhost:8000 -t public
```

---

## 🎉 Selamat!

Aplikasi CRUD Notes sudah siap digunakan. Semoga bermanfaat untuk pembelajaran PHP dan MySQL!

**Last Updated:** January 8, 2024
**Version:** 1.0

Jika ada pertanyaan, silakan baca dokumentasi lengkap di README.md atau CODE_EXPLANATION.md.

