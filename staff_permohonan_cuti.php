<?php
session_start();
require_once "config.php"; // Pastikan file ini benar

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $nip = mysqli_real_escape_string($koneksi, $_POST['NIP']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tanggal_mulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $jenis_cuti = mysqli_real_escape_string($koneksi, $_POST['jenis_cuti']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $tanggal_pengajuan = date("Y-m-d"); // Tanggal saat ini untuk pengajuan cuti

    // Generate id_cuti otomatis
    $result = mysqli_query($koneksi, "SELECT id_cuti FROM admin_pengajuan_cuti ORDER BY id_cuti DESC LIMIT 1");
    $lastId = mysqli_fetch_assoc($result)['id_cuti'];

    if ($lastId) {
        // Ambil angka terakhir dan tambahkan 1
        $number = (int) substr($lastId, 2) + 1;
        $id_cuti = 'CT' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        // Jika belum ada data, mulai dari CT001
        $id_cuti = 'CT001';
    }

    // Query untuk menyimpan data ke tabel admin_pengajuan_cuti
    $sql = "INSERT INTO admin_pengajuan_cuti (id_cuti, NIP, nama, tanggal_pengajuan, tanggal_awal, tanggal_akhir, jenis_cuti, konfirmasi_permohonan) 
            VALUES ('$id_cuti', '$nip', '$nama', '$tanggal_pengajuan', '$tanggal_mulai', '$tanggal_selesai', '$jenis_cuti', '$konfirmasi_permohonan')";

    if (mysqli_query($koneksi, $sql)) {
        // Redirect kembali ke halaman utama dengan pesan sukses
        $_SESSION['message'] = "Data permohonan cuti berhasil ditambahkan.";
        header("Location: staff_permohonan_cuti.php");
        exit();
    } else {
        // Jika ada error saat menyimpan
        $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($koneksi);
        header("Location: admin_permohonan_cuti.php");
        exit();
    }
} else {
    header("Location: staff_form_cuti.php");
    exit();
}
?>
