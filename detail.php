<?php
require_once 'config.php';
checkLogin();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_roti = mysqli_real_escape_string($conn, $_GET['id']);

// Update status jika ada request
if (isset($_POST['update_status'])) {
    $update_query = "UPDATE roti SET status = 'Habis' WHERE id_roti = '$id_roti'";
    mysqli_query($conn, $update_query);
}

// Ambil data roti
$query = "SELECT * FROM roti WHERE id_roti = '$id_roti'";
$result = mysqli_query($conn, $query);
$roti = mysqli_fetch_assoc($result);

if (!$roti) {
    header("Location: index.php");
    exit();
}

$gambar_url = !empty($roti['gambar_url']) ? $roti['gambar_url'] : 'https://placehold.co/600x400?text=Gambar+Kosong';
$status_class = $roti['status'] == 'Tersedia' ? 'status-tersedia' : 'status-habis';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Roti - Bakery Bliss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">☺ Bakery Bliss</div>
                <div class="nav-links">
                    <span>Selamat Datang, <?php echo $_SESSION['username']; ?></span>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Dashboard</a> &raquo; Detail Roti
        </div>
        
        <h1 style="margin-bottom: 1.5rem;">Detail Data Roti</h1>
        
        <div class="detail-container">
            <div class="detail-image">
                <img src="<?php echo $gambar_url; ?>" alt="<?php echo $roti['nama_roti']; ?>">
            </div>
            
            <div class="detail-info">
                <h2><?php echo $roti['nama_roti']; ?></h2>
                <p><strong>Jenis Roti:</strong> <?php echo $roti['jenis_roti']; ?></p>
                <p><strong>Harga:</strong> Rp <?php echo number_format($roti['harga'], 0, ',', '.'); ?></p>
                <p><strong>Status:</strong> <span class="status-badge <?php echo $status_class; ?>"><?php echo $roti['status']; ?></span></p>
                
                <?php if ($roti['status'] == 'Tersedia'): ?>
                <form method="POST" action="" style="margin-top: 1rem;">
                    <button type="submit" name="update_status" class="btn">Update Status Menjadi Habis</button>
                </form>
                <?php endif; ?>
                
                <div style="margin-top: 1.5rem;">
                    <a href="edit.php?id=<?php echo $roti['id_roti']; ?>" class="btn">Edit Data</a>
                    <a href="delete.php?id=<?php echo $roti['id_roti']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus Data</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>