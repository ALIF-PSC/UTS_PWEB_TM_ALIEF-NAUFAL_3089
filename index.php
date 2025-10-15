<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM buku ORDER BY id_buku DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">Perpustakaan Digital</div>
                <ul class="nav-links">
                    <li><a href="../index.php">Dashboard</a></li>
                    <li><a href="index.php" class="active">Buku</a></li>
                    <li><a href="../anggota/index.php">Anggota</a></li>
                    <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Buku</h1>
                <a href="create.php" class="btn btn-success">Tambah Buku</a>
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

                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($buku) > 0): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($buku as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['judul_buku']); ?></td>
                                    <td><?php echo htmlspecialchars($item['pengarang']); ?></td>
                                    <td><?php echo htmlspecialchars($item['penerbit']); ?></td>
                                    <td><?php echo $item['tahun_terbit']; ?></td>
                                    <td><?php echo $item['stok']; ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $item['id_buku']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete.php?id=<?php echo $item['id_buku']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data buku</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>