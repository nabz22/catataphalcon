# ✅ CHECKLIST INSTALASI & RUNNING

Gunakan checklist ini untuk memastikan semua langkah instalasi sudah benar.

---

## 🎯 PHASE 1: PERSIAPAN ENVIRONMENT

### Instalasi Tools

- [ ] Install XAMPP / WAMP / Docker
  - Download dari: https://www.apachefriends.org/
  - Ikuti wizard installation
  - Pilih komponen: Apache, MySQL, PHP

- [ ] Verifikasi instalasi:
  ```bash
  php -v          # Cek versi PHP
  mysql -u root   # Cek MySQL bisa diakses
  ```

- [ ] XAMPP/WAMP sudah berjalan:
  - [ ] Apache running (hijau di Control Panel)
  - [ ] MySQL running (hijau di Control Panel)

---

## 📁 PHASE 2: COPY PROJECT

### Setup Folder

- [ ] Copy folder `notes-app` ke:
  - Windows XAMPP: `C:\xampp\htdocs\notes-app`
  - Windows WAMP: `C:\wamp64\www\notes-app`
  - Linux: `/var/www/html/notes-app`
  - macOS: `/Library/WebServer/Documents/notes-app`

- [ ] Verifikasi folder structure:
  ```
  notes-app/
  ├── config/
  │   └── database.php ✓
  ├── public/
  │   ├── index.php ✓
  │   ├── create.php ✓
  │   ├── edit.php ✓
  │   └── delete.php ✓
  ├── assets/
  │   └── style.css ✓
  └── schema.sql ✓
  ```

---

## 🔧 PHASE 3: KONFIGURASI DATABASE CONNECTION

### Edit Konfigurasi

- [ ] Buka file: `notes-app/config/database.php`

- [ ] Set konfigurasi sesuai environment:

  **Untuk XAMPP Default:**
  ```php
  $host = 'localhost';
  $db = 'notes_db';
  $user = 'root';
  $pass = '';  // Kosong untuk XAMPP default
  ```

  **Untuk WAMP Default:**
  ```php
  $host = 'localhost';
  $db = 'notes_db';
  $user = 'root';
  $pass = '';  // Kosong untuk WAMP default
  ```

  **Untuk Docker:**
  ```php
  $host = 'db';
  $db = 'notes_db';
  $user = 'root';
  $pass = 'root';
  ```

- [ ] Save file

---

## 🗄️ PHASE 4: IMPORT DATABASE

### Metode A: phpMyAdmin (RECOMMENDED)

- [ ] Buka browser: `http://localhost/phpmyadmin`

- [ ] Login:
  - [ ] Username: `root`
  - [ ] Password: (kosong atau sesuai konfigurasi)
  - [ ] Klik "Go"

- [ ] Buat database:
  - [ ] Klik "Databases" (di atas) atau "+ Create Database"
  - [ ] Nama: `notes_db`
  - [ ] Charset: `utf8mb4_unicode_ci`
  - [ ] Klik "Create"

- [ ] Import schema:
  - [ ] Pilih database `notes_db`
  - [ ] Klik tab "SQL" (di atas)
  - [ ] Buka file: `notes-app/schema.sql`
  - [ ] Copy semua isinya
  - [ ] Paste di text area phpMyAdmin
  - [ ] Klik "Go" / "Execute"
  - [ ] Muncul pesan hijau: "Your SQL query has been executed successfully" ✓

- [ ] Verifikasi:
  - [ ] Tab "Databases" muncul `notes_db`
  - [ ] Di dalam `notes_db` ada tabel `notes`
  - [ ] Klik tabel `notes`, lihat struktur (id, judul, isi, tanggal)

---

### Metode B: MySQL Command Line

```bash
# 1. Login MySQL
mysql -u root -p
# Tekan Enter jika password kosong

# 2. Jalankan script
source C:/Users/ThinkPad%20T14%20G1/nazmi1/notes-app/schema.sql;

# 3. Verifikasi
SHOW DATABASES;  # Harus ada notes_db
USE notes_db;
SHOW TABLES;     # Harus ada notes
DESC notes;      # Lihat struktur
EXIT;
```

- [ ] Database `notes_db` terbuat
- [ ] Tabel `notes` terbuat
- [ ] Struktur tabel:
  - id (INT, PRIMARY KEY, AUTO_INCREMENT)
  - judul (VARCHAR 255)
  - isi (TEXT)
  - tanggal (DATE)

---

## 🚀 PHASE 5: RUNNING APLIKASI

### Akses Aplikasi

**Opsi A: XAMPP/WAMP**
- [ ] Apache sudah running di XAMPP/WAMP Control Panel
- [ ] Buka browser
- [ ] Ketik URL: `http://localhost/notes-app/public`
- [ ] Tekan Enter
- [ ] Halaman aplikasi muncul ✓

**Opsi B: PHP Built-in Server**
- [ ] Buka terminal/command prompt
- [ ] Masuk ke folder: `cd C:\Users\ThinkPad T14 G1\nazmi1\notes-app`
- [ ] Jalankan: `php -S localhost:8000 -t public`
- [ ] Buka browser: `http://localhost:8000`
- [ ] Halaman aplikasi muncul ✓

**Opsi C: Docker**
- [ ] Docker sudah running
- [ ] Jalankan: `docker-compose up -d`
- [ ] Buka browser: `http://localhost:8080`
- [ ] Halaman aplikasi muncul ✓

---

## 🧪 PHASE 6: TEST SEMUA FITUR CRUD

### Test CREATE (Tambah Catatan)

- [ ] Klik tombol "+ Tambah Catatan" di halaman utama
- [ ] Form "Tambah Catatan" terbuka
- [ ] Isi form dengan data:
  - [ ] Judul: "Test Catatan"
  - [ ] Isi: "Ini adalah catatan test untuk verifikasi"
  - [ ] Tanggal: (pilih hari ini)
- [ ] Klik "Simpan Catatan"
- [ ] Verifikasi:
  - [ ] Muncul pesan "Catatan berhasil ditambahkan!" ✓
  - [ ] Redirect ke halaman daftar ✓
  - [ ] Catatan muncul di tabel ✓

### Test READ (Lihat Daftar)

- [ ] Di halaman utama (`index.php`):
  - [ ] Tabel daftar catatan terlihat
  - [ ] Catatan yang dibuat muncul di tabel
  - [ ] Kolom: No, Judul, Isi, Tanggal, Aksi ✓
  - [ ] Format tanggal: dd-mm-yyyy ✓
  - [ ] Ada tombol "Edit" dan "Hapus" untuk setiap catatan ✓

### Test UPDATE (Edit Catatan)

- [ ] Klik tombol "Edit" pada catatan test
- [ ] Form "Edit Catatan" terbuka dengan data ter-isi
- [ ] Ubah data:
  - [ ] Judul: "Test Catatan - UPDATED"
  - [ ] Isi: "Catatan sudah diupdate"
  - [ ] Tanggal: (ubah ke hari lain)
- [ ] Klik "Update Catatan"
- [ ] Verifikasi:
  - [ ] Muncul pesan "Catatan berhasil diupdate!" ✓
  - [ ] Redirect ke halaman daftar ✓
  - [ ] Data di tabel sudah berubah ✓

### Test DELETE (Hapus Catatan)

- [ ] Di halaman daftar, klik tombol "Hapus" pada catatan test
- [ ] Dialog konfirmasi muncul: "Yakin ingin menghapus?"
- [ ] Klik "OK"
- [ ] Verifikasi:
  - [ ] Catatan hilang dari tabel ✓
  - [ ] Redirect ke halaman daftar ✓
  - [ ] Pesan sukses (opsional) ✓

---

## 📊 PHASE 7: VERIFIKASI DATABASE

### Cek Data di Database

```bash
# Login MySQL
mysql -u root -p notes_db

# Lihat semua catatan
SELECT * FROM notes;

# Lihat jumlah catatan
SELECT COUNT(*) FROM notes;

# Lihat struktur tabel
DESC notes;

# Lihat catatan dengan filter
SELECT * FROM notes WHERE tanggal = '2024-01-08';
```

- [ ] MySQL query berhasil dijalankan
- [ ] Data catatan muncul di hasil query
- [ ] Jumlah catatan sesuai dengan yang dibuat di aplikasi

---

## 🎯 TROUBLESHOOTING

### Jika ada Error

| Error | Checkbox | Solusi |
|-------|----------|--------|
| "Koneksi Database Gagal" | [ ] | Cek MySQL running, config/database.php |
| "Table doesn't exist" | [ ] | Import schema.sql di phpMyAdmin |
| Halaman Blank | [ ] | Cek error (F12), refresh (Ctrl+F5) |
| CSS tidak muncul | [ ] | Refresh browser (Ctrl+F5), cek path |
| Form tidak submit | [ ] | Cek PHP extension pdo_mysql aktif |

---

## ✅ FINAL CHECKLIST

Sebelum dinyatakan SELESAI, pastikan:

- [ ] Folder `notes-app` sudah di htdocs / web directory
- [ ] File `config/database.php` sudah dikonfigurasi sesuai environment
- [ ] Database `notes_db` sudah dibuat
- [ ] Tabel `notes` sudah dibuat dengan struktur benar
- [ ] Bisa akses halaman: `http://localhost/notes-app/public` tanpa error
- [ ] Halaman menampilkan tabel daftar catatan (meski masih kosong)
- [ ] Test CREATE: berhasil tambah catatan
- [ ] Test READ: catatan muncul di tabel
- [ ] Test UPDATE: berhasil edit catatan
- [ ] Test DELETE: berhasil hapus catatan
- [ ] UI tampil dengan baik (CSS ter-load, layout rapi)
- [ ] Tidak ada error di browser console (F12)

---

## 🎉 STATUS: SIAP DIGUNAKAN

Jika semua checklist di atas ✅ SELESAI, maka aplikasi CRUD Notes **SUDAH SIAP DIGUNAKAN**!

---

## 📚 Dokumentasi Lanjutan

Setelah instalasi selesai, baca:

| File | Untuk Mempelajari |
|------|------------------|
| README_SETUP.md | Panduan lengkap instalasi, running, & import SQL |
| README.md | Overview project lengkap |
| CODE_EXPLANATION.md | Penjelasan teknis setiap baris kode PHP |
| QUICK_START.md | Quick reference & tips |

---

**Tanggal Update:** January 8, 2024
**Version:** 1.0

**SELAMAT! Aplikasi CRUD Notes siap digunakan! 🚀**

