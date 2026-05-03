<?php
session_start();

// Termasuk file koneksi database dan file auth.php
include 'config.php';
include 'auth.php';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NIP = $_POST['NIP'];
    $password = $_POST['password'];

    // Buat objek Auth
    $auth = new Auth($koneksi, $NIP, $password);
    $user = $auth->login();

    if ($user) {
        // Enkapsulasi data ke session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['NIP'] = $user['NIP'];
        $_SESSION['nama_user'] = $user['nama_user'];
        $_SESSION['hak'] = $user['hak'];

        // Redirect berdasarkan hak akses
        if ($user['hak'] === 'karyawan') {
            header('Location: grafik_karyawan.php');
            exit;
        } elseif ($user['hak'] === 'admin') {
            header('Location: grafik_absensi.php');
            exit;
        }
    } else {
        echo "<script>alert('NIP atau password salah!'); window.location.assign('form_login.php');</script>";
    }
} else {
    echo "Akses tidak diizinkan.";
}
?>
