<?php
session_start(); // Memastikan sesi dimulai
require_once "config.php";
require_once "template/header.php";  // Include header (with navbar)
require_once "template/sidebar.php";
require_once "template/navbar.php";  // Navbar with additional styling

$nip = isset($_SESSION['NIP']) ? $_SESSION['NIP'] : '';

// Fetching leave requests from the database
$sql = "SELECT * FROM admin_pengajuan_cuti";
$result = mysqli_query($koneksi, $sql);

class MainContent {
    // Render the table of leave requests
    public static function renderTable($result) {
        echo '<div class="content mt-5">'; // Increased top margin for better spacing
        echo '    <h4 class="mb-4">Data Permohonan Cuti</h4>'; // Added margin bottom for title
        echo '    <div class="d-flex justify-content-between align-items-center mb-4">'; // Adjusted margin
        echo '        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDataModal">'; 
        echo '            <i class="bi bi-person-plus"></i> Tambah';
        echo '        </button>';
        echo '    </div>';
        echo '    <table class="table table-striped table-bordered table-hover text-center">'; // Added table-hover for hover effect
        echo '        <thead class="table-light">'; // Light background for the header
        echo '            <tr>';
        echo '                <th>Id Cuti</th>';
        echo '                <th>NIP</th>';
        echo '                <th>Nama</th>';
        echo '                <th>Tanggal Pengajuan</th>';
        echo '                <th>Tanggal Mulai</th>';
        echo '                <th>Tanggal Selesai</th>';
        echo '                <th>Alesan Cuti</th>';
        echo '                <th>Status</th>';
        echo '            </tr>';
        echo '        </thead>';
        echo '        <tbody>';
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '    <td>' . $row['id_cuti'] . '</td>';
                echo '    <td>' . $row['NIP'] . '</td>';
                echo '    <td>' . $row['nama'] . '</td>';
                echo '    <td>' . $row['tanggal_pengajuan'] . '</td>';
                echo '    <td>' . $row['tanggal_awal'] . '</td>';
                echo '    <td>' . $row['tanggal_akhir'] . '</td>';
                echo '    <td>' . $row['jenis_cuti'] . '</td>';
                
                // Set button color based on status
                $status = $row['status'];
                $buttonClass = ($status === 'Setujui') ? 'btn-success' : (($status === 'Ditolak') ? 'btn-danger' : 'btn-warning');
                echo '    <td><button class="btn ' . $buttonClass . ' btn-sm">' . ucfirst($status) . '</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8" class="text-center">Tidak ada permohonan cuti</td></tr>';
        }
        echo '        </tbody>';
        echo '    </table>';
        echo '</div>';
    }

    // Render modal to add leave request
    public static function renderAddLeaveRequestModal($nip) {
        echo '<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">';
        echo '    <div class="modal-dialog">';
        echo '        <div class="modal-content">';
        echo '            <div class="modal-header">';
        echo '                <h5 class="modal-title" id="tambahDataLabel">Tambah Permohonan Cuti</h5>';
        echo '                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '            </div>';
        echo '            <form action="staff_permohonan_cuti.php" method="POST">';
        echo '                <div class="modal-body">';
        echo '                    <div class="mb-3">';
        echo '                        <label for="NIP" class="form-label">NIP</label>';
        echo '                        <input type="text" class="form-control" name="NIP" value="' . htmlspecialchars($nip) . '" readonly>';
        echo '                    </div>';
        echo '                    <div class="mb-3">';
        echo '                        <label for="nama" class="form-label">Nama</label>';
        echo '                        <input type="text" class="form-control" name="nama" required>';
        echo '                    </div>';
        echo '                    <div class="mb-3">';
        echo '                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Cuti</label>';
        echo '                        <input type="date" class="form-control" name="tanggal_mulai" required>';
        echo '                    </div>';
        echo '                    <div class="mb-3">';
        echo '                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai Cuti</label>';
        echo '                        <input type="date" class="form-control" name="tanggal_selesai" required>';
        echo '                    </div>';
        echo '                    <div class="mb-3">';
        echo '                        <label for="jenis_cuti" class="form-label">Alesan Cuti</label>';
        echo '                        <input type="text" class="form-control" name="jenis_cuti" required>';
        echo '                    </div>';
        echo '                    <div class="mb-3">';
        echo '                        <label for="status" class="form-label">Status</label>';
        echo '                        <select class="form-select" name="status" required>';
        echo '                            <option value="pending">Pending</option>';
        echo '                        </select>';
        echo '                    </div>';
        echo '                </div>';
        echo '                <div class="modal-footer">';
        echo '                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
        echo '                    <button type="submit" class="btn btn-primary">Tambah</button>';
        echo '                </div>';
        echo '            </form>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Cuti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin-top: 0;
            background-color: #f8f9fa;
        }
        .content {
            padding: 10px;
        }

        .table th, .table td {
            vertical-align: middle;
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
            padding: 3px 20px;
     
        }

    </style>
</head>
<body>
    <!-- Navbar is already included in the header.php -->
    <div class="content-wrapper" style="min-height: 100vh; background-color: #e9ecef;">
        <div class="container d-flex justify-content-center align-items-center" style="height: auto;">
            <?php MainContent::renderTable($result); ?>
            <?php MainContent::renderAddLeaveRequestModal($nip); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
