<?php
require_once 'config.php';
checkLogin();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_roti = mysqli_real_escape_string($conn, $_GET['id']);

// Hapus data
$query = "DELETE FROM roti WHERE id_roti = '$id_roti'";
if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "Data roti berhasil dihapus!";
} else {
    $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($conn);
}

header("Location: index.php");
exit();
?>