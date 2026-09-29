<?php
include("../koneksi.php");

$id = $_GET['id'];

mysqli_query($conn, "UPDATE pesanan SET status='Diproses' WHERE id_pesanan='$id'");

header("Location: admin_pesanan.php");