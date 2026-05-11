<?php
session_start();

require_once "config.php";

require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";

// ======================
// AMBIL NIP LOGIN
// ======================
$nip =
    isset($_SESSION['NIP'])
    ? $_SESSION['NIP']
    : '';

// ======================
// QUERY DATA CUTI
// ======================
try {

    $stmt = $koneksi->prepare("
        SELECT *
        FROM admin_pengajuan_cuti
        ORDER BY id_cuti DESC
    ");

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Terjadi kesalahan database: " . $e->getMessage());
}

// ======================
// CLASS MAIN CONTENT
// ======================
class MainContent
{
    // ======================
    // TABLE CUTI
    // ======================
    public static function renderTable($result)
    {
        echo '
        <div class="content mt-5">

            <h4 class="mb-4">
                Data Permohonan Cuti
            </h4>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahDataModal">

                    <i class="bi bi-person-plus"></i>

                    Tambah

                </button>

            </div>

            <table class="table table-striped table-bordered table-hover text-center">

                <thead class="table-light">

                    <tr>

                        <th>Id Cuti</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Alesan Cuti</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>
        ';

        // ======================
        // LOOP DATA
        // ======================
        if ($result) {

            foreach ($result as $row) {

                $status = $row['status'];

                $buttonClass =
                    ($status === 'Setujui')
                    ? 'btn-success'
                    : (
                        ($status === 'Ditolak')
                        ? 'btn-danger'
                        : 'btn-warning'
                    );

                echo '
                <tr>

                    <td>' . htmlspecialchars($row['id_cuti']) . '</td>

                    <td>' . htmlspecialchars($row['NIP']) . '</td>

                    <td>' . htmlspecialchars($row['nama']) . '</td>

                    <td>' . htmlspecialchars($row['tanggal_pengajuan']) . '</td>

                    <td>' . htmlspecialchars($row['tanggal_awal']) . '</td>

                    <td>' . htmlspecialchars($row['tanggal_akhir']) . '</td>

                    <td>' . htmlspecialchars($row['jenis_cuti']) . '</td>

                    <td>

                        <button class="btn ' . $buttonClass . ' btn-sm">

                            ' . ucfirst($status) . '

                        </button>

                    </td>

                </tr>
                ';
            }

        } else {

            echo '
            <tr>

                <td colspan="8"
                    class="text-center">

                    Tidak ada permohonan cuti

                </td>

            </tr>
            ';
        }

        echo '
                </tbody>

            </table>

        </div>
        ';
    }

    // ======================
    // MODAL TAMBAH CUTI
    // ======================
    public static function renderAddLeaveRequestModal($nip)
    {
        echo '
        <div class="modal fade"
             id="tambahDataModal"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Tambah Permohonan Cuti

                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="staff_permohonan_cuti.php"
                          method="POST">

                        <div class="modal-body">

                            <!-- NIP -->
                            <div class="mb-3">

                                <label class="form-label">
                                    NIP
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="NIP"
                                       value="' . htmlspecialchars($nip) . '"
                                       readonly>

                            </div>

                            <!-- NAMA -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="nama"
                                       required>

                            </div>

                            <!-- TANGGAL MULAI -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Tanggal Mulai Cuti
                                </label>

                                <input type="date"
                                       class="form-control"
                                       name="tanggal_mulai"
                                       required>

                            </div>

                            <!-- TANGGAL SELESAI -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Tanggal Selesai Cuti
                                </label>

                                <input type="date"
                                       class="form-control"
                                       name="tanggal_selesai"
                                       required>

                            </div>

                            <!-- ALASAN -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Alasan Cuti
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="jenis_cuti"
                                       required>

                            </div>

                            <!-- STATUS -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select class="form-select"
                                        name="status"
                                        required>

                                    <option value="pending">

                                        Pending

                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                Close

                            </button>

                            <button type="submit"
                                    class="btn btn-primary">

                                Tambah

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
        ';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Permohonan Cuti</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
      rel="stylesheet">

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.content {
    padding: 10px;
}

.table th,
.table td {
    vertical-align: middle;
}

.btn-primary {
    border-radius: 25px;
    font-size: 14px;
}

.modal-content {
    border-radius: 10px;
}

.navbar {
    padding: 3px 20px;
}

</style>

</head>

<body>

<div class="content-wrapper"
     style="min-height:100vh;background-color:#e9ecef;">

    <div class="container d-flex justify-content-center align-items-center">

        <?php MainContent::renderTable($result); ?>

        <?php MainContent::renderAddLeaveRequestModal($nip); ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
