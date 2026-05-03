<?php
include 'config.php';

// Periksa apakah form sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil ID maksimum dari database
    $sql_max_id = "SELECT MAX(id_user) AS max_id FROM user";
    $result_max_id = mysqli_query($koneksi, $sql_max_id);
    $row_max_id = mysqli_fetch_assoc($result_max_id);
    $max_id = isset($row_max_id['max_id']) ? (int)substr($row_max_id['max_id'], 1) : 0; // Default 0 jika tidak ada data

    // Buat ID baru
    $id_user = 'U' . str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);
    $NIP = $_POST['NIP'];
    $nama_user = $_POST['nama_user'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $hak = $_POST['hak'];

    // Query SQL untuk insert data ke tabel user
    $sql = "INSERT INTO user (id_user, NIP, nama_user, tgl_lahir, alamat, no_telp, hak) 
            VALUES ('$id_user', '$NIP', '$nama_user', '$tgl_lahir', '$alamat', '$no_telp', '$hak')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='staff.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
