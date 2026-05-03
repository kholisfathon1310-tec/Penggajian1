<?php
session_start();
require_once "config.php";
require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";
require_once "template/footer.php";

// Periksa apakah karyawan sudah login
if (!isset($_SESSION['NIP'])) {
    header("Location: login.php");
    exit();
}

// Ambil data login berdasarkan jam_masuk
$query = "
    SELECT DATE(jam_masuk) AS tanggal, COUNT(*) AS jumlah_login
    FROM admin_absen
    WHERE NIP = '" . $koneksi->real_escape_string($_SESSION['NIP']) . "'
    GROUP BY DATE(jam_masuk)
    ORDER BY tanggal DESC
";
$result = $koneksi->query($query);

// Inisialisasi array untuk menyimpan tanggal dan jumlah login
$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['tanggal'];
    $data[] = $row['jumlah_login'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Absen Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Grafik Absen Karyawan</h4>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'bar', // Mengubah tipe grafik menjadi batang (bar)
            data: {
                labels: <?php echo json_encode($labels); ?>, // Tanggal login
                datasets: [{
                    label: 'Jumlah Login Karyawan',
                    data: <?php echo json_encode($data); ?>, // Jumlah login per tanggal
                    backgroundColor: 'rgba(111, 23, 60, 0.2)', // Warna batang navy (biru laut)
                    borderColor: 'rgb(41, 128, 0)', // Warna border navy
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Responsif terhadap ukuran layar
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
                        beginAtZero: true // Mulai dari 0 di sumbu Y
                    }
                }
            }
        });
    </script>
</body>
</html>
