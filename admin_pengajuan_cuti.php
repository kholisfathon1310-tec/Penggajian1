<?php
ob_start();

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
// VALIDASI ID CUTI
// ======================
if (isset($_GET['id_cuti'])) {

    $id_cuti = $_GET['id_cuti'];

    // ======================
    // AMBIL DATA CUTI
    // ======================
    $stmt = $conn->prepare("
        SELECT *
        FROM admin_pengajuan_cuti
        WHERE id_cuti = :id
    ");

    $stmt->execute([
        ':id' => $id_cuti
    ]);

    $data =
        $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Detail Permohonan Cuti
</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.content {
    padding: 20px;
    margin-top: 90px;
}

.table th {
    background-color: #343a40;
    color: white;
}

.btn-success {
    background-color: green;
}

.btn-danger {
    background-color: red;
}

.alert {
    margin-top: 20px;
}

.table-responsive {
    overflow-x: auto;
}

.fw-bold {
    font-weight: bold;
}

</style>

</head>

<body>

<div class="content p-4">

    <h2 class="text-center fw-bold">

        Detail Permohonan Cuti Karyawan

    </h2>

    <!-- NOTIF -->
    <?php if (isset($_SESSION['notif'])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= htmlspecialchars($_SESSION['notif']) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

        <?php unset($_SESSION['notif']); ?>

    <?php endif; ?>

    <!-- DETAIL -->
    <div class="mb-4">

        <p>

            <strong>NIP:</strong>

            <?= htmlspecialchars($data['NIP']) ?>

        </p>

        <p>

            <strong>Nama:</strong>

            <?= htmlspecialchars($data['nama']) ?>

        </p>

        <p>

            <strong>Posisi:</strong>

            <?= htmlspecialchars($data['Hak']) ?>

        </p>

    </div>

    <!-- TABEL -->
    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Konfirmasi Pengajuan</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>

                        <?= htmlspecialchars($data['tanggal_awal']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($data['tanggal_akhir']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($data['jenis_cuti']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($data['tanggal_pengajuan']) ?>

                    </td>

                    <td>

                        <div class="d-flex justify-content-center">

                            <!-- SETUJUI -->
                            <form method="POST"
                                  class="me-2">

                                <button type="submit"
                                        name="status"
                                        value="Disetujui"
                                        class="btn btn-success btn-sm">

                                    Setujui

                                </button>

                            </form>

                            <!-- TOLAK -->
                            <form method="POST">

                                <button type="submit"
                                        name="status"
                                        value="Ditolak"
                                        class="btn btn-danger btn-sm">

                                    Tolak

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

<?php
// ======================
// UPDATE STATUS
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['status'])
) {

    $status = $_POST['status'];

    if (
        in_array(
            $status,
            ['Disetujui', 'Ditolak']
        )
    ) {

        $stmtUpdate = $conn->prepare("
            UPDATE admin_pengajuan_cuti
            SET status = :status
            WHERE id_cuti = :id
        ");

        $result = $stmtUpdate->execute([

            ':status' => $status,

            ':id' => $id_cuti
        ]);

        if ($result) {

            $_SESSION['notif'] =
                "Status pengajuan cuti berhasil diperbarui menjadi '$status'.";

            header("Location: admin_cuti_utama.php");

            exit();
        }
    }
}
?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php

    } else {

        echo "
        <div class='alert alert-danger'>

            Data pengajuan cuti tidak ditemukan untuk ID ini.

        </div>
        ";
    }

} else {

    echo "
    <div class='alert alert-warning'>

        ID Cuti tidak valid!

    </div>
    ";
}

ob_end_flush();
?>
