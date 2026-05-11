<?php
session_start();

require_once "config.php";

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";
require_once "template_admin/navbar.php";

// ======================
// AMBIL DATA STAFF
// ======================
try {

    $stmt = $koneksi->prepare("
        SELECT *
        FROM user
        ORDER BY NIP ASC
    ");

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ======================
    // AMBIL NIP TERAKHIR
    // ======================
    $stmtLastNip = $koneksi->prepare("
        SELECT MAX(NIP) AS last_nip
        FROM user
    ");

    $stmtLastNip->execute();

    $rowLastNip = $stmtLastNip->fetch(PDO::FETCH_ASSOC);

    // ======================
    // GENERATE NIP BARU
    // ======================
    $last_nip_number =
        $rowLastNip['last_nip']
        ? (int) substr($rowLastNip['last_nip'], 1)
        : 0;

    $new_nip_number = $last_nip_number + 1;

    $new_nip =
        "K" .
        str_pad($new_nip_number, 4, "0", STR_PAD_LEFT);

} catch (PDOException $e) {

    die("Terjadi kesalahan database: " . $e->getMessage());
}

// ======================
// CLASS MAIN CONTENT
// ======================
class MainContent
{
    // ======================
    // TABLE STAFF
    // ======================
    public static function renderTable($result)
    {
        echo '
        <div class="content container mt-5">

            <h4 class="text-center">
                Data Staff
            </h4>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahDataModal">

                    <i class="bi bi-person-plus"></i>

                    Tambah Staff

                </button>

            </div>

            <div class="table-container"
                 style="max-height:400px;overflow-y:auto;">

                <table class="table table-bordered text-center table-striped">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Hak</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>
        ';

        // ======================
        // LOOP DATA
        // ======================
        if ($result) {

            foreach ($result as $row) {

                echo '
                <tr>

                    <td>' . htmlspecialchars($row['NIP']) . '</td>

                    <td>' . htmlspecialchars($row['nama_user']) . '</td>

                    <td>' . htmlspecialchars($row['tgl_lahir']) . '</td>

                    <td>' . htmlspecialchars($row['alamat']) . '</td>

                    <td>' . htmlspecialchars($row['no_telp']) . '</td>

                    <td>' . htmlspecialchars($row['hak']) . '</td>

                    <td>

                        <!-- EDIT -->
                        <button class="btn btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editDataModal' . $row['NIP'] . '">

                            <i class="fas fa-edit"></i>

                        </button>

                        <!-- DELETE -->
                        <button class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteDataModal' . $row['NIP'] . '">

                            <i class="fas fa-trash"></i>

                        </button>

                    </td>

                </tr>
                ';

                // ======================
                // MODAL EDIT
                // ======================
                self::renderEditModal($row);

                // ======================
                // MODAL DELETE
                // ======================
                self::renderDeleteModal($row);
            }

        } else {

            echo '
            <tr>

                <td colspan="7"
                    class="text-center">

                    Tidak ada data staff

                </td>

            </tr>
            ';
        }

        echo '
                    </tbody>

                </table>

            </div>

        </div>
        ';
    }

    // ======================
    // MODAL TAMBAH
    // ======================
    public static function renderAddStaffModal($new_nip)
    {
        echo '
        <div class="modal fade"
             id="tambahDataModal"
             tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            Tambah Data Staff

                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="tambah_staff.php"
                          method="POST">

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    NIP
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="NIP"
                                       value="' . htmlspecialchars($new_nip) . '"
                                       readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="nama_user"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                       class="form-control"
                                       name="tgl_lahir"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="alamat"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    No Telepon
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="no_telp"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Hak
                                </label>

                                <select class="form-control"
                                        name="hak"
                                        required>

                                    <option value="admin">
                                        Admin
                                    </option>

                                    <option value="karyawan">
                                        Karyawan
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

    // ======================
    // MODAL EDIT
    // ======================
    public static function renderEditModal($row)
    {
        echo '
        <div class="modal fade"
             id="editDataModal' . $row['NIP'] . '"
             tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header bg-warning text-white">

                        <h5 class="modal-title">

                            Edit Data Staff

                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="edit_staff.php"
                          method="POST">

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    NIP
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="NIP"
                                       value="' . htmlspecialchars($row['NIP']) . '"
                                       readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="nama_user"
                                       value="' . htmlspecialchars($row['nama_user']) . '"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                       class="form-control"
                                       name="tgl_lahir"
                                       value="' . htmlspecialchars($row['tgl_lahir']) . '"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="alamat"
                                       value="' . htmlspecialchars($row['alamat']) . '"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    No Telepon
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="no_telp"
                                       value="' . htmlspecialchars($row['no_telp']) . '"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Hak
                                </label>

                                <select class="form-control"
                                        name="hak"
                                        required>

                                    <option value="admin"
                                        ' . ($row['hak'] == 'admin' ? 'selected' : '') . '>

                                        Admin

                                    </option>

                                    <option value="karyawan"
                                        ' . ($row['hak'] == 'karyawan' ? 'selected' : '') . '>

                                        Karyawan

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
                                    class="btn btn-warning">

                                Update

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
        ';
    }

    // ======================
    // MODAL DELETE
    // ======================
    public static function renderDeleteModal($row)
    {
        echo '
        <div class="modal fade"
             id="deleteDataModal' . $row['NIP'] . '"
             tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">

                            Delete Data Staff

                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        Apakah Anda yakin ingin menghapus data staff ini?

                    </div>

                    <div class="modal-footer">

                        <form action="hapus_staff.php"
                              method="POST">

                            <input type="hidden"
                                   name="NIP"
                                   value="' . htmlspecialchars($row['NIP']) . '">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                Close

                            </button>

                            <button type="submit"
                                    class="btn btn-danger">

                                Hapus

                            </button>

                        </form>

                    </div>

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

<title>Data Staff</title>

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

.table th {
    background-color: #343a40;
    color: white;
}

.table td {
    background-color: #ffffff;
    color: #212529;
}

.modal-content {
    border-radius: 10px;
}

</style>

</head>

<body>

<div class="content-wrapper"
     style="min-height:100vh;background-color:#e9ecef;">

    <div class="container d-flex justify-content-center">

        <div class="col-md-12">

            <?php MainContent::renderTable($result); ?>

            <?php MainContent::renderAddStaffModal($new_nip); ?>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
