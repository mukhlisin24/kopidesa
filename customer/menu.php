<?php
require_once '../function.php';

// Memanggil fungsi dari function.php
$menus = get_semua_menu();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Kopi Desa</title>
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

        /* CSS Sidebar Disamakan dengan Keranjang */
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
            padding: 20px;
        }

        .navbar-mobile {
            display: none;
            background-color: #6f4e37;
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .category-wrapper {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            -webkit-overflow-scrolling: touch;
        }

        .category-wrapper::-webkit-scrollbar {
            display: none;
        }

        .nav-pills-custom {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            padding: 0 15px;
        }

        .nav-link-item {
            color: #888;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            background: white;
            transition: 0.3s;
            display: inline-block;
        }

        .nav-link-item.active {
            background-color: #6f4e37;
            color: white;
            border-color: #6f4e37;
        }

        .search-wrapper {
            max-width: 500px;
            margin: 0 auto 20px auto;
            position: relative;
            padding: 0 15px;
        }

        .search-input {
            border-radius: 25px;
            padding: 12px 20px 12px 45px;
            border: 1px solid #ddd;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .menu-item-card {
            background: white;
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 5px;
            height: 100%;
        }

        .menu-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background-color: #eee;
        }

        .menu-info {
            padding: 10px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn-warning-custom {
            background-color: #ffcc00;
            color: #000;
            border: none;
            font-weight: 700;
            border-radius: 8px;
            font-size: 0.8rem;
            padding: 5px;
            width: 100%;
            transition: 0.2s;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .btn-warning-custom:hover {
            background-color: #e6b800;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .navbar-mobile {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .category-wrapper {
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="fw-bold mb-5" style="color: #6f4e37;">KOPI DESA</h4>

        <a href="index.php?page=menu" class="nav-link-custom active">
            <i class="bi bi-grid-fill"></i> Menu
        </a>
        <a href="index.php?page=keranjang" class="nav-link-custom">
            <i class="bi bi-cart-fill"></i> Keranjang
        </a>
    </div>

    <div class="navbar-mobile">
        <span class="fw-bold">KOPI DESA</span>
        <a href="index.php?page=keranjang" class="text-white"><i class="bi bi-cart-fill fs-4"></i></a>
    </div>

    <div class="content-area">
        <div class="category-wrapper">
            <div class="nav-pills-custom" id="categoryFilter">
                <div class="nav-link-item active" data-filter="all">Semua</div>
                <div class="nav-link-item" data-filter="coffee">Coffee</div>
                <div class="nav-link-item" data-filter="non-coffee">Non-Coffee</div>
                <div class="nav-link-item" data-filter="snack">Snack</div>
            </div>
        </div>

        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input shadow-sm" placeholder="Cari menu...">
        </div>

        <div class="row g-3" id="menuList">
            <?php if ($menus && mysqli_num_rows($menus) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($menus)): ?>
                    <?php $gambar_src = !empty($row['gambar']) ? "../assets/" . htmlspecialchars($row['gambar']) : "https://via.placeholder.com/150?text=No+Image"; ?>
                    <div class="col-12 col-md-6 col-lg-4 menu-column"
                        data-category="<?= strtolower(trim($row['kategori_menu'])) ?>"
                        data-name="<?= htmlspecialchars(strtolower($row['nama_menu'])) ?>">
                        <div class="menu-item-card">
                            <div class="row g-0 h-100">
                                <div class="col-4">
                                    <img src="<?= $gambar_src ?>" class="menu-img"
                                        alt="<?= htmlspecialchars($row['nama_menu']) ?>">
                                </div>
                                <div class="col-8">
                                    <div class="menu-info">
                                        <div>
                                            <div class="fw-bold mb-0" style="font-size: 0.95rem; line-height: 1.2;">
                                                <?= htmlspecialchars($row['nama_menu']) ?>
                                            </div>
                                            <div class="text-muted mt-1" style="font-size: 0.70rem;">
                                                <?= htmlspecialchars($row['kategori_menu']) ?>
                                            </div>
                                            <div class="text-success fw-bold mt-1" style="font-size: 0.9rem;">Rp
                                                <?= number_format($row['harga'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            <a href="index.php?page=detail_menu&id=<?= $row['id_menu'] ?>"
                                                class="btn btn-outline-secondary btn-sm w-50">
                                                Detail
                                            </a>

                                            <a href="index.php?page=add_cart&id=<?= $row['id_menu'] ?>"
                                                class="btn-warning-custom w-50">
                                                Tambah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center mt-5 text-muted">
                    <p>Belum ada menu yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('searchInput');
            const categoryBtns = document.querySelectorAll('.nav-link-item');
            const menuColumns = document.querySelectorAll('.menu-column');

            function runFilter() {
                const searchText = searchInput.value.toLowerCase().trim();
                const activeCategory = document.querySelector('.nav-link-item.active').getAttribute('data-filter').toLowerCase();

                menuColumns.forEach(col => {
                    const name = col.getAttribute('data-name');
                    const cat = col.getAttribute('data-category');
                    if (name.includes(searchText) && (activeCategory === 'all' || cat === activeCategory)) {
                        col.style.display = "block";
                    } else {
                        col.style.display = "none";
                    }
                });
            }

            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active'); runFilter();
                });
            });
            searchInput.addEventListener('input', runFilter);
        });
    </script>
</body>

</html>