<?php
require_once '../function.php';

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT * FROM menu
    WHERE id_menu = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='text-center mt-5'>Menu tidak ditemukan</div>";
    exit;
}

$gambar = !empty($data['gambar'])
    ? "../assets/" . htmlspecialchars($data['gambar'])
    : "https://via.placeholder.com/500x300?text=No+Image";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu - Kopi Desa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f2;
            color: #333;
            margin: 0;
        }

        /* Sidebar */
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

        .nav-link-custom {
            color: #555;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .nav-link-custom i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .nav-link-custom:hover {
            background-color: #f8f5f2;
            color: #6f4e37;
        }

        .nav-link-custom.active {
            background-color: #6f4e37;
            color: white !important;
        }

        .content-area {
            margin-left: 220px;
            padding: 25px;
        }

        .navbar-mobile {
            display: none;
            background-color: #6f4e37;
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1100;
        }

        /* Card Detail */
        .detail-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        /* Wrapper Gambar */
        .image-wrapper {
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            overflow: hidden;
        }

        /* Gambar */
        .detail-img {
            width: 100%;
            height: 350px;
            object-fit: contain;
            background-color: white;
            border-radius: 20px;
            display: block;
        }

        /* Body */
        .detail-body {
            padding: 25px;
        }

        .menu-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }

        .menu-category {
            color: #888;
            font-size: 0.95rem;
            margin-top: 5px;
        }

        .menu-price {
            color: #198754;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 10px;
        }

        .menu-description {
            margin-top: 20px;
            line-height: 1.8;
            color: #555;
            background-color: #f8f5f2;
            padding: 18px;
            border-radius: 15px;
        }

        /* Button */
        .btn-custom {
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-add {
            background-color: #ffcc00;
            color: black;
        }

        .btn-add:hover {
            background-color: #e6b800;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        /* Mobile */
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
                justify-content: space-between;
                align-items: center;
            }

            .detail-img {
                height: 250px;
            }

            .menu-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="fw-bold mb-5" style="color: #6f4e37;">
            KOPI DESA
        </h4>

        <a href="index.php?page=menu" class="nav-link-custom active">
            <i class="bi bi-grid-fill"></i>
            Menu
        </a>

        <a href="index.php?page=keranjang" class="nav-link-custom">
            <i class="bi bi-cart-fill"></i>
            Keranjang
        </a>
    </div>

    <!-- Navbar Mobile -->
    <div class="navbar-mobile">
        <span class="fw-bold">KOPI DESA</span>

        <a href="index.php?page=keranjang" class="text-white">
            <i class="bi bi-cart-fill fs-4"></i>
        </a>
    </div>

    <!-- Content -->
    <div class="content-area">

        <div class="detail-card">

            <!-- Gambar -->
            <div class="image-wrapper">
                <img src="<?= $gambar ?>" class="detail-img" alt="<?= htmlspecialchars($data['nama_menu']) ?>">
            </div>

            <!-- Detail -->
            <div class="detail-body">

                <div class="menu-title">
                    <?= htmlspecialchars($data['nama_menu']) ?>
                </div>

                <div class="menu-category">
                    <?= htmlspecialchars($data['kategori_menu']) ?>
                </div>

                <div class="menu-price">
                    Rp <?= number_format($data['harga'], 0, ',', '.') ?>
                </div>

                <div class="menu-description">
                    <?= htmlspecialchars($data['deskripsi'] ?? 'Belum ada deskripsi menu.') ?>
                </div>

                <!-- Button -->
                <div class="row mt-4 g-2">

                    <div class="col-md-6">
                        <a href="index.php?page=add_cart&id=<?= $data['id_menu'] ?>"
                            class="btn btn-custom btn-add w-100">

                            <i class="bi bi-cart-plus"></i>
                            Tambah ke Keranjang
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="index.php?page=menu" class="btn btn-custom btn-back w-100">

                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Menu
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>

</body>

</html>