<?php
session_start();

require_once 'config.php';

require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";

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
            periode,
            salary
        FROM admin_penggajian
        WHERE NIP = :nip
        ORDER BY tanggal_gaji ASC
    ");

    $stmt->execute([
        ':nip' => $nipLogin
    ]);

    $gajiData =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($gajiData as $row) {

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
    Dashboard Karyawan
</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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

    <!-- ======================
         GRAFIK GAJI
    ======================= -->
    <div class="card">

        <div class="card-header">

            Grafik Gaji Karyawan

        </div>

        <div class="card-body">

            <canvas id="salaryChart"
                    height="100">
            </canvas>

        </div>

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

        scales: {

            y: {

                beginAtZero: true
            }
        }
    }
});

</script>

<?php require_once "template/footer.php"; ?>

</body>
</html>
