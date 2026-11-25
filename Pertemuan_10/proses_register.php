<?php
require_once 'auth.php';
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

verify_csrf_token($_POST['csrf_token'] ?? '');

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi server-side
if ($username === '') {
    $_SESSION['error'] = "Username wajib diisi.";
    header("Location: register.php");
    exit;
}

if (strlen($username) > 50) {
    $_SESSION['error'] = "Username maksimal 50 karakter.";
    header("Location: register.php");
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['error'] = "Password minimal 8 karakter.";
    header("Location: register.php");
    exit;
}

// Cek apakah username sudah terdaftar
$cekStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$cekStmt->bind_param("s", $username);
$cekStmt->execute();
$cekStmt->store_result();

if ($cekStmt->num_rows > 0) {
    $cekStmt->close();
    $conn->close();
    $_SESSION['error'] = "Registrasi ditolak: Username sudah digunakan.";
    header("Location: register.php");
    exit;
}
$cekStmt->close();

// Hashing password dan simpan ke database
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
$stmt->bind_param("ss", $username, $hash);

try {
    $stmt->execute();
    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit;
} catch (mysqli_sql_exception $e) {
    $stmt->close();
    $conn->close();
    $_SESSION['error'] = "Registrasi gagal atau username sudah digunakan.";
    header("Location: register.php");
    exit;
}