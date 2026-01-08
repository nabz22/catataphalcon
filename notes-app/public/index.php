<?php
/**
 * INDEX.PHP - Menampilkan Daftar Catatan (READ)
 */

require_once '../config/database.php';

try {
    $sql = "SELECT id, judul, isi, tanggal FROM notes ORDER BY tanggal DESC";
    $result = $pdo->query($sql);
    $notes = $result->fetchAll();
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Catatan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>📝 CRUD Catatan</h1>
        
        <a href="create.php" class="btn btn-primary">+ Tambah Catatan</a>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi Ringkas</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($notes) && count($notes) > 0): ?>
                    <?php $no = 1; foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($note['judul']); ?></td>
                        <td><?php echo htmlspecialchars(substr($note['isi'], 0, 50)) . '...'; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($note['tanggal'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $note['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?php echo $note['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Belum ada catatan. <a href="create.php">Tambah catatan sekarang</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
