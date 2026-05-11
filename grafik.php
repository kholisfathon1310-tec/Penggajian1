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
// AMBIL DATA GRAFIK
// ======================
try {

    $stmt = $koneksi->prepare("
        SELECT 
            DATE_FORMAT(tanggal_gaji, '%Y-%m') AS bulan,
            SUM(salary) AS total_penggajian
        FROM admin_penggajian
        GROUP BY bulan
        ORDER BY bulan ASC
    ");

    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ======================
    // ARRAY DATA
    // ======================
    $bulan = [];

    $total_penggajian = [];

    foreach ($data as $row) {

        $bulan[] =
            $row['bulan'];

        $total_penggajian[] =
            $row['total_penggajian'];
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
    Grafik Penggajian
</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

.container {
    margin-top: 80px;
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

    <div class="card">

        <div class="card-header">

            Grafik Total Penggajian Per Bulan

        </div>

        <div class="card-body">

            <canvas id="gajiChart"
                    width="400"
                    height="200">
            </canvas>

        </div>

    </div>

</div>

<script>

const ctx =
    document
    .getElementById('gajiChart')
    .getContext('2d');

const gajiChart = new Chart(ctx, {

    type: 'line',

    data: {

        labels:
            <?= json_encode($bulan); ?>,

        datasets: [{

            label:
                'Total Penggajian',

            data:
                <?= json_encode($total_penggajian); ?>,

            borderColor:
                'rgba(75, 192, 192, 1)',

            backgroundColor:
                'rgba(75, 192, 192, 0.2)',

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
