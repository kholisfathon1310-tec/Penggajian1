<?php
// Pastikan session dimulai pertama kali sebelum ada output
session_start();

// Konfigurasi Database
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName = "penggajian";
    protected $connection;

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);
        if ($this->connection->connect_error) {
            die("Koneksi gagal: " . $this->connection->connect_error);
        }
    }
}

// Class User untuk operasi data user
class User extends Database {
    private $nip;
    private $nama;
    private $tglLahir;
    private $noTelp;
    private $alamat;

    // Enkapsulasi: Setter dan Getter untuk properti
    public function setNIP($nip) {
        $this->nip = $nip;
    }

    public function getNIP() {
        return $this->nip;
    }

    public function setNama($nama) {
        $this->nama = $nama;
    }

    public function getNama() {
        return $this->nama;
    }

    public function setTglLahir($tglLahir) {
        $this->tglLahir = $tglLahir;
    }

    public function getTglLahir() {
        return $this->tglLahir;
    }

    public function setNoTelp($noTelp) {
        $this->noTelp = $noTelp;
    }

    public function getNoTelp() {
        return $this->noTelp;
    }

    public function setAlamat($alamat) {
        $this->alamat = $alamat;
    }

    public function getAlamat() {
        return $this->alamat;
    }

    // Ambil data user berdasarkan NIP
    public function getUserData($nip) {
        $query = "SELECT * FROM user WHERE NIP = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $nip);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update data user
    public function updateProfile() {
        $query = "UPDATE user SET nama_user = ?, tgl_lahir = ?, no_telp = ?, alamat = ? WHERE NIP = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssss", $this->nama, $this->tglLahir, $this->noTelp, $this->alamat, $this->nip);
        return $stmt->execute();
    }
}

// Pewarisan: Class Admin memperluas User
class Admin extends User {
    public function deleteUser($nip) {
        $query = "DELETE FROM user WHERE NIP = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $nip);
        return $stmt->execute();
    }
}

// Polimorfisme: Class Validator untuk validasi input
class Validator {
    public static function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public static function validateDate($date) {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

// Cek apakah user sudah login
if (!isset($_SESSION['NIP'])) {
    header("Location: form_login.php");
    exit();
}

// Ambil NIP dari session
$nip = $_SESSION['NIP'];

// Buat objek User
$user = new User();
$userData = $user->getUserData($nip);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses update profil
    $user->setNIP($nip);
    $user->setNama(Validator::sanitizeInput($_POST['nama_user']));
    $user->setTglLahir(Validator::sanitizeInput($_POST['tgl_lahir']));
    $user->setNoTelp(Validator::sanitizeInput($_POST['no_telp']));
    $user->setAlamat(Validator::sanitizeInput($_POST['alamat']));

    if ($user->updateProfile()) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}
?>

<!-- Tampilan Halaman -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-dark text-white">Detail Profil</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="NIP" class="form-label">NIP</label>
                    <input type="text" id="NIP" name="NIP" class="form-control" 
                           value="<?php echo htmlspecialchars($userData['NIP']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama_user" class="form-label">Nama</label>
                    <input type="text" id="nama_user" name="nama_user" class="form-control" 
                           value="<?php echo htmlspecialchars($userData['nama_user']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" 
                           value="<?php echo htmlspecialchars($userData['tgl_lahir']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="no_telp" class="form-label">No Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" class="form-control" 
                           value="<?php echo htmlspecialchars($userData['no_telp']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" 
                           value="<?php echo htmlspecialchars($userData['alamat']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
