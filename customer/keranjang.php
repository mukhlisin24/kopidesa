<?php
require_once '../function.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Kopi Desa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f2;
            margin: 0;
        }

        .sidebar {
            background-color: white;
            border-right: 1px solid #ddd;
            height: 100vh;
            position: fixed;
            width: 220px;
            padding: 30px 20px;
            z-index: 1000;
            text-align: center;
        }

        .content-area {
            margin-left: 220px;
            padding: 40px;
            min-height: 100vh;
        }

        .nav-link-custom {
            color: #555;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .nav-link-custom i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .nav-link-custom.active {
            background-color: #6f4e37;
            color: white !important;
        }

        .main-order-frame {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .order-header {
            background: #fdfdfd;
            padding: 20px;
            border-bottom: 2px dashed #eee;
            text-align: center;
        }

        .order-body {
            padding: 20px;
        }

        .cart-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f9f9f9;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .item-price {
            color: #888;
            font-size: 0.9rem;
        }

        .qty-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 20px;
        }

        .btn-qty {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-qty:hover {
            background: #eee;
        }

        .qty-number {
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        .btn-remove {
            color: #dc3545;
            margin-left: 15px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .order-footer {
            background: #fafafa;
            padding: 30px;
            border-top: 2px dashed #eee;
        }

        .summary-line,
        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-line {
            margin-bottom: 10px;
            color: #666;
        }

        .total-line {
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .total-label {
            font-weight: 800;
            font-size: 1.2rem;
        }

        .total-amount {
            font-weight: 800;
            font-size: 1.5rem;
            color: #198754;
        }

        .btn-checkout {
            background-color: #ffcc00;
            color: #000;
            border: none;
            font-weight: 800;
            border-radius: 12px;
            padding: 15px;
            width: 100%;
            transition: 0.3s;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .btn-checkout:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }

        .navbar-mobile {
            display: none;
            background-color: #6f4e37;
            color: white;
            padding: 15px;
            position: sticky;
            top: 0;
            z-index: 1100;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content-area {
                margin-left: 0;
                padding: 15px;
            }

            .navbar-mobile {
                display: flex;
            }

            .cart-row {
                flex-wrap: wrap;
                gap: 10px;
            }

            .item-info {
                flex-basis: 100%;
            }

            .qty-control {
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h4 class="fw-bold mb-5" style="color:#6f4e37;">KOPI DESA</h4>
        <a href="index.php?page=menu" class="nav-link-custom">
            <i class="bi bi-grid-fill"></i> Menu
        </a>

        <a href="index.php?page=keranjang" class="nav-link-custom active">
            <i class="bi bi-cart-fill"></i> Keranjang
        </a>
    </div>

    <div class="navbar-mobile">
        <span class="fw-bold">KERANJANG</span>

        <a href="index.php?page=menu" class="text-white text-decoration-none">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
    </div>

    <div class="content-area">
        <div class="main-order-frame">
            <div class="order-header">
                <h4 class="fw-bold m-0">Detail Pesanan Saya</h4>
            </div>
            <div class="order-body">
                <?php
                $total_bayar = 0;
                if (!empty($_SESSION['keranjang'])):
                    foreach ($_SESSION['keranjang'] as $id_menu => $jumlah):
                        $data = get_menu_by_id($id_menu);
                        if ($data):
                            $subtotal = $data['harga'] * $jumlah;
                            $total_bayar += $subtotal;
                            ?>
                            <div class="cart-row">
                                <div class="item-info">
                                    <div class="item-name"><?= htmlspecialchars($data['nama_menu']) ?></div>
                                    <div class="item-price">Rp <?= number_format($data['harga'], 0, ',', '.') ?></div>
                                </div>
                                <div class="qty-control">
                                    <?php if ($jumlah <= 1): ?>
                                        <button type="button" class="btn-qty border-0" data-bs-toggle="modal"
                                            data-bs-target="#hapusModal<?= $data['id_menu'] ?>">-</button>
                                    <?php else: ?>
                                        <a href="index.php?page=decrease_qty&id=<?= $data['id_menu'] ?>"
                                            class="btn-qty text-decoration-none">-</a>
                                    <?php endif; ?>

                                    <span class="qty-number"><?= intval($jumlah) ?></span>

                                    <a href="index.php?page=increase_qty&id=<?= $data['id_menu'] ?>"
                                        class="btn-qty text-decoration-none">
                                        +
                                    </a>
                                </div>

                                <div style="display:flex;align-items:center;min-width:120px;justify-content:flex-end;">

                                    <span class="fw-bold me-2">
                                        Rp <?= number_format($subtotal, 0, ',', '.') ?>
                                    </span>

                                    <button type="button" class="btn-remove border-0 bg-transparent" data-bs-toggle="modal"
                                        data-bs-target="#hapusModal<?= $data['id_menu'] ?>">

                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="hapusModal<?= $data['id_menu'] ?>" tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0" style="border-radius:15px;">

                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body text-center">

                                            <i class="bi bi-trash-fill text-danger" style="font-size:3rem;"></i>

                                            <p class="mt-3 mb-1 fw-semibold">
                                                Hapus menu dari keranjang?
                                            </p>

                                            <small class="text-muted">
                                                <?= htmlspecialchars($data['nama_menu']) ?>
                                            </small>
                                        </div>

                                        <div class="modal-footer border-0 justify-content-center">

                                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                                                style="border-radius:10px;">
                                                Batal
                                            </button>

                                            <a href="index.php?page=remove_from_cart&id=<?= $data['id_menu'] ?>"
                                                class="btn btn-danger px-4" style="border-radius:10px;">
                                                Hapus
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php
                        endif;
                    endforeach;
                else:
                    ?>

                    <div class="text-center py-5">

                        <i class="bi bi-cart-x text-muted" style="font-size:3rem;"></i>

                        <p class="text-muted mt-3">
                            Keranjang Anda kosong.
                        </p>

                        <a href="index.php?page=menu" class="btn btn-sm btn-dark px-4 py-2 mt-2"
                            style="border-radius:10px;">
                            Kembali ke Menu
                        </a>
                    </div>

                <?php endif; ?>

            </div>

            <?php if ($total_bayar > 0): ?>

                <div class="order-footer">

                    <div class="summary-line">
                        <span>Subtotal Pesanan</span>
                        <span>Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
                    </div>

                    <div class="summary-line">
                        <span>Pajak & Layanan</span>
                        <span class="text-success fw-bold">Gratis</span>
                    </div>

                    <div class="total-line">
                        <span class="total-label">TOTAL BAYAR</span>

                        <span class="total-amount">
                            Rp <?= number_format($total_bayar, 0, ',', '.') ?>
                        </span>
                    </div>

                    <button type="button" class="btn-checkout shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#modalKonfirmasi">

                        KONFIRMASI PESANAN SEKARANG
                    </button>

                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- Modal Checkout -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content" style="border-radius:15px;border:none;">

                <div class="modal-header" style="border-bottom:1px solid #eee;">

                    <h5 class="modal-title fw-bold">
                        Konfirmasi Pesanan
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center p-4">

                    <i class="bi bi-question-circle text-warning" style="font-size:3rem;"></i>

                    <p class="mt-3 fs-5">
                        Apakah Anda ingin melanjutkan pemesanan?
                    </p>
                </div>

                <div class="modal-footer border-0 justify-content-center gap-2">

                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        style="border-radius:8px;">
                        Batal
                    </button>

                    <a href="index.php?page=proses_checkout" class="btn btn-success px-4" style="border-radius:8px;">
                        Lanjut Pesan
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>