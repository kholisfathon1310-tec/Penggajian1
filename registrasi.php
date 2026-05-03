<?php
session_start(); // Pastikan session dimulai

class User {
    // Atribut private (Enkapsulasi)
    private $id_user;
    private $nip;
    private $nama_user;
    private $username;
    private $password;
    private $no_telp;
    private $alamat;
    private $tgl_lahir;
    private $hak = 'karyawan'; // Role default "karyawan"

    // Constructor
    public function __construct($nama_user, $username, $password, $no_telp, $alamat, $tgl_lahir) {
        $this->nama_user = $nama_user;
        $this->username = $username;
        $this->password = $password; // Password langsung diambil dari form
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->tgl_lahir = $tgl_lahir;
    }

    // Getter
    public function getNIP() {
        return $this->nip;
    }

    // Setter
    public function setNIP($nip) {
        $this->nip = $nip;
    }

    // Metode registrasi
    // Metode registrasi
public function registerUser($koneksi) {
    // Ambil NIP terakhir dari database
    $query = "SELECT MAX(CAST(SUBSTRING(nip, 2) AS UNSIGNED)) AS max_nip FROM user";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $max_nip = $row['max_nip'];

    // Jika tidak ada NIP yang ditemukan, set NIP pertama sebagai K0001
    if ($max_nip === NULL) {
        $new_nip = 'K0001';
    } else {
        // Menentukan NIP baru dengan format Kxxxx
        $new_nip = 'K' . str_pad($max_nip + 1, 4, '0', STR_PAD_LEFT);
    }
    $this->setNIP($new_nip);

    // Ambil ID terakhir dari tabel user
    $query = "SELECT MAX(CAST(SUBSTRING(id_user, 2) AS UNSIGNED)) AS max_id FROM user";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    // Buat ID baru
    $new_id = 'U' . str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);

    // Query untuk memasukkan data ke dalam database
    $query_insert = "INSERT INTO user (id_user, nip, nama_user, username, password, no_telp, alamat, tgl_lahir, hak) 
                     VALUES ('$new_id', '$this->nip', '$this->nama_user', '$this->username', '$this->password', '$this->no_telp', '$this->alamat', '$this->tgl_lahir','$this->hak')";
    
    // Eksekusi query
    if (mysqli_query($koneksi, $query_insert)) {
        return true;
    } else {
        return false;
    }
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('img/registrasi.jpeg');
            background-size: cover;
        }
    </style>
</head>
<body class="bg-gradient">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg col-lg-6 my-5 mx-auto">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">
                                    <font color="black"><strong>Daftar Akun!</strong></font>
                                </h1>
                            </div>
                            <?php
                            // Tampilkan notifikasi jika ada
                            if (isset($_SESSION['notif'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['notif'] . '</div>';
                                unset($_SESSION['notif']);
                            }
                            ?>
                            <form method="post" action="" class="user">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="Nama Anda" name="nama_user" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="Username Anda" name="username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" placeholder="Password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="No Telepon" name="no_telp" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" placeholder="Alamat" name="alamat" required>
                                </div>
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-user" placeholder="Tanggal lahir" name="tgl_lahir" required>
                                </div>
                                <button type="submit" class="btn btn-user btn-block" style="background-color:black">
                                    <font color="#ffffff">Daftar</font>
                                </button>
                            </form>
                            <div class="text-center">
                                <a class="small" href="form_login.php">Sudah Punya Akun? Silahkan Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_user = $_POST['nama_user'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $no_telp = $_POST['no_telp'];
        $alamat = $_POST['alamat'];
        $tgl_lahir = $_POST['tgl_lahir'];

        // Koneksi ke database
        $koneksi = mysqli_connect("localhost", "root", "", "penggajian") or die("Koneksi gagal!");

        // Membuat objek User
        $user = new User($nama_user, $username, $password, $no_telp, $alamat, $tgl_lahir);
        $result = $user->registerUser($koneksi);

        // Notifikasi jika berhasil
        if ($result) {
            $_SESSION['notif'] = "Akun berhasil ditambahkan dengan NIP " . $user->getNIP() . ". Silakan login.";
            header("Location: registrasi.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Pendaftaran gagal, silakan coba lagi!</div>";
        }
    }
    ?>
</body>
</html>
