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
// AMBIL DATA USER
// ======================
$stmt = $conn->prepare("
    SELECT
        NIP,
        nama_user,
        hak
    FROM user
");

$stmt->execute();

$employees =
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

<style>

body {
    background-color: #f8f9fa;
}

.container {
    margin-top: 40px;
}

.card {
    border-radius: 10px;
}

</style>

</head>

<body>

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                Input Gaji Karyawan

            </h4>

        </div>

        <div class="card-body">

            <!-- ALERT -->
            <?php if (isset($_SESSION['message'])): ?>

                <div class="alert alert-success">

                    <?= htmlspecialchars($_SESSION['message']); ?>

                </div>

                <?php unset($_SESSION['message']); ?>

            <?php endif; ?>

            <!-- FORM -->
            <form action="simpan_gaji.php"
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

                        <?php foreach ($employees as $employee): ?>

                            <option
                                value="<?= htmlspecialchars($employee['NIP']); ?>"
                                data-nama="<?= htmlspecialchars($employee['nama_user']); ?>"
                                data-hak="<?= htmlspecialchars($employee['hak']); ?>">

                                <?= htmlspecialchars($employee['NIP']); ?>

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
                           readonly
                           required>

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
                           readonly
                           required>

                </div>

                <!-- PERIODE -->
                <div class="mb-3">

                    <label class="form-label">

                        Periode

                    </label>

                    <select class="form-control"
                            name="periode"
                            required>

                        <option value="">
                            Pilih Periode
                        </option>

                        <option value="Januari 2026">
                            Januari 2026
                        </option>

                        <option value="Februari 2026">
                            Februari 2026
                        </option>

                        <option value="Maret 2026">
                            Maret 2026
                        </option>

                        <option value="April 2026">
                            April 2026
                        </option>

                    </select>

                </div>

                <!-- TANGGAL GAJI -->
                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Gaji

                    </label>

                    <input type="date"
                           class="form-control"
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

                <!-- TRANSPORTASI -->
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
                           readonly>

                </div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-between">

                    <a href="admin_gaji.php"
                       class="btn btn-secondary">

                        Kembali

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        Simpan Data

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const nipSelect =
        document.getElementById('NIP');

    const namaInput =
        document.getElementById('nama_user');

    const hakInput =
        document.getElementById('hak');

    // ======================
    // AUTO FILL DATA USER
    // ======================
    nipSelect.addEventListener('change', function () {

        const selectedOption =
            this.options[this.selectedIndex];

        namaInput.value =
            selectedOption.getAttribute('data-nama');

        hakInput.value =
            selectedOption.getAttribute('data-hak');
    });

    // ======================
    // HITUNG TOTAL
    // ======================
    function hitungTotal() {

        const baseSalary =
            parseFloat(
                document.getElementById('base_salary').value
            ) || 0;

        const bpjs =
            parseFloat(
                document.getElementById('pot_BPJS').value
            ) || 0;

        const transportasi =
            parseFloat(
                document.getElementById('transportasi').value
            ) || 0;

        const potAbsen =
            parseFloat(
                document.getElementById('pot_absen').value
            ) || 0;

        const lembur =
            document.getElementById('lembur').value;

        const gajiLembur =
            (lembur === 'Iya')
            ? 50000
            : 0;

        const total =
            baseSalary
            - bpjs
            - potAbsen
            + transportasi
            + gajiLembur;

        document.getElementById('salary').value =
            total;
    }

    document.getElementById('base_salary')
        .addEventListener('input', hitungTotal);

    document.getElementById('pot_BPJS')
        .addEventListener('input', hitungTotal);

    document.getElementById('transportasi')
        .addEventListener('input', hitungTotal);

    document.getElementById('pot_absen')
        .addEventListener('input', hitungTotal);

    document.getElementById('lembur')
        .addEventListener('change', hitungTotal);

});

</script>

</body>
</html>
