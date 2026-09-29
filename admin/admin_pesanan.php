<?php
include("../koneksi.php");
date_default_timezone_set('Asia/Jakarta');

$data = mysqli_query($conn, "
    SELECT * FROM pesanan
    WHERE tanggal >= CURDATE()
    ORDER BY tanggal DESC
");

$pending = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id_pesanan 
     FROM pesanan 
     WHERE status='menunggu'"
));
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan Aktif</title>

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

        .main-content {
            margin-left: 230px;
            padding: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .table-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table th {
            background: #f8f9fa;
            font-size: 14px;
            color: #555;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .badge-status {
            width: 110px;
            height: 38px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .diproses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .btn-action {
            border: none;
            width: 110px;
            height: 40px;
            border-radius: 10px;
            color: white;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-green {
            background: #27ae60;
        }

        .btn-green:hover {
            background: #219150;
        }

        .btn-blue {
            background: #3498db;
        }

        .btn-blue:hover {
            background: #2980b9;
        }

        .btn-detail {
            background: #2c3e50;
        }

        .btn-detail:hover {
            background: #1f2d3a;
        }

        .modal-confirm .modal-content {
            border-radius: 16px;
            border: none;
        }

        .modal-confirm .modal-header {
            border-bottom: 1px solid #eee;
        }

        .modal-confirm .modal-footer {
            border-top: 1px solid #eee;
        }

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

    <div class="main-content">

        <h3 class="page-title">
            Pesanan Hari Ini (<?= date('d M Y') ?>)
        </h3>

        <div class="table-card">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Meja</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="280">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($d = mysqli_fetch_assoc($data)) { ?>

                            <tr>

                                <td>
                                    <?= date('d M Y H:i', strtotime($d['tanggal'])) ?>
                                </td>

                                <td>
                                    Meja <?= $d['no_meja'] ?>
                                </td>

                                <td class="fw-bold text-success">
                                    Rp <?= number_format($d['total_harga'], 0, ',', '.') ?>
                                </td>

                                <td>
                                    <span class="badge-status <?= strtolower($d['status']) ?>">
                                        <?= ucfirst($d['status']) ?>
                                    </span>
                                </td>

                                <td>

                                    <div class="d-flex gap-2 flex-wrap">

                                        <?php if ($d['status'] == 'menunggu') { ?>

                                            <button class="btn-action btn-green" data-bs-toggle="modal"
                                                data-bs-target="#modalDiproses<?= $d['id_pesanan'] ?>">

                                                <i class="bi bi-check-circle"></i>
                                                Konfirmasi

                                            </button>

                                        <?php } elseif ($d['status'] == 'diproses') { ?>

                                            <button class="btn-action btn-blue" data-bs-toggle="modal"
                                                data-bs-target="#modalSelesai<?= $d['id_pesanan'] ?>">

                                                <i class="bi bi-check2-all"></i>
                                                Selesaikan

                                            </button>

                                        <?php } else { ?>

                                            <div class="btn-action" style="background:#27ae60; cursor:default;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Selesai
                                            </div>

                                        <?php } ?>

                                        <a href="index.php?page=detail_pesanan&id=<?= $d['id_pesanan'] ?>"
                                            class="btn-action btn-detail">

                                            <i class="bi bi-eye-fill"></i>
                                            Detail

                                        </a>

                                    </div>

                                    <!-- MODAL DIPROSES -->

                                    <div class="modal fade modal-confirm" id="modalDiproses<?= $d['id_pesanan'] ?>"
                                        tabindex="-1">

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Konfirmasi Pesanan
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Yakin ingin memproses pesanan meja
                                                    <strong>
                                                        <?= $d['no_meja'] ?>
                                                    </strong> ?

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Batal

                                                    </button>

                                                    <a href="update_status.php?id=<?= $d['id_pesanan'] ?>&status=diproses"
                                                        class="btn btn-success">

                                                        Ya, Proses

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- MODAL SELESAI -->

                                    <div class="modal fade modal-confirm" id="modalSelesai<?= $d['id_pesanan'] ?>"
                                        tabindex="-1">

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Selesaikan Pesanan
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Pesanan meja
                                                    <strong>
                                                        <?= $d['no_meja'] ?>
                                                    </strong>
                                                    sudah selesai?

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                                        Batal

                                                    </button>

                                                    <a href="update_status.php?id=<?= $d['id_pesanan'] ?>&status=selesai"
                                                        class="btn btn-primary">

                                                        Ya, Selesaikan

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>