<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM anggota ORDER BY id_anggota DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggota</title>
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
                    <li><a href="index.php" class="active">Anggota</a></li>
                    <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Manajemen Anggota</h1>
                <a href="create.php" class="btn btn-success">Tambah Anggota</a>
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
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($anggota) > 0): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($anggota as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_anggota']); ?></td>
                                    <td><?php echo htmlspecialchars($item['email']); ?></td>
                                    <td><?php echo htmlspecialchars($item['telepon']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['tanggal_daftar'])); ?></td>
                                    <td>
                                        <span style="color: <?php echo $item['status'] == 'Aktif' ? '#27ae60' : '#e74c3c'; ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $item['id_anggota']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="delete.php?id=<?php echo $item['id_anggota']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus anggota?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data anggota</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>