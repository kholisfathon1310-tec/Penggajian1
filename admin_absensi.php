<?php
session_start();

require_once 'config.php';

require_once "template_admin/header.php";
require_once "template_admin/sidebar.php";

// ======================
// KONEKSI DATABASE
// ======================
$conn = $koneksi;

// ======================
// VALIDASI FILE GAMBAR
// ======================
function validateImage($file)
{
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    $fileExtension = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );

    if (!in_array($fileExtension, $allowedExtensions)) {

        return false;
    }

    return true;
}

// ======================
// UPLOAD FOTO ABSEN
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['upload_absen'])
) {

    $id_absen = $_POST['id_absen'];

    if (
        isset($_FILES['foto_absen']) &&
        $_FILES['foto_absen']['error'] === 0
    ) {

        if (!is_dir('uploads/absen')) {

            mkdir('uploads/absen', 0777, true);
        }

        if (validateImage($_FILES['foto_absen'])) {

            $extension = strtolower(
                pathinfo(
                    $_FILES['foto_absen']['name'],
                    PATHINFO_EXTENSION
                )
            );

            $newFileName =
                uniqid('absen_') .
                '.' .
                $extension;

            $uploadPath =
                'uploads/absen/' .
                $newFileName;

            if (
                move_uploaded_file(
                    $_FILES['foto_absen']['tmp_name'],
                    $uploadPath
                )
            ) {

                $stmt = $conn->prepare("
                    UPDATE admin_absen
                    SET
                        image = :image,
                        status_kehadiran = 'Hadir'
                    WHERE id_absen = :id
                ");

                $result = $stmt->execute([

                    ':image' => $uploadPath,

                    ':id' => $id_absen
                ]);

                if ($result) {

                    echo "
                    <script>
                        alert('Foto absen berhasil diunggah');
                        window.location.href='absensi.php';
                    </script>
                    ";

                    exit;
                }
            }
        }

        echo "
        <script>
            alert('Format file tidak didukung');
            window.location.href='absensi.php';
        </script>
        ";

        exit;
    }

    echo "
    <script>
        alert('Harap unggah foto absen');
        window.location.href='absensi.php';
    </script>
    ";

    exit;
}

// ======================
// UPLOAD FOTO LEMBUR
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['upload_lembur'])
) {

    $id_absen = $_POST['id_absen'];

    $lembur_detail = $_POST['lembur_detail'];

    if (
        isset($_FILES['foto_lembur']) &&
        $_FILES['foto_lembur']['error'] === 0
    ) {

        if (!is_dir('uploads/lembur')) {

            mkdir('uploads/lembur', 0777, true);
        }

        if (validateImage($_FILES['foto_lembur'])) {

            $extension = strtolower(
                pathinfo(
                    $_FILES['foto_lembur']['name'],
                    PATHINFO_EXTENSION
                )
            );

            $newFileName =
                uniqid('lembur_') .
                '.' .
                $extension;

            $uploadPath =
                'uploads/lembur/' .
                $newFileName;

            if (
                move_uploaded_file(
                    $_FILES['foto_lembur']['tmp_name'],
                    $uploadPath
                )
            ) {

                $stmt = $conn->prepare("
                    UPDATE admin_absen
                    SET
                        foto_lembur = :foto,
                        lembur = :lembur
                    WHERE id_absen = :id
                ");

                $result = $stmt->execute([

                    ':foto' => $uploadPath,

                    ':lembur' => $lembur_detail,

                    ':id' => $id_absen
                ]);

                if ($result) {

                    echo "
                    <script>
                        alert('Foto lembur berhasil diunggah');
                        window.location.href='absensi.php';
                    </script>
                    ";

                    exit;
                }
            }
        }

        echo "
        <script>
            alert('Format file tidak didukung');
            window.location.href='absensi.php';
        </script>
        ";

        exit;
    }

    echo "
    <script>
        alert('Harap unggah foto lembur');
        window.location.href='absensi.php';
    </script>
    ";

    exit;
}

// ======================
// AUTO TIDAK HADIR
// ======================
$currentTime = new DateTime();

$stmt = $conn->prepare("
    SELECT
        id_absen,
        jam_masuk
    FROM admin_absen
    WHERE status_kehadiran IS NULL
");

$stmt->execute();

$dataAbsen =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataAbsen as $row) {

    $jamMasuk =
        new DateTime($row['jam_masuk']);

    $interval =
        $currentTime->diff($jamMasuk);

    if ($interval->h >= 5) {

        $update = $conn->prepare("
            UPDATE admin_absen
            SET status_kehadiran = 'Tidak Hadir'
            WHERE id_absen = :id
        ");

        $update->execute([
            ':id' => $row['id_absen']
        ]);
    }
}

// ======================
// AMBIL DATA ABSENSI
// ======================
$stmt = $conn->prepare("
    SELECT *
    FROM admin_absen
    ORDER BY id_absen DESC
");

$stmt->execute();

$result =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

// ======================
// CLASS TABEL
// ======================
class AbsensiContent
{
    public static function renderTable($result)
    {
        echo '
        <div class="content container mt-5">

            <h4 class="text-center">
                Data Absensi Karyawan
            </h4>

            <div class="table-container"
                 style="max-height:400px;overflow-y:auto;">

                <table class="table table-bordered text-center table-striped">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Foto Absen</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Durasi Kerja</th>
                            <th>Status Kehadiran</th>
                            <th>Lembur</th>
                            <th>Foto Lembur</th>

                        </tr>

                    </thead>

                    <tbody>
        ';

        if ($result) {

            foreach ($result as $row) {

                $idAbsenFormatted =
                    'AB' .
                    str_pad(
                        $row['id_absen'],
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

                echo '
                <tr>

                    <td>' . htmlspecialchars($idAbsenFormatted) . '</td>

                    <td>' . htmlspecialchars($row['NIP']) . '</td>

                    <td>' . htmlspecialchars($row['nama_user']) . '</td>

                    <td>' . htmlspecialchars($row['posisi']) . '</td>

                    <td>
                ';

                if (!empty($row['image'])) {

                    echo '
                    <img src="' .
                    htmlspecialchars($row['image']) .
                    '"
                    class="img-thumbnail"
                    style="width:60px;height:60px;">
                    ';

                } else {

                    echo '
                    <span class="text-muted">
                        Tidak Ada
                    </span>
                    ';
                }

                echo '
                    </td>

                    <td>' . htmlspecialchars($row['jam_masuk']) . '</td>

                    <td>' . htmlspecialchars($row['jam_keluar']) . '</td>

                    <td>' . htmlspecialchars($row['durasi_kerja']) . '</td>

                    <td>
                ';

                if ($row['status_kehadiran'] === 'Hadir') {

                    echo '
                    <span class="badge bg-success">
                        Hadir
                    </span>
                    ';

                } elseif ($row['status_kehadiran'] === 'Tidak Hadir') {

                    echo '
                    <span class="badge bg-danger">
                        Tidak Hadir
                    </span>
                    ';

                } else {

                    echo '
                    <span class="badge bg-secondary">
                        Pending
                    </span>
                    ';
                }

                echo '
                    </td>

                    <td>' . htmlspecialchars($row['lembur']) . '</td>

                    <td>
                ';

                if (!empty($row['foto_lembur'])) {

                    echo '
                    <img src="' .
                    htmlspecialchars($row['foto_lembur']) .
                    '"
                    class="img-thumbnail"
                    style="width:60px;height:60px;">
                    ';

                } else {

                    echo '
                    <span class="text-muted">
                        Tidak Ada
                    </span>
                    ';
                }

                echo '
                    </td>

                </tr>
                ';
            }

        } else {

            echo '
            <tr>

                <td colspan="11">
                    Tidak ada data absensi
                </td>

            </tr>
            ';
        }

        echo '
                    </tbody>

                </table>

            </div>

        </div>
        ';
    }
}
?>

<?php require_once "template_admin/footer.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Data Absensi Karyawan
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet">

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

<div class="content-wrapper"
     style="min-height:100vh;">

    <div class="container">

        <?php
        AbsensiContent::renderTable($result);
        ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
