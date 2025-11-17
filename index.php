<?php
require_once 'config.php';
checkLogin();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bakery Bliss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">☺ Bakery Bliss</div>
                <div class="nav-links">
                    <span>Selamat Datang, <?php echo $_SESSION['username']; ?></span>
                    <a href="add.php" class="btn">Tambah Roti</a>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Data Menu Roti</h1>
        
        <?php
        $query = "SELECT * FROM roti ORDER BY id_roti DESC";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="menu-grid">';
            while ($roti = mysqli_fetch_assoc($result)) {
                $gambar_url = !empty($roti['gambar_url']) ? $roti['gambar_url'] : 'https://placehold.co/600x400?text=Gambar+Kosong';
                $status_class = $roti['status'] == 'Tersedia' ? 'status-tersedia' : 'status-habis';
                
                echo '
                <div class="menu-card">
                    <img src="' . $gambar_url . '" alt="' . $roti['nama_roti'] . '">
                    <div class="menu-card-content">
                        <div class="menu-card-title">' . $roti['nama_roti'] . '</div>
                        <div class="menu-card-info">Jenis: ' . $roti['jenis_roti'] . '</div>
                        <div class="menu-card-price">Rp ' . number_format($roti['harga'], 0, ',', '.') . '</div>
                        <div class="status-badge ' . $status_class . '">' . $roti['status'] . '</div>
                        <div class="menu-card-actions">
                            <a href="detail.php?id=' . $roti['id_roti'] . '" class="btn">Detail</a>
                            <a href="edit.php?id=' . $roti['id_roti'] . '" class="btn btn-secondary">Edit</a>
                            <a href="delete.php?id=' . $roti['id_roti'] . '" class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>
                        </div>
                    </div>
                </div>';
            }
            echo '</div>';
        } else {
            echo '<div class="card">Data menu roti tidak ditemukan.</div>';
        }
        ?>
    </div>
</body>
</html>