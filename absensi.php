<?php
ob_start();
session_start();

require_once "config.php";
require_once "template/header.php";
require_once "template/sidebar.php";


// Pastikan user sudah login
if (!isset($_SESSION['NIP']) || !isset($_SESSION['nama_user'])) {
    header("Location: form_login.php");
    exit();
}

// Ambil NIP dan nama dari session
$nip = $_SESSION['NIP'];
$nama_user = $_SESSION['nama_user'];

// Sambungkan ke database
try {
    $conn = new PDO("mysql:host=localhost;dbname=penggajian", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Fungsi untuk memvalidasi file gambar
function validateImage($file) {
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($fileExtension, $allowedExtensions)) {
        return "File harus berupa gambar (jpg, jpeg, png).";
    }
    if ($file['size'] > $maxFileSize) {
        return "Ukuran file tidak boleh lebih dari 2MB.";
    }
    return true;
}

// Ambil posisi pengguna dari database
$queryPosisi = "SELECT hak FROM user WHERE NIP = :nip"; // Pastikan nama tabel dan kolom sesuai
$stmtPosisi = $conn->prepare($queryPosisi);
$stmtPosisi->bindParam(":nip", $nip);
$stmtPosisi->execute();
$posisi = $stmtPosisi->fetchColumn(); // Mengambil posisi pengguna

// Fungsi untuk mencatat absensi masuk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absen_masuk'])) {
    if (isset($_FILES['image'])) {
        $validationResult = validateImage($_FILES['image']);
        if ($validationResult === true) {
            $imagePath = "uploads/" . uniqid() . "-" . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $query = "INSERT INTO admin_absen (NIP, nama_user, posisi, jam_masuk, image, lembur) 
                          VALUES (:nip, :nama_user, :posisi, NOW(), :image, 'tidak')";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":nip", $nip);
                $stmt->bindParam(":nama_user", $nama_user);
                $stmt->bindParam(":posisi", $posisi); // Gunakan posisi yang diambil dari database
                $stmt->bindParam(":image", $imagePath);

                if ($stmt->execute()) {
                    $message = "Absensi masuk berhasil dicatat!";
                } else {
                    $message = "Gagal mencatat absensi masuk.";
                }
            } else {
                $message = "Gagal mengunggah foto.";
            }
        } else {
            $message = $validationResult;
        }
    } else {
        $message = "Silakan unggah foto untuk absensi masuk.";
    }
}

// Fungsi untuk mencatat absensi pulang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absen_pulang'])) {
    $query = "UPDATE admin_absen 
              SET jam_keluar = NOW(), 
                  durasi_kerja = TIMEDIFF(NOW(), jam_masuk),
                  status_kehadiran = 'Hadir' 
              WHERE NIP = :nip AND jam_keluar IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nip", $nip);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $message = "Absensi pulang berhasil dicatat!";
    } else {
        $message = "Gagal mencatat absensi pulang. Pastikan Anda sudah absen masuk.";
    }
}

// Fungsi untuk mencatat lembur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lembur'])) {
    if (isset($_FILES['lembur_image'])) {
        $validationResult = validateImage($_FILES['lembur_image']);
        if ($validationResult === true) {
            $lemburImagePath = "uploads/" . uniqid() . "-" . basename($_FILES['lembur_image']['name']);
            if (move_uploaded_file($_FILES['lembur_image']['tmp_name'], $lemburImagePath)) {
                $query = "UPDATE admin_absen 
                          SET lembur = 'iya', jam_lembur = NOW(), foto_lembur = :foto_lembur 
                          WHERE NIP = :nip AND lembur = 'tidak' AND jam_keluar IS NULL";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":foto_lembur", $lemburImagePath);
                $stmt->bindParam(":nip", $nip);

                if ($stmt->execute()) {
                    $message = "Lembur berhasil dicatat!";
                } else {
                    $message = "Gagal mencatat lembur.";
                }
            } else {
                $message = "Gagal mengunggah foto lembur.";
            }
        } else {
            $message = $validationResult;
        }
    } else {
        $message = "Silakan unggah foto untuk mencatat lembur.";
    }
}

// Periksa apakah user sudah absen masuk hari ini
$query = "SELECT * FROM admin_absen WHERE NIP = :nip AND DATE(jam_masuk) = CURDATE() AND jam_keluar IS NULL";
$stmt = $conn->prepare($query);
$stmt->bindParam(":nip", $nip);
$stmt->execute();
$absen_masuk = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-header text-center">
            <h4>Absensi Karyawan</h4>
        </div>
        <div class="card-body">
            <h6 class="text-center">Selamat datang, <?= htmlspecialchars($nama_user) ?> (NIP: <?= htmlspecialchars($nip) ?>)</h6>

            <?php if (isset($message)): ?>
                <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <?php if (!$absen_masuk): ?>
                    <!-- Form Absen Masuk -->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">Unggah Foto Sedang bekerja dikantor</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" name="absen_masuk" class="btn btn-success w-100">Absen Masuk</button>
                    </form>
                <?php else: ?>
                    <!-- Form Absen Pulang -->
                    <form method="POST">
                        <button type="submit" name="absen_pulang" class="btn btn-danger w-100">Absen Pulang</button>
                    </form>

                    <!-- Form Absen Lembur -->
                    <?php if ($absen_masuk['lembur'] === 'tidak' && !$absen_masuk['jam_keluar']): ?>
                        <form method="POST" enctype="multipart/form-data" class="mt-3">
                            <div class="mb-3">
                                <label for="lembur_image" class="form-label">Unggah Foto Lembur</label>
                                <input type="file" class="form-control" id="lembur_image" name="lembur_image" required>
                            </div>
                            <button type="submit" name="lembur" class="btn btn-warning w-100">Catat Lembur</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
