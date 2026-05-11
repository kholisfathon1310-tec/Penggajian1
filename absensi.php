<?php
ob_start();

session_start();

require_once "config.php";

require_once "template/header.php";
require_once "template/sidebar.php";

// ======================
// CEK LOGIN
// ======================
if (
    !isset($_SESSION['NIP']) ||
    !isset($_SESSION['nama_user'])
) {

    header("Location: form_login.php");

    exit();
}

// ======================
// SESSION USER
// ======================
$nip = $_SESSION['NIP'];

$nama_user = $_SESSION['nama_user'];

// ======================
// KONEKSI DATABASE
// ======================
$conn = $koneksi;

// ======================
// VALIDASI IMAGE
// ======================
function validateImage($file)
{
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    $fileExtension =
        strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

    $maxFileSize = 2 * 1024 * 1024;

    if (!in_array($fileExtension, $allowedExtensions)) {

        return "File harus berupa jpg, jpeg, atau png.";
    }

    if ($file['size'] > $maxFileSize) {

        return "Ukuran file maksimal 2MB.";
    }

    return true;
}

// ======================
// AMBIL POSISI USER
// ======================
$stmtPosisi = $conn->prepare("
    SELECT hak
    FROM user
    WHERE NIP = :nip
");

$stmtPosisi->execute([
    ':nip' => $nip
]);

$posisi = $stmtPosisi->fetchColumn();

// ======================
// ABSEN MASUK
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['absen_masuk'])
) {

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === 0
    ) {

        $validationResult =
            validateImage($_FILES['image']);

        if ($validationResult === true) {

            // ======================
            // BUAT FOLDER UPLOAD
            // ======================
            if (!is_dir('uploads')) {

                mkdir('uploads', 0777, true);
            }

            // ======================
            // PATH FOTO
            // ======================
            $imagePath =
                "uploads/" .
                uniqid() .
                "-" .
                basename($_FILES['image']['name']);

            // ======================
            // UPLOAD FOTO
            // ======================
            if (
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $imagePath
                )
            ) {

                // ======================
                // INSERT ABSEN
                // ======================
                $stmt = $conn->prepare("
                    INSERT INTO admin_absen (
                        NIP,
                        nama_user,
                        posisi,
                        jam_masuk,
                        image,
                        lembur
                    )
                    VALUES (
                        :nip,
                        :nama_user,
                        :posisi,
                        NOW(),
                        :image,
                        'tidak'
                    )
                ");

                $result = $stmt->execute([

                    ':nip' => $nip,

                    ':nama_user' => $nama_user,

                    ':posisi' => $posisi,

                    ':image' => $imagePath
                ]);

                if ($result) {

                    $message =
                        "Absensi masuk berhasil dicatat!";

                } else {

                    $message =
                        "Gagal mencatat absensi masuk.";
                }

            } else {

                $message =
                    "Gagal upload foto.";
            }

        } else {

            $message = $validationResult;
        }

    } else {

        $message =
            "Silakan upload foto absensi.";
    }
}

// ======================
// ABSEN PULANG
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['absen_pulang'])
) {

    $stmt = $conn->prepare("
        UPDATE admin_absen
        SET
            jam_keluar = NOW(),
            durasi_kerja = TIMEDIFF(NOW(), jam_masuk),
            status_kehadiran = 'Hadir'
        WHERE NIP = :nip
        AND jam_keluar IS NULL
    ");

    $result = $stmt->execute([
        ':nip' => $nip
    ]);

    if (
        $result &&
        $stmt->rowCount() > 0
    ) {

        $message =
            "Absensi pulang berhasil dicatat!";

    } else {

        $message =
            "Gagal absensi pulang.";
    }
}

// ======================
// LEMBUR
// ======================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['lembur'])
) {

    if (
        isset($_FILES['lembur_image']) &&
        $_FILES['lembur_image']['error'] === 0
    ) {

        $validationResult =
            validateImage($_FILES['lembur_image']);

        if ($validationResult === true) {

            if (!is_dir('uploads')) {

                mkdir('uploads', 0777, true);
            }

            $lemburImagePath =
                "uploads/" .
                uniqid() .
                "-" .
                basename($_FILES['lembur_image']['name']);

            if (
                move_uploaded_file(
                    $_FILES['lembur_image']['tmp_name'],
                    $lemburImagePath
                )
            ) {

                $stmt = $conn->prepare("
                    UPDATE admin_absen
                    SET
                        lembur = 'iya',
                        jam_lembur = NOW(),
                        foto_lembur = :foto
                    WHERE NIP = :nip
                    AND lembur = 'tidak'
                    AND jam_keluar IS NULL
                ");

                $result = $stmt->execute([

                    ':foto' => $lemburImagePath,

                    ':nip' => $nip
                ]);

                if ($result) {

                    $message =
                        "Lembur berhasil dicatat!";

                } else {

                    $message =
                        "Gagal mencatat lembur.";
                }

            } else {

                $message =
                    "Gagal upload foto lembur.";
            }

        } else {

            $message = $validationResult;
        }

    } else {

        $message =
            "Silakan upload foto lembur.";
    }
}

// ======================
// CEK ABSEN HARI INI
// ======================
$stmt = $conn->prepare("
    SELECT *
    FROM admin_absen
    WHERE NIP = :nip
    AND DATE(jam_masuk) = CURDATE()
    AND jam_keluar IS NULL
");

$stmt->execute([
    ':nip' => $nip
]);

$absen_masuk =
    $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Absensi</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>

<body>

<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow-sm"
         style="max-width:400px;width:100%;">

        <div class="card-header text-center">

            <h4>
                Absensi Karyawan
            </h4>

        </div>

        <div class="card-body">

            <h6 class="text-center">

                Selamat datang,
                <?= htmlspecialchars($nama_user); ?>

                (NIP:
                <?= htmlspecialchars($nip); ?>)

            </h6>

            <?php if (isset($message)): ?>

                <div class="alert alert-info text-center mt-3">

                    <?= htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>

            <div class="text-center mt-4">

                <?php if (!$absen_masuk): ?>

                    <!-- ABSEN MASUK -->
                    <form method="POST"
                          enctype="multipart/form-data">

                        <div class="mb-3">

                            <label class="form-label">

                                Upload Foto Sedang Bekerja

                            </label>

                            <input type="file"
                                   class="form-control"
                                   name="image"
                                   required>

                        </div>

                        <button type="submit"
                                name="absen_masuk"
                                class="btn btn-success w-100">

                            Absen Masuk

                        </button>

                    </form>

                <?php else: ?>

                    <!-- ABSEN PULANG -->
                    <form method="POST">

                        <button type="submit"
                                name="absen_pulang"
                                class="btn btn-danger w-100">

                            Absen Pulang

                        </button>

                    </form>

                    <!-- LEMBUR -->
                    <?php
                    if (
                        $absen_masuk['lembur'] === 'tidak' &&
                        !$absen_masuk['jam_keluar']
                    ):
                    ?>

                        <form method="POST"
                              enctype="multipart/form-data"
                              class="mt-3">

                            <div class="mb-3">

                                <label class="form-label">

                                    Upload Foto Lembur

                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="lembur_image"
                                       required>

                            </div>

                            <button type="submit"
                                    name="lembur"
                                    class="btn btn-warning w-100">

                                Catat Lembur

                            </button>

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

<?php
ob_end_flush();
?>
