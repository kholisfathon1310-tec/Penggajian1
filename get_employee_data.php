<?php

require_once 'config.php';

// ======================
// RESPONSE JSON
// ======================
header('Content-Type: application/json');

// ======================
// CEK NIP
// ======================
if (isset($_GET['NIP'])) {

    $NIP = trim($_GET['NIP']);

    try {

        // ======================
        // QUERY USER
        // ======================
        $stmt = $koneksi->prepare("
            SELECT 
                nama_user,
                hak
            FROM user
            WHERE NIP = :nip
        ");

        $stmt->execute([
            ':nip' => $NIP
        ]);

        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        // ======================
        // DATA DITEMUKAN
        // ======================
        if ($employee) {

            echo json_encode([
                'success' => true,
                'nama_user' => $employee['nama_user'],
                'hak' => $employee['hak']
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan.'
            ]);
        }

    } catch (PDOException $e) {

        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan database.',
            'error' => $e->getMessage()
        ]);
    }

} else {

    echo json_encode([
        'success' => false,
        'message' => 'NIP tidak ditemukan.'
    ]);
}

?>
