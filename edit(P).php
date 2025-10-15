<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');
$query = "SELECT p.*, a.nama_anggota, b.judul_buku, b.stok 
          FROM peminjaman p 
          JOIN anggota a ON p.id_anggota = a.id_anggota 
          JOIN buku b ON p.id_buku = b.id_buku 
          WHERE p.id_peminjaman = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();
$peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$peminjaman) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan!";
    header("Location: index.php");
    exit();
}

$anggota_query = "SELECT * FROM anggota WHERE status = 'Aktif' ORDER BY nama_anggota";
$buku_query = "SELECT * FROM buku WHERE stok > 0 OR id_buku = ? ORDER BY judul_buku";

$anggota_stmt = $db->prepare($anggota_query);
$anggota_stmt->execute();
$anggota = $anggota_stmt->fetchAll(PDO::FETCH_ASSOC);

$buku_stmt = $db->prepare($buku_query);
$buku_stmt->bindParam(1, $peminjaman['id_buku']);
$buku_stmt->execute();
$buku = $buku_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    try {
        $query = "UPDATE peminjaman SET id_anggota=:id_anggota, id_buku=:id_buku, tanggal_pinjam=:tanggal_pinjam, tanggal_kembali=:tanggal_kembali, status=:status WHERE id_peminjaman=:id_peminjaman";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":id_anggota", $_POST['id_anggota']);
        $stmt->bindParam(":id_buku", $_POST['id_buku']);
        $stmt->bindParam(":tanggal_pinjam", $_POST['tanggal_pinjam']);
        $stmt->bindParam(":tanggal_kembali", $_POST['tanggal_kembali']);
        $stmt->bindParam(":status", $_POST['status']);
        $stmt->bindParam(":id_peminjaman", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data peminjaman berhasil diupdate!";
            header("Location: index.php");
            exit();
        }
    } catch(PDOException $exception) {
        $_SESSION['error'] = "Error: " . $exception->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjaman</title>
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
                <h1 class="page-title">Edit Peminjaman</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                    <div class="form-group">
                        <label for="id_anggota">Anggota *</label>
                        <select class="form-control" id="id_anggota" name="id_anggota" required>
                            <option value="">Pilih Anggota</option>
                            <?php foreach ($anggota as $a): ?>
                                <option value="<?php echo $a['id_anggota']; ?>" <?php echo $a['id_anggota'] == $peminjaman['id_anggota'] ? 'selected' : ''; ?>>
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
                                <option value="<?php echo $b['id_buku']; ?>" <?php echo $b['id_buku'] == $peminjaman['id_buku'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($b['judul_buku']) . ' - Stok: ' . $b['stok']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_pinjam">Tanggal Pinjam *</label>
                        <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo $peminjaman['tanggal_pinjam']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_kembali">Tanggal Kembali *</label>
                        <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="<?php echo $peminjaman['tanggal_kembali']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Dipinjam" <?php echo $peminjaman['status'] == 'Dipinjam' ? 'selected' : ''; ?>>Dipinjam</option>
                            <option value="Dikembalikan" <?php echo $peminjaman['status'] == 'Dikembalikan' ? 'selected' : ''; ?>>Dikembalikan</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="index.php" class="btn">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>