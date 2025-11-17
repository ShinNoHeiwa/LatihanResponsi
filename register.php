<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];
    
    // Validasi
    if (empty($username) || empty($password) || empty($re_password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $re_password) {
        $error = "Konfirmasi password tidak sama!";
    } else {
        // Cek apakah username sudah ada
        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // Hash password dan simpan user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bakery Bliss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 400px; margin: 2rem auto;">
            <h2 style="text-align: center; margin-bottom: 1.5rem; color: #e91e63;">Register Admin</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="re_password">Re-Password</label>
                    <input type="password" id="re_password" name="re_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Register</button>
            </form>
            
            <div style="text-align: center; margin-top: 1rem;">
                <a href="login.php" class="btn btn-secondary">Menuju ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>