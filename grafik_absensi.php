<?php
error_reporting(E_ALL);

ini_set('display_errors', 1);

require_once "config.php";

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// =======================
// DATA ABSENSI
// =======================
$stmt = $koneksi->prepare("
    SELECT

        nama_user,

        COUNT(*) AS hadir

    FROM admin_absen

    GROUP BY nama_user
");

$stmt->execute();

$karyawan = [];

$hadir = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $karyawan[] =
        $row['nama_user'];

    $hadir[] =
        $row['hadir'];
}

// =======================
// DATA GAJI
// =======================
$stmt2 = $koneksi->prepare("
    SELECT

        DATE_FORMAT(
            tanggal_gaji,
            '%Y-%m'
        ) AS bulan,

        SUM(salary) AS total_gaji

    FROM admin_penggajian

    GROUP BY bulan

    ORDER BY bulan ASC
");

$stmt2->execute();

$bulan = [];

$total_gaji = [];

while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

    $bulan[] =
        $row['bulan'];

    $total_gaji[] =
        $row['total_gaji'];
}
?>

<div class="container-fluid mt-4">

<br>
<br>
<br>
<br>

<center>

    <h4 class="mb-4">
        📊 Grafik Absensi
    </h4>

</center>

<div class="card shadow mb-4">

    <div class="card-body">

        <canvas id="absensiChart"
                height="100">
        </canvas>

    </div>

</div>

<center>

    <h4 class="mb-4">
        💰 Grafik Penggajian
    </h4>

</center>

<div class="card shadow">

    <div class="card-body">

        <canvas id="gajiChart"
                height="100">
        </canvas>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// =======================
// GRAFIK ABSENSI
// =======================
new Chart(

    document.getElementById('absensiChart'),

    {

        type: 'bar',

        data: {

            labels:
                <?= json_encode($karyawan); ?>,

            datasets: [{

                label:
                    'Jumlah Hadir',

                data:
                    <?= json_encode($hadir); ?>,

                backgroundColor: [

                    'rgba(0, 0, 128, 0.9)',

                    'rgba(169, 169, 169, 0.9)',

                    'rgba(255, 165, 0, 0.9)',

                    'rgba(0, 0, 128, 0.9)',

                    'rgba(169, 169, 169, 0.9)',

                    'rgba(255, 165, 0, 0.9)'
                ],

                borderRadius: 10
            }]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    position: 'top'
                },

                tooltip: {

                    backgroundColor:
                        'rgba(0,0,0,0.7)',

                    titleColor:
                        '#fff',

                    bodyColor:
                        '#fff'
                }
            },

            scales: {

                y: {

                    beginAtZero: true
                }
            }
        }
    }
);

// =======================
// GRAFIK GAJI
// =======================
new Chart(

    document.getElementById('gajiChart'),

    {

        type: 'line',

        data: {

            labels:
                <?= json_encode($bulan); ?>,

            datasets: [{

                label:
                    'Total Gaji',

                data:
                    <?= json_encode($total_gaji); ?>,

                borderColor:
                    'rgba(255,165,0,1)',

                backgroundColor:
                    'rgba(255,165,0,0.2)',

                tension: 0.4,

                fill: true
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

                    beginAtZero: true
                }
            }
        }
    }
);

</script>

<?php
require_once "template_admin/footer.php";
?>