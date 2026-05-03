<?php
// Mulai sesi dan buffer output
session_start();
ob_start();

// Memuat konfigurasi dan template
require_once "config.php";
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// Query untuk mengambil semua data pengajuan cuti
$sql = "SELECT * FROM admin_pengajuan_cuti";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Cuti Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin-top: 0;
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px; /* Memberikan padding agar tampilan lebih rapi */
            padding-top: 90px; /* Memberikan ruang untuk navbar */
        }

        .table th {
            background-color: #343a40;
            color: white;
        }
        .table td {
            background-color: #ffffff;
            color: #212529;
        }

        .btn-primary {
            border-radius: 25px;
            font-size: 14px;
        }

        .search-box {
            max-width: 250px;
        }

        .modal-content {
            border-radius: 10px;
        }

        /* Clean up navbar styles */
        .navbar {
            padding: 10px 20px;
        }

        /* Responsif untuk tabel */
        .table-responsive {
            overflow-x: auto;
        }

    </style>
</head>
<body>
    <div class="content">
        <h2 class="text-center">Daftar Pengajuan Cuti Karyawan</h2>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['notif'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['notif']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['notif']); ?>
        <?php endif; ?>

        <!-- Tabel Daftar Pengajuan Cuti -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead style="background-color:rgb(60, 100, 139); color: white;">
                    <tr>
                        <th>ID Cuti</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_cuti']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Disetujui'): ?>
                                        <span class="badge bg-success">Disetujui</span>
                                    <?php elseif ($row['status'] == 'Ditolak'): ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php else: ?>
                                        <form method="POST" action="">
                                            <button type="submit" name="status" value="Disetujui" class="btn btn-success btn-sm me-2">Setujui</button>
                                            <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
                                            <input type="hidden" name="id_cuti" value="<?= htmlspecialchars($row['id_cuti']); ?>">
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin_pengajuan_cuti.php?id_cuti=<?= htmlspecialchars($row['id_cuti']); ?>" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pengajuan cuti.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Menangani pembaruan status pengajuan cuti
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'], $_POST['id_cuti'])) {
        $status = $_POST['status'];
        $id_cuti = $_POST['id_cuti'];

        // Update status di database
        $stmt = $koneksi->prepare("UPDATE admin_pengajuan_cuti SET status = ? WHERE id_cuti = ?");
        $stmt->bind_param("ss", $status, $id_cuti);

        if ($stmt->execute()) {
            // Simpan notifikasi berhasil ke sesi
            $_SESSION['notif'] = "Status pengajuan cuti berhasil diperbarui menjadi '$status'.";
            // Redirect setelah pembaruan status untuk menampilkan notifikasi
            header("Location: admin_pengajuan_cuti.php");
            exit();
        } else {
            echo "<p class='alert alert-danger'>Terjadi kesalahan saat memperbarui status.</p>";
        }
        $stmt->close();
    }

    // Tutup koneksi database
    $koneksi->close();
    ob_end_flush();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
