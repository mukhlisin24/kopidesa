<?php
include("../koneksi.php");

$id = $_GET['id'] ?? 0;

$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM pesanan WHERE id_pesanan='$id'
"));

if (!$pesanan) {
    echo "<div style='text-align:center;margin-top:50px;'>Pesanan tidak ditemukan</div>";
    exit;
}

/* GANTI sesuai tabel kamu (kalau belum ada detail_pesanan akan error) */
$detail = mysqli_query($conn, "
    SELECT dp.*, m.nama_menu, m.harga
    FROM pesanan_detail dp
    JOIN menu m ON dp.id_menu=m.id_menu
    WHERE dp.id_pesanan='$id'
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: Poppins, sans-serif;
            background: #f4f7f6;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #2c3e50;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;

            display: flex;
            flex-direction: column;
        }

        .logo {
            text-align: center;
            color: #f1c40f;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .nav-link-admin {
            color: #bdc3c7;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 25px;
            transition: 0.3s;
            font-size: 15px;
        }

        .nav-link-admin:hover,
        .nav-link-admin.active {
            background: #6f4e37;
            color: white;
        }

        .nav-link-admin i {
            font-size: 18px;
        }

        .main {
            margin-left: 250px;
            padding: 25px;
        }

        .card-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            overflow: hidden;
        }

        .header {
            background: #6f4e37;
            color: white;
            padding: 18px;
            text-align: center;
        }

        .body {
            padding: 20px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .total {
            text-align: right;
            font-weight: 800;
            font-size: 18px;
            margin-top: 10px;
        }

        .btn-back {
            display: block;
            text-align: center;
            background: #6f4e37;
            color: white;
            padding: 12px;
            text-decoration: none;
            margin: 15px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
            KOPI DESA
        </div>
        <nav class="d-flex flex-column h-100">
            <a href="index.php?page=dashboard" class="nav-link-admin">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            <a href="index.php?page=admin_menu" class="nav-link-admin">
                <i class="bi bi-cup-hot"></i>
                Data Menu
            </a>
            <a href="index.php?page=admin_pesanan" class="nav-link-admin active">
                <i class="bi bi-cart-check"></i>
                Pesanan
            </a>
            <a href="index.php?page=admin_riwayat" class="nav-link-admin">
                <i class="bi bi-clock-history"></i>
                Riwayat
            </a>
            <a href="index.php?page=logout" class="nav-link-admin text-danger mt-auto">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="card-box">
            <div class="header">
                <h5 class="mb-0">Detail Pesanan</h5>
                <small>Meja <?= $pesanan['no_meja'] ?> | <?= $pesanan['status'] ?></small>
            </div>

            <div class="body">
                <?php while ($d = mysqli_fetch_assoc($detail)) {
                    $subtotal = $d['harga'] * $d['jumlah'];
                    $total += $subtotal;
                    ?>
                    <div class="item">
                        <div>
                            <div class="fw-bold"><?= $d['nama_menu'] ?></div>
                            <small class="text-muted">
                                <?= $d['jumlah'] ?> x <?= number_format($d['harga'], 0, ',', '.') ?>
                            </small>
                        </div>
                        <div class="fw-bold">
                            Rp <?= number_format($subtotal, 0, ',', '.') ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="total">
                    TOTAL: Rp <?= number_format($total, 0, ',', '.') ?>
                </div>

            </div>

            <a href="index.php?page=admin_pesanan" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

        </div>

    </div>

</body>

</html>