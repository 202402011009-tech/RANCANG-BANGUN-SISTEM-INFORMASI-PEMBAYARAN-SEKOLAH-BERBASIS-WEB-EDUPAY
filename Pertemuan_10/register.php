<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Permintaan tidak valid (CSRF token tidak cocok).";
    } else {
        $username        = trim($_POST['username'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($username === '') {
            $errors[] = "Username wajib diisi.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
            $errors[] = "Username harus 3-50 karakter (huruf, angka, atau underscore).";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password minimal 8 karakter.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Konfirmasi password tidak cocok.";
        }

        if (empty($errors)) {
            $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $cek->bind_param("s", $username);
            $cek->execute();
            $cek->store_result();

            if ($cek->num_rows > 0) {
                $errors[] = "Registrasi ditolak: Username sudah digunakan.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                $stmt->bind_param("ss", $username, $hash);
                $stmt->execute();
                $stmt->close();

                $_SESSION['flash_success'] = "Registrasi berhasil! Silakan login dengan akun Anda.";
                $cek->close();
                header("Location: login.php");
                exit;
            }
            $cek->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <!-- PEMANGGIL CSS AGAR TAMPILAN TIDAK POLOS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-sm">
        <div class="card">
            <h2>Registrasi Pengguna</h2>
            <p class="muted">Buat akun baru untuk mengakses sistem.</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $err): ?>
                        <div><?= e($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($username) ?>" required minlength="3" maxlength="50" placeholder="Contoh: syafiq_01">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn btn-primary btn-full">Daftar</button>
            </form>

            <div class="link-footer">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>