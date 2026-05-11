<?php
session_start();

require_once "config.php";

// ======================
// CLASS USER
// ======================
class User {

    // ======================
    // PRIVATE ATTRIBUTE
    // ======================
    private $id_user;
    private $nip;
    private $nama_user;
    private $username;
    private $password;
    private $no_telp;
    private $alamat;
    private $tgl_lahir;
    private $hak = 'karyawan';

    // ======================
    // CONSTRUCTOR
    // ======================
    public function __construct(
        $nama_user,
        $username,
        $password,
        $no_telp,
        $alamat,
        $tgl_lahir
    ) {

        $this->nama_user = $nama_user;
        $this->username = $username;
        $this->password = $password;
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->tgl_lahir = $tgl_lahir;
    }

    // ======================
    // GET NIP
    // ======================
    public function getNIP()
    {
        return $this->nip;
    }

    // ======================
    // SET NIP
    // ======================
    public function setNIP($nip)
    {
        $this->nip = $nip;
    }

    // ======================
    // REGISTER USER
    // ======================
    public function registerUser($koneksi)
    {
        try {

            // ======================
            // AMBIL NIP TERAKHIR
            // ======================
            $stmt = $koneksi->prepare("
                SELECT 
                    MAX(CAST(SUBSTRING(NIP, 2) AS UNSIGNED)) AS max_nip
                FROM user
            ");

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $max_nip = $row['max_nip'];

            // ======================
            // GENERATE NIP
            // ======================
            if ($max_nip == NULL) {

                $new_nip = 'K0001';

            } else {

                $new_nip =
                    'K' .
                    str_pad($max_nip + 1, 4, '0', STR_PAD_LEFT);
            }

            $this->setNIP($new_nip);

            // ======================
            // AMBIL ID USER TERAKHIR
            // ======================
            $stmt = $koneksi->prepare("
                SELECT 
                    MAX(CAST(SUBSTRING(id_user, 2) AS UNSIGNED)) AS max_id
                FROM user
            ");

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $max_id = $row['max_id'];

            // ======================
            // GENERATE ID USER
            // ======================
            $new_id =
                'U' .
                str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);

            // ======================
            // INSERT USER
            // ======================
            $stmt = $koneksi->prepare("
                INSERT INTO user (
                    id_user,
                    NIP,
                    nama_user,
                    username,
                    password,
                    no_telp,
                    alamat,
                    tgl_lahir,
                    hak
                )
                VALUES (
                    :id_user,
                    :nip,
                    :nama_user,
                    :username,
                    :password,
                    :no_telp,
                    :alamat,
                    :tgl_lahir,
                    :hak
                )
            ");

            return $stmt->execute([

                ':id_user' => $new_id,
                ':nip' => $this->nip,
                ':nama_user' => $this->nama_user,
                ':username' => $this->username,
                ':password' => $this->password,
                ':no_telp' => $this->no_telp,
                ':alamat' => $this->alamat,
                ':tgl_lahir' => $this->tgl_lahir,
                ':hak' => $this->hak
            ]);

        } catch (PDOException $e) {

            die("Terjadi kesalahan database: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Daftar Akun</title>

<link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>

body {
    background-image: url('img/registrasi.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
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

                        <!-- HEADER -->
                        <div class="text-center">

                            <h1 class="h4 text-gray-900 mb-4">

                                <font color="black">

                                    <strong>
                                        Daftar Akun!
                                    </strong>

                                </font>

                            </h1>

                        </div>

                        <!-- NOTIFIKASI -->
                        <?php
                        if (isset($_SESSION['notif'])) {

                            echo '
                            <div class="alert alert-success"
                                 role="alert">

                                 ' . $_SESSION['notif'] . '

                            </div>';

                            unset($_SESSION['notif']);
                        }
                        ?>

                        <!-- FORM -->
                        <form method="POST"
                              action=""
                              class="user">

                            <!-- NAMA -->
                            <div class="form-group">

                                <input type="text"
                                       class="form-control form-control-user"
                                       placeholder="Nama Anda"
                                       name="nama_user"
                                       required>

                            </div>

                            <!-- USERNAME -->
                            <div class="form-group">

                                <input type="text"
                                       class="form-control form-control-user"
                                       placeholder="Username Anda"
                                       name="username"
                                       required>

                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group">

                                <input type="password"
                                       class="form-control form-control-user"
                                       placeholder="Password"
                                       name="password"
                                       required>

                            </div>

                            <!-- TELEPON -->
                            <div class="form-group">

                                <input type="text"
                                       class="form-control form-control-user"
                                       placeholder="No Telepon"
                                       name="no_telp"
                                       required>

                            </div>

                            <!-- ALAMAT -->
                            <div class="form-group">

                                <input type="text"
                                       class="form-control form-control-user"
                                       placeholder="Alamat"
                                       name="alamat"
                                       required>

                            </div>

                            <!-- TGL LAHIR -->
                            <div class="form-group">

                                <input type="date"
                                       class="form-control form-control-user"
                                       name="tgl_lahir"
                                       required>

                            </div>

                            <!-- BUTTON -->
                            <button type="submit"
                                    class="btn btn-user btn-block"
                                    style="background-color:black">

                                <font color="#ffffff">

                                    Daftar

                                </font>

                            </button>

                        </form>

                        <!-- LOGIN -->
                        <div class="text-center">

                            <a class="small"
                               href="form_login.php">

                                Sudah Punya Akun?
                                Silahkan Login!

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

// ======================
// PROSES REGISTER
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $tgl_lahir = $_POST['tgl_lahir'];

    // ======================
    // OBJECT USER
    // ======================
    $user = new User(
        $nama_user,
        $username,
        $password,
        $no_telp,
        $alamat,
        $tgl_lahir
    );

    $result = $user->registerUser($koneksi);

    // ======================
    // BERHASIL
    // ======================
    if ($result) {

        $_SESSION['notif'] =
            "Akun berhasil ditambahkan dengan NIP " .
            $user->getNIP() .
            ". Silakan login.";

        header("Location: registrasi.php");

        exit();

    } else {

        echo "
        <div class='alert alert-danger'>

            Pendaftaran gagal,
            silakan coba lagi!

        </div>
        ";
    }
}
?>

</body>
</html>
