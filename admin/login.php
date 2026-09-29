<?php
// Jika sudah login, langsung lempar ke dashboard (mencegah login dua kali)
if (isset($_SESSION['id_admin'])) {
    header("Location: index.php?page=dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kopi Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f1ee; font-family: 'Poppins', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .login-box { width: 100%; max-width: 400px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .login-box h2 { font-weight: 800; text-align: center; margin-bottom: 30px; letter-spacing: 2px; color: #333; }
        .form-label { font-weight: 600; color: #555; margin-bottom: 5px; }
        .form-control { border-radius: 5px; padding: 10px; border: 1px solid #ccc; margin-bottom: 15px; }
        .form-control:focus { border-color: #6f4e37; box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25); }
        .show-pw-container { display: flex; align-items: center; gap: 8px; margin-bottom: 25px; font-size: 0.9rem; color: #666; }
        .show-pw-container input { cursor: pointer; }
        .button-group { display: flex; gap: 10px; }
        .btn-login { flex: 2; background-color: #6f4e37; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: 700; text-transform: uppercase; transition: 0.3s; }
        .btn-reset { flex: 1; background-color: #dc3545; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: 700; text-transform: uppercase; transition: 0.3s; }
        .btn-login:hover { background-color: #5d4037; }
        .btn-reset:hover { background-color: #a71d2a; }
        .alert-error { background-color: #f8d7da; color: #842029; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; border: 1px solid #f5c2c7; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>LOGIN</h2>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
        <div class="alert-error">
            <strong>Gagal!</strong> Username atau Password salah!
        </div>
    <?php endif; ?>

    <form action="index.php?page=login_aksi" method="POST">
        <div class="mb-2">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
        </div>

        <div class="mb-1">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Password" required>
        </div>

        <div class="show-pw-container">
            <input type="checkbox" id="showPassword" onclick="togglePassword()">
            <label for="showPassword" style="cursor:pointer;">Show Password</label>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-login">Login</button>
            <button type="reset" class="btn-reset">Reset</button>
        </div>
    </form>
</div>

<script>
    function togglePassword() {
        var x = document.getElementById("passwordInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

</body>
</html>