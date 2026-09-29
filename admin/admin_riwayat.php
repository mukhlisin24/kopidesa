<?php
// ================= RIWAYAT PESANAN =================

$riwayat = mysqli_query(
    $conn,
    "SELECT * FROM pesanan 
     WHERE status='selesai'
     ORDER BY id_pesanan DESC"
);

// ================= TOTAL PESANAN MENUNGGU =================

$pending = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id_pesanan 
     FROM pesanan 
     WHERE status='menunggu' and tanggal >= CURDATE()"
));
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Penjualan</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICON -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- FLATICON -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f6;
            overflow-x: hidden;
        }

        /* ================= SIDEBAR ================= */

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

        /* ================= MAIN CONTENT ================= */

        .main-content {
            margin-left: 230px;
            padding: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        /* ================= TABLE CARD ================= */

        .table-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
        }

        th {
            background: #f8f9fa !important;
            padding: 14px !important;
            font-size: 14px;
            color: #555;
        }

        td {
            padding: 14px !important;
            vertical-align: middle;
            font-size: 14px;
        }

        /* ================= BADGE ================= */

        .badge-success-custom {
            background: #d1fae5;
            color: #065f46;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ================= TOTAL ================= */

        .total-text {
            font-weight: bold;
            color: #27ae60;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width:768px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

</head>

<body>

    <!-- ================= SIDEBAR ================= -->

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
            <a href="index.php?page=admin_pesanan" class="nav-link-admin">
                <i class="bi bi-cart-check"></i>
                Pesanan
                <span class="badge bg-danger ms-auto">
                    <?= $pending ?>
                </span>
            </a>
            <a href="index.php?page=admin_riwayat" class="nav-link-admin active">
                <i class="bi bi-clock-history"></i>
                Riwayat
            </a>
            <a href="index.php?page=logout" class="nav-link-admin text-danger mt-auto">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- ================= MAIN CONTENT ================= -->

    <div class="main-content">

        <h3 class="page-title">
            Riwayat Penjualan
        </h3>

        <div class="table-card">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Meja</th>
                        <th>Total Bayar</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($r = mysqli_fetch_assoc($riwayat)) { ?>

                        <tr>

                            <td>

                                <strong>
                                    #<?= $r['id_pesanan'] ?>
                                </strong>

                            </td>

                            <td>

                                <?= date('d M Y, H:i', strtotime($r['tanggal'])) ?>

                            </td>

                            <td>

                                Meja <?= $r['no_meja'] ?>

                            </td>

                            <td class="total-text">

                                Rp
                                <?= number_format($r['total_harga'], 0, ',', '.') ?>

                            </td>

                            <td>

                                <span class="badge-success-custom">

                                    SELESAI

                                </span>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>