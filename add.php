<?php
require_once 'config.php';
checkLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_roti = mysqli_real_escape_string($conn, $_POST['nama_roti']);
    $jenis_roti = mysqli_real_escape_string($conn, $_POST['jenis_roti']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $gambar_url = mysqli_real_escape_string($conn, $_POST['gambar_url']);
    
    // Validasi
    if (empty($nama_roti) || empty($jenis_roti) || empty($harga)) {
        $error = "Nama, jenis, dan harga harus diisi!";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = "Harga harus berupa angka positif!";
    } else {
        // Set default gambar jika kosong
        if (empty($gambar_url)) {
            $gambar_url = 'https://placehold.co/600x400?text=Gambar+Kosong';
        }
        
        // Status default Tersedia
        $status = 'Tersedia';
        
        $query = "INSERT INTO roti (nama_roti, jenis_roti, harga, status, gambar_url) 
                  VALUES ('$nama_roti', '$jenis_roti', '$harga', '$status', '$gambar_url')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Data roti berhasil ditambahkan!";
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Roti - Bakery Bliss</title>
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
            <a href="index.php">Dashboard</a> &raquo; Tambah Roti
        </div>
        
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; color: #e91e63;">Tambah Data Menu Roti Baru</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_roti">Nama Roti</label>
                    <input type="text" id="nama_roti" name="nama_roti" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="jenis_roti">Jenis Roti</label>
                    <select id="jenis_roti" name="jenis_roti" class="form-control" required>
                        <option value="">Pilih Jenis Roti</option>
                        <option value="Roti Manis">Roti Manis</option>
                        <option value="Roti Tawar">Roti Tawar</option>
                        <option value="Roti Sobek">Roti Sobek</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga Roti (Rp)</label>
                    <input type="number" id="harga" name="harga" class="form-control" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="gambar_url">Gambar URL (Opsional)</label>
                    <input type="url" id="gambar_url" name="gambar_url" class="form-control" placeholder="https://example.com/image.jpg">
                    <small>Jika kosong, akan menggunakan gambar default</small>
                </div>
                
                <button type="submit" class="btn">Simpan Data</button>
                <a href="index.php" class="btn btn-secondary">Reset Form</a>
            </form>
        </div>
    </div>
</body>
</html>