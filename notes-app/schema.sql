-- ================================================
-- SQL untuk CRUD Aplikasi Notes
-- Database: notes_db
-- Tabel: notes
-- ================================================

-- 1. CREATE DATABASE
CREATE DATABASE IF NOT EXISTS notes_db;
USE notes_db;

-- 2. CREATE TABLE
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. INSERT DATA SAMPEL (OPTIONAL)
-- Uncomment untuk menambah data sampel

/*
INSERT INTO notes (judul, isi, tanggal) VALUES
('Catatan Pertama', 'Ini adalah catatan pertama saya. Berisi informasi penting yang perlu saya ingat.', '2024-01-07'),
('Catatan Ke-2', 'Catatan kedua tentang proyek yang sedang saya kerjakan dengan deadline minggu depan.', '2024-01-06'),
('Catatan Ke-3', 'Daftar belanja yang perlu dibeli: susu, telur, roti, dan buah-buahan segar.', '2024-01-05');
*/

-- 4. VERIFIKASI TABEL (untuk testing)
-- SELECT * FROM notes;
-- DESC notes;
