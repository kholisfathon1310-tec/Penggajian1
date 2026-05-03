<?php
session_start();
require_once "config.php"; // Koneksi ke database
require_once "template_admin/header.php"; // Header admin
require_once "template_admin/sidebar.php"; // Sidebar admin


// Proses unggah foto absen masuk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_absen'])) {
    $id_absen = $_POST['id_absen'];

    // Periksa apakah file foto absen diunggah
    if (isset($_FILES['foto_absen']) && $_FILES['foto_absen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/absen/'; // Folder tempat menyimpan foto absen
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
        }

        $fileTmpPath = $_FILES['foto_absen']['tmp_name'];
        $fileName = basename($_FILES['foto_absen']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('absen_') . '.' . $fileExtension;
            $uploadFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // Update database dengan path foto absen
                $query = "UPDATE admin_absen SET image = ?, status_kehadiran = 'Hadir' WHERE id_absen = ?";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param('si', $uploadFilePath, $id_absen);
                $stmt->execute();
                $stmt->close();

                echo "<script>alert('Foto absen berhasil diunggah'); window.location.href = 'absensi.php';</script>";
            } else {
                echo "<script>alert('Gagal mengunggah foto'); window.location.href = 'absensi.php';</script>";
            }
        } else {
            echo "<script>alert('Format file tidak didukung (hanya JPG, JPEG, PNG)'); window.location.href = 'absensi.php';</script>";
        }
    } else {
        echo "<script>alert('Harap unggah foto absen'); window.location.href = 'absensi.php';</script>";
    }
}

// Proses unggah foto lembur dan detail lembur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_lembur'])) {
    $id_absen = $_POST['id_absen'];
    $lembur_detail = $_POST['lembur_detail']; // Detil lembur (misal: jam lembur)
    
    // Periksa apakah file foto lembur diunggah
    if (isset($_FILES['foto_lembur']) && $_FILES['foto_lembur']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/lembur/'; // Folder tempat menyimpan foto lembur
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
        }

        $fileTmpPath = $_FILES['foto_lembur']['tmp_name'];
        $fileName = basename($_FILES['foto_lembur']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('lembur_') . '.' . $fileExtension;
            $uploadFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // Update database dengan path foto lembur dan detail lembur
                $query = "UPDATE admin_absen SET foto_lembur = ?, lembur = ? WHERE id_absen = ?";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param('ssi', $uploadFilePath, $lembur_detail, $id_absen);
                $stmt->execute();
                $stmt->close();

                echo "<script>alert('Foto lembur berhasil diunggah'); window.location.href = 'absensi.php';</script>";
            } else {
                echo "<script>alert('Gagal mengunggah foto lembur'); window.location.href = 'absensi.php';</script>";
            }
        } else {
            echo "<script>alert('Format file tidak didukung (hanya JPG, JPEG, PNG)'); window.location.href = 'absensi.php';</script>";
        }
    } else {
        echo "<script>alert('Harap unggah foto lembur'); window.location.href = 'absensi.php';</script>";
    }
}

// Skrip untuk memeriksa absensi yang tidak dilakukan dalam 5 jam dari jam kerja
$currentTime = new DateTime();
$workStartTime = new DateTime('09:00'); // Jam kerja dimulai pukul 09:00

// Cek jika absen masuk tidak terjadi dalam 5 jam dari jam kerja
$sqlCheckAbsen = "SELECT id_absen, jam_masuk FROM admin_absen WHERE status_kehadiran IS NULL";
$resultCheckAbsen = mysqli_query($koneksi, $sqlCheckAbsen);

while ($row = mysqli_fetch_assoc($resultCheckAbsen)) {
    $jamMasuk = new DateTime($row['jam_masuk']);
    $interval = $currentTime->diff($jamMasuk);
    
    // Jika lebih dari 5 jam, status akan otomatis menjadi "Tidak Hadir"
    if ($interval->h >= 5) {
        $updateQuery = "UPDATE admin_absen SET status_kehadiran = 'Tidak Hadir' WHERE id_absen = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param('i', $row['id_absen']);
        $stmt->execute();
        $stmt->close();
    }
}

// Ambil data absensi dari database
$sql = "SELECT * FROM admin_absen";
$result = mysqli_query($koneksi, $sql);

// Kelas untuk menampilkan tabel absensi
class AbsensiContent {
    public static function renderTable($result)
    {
        echo '<div class="content container mt-5">';
        echo '<h4 class="text-center">Data Absensi Karyawan</h4>';
        echo '<div class="table-container" style="max-height: 400px; overflow-y: auto;">';
        echo '<table class="table table-bordered text-center table-striped">';
        echo '<thead class="table-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>NIP</th>';
        echo '<th>Nama</th>';
        echo '<th>Posisi</th>';
        echo '<th>Foto Absen</th>';
        echo '<th>Jam Masuk</th>';
        echo '<th>Jam Keluar</th>';
        echo '<th>Durasi Kerja</th>';
        echo '<th>Status Kehadiran</th>';
        echo '<th>Lembur</th>';
        echo '<th>Foto Lembur</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Format ID absensi ke format AB001, AB002, dst.
                $idAbsenFormatted = 'AB' . str_pad($row['id_absen'], 3, '0', STR_PAD_LEFT);

                echo '<tr>';
                echo '<td>' . htmlspecialchars($idAbsenFormatted) . '</td>';
                echo '<td>' . htmlspecialchars($row['NIP']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_user']) . '</td>';
                echo '<td>' . htmlspecialchars($row['posisi']) . '</td>';
                echo '<td>';
                if (!empty($row['image'])) {
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Foto Absen" class="img-thumbnail" style="width: 60px; height: 60px;">';
                } else {
                    echo '<span class="text-muted">Tidak Ada</span>';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row['jam_masuk']) . '</td>';
                echo '<td>' . htmlspecialchars($row['jam_keluar']) . '</td>';
                echo '<td>' . htmlspecialchars($row['durasi_kerja']) . '</td>';
                echo '<td>';
                $status = $row['status_kehadiran'];
                if ($status === 'Hadir') {
                    echo '<span class="badge bg-success">Hadir</span>';
                } elseif ($status === 'Tidak Hadir') {
                    echo '<span class="badge bg-danger">Tidak Hadir</span>';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row['lembur']) . '</td>';
                echo '<td>';
                if (!empty($row['foto_lembur'])) {
                    echo '<img src="' . htmlspecialchars($row['foto_lembur']) . '" alt="Foto Lembur" class="img-thumbnail" style="width: 60px; height: 60px;">';
                } else {
                    echo '<span class="text-muted">Tidak Ada</span>';
                }
                echo '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
}

// Menampilkan absensi


?>

<!-- Footer -->
<?php require_once "template_admin/footer.php"; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .table td {
            color: #212529;
        }
        .content-wrapper {
            padding-top: 50px;
        }
        .table-container {
            margin-top: 20px;
        }
        .img-thumbnail {
            border-radius: 5px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="content-wrapper" style="min-height: 100vh;">
        <div class="container">
            <?php AbsensiContent::renderTable($result); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
