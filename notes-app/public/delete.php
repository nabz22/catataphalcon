<?php
/**
 * DELETE.PHP - Hapus Catatan (DELETE)
 */

require_once '../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php?delete=error');
    exit;
}

try {
    $checkSql = "SELECT id FROM notes WHERE id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$id]);
    
    if ($checkStmt->fetch()) {
        $deleteSql = "DELETE FROM notes WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$id]);
        
        header('Location: index.php?delete=success');
        exit;
    } else {
        header('Location: index.php?delete=notfound');
        exit;
    }
    
} catch (PDOException $e) {
    header('Location: index.php?delete=error');
    exit;
}
?>
