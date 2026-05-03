<?php
// Koneksi ke database dan include template
require_once "config.php";
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";
require_once "template_admin/footer.php";

// Kelas Database
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
                type: 'line',
                data: {
                    labels: <?php echo json_encode($bulan); ?>, // Bulan
                    datasets: [{
                        label: 'Total Penggajian',
                        data: <?php echo json_encode($total_penggajian); ?>, // Total penggajian
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
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
