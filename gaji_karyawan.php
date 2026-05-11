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

// ======================
// ARRAY GRAFIK
// ======================
$labels = [];

$salaryData = [];

// ======================
// AMBIL DATA GAJI
// ======================
try {

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
        ORDER BY tanggal_gaji ASC
    ");

    $stmt->execute([
        ':nip' => $nipLogin
    ]);

    $data =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ======================
    // DATA GRAFIK
    // ======================
    foreach ($data as $row) {

        $labels[] =
            $row['periode'];

        $salaryData[] =
            $row['salary'];
    }

} catch (PDOException $e) {

    die(
        "Terjadi kesalahan database: "
        . $e->getMessage()
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Daftar Gaji Karyawan
</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #343a40;
    color: white;
    font-weight: bold;
    text-align: center;
}

</style>

</head>

<body>

<div class="container">

    <!-- ======================
         GRAFIK
    ======================= -->
    <div class="card mb-5">

        <div class="card-header">

            Grafik Gaji Karyawan

        </div>

        <div class="card-body">

            <canvas id="salaryChart"
                    height="100">
            </canvas>

        </div>

    </div>

    <!-- ======================
         TABEL
    ======================= -->
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

            if ($data) {

                foreach ($data as $row) {

                    echo "
                    <tr>

                        <td>
                            " . htmlspecialchars($row['NIP']) . "
                        </td>

                        <td>
                            " . htmlspecialchars($row['nama_user']) . "
                        </td>

                        <td>
                            " . htmlspecialchars($row['hak']) . "
                        </td>

                        <td>
                            " . htmlspecialchars($row['periode']) . "
                        </td>

                        <td>
                            Rp " . number_format($row['base_salary'], 0, ',', '.') . "
                        </td>

                        <td>
                            " . htmlspecialchars($row['tanggal_gaji']) . "
                        </td>

                        <td>
                            Rp " . number_format($row['pot_BPJS'], 0, ',', '.') . "
                        </td>

                        <td>
                            " . htmlspecialchars($row['lembur']) . "
                        </td>

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

            ?>

            </tbody>

        </table>

    </div>

</div>

<script>

// ======================
// GRAFIK GAJI
// ======================
const ctx =
    document
    .getElementById('salaryChart')
    .getContext('2d');

new Chart(ctx, {

    type: 'line',

    data: {

        labels:
            <?= json_encode($labels); ?>,

        datasets: [{

            label:
                'Total Gaji',

            data:
                <?= json_encode($salaryData); ?>,

            borderColor:
                'rgba(54, 162, 235, 1)',

            backgroundColor:
                'rgba(54, 162, 235, 0.2)',

            borderWidth: 2,

            fill: true,

            tension: 0.3
        }]
    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: true
            }
        },

        scales: {

            y: {

                beginAtZero: true
            }
        }
    }
});

</script>

</body>
</html>
