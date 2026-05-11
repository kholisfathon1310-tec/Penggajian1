<?php
session_start();

require_once 'config.php';

// ======================
// CEK NIP
// ======================
if (isset($_POST['NIP'])) {

    $NIP = trim($_POST['NIP']);

    try {

        // ======================
        // QUERY HAPUS STAFF
        // ======================
        $stmt = $koneksi->prepare("
            DELETE FROM user
            WHERE NIP = :nip
        ");

        $result = $stmt->execute([
            ':nip' => $NIP
        ]);

        // ======================
        // BERHASIL
        // ======================
        if ($result) {

            $_SESSION['message'] =
                "Data staff berhasil dihapus.";

            $_SESSION['message_type'] =
                "success";

        } else {

            // ======================
            // GAGAL
            // ======================
            $_SESSION['message'] =
                "Gagal menghapus data staff.";

            $_SESSION['message_type'] =
                "danger";
        }

    } catch (PDOException $e) {

        // ======================
        // ERROR DATABASE
        // ======================
        $_SESSION['message'] =
            "Terjadi kesalahan database: " . $e->getMessage();

        $_SESSION['message_type'] =
            "danger";
    }

} else {

    // ======================
    // NIP TIDAK ADA
    // ======================
    $_SESSION['message'] =
        "Data staff tidak ditemukan.";

    $_SESSION['message_type'] =
        "danger";
}

// ======================
// REDIRECT
// ======================
header("Location: staff.php");
exit;

?>
