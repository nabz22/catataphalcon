# CRUD Catatan - Aplikasi Phalcon Lengkap

Aplikasi web CRUD (Create, Read, Update, Delete) sederhana untuk manajemen catatan menggunakan Framework Phalcon dengan arsitektur MVC.

## 📋 Fitur Aplikasi

- ✅ **Tambah Catatan** - Buat catatan baru dengan judul, isi, dan tanggal
- ✅ **Lihat Daftar** - Tampilkan semua catatan dalam bentuk grid card yang menarik
- ✅ **Edit Catatan** - Ubah data catatan yang sudah dibuat
- ✅ **Hapus Catatan** - Hapus catatan dengan konfirmasi
- ✅ **Flash Messages** - Notifikasi sukses/error untuk setiap aksi
- ✅ **Responsive Design** - Tampilan optimal di desktop, tablet, dan mobile

## 🏗️ Struktur Aplikasi

```
phalcon-notes/
├── app/
│   ├── controllers/
│   │   ├── IndexController.php
│   │   └── NotesController.php          ✓ Controller untuk CRUD
│   ├── models/
│   │   └── Notes.php                    ✓ Model ORM Phalcon
│   ├── views/
│   │   ├── index/
│   │   │   └── index.phtml
│   │   └── notes/
│   │       ├── index.phtml              ✓ Daftar catatan
│   │       ├── create.phtml             ✓ Form tambah
│   │       └── edit.phtml               ✓ Form edit
│   └── config/
│       ├── config.php                   ✓ Konfigurasi database
│       ├── router.php                   ✓ Routing
│       └── services.php                 ✓ Service container
├── public/
│   ├── css/
│   │   └── style.css                    ✓ Styling aplikasi
│   └── index.php                        ✓ Entry point
├── database/
│   └── init.sql                         ✓ SQL initialization
├── Dockerfile                           ✓ Docker image config
├── docker-compose.yml                   ✓ Docker compose
└── README_APLIKASI.md                   ✓ Dokumentasi ini
```

## 🗄️ Database

**Nama Database:** `notes_db`

**Tabel:** `notes`

```sql
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi LONGTEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Konfigurasi Koneksi:**
- Host: `db` (dalam Docker)
- Username: `root`
- Password: `root`
- Database: `notes_db`

## 🚀 Cara Menjalankan

### 1. Build dan Run Docker

```bash
docker-compose up -d --build
```

Ini akan:
- Build Docker image untuk PHP/Apache/Phalcon
- Menjalankan MySQL container dengan database notes_db
- Eksekusi `database/init.sql` untuk setup tabel dan sample data

### 2. Akses Aplikasi

Buka browser dan navigasi ke:

```
http://localhost:8080
```

Anda akan diarahkan otomatis ke `/notes` yang menampilkan daftar catatan.

### 3. Akses phpMyAdmin (Optional)

Untuk mengelola database via GUI:

```
http://localhost:8090
```

- Username: `root`
- Password: `root`

## 📖 Panduan Penggunaan

### Melihat Daftar Catatan

1. Buka `http://localhost:8080`
2. Halaman akan menampilkan semua catatan dalam bentuk card grid
3. Setiap card menampilkan: judul, preview isi, tanggal, dan tombol aksi

### Tambah Catatan Baru

1. Klik tombol **"+ Tambah Catatan"** di halaman daftar
2. Isi form:
   - **Judul**: Masukkan judul catatan (wajib diisi)
   - **Isi Catatan**: Masukkan isi catatan (wajib diisi)
   - **Tanggal**: Pilih tanggal (default: hari ini)
3. Klik **"Simpan Catatan"**
4. Jika berhasil, akan melihat notifikasi success dan diarahkan ke daftar

### Edit Catatan

1. Di halaman daftar, klik tombol **"Edit"** pada card catatan
2. Form edit akan muncul dengan data saat ini
3. Ubah data sesuai kebutuhan
4. Klik **"Perbarui Catatan"**
5. Jika berhasil, akan diarahkan ke daftar dengan notifikasi success

### Hapus Catatan

1. Di halaman daftar, klik tombol **"Hapus"** pada card catatan
2. Konfirmasi pop-up akan muncul: "Yakin ingin menghapus catatan ini?"
3. Klik **"OK"** untuk menghapus
4. Catatan akan dihapus dan daftar refresh otomatis

## 🔧 Perintah Docker Penting

### Melihat status container

```bash
docker-compose ps
```

### Melihat logs aplikasi

```bash
docker-compose logs -f app
```

### Akses shell container aplikasi

```bash
docker-compose exec app bash
```

### Akses MySQL shell

```bash
docker-compose exec db mysql -uroot -proot notes_db
```

### Stop container

```bash
docker-compose stop
```

### Hapus container dan volumes

```bash
docker-compose down -v
```

### Restart container

```bash
docker-compose restart
```

## 📝 Penjelasan Kode

### Model: Notes.php

- Menggunakan Phalcon ORM dengan mapping tabel `notes`
- Properti model sesuai dengan field tabel database
- Validasi sederhana: judul dan isi wajib diisi
- Auto-timestamp untuk `created_at` dan `updated_at`

### Controller: NotesController.php

**indexAction()**
- Mengambil semua catatan dari database
- Urutkan berdasarkan tanggal terbaru
- Pass data ke view `notes/index.phtml`

**createAction()**
- Menampilkan form jika request GET
- Menerima data form dan menyimpan ke database jika POST
- Validasi model otomatis pada saat save
- Flash message untuk sukses/error
- Redirect ke halaman daftar setelah sukses

**editAction($id)**
- Cek keberadaan catatan berdasarkan ID
- Menampilkan form dengan data saat ini jika GET
- Update data jika POST
- Flash message untuk feedback
- Redirect setelah sukses

**deleteAction($id)**
- Cek keberadaan catatan
- Hapus dari database
- Flash message dan redirect

### View: Phalcon PHTML

Menggunakan template engine PHP native (.phtml):
- Syntax sederhana: `<?php ?>` untuk PHP code
- Akses controller data via `$this->variableName`
- Flash messages otomatis dari controller

### Routing: router.php

Menggunakan Phalcon Router untuk define URL patterns:
- `/notes` → indexAction()
- `/notes/create` → createAction() (GET: form, POST: simpan)
- `/notes/edit/{id}` → editAction($id) (GET: form, POST: update)
- `/notes/delete/{id}` → deleteAction($id)

### Config: config.php

Database configuration dengan support environment variables:
- DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME
- Default value fallback jika env var tidak tersedia
- Charset UTF-8MB4 untuk support emoji dan karakter spesial

## 🎨 Styling

CSS yang digunakan:
- Modern gradient background
- Card-based layout dengan grid responsive
- Smooth transitions dan hover effects
- Mobile-first responsive design
- Alert messages dengan color coding (success/error)

## 🐛 Troubleshooting

### Aplikasi tidak bisa akses database

```bash
# Cek koneksi database
docker-compose exec app ping db

# Test koneksi MySQL
docker-compose exec app mysql -h db -u root -proot -e "SELECT 1"
```

### Halaman menampilkan error 500

```bash
# Lihat error detail di logs
docker-compose logs -f app

# Cek folder cache writable
docker-compose exec app chmod -R 775 /var/www/html/cache
```

### Tabel tidak ada (error: Table 'notes_db.notes' doesn't exist)

```bash
# Re-run init.sql
docker-compose exec db mysql -u root -proot < database/init.sql
```

### Port 8080 sudah digunakan

Edit `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Ubah port host dari 8080 ke 8081
```

Kemudian akses `http://localhost:8081`

## 📚 Teknologi yang Digunakan

- **Framework**: Phalcon 5.0
- **PHP**: 8.1
- **Database**: MySQL 8.0
- **Web Server**: Apache
- **Container**: Docker & Docker Compose
- **Frontend**: HTML5 + CSS3

## ✨ Fitur Tambahan yang Bisa Dikembangkan

- [ ] Autentikasi user
- [ ] Permission/authorization
- [ ] Kategori/tag untuk catatan
- [ ] Search dan filter
- [ ] Export ke PDF/CSV
- [ ] Backup database
- [ ] API REST untuk mobile app
- [ ] Real-time sync dengan WebSocket

## 📄 Lisensi

Aplikasi ini bebas digunakan untuk pembelajaran dan pengembangan.

---

**Dibuat dengan ❤️ menggunakan Phalcon Framework**
