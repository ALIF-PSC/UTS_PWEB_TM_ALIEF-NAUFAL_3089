<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');
$query = "SELECT * FROM buku WHERE id_buku = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    $_SESSION['error'] = "Buku tidak ditemukan!";
    header("Location: index.php");
    exit();
}

if ($_POST) {
    try {
        $query = "UPDATE buku SET judul_buku=:judul_buku, pengarang=:pengarang, penerbit=:penerbit, tahun_terbit=:tahun_terbit, isbn=:isbn, stok=:stok WHERE id_buku=:id_buku";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":judul_buku", $_POST['judul_buku']);
        $stmt->bindParam(":pengarang", $_POST['pengarang']);
        $stmt->bindParam(":penerbit", $_POST['penerbit']);
        $stmt->bindParam(":tahun_terbit", $_POST['tahun_terbit']);
        $stmt->bindParam(":isbn", $_POST['isbn']);
        $stmt->bindParam(":stok", $_POST['stok']);
        $stmt->bindParam(":id_buku", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Buku berhasil diupdate!";
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
    <title>Edit Buku</title>
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
                <h1 class="page-title">Edit Buku</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                    <div class="form-group">
                        <label for="judul_buku">Judul Buku</label>
                        <input type="text" class="form-control" id="judul_buku" name="judul_buku" value="<?php echo htmlspecialchars($buku['judul_buku']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($buku['pengarang']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?php echo htmlspecialchars($buku['penerbit']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?php echo $buku['tahun_terbit']; ?>" min="1900" max="2024">
                    </div>
                    
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($buku['isbn']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $buku['stok']; ?>" min="0">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="index.php" class="btn">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>