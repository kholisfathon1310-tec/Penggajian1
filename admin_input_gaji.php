<?php
require_once "config.php"; // Koneksi ke database
require_once "template_admin/header.php"; // Header admin
require_once "template_admin/sidebar.php"; // Sidebar admin
require_once "template_admin/navbar.php"; // Sidebar admin

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_karyawan = $_POST['nama_karyawan'];
    $posisi = $_POST['posisi'];
    $periode = $_POST['periode'];
    $tanggal_gaji = $_POST['tanggal_gaji'];
    $pot_BPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];
    $opsi_lembur = $_POST['opsi_lembur'];
    $lembur = $_POST['lembur'] ?? 0;
    $gaji_lembur_per_jam = $_POST['gaji_lembur_per_jam'] ?? 0;

    // Kalkulasi total gaji
    $gaji_lembur = $lembur * $gaji_lembur_per_jam;
    $total = $transportasi - $pot_BPJS + $gaji_lembur;

    // Koneksi database
    $database = new Database();
    $conn = $database->getConnection();

    // Ambil NIP terakhir yang sudah ada
    $query = "SELECT NIP FROM admin_penggajian ORDER BY NIP DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $lastNIP = $stmt->fetchColumn();

    // Menghasilkan NIP baru
    if ($lastNIP) {
        // Ambil angka terakhir dari NIP
        $lastNumber = (int) substr($lastNIP, 1); // Mengambil angka setelah 'G' (misalnya G001 -> 1)
        $newNumber = $lastNumber + 1; // Menambahkan angka
    } else {
        // Jika belum ada data, mulai dari 1
        $newNumber = 1;
    }

    // Format ID dengan 'G' dan 3 digit angka (misalnya 'G001', 'G002')
    $newNIP = 'G' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    // Query untuk insert data
    $query = "INSERT INTO admin_penggajian (
        NIP, nama_karyawan, posisi, periode, tanggal_gaji, pot_BPJS, transportasi, 
        opsi_lembur, lembur, gaji_lembur_per_jam, total
    ) VALUES (
        :NIP, :nama_karyawan, :posisi, :periode, :tanggal_gaji, :pot_BPJS, :transportasi, 
        :opsi_lembur, :lembur, :gaji_lembur_per_jam, :total
    )";

    // Siapkan query
    $stmt = $conn->prepare($query);

    // Bind parameter
    $stmt->bindParam(':NIP', $newNIP);
    $stmt->bindParam(':nama_karyawan', $nama_karyawan);
    $stmt->bindParam(':posisi', $posisi);
    $stmt->bindParam(':periode', $periode);
    $stmt->bindParam(':tanggal_gaji', $tanggal_gaji);
    $stmt->bindParam(':pot_BPJS', $pot_BPJS);
    $stmt->bindParam(':transportasi', $transportasi);
    $stmt->bindParam(':opsi_lembur', $opsi_lembur);
    $stmt->bindParam(':lembur', $lembur);
    $stmt->bindParam(':gaji_lembur_per_jam', $gaji_lembur_per_jam);
    $stmt->bindParam(':total', $total);

    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: admin_gaji.php?success=1");
    } else {
        $error = "Gagal menyimpan data.";
    }
}

// Cek apakah NIP sudah dipilih, jika sudah ambil data nama dan posisi
if (isset($_GET['NIP'])) {
    $NIP = $_GET['NIP'];
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        $query = "SELECT nama_user, hak FROM user WHERE NIP = :NIP";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':NIP', $NIP);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $employee['nama_user'];
            $position = $employee['hak'];
        } else {
            echo "NIP tidak ditemukan di tabel user.";
        }
    }
}

// Fetch NIPs from the database for the dropdown
$database = new Database();
$conn = $database->getConnection();
$query = "SELECT NIP FROM user"; // Assume the NIP field is stored in the 'user' table
$stmt = $conn->prepare($query);
$stmt->execute();
$nips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Gaji Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Input Gaji Karyawan</h2>

    <?php 
    if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="admin_input_gaji.php" method="POST">
        <!-- NIP dropdown -->
        <div class="mb-3">
            <label for="NIP" class="form-label">NIP</label>
            <select class="form-control" id="NIP" name="NIP" required>
                <option value="">Pilih NIP</option>
                <?php foreach ($nips as $nip): ?>
                    <option value="<?= $nip['NIP']; ?>" <?= isset($NIP) && $NIP == $nip['NIP'] ? 'selected' : ''; ?>>
                        <?= $nip['NIP']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?= isset($name) ? $name : '' ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="posisi" class="form-label">Posisi</label>
            <input type="text" class="form-control" id="posisi" name="posisi" value="<?= isset($position) ? $position : '' ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="periode" class="form-label">Periode</label>
            <select class="form-control" id="periode" name="periode" required>
                <option value="">Pilih Periode</option>
                <option value="2025-01-01 to 2025-03-31">Januari - Maret 2025</option>
                <option value="2025-04-01 to 2025-06-30">April - Juni 2025</option>
                <option value="2025-07-01 to 2025-09-30">Juli - September 2025</option>
                <option value="2025-10-01 to 2025-12-31">Oktober - Desember 2025</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_gaji" class="form-label">Tanggal Gaji</label>
            <input type="date" class="form-control" id="tanggal_gaji" name="tanggal_gaji" required>
        </div>

        <div class="mb-3">
            <label for="pot_BPJS" class="form-label">Potongan BPJS</label>
            <input type="number" class="form-control" id="pot_BPJS" name="pot_BPJS" required>
        </div>

        <div class="mb-3">
            <label for="transportasi" class="form-label">Transportasi</label>
            <input type="number" class="form-control" id="transportasi" name="transportasi" required>
        </div>

        <div class="mb-3">
            <label for="opsi_lembur" class="form-label">Opsi Lembur</label>
            <select class="form-control" id="opsi_lembur" name="opsi_lembur" required>
                <option value="ya">Ya</option>
                <option value="tidak">Tidak</option>
            </select>
        </div>

        <div id="lembur_section" style="display: none;">
            <div class="mb-3">
                <label for="lembur" class="form-label">Jumlah Jam Lembur</label>
                <input type="number" class="form-control" id="lembur" name="lembur">
            </div>
        </div>

        <div id="gaji_lembur_section" style="display: none;">
            <div class="mb-3">
                <label for="gaji_lembur_per_jam" class="form-label">Gaji Lembur per Jam</label>
                <input type="number" class="form-control" id="gaji_lembur_per_jam" name="gaji_lembur_per_jam" value="50000">
            </div>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total Gaji</label>
            <input type="number" class="form-control" id="total" name="total" readonly value="0">
        </div>

        <div class="d-flex justify-content-between">
            <a href="admin_gaji.php" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<!-- Logika opsi lembur -->
<script>
    document.getElementById('opsi_lembur').addEventListener('change', function() {
        const lemburSection = document.getElementById('lembur_section');
        const gajiLemburSection = document.getElementById('gaji_lembur_section');
        if (this.value === 'ya') {
            lemburSection.style.display = 'block';
            gajiLemburSection.style.display = 'block';
        } else {
            lemburSection.style.display = 'none';
            gajiLemburSection.style.display = 'none';
        }
    });
</script>

</body>
</html>
