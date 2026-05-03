<?php
// Koneksi ke database

require_once "config.php";
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";
require_once "template_admin/footer.php";


class Database {
    private $host = "localhost";
    private $db_name = "penggajian";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Ambil data absensi dari database
$database = new Database();
$conn = $database->getConnection();

// Query untuk mengambil data absensi
$query = "SELECT nama_user, COUNT(*) AS hadir FROM admin_absen GROUP BY nama_user";
$stmt = $conn->prepare($query);
$stmt->execute();

// Ambil data dan simpan dalam array
$karyawan = [];
$hadir = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $karyawan[] = $row['nama_user'];
    $hadir[] = $row['hadir'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Absensi Karyawan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 100px; /* Memberikan jarak atas untuk menghindari navbar */
        }
        .chart-container {
            width: 80%;
            margin: 50px auto; /* Memberikan margin atas dan bawah */
        }
    </style>
</head>
<body>
    <h1>Grafik Absensi Karyawan</h1>
    <div class="chart-container">
        <canvas id="absensiChart"></canvas>
    </div>

    <script>
        // Data absensi yang disimulasikan
        var absensiData = {
            labels: <?php echo json_encode($karyawan); ?>,  // Nama karyawan dari PHP
            datasets: [{
                label: 'Absensi Karyawan',
                data: <?php echo json_encode($hadir); ?>,  // Data absensi dari PHP
                backgroundColor: [
                    'rgba(0, 0, 128, 1)',  // Warna navy
                    'rgba(169, 169, 169, 1)',  // Warna abu-abu (gray)
                    'rgba(255, 165, 0, 1)',  // Warna jingga/orange
                    'rgba(0, 0, 128, 1)',  // Warna navy
                    'rgba(169, 169, 169, 1)',  // Warna abu-abu (gray)
                    'rgba(255, 165, 0, 1)'   // Warna jingga/orange
                ],
                borderColor: [
                    'rgba(0, 0, 128, 1)',   // Warna navy
                    'rgba(169, 169, 169, 1)',   // Warna abu-abu (gray)
                    'rgba(255, 165, 0, 1)',   // Warna jingga/orange
                    'rgba(0, 0, 128, 1)',   // Warna navy
                    'rgba(169, 169, 169, 1)',  // Warna abu-abu (gray)
                    'rgba(255, 165, 0, 1)'    // Warna jingga/orange
                ],
                borderWidth: 1
            }]
        };

        // Konfigurasi chart
        var config = {
            type: 'bar',
            data: absensiData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: 'white',
                        bodyColor: 'white'
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
        };

        // Inisialisasi chart
        var absensiChart = new Chart(
            document.getElementById('absensiChart'),
            config
        );
    </script>
</body>
</html>

<?php
// Koneksi ke database dan include template

// Membuat koneksi database
$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    // Query untuk mengambil total penggajian per bulan
    $query = "SELECT DATE_FORMAT(tanggal_gaji, '%Y-%m') AS bulan, SUM(total) AS total_penggajian 
              FROM admin_penggajian 
              GROUP BY bulan 
              ORDER BY bulan ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Persiapkan data untuk grafik
    $bulan = [];
    $total_penggajian = [];

    foreach ($data as $row) {
        $bulan[] = $row['bulan'];
        $total_penggajian[] = $row['total_penggajian'];
    }
} else {
    echo "Koneksi ke database gagal.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Penggajian</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Grafik Total Penggajian Per Bulan</h2>
        <canvas id="gajiChart" width="400" height="200"></canvas>
        <script>
            var ctx = document.getElementById('gajiChart').getContext('2d');
            var gajiChart = new Chart(ctx, {
                type: 'bar',  // Bar chart type
                data: {
                    labels: <?php echo json_encode($bulan); ?>, // Bulan
                    datasets: [{
                        label: 'Total Penggajian',
                        data: <?php echo json_encode($total_penggajian); ?>, // Total penggajian
                        backgroundColor: 'rgba(0, 0, 128, 0.6)', // Navy blue color
                        borderColor: 'rgba(0, 0, 128, 1)', // Navy blue border color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
