<?php
session_start();

// ======================
// CONFIG & TEMPLATE
// ======================
require_once "config.php";

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";
require_once "template_admin/footer.php";

// ======================
// DATA ABSENSI
// ======================
try {

    $stmtAbsen = $koneksi->prepare("
        SELECT 
            nama_user,
            COUNT(*) AS hadir
        FROM admin_absen
        GROUP BY nama_user
    ");

    $stmtAbsen->execute();

    $karyawan = [];
    $hadir = [];

    while ($row = $stmtAbsen->fetch(PDO::FETCH_ASSOC)) {

        $karyawan[] = $row['nama_user'];
        $hadir[] = $row['hadir'];
    }

} catch (PDOException $e) {

    die("Error Absensi: " . $e->getMessage());
}

// ======================
// DATA PENGGAJIAN
// ======================
try {

    $stmtGaji = $koneksi->prepare("
        SELECT 
            DATE_FORMAT(tanggal_gaji, '%Y-%m') AS bulan,
            SUM(total) AS total_penggajian
        FROM admin_penggajian
        GROUP BY bulan
        ORDER BY bulan ASC
    ");

    $stmtGaji->execute();

    $bulan = [];
    $total_penggajian = [];

    while ($row = $stmtGaji->fetch(PDO::FETCH_ASSOC)) {

        $bulan[] = $row['bulan'];
        $total_penggajian[] = $row['total_penggajian'];
    }

} catch (PDOException $e) {

    die("Error Penggajian: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Grafik</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fc;
}

.container-custom {
    width: 90%;
    margin: auto;
    margin-top: 100px;
}

.chart-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-title {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
    color: #333;
}

</style>

</head>

<body>

<div class="container-custom">

    <!-- ======================
         GRAFIK ABSENSI
    ======================= -->
    <div class="chart-box">

        <h2 class="chart-title">
            Grafik Absensi Karyawan
        </h2>

        <canvas id="absensiChart"></canvas>

    </div>

    <!-- ======================
         GRAFIK PENGGAJIAN
    ======================= -->
    <div class="chart-box">

        <h2 class="chart-title">
            Grafik Total Penggajian Per Bulan
        </h2>

        <canvas id="gajiChart"></canvas>

    </div>

</div>

<script>

// ======================
// GRAFIK ABSENSI
// ======================
const absensiChart = new Chart(
    document.getElementById('absensiChart'),
    {
        type: 'bar',

        data: {

            labels: <?= json_encode($karyawan); ?>,

            datasets: [{

                label: 'Absensi Karyawan',

                data: <?= json_encode($hadir); ?>,

                backgroundColor: [
                    'rgba(0, 0, 128, 0.8)',
                    'rgba(169, 169, 169, 0.8)',
                    'rgba(255, 165, 0, 0.8)',
                    'rgba(0, 0, 128, 0.8)',
                    'rgba(169, 169, 169, 0.8)',
                    'rgba(255, 165, 0, 0.8)'
                ],

                borderWidth: 1
            }]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: 'top'
                }
            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    }
);

// ======================
// GRAFIK PENGGAJIAN
// ======================
const gajiChart = new Chart(
    document.getElementById('gajiChart'),
    {
        type: 'bar',

        data: {

            labels: <?= json_encode($bulan); ?>,

            datasets: [{

                label: 'Total Penggajian',

                data: <?= json_encode($total_penggajian); ?>,

                backgroundColor: 'rgba(0, 0, 128, 0.6)',

                borderColor: 'rgba(0, 0, 128, 1)',

                borderWidth: 1
            }]
        },

        options: {

            responsive: true,

            scales: {

                y: {

                    beginAtZero: true
                }
            }
        }
    }
);

</script>

</body>
</html>
