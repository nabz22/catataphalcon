# 📝 CRUD Aplikasi Catatan (Notes)

Aplikasi web CRUD sederhana untuk mengelola catatan menggunakan **PHP** dan **MySQL**.

---

## 🚀 Quick Start (3 Langkah)

### 1️⃣ Copy Project
```bash
# Copy folder notes-app ke htdocs (XAMPP)
# atau directory web server Anda
```

### 2️⃣ Import Database
```sql
-- Buka http://localhost/phpmyadmin
-- Tab SQL > Paste isi file: notes-app/schema.sql
-- Klik "Go"
```

### 3️⃣ Buka di Browser
```
http://localhost/notes-app/public
```

✅ **SELESAI!**

---

## 📋 Fitur CRUD

| Operasi | Fungsi |
|---------|--------|
| **CREATE** | Tambah catatan baru |
| **READ** | Tampilkan daftar catatan |
| **UPDATE** | Edit catatan yang ada |
| **DELETE** | Hapus catatan |

---

## 📁 Struktur Folder

```
notes-app/
├── config/database.php          ← Koneksi MySQL
├── public/
│   ├── index.php                ← Halaman daftar (READ)
│   ├── create.php               ← Form tambah (CREATE)
│   ├── edit.php                 ← Form edit (UPDATE)
│   └── delete.php               ← Hapus catatan (DELETE)
├── assets/style.css             ← CSS styling
├── schema.sql                   ← SQL untuk database
└── README_SETUP.md              ← Panduan lengkap
```

---

## 📚 Dokumentasi

| File | Penjelasan |
|------|-----------|
| **README_SETUP.md** | 📖 **Panduan lengkap instalasi, running, & import SQL** |
| README.md | Dokumentasi lengkap project |
| CODE_EXPLANATION.md | Penjelasan teknis kode PHP |
| QUICK_START.md | Panduan cepat 5 menit |
| INSTALL.md | Panduan instalasi detail |

---

## ⚙️ Persyaratan

- **PHP** >= 7.4 (dengan PDO MySQL)
- **MySQL** >= 5.7
- **Web Server** (Apache, Nginx, atau PHP Built-in)

### Install Tools:
- **XAMPP** (All-in-one) - https://www.apachefriends.org/
- **WAMP** (Windows) - https://www.wampserver.com/
- **Docker** (Containerized)

---

## 🔧 Konfigurasi Database

Edit file: `notes-app/config/database.php`

```php
$host = 'localhost';    // Host MySQL
$db = 'notes_db';       // Nama database
$user = 'root';         // Username
$pass = '';             // Password (kosong untuk XAMPP default)
```

---

## ✅ Verifikasi Instalasi

```bash
# 1. Pastikan MySQL running
mysql -u root -p

# 2. Cek database dibuat
SHOW DATABASES;
# Output: harus ada 'notes_db'

# 3. Cek tabel dibuat
USE notes_db;
SHOW TABLES;
# Output: harus ada 'notes'

# 4. Buka browser
# http://localhost/notes-app/public
```

---

## 🧪 Test Aplikasi

1. **Create** → Klik "+ Tambah Catatan" → Isi form → Simpan
2. **Read** → Lihat daftar catatan di halaman utama
3. **Update** → Klik "Edit" → Ubah data → Update
4. **Delete** → Klik "Hapus" → Konfirmasi

---

## 🐛 Troubleshooting

| Error | Solusi |
|-------|--------|
| "Koneksi Database Gagal" | Start MySQL di XAMPP, cek config/database.php |
| "Table 'notes_db.notes' doesn't exist" | Import schema.sql di phpMyAdmin |
| CSS tidak muncul | Refresh browser (Ctrl+F5), cek path |
| Form tidak bisa submit | Pastikan PHP extension pdo_mysql aktif |

---

## 📖 Panduan Lengkap

**Untuk instalasi lengkap, running, & import SQL:**
👉 **Baca file: `notes-app/README_SETUP.md`**

---

## 💻 Alternative: PHP Built-in Server

```bash
cd notes-app
php -S localhost:8000 -t public

# Buka: http://localhost:8000
```

---

## 🎨 Fitur UI

- ✅ Responsive design (mobile-friendly)
- ✅ Gradient background
- ✅ Modern buttons & styling
- ✅ Form validation
- ✅ Smooth transitions

---

## 🔒 Security

- ✅ Prepared Statements (prevent SQL Injection)
- ✅ htmlspecialchars() (prevent XSS)
- ✅ Input Validation
- ✅ Error Handling

---

## 🎯 Teknologi

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+ / MariaDB
- **Frontend:** HTML5 + CSS3
- **ORM:** PDO (PHP Data Objects)

---

## 📊 Database Schema

```sql
CREATE DATABASE notes_db;

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🚀 Deployment

Untuk deploy ke production server:

1. Upload ke server via FTP/SSH
2. Configure database.php
3. Import schema.sql
4. Set proper permissions (chmod 755 folder, 644 file)
5. Configure SSL/HTTPS
6. Backup database regularly

---

## 📞 Support & Resources

- **PHP Documentation:** https://www.php.net/manual/
- **MySQL Documentation:** https://dev.mysql.com/doc/
- **MDN Web Docs:** https://developer.mozilla.org/

---

## 📝 Lisensi

Project ini bebas digunakan untuk keperluan pembelajaran dan pengembangan.

---
---

**👉 MULAI SEKARANG: Baca `notes-app/README_SETUP.md` untuk panduan lengkap!**

