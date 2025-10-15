<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

try {
    $db->beginTransaction();

    $get_book_query = "SELECT id_buku FROM peminjaman WHERE id_peminjaman = ?";
    $get_book_stmt = $db->prepare($get_book_query);
    $get_book_stmt->bindParam(1, $id);
    $get_book_stmt->execute();
    $id_buku = $get_book_stmt->fetchColumn();

    $update_loan = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = ?";
    $update_loan_stmt = $db->prepare($update_loan);
    $update_loan_stmt->bindParam(1, $id);
    $update_loan_stmt->execute();

    $update_stock = "UPDATE buku SET stok = stok + 1 WHERE id_buku = ?";
    $update_stock_stmt = $db->prepare($update_stock);
    $update_stock_stmt->bindParam(1, $id_buku);
    $update_stock_stmt->execute();

    $db->commit();
    $_SESSION['success'] = "Buku berhasil dikembalikan!";
    
} catch(PDOException $exception) {
    $db->rollBack();
    $_SESSION['error'] = "Error: " . $exception->getMessage();
}

header("Location: index.php");
exit();
?>