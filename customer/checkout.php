<?php
require_once '../function.php';

$is_success = isset($_GET['status']) && $_GET['status'] == 'success';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_success ? 'Pemesanan Berhasil' : 'Konfirmasi Pesanan' ?> - Kopi Desa</title>
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
            padding: 25px;
            border-bottom: 2px dashed #eee;
            text-align: center;
        }

        .order-body {
            padding: 20px 40px;
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

        .item-qty {
            color: #6f4e37;
            font-weight: 700;
            background: #fdf5e6;
            padding: 2px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin-left: 10px;
        }

        .item-price {
            color: #888;
            font-size: 0.9rem;
        }

        .order-footer {
            background: #fafafa;
            padding: 30px;
            border-top: 2px dashed #eee;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .btn-action {
            border: none;
            font-weight: 800;
            border-radius: 12px;
            padding: 15px;
            width: 100%;
            transition: 0.3s;
            font-size: 1.1rem;
            text-align: center;
            text-transform: uppercase;
            cursor: pointer;
        }

        .btn-warning-custom {
            background-color: #ffcc00;
            color: #000;
        }

        .btn-warning-custom:hover {
            background-color: #e6b800;
        }

        .btn-finish {
            background-color: #6f4e37;
            color: white;
            text-decoration: none;
            display: block;
        }

        .btn-finish:hover {
            background-color: #5d4037;
            color: white;
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
                display: flex !important;
            }

            .order-body {
                padding: 15px;
            }
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
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="fw-bold mb-5" style="color: #6f4e37;">KOPI DESA</h4>
        <a href="index.php?page=menu" class="nav-link-custom"><i class="bi bi-grid-fill"></i> Menu</a>
        <a href="index.php?page=keranjang" class="nav-link-custom active"><i class="bi bi-cart-fill"></i> Keranjang</a>
    </div>

    <div class="navbar-mobile">
        <span class="fw-bold"><?= $is_success ? 'STATUS PESANAN' : 'CHECKOUT' ?></span>
        <a href="index.php?page=menu" class="text-white"><i class="bi bi-house-door-fill fs-4"></i></a>
    </div>

    <div class="content-area">
        <form action="index.php?page=proses_checkout" method="POST">
            <div class="main-order-frame">
                <div class="order-header">
                    <?php if ($is_success): ?>
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold text-success mt-2">PEMESANAN MEJA <?= $_SESSION['no_meja'] ?> BERHASIL</h3>
                        <p class="text-muted mb-1">Terima kasih! Pesanan Anda sedang diteruskan ke dapur.</p>
                        <div class="badge bg-dark fs-5 mt-2 p-2">No. Antrean:
                            #INV-<?= $_SESSION['pesanan_terakhir'] ?? '00' ?></div>
                    <?php else: ?>
                        <h4 class="fw-bold m-0 text-uppercase">Konfirmasi Rincian Pesanan</h4>
                        <?php if (isset($_SESSION['no_meja']) && $_SESSION['no_meja'] > 0): ?>
                            <p class="text-muted mb-0 mt-2">Anda memesan dari <strong class="fs-5 text-success">Meja
                                    <?= htmlspecialchars($_SESSION['no_meja']) ?></strong></p>
                        <?php else: ?>
                            <p class="text-danger fw-bold mb-0 mt-2"><i class="bi bi-exclamation-triangle"></i> Nomor Meja Tidak
                                Ditemukan!</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="order-body">
                    <?php
                    $total_bayar = 0;
                    $items = ($is_success && isset($_SESSION['keranjang_last'])) ? $_SESSION['keranjang_last'] : ($_SESSION['keranjang'] ?? []);

                    if (!empty($items)):
                        foreach ($items as $id_menu => $jumlah):
                            if ($is_success) {
                                $nama = $jumlah['nama'] ?? 'Menu';
                                $harga = $jumlah['harga'] ?? 0;
                                $qty = $jumlah['jumlah'] ?? 0;
                                $subtotal = $jumlah['subtotal'] ?? 0;
                            } else {
                                // Memanggil fungsi dari function.php
                                $data = get_menu_by_id($id_menu);
                                $nama = $data ? $data['nama_menu'] : 'Menu Tidak Ditemukan';
                                $harga = $data ? $data['harga'] : 0;
                                $qty = intval($jumlah);
                                $subtotal = $harga * $qty;
                            }
                            $total_bayar += $subtotal;
                            ?>
                            <div class="cart-row">
                                <div class="item-info">
                                    <span class="item-name"><?= htmlspecialchars($nama) ?></span>
                                    <span class="item-qty">x<?= $qty ?></span>
                                    <div class="item-price">Rp <?= number_format($harga, 0, ',', '.') ?></div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                        <p class="text-center py-4 text-muted">Tidak ada rincian pesanan.</p>
                    <?php endif; ?>
                </div>

                <div class="order-footer">
                    <div class="total-line">
                        <span class="total-label text-uppercase">Total Pembayaran</span>
                        <span class="total-amount">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
                    </div>

                    <?php if ($is_success): ?>
                        <a href="index.php?page=home" class="btn-action btn-finish"
                            style="background-color: #6f4e37; color: white;">Selesai & Kembali</a>
                    <?php else: ?>
                        <?php if ($total_bayar > 0 && isset($_SESSION['no_meja']) && $_SESSION['no_meja'] > 0): ?>
                            <button type="submit" class="btn-action" style="background-color: #ffcc00; color: #000;">Proses
                                Pesanan ke Dapur</button>
                        <?php elseif (!isset($_SESSION['no_meja']) || $_SESSION['no_meja'] == 0): ?>
                            <a href="index.php?page=home" class="btn-action"
                                style="background-color: #dc3545; color: white; text-decoration:none;"><i
                                    class="bi bi-qr-code-scan"></i> Scan QR Code Meja Dulu</a>
                        <?php else: ?>
                            <a href="index.php?page=menu" class="btn-action btn-finish">Kembali ke Menu</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</body>

</html>