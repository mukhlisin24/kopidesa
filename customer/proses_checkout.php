<?php
require_once '../function.php';

date_default_timezone_set('Asia/Jakarta');

// =======================
// VALIDASI KERANJANG
// =======================

if (empty($_SESSION['keranjang'])) {
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Keranjang Kosong</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">

        <style>
            body {
                background: #f8f5f2;
                font-family: Poppins, sans-serif;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .box {
                background: white;
                padding: 40px;
                border-radius: 18px;
                text-align: center;
                box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
                max-width: 420px;
            }
        </style>
    </head>

    <body>

        <div class="box">

            <h3 class="text-danger mb-3">
                Keranjang Kosong
            </h3>

            <p>
                Anda belum memilih menu.
            </p>

            <a href="index.php?page=menu"
                class="btn btn-dark mt-3">

                Kembali ke Menu

            </a>

        </div>

    </body>

    </html>

    <?php
    exit;
}

// =======================
// VALIDASI QR MEJA
// =======================

if (!isset($_SESSION['no_meja']) || empty($_SESSION['no_meja'])) {
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>QR Belum Dipindai</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">

        <style>
            body {
                background: #f8f5f2;
                font-family: Poppins, sans-serif;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .box {
                background: white;
                padding: 40px;
                border-radius: 18px;
                text-align: center;
                box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
                max-width: 450px;
            }
        </style>
    </head>

    <body>

        <div class="box">

            <h3 class="text-warning mb-3">
                Scan QR Meja Dulu
            </h3>

            <p>
                Anda belum scan QR meja.
                Silakan scan QR terlebih dahulu sebelum memesan.
            </p>

            <a href="index.php?page=home"
                class="btn btn-warning mt-3">

                Kembali

            </a>

        </div>

    </body>

    </html>

    <?php
    exit;
}

// =======================
// PROSES CHECKOUT
// =======================

$no_meja = intval($_SESSION['no_meja']);

$total_harga = 0;

$detail_pesanan = [];

// HITUNG TOTAL

foreach ($_SESSION['keranjang'] as $id_menu => $jumlah) {

    $data_menu = get_menu_by_id($id_menu);

    if ($data_menu) {

        $harga = $data_menu['harga'];

        $subtotal = $harga * $jumlah;

        $total_harga += $subtotal;

        $detail_pesanan[] = [

            'id_menu' => $id_menu,
            'jumlah' => $jumlah,
            'nama' => $data_menu['nama_menu'],
            'harga' => $harga,
            'subtotal' => $subtotal

        ];
    }
}

// =======================
// INSERT PESANAN
// =======================

$tanggal = date('Y-m-d H:i:s');

$status_awal = 'menunggu';

$query_pesanan = "
    INSERT INTO pesanan
    (
        no_meja,
        tanggal,
        total_harga,
        status
    )
    VALUES
    (
        $no_meja,
        '$tanggal',
        $total_harga,
        '$status_awal'
    )
";

if (mysqli_query($conn, $query_pesanan)) {

    // AMBIL ID PESANAN

    $id_pesanan_baru = mysqli_insert_id($conn);

    // INSERT DETAIL

    foreach ($detail_pesanan as $detail) {

        $id_m = $detail['id_menu'];

        $qty = $detail['jumlah'];

        $hrg = $detail['harga'];

        $subt = $detail['subtotal'];

        $query_detail = "
            INSERT INTO pesanan_detail
            (
                id_pesanan,
                id_menu,
                harga,
                jumlah,
                subtotal
            )
            VALUES
            (
                $id_pesanan_baru,
                $id_m,
                $hrg,
                $qty,
                $subt
            )
        ";

        mysqli_query($conn, $query_detail);
    }

    // SIMPAN SESSION STRUK

    $_SESSION['keranjang_last'] = $detail_pesanan;

    $_SESSION['pesanan_terakhir'] = $id_pesanan_baru;

    // HAPUS KERANJANG

    unset($_SESSION['keranjang']);

    // REDIRECT

    header("Location: index.php?page=checkout&status=success");

    exit;

} else {

    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Checkout Gagal</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">

        <style>
            body {
                background: #f8f5f2;
                font-family: Poppins, sans-serif;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .box {
                background: white;
                padding: 40px;
                border-radius: 18px;
                text-align: center;
                box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
                max-width: 450px;
            }
        </style>
    </head>

    <body>

        <div class="box">

            <h3 class="text-danger mb-3">
                Checkout Gagal
            </h3>

            <p>
                Terjadi kesalahan saat memproses pesanan.
            </p>

            <a href="index.php?page=keranjang"
                class="btn btn-danger mt-3">

                Kembali ke Keranjang

            </a>

        </div>

    </body>

    </html>

    <?php
}
?>