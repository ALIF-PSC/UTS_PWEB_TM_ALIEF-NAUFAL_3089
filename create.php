<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_POST) {
    try {
        $query = "INSERT INTO buku SET judul_buku=:judul_buku, pengarang=:pengarang, penerbit=:penerbit, tahun_terbit=:tahun_terbit, isbn=:isbn, stok=:stok";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":judul_buku", $_POST['judul_buku']);
        $stmt->bindParam(":pengarang", $_POST['pengarang']);
        $stmt->bindParam(":penerbit", $_POST['penerbit']);
        $stmt->bindParam(":tahun_terbit", $_POST['tahun_terbit']);
        $stmt->bindParam(":isbn", $_POST['isbn']);
        $stmt->bindParam(":stok", $_POST['stok']);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Buku berhasil ditambahkan!";
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
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">Perpustakaan Digital</div>
                <ul class="nav-links">
                    <li><a href="../index.php">Dashboard</a></li>
                    <li><a href="index.php">Buku</a></li>
                    <li><a href="../anggota/index.php">Anggota</a></li>
                    <li><a href="../peminjaman/index.php">Peminjaman</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Tambah Buku</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <form action="create.php" method="POST">
                    <div class="form-group">
                        <label for="judul_buku">Judul Buku</label>
                        <input type="text" class="form-control" id="judul_buku" name="judul_buku" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit">
                    </div>
                    
                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1900" max="2024">
                    </div>
                    
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" value="0">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>