<?php
require_once 'auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
</head>
<body>
    <h1>Selamat Datang di Halaman Admin</h1>
    <p>Anda login sebagai administrator: <strong><?= e($_SESSION['username']) ?></strong></p>
    <p>
        <a href="dashboard.php">Kembali ke Dashboard</a> |
        <a href="logout.php">Logout</a>
    </p>
</body>
</html>