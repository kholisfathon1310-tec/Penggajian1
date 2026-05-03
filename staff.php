<?php
session_start(); // Memastikan sesi dimulai
require_once "config.php";
require_once "template_admin/header.php";  // Include header (with navbar)
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";  // Navbar with additional styling

$sql = "SELECT * FROM user";
$result = mysqli_query($koneksi, $sql);

// Mendapatkan NIP terakhir
$sql_last_nip = "SELECT MAX(NIP) AS last_nip FROM user";
$result_last_nip = mysqli_query($koneksi, $sql_last_nip);
$row_last_nip = mysqli_fetch_assoc($result_last_nip);

// Ambil angka dari NIP terakhir atau set default 0 jika belum ada
$last_nip_number = $row_last_nip['last_nip'] ? (int)substr($row_last_nip['last_nip'], 1) : 0;

// Hitung NIP baru
$new_nip_number = $last_nip_number + 1;
$new_nip = "K" . str_pad($new_nip_number, 4, "0", STR_PAD_LEFT);

class MainContent {
    public static function renderTable($result)
    {
        echo '<div class="content container mt-5">';
        echo '<h4 class="text-center">Data Staff</h4>';
        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
        echo '<button class="btn btn-tambah btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">';
        echo '<i class="bi bi-person-plus"></i> Tambah Staff';
        echo '</button>';
        echo '</div>';

        // Wrapper for scrollable table
        echo '<div class="table-container" style="max-height: 400px; overflow-y: auto;">';
        echo '<table class="table table-bordered text-center table-striped">';
        echo '<thead class="table-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Nama</th>';
        echo '<th>Tanggal Lahir</th>';
        echo '<th>Alamat</th>';
        echo '<th>No Telepon</th>';
        echo '<th>Hak</th>';
        echo '<th>Aksi</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['NIP']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_user']) . '</td>';
                echo '<td>' . htmlspecialchars($row['tgl_lahir']) . '</td>';
                echo '<td>' . htmlspecialchars($row['alamat']) . '</td>';
                echo '<td>' . htmlspecialchars($row['no_telp']) . '</td>';
                echo '<td>' . htmlspecialchars($row['hak']) . '</td>';
                echo '<td>';
                // Edit Button
                echo '<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDataModal' . $row['NIP'] . '">';
                echo '<i class="fas fa-edit"></i>';
                echo '</button>';
                // Delete Button
                echo '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDataModal' . $row['NIP'] . '">';
                echo '<i class="fas fa-trash"></i>';
                echo '</button>';
                echo '</td>';
                echo '</tr>';

                // Modal Edit
                self::renderEditModal($row);
                // Modal Delete
                self::renderDeleteModal($row);
            }
        } else {
            echo '<tr><td colspan="7" class="text-center">Tidak ada data karyawan</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // End of table-container div
        echo '</div>';
    }

    public static function renderAddStaffModal($new_nip)
    {
        echo '<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">';
        echo '<div class="modal-dialog">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header bg-primary text-white">';
        echo '<h5 class="modal-title" id="tambahDataLabel">Tambah Data Staff</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<form action="tambah_staff.php" method="POST">';
        echo '<div class="modal-body">';
        echo '<div class="mb-3">';
        echo '<label for="NIP" class="form-label">NIP</label>';
        echo '<input type="text" class="form-control" name="NIP" value="' . htmlspecialchars($new_nip) . '" readonly>';
        echo '</div>';
        echo '<div class="mb-3"><label for="nama_user" class="form-label">Nama</label><input type="text" class="form-control" name="nama_user" required></div>';
        echo '<div class="mb-3"><label for="tgl_lahir" class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tgl_lahir" required></div>';
        echo '<div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" name="alamat" required></div>';
        echo '<div class="mb-3"><label for="no_telp" class="form-label">No Telepon</label><input type="text" class="form-control" name="no_telp" required></div>';
        echo '<div class="mb-3"><label for="hak" class="form-label">Hak</label><select class="form-control" name="hak" required>';
        echo '<option value="admin">Admin</option>';
        echo '<option value="karyawan">Karyawan</option>';
        echo '</select></div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
        echo '<button type="submit" class="btn btn-primary">Tambah</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Modal Edit
    public static function renderEditModal($row)
    {
        echo '<div class="modal fade" id="editDataModal' . $row['NIP'] . '" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">';
        echo '<div class="modal-dialog">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header bg-warning text-white">';
        echo '<h5 class="modal-title" id="editDataLabel">Edit Data Staff</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<form action="edit_staff.php" method="POST">';
        echo '<div class="modal-body">';
        echo '<div class="mb-3"><label for="NIP" class="form-label">NIP</label><input type="text" class="form-control" name="NIP" value="' . $row['NIP'] . '" readonly></div>';
        echo '<div class="mb-3"><label for="nama_user" class="form-label">Nama</label><input type="text" class="form-control" name="nama_user" value="' . $row['nama_user'] . '" required></div>';
        echo '<div class="mb-3"><label for="tgl_lahir" class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tgl_lahir" value="' . $row['tgl_lahir'] . '" required></div>';
        echo '<div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" name="alamat" value="' . $row['alamat'] . '" required></div>';
        echo '<div class="mb-3"><label for="no_telp" class="form-label">No Telepon</label><input type="text" class="form-control" name="no_telp" value="' . $row['no_telp'] . '" required></div>';
        echo '<div class="mb-3"><label for="hak" class="form-label">Hak</label><select class="form-control" name="hak" required>';
        echo '<option value="admin"' . ($row['hak'] == 'admin' ? ' selected' : '') . '>Admin</option>';
        echo '<option value="karyawan"' . ($row['hak'] == 'karyawan' ? ' selected' : '') . '>Karyawan</option>';
        echo '</select></div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
        echo '<button type="submit" class="btn btn-warning">Update</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Modal Delete
public static function renderDeleteModal($row)
{
    echo '<div class="modal fade" id="deleteDataModal' . $row['NIP'] . '" tabindex="-1" aria-labelledby="deleteDataLabel" aria-hidden="true">';
    echo '<div class="modal-dialog">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header bg-danger text-white">';
    echo '<h5 class="modal-title" id="deleteDataLabel">Delete Data Staff</h5>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo 'Apakah Anda yakin ingin menghapus data staff ini?';
    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<form action="hapus_staff.php" method="POST">';
    echo '<input type="hidden" name="NIP" value="' . $row['NIP'] . '">';
    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
    echo '<button type="submit" class="btn btn-danger">Hapus</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

}
?>

<!-- Your HTML Content -->


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
            padding: 3px 20px;
     
        }

    </style>
</head>
<body>
    <div class="content-wrapper" style="min-height: 100vh; background-color: #e9ecef;">
        <div class="container d-flex justify-content-center"> <!-- Center content horizontally -->
            <div class="col-md-100"> <!-- Adjust column width to make it wider -->
                <?php MainContent::renderTable($result); ?>
                <?php MainContent::renderAddStaffModal($new_nip); ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



    