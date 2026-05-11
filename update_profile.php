<?php
session_start();

require_once "config.php";

// ======================
// CLASS STAFF
// ======================
class Staff
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // ======================
    // GET DATA STAFF
    // ======================
    public function getStaffByNIP($NIP)
    {
        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM user
            WHERE NIP = :nip
        ");

        $stmt->execute([
            ':nip' => $NIP
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================
    // UPDATE STAFF
    // ======================
    public function updateStaff(
        $NIP,
        $nama_user,
        $tgl_lahir,
        $alamat,
        $no_telp,
        $hak
    ) {

        $stmt = $this->koneksi->prepare("
            UPDATE user
            SET
                nama_user = :nama_user,
                tgl_lahir = :tgl_lahir,
                alamat = :alamat,
                no_telp = :no_telp,
                hak = :hak
            WHERE NIP = :nip
        ");

        return $stmt->execute([

            ':nama_user' => $nama_user,

            ':tgl_lahir' => $tgl_lahir,

            ':alamat' => $alamat,

            ':no_telp' => $no_telp,

            ':hak' => $hak,

            ':nip' => $NIP
        ]);
    }
}

// ======================
// OBJECT STAFF
// ======================
$staff = new Staff($koneksi);

// ======================
// PROSES UPDATE
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $NIP = $_POST['NIP'];

    $nama_user = $_POST['nama_user'];

    $tgl_lahir = $_POST['tgl_lahir'];

    $alamat = $_POST['alamat'];

    $no_telp = $_POST['no_telp'];

    $hak = $_POST['hak'];

    if (
        $staff->updateStaff(
            $NIP,
            $nama_user,
            $tgl_lahir,
            $alamat,
            $no_telp,
            $hak
        )
    ) {

        $_SESSION['message'] =
            "Data staff berhasil diperbarui.";

        $_SESSION['message_type'] =
            "success";

        header("Location: staff.php");

        exit();

    } else {

        $error =
            "Gagal mengupdate data staff.";
    }

} else {

    // ======================
    // AMBIL DATA STAFF
    // ======================
    if (isset($_GET['NIP'])) {

        $NIP = $_GET['NIP'];

        $staffData =
            $staff->getStaffByNIP($NIP);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Edit Data Staff
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet">

<style>

body {
    background-color: #f8f9fa;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-dark text-white">

            Edit Data Staff

        </div>

        <div class="card-body">

            <?php if (isset($error)): ?>

                <div class="alert alert-danger">

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>

            <?php if (isset($staffData)): ?>

            <form action=""
                  method="POST">

                <!-- NIP -->
                <div class="mb-3">

                    <label class="form-label">

                        NIP

                    </label>

                    <input type="text"
                           class="form-control"
                           name="NIP"
                           value="<?= htmlspecialchars($staffData['NIP']); ?>"
                           readonly>

                </div>

                <!-- NAMA -->
                <div class="mb-3">

                    <label class="form-label">

                        Nama

                    </label>

                    <input type="text"
                           class="form-control"
                           name="nama_user"
                           value="<?= htmlspecialchars($staffData['nama_user']); ?>"
                           required>

                </div>

                <!-- TGL -->
                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Lahir

                    </label>

                    <input type="date"
                           class="form-control"
                           name="tgl_lahir"
                           value="<?= htmlspecialchars($staffData['tgl_lahir']); ?>"
                           required>

                </div>

                <!-- ALAMAT -->
                <div class="mb-3">

                    <label class="form-label">

                        Alamat

                    </label>

                    <input type="text"
                           class="form-control"
                           name="alamat"
                           value="<?= htmlspecialchars($staffData['alamat']); ?>"
                           required>

                </div>

                <!-- TELEPON -->
                <div class="mb-3">

                    <label class="form-label">

                        No Telepon

                    </label>

                    <input type="text"
                           class="form-control"
                           name="no_telp"
                           value="<?= htmlspecialchars($staffData['no_telp']); ?>"
                           required>

                </div>

                <!-- HAK -->
                <div class="mb-3">

                    <label class="form-label">

                        Hak Akses

                    </label>

                    <select class="form-control"
                            name="hak"
                            required>

                        <option value="admin"
                            <?= ($staffData['hak'] == 'admin') ? 'selected' : ''; ?>>

                            Admin

                        </option>

                        <option value="karyawan"
                            <?= ($staffData['hak'] == 'karyawan') ? 'selected' : ''; ?>>

                            Karyawan

                        </option>

                    </select>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                        class="btn btn-primary">

                    Update Data

                </button>

                <a href="staff.php"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </form>

            <?php else: ?>

                <div class="alert alert-warning">

                    Staff tidak ditemukan.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>
