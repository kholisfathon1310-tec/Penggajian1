<?php
session_start();

require_once "config.php";

require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";

// ======================
// CEK LOGIN
// ======================
if (!isset($_SESSION['NIP'])) {

    header("Location: form_login.php");

    exit();
}

// ======================
// AMBIL NIP LOGIN
// ======================
$nipLogin = $_SESSION['NIP'];

// ======================
// ARRAY DATA
// ======================
$labels = [];

$data = [];

try {

    // ======================
    // QUERY ABSENSI
    // ======================
    $stmt = $koneksi->prepare("
        SELECT
            DATE(jam_masuk) AS tanggal,
            COUNT(*) AS jumlah_login
        FROM admin_absen
        WHERE NIP = :nip
        GROUP BY DATE(jam_masuk)
        ORDER BY tanggal DESC
    ");

    $stmt->execute([
        ':nip' => $nipLogin
    ]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $labels[] =
            $row['tanggal'];

        $data[] =
            $row['jumlah_login'];
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
    Grafik Absen Karyawan
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet">

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
    text-align: center;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="card-header">

            <h4 class="card-title">

                Grafik Absen Karyawan

            </h4>

        </div>

        <div class="card-body">

            <canvas id="attendanceChart"
                    width="400"
                    height="200">
            </canvas>

        </div>

    </div>

</div>

<script>

const ctx =
    document
    .getElementById('attendanceChart')
    .getContext('2d');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels:
            <?= json_encode($labels); ?>,

        datasets: [{

            label:
                'Jumlah Login Karyawan',

            data:
                <?= json_encode($data); ?>,

            backgroundColor:
                'rgba(111, 23, 60, 0.2)',

            borderColor:
                'rgb(41, 128, 0)',

            borderWidth: 1
        }]
    },

    options: {

        responsive: true,

        scales: {

            x: {

                title: {

                    display: true,

                    text: 'Tanggal'
                }
            },

            y: {

                title: {

                    display: true,

                    text: 'Jumlah Login'
                },

                beginAtZero: true
            }
        }
    }
});

</script>

<?php require_once "template/footer.php"; ?>

</body>
</html>