<?php
// ======================
// SESSION
// ======================
session_start();

ob_start();

// ======================
// CONFIG & TEMPLATE
// ======================
require_once "config.php";

require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";

// ======================
// CEK LOGIN
// ======================
if (!isset($_SESSION['NIP'])) {

    header("Location: form_login.php");
    exit();
}

// ======================
// AMBIL NIP LOGIN
// ======================
$nip = $_SESSION['NIP'];

try {

    // ======================
    // QUERY USER
    // ======================
    $stmt = $koneksi->prepare("
        SELECT *
        FROM user
        WHERE NIP = :nip
    ");

    $stmt->execute([
        ':nip' => $nip
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // ======================
    // USER TIDAK ADA
    // ======================
    if (!$row) {

        echo "
        <script>
            alert('Data user tidak ditemukan!');
            window.location.href='dashboard.php';
        </script>
        ";

        exit();
    }

} catch (PDOException $e) {

    die("Terjadi kesalahan database: " . $e->getMessage());
}
?>

<!-- CONTENT -->
<div class="content-wrapper"
     style="min-height: 100vh;
            padding-top: 80px;
            background-color: #e9ecef;">

    <div class="container d-flex justify-content-center align-items-center">

        <div class="row justify-content-center w-100">

            <div class="col-md-8 col-lg-6">

                <!-- CARD -->
                <div class="card border-0 shadow-lg rounded-lg">

                    <div class="card-header text-center bg-dark text-white">

                        <h3 class="fw-bold mb-0">
                            Detail Profil
                        </h3>

                    </div>

                    <div class="card-body p-5">

                        <form method="POST"
                              action="update_profile.php">

                            <table class="table table-borderless">

                                <!-- NIP -->
                                <tr>

                                    <th style="width: 40%;">
                                        NIP:
                                    </th>

                                    <td>

                                        <input type="text"
                                               name="NIP"
                                               class="form-control bg-light border-0 rounded-pill"
                                               value="<?= htmlspecialchars($row['NIP']); ?>"
                                               required
                                               readonly>

                                    </td>

                                </tr>

                                <!-- NAMA -->
                                <tr>

                                    <th>
                                        Nama:
                                    </th>

                                    <td>

                                        <input type="text"
                                               name="nama_user"
                                               class="form-control bg-light border-0 rounded-pill"
                                               value="<?= htmlspecialchars($row['nama_user']); ?>"
                                               required>

                                    </td>

                                </tr>

                                <!-- TANGGAL -->
                                <tr>

                                    <th>
                                        Tanggal Lahir:
                                    </th>

                                    <td>

                                        <input type="date"
                                               name="tgl_lahir"
                                               class="form-control bg-light border-0 rounded-pill"
                                               value="<?= htmlspecialchars($row['tgl_lahir']); ?>"
                                               required>

                                    </td>

                                </tr>

                                <!-- TELEPON -->
                                <tr>

                                    <th>
                                        No Telepon:
                                    </th>

                                    <td>

                                        <input type="text"
                                               name="no_telp"
                                               class="form-control bg-light border-0 rounded-pill"
                                               value="<?= htmlspecialchars($row['no_telp']); ?>"
                                               required>

                                    </td>

                                </tr>

                                <!-- ALAMAT -->
                                <tr>

                                    <th>
                                        Alamat:
                                    </th>

                                    <td>

                                        <input type="text"
                                               name="alamat"
                                               class="form-control bg-light border-0 rounded-pill"
                                               value="<?= htmlspecialchars($row['alamat']); ?>"
                                               required>

                                    </td>

                                </tr>

                            </table>

                            <!-- BUTTON -->
                            <div class="d-flex justify-content-end mt-4">

                                <button type="button"
                                        class="btn btn-success rounded-pill px-4 py-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProfileModal">

                                    <i class="fas fa-edit me-2"></i>

                                    Edit Profil

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ======================
     MODAL EDIT
====================== -->
<div class="modal fade"
     id="editProfileModal"
     tabindex="-1"
     aria-labelledby="editProfileModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-dark text-white">

                <h5 class="modal-title"
                    id="editProfileModalLabel">

                    Edit Profil

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="update_profile.php">

                    <!-- NIP -->
                    <div class="mb-3">

                        <label class="form-label">
                            NIP
                        </label>

                        <input type="text"
                               name="NIP"
                               class="form-control"
                               value="<?= htmlspecialchars($row['NIP']); ?>"
                               readonly>

                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">

                        <label class="form-label">
                            Nama
                        </label>

                        <input type="text"
                               name="nama_user"
                               class="form-control"
                               value="<?= htmlspecialchars($row['nama_user']); ?>"
                               required>

                    </div>

                    <!-- TANGGAL -->
                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal Lahir
                        </label>

                        <input type="date"
                               name="tgl_lahir"
                               class="form-control"
                               value="<?= htmlspecialchars($row['tgl_lahir']); ?>"
                               required>

                    </div>

                    <!-- TELEPON -->
                    <div class="mb-3">

                        <label class="form-label">
                            No Telepon
                        </label>

                        <input type="text"
                               name="no_telp"
                               class="form-control"
                               value="<?= htmlspecialchars($row['no_telp']); ?>"
                               required>

                    </div>

                    <!-- ALAMAT -->
                    <div class="mb-3">

                        <label class="form-label">
                            Alamat
                        </label>

                        <input type="text"
                               name="alamat"
                               class="form-control"
                               value="<?= htmlspecialchars($row['alamat']); ?>"
                               required>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary rounded-pill px-3"
                                data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit"
                                class="btn btn-primary rounded-pill px-4">

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

require_once "template/footer.php";

ob_end_flush();

?>
