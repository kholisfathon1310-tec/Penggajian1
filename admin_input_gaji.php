<?php
session_start();

require_once 'config.php';

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// ======================
// KONEKSI DATABASE
// ======================
$conn = $koneksi;

// ======================
// SIMPAN DATA
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $NIP = $_POST['NIP'];

    $nama_user = $_POST['nama_user'];

    $hak = $_POST['hak'];

    $periode = $_POST['periode'];

    $tanggal_gaji = $_POST['tanggal_gaji'];

    $base_salary = $_POST['base_salary'];

    $pot_BPJS = $_POST['pot_BPJS'];

    $transportasi = $_POST['transportasi'];

    $pot_absen = $_POST['pot_absen'];

    $lembur = $_POST['lembur'];

    // ======================
    // HITUNG LEMBUR
    // ======================
    $gajiLembur =
        ($lembur === 'Iya')
        ? 50000
        : 0;

    // ======================
    // HITUNG TOTAL
    // ======================
    $salary =
        $base_salary
        - $pot_BPJS
        - $pot_absen
        + $transportasi
        + $gajiLembur;

    // ======================
    // INSERT / UPDATE
    // ======================
    $cek = $conn->prepare("
        SELECT COUNT(*)
        FROM admin_penggajian
        WHERE NIP = :nip
    ");

    $cek->execute([
        ':nip' => $NIP
    ]);

    $exists = $cek->fetchColumn();

    if ($exists > 0) {

        $stmt = $conn->prepare("
            UPDATE admin_penggajian
            SET
                nama_user = :nama_user,
                hak = :hak,
                periode = :periode,
                tanggal_gaji = :tanggal_gaji,
                base_salary = :base_salary,
                pot_BPJS = :pot_BPJS,
                transportasi = :transportasi,
                pot_absen = :pot_absen,
                lembur = :lembur,
                salary = :salary
            WHERE NIP = :nip
        ");

    } else {

        $stmt = $conn->prepare("
            INSERT INTO admin_penggajian (
                NIP,
                nama_user,
                hak,
                periode,
                tanggal_gaji,
                base_salary,
                pot_BPJS,
                transportasi,
                pot_absen,
                lembur,
                salary
            )
            VALUES (
                :nip,
                :nama_user,
                :hak,
                :periode,
                :tanggal_gaji,
                :base_salary,
                :pot_BPJS,
                :transportasi,
                :pot_absen,
                :lembur,
                :salary
            )
        ");
    }

    $result = $stmt->execute([

        ':nip' => $NIP,

        ':nama_user' => $nama_user,

        ':hak' => $hak,

        ':periode' => $periode,

        ':tanggal_gaji' => $tanggal_gaji,

        ':base_salary' => $base_salary,

        ':pot_BPJS' => $pot_BPJS,

        ':transportasi' => $transportasi,

        ':pot_absen' => $pot_absen,

        ':lembur' => $lembur,

        ':salary' => $salary
    ]);

    if ($result) {

        header("Location: admin_gaji.php?success=1");

        exit;

    } else {

        $error = "Gagal menyimpan data.";
    }
}

// ======================
// AMBIL DATA USER
// ======================
$name = '';

$position = '';

if (isset($_GET['NIP'])) {

    $NIP = $_GET['NIP'];

    $stmt = $conn->prepare("
        SELECT
            nama_user,
            hak
        FROM user
        WHERE NIP = :nip
    ");

    $stmt->execute([
        ':nip' => $NIP
    ]);

    $employee =
        $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {

        $name =
            $employee['nama_user'];

        $position =
            $employee['hak'];
    }
}

// ======================
// AMBIL SEMUA NIP
// ======================
$stmt = $conn->prepare("
    SELECT
        NIP
    FROM user
");

$stmt->execute();

$nips =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Input Gaji Karyawan
</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>

<body>

<div class="container mt-5">

    <h2 class="text-center">

        Input Gaji Karyawan

    </h2>

    <?php if (isset($error)): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>

    <form action="admin_input_gaji.php"
          method="POST">

        <!-- NIP -->
        <div class="mb-3">

            <label class="form-label">

                NIP

            </label>

            <select class="form-control"
                    id="NIP"
                    name="NIP"
                    required>

                <option value="">
                    Pilih NIP
                </option>

                <?php foreach ($nips as $nip): ?>

                    <option value="<?= $nip['NIP']; ?>">

                        <?= $nip['NIP']; ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- NAMA -->
        <div class="mb-3">

            <label class="form-label">

                Nama Karyawan

            </label>

            <input type="text"
                   class="form-control"
                   id="nama_user"
                   name="nama_user"
                   value="<?= htmlspecialchars($name) ?>"
                   readonly>

        </div>

        <!-- POSISI -->
        <div class="mb-3">

            <label class="form-label">

                Posisi

            </label>

            <input type="text"
                   class="form-control"
                   id="hak"
                   name="hak"
                   value="<?= htmlspecialchars($position) ?>"
                   readonly>

        </div>

        <!-- PERIODE -->
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

        <!-- TANGGAL -->
        <div class="mb-3">

            <label class="form-label">

                Tanggal Gaji

            </label>

            <input type="date"
                   class="form-control"
                   id="tanggal_gaji"
                   name="tanggal_gaji"
                   required>

        </div>

        <!-- GAJI POKOK -->
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

        <!-- BPJS -->
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

        <!-- TRANSPORT -->
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

        <!-- POTONGAN ABSEN -->
        <div class="mb-3">

            <label class="form-label">

                Potongan Absen

            </label>

            <input type="number"
                   class="form-control"
                   id="pot_absen"
                   name="pot_absen"
                   value="0">

        </div>

        <!-- LEMBUR -->
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

        <!-- TOTAL -->
        <div class="mb-3">

            <label class="form-label">

                Total Gaji

            </label>

            <input type="number"
                   class="form-control"
                   id="salary"
                   name="salary"
                   readonly
                   value="0">

        </div>

        <div class="d-flex justify-content-between">

            <a href="admin_gaji.php"
               class="btn btn-secondary">

                Kembali

            </a>

            <button type="submit"
                    class="btn btn-primary">

                Simpan

            </button>

        </div>

    </form>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const nipSelect =
        document.getElementById('NIP');

    const namaInput =
        document.getElementById('nama_user');

    const hakInput =
        document.getElementById('hak');

    const employeeData =
        <?= json_encode($nips); ?>;

    // ======================
    // AUTO HITUNG TOTAL
    // ======================
    function calculateTotal() {

        const baseSalary =
            parseFloat(
                document.getElementById('base_salary').value
            ) || 0;

        const bpjs =
            parseFloat(
                document.getElementById('pot_BPJS').value
            ) || 0;

        const transport =
            parseFloat(
                document.getElementById('transportasi').value
            ) || 0;

        const potAbsen =
            parseFloat(
                document.getElementById('pot_absen').value
            ) || 0;

        const lembur =
            document.getElementById('lembur').value;

        const lemburRate =
            (lembur === 'Iya')
            ? 50000
            : 0;

        const total =
            baseSalary
            - bpjs
            - potAbsen
            + transport
            + lemburRate;

        document.getElementById('salary').value =
            total;
    }

    document.getElementById('base_salary')
        .addEventListener('input', calculateTotal);

    document.getElementById('pot_BPJS')
        .addEventListener('input', calculateTotal);

    document.getElementById('transportasi')
        .addEventListener('input', calculateTotal);

    document.getElementById('pot_absen')
        .addEventListener('input', calculateTotal);

    document.getElementById('lembur')
        .addEventListener('change', calculateTotal);
});

</script>

</body>
</html>
