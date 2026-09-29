<?php
// 1. Pastikan session berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================================
// 2. KONEKSI DATABASE (LANGSUNG DI SINI AGAR TIDAK ERROR NULL)
// ==========================================================
$host = "localhost";
$user = "root";       // Username bawaan Laragon/XAMPP
$pass = "";           // Password bawaan Laragon/XAMPP (kosong)
$db = "kopdes_dinein";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


// ==========================================================
// 3. KUMPULAN FUNGSI UNTUK CUSTOMER
// ==========================================================

function get_semua_menu()
{
    global $conn;
    $query = "SELECT * FROM menu WHERE status = 'tersedia' ORDER BY kategori_menu ASC, nama_menu ASC";
    return mysqli_query($conn, $query);
}

function get_menu_by_id($id)
{
    global $conn;
    $id_safe = intval($id); // Mencegah error SQL Injection
    $query = "SELECT * FROM menu WHERE id_menu = $id_safe";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}


// ==========================================================
// 4. KUMPULAN FUNGSI UNTUK ADMIN
// ==========================================================

function tambah_menu($data, $file)
{
    global $conn;

    // Amankan input text
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $kategori = mysqli_real_escape_string($conn, $data['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    $harga = intval($data['harga']);
    $status = mysqli_real_escape_string($conn, $data['status']);

    // Proses Upload Gambar
    $gambar = "";
    if (isset($file['error']) && $file['error'] == 0) {
        // Buat nama file unik agar tidak tertimpa
        $nama_file = time() . "_" . basename($file['name']);

        // Gunakan __DIR__ agar path upload akurat meskipun dipanggil dari folder admin
        $target_direktori = __DIR__ . "/assets/" . $nama_file;

        if (move_uploaded_file($file['tmp_name'], $target_direktori)) {
            $gambar = $nama_file;
        }
    }

    $query = "INSERT INTO menu (nama_menu, kategori_menu, harga, gambar, status, deskripsi) 
              VALUES ('$nama', '$kategori', $harga, '$gambar', '$status', '$deskripsi')";

    return mysqli_query($conn, $query);
}

function hapus_menu($id)
{
    global $conn;
    $id_safe = intval($id);

    // Cek apakah menu memiliki gambar fisik sebelum dihapus dari database
    $data_menu = get_menu_by_id($id_safe);
    if ($data_menu && !empty($data_menu['gambar'])) {
        $path_gambar = __DIR__ . "/assets/img/" . $data_menu['gambar'];
        // Jika file fisiknya ada, hapus dari folder
        if (file_exists($path_gambar)) {
            unlink($path_gambar);
        }
    }

    // Hapus data dari tabel
    $query = "DELETE FROM menu WHERE id_menu = $id_safe";
    return mysqli_query($conn, $query);
}

function update_status_pesanan($id_pesanan, $status_baru)
{
    global $conn;
    $id_safe = intval($id_pesanan);
    $status_safe = mysqli_real_escape_string($conn, $status_baru);

    // UBAH: status_pesanan menjadi status
    $query = "UPDATE pesanan SET status = '$status_safe' WHERE id_pesanan = $id_safe";
    return mysqli_query($conn, $query);
}
function update_status_menu($id_menu, $status_baru)
{
    global $conn;

    $id_safe = intval($id_menu);
    $status_safe = mysqli_real_escape_string($conn, $status_baru);

    $query = "UPDATE menu 
              SET status = '$status_safe' 
              WHERE id_menu = $id_safe";

    return mysqli_query($conn, $query);
}