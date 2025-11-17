<?php
session_start();

$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "ngajar24_latres_web-if-f";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>