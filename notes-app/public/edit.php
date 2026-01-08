<?php
/**
 * EDIT.PHP - Form Edit Catatan (UPDATE)
 */

require_once '../config/database.php';

$error = '';
$success = '';
$note = null;

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $error = 'ID Catatan tidak valid!';
} else {
    try {
        $sql = "SELECT id, judul, isi, tanggal FROM notes WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $note = $stmt->fetch();
        
        if (!$note) {
            $error = 'Catatan tidak ditemukan!';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $note) {
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
            $sql = "UPDATE notes SET judul = ?, isi = ?, tanggal = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi, $tanggal, $id]);
            
            $success = 'Catatan berhasil diupdate!';
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
    <title>Edit Catatan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Edit Catatan</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($note): ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label for="judul">Judul Catatan:</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($note['judul']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="isi">Isi Catatan:</label>
                <textarea id="isi" name="isi" rows="8" required><?php echo htmlspecialchars($note['isi']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $note['tanggal']; ?>" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Catatan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
        
        <?php endif; ?>
    </div>
</body>
</html>
