<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, a.nama_anggota, b.judul_buku 
          FROM peminjaman p 
          JOIN anggota a ON p.id_anggota = a.id_anggota 
          JOIN buku b ON p.id_buku = b.id_buku 
          ORDER BY p.tanggal_pinjam DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjaman</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">Perpustakaan Digital</div>
                <ul class="nav-links">
                    <li><a href="../index.php">Dashboard</a></li>
                    <li><a href="../buku/index.php">Buku</a></li>
                    <li><a href="../anggota/index.php">Anggota</a></li>
                    <li><a href="index.php" class="active">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Manajemen Peminjaman</h1>
                <a href="create.php" class="btn btn-success">Tambah Peminjaman</a>
            </div>

            <div class="card">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($peminjaman) > 0): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($peminjaman as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_anggota']); ?></td>
                                    <td><?php echo htmlspecialchars($item['judul_buku']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['tanggal_pinjam'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['tanggal_kembali'])); ?></td>
                                    <td>
                                        <span style="color: <?php echo $item['status'] == 'Dipinjam' ? '#e74c3c' : '#27ae60'; ?>; font-weight: bold;">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($item['status'] == 'Dipinjam'): ?>
                                            <a href="kembalikan.php?id=<?php echo $item['id_peminjaman']; ?>" class="btn btn-success" onclick="return confirm('Yakin buku sudah dikembalikan?')">Kembalikan</a>
                                        <?php endif; ?>
                                        <a href="edit.php?id=<?php echo $item['id_peminjaman']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete.php?id=<?php echo $item['id_peminjaman']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data peminjaman?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>