<?php
session_start();

ob_start();

require_once 'config.php';

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// ======================
// KONEKSI DATABASE
// ======================
$conn = $koneksi;

// ======================
// UPDATE STATUS CUTI
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['status'], $_POST['id_cuti'])
) {

    $status = $_POST['status'];

    $id_cuti = $_POST['id_cuti'];

    try {

        $stmt = $conn->prepare("
            UPDATE admin_pengajuan_cuti
            SET status = :status
            WHERE id_cuti = :id
        ");

        $result = $stmt->execute([

            ':status' => $status,

            ':id' => $id_cuti
        ]);

        if ($result) {

            $_SESSION['notif'] =
                "Status pengajuan cuti berhasil diperbarui menjadi '$status'.";

            header("Location: admin_pengajuan_cuti.php");

            exit;
        }

    } catch (Exception $e) {

        echo "
        <p class='alert alert-danger'>

            Terjadi kesalahan:
            " . htmlspecialchars($e->getMessage()) . "

        </p>
        ";
    }
}

// ======================
// AMBIL DATA CUTI
// ======================
$stmt = $conn->prepare("
    SELECT *
    FROM admin_pengajuan_cuti
    ORDER BY id_cuti DESC
");

$stmt->execute();

$result =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Daftar Pengajuan Cuti Karyawan
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<style>

body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
}

.content {
    padding: 25px;
    padding-top: 90px;
}

/* Judul */
h2 {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 25px;
}

/* Card tabel */
.table-responsive {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Header tabel */
.table th {
    background-color: #0d6efd !important;
    color: white;
    text-align: center;
    vertical-align: middle;
}

/* Isi tabel */
.table td {
    background-color: white;
    color: #333;
    vertical-align: middle;
    text-align: center;
}

/* Hover tabel */
.table-hover tbody tr:hover {
    background-color: #f1f7ff;
    transition: 0.2s;
}

/* Tombol */
.btn {
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    padding: 6px 14px;
}

/* Tombol detail */
.btn-info {
    color: white;
}

/* Alert */
.alert {
    border-radius: 12px;
}

/* Badge */
.badge {
    padding: 8px 12px;
    font-size: 13px;
    border-radius: 8px;
}

/* Modal */
.modal-content {
    border-radius: 15px;
}

</style>

</head>

<body>

<div class="content">

    <h2 class="text-center">

        Daftar Pengajuan Cuti Karyawan

    </h2>

    <!-- NOTIFIKASI -->
    <?php if (isset($_SESSION['notif'])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= htmlspecialchars($_SESSION['notif']); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

        <?php unset($_SESSION['notif']); ?>

    <?php endif; ?>

    <!-- TABEL -->
    <div class="table-responsive">

        <table class="table table-bordered table-striped table-hover">

            <thead>

                <tr>

                    <th>ID Cuti</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Detail</th>

                </tr>

            </thead>

            <tbody>

            <?php if ($result): ?>

                <?php foreach ($result as $row): ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($row['id_cuti']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($row['nama']); ?>

                        </td>

                        <td>

                            <?php if ($row['status'] == 'Disetujui'): ?>

                                <span class="badge bg-success">

                                    Disetujui

                                </span>

                            <?php elseif ($row['status'] == 'Ditolak'): ?>

                                <span class="badge bg-danger">

                                    Ditolak

                                </span>

                            <?php else: ?>

                                <form method="POST">

                                    <input type="hidden"
                                           name="id_cuti"
                                           value="<?= htmlspecialchars($row['id_cuti']); ?>">

                                    <button type="submit"
                                            name="status"
                                            value="Disetujui"
                                            class="btn btn-success btn-sm me-2">

                                        Setujui

                                    </button>

                                    <button type="submit"
                                            name="status"
                                            value="Ditolak"
                                            class="btn btn-danger btn-sm">

                                        Tolak

                                    </button>

                                </form>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="admin_pengajuan_cuti.php?id_cuti=<?= htmlspecialchars($row['id_cuti']); ?>"
                               class="btn btn-info btn-sm">

                                Detail

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="4"
                        class="text-center">

                        Tidak ada data pengajuan cuti.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
ob_end_flush();
?>
