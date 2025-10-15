<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_POST) {
    try {
        $query = "INSERT INTO anggota SET nama_anggota=:nama_anggota, email=:email, telepon=:telepon, alamat=:alamat, tanggal_daftar=:tanggal_daftar, status=:status";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":nama_anggota", $_POST['nama_anggota']);
        $stmt->bindParam(":email", $_POST['email']);
        $stmt->bindParam(":telepon", $_POST['telepon']);
        $stmt->bindParam(":alamat", $_POST['alamat']);
        $stmt->bindParam(":tanggal_daftar", $_POST['tanggal_daftar']);
        $stmt->bindParam(":status", $_POST['status']);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Anggota berhasil ditambahkan!";
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
    <title>Tambah Anggota</title>
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
                    <li><a href="index.php">Anggota</a></li>
                    <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Tambah Anggota</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <form action="create.php" method="POST">
                    <div class="form-group">
                        <label for="nama_anggota">Nama Anggota *</label>
                        <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="08123456789">
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_daftar">Tanggal Daftar *</label>
                        <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>