<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

try {
    $check_query = "SELECT COUNT(*) FROM peminjaman WHERE id_anggota = ? AND status = 'Dipinjam'";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(1, $id);
    $check_stmt->execute();
    $active_loans = $check_stmt->fetchColumn();

    if ($active_loans > 0) {
        $_SESSION['error'] = "Tidak dapat menghapus anggota! Masih ada peminjaman aktif.";
    } else {
        $query = "DELETE FROM anggota WHERE id_anggota = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Anggota berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus anggota!";
        }
    }
} catch(PDOException $exception) {
    $_SESSION['error'] = "Error: " . $exception->getMessage();
}

header("Location: index.php");
exit();
?>