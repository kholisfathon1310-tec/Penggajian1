<?php
session_start();

require_once "config.php";

// ======================
// CLASS USER
// ======================
class User
{
    private $koneksi;

    private $nip;
    private $nama;
    private $tglLahir;
    private $noTelp;
    private $alamat;

    // ======================
    // CONSTRUCTOR
    // ======================
    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // ======================
    // SETTER
    // ======================
    public function setNIP($nip)
    {
        $this->nip = $nip;
    }

    public function setNama($nama)
    {
        $this->nama = $nama;
    }

    public function setTglLahir($tglLahir)
    {
        $this->tglLahir = $tglLahir;
    }

    public function setNoTelp($noTelp)
    {
        $this->noTelp = $noTelp;
    }

    public function setAlamat($alamat)
    {
        $this->alamat = $alamat;
    }

    // ======================
    // GET DATA USER
    // ======================
    public function getUserData($nip)
    {
        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM user
            WHERE NIP = :nip
        ");

        $stmt->execute([
            ':nip' => $nip
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================
    // UPDATE PROFILE
    // ======================
    public function updateProfile()
    {
        $stmt = $this->koneksi->prepare("
            UPDATE user
            SET
                nama_user = :nama_user,
                tgl_lahir = :tgl_lahir,
                no_telp = :no_telp,
                alamat = :alamat
            WHERE NIP = :nip
        ");

        return $stmt->execute([

            ':nama_user' => $this->nama,
            ':tgl_lahir' => $this->tglLahir,
            ':no_telp' => $this->noTelp,
            ':alamat' => $this->alamat,
            ':nip' => $this->nip
        ]);
    }
}

// ======================
// CLASS VALIDATOR
// ======================
class Validator
{
    public static function sanitizeInput($data)
    {
        return htmlspecialchars(
            stripslashes(trim($data))
        );
    }

    public static function validateDate($date)
    {
        $format = 'Y-m-d';

        $d = DateTime::createFromFormat(
            $format,
            $date
        );

        return $d && $d->format($format) === $date;
    }
}

// ======================
// CEK LOGIN
// ======================
if (!isset($_SESSION['NIP'])) {

    header("Location: form_login.php");

    exit();
}

// ======================
// AMBIL NIP SESSION
// ======================
$nip = $_SESSION['NIP'];

// ======================
// OBJECT USER
// ======================
$user = new User($koneksi);

// ======================
// AMBIL DATA USER
// ======================
$userData = $user->getUserData($nip);

// ======================
// PROSES UPDATE
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user->setNIP($nip);

    $user->setNama(
        Validator::sanitizeInput(
            $_POST['nama_user']
        )
    );

    $user->setTglLahir(
        Validator::sanitizeInput(
            $_POST['tgl_lahir']
        )
    );

    $user->setNoTelp(
        Validator::sanitizeInput(
            $_POST['no_telp']
        )
    );

    $user->setAlamat(
        Validator::sanitizeInput(
            $_POST['alamat']
        )
    );

    // ======================
    // UPDATE DATA
    // ======================
    if ($user->updateProfile()) {

        $_SESSION['message'] =
            "Profil berhasil diperbarui!";

        $_SESSION['message_type'] =
            "success";

        header("Location: profile.php");

        exit();

    } else {

        $_SESSION['message'] =
            "Gagal memperbarui profil!";

        $_SESSION['message_type'] =
            "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Detail Profil</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

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

            Detail Profil

        </div>

        <div class="card-body">

            <!-- ALERT -->
            <?php if (isset($_SESSION['message'])): ?>

                <div class="alert alert-<?= $_SESSION['message_type']; ?>">

                    <?= $_SESSION['message']; ?>

                </div>

                <?php
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>

            <?php endif; ?>

            <!-- FORM -->
            <form method="POST" action="">

                <!-- NIP -->
                <div class="mb-3">

                    <label class="form-label">
                        NIP
                    </label>

                    <input type="text"
                           class="form-control"
                           value="<?= htmlspecialchars($userData['NIP']); ?>"
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
                           value="<?= htmlspecialchars($userData['nama_user']); ?>"
                           required>

                </div>

                <!-- TGL -->
                <div class="mb-3">

                    <label class="form-label">
                        Tanggal Lahir
                    </label>

                    <input type="date"
                           name="tgl_lahir"
                           class="form-control"
                           value="<?= htmlspecialchars($userData['tgl_lahir']); ?>"
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
                           value="<?= htmlspecialchars($userData['no_telp']); ?>"
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
                           value="<?= htmlspecialchars($userData['alamat']); ?>"
                           required>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                        class="btn btn-primary">

                    Simpan Perubahan

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>
