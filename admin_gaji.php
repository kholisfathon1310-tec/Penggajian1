<?php
require_once "config.php"; // Koneksi ke database
require_once "template_admin/header.php"; // Header admin
require_once "template_admin/sidebar.php"; // Sidebar admin

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

// Mengambil data NIP dan Nama dari tabel user
$database = new Database();
$conn = $database->getConnection();
$query = "SELECT NIP, nama_user, hak FROM user"; // Ambil data dari tabel user
$stmt = $conn->prepare($query);
$stmt->execute();
$employeeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil data gaji karyawan
$querySalary = "SELECT * FROM admin_penggajian"; // Ambil data dari tabel gaji
$stmtSalary = $conn->prepare($querySalary);
$stmtSalary->execute();
$salaryData = $stmtSalary->fetchAll(PDO::FETCH_ASSOC);

// Menangani request edit data gaji
if (isset($_GET['edit'])) {
    $nipToEdit = $_GET['edit'];
    $queryEdit = "SELECT * FROM admin_penggajian WHERE NIP = :NIP";
    $stmtEdit = $conn->prepare($queryEdit);
    $stmtEdit->bindParam(':NIP', $nipToEdit);
    $stmtEdit->execute();
    $salaryEditData = $stmtEdit->fetch(PDO::FETCH_ASSOC);
}

// Mengupdate data gaji
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $NIP = $_POST['NIP'];
    $baseSalary = $_POST['base_salary'];
    $periode = $_POST['periode'];
    $potBPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];
    $potAbsen = $_POST['pot_absen'];
    $lembur = $_POST['lembur'];

    $lemburRate = 50000; // Gaji lembur per jam
    $gajiLembur = $lembur * $lemburRate;
    $totalGaji = $baseSalary - $potBPJS - $potAbsen + $transportasi + $gajiLembur;

    $queryUpdate = "UPDATE admin_penggajian SET base_salary = :base_salary, , periode = :periode, pot_BPJS = :pot_BPJS, transportasi = :transportasi, pot_absen = :pot_absen, lembur = :lembur, salary = :salary WHERE NIP = :NIP";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bindParam(':base_salary', $baseSalary);
    $stmtUpdate->bindParam(':pot_BPJS', $potBPJS);
    $stmtUpdate->bindParam(':periode', $periode);
    $stmtUpdate->bindParam(':transportasi', $transportasi);
    $stmtUpdate->bindParam(':pot_absen', $potAbsen);
    $stmtUpdate->bindParam(':lembur', $lembur);
    $stmtUpdate->bindParam(':salary', $totalGaji);
    $stmtUpdate->bindParam(':NIP', $NIP);
    $stmtUpdate->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gaji Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const openModalButton = document.getElementById("openModalButton");
            const modal = document.getElementById("inputSalaryModal");
            const closeModalButton = document.getElementById("closeModalButton");
            const nipSelect = document.getElementById('NIP');
            const namaUserInput = document.getElementById('nama_user');
            const hakInput = document.getElementById('hak');

            // Open modal
            openModalButton.addEventListener("click", function () {
                modal.style.display = "block";
            });

            // Close modal
            closeModalButton.addEventListener("click", function () {
                modal.style.display = "none";
            });

            // Menangani pemilihan NIP untuk mengisi nama dan posisi
            nipSelect.addEventListener('change', function () {
                const selectedNIP = nipSelect.value;
                const selectedEmployee = <?= json_encode($employeeData) ?>.find(emp => emp.NIP === selectedNIP);

                if (selectedEmployee) {
                    namaUserInput.value = selectedEmployee.nama_user;
                    hakInput.value = selectedEmployee.hak;
                }
            });

            // Function to calculate total salary
            function calculateTotalSalary() {
                const baseSalary = parseFloat(document.getElementById('base_salary').value) || 0;
                const periode = parseFloat(document.getElementById('periode').value) || 0;
                const potBPJS = parseFloat(document.getElementById('pot_BPJS').value) || 0;
                const transportasi = parseFloat(document.getElementById('transportasi').value) || 0;
                const potAbsen = parseFloat(document.getElementById('pot_absen').value) || 0;
                const lembur = parseFloat(document.getElementById('lembur').value) || 0;
                const lemburRate = 50000; // Gaji lembur per jam

                // Menghitung total gaji
                const gajiLembur = lembur * lemburRate;
                const totalGaji = baseSalary - potBPJS - potAbsen + transportasi + gajiLembur;

                // Update total salary in form
                document.getElementById('total').value = totalGaji.toFixed(2);
            }

            // Event listeners to recalculate when values change
            document.getElementById('base_salary').addEventListener('input', calculateTotalSalary);
            document.getElementById('Periode').addEventListener('input', calculateTotalSalary);
            document.getElementById('pot_BPJS').addEventListener('input', calculateTotalSalary);
            document.getElementById('transportasi').addEventListener('input', calculateTotalSalary);
            document.getElementById('pot_absen').addEventListener('input', calculateTotalSalary);
            document.getElementById('lembur').addEventListener('input', calculateTotalSalary);
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Daftar Gaji Karyawan</h2>
    <div class="d-flex justify-content-end mb-3">
        <button id="openModalButton" class="btn btn-success"> Data Gaji</button>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
           
            <th>NIP</th>
            <th>Nama</th>
            <th>Posisi</th>
            <th>Tanggal gaji</th>
            <th>Gaji pokok</th> 
            <th>Periode</th> 
            <th>Potongan BPJS</th>
            <th>Lembur</th>
            <th>Total Gaji</th>
        
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($salaryData)) {
            foreach ($salaryData as $index => $salary) {
                echo "<tr>";
               
                echo "<td>" . $salary['NIP'] . "</td>";
                echo "<td>" . $salary['nama_user'] . "</td>";
                echo "<td>" . $salary['hak'] . "</td>";
                echo "<td>" . $salary['tanggal_gaji'] . "</td>";
                echo "<td>" . $salary['base_salary'] . "</td>";
                echo "<td>" . $salary['periode'] . "</td>";
                echo "<td>" . $salary['pot_BPJS'] . "</td>";
                echo "<td>" . $salary['lembur'] . "</td>";
                echo "<td>Rp " . number_format($salary['salary'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9' class='text-center'>Tidak ada data gaji.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Modal untuk input gaji -->
<div id="inputSalaryModal" class="modal" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Gaji Karyawan</h5>
                <button type="button" class="btn-close" id="closeModalButton"></button>
            </div>
            <div class="modal-body">
            <form action="simpan_gaji.php" method="POST">
                    <div class="mb-3">
                        <label for="NIP" class="form-label">NIP</label>
                        <select class="form-select" id="NIP" name="NIP" required>
                            <option value="" disabled selected>Pilih NIP</option>
                            <?php
                            foreach ($employeeData as $employee) {
                                $selected = isset($salaryEditData) && $salaryEditData['NIP'] == $employee['NIP'] ? 'selected' : '';
                                echo "<option value='" . $employee['NIP'] . "' $selected>" . $employee['NIP'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user" readonly value="<?= isset($salaryEditData) ? $salaryEditData['nama_user'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="hak" class="form-label">Posisi</label>
                        <input type="text" class="form-control" id="hak" name="hak" readonly value="<?= isset($salaryEditData) ? $salaryEditData['hak'] : '' ?>">
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
                        <label for="salary" class="form-label">Gaji Pokok</label>
                        <input type="text" class="form-control" id="base_salary" name="base_salary" required value="<?= isset($salaryEditData) ? $salaryEditData['base_salary'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="pot_BPJS" class="form-label">Potongan BPJS</label>
                        <input type="number" class="form-control" id="pot_BPJS" name="pot_BPJS" required value="<?= isset($salaryEditData) ? $salaryEditData['pot_BPJS'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_gaji" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_gaji" name="tanggal_gaji" required value="<?= isset($salaryEditData) ? $salaryEditData['tangga_gaji'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="transportasi" class="form-label">Transportasi</label>
                        <input type="number" class="form-control" id="transportasi" name="transportasi" required value="<?= isset($salaryEditData) ? $salaryEditData['transportasi'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="pot_absen" class="form-label">Pot Absen</label>
                        <input type="number" class="form-control" id="pot_absen" name="pot_absen" value="<?= isset($salaryEditData) ? $salaryEditData['pot_absen'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="lembur" class="form-label">Lembur</label>
                        <select class="form-control" id="lembur" name="lembur" required>    
                            <option value="Tidak">Tidak</option>
                            <option value="Iya">Iya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total Gaji</label>
                        <input type="number" class="form-control" id="total" name="total" readonly value="<?= isset($salaryEditData) ? $salaryEditData['salary'] : 0 ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="update">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
