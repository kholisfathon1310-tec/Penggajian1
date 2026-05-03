<?php
require_once 'Database.php';

// Mendapatkan NIP dari query string
if (isset($_GET['NIP'])) {
    $NIP = $_GET['NIP'];

    // Koneksi ke database
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        // Query untuk mengambil data karyawan berdasarkan NIP
        $query = "SELECT nama_user, hak FROM user WHERE NIP = :NIP";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':NIP', $NIP);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Mengambil data karyawan
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            // Mengembalikan data dalam format JSON
            echo json_encode(['success' => true, 'nama_user' => $employee['nama_user'], 'hak' => $employee['hak']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data karyawan tidak ditemukan.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Koneksi ke database gagal.']);
    }
}
?>
