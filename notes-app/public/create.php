<?php
/**
 * CREATE.PHP - Form Tambah Catatan (CREATE)
 */

require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    
    if (empty($judul)) {
        $error = 'Judul tidak boleh kosong!';
    } elseif (empty($isi)) {
        $error = 'Isi catatan tidak boleh kosong!';
    } elseif (empty($tanggal)) {
        $error = 'Tanggal tidak boleh kosong!';
    } else {
        try {
            $sql = "INSERT INTO notes (judul, isi, tanggal) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi, $tanggal]);
            
            $success = 'Catatan berhasil ditambahkan!';
            header('Refresh: 2; url=index.php');
            
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Catatan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Tambah Catatan Baru</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else: ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label for="judul">Judul Catatan:</label>
                <input type="text" id="judul" name="judul" placeholder="Masukkan judul catatan" required>
            </div>
            
            <div class="form-group">
                <label for="isi">Isi Catatan:</label>
                <textarea id="isi" name="isi" placeholder="Masukkan isi catatan" rows="8" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</body>
</html>
