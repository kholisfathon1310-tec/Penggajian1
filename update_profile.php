<?php
session_start();

require_once "config.php";

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
// PROSES UPDATE
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_user =
        htmlspecialchars(trim($_POST['nama_user']));

    $tgl_lahir =
        htmlspecialchars(trim($_POST['tgl_lahir']));

    $no_telp =
        htmlspecialchars(trim($_POST['no_telp']));

    $alamat =
        htmlspecialchars(trim($_POST['alamat']));

    try {

        // ======================
        // UPDATE PROFILE
        // ======================
        $stmt = $koneksi->prepare("
            UPDATE user
            SET
                nama_user = :nama_user,
                tgl_lahir = :tgl_lahir,
                no_telp = :no_telp,
                alamat = :alamat
            WHERE NIP = :nip
        ");

        $result = $stmt->execute([

            ':nama_user' => $nama_user,

            ':tgl_lahir' => $tgl_lahir,

            ':no_telp' => $no_telp,

            ':alamat' => $alamat,

            ':nip' => $nip
        ]);

        // ======================
        // BERHASIL
        // ======================
        if ($result) {

            $_SESSION['nama_user'] =
                $nama_user;

            echo "
            <script>

                alert('Profil berhasil diperbarui!');

                window.location.href='profile.php';

            </script>
            ";

            exit();

        } else {

            echo "
            <script>

                alert('Gagal memperbarui profil!');

                window.location.href='profile.php';

            </script>
            ";
        }

    } catch (PDOException $e) {

        die(
            'Terjadi kesalahan database: '
            . $e->getMessage()
        );
    }
}
?>