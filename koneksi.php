<?php
$host = "localhost";
$user = "root";       // Sesuaikan dengan username database Anda
$pass = "";           // Sesuaikan dengan password database Anda
$db   = "kopdes_dinein";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>