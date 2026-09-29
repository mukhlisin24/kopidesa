<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Desa - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; font-family: 'Poppins', sans-serif; overflow: hidden; background-color: #222; }
        .bg-hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('../assets/img/bg-cafe.jpg');
            background-size: cover; background-position: center; height: 100vh; display: flex; align-items: center; justify-content: center; color: white; text-align: center;
        }
        .hero-title { letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .meja-info { font-size: 1.1rem; font-weight: 600; color: #ffcc00; margin-bottom: 30px; }
        .meja-alert { color: #ff4d4d; font-weight: 600; background: rgba(255, 77, 77, 0.1); padding: 10px; border-radius: 8px; border: 1px solid rgba(255, 77, 77, 0.3); margin-bottom: 25px; display: inline-block;}
        .btn-menu { background-color: #6f4e37; color: white; padding: 14px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.2rem; display: inline-block; transition: all 0.3s ease; }
        .btn-menu:hover { background-color: transparent; border: 2px solid #ffcc00; color: #ffcc00; transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="bg-hero">
        <div class="container px-4">
            <h1 class="display-4 fw-bold mb-2 hero-title">KOPI DESA</h1>
            <p class="lead mb-3">Pesan langsung dari mejamu, tanpa antre!</p>
            
            <?php if (isset($_SESSION['no_meja']) && $_SESSION['no_meja'] > 0): ?>
                <div class="meja-info">
                    📍 Terkoneksi dengan <strong>Meja <?= htmlspecialchars($_SESSION['no_meja']) ?></strong>
                </div>
                <a href="index.php?page=menu" class="btn-menu shadow">Buka Buku Menu</a>
            <?php else: ?>
                <div class="meja-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Anda belum men-scan QR Code Meja. <br> Silakan scan QR Code yang ada di atas meja Anda.
                </div>
                <br>
                <a href="index.php?page=menu" class="btn btn-outline-light rounded-pill px-4">Lihat Menu Saja</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>