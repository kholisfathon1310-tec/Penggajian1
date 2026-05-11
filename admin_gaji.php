<?php
session_start();

require_once 'config.php';
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";

// ======================
// KONEKSI DATABASE
// ======================
$db = new Database();
$conn = $db->getConnection();

// ======================
// AMBIL DATA USER
// ======================
$stmt = $conn->prepare("
    SELECT NIP, nama_user, hak 
    FROM user
");

$stmt->execute();

$employeeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ======================
// AMBIL DATA GAJI
// ======================
$stmtSalary = $conn->prepare("
    SELECT * 
    FROM admin_penggajian
    ORDER BY tanggal_gaji DESC
");

$stmtSalary->execute();

$salaryData = $stmtSalary->fetchAll(PDO::FETCH_ASSOC);

$salaryEditData = null;

if (isset($_GET['edit'])) {

    $nipToEdit = $_GET['edit'];

    $stmtEdit = $conn->prepare("
        SELECT * 
        FROM admin_penggajian 
        WHERE NIP = :nip
    ");

    $stmtEdit->execute([
        ':nip' => $nipToEdit
    ]);

    $salaryEditData = $stmtEdit->fetch(PDO::FETCH_ASSOC);
}

// ======================
// UPDATE DATA GAJI
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $NIP = $_POST['NIP'];
    $baseSalary = $_POST['base_salary'];
    $periode = $_POST['periode'];
    $potBPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];
    $potAbsen = $_POST['pot_absen'];
    $lembur = $_POST['lembur'];

    $lemburRate = 50000;

    $gajiLembur = ($lembur === 'Iya') ? $lemburRate : 0;

    $totalGaji = $baseSalary
                - $potBPJS
                - $potAbsen
                + $transportasi
                + $gajiLembur;

    $stmtUpdate = $conn->prepare("
        UPDATE admin_penggajian
        SET
            base_salary = :base_salary,
            periode = :periode,
            pot_BPJS = :pot_BPJS,
            transportasi = :transportasi,
            pot_absen = :pot_absen,
            lembur = :lembur,
            salary = :salary
        WHERE NIP = :nip
    ");

    $stmtUpdate->execute([
        ':base_salary' => $baseSalary,
        ':periode' => $periode,
        ':pot_BPJS' => $potBPJS,
        ':transportasi' => $transportasi,
        ':pot_absen' => $potAbsen,
        ':lembur' => $lembur,
        ':salary' => $totalGaji,
        ':nip' => $NIP
    ]);

    header("Location: admin_gaji.php");
    exit;
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

    // OPEN MODAL
    openModalButton.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // CLOSE MODAL
    closeModalButton.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // AUTO FILL USER
    nipSelect.addEventListener('change', function () {

        const selectedNIP = nipSelect.value;

        const selectedEmployee =
            <?= json_encode($employeeData) ?>.find(emp => emp.NIP === selectedNIP);

        if (selectedEmployee) {

            namaUserInput.value = selectedEmployee.nama_user;
            hakInput.value = selectedEmployee.hak;
        }
    });

    // HITUNG TOTAL
    function calculateTotalSalary() {

        const baseSalary =
            parseFloat(document.getElementById('base_salary').value) || 0;

        const potBPJS =
            parseFloat(document.getElementById('pot_BPJS').value) || 0;

        const transportasi =
            parseFloat(document.getElementById('transportasi').value) || 0;

        const potAbsen =
            parseFloat(document.getElementById('pot_absen').value) || 0;

        const lembur =
            document.getElementById('lembur').value;

        const lemburRate = (lembur === 'Iya') ? 50000 : 0;

        const total =
            baseSalary
            - potBPJS
            - potAbsen
            + transportasi
            + lemburRate;

        document.getElementById('total').value = total;
    }

    document.getElementById('base_salary')
        .addEventListener('input', calculateTotalSalary);

    document.getElementById('pot_BPJS')
        .addEventListener('input', calculateTotalSalary);

    document.getElementById('transportasi')
        .addEventListener('input', calculateTotalSalary);

    document.getElementById('pot_absen')
        .addEventListener('input', calculateTotalSalary);

    document.getElementById('lembur')
        .addEventListener('change', calculateTotalSalary);
});
</script>
</head>

<body>

<div class="container mt-5">

    <h2 class="text-center">
        Daftar Gaji Karyawan
    </h2>

    <div class="d-flex justify-content-end mb-3">

        <button id="openModalButton"
                class="btn btn-success">

            Data Gaji

        </button>

    </div>

    <!-- TABEL -->
    <table class="table table-bordered">

        <thead>

        <tr>
            <th>NIP</th>
            <th>Nama</th>
            <th>Posisi</th>
            <th>Tanggal Gaji</th>
            <th>Gaji Pokok</th>
            <th>Periode</th>
            <th>Potongan BPJS</th>
            <th>Lembur</th>
            <th>Total Gaji</th>
        </tr>

        </thead>

        <tbody>

        <?php if ($salaryData): ?>

            <?php foreach ($salaryData as $salary): ?>

                <tr>

                    <td><?= htmlspecialchars($salary['NIP']) ?></td>

                    <td><?= htmlspecialchars($salary['nama_user']) ?></td>

                    <td><?= htmlspecialchars($salary['hak']) ?></td>

                    <td><?= htmlspecialchars($salary['tanggal_gaji']) ?></td>

                    <td><?= htmlspecialchars($salary['base_salary']) ?></td>

                    <td><?= htmlspecialchars($salary['periode']) ?></td>

                    <td><?= htmlspecialchars($salary['pot_BPJS']) ?></td>

                    <td><?= htmlspecialchars($salary['lembur']) ?></td>

                    <td>
                        Rp <?= number_format($salary['salary'], 0, ',', '.') ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="9" class="text-center">
                    Tidak ada data gaji.
                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<!-- MODAL -->
<div id="inputSalaryModal"
     class="modal"
     style="display:none;">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Input Gaji Karyawan
                </h5>

                <button type="button"
                        class="btn-close"
                        id="closeModalButton">
                </button>

            </div>

            <div class="modal-body">

                <form action="simpan_gaji.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            NIP
                        </label>

                        <select class="form-select"
                                id="NIP"
                                name="NIP"
                                required>

                            <option value="" disabled selected>
                                Pilih NIP
                            </option>

                            <?php foreach ($employeeData as $employee): ?>

                                <option value="<?= $employee['NIP']; ?>">

                                    <?= $employee['NIP']; ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nama
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nama_user"
                               name="nama_user"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Posisi
                        </label>

                        <input type="text"
                               class="form-control"
                               id="hak"
                               name="hak"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Periode
                        </label>

                        <select class="form-control"
                                id="periode"
                                name="periode"
                                required>

                            <option value="">
                                Pilih Periode
                            </option>

                            <option value="2025-01-01 to 2025-03-31">
                                Januari - Maret 2025
                            </option>

                            <option value="2025-04-01 to 2025-06-30">
                                April - Juni 2025
                            </option>

                            <option value="2025-07-01 to 2025-09-30">
                                Juli - September 2025
                            </option>

                            <option value="2025-10-01 to 2025-12-31">
                                Oktober - Desember 2025
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Gaji Pokok
                        </label>

                        <input type="number"
                               class="form-control"
                               id="base_salary"
                               name="base_salary"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Potongan BPJS
                        </label>

                        <input type="number"
                               class="form-control"
                               id="pot_BPJS"
                               name="pot_BPJS"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Transportasi
                        </label>

                        <input type="number"
                               class="form-control"
                               id="transportasi"
                               name="transportasi"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Potongan Absen
                        </label>

                        <input type="number"
                               class="form-control"
                               id="pot_absen"
                               name="pot_absen">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Lembur
                        </label>

                        <select class="form-control"
                                id="lembur"
                                name="lembur"
                                required>

                            <option value="Tidak">
                                Tidak
                            </option>

                            <option value="Iya">
                                Iya
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Total Gaji
                        </label>

                        <input type="number"
                               class="form-control"
                               id="total"
                               name="total"
                               readonly>

                    </div>

                    <button type="submit"
                            class="btn btn-primary"
                            name="update">

                        Submit

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
