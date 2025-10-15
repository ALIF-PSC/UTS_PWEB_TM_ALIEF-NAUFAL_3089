<?php
session_start();
include 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$total_buku = $db->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$total_anggota = $db->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$total_peminjaman = $db->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'Dipinjam'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">Perpustakaan Digital</div>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="buku/index.php">Buku</a></li>
                    <li><a href="anggota/index.php">Anggota</a></li>
                    <li><a href="peminjaman/index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="card">
                <h1>Dashboard Perpustakaan</h1>
                <p>Selamat datang di Sistem Informasi Perpustakaan Digital</p>
                
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <h3><?php echo $total_buku; ?></h3>
                        <p>Total Buku</p>
                    </div>
                    <div class="dashboard-card">
                        <h3><?php echo $total_anggota; ?></h3>
                        <p>Total Anggota</p>
                    </div>
                    <div class="dashboard-card">
                        <h3><?php echo $total_peminjaman; ?></h3>
                        <p>Sedang Dipinjam</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>