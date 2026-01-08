-- Create database
CREATE DATABASE IF NOT EXISTS notes_db;
USE notes_db;

-- Create notes table
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi LONGTEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO notes (judul, isi, tanggal) VALUES 
('Catatan Pertama', 'Ini adalah catatan pertama saya', '2024-01-01'),
('Meeting Notes', 'Hasil rapat hari ini sangat produktif', '2024-01-02'),
('Todo List', 'Perlu menyelesaikan project sebelum akhir minggu', '2024-01-03');
