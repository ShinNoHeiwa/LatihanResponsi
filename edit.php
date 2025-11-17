<?php
require_once 'config.php';
checkLogin();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_roti = mysqli_real_escape_string($conn, $_GET['id']);
$error = '';
$success = '';

// Ambil data roti
$query = "SELECT * FROM roti WHERE id_roti = '$id_roti'";
$result = mysqli_query($conn, $query);
$roti = mysqli_fetch_assoc($result);

if (!$roti) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_roti = mysqli_real_escape_string($conn, $_POST['nama_roti']);
    $jenis_roti = mysqli_real_escape_string($conn, $_POST['jenis_roti']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $gambar_url = mysqli_real_escape_string($conn, $_POST['gambar_url']);
    
    // Validasi
    if (empty($nama_roti) || empty($jenis_roti) || empty($harga) || empty($status)) {
        $error = "Semua field harus diisi!";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = "Harga harus berupa angka positif!";
    } else {
        // Set default gambar jika kosong
        if (empty($gambar_url)) {
            $gambar_url = 'https://placehold.co/600x400?text=Gambar+Kosong';
        }
        
        $update_query = "UPDATE roti SET 
                        nama_roti = '$nama_roti', 
                        jenis_roti = '$jenis_roti', 
                        harga = '$harga', 
                        status = '$status', 
                        gambar_url = '$gambar_url' 
                        WHERE id_roti = '$id_roti'";
        
        if (mysqli_query($conn, $update_query)) {
            $success = "Data roti berhasil diperbarui!";
            // Refresh data
            $result = mysqli_query($conn, $query);
            $roti = mysqli_fetch_assoc($result);
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
    <title>Edit Roti - Bakery Bliss</title>
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
            <a href="index.php">Dashboard</a> &raquo; Edit Roti
        </div>
        
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; color: #e91e63;">Update Data Menu Roti: <?php echo $roti['nama_roti']; ?></h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_roti">Nama Roti</label>
                    <input type="text" id="nama_roti" name="nama_roti" class="form-control" value="<?php echo $roti['nama_roti']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="jenis_roti">Jenis Roti</label>
                    <select id="jenis_roti" name="jenis_roti" class="form-control" required>
                        <option value="Roti Manis" <?php echo $roti['jenis_roti'] == 'Roti Manis' ? 'selected' : ''; ?>>Roti Manis</option>
                        <option value="Roti Tawar" <?php echo $roti['jenis_roti'] == 'Roti Tawar' ? 'selected' : ''; ?>>Roti Tawar</option>
                        <option value="Roti Sobek" <?php echo $roti['jenis_roti'] == 'Roti Sobek' ? 'selected' : ''; ?>>Roti Sobek</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga Roti (Rp)</label>
                    <input type="number" id="harga" name="harga" class="form-control" min="0" value="<?php echo $roti['harga']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Status Roti</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Tersedia" <?php echo $roti['status'] == 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="Habis" <?php echo $roti['status'] == 'Habis' ? 'selected' : ''; ?>>Habis</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="gambar_url">Gambar (Link URL)</label>
                    <input type="url" id="gambar_url" name="gambar_url" class="form-control" value="<?php echo $roti['gambar_url']; ?>">
                </div>
                
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>