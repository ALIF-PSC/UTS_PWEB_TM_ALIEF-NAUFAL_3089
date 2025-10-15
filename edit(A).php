<?php
session_start();
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');
$query = "SELECT * FROM anggota WHERE id_anggota = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    $_SESSION['error'] = "Anggota tidak ditemukan!";
    header("Location: index.php");
    exit();
}

if ($_POST) {
    try {
        $query = "UPDATE anggota SET nama_anggota=:nama_anggota, email=:email, telepon=:telepon, alamat=:alamat, tanggal_daftar=:tanggal_daftar, status=:status WHERE id_anggota=:id_anggota";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":nama_anggota", $_POST['nama_anggota']);
        $stmt->bindParam(":email", $_POST['email']);
        $stmt->bindParam(":telepon", $_POST['telepon']);
        $stmt->bindParam(":alamat", $_POST['alamat']);
        $stmt->bindParam(":tanggal_daftar", $_POST['tanggal_daftar']);
        $stmt->bindParam(":status", $_POST['status']);
        $stmt->bindParam(":id_anggota", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Anggota berhasil diupdate!";
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
    <title>Edit Anggota</title>
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
                <h1 class="page-title">Edit Anggota</h1>
                <a href="index.php" class="btn">Kembali</a>
            </div>

            <div class="card">
                <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                    <div class="form-group">
                        <label for="nama_anggota">Nama Anggota *</label>
                        <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" value="<?php echo htmlspecialchars($anggota['nama_anggota']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($anggota['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($anggota['telepon']); ?>" placeholder="08123456789">
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($anggota['alamat']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal_daftar">Tanggal Daftar *</label>
                        <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" value="<?php echo $anggota['tanggal_daftar']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif" <?php echo $anggota['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Non-Aktif" <?php echo $anggota['status'] == 'Non-Aktif' ? 'selected' : ''; ?>>Non-Aktif</option>
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