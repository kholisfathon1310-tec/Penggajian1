<?php
session_start();

require_once 'config.php';
require_once 'auth.php';

// ======================
// CEK METHOD
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ======================
    // AMBIL INPUT
    // ======================
    $NIP = trim($_POST['NIP']);
    $password = trim($_POST['password']);

    // ======================
    // VALIDASI INPUT
    // ======================
    if (empty($NIP) || empty($password)) {

        echo "
        <script>
            alert('NIP dan Password wajib diisi!');
            window.location.assign('form_login.php');
        </script>
        ";

        exit;
    }

    // ======================
    // KONEKSI DATABASE
    // ======================
    $db = new Database();
    $koneksi = $db->getConnection();

    // ======================
    // LOGIN AUTH
    // ======================
    $auth = new Auth($koneksi, $NIP, $password);

    $user = $auth->login();

    // ======================
    // LOGIN BERHASIL
    // ======================
    if ($user) {

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['NIP'] = $user['NIP'];
        $_SESSION['nama_user'] = $user['nama_user'];
        $_SESSION['hak'] = $user['hak'];

        // ======================
        // REDIRECT ROLE
        // ======================
        if ($user['hak'] === 'karyawan') {

            header('Location: grafik_karyawan.php');
            exit;

        } elseif ($user['hak'] === 'admin') {

            header('Location: grafik_absensi.php');
            exit;

        } else {

            echo "
            <script>
                alert('Role user tidak dikenali!');
                window.location.assign('form_login.php');
            </script>
            ";

            exit;
        }

    } else {

        // ======================
        // LOGIN GAGAL
        // ======================
        echo "
        <script>
            alert('NIP atau password salah!');
            window.location.assign('form_login.php');
        </script>
        ";

        exit;
    }

} else {

    // ======================
    // AKSES LANGSUNG
    // ======================
    echo "
    <script>
        alert('Akses tidak diizinkan!');
        window.location.assign('form_login.php');
    </script>
    ";

    exit;
}
?>
