<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------------
// SISTEM DETEKSI QR CODE MEJA
// Jika ada parameter ?meja=X di URL, simpan ke sesi pelanggan
if (isset($_GET['meja']) && !empty($_GET['meja'])) {
    $_SESSION['no_meja'] = intval($_GET['meja']);
}
// ------------------------------------------------------------------

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        include 'home.php';
        break;
    case 'menu':
        include 'menu.php';
        break;
    case 'keranjang':
        include 'keranjang.php';
        break;
    case 'checkout':
        include 'checkout.php';
        break;
    case 'detail_menu':
        include 'detail_menu.php';
        break;

    // FITUR KERANJANG
    case 'add_cart':
        $id = intval($_GET['id']);
        if ($id) {
            if (!isset($_SESSION['keranjang'][$id])) {
                $_SESSION['keranjang'][$id] = 1;
            } else {
                $_SESSION['keranjang'][$id] += 1;
            }
        }
        header("Location: index.php?page=menu");
        break;
    case 'increase_qty':
        $id = intval($_GET['id']);
        if ($id && isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id] += 1;
        }
        header("Location: index.php?page=keranjang");
        break;
    case 'decrease_qty':
        $id = intval($_GET['id']);
        if ($id && isset($_SESSION['keranjang'][$id])) {
            if ($_SESSION['keranjang'][$id] > 1) {
                $_SESSION['keranjang'][$id] -= 1;
            } else {
                unset($_SESSION['keranjang'][$id]);
            }
        }
        header("Location: index.php?page=keranjang");
        break;
    case 'remove_from_cart':
        $id = intval($_GET['id']);
        if ($id && isset($_SESSION['keranjang'][$id])) {
            unset($_SESSION['keranjang'][$id]);
        }
        header("Location: index.php?page=keranjang");
        break;

    // FITUR CHECKOUT (Eksekusi ke Database)
    case 'proses_checkout':
        include 'proses_checkout.php';
        break;

    default:
        echo "<h2 style='text-align:center; margin-top:50px;'>404 - Halaman tidak ditemukan</h2>";
        break;
}
?>