<?php
// Pastikan session dimulai pertama kali sebelum ada output
session_start();

// Aktifkan output buffering untuk mencegah masalah header
ob_start();

// Masukkan file konfigurasi dan template
require_once "config.php";
require_once "template/header.php";
require_once "template/sidebar.php";
require_once "template/navbar.php";

// Pastikan user sudah login
if (!isset($_SESSION['NIP'])) {
    // Redirect ke halaman login jika user belum login
    header("Location: form_login.php");
    exit();
}

// Ambil NIP dari session
$nip = $_SESSION['NIP'];

// Ambil data user dari database
$query = "SELECT * FROM user WHERE NIP = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $nip); // Bind parameter untuk menghindari SQL injection
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); // Ambil hasil query

// Jika data user tidak ditemukan, redirect ke dashboard
if (!$row) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location.href='dashboard.php';</script>";
    exit();
}

$stmt->close();
?>
<!-- Content Wrapper -->
<div class="content-wrapper" style="min-height: 100vh; padding-top: 80px; background-color: #e9ecef;">
    <div class="container d-flex justify-content-center align-items-center" style="height: auto;">
        <!-- Card Profil -->
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-lg">
                    <div class="card-header text-center bg-dark text-white">
                        <h3 class="fw-bold mb-0">Detail Profil</h3>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="update_profile.php">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">NIP:</th>
                                    <td>
                                        <input type="text" name="NIP" class="form-control bg-light border-0 rounded-pill" 
                                               value="<?php echo htmlspecialchars($row['NIP']); ?>" required readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama:</th>
                                    <td>
                                        <input type="text" name="nama_user" class="form-control bg-light border-0 rounded-pill" 
                                               value="<?php echo htmlspecialchars($row['nama_user']); ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir:</th>
                                    <td>
                                        <input type="date" name="tgl_lahir" class="form-control bg-light border-0 rounded-pill" 
                                               value="<?php echo htmlspecialchars($row['tgl_lahir']); ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th>No Telepon:</th>
                                    <td>
                                        <input type="text" name="no_telp" class="form-control bg-light border-0 rounded-pill" 
                                               value="<?php echo htmlspecialchars($row['no_telp']); ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alamat:</th>
                                    <td>
                                        <input type="text" name="alamat" class="form-control bg-light border-0 rounded-pill" 
                                               value="<?php echo htmlspecialchars($row['alamat']); ?>" required>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tombol Simpan -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-success rounded-pill px-4 py-2" 
                                        data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit me-2"></i>Edit Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="update_profile.php">
                    <div class="mb-3">
                        <label for="NIP" class="form-label">NIP</label>
                        <input type="text" id="NIP" name="NIP" class="form-control" 
                               value="<?php echo htmlspecialchars($row['NIP']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama</label>
                        <input type="text" id="nama_user" name="nama_user" class="form-control" 
                               value="<?php echo htmlspecialchars($row['nama_user']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" 
                               value="<?php echo htmlspecialchars($row['tgl_lahir']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" class="form-control" 
                               value="<?php echo htmlspecialchars($row['no_telp']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" 
                               value="<?php echo htmlspecialchars($row['alamat']); ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Output footer
require_once "template/footer.php";

// Bersihkan output buffer
ob_end_flush();
?>
