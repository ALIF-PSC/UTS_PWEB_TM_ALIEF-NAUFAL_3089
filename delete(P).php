<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

try {
    $get_query = "SELECT id_buku, status FROM peminjaman WHERE id_peminjaman = ?";
    $get_stmt = $db->prepare($get_query);
    $get_stmt->bindParam(1, $id);
    $get_stmt->execute();
    $peminjaman = $get_stmt->fetch(PDO::FETCH_ASSOC);

    $db->beginTransaction();

    $delete_query = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(1, $id);
    $delete_stmt->execute();

    if ($peminjaman['status'] == 'Dipinjam') {
        $update_stock = "UPDATE buku SET stok = stok + 1 WHERE id_buku = ?";
        $update_stmt = $db->prepare($update_stock);
        $update_stmt->bindParam(1, $peminjaman['id_buku']);
        $update_stmt->execute();
    }

    $db->commit();
    $_SESSION['success'] = "Data peminjaman berhasil dihapus!";
    
} catch(PDOException $exception) {
    $db->rollBack();
    $_SESSION['error'] = "Error: " . $exception->getMessage();
}

header("Location: index.php");
exit();
?>