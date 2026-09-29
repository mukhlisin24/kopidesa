<?php
$profit = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(total_harga) as total 
     FROM pesanan 
     WHERE status='selesai'"
))['total'] ?? 0;

$sale = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id_pesanan 
     FROM pesanan 
     WHERE status='selesai'"
));

$total_menu = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id_menu 
     FROM menu"
));

$pending = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id_pesanan 
     FROM pesanan 
     WHERE status='menunggu'"
));

$orders = mysqli_query(
    $conn,
    "SELECT * FROM pesanan
     WHERE status='menunggu'
     ORDER BY id_pesanan DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

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

        .card-custom {
            background: white;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
        }

        .green {
            background: #2ecc71;
        }

        .blue {
            background: #3498db;
        }

        .purple {
            background: #9b59b6;
        }

        .orange {
            background: #f39c12;
        }

        .label {
            color: #888;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .card-custom h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .order-box {
            background: white;
            border-radius: 14px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .order-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item strong {
            font-size: 15px;
        }

        .order-item p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .btn-confirm {
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            background: #27ae60;
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

        @media(max-width: 992px) {

            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        @media(max-width: 768px) {

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

            <a href="index.php?page=dashboard" class="nav-link-admin active">
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
            Dashboard
        </h3>

        <div class="row g-3">

            <div class="col-lg-3 col-md-6">

                <div class="card-custom">

                    <div class="icon-box green">
                        <i class="fi fi-br-dollar"></i>
                    </div>

                    <div>

                        <p class="label">
                            Profit
                        </p>

                        <h3>
                            Rp <?= number_format($profit, 0, ',', '.') ?>
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card-custom">

                    <div class="icon-box blue">
                        <i class="fi fi-br-chart-line-up"></i>
                    </div>

                    <div>

                        <p class="label">
                            Sale
                        </p>

                        <h3>
                            <?= $sale ?>
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card-custom">

                    <div class="icon-box purple">
                        <i class="fi fi-br-box"></i>
                    </div>

                    <div>

                        <p class="label">
                            Product
                        </p>

                        <h3>
                            <?= $total_menu ?>
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card-custom">

                    <div class="icon-box orange">
                        <i class="fi fi-br-time-fast"></i>
                    </div>

                    <div>

                        <p class="label">
                            Perlu Diproses
                        </p>

                        <h3>
                            <?= $pending ?>
                        </h3>

                    </div>

                </div>

            </div>

        </div>

        <div class="order-box">

            <div class="order-title">
                Pesanan Masuk
            </div>

            <?php while ($o = mysqli_fetch_assoc($orders)) { ?>

                <div class="order-item">

                    <div>

                        <strong>
                            Meja <?= $o['no_meja'] ?>
                        </strong>

                        <p>
                            Rp <?= number_format($o['total_harga'], 0, ',', '.') ?>
                        </p>

                    </div>

                    <button class="btn-confirm" data-bs-toggle="modal"
                        data-bs-target="#modalConfirm<?= $o['id_pesanan'] ?>">

                        Konfirmasi

                    </button>

                </div>

                <!-- MODAL -->

                <div class="modal fade modal-confirm" id="modalConfirm<?= $o['id_pesanan'] ?>" tabindex="-1">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Konfirmasi Pesanan
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body">

                                Yakin ingin memproses pesanan meja
                                <strong>
                                    <?= $o['no_meja'] ?>
                                </strong> ?

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <a href="confirm.php?id=<?= $o['id_pesanan'] ?>" class="btn btn-success">

                                    Ya, Konfirmasi

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>