<?php
session_start();

require_once 'config.php';
require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/footer.php";

// ======================
// CEK LOGIN
// ======================
if (!isset($_SESSION['NIP'])) {

    header("Location: form_login.php");
    exit;
}

// ======================
// AMBIL NIP LOGIN
// ======================
$nipLogin = $_SESSION['NIP'];

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daftar Gaji Karyawan</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>

.container {
    margin-top: 80px;
}

.table th {
    background-color: #343a40;
    color: white;
    text-align: center;
}

.table td {
    vertical-align: middle;
}

</style>

</head>

<body>

<div class="container">

    <h2 class="text-center mb-4">
        Daftar Gaji Anda
    </h2>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Periode</th>
                    <th>Gaji Pokok</th>
                    <th>Tanggal Gaji</th>
                    <th>Potongan BPJS</th>
                    <th>Lembur</th>
                    <th>Total Gaji</th>

                </tr>

            </thead>

            <tbody>

            <?php

            try {

                // ======================
                // QUERY DATA GAJI
                // ======================
                $stmt = $koneksi->prepare("
                    SELECT 
                        NIP,
                        nama_user,
                        hak,
                        periode,
                        base_salary,
                        tanggal_gaji,
                        pot_BPJS,
                        lembur,
                        salary
                    FROM admin_penggajian
                    WHERE NIP = :nip
                    ORDER BY tanggal_gaji DESC
                ");

                $stmt->execute([
                    ':nip' => $nipLogin
                ]);

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ======================
                // TAMPILKAN DATA
                // ======================
                if ($data) {

                    foreach ($data as $row) {

                        echo "
                        <tr>

                            <td>" . htmlspecialchars($row['NIP']) . "</td>

                            <td>" . htmlspecialchars($row['nama_user']) . "</td>

                            <td>" . htmlspecialchars($row['hak']) . "</td>

                            <td>" . htmlspecialchars($row['periode']) . "</td>

                            <td>
                                Rp " . number_format($row['base_salary'], 0, ',', '.') . "
                            </td>

                            <td>" . htmlspecialchars($row['tanggal_gaji']) . "</td>

                            <td>
                                Rp " . number_format($row['pot_BPJS'], 0, ',', '.') . "
                            </td>

                            <td>" . htmlspecialchars($row['lembur']) . "</td>

                            <td>
                                Rp " . number_format($row['salary'], 0, ',', '.') . "
                            </td>

                        </tr>
                        ";
                    }

                } else {

                    echo "
                    <tr>

                        <td colspan='9'
                            class='text-center text-muted'>

                            Tidak ada data gaji untuk akun Anda.

                        </td>

                    </tr>
                    ";
                }

            } catch (PDOException $e) {

                echo "
                <tr>

                    <td colspan='9'
                        class='text-center text-danger'>

                        Terjadi kesalahan:
                        " . htmlspecialchars($e->getMessage()) . "

                    </td>

                </tr>
                ";
            }

            ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
