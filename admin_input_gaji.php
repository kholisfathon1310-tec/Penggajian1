<?php
session_start();

require_once 'Database.php';
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// ======================
// KONEKSI DATABASE
// ======================
$db = new Database();
$conn = $db->getConnection();

// ======================
// SIMPAN DATA
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $NIP = $_POST['NIP'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $posisi = $_POST['posisi'];
    $periode = $_POST['periode'];
    $tanggal_gaji = $_POST['tanggal_gaji'];
    $pot_BPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];

    $opsi_lembur = $_POST['opsi_lembur'];

    $lembur = $_POST['lembur'] ?? 0;
    $gaji_lembur_per_jam = $_POST['gaji_lembur_per_jam'] ?? 0;

    // ======================
    // HITUNG TOTAL
    // ======================
    $gaji_lembur = $lembur * $gaji_lembur_per_jam;

    $total = $transportasi
            - $pot_BPJS
            + $gaji_lembur;

    // ======================
    // INSERT
    // ======================
    $stmt = $conn->prepare("
        INSERT INTO admin_penggajian (
            NIP,
            nama_karyawan,
            posisi,
            periode,
            tanggal_gaji,
            pot_BPJS,
            transportasi,
            opsi_lembur,
            lembur,
            gaji_lembur_per_jam,
            total
        )
        VALUES (
            :nip,
            :nama,
            :posisi,
            :periode,
            :tanggal,
            :bpjs,
            :transportasi,
            :opsi_lembur,
            :lembur,
            :gaji_lembur,
            :total
        )
    ");

    $result = $stmt->execute([
        ':nip' => $NIP,
        ':nama' => $nama_karyawan,
        ':posisi' => $posisi,
        ':periode' => $periode,
        ':tanggal' => $tanggal_gaji,
        ':bpjs' => $pot_BPJS,
        ':transportasi' => $transportasi,
        ':opsi_lembur' => $opsi_lembur,
        ':lembur' => $lembur,
        ':gaji_lembur' => $gaji_lembur_per_jam,
        ':total' => $total
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
        SELECT nama_user, hak
        FROM user
        WHERE NIP = :nip
    ");

    $stmt->execute([
        ':nip' => $NIP
    ]);

    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {

        $name = $employee['nama_user'];
        $position = $employee['hak'];
    }
}

// ======================
// AMBIL SEMUA NIP
// ======================
$stmt = $conn->prepare("
    SELECT NIP
    FROM user
");

$stmt->execute();

$nips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Input Gaji Karyawan</title>

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

    <form action="admin_input_gaji.php" method="POST">

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

                    <option value="<?= $nip['NIP']; ?>"
                        <?= (isset($NIP) && $NIP == $nip['NIP']) ? 'selected' : ''; ?>>

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
                   id="nama_karyawan"
                   name="nama_karyawan"
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
                   id="posisi"
                   name="posisi"
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

        <!-- OPSI LEMBUR -->
        <div class="mb-3">

            <label class="form-label">
                Opsi Lembur
            </label>

            <select class="form-control"
                    id="opsi_lembur"
                    name="opsi_lembur"
                    required>

                <option value="ya">
                    Ya
                </option>

                <option value="tidak">
                    Tidak
                </option>

            </select>

        </div>

        <!-- LEMBUR -->
        <div id="lembur_section"
             style="display:none;">

            <div class="mb-3">

                <label class="form-label">
                    Jumlah Jam Lembur
                </label>

                <input type="number"
                       class="form-control"
                       id="lembur"
                       name="lembur">

            </div>

        </div>

        <!-- GAJI LEMBUR -->
        <div id="gaji_lembur_section"
             style="display:none;">

            <div class="mb-3">

                <label class="form-label">
                    Gaji Lembur per Jam
                </label>

                <input type="number"
                       class="form-control"
                       id="gaji_lembur_per_jam"
                       name="gaji_lembur_per_jam"
                       value="50000">

            </div>

        </div>

        <!-- TOTAL -->
        <div class="mb-3">

            <label class="form-label">
                Total Gaji
            </label>

            <input type="number"
                   class="form-control"
                   id="total"
                   name="total"
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

<!-- SCRIPT -->
<script>

document.getElementById('opsi_lembur')
.addEventListener('change', function() {

    const lemburSection =
        document.getElementById('lembur_section');

    const gajiSection =
        document.getElementById('gaji_lembur_section');

    if (this.value === 'ya') {

        lemburSection.style.display = 'block';
        gajiSection.style.display = 'block';

    } else {

        lemburSection.style.display = 'none';
        gajiSection.style.display = 'none';
    }
});

</script>

</body>
</html>
