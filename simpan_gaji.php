<?php
// Koneksi ke database
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

// Format angka ke dalam format Rupiah
function formatRupiah($angka) {
    return "Rp" . number_format($angka, 0, ',', '.');
}

// Ambil data dari form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NIP = $_POST['NIP'];
    $nama_user = $_POST['nama_user'];
    $tanggal_gaji = $_POST['tanggal_gaji'];
    $hak = $_POST['hak'];
    $periode = $_POST['periode'];
    $baseSalary = $_POST['base_salary'];
    $potBPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];
    $potAbsen = $_POST['pot_absen'];
    $lembur = $_POST['lembur']; // Nilai dari dropdown ("Iya" atau "Tidak")

    // Hitung gaji lembur (tambah 50 jika lembur "Iya")
    $gajiLembur = ($lembur === "Iya") ? 50000 : 0;

    // Hitung total gaji
    $totalGaji = $baseSalary - $potBPJS - $potAbsen + $transportasi + $gajiLembur;

    // Koneksi ke database
    $database = new Database();
    $conn = $database->getConnection();

    // Cek apakah NIP sudah ada
    $checkQuery = "SELECT COUNT(*) FROM admin_penggajian WHERE NIP = :NIP";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bindParam(':NIP', $NIP);
    $stmt->execute();
    $exists = $stmt->fetchColumn();

    // Jika NIP sudah ada, lakukan UPDATE
    if ($exists > 0) {
        $query = "UPDATE admin_penggajian 
          SET nama_user = :nama_user, hak = :hak, base_salary = :base_salary, periode = :periode, pot_BPJS = :pot_BPJS, tanggal_gaji = :tanggal_gaji,
              transportasi = :transportasi, pot_absen = :pot_absen, lembur = :lembur, salary = :salary 
          WHERE NIP = :NIP";

    } else {
        // Jika NIP belum ada, lakukan INSERT
        $query = "INSERT INTO admin_penggajian (NIP, nama_user, hak, periode, base_salary, pot_BPJS, tanggal_gaji, transportasi, pot_absen, lembur, salary) 
                  VALUES (:NIP, :nama_user, :hak, :base_salary, :periode, :pot_BPJS, :tanggal_gaji, :transportasi, :pot_absen, :lembur, :salary)";
    }

    // Prepare statement dan bind parameter
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':NIP', $NIP);
    $stmt->bindParam(':nama_user', $nama_user);
    $stmt->bindParam(':hak', $hak);
    $stmt->bindParam(':periode', $periode);
    $stmt->bindParam(':base_salary', $baseSalary);
    $stmt->bindParam(':tanggal_gaji', $tanggal_gaji);
    $stmt->bindParam(':pot_BPJS', $potBPJS);
    $stmt->bindParam(':transportasi', $transportasi);
    $stmt->bindParam(':pot_absen', $potAbsen);
    $stmt->bindParam(':lembur', $lembur);
    $stmt->bindParam(':salary', $totalGaji);

    // Execute query
    if ($stmt->execute()) {
        // Redirect ke halaman admin gaji
        header("Location: admin_gaji.php"); // Ganti dengan URL halaman admin gaji
        exit();
    } else {
        echo "Terjadi kesalahan saat menyimpan data.";
    }
}
?>
