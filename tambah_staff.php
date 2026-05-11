<?php
session_start();

require_once "config.php";

// ======================
// CEK METHOD
// ======================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ======================
    // AMBIL ID USER TERAKHIR
    // ======================
    try {

        $stmt = $koneksi->prepare("
            SELECT MAX(id_user) AS max_id
            FROM user
        ");

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $max_id =
            isset($row['max_id'])
            ? (int) substr($row['max_id'], 1)
            : 0;

        // ======================
        // GENERATE ID USER
        // ======================
        $id_user =
            'U' .
            str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);

        // ======================
        // AMBIL DATA FORM
        // ======================
        $NIP = trim($_POST['NIP']);

        $nama_user =
            trim($_POST['nama_user']);

        $tgl_lahir =
            trim($_POST['tgl_lahir']);

        $alamat =
            trim($_POST['alamat']);

        $no_telp =
            trim($_POST['no_telp']);

        $hak =
            trim($_POST['hak']);

        // ======================
        // INSERT DATA
        // ======================
        $stmt = $koneksi->prepare("
            INSERT INTO user (
                id_user,
                NIP,
                nama_user,
                tgl_lahir,
                alamat,
                no_telp,
                hak
            )
            VALUES (
                :id_user,
                :nip,
                :nama_user,
                :tgl_lahir,
                :alamat,
                :no_telp,
                :hak
            )
        ");

        $result = $stmt->execute([

            ':id_user' => $id_user,
            ':nip' => $NIP,
            ':nama_user' => $nama_user,
            ':tgl_lahir' => $tgl_lahir,
            ':alamat' => $alamat,
            ':no_telp' => $no_telp,
            ':hak' => $hak
        ]);

        // ======================
        // BERHASIL
        // ======================
        if ($result) {

            $_SESSION['message'] =
                "Data staff berhasil ditambahkan.";

            $_SESSION['message_type'] =
                "success";

            header("Location: staff.php");

            exit();

        } else {

            $_SESSION['message'] =
                "Gagal menambahkan data staff.";

            $_SESSION['message_type'] =
                "danger";

            header("Location: staff.php");

            exit();
        }

    } catch (PDOException $e) {

        $_SESSION['message'] =
            "Terjadi kesalahan database: " .
            $e->getMessage();

        $_SESSION['message_type'] =
            "danger";

        header("Location: staff.php");

        exit();
    }

} else {

    // ======================
    // AKSES TIDAK VALID
    // ======================
    header("Location: staff.php");
    exit();
}

?>
