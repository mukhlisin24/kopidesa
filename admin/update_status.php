<?php
include("../koneksi.php");

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Data tidak lengkap");
}

$id = intval($_GET['id']);
$status = $_GET['status'];

$allowed = ['diproses', 'selesai'];
if (!in_array($status, $allowed)) {
    die("Status tidak valid");
}

$cek = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan='$id'");
if (mysqli_num_rows($cek) == 0) {
    die("Pesanan tidak ditemukan");
}

mysqli_query($conn, "UPDATE pesanan SET status='$status' WHERE id_pesanan='$id'");

header("Location: admin_pesanan.php");