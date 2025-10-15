<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

try {
    $query = "DELETE FROM buku WHERE id_buku = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Buku berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus buku!";
    }
} catch(PDOException $exception) {
    $_SESSION['error'] = "Error: " . $exception->getMessage();
}

header("Location: index.php");
exit();
?>