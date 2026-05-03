<?php
ob_start();
session_start();

require_once "config.php";
require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

if (isset($_GET['id_cuti'])) {
    $id_cuti = $_GET['id_cuti'];

    // Koneksi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "penggajian";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mengambil detail pengajuan cuti berdasarkan 'id_cuti'
    $stmt = $conn->prepare("SELECT * FROM admin_pengajuan_cuti WHERE id_cuti = ?");
    $stmt->bind_param("s", $id_cuti);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detail Permohonan Cuti</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f8f9fa;
                }

                .content {
                    padding: 20px;
                    margin-top: 90px; /* Spasi agar konten tidak tertutup navbar */
                }

                .table th {
                    background-color: #343a40;
                    color: white;
                }

                .btn-status {
                    width: 100px;
                    border: none;
                    color: white;
                    padding: 5px;
                    text-align: center;
                    border-radius: 8px;
                    cursor: pointer;
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
                <h2 class="text-center fw-bold">Detail Permohonan Cuti Karyawan</h2>

                <?php if (isset($_SESSION['notif'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['notif']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['notif']); ?>
                <?php endif; ?>

                <div class="mb-4">
                    <p><strong>NIP:</strong> <?= htmlspecialchars($data['NIP']) ?></p>
                    <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
                    <p><strong>Posisi:</strong> <?= htmlspecialchars($data['Hak']) ?></p>
                </div>

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
                                <td><?= htmlspecialchars($data['tanggal_awal']) ?></td>
                                <td><?= htmlspecialchars($data['tanggal_akhir']) ?></td>
                                <td><?= htmlspecialchars($data['jenis_cuti']) ?></td>
                                <td><?= htmlspecialchars($data['tanggal_pengajuan']) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <form method="POST" class="me-2">
                                            <button type="submit" name="status" value="Disetujui" class="btn btn-success btn-sm">Setujui</button>
                                        </form>
                                        <form method="POST">
                                            <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php
                // Update status ketika tombol diklik
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
                    $status = $_POST['status'];

                    if (in_array($status, ['Disetujui', 'Ditolak'])) {
                        $stmt = $conn->prepare("UPDATE admin_pengajuan_cuti SET status = ? WHERE id_cuti = ?");
                        $stmt->bind_param("ss", $status, $id_cuti);
                        if ($stmt->execute()) {
                            $_SESSION['notif'] = "Status pengajuan cuti berhasil diperbarui menjadi '$status'.";
                            header("Location: admin_cuti_utama.php");
                            exit();
                        }
                    }
                }
                ?>

            </div>
        </body>
        </html>

        <?php
    } else {
        echo "<div class='alert alert-danger'>Data pengajuan cuti tidak ditemukan untuk ID ini.</div>";
    }

    $conn->close();
} else {
    echo "<div class='alert alert-warning'>ID Cuti tidak valid!</div>";
}

ob_end_flush();
?>
