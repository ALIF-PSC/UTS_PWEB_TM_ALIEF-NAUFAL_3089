<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$anggota_query = "SELECT * FROM anggota WHERE status = 'Aktif' ORDER BY nama_anggota";
$buku_query = "SELECT * FROM buku WHERE stok > 0 ORDER BY judul_buku";

$anggota_stmt = $db->prepare($anggota_query);
$anggota_stmt->execute();
$anggota = $anggota_stmt->fetchAll(PDO::FETCH_ASSOC);

$buku_stmt = $db->prepare($buku_query);
$buku_stmt->execute();
$buku = $buku_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    try {
        $check_stock = "SELECT stok FROM buku WHERE id_buku = ?";
        $check_stmt = $db->prepare($check_stock);
        $check_stmt->bindParam(1, $_POST['id_buku']);
        $check_stmt->execute();
        $current_stock = $check_stmt->fetchColumn();

        if ($current_stock <= 0) {
            $_SESSION['error'] = "Stok buku habis!";
        } else {
            $db->beginTransaction();

            $query = "INSERT INTO peminjaman SET id_anggota=:id_anggota, id_buku=:id_buku, tanggal_pinjam=:tanggal_pinjam, tanggal_kembali=:tanggal_kembali, status=:status";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(":id_anggota", $_POST['id_anggota']);
            $stmt->bindParam(":id_buku", $_POST['id_buku']);
            $stmt->bindParam(":tanggal_pinjam", $_POST['tanggal_pinjam']);
            $stmt->bindParam(":tanggal_kembali", $_POST['tanggal_kembali']);
            $stmt->bindParam(":status", $_POST['status']);
            
            if ($stmt->execute()) {
                $update_stock = "UPDATE buku SET stok = stok - 1 WHERE id_buku = ?";
                $update_stmt = $db->prepare($update_stock);
                $update_stmt->bindParam(1, $_POST['id_buku']);
                $update_stmt->execute();

                $db->commit();
                $_SESSION['success'] = "Peminjaman berhasil dibuat!";
                header("Location: index.php");
                exit();
            }
        }
    } catch(PDOException $exception) {
        $db->rollBack();
        $_SESSION['error'] = "Error: " . $exception->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peminjaman</title>
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
                    <li><a href="index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Tambah Peminjaman</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="create.php" method="POST">
                    <div class="form-group">
                        <label for="id_anggota">Anggota *</label>
                        <select class="form-control" id="id_anggota" name="id_anggota" required>
                            <option value="">Pilih Anggota</option>
                            <?php foreach ($anggota as $a): ?>
                                <option value="<?php echo $a['id_anggota']; ?>">
                                    <?php echo htmlspecialchars($a['nama_anggota']) . ' - ' . $a['email']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_buku">Buku *</label>
                        <select class="form-control" id="id_buku" name="id_buku" required>
                            <option value="">Pilih Buku</option>
                            <?php foreach ($buku as $b): ?>
                                <option value="<?php echo $b['id_buku']; ?>" data-stock="<?php echo $b['stok']; ?>">
                                    <?php echo htmlspecialchars($b['judul_buku']) . ' - Stok: ' . $b['stok']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_pinjam">Tanggal Pinjam *</label>
                        <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_kembali">Tanggal Kembali *</label>
                        <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Dipinjam" selected>Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn">Batal</a>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('tanggal_pinjam').addEventListener('change', function() {
            var pinjam = new Date(this.value);
            var kembali = new Date(pinjam);
            kembali.setDate(kembali.getDate() + 7);
            document.getElementById('tanggal_kembali').value = kembali.toISOString().split('T')[0];
        });
    </script>
</body>
</html>