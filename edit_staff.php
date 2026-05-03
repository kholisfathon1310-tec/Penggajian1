<?php
session_start();
require_once 'config.php';

// Kelas untuk operasi staff
class Staff
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function getStaffByNIP($NIP)
    {
        $sql = "SELECT * FROM user WHERE NIP = ?";
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("s", $NIP);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStaff($NIP, $nama_user, $tgl_lahir, $alamat, $no_telp, $hak)
    {
        $sql = "UPDATE user SET nama_user = ?, tgl_lahir = ?, alamat = ?, no_telp = ?, hak = ? WHERE NIP = ?";
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("ssssss", $nama_user, $tgl_lahir, $alamat, $no_telp, $hak, $NIP);
        return $stmt->execute();
    }
}

// Jika ada data yang dikirimkan melalui form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NIP = $_POST['NIP'];
    $nama_user = $_POST['nama_user'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $hak = $_POST['hak'];

    $staff = new Staff($koneksi);
    if ($staff->updateStaff($NIP, $nama_user, $tgl_lahir, $alamat, $no_telp, $hak)) {
        header("Location: staff.php"); // Redirect ke halaman daftar staff setelah berhasil edit
    } else {
        echo "Gagal mengupdate data staff.";
    }
} else {
    // Jika tidak ada data POST, ambil data staff berdasarkan NIP dari URL
    if (isset($_GET['NIP'])) {
        $NIP = $_GET['NIP'];
        $staff = new Staff($koneksi);
        $staffData = $staff->getStaffByNIP($NIP);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h4 class="text-center">Edit Data Staff</h4>

    <?php if (isset($staffData)): ?>
    <form action="edit_staff.php" method="POST">
        <div class="mb-3">
            <label for="NIP" class="form-label">NIP</label>
            <input type="text" class="form-control" name="NIP" value="<?php echo $staffData['NIP']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_user" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama_user" value="<?php echo $staffData['nama_user']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tgl_lahir" value="<?php echo $staffData['tgl_lahir']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" name="alamat" value="<?php echo $staffData['alamat']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="no_telp" class="form-label">No Telepon</label>
            <input type="text" class="form-control" name="no_telp" value="<?php echo $staffData['no_telp']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="hak" class="form-label">Hak</label>
            <select class="form-control" name="hak" required>
                <option value="admin" <?php if ($staffData['hak'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="karyawan" <?php if ($staffData['hak'] == 'karyawan') echo 'selected'; ?>>Karyawan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <?php else: ?>
        <p>Staff dengan NIP tersebut tidak ditemukan.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
