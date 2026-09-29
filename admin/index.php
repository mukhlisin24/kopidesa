<?php
require_once '../function.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// SISTEM KEAMANAN PINTU MASUK
// Jika belum login, HANYA boleh mengakses halaman 'login' dan 'login_aksi'
if (!isset($_SESSION['id_admin']) && $page != 'login' && $page != 'login_aksi') {
    header("Location: index.php?page=login");
    exit;
}

// ROUTING HALAMAN
switch ($page) {
    case 'dashboard':
        include 'dashboard.php';
        break;
    case 'admin_menu':
        include 'admin_menu.php';
        break;
    case 'admin_pesanan':
        include 'admin_pesanan.php';
        break;
    case 'admin_riwayat':
        include 'admin_riwayat.php';
        break;
    case 'login':
        include 'login.php';
        break;
    case 'detail_pesanan':
        include 'detail_pesanan.php';
        break;
    case 'update_status_menu':
        include 'update_status_menu.php';
        break;

    // --- AKSI PROSES ---
    case 'login_aksi':
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Cek kecocokan di database
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);
            // Simpan sesi admin jika berhasil
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['username'] = $admin['username'];
            header("Location: index.php?page=dashboard");
        } else {
            // Jika salah, kembalikan ke login dengan pesan error
            header("Location: index.php?page=login&pesan=gagal");
        }
        exit;
        break;

    case 'logout':
        unset($_SESSION['id_admin']);
        unset($_SESSION['username']);
        header("Location: index.php?page=login");
        exit;
        break;

    case 'tambah_menu_aksi':
        if (tambah_menu($_POST, $_FILES['gambar'])) {
            header("Location: index.php?page=admin_menu&msg=tambah_sukses");
        }
        break;

    case 'hapus_menu':
        if (hapus_menu($_GET['id'])) {
            header("Location: index.php?page=admin_menu&msg=hapus_sukses");
        }
        break;

    case 'update_pesanan':
        if (update_status_pesanan($_POST['id_pesanan'], $_POST['status'])) {
            if ($_POST['status'] == 'selesai') {
                header("Location: index.php?page=admin_riwayat");
            } else {
                header("Location: index.php?page=admin_pesanan");
            }
        }
        break;

    default:
        include 'dashboard.php';
        break;
}
?>