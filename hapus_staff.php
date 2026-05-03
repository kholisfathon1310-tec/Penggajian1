<?php
session_start();
require_once 'config.php'; // Pastikan ini sudah berisi koneksi database

// Cek apakah ada NIP yang dikirim melalui POST
if (isset($_POST['NIP'])) {
    $NIP = $_POST['NIP'];
    
    // Query untuk menghapus staff berdasarkan NIP
    $sql = "DELETE FROM user WHERE NIP = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $NIP);
    
    if ($stmt->execute()) {
        // Simpan pesan sukses di sesi
        $_SESSION['message'] = "Data staff berhasil dihapus.";
        $_SESSION['message_type'] = "success"; // Tipe pesan sukses
    } else {
        // Simpan pesan gagal di sesi
        $_SESSION['message'] = "Gagal menghapus data staff.";
        $_SESSION['message_type'] = "danger"; // Tipe pesan error
    }

    // Redirect ke halaman staff setelah operasi selesai
    header("Location: staff.php");
    exit; // Pastikan script berhenti setelah redirect
} else {
    // Jika NIP tidak ditemukan
    $_SESSION['message'] = "Data staff tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
    header("Location: staff.php");
    exit;
}
