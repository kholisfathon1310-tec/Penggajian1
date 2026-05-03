<?php
session_start();
require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/footer.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['NIP'])) {
    die("Anda harus login terlebih dahulu.");
}

// Ambil NIP dari session
$nipLogin = $_SESSION['NIP'];

// Koneksi ke database
try {
    $koneksi = new PDO("mysql:host=localhost;dbname=penggajian", "root", "");
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gaji Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Daftar Gaji Anda</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Posisi</th>
                <th>Periode</th>
                <th>Gaji pokok</th>
                <th>Tanggal Gaji</th>
                <th>Potongan BPJS</th>
                <th>Lembur</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
        <?php
        try {
            // Mengambil data gaji berdasarkan NIP
            $query = "SELECT NIP, nama_user, hak, periode, base_salary, tanggal_gaji, pot_BPJS, lembur, salary 
                      FROM admin_penggajian 
                      WHERE NIP = :nip";
            $stmt = $koneksi->prepare($query);
            $stmt->bindParam(':nip', $nipLogin, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tampilkan data
            if ($data) {
                foreach ($data as $i => $row) {
                    echo "<tr>
                             <td>" . htmlspecialchars($row['NIP']) . "</td>
                            <td>" . htmlspecialchars($row['nama_user']) . "</td>
                            <td>" . htmlspecialchars($row['hak']) . "</td>
                            <td>" . htmlspecialchars($row['periode']) . "</td>
                            <td>" . htmlspecialchars($row['base_salary']) . "</td>
                            <td>" . htmlspecialchars($row['tanggal_gaji']) . "</td>
                            <td>" . htmlspecialchars($row['pot_BPJS']) . "</td>
                            <td>" . htmlspecialchars($row['lembur']) . "</td>
                            <td>Rp " . number_format($row['salary'], 0, ',', '.') . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data gaji untuk NIP Anda.</td></tr>";
            }
        } catch (Exception $e) {
            echo "<tr><td colspan='8' class='text-danger text-center'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
