<?php

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$query = "SELECT * FROM menu";

if (!empty($search)) {

    $query .= "
    WHERE nama_menu LIKE '%$search%'
    OR kategori_menu LIKE '%$search%'";
}

$query .= " ORDER BY id_menu DESC";

$menus = mysqli_query($conn, $query);

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

    <title>Kelola Menu</title>

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

        /* ================= MAIN ================= */

        .main-content {
            margin-left: 230px;
            padding: 25px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
        }

        /* ================= BUTTON ================= */

        .btn-add {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-add:hover {
            background: #27ae60;
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

        /* ================= IMAGE ================= */

        .menu-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* ================= BADGE ================= */

        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .habis {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ================= ACTION BUTTON ================= */

        .btn-delete {
            background: #e74c3c;
            border: none;
            color: white;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        /* ================= MODAL ================= */

        .modal-content {
            border-radius: 14px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #eee;
        }

        .modal-footer {
            border-top: 1px solid #eee;
        }

        /* ================= RESPONSIVE ================= */

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

        .status-select {
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            cursor: pointer;
            text-align: center;
        }

        .status-select.tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .status-select.tidak-tersedia {
            background: #fee2e2;
            color: #991b1b;
        }

        .menu-habis {
            background: #fff1f1 !important;
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
            <a href="index.php?page=admin_menu" class="nav-link-admin active">
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

    <!-- ================= MAIN CONTENT ================= -->

    <div class="main-content">

        <div class="page-header">

            <h3 class="page-title">
                Kelola Menu
            </h3>

            <div class="d-flex gap-2 align-items-center">

                <form method="GET" class="d-flex">

                    <input type="hidden" name="page" value="admin_menu">

                    <input type="text" name="search" class="form-control" placeholder="Cari menu..."
                        value="<?= htmlspecialchars($search) ?>">

                    <button type="submit" class="btn btn-dark ms-2">

                        <i class="bi bi-search"></i>

                    </button>

                    <a href="index.php?page=admin_menu" class="btn btn-secondary ms-2">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </form>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">

                    <i class="bi bi-plus-lg"></i>
                    Tambah Menu

                </button>

            </div>

        </div>

        <!-- ================= TABLE ================= -->

        <div class="table-card">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th style="text-align: center;">Status</th>
                        <th width="90">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($m = mysqli_fetch_assoc($menus)) { ?>

                        <tr class="<?= $m['status'] == 'tidak tersedia' ? 'menu-habis' : '' ?>">

                            <td>

                                <img src="../assets/<?= $m['gambar'] ?: 'default.jpg' ?>" class="menu-img">

                            </td>

                            <td>

                                <strong>
                                    <?= $m['nama_menu'] ?>
                                </strong>

                            </td>

                            <td>

                                <?= $m['kategori_menu'] ?>

                            </td>

                            <td>

                                Rp
                                <?= number_format($m['harga'], 0, ',', '.') ?>

                            </td>

                            <td>

                                <form action="index.php?page=update_status_menu" method="POST">

                                    <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">

                                    <select name="status"
                                        class="form-select form-select-sm status-select <?= $m['status'] == 'tidak tersedia' ? 'tidak-tersedia' : 'tersedia' ?>"
                                        onchange="this.form.submit()">
                                        <option value="tersedia" <?= $m['status'] == 'tersedia' ? 'selected' : '' ?>>
                                            Tersedia
                                        </option>
                                        <option value="tidak tersedia" <?= $m['status'] == 'tidak tersedia' ? 'selected' : '' ?>>
                                            Habis
                                        </option>
                                    </select>

                                </form>

                            </td>

                            <td>

                                <a href="index.php?page=hapus_menu&id=<?= $m['id_menu'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus menu ini?')">

                                    <button class="btn-delete">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- ================= MODAL ================= -->

    <div class="modal fade" id="modalTambah" tabindex="-1">

        <div class="modal-dialog">

            <form action="index.php?page=tambah_menu_aksi" method="POST" enctype="multipart/form-data"
                class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Menu Baru
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Menu
                        </label>

                        <input type="text" name="nama" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Kategori
                        </label>

                        <select name="kategori" class="form-select">

                            <option>Coffee</option>
                            <option>Non-Coffee</option>
                            <option>Snack</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Harga
                        </label>

                        <input type="number" name="harga" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <input type="text" name="deskripsi" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="tersedia">
                                Tersedia
                            </option>

                            <option value="tidak tersedia">
                                Habis
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Foto Produk
                        </label>

                        <input type="file" name="gambar" class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit" class="btn btn-primary">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>