<?php
require_once 'config.php';
require_login();

$username  = $_SESSION['username'];
$role      = $_SESSION['role'];
$userId    = $_SESSION['user_id'];
$loginTime = $_SESSION['login_time'] ?? date('d M Y, H:i:s');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <!-- PEMANGGIL CSS AGAR TAMPILAN KEREN & RAPI -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">Sistem Login &amp; RBAC</a>
        <div class="nav-links">
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
            <?php if ($role === 'admin'): ?>
                <a href="admin_panel.php" class="btn btn-primary">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>Selamat Datang, <?= e($username) ?>!</h1>
            <p class="muted">Anda berhasil terautentikasi dan sesi login Anda sedang aktif di server.</p>

            <div class="grid">
                <div class="stat-box">
                    <div class="stat-label">User ID</div>
                    <div class="stat-value">#<?= e((string) $userId) ?></div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Username</div>
                    <div class="stat-value"><?= e($username) ?></div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Role / Hak Akses</div>
                    <div class="stat-value">
                        <span class="badge badge-<?= e($role) ?>"><?= e($role) ?></span>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Waktu Login</div>
                    <div class="stat-value" style="font-size: 1rem;"><?= e($loginTime) ?></div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Matriks Hak Akses (RBAC)</h2>
            <p class="muted">Pemeriksaan otorisasi dilakukan pada setiap halaman di sisi server.</p>
            <table>
                <thead>
                    <tr>
                        <th>Fitur / Halaman</th>
                        <th>Role: User</th>
                        <th>Role: Admin</th>
                        <th>Akses Anda</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dashboard Pengguna (`dashboard.php`)</td>
                        <td>Diizinkan</td>
                        <td>Diizinkan</td>
                        <td><span class="badge badge-admin">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>Admin Panel &amp; Manajemen Role (`admin_panel.php`)</td>
                        <td>Ditolak (403)</td>
                        <td>Diizinkan</td>
                        <td>
                            <?php if ($role === 'admin'): ?>
                                <span class="badge badge-admin">Diizinkan</span>
                            <?php else: ?>
                                <span class="badge badge-user">Dibatasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Logout &amp; Hapus Session (`logout.php`)</td>
                        <td>Diizinkan</td>
                        <td>Diizinkan</td>
                        <td><span class="badge badge-admin">Aktif</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="actions" style="margin-top: 20px;">
                <?php if ($role === 'admin'): ?>
                    <a href="admin_panel.php" class="btn btn-primary">Buka Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>