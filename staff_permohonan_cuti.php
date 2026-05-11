<?php
session_start();

require_once "config.php";

// ======================
// CEK METHOD
// ======================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ======================
    // AMBIL DATA FORM
    // ======================
    $nip = trim($_POST['NIP']);
    $nama = trim($_POST['nama']);

    $tanggal_mulai =
        trim($_POST['tanggal_mulai']);

    $tanggal_selesai =
        trim($_POST['tanggal_selesai']);

    $jenis_cuti =
        trim($_POST['jenis_cuti']);

    $status =
        trim($_POST['status']);

    // ======================
    // TANGGAL PENGAJUAN
    // ======================
    $tanggal_pengajuan =
        date("Y-m-d");

    try {

        // ======================
        // AMBIL ID CUTI TERAKHIR
        // ======================
        $stmt = $koneksi->prepare("
            SELECT id_cuti
            FROM admin_pengajuan_cuti
            ORDER BY id_cuti DESC
            LIMIT 1
        ");

        $stmt->execute();

        $lastData =
            $stmt->fetch(PDO::FETCH_ASSOC);

        // ======================
        // GENERATE ID CUTI
        // ======================
        if ($lastData) {

            $lastId = $lastData['id_cuti'];

            $number =
                (int) substr($lastId, 2) + 1;

            $id_cuti =
                'CT' .
                str_pad($number, 3, '0', STR_PAD_LEFT);

        } else {

            $id_cuti = 'CT001';
        }

        // ======================
        // INSERT DATA CUTI
        // ======================
        $stmt = $koneksi->prepare("
            INSERT INTO admin_pengajuan_cuti (
                id_cuti,
                NIP,
                nama,
                tanggal_pengajuan,
                tanggal_awal,
                tanggal_akhir,
                jenis_cuti,
                status
            )
            VALUES (
                :id_cuti,
                :nip,
                :nama,
                :tanggal_pengajuan,
                :tanggal_awal,
                :tanggal_akhir,
                :jenis_cuti,
                :status
            )
        ");

        $result = $stmt->execute([

            ':id_cuti' => $id_cuti,
            ':nip' => $nip,
            ':nama' => $nama,
            ':tanggal_pengajuan' => $tanggal_pengajuan,
            ':tanggal_awal' => $tanggal_mulai,
            ':tanggal_akhir' => $tanggal_selesai,
            ':jenis_cuti' => $jenis_cuti,
            ':status' => $status
        ]);

        // ======================
        // BERHASIL
        // ======================
        if ($result) {

            $_SESSION['message'] =
                "Data permohonan cuti berhasil ditambahkan.";

            $_SESSION['message_type'] =
                "success";

            header("Location: staff_permohonan_cuti.php");

            exit();

        } else {

            $_SESSION['message'] =
                "Gagal menambahkan data cuti.";

            $_SESSION['message_type'] =
                "danger";

            header("Location: staff_permohonan_cuti.php");

            exit();
        }

    } catch (PDOException $e) {

        $_SESSION['message'] =
            "Terjadi kesalahan database: " .
            $e->getMessage();

        $_SESSION['message_type'] =
            "danger";

        header("Location: staff_permohonan_cuti.php");

        exit();
    }

} else {

    // ======================
    // AKSES TIDAK VALID
    // ======================
    header("Location: staff_form_cuti.php");
    exit();
}

?>
